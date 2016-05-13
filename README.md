# CsvValidator
A Laravel package to validate csv data.
(This is for Laravel 4.2. [For Laravel 5](https://github.com/SUKOHI/CsvValidator))

# Requirements

* "maatwebsite/excel": "~1.3.0"

# Installation

Execute composer command.

    composer require sukohi/csv-validator:1.*

Register the service provider in app.php

    'providers' => [
        ...Others...,  
        'Maatwebsite\Excel\ExcelServiceProvider', 
        'Sukohi\CsvValidator\CsvValidatorServiceProvider',
    ]

Also alias

    'aliases' => [
        ...Others...,  
        'Excel' => 'Maatwebsite\Excel\Facades\Excel',
        'CsvValidator' => 'Sukohi\CsvValidator\Facades\CsvValidator',
    ]
    
# Basic usage

    $csv_path = 'test.csv';
    $rules = [
        0 => 'required',
        1 => 'required|integer',
        2 => 'required|min:4'
    ];
    $csv_validator = CsvValidator::make($csv_path, $rules);
    
    if($csv_validator->fails()) {
    
        $errors = $csv_validator->getErrors();

    } else {

        $csv_data = $csv_validator->getData();

    }
    

# Rules

You can set keys instead of indexes like so.

    $rules = [
        'Product Title' => 'required',
        'Product No.' => 'required',
        'Position' => 'required'
    ];

In this case, the first row of the CSV need to have `Product Title`, `Product No.` and `Position`.  
And This keys will be used as attribute names for error message.

* [See](https://laravel.com/docs/4.2/validation#available-validation-rules) the details of the rules. 

# Attribute names for error message

If you set indexes for rules like so.

    $rules = [
        0 => 'required',
        1 => 'required|integer',
        2 => 'required|min:4'
    ];
    
You can set attribute names before calling fails() like this.

    $csv_validator->setAttributeNames([
        0 => 'Product Title',
        1 => 'Product No.',
        2 => 'Position'
    ]);

# Encoding

You can set a specific encoding as the 3rd argument.(Default: UTF-8)

    CsvValidator::make($csv_path, $rules, 'SJIS-win');

# Error messages

You can get error messages after calling fails().

    $errors = $csv_validator->getErrors();
    
    foreach ($errors as $row_index => $error) {
    
        foreach ($error as $col_index => $messages) {
    
            echo 'Row '. $row_index .', Col '.$col_index .': '. implode(',', $messages) .'<br>';
    
        }
    
    }

# CSV data

You also can get CSV data after calling fails().

    $csv_data = $csv_validator->getData();

# Exception

In the case of the below, you must receive Exception.

* Your CSV does not have any data.
* Heading key not found.

e.g)

    try {

        $csv_validator = CsvValidator::make($csv_path, $rules, $encoding);

        if($csv_validator->fails()) {

            // Do something..

        }

    } catch (\Exception $e) {

        echo $e->getMessage();

    }

# CSV Settings

You can set delimiter, enclosure and line_ending through `csv.php` that Laravel Excel can publish by executing the following command.

    php artisan config:publish maatwebsite/excel

See [here](http://www.maatwebsite.nl/laravel-excel/docs/getting-started) for the details.

# License

This package is licensed under the LGPL License(following Laravel Excel).

Copyright 2016 Sukohi Kuhoh