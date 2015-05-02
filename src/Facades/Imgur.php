<?php namespace Redeman\Imgur\Facades;

use Illuminate\Support\Facades\Facade;

class Imgur extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Imgur\Client';
    }

}