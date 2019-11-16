# CsvValidator
A Laravel package that allows to validate csv data.  
This package is maintained under Laravel 5.7.  

# Installation

Run this command.

    composer require sukohi/csv-validator:3.*
    
# Basic usage

You can use a rule called `Csv` as usual validation.  
And all of the validation rules of Laravel is available for `$csv_rules` as follows.

    <?php
    
    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Sukohi\CsvValidator\Rules\Csv;
    
    class CsvValidatorController extends Controller
    {
        public function store(Request $request) {
    
            $csv_rules = [
                0 => 'required',
                1 => 'integer',
                2 => 'required|min:10'
            ];
    
            $request->validate([
                'users_csv' => [
                    new Csv($csv_rules) // <- here
                ]
            ]);
    
            // Do something..
    
        }
    }

# Encoding

You can set a specific encoding to convert csv data.

    $csv_rules = [
        0 => 'required',
        1 => 'integer',
        2 => 'required|min:10'
    ];
    $from_encoding = 'sjis-win';

    $request->validate([
        'csv_file' => [
            new Csv($csv_rules, $from_encoding)
        ]
    ]);

# Csv rules

You can skip column like this.

    $csv_rules = [
        0 => 'required',
        3 => 'required|min:10'
    ];

    $request->validate([
        'csv_file' => [
            new Csv($csv_rules)
        ]
    ]);

# Messages

Attributes of error messages are like this.

    The A1 field is required.
    The C1 field is required.
    The C2 must be at least 10 characters.
    The C3 must be at least 10 characters.
    The C4 field is required.

# License

This package is licensed under the MIT License.

Copyright 2019 Sukohi Kuhoh
