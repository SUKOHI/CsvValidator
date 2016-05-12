<?php

abstract class ExcelFileExt extends \Maatwebsite\Excel\Files\ExcelFile {

	protected $delimiter  = ',';
	protected $enclosure  = '"';
	protected $lineEnding = '\r\n';

}