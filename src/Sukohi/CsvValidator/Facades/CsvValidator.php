<?php namespace Sukohi\CsvValidator\Facades;

use Illuminate\Support\Facades\Facade;

class CsvValidator extends Facade {

	protected static function getFacadeAccessor() { return 'csv-validator'; }

}