<?php

namespace Xup\Core\Traits;

use Xup\Core\Exceptions\ReadOnlyModelException;

trait IsReadOnly
{

    public static function create(array $attributes = [])
    {
        throw  new ReadOnlyModelException;
    }

    public static function firstOrCreate(array $arr)
    {
        throw new ReadOnlyModelException;
    }

    public function save(array $options = [])
    {
        throw new ReadOnlyModelException;
    }

    public function update(array $attributes = [], array $options = [])
    {
        throw new ReadOnlyModelException;
    }

    public function delete(){
        throw new ReadOnlyModelException;
    }

    public function forceDelete()
    {
        throw new ReadOnlyModelException;
    }


}