<?php

namespace Xup\Core\Console\Commands\Sde;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Xup\Core\Models\Sde\MapDenormalize;
use Xup\Core\Settings\Settings;
use Xup\Core\Settings\Xup;
use function Illuminate\Events\queueable;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xup:sde:update 
                            {--local : Check the local config file for the version}
                            {--force : Force the re-installation}';

    protected $json;

    protected $storage_path;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the SDE Information.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Xup\Core\Exceptions\SettingsException
     */
    public function handle()
    {


        //Test DB connection
        DB::connection()->getDatabaseName();

        if(! $this->confirm("Are you sure you want to update to the latest EVE SDE?", true))
        {
            $this->warn("exiting");
            return;
        }

        $this->json = $this->getJson();

        if(! $this->json)
        {
            $this->warn('unable to load required Sde');
            return;
        }

        if($this->option('local')){
            $version_number = env('SDE_VERSION', null);

            if(! is_null($version_number))
            {
                $this->comment("Using environment defined version number");
                $this->json->version = $version_number;
            }else{
                $this->warn('No SDE version supplied with environment. USing default: '.$this->json->version);
            }
        }

        if($this->json->version == Xup::get('sde_version') && $this->option('force') == false)
        {
            $this->warn("Already running the latest compatible SDE");
            $this->warn("If you need to reinstall, use force");

            return;
        }

        if($this->option('force') == true){
            $this->warn("This will re download the current SDE. Are you sure?");

            if(! $this->confirm("Are you sure? ", true)){
                $this->info("Nothing has been updated");
                return;
            }
        }

        $extra_tables = config('sead.sde.tables', []);

        $this->json->tables = array_unique(array_merge($this->json->tables, $extra_tables));
        sort($this->json->tables);

        if(!$this->isStorageOk()){
            $this->error('cannot write to storage folder');
            return;
        }

        $this->getSde();

        $this->importSde();

        $this->explodeMap();

        //Xup::set('sde_version', $this->json->version);

        return 0;
    }

    public function getJson()
    {
        $result = Http::withHeaders(['Accept' => 'application/json'])->get('https://raw.githubusercontent.com/eve-xup/sde/master/sde.json');
        //dd($result);
        if($result->status() != 200)
            return json_encode([]);

        return json_decode($result->body());
    }

    public function isStorageOk()
    {
        $storage = storage_path().'/sde/'.$this->json->version.'/';

        if(File::isWritable(storage_path())){
            if(!File::exists($storage))
                File::makeDirectory($storage, 0755, true);

            $this->storage_path = $storage;

            return true;
        }

        return false;
    }

    public function getProgressBar($iterations)
    {
        $bar = $this->output->createProgressBar($iterations);

        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s% %memory:6s%');

        return $bar;
    }

    private function getSde(){
        $this->line('Downloading...');
        $bar = $this->getProgressBar(count($this->json->tables));

        foreach($this->json->tables as $table){

            $url = str_replace(':version', $this->json->version, $this->json->url).
                $table.$this->json->format;
            $destination = $this->storage_path .$table . $this->json->format;


            $file_handler = fopen($destination, 'w');

            $result = Http::sink($file_handler)->get($url);

            fclose($file_handler);

            if($result->status() != 200)
                $this->error('Unable to download '.$table);

            $bar->advance();

        }
        $bar->finish();
        $this->line('');
    }

    private function importSde(){
        $this->line('Importing...');
        $bar = $this->getProgressBar(count($this->json->tables));

        foreach($this->json->tables as $table){
            $archive_path = $this->storage_path .$table.$this->json->format;
            $extracted_path = $this->storage_path . $table.'.sql';
            if(!File::exists($archive_path)){
                $this->warn($archive_path.' does not exist');
                continue;
            }

            $input_file = bzopen($archive_path, 'r');
            $output_file = fopen($extracted_path, 'w');

            while($chunk = bzread($input_file, 4096))
                fwrite($output_file, $chunk, 4096);

            bzclose($input_file);
            fclose($output_file);

            $import_command = 'mysql -u ' . config('database.connections.mysql.username') .
                (strlen(config('database.connections.mysql.password')) ? ' -p' : '') .
                escapeshellcmd(config('database.connections.mysql.password')) .
                ' -h ' . config('database.connections.mysql.host') .
                ' -P ' . config('database.connections.mysql.port') .
                ' ' . config('database.connections.mysql.database') .
                ' < ' . $extracted_path;

            exec($import_command, $output, $exit_code);

            if($exit_code !== 0)
                $this->error(spintf("Input failed with exit code %d and output %s", $exit_code, $output));

            $bar->advance();


        }

        $bar->finish();
        $this->line('');

    }

    private function explodeMap(){
        DB::table('regions')->truncate();
        DB::table('regions')
            ->insertUsing(
                ['region_id', 'name'],
                DB::table('mapDenormalize')->where('groupID', MapDenormalize::REGION)
                ->select('itemID', 'itemName'));

        DB::table('constellations')->truncate();
        DB::table('constellations')
            ->insertUsing(
                ['constellation_id', 'region_id', 'name'],
                DB::table('mapDenormalize')->where('groupID', MapDenormalize::CONSTELLATION)
                ->select('itemID', 'regionID', 'itemName')
            );

        DB::table('solar_systems')->truncate();
        DB::table('solar_systems')
            ->insertUsing(
                ['system_id', 'constellation_id', 'region_id', 'name', 'security'],
                DB::table('mapDenormalize')->where('groupID', MapDenormalize::SYSTEM)
                ->select('itemID', 'constellationID', 'regionID', 'itemName', 'security')
            );

        DB::table('planets')->truncate();
        DB::table('planets')
            ->insertUsing(
                ['planet_id', 'system_id', 'constellation_id', 'region_id', 'name', 'type_id',
                    'x', 'y', 'z', 'radius', 'celestial_index'],
                DB::table('mapDenormalize')->where('groupID', MapDenormalize::PLANET)
                ->select('itemID', 'solarSystemID', 'constellationID', 'regionID', 'itemName', 'typeID',
                'x', 'y', 'z', 'radius', 'celestialIndex')
            );

        DB::table('moons')->truncate();
        DB::table('moons')
            ->insertUsing(
                ['moon_id', 'planet_id', 'system_id', 'constellation_id', 'region_id', 'name', 'type_id',
                    'x', 'y', 'z', 'radius', 'celestialIndex', 'orbit_index'],
                DB::table('mapDenormalize')->where('groupID', MapDenormalize::MOON)
                    ->select('itemID', 'orbitID', 'solarSystemID', 'constellationID', 'regionID', 'itemName', 'typeID',
                    'x', 'y', 'x', 'radius', 'celestialIndex', 'orbitIndex')
            );
    }





}