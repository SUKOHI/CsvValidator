# CsvValidator
A Laravel package that allows you to validate csv data.  
This package is compatible with Laravel 5.7 -> 8.X  

# Installation

Run this command.

    composer require sukohi/csv-validator:3.*
    
# Basic usage

You can use a rule called `Csv` as usual validation.  
And all the validation rules of Laravel is available for `$csv_rules` as follows.

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

# with Options

    $csv_rules = [
        0 => 'required',
        1 => 'integer',
        2 => 'required|min:10'
    ];
    $options = [
        'encoding' => 'sjsin-win',
        'start_row' => 1,   // <- Starting validation from row one, not zero. (*1)
        'end_row' => 4,     // <- Ending validation
        'row_callback' => function($row_number, $row_data) {

            return true;    // `false` means skipping validation

        }
    ];
    $request->validate([
        'csv_file' => [
            new Csv($csv_rules, $options)
        ]
    ]);

(*1) For example, if you don't want to validate the header row, it means the first row, set `2` here.

***Note***: The former coding is also available.

    new Csv($csv_rules, 'sjis-win')

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

# Retrieve

You can access csv data by adding `_data` after attribute of csv file as follows.

    $csv_data = $request->csv_file_data;    // when attribute is `csv_file`

# Messages

Attributes of error messages are like this.

    The A1 field is required.
    The C1 field is required.
    The C2 must be at least 10 characters.
    The C3 must be at least 10 characters.
    The C4 field is required.

# Contributor
Thank you for your contributions!

* [Hugo Leon](https://github.com/hugoleon46)

# License

This package is licensed under the MIT License.

Copyright 2020 Sukohi Kuhoh
