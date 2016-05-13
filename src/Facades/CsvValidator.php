<?php namespace Sukohi\CsvValidator\Facades;

use Illuminate\Support\Facades\Facade;

class CsvValidator extends Facade {

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'csv-validator'; }

}