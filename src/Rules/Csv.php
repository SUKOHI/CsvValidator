<?php

namespace Sukohi\CsvValidator\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class Csv implements Rule
{
    private $rules = [];
    private $encoding = '';
    private $error_messages = [];

    /**
     * Create a new rule instance.
     *
     * @param  array  $rules
     * @param  string  $encoding
     * @return void
     */
    public function __construct($rules, $encoding = '')
    {
        $this->rules = $rules;
        $this->encoding = $encoding;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = false;
        $request = request();
        $csv_file = $request->file($attribute);

        if($csv_file->isValid()) {

            $errors = [];
            $csv_path = $csv_file->path();
            $csv_data = \FluentCsv::parse($csv_path, $this->encoding);

            foreach($csv_data as $row_index => $row_data) {

                $attribute_names = $this->getAttributeNames($row_index);
                $validator = \Validator::make(
                    $this->getFilteredRowData($row_data),
                    $this->rules
                );
                $validator->setAttributeNames($attribute_names);

                if($validator->fails()) {

                    $line_errors = $validator->errors()->toArray();

                    foreach($line_errors as $error_index => $line_error) {

                        $errors[] = $line_error[0];

                    }

                }

            }

            $this->error_messages = $errors;

            if(empty($errors)) {

                $result = true;

            }

        }

        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_messages;
    }

    private function getAttributeNames($row_index) {

        $attribute_names = [];
        $row_no = $row_index + 1;

        foreach($this->rules as $col_index => $rule) {

            $col_name = $this->getColumnName($col_index);
            $attribute_name = $col_name . $row_no;
            $attribute_names[] = $attribute_name;

        }

        return $attribute_names;

    }

    private function getColumnName($index) {

        $prefix = '';
        $alphabet = range('A', 'Z');

        if(!isset($alphabet[$index])) {

            $prefix_index = floor($index / count($alphabet)) - 1;
            $prefix = $alphabet[$prefix_index];
            $index = $index % count($alphabet);

        }

        return $prefix . $alphabet[$index];

    }

    private function getFilteredRowData($row_data) {

        $filtered_data = [];
        $indexes = array_keys($this->rules);

        foreach($indexes as $index) {

            $filtered_data[$index] = Arr::get($row_data, $index, '');

        }

        return $filtered_data;

    }
}
