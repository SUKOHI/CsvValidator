<?php

namespace Sukohi\CsvValidator\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class Csv implements Rule
{
    private $rules = [];
    private $options = [];
    private $error_messages = [];

    /**
     * Create a new rule instance.
     *
     * @param  array  $rules
     * @param  array  $options
     * @return void
     */
    public function __construct($rules, $options = [])
    {
        if(gettype($options) === 'string') {

            $options = ['encoding' => $options];

        }

        $this->rules = $rules;
        $this->options = $options;
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

        if($request->hasFile($attribute)) {

            $errors = [];
            $csv_path = $request->file($attribute)->path();
            $csv_data = \FluentCsv::parse($csv_path, $this->getOption('encoding'));

            $request->merge([
                $attribute .'_data' => $csv_data
            ]);

            $start_row_number = intval($this->getOption('start_row', -1));
            $end_row_number = intval($this->getOption('end_row', -1));

            foreach($csv_data as $row_index => $row_data) {

                $row_number = $row_index + 1;

                if(is_callable($this->getOption('row_callback')) &&
                    !$this->getOption('row_callback')($row_number, $row_data)) {

                    continue;

                } else if(($start_row_number !== -1 && $row_number < $start_row_number) ||
                    ($end_row_number !== -1 && $row_number > $end_row_number)) {

                    continue;

                }

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
     * Get the validation error messages.
     *
     * @return array
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

    private function getOption($key, $default = null) {

        return Arr::get($this->options, $key, $default);

    }
}
