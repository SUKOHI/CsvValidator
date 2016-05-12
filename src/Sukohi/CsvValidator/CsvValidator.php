<?php namespace Sukohi\CsvValidator;

class CsvValidator {

	private $csv_data, $rules, $heading_row, $errors, $heading_keys = [];

	public function make($csv_path, $rules, $encoding = 'UTF-8') {

		$this->csv_data = [];
		$this->setRules($rules);

		\Excel::load($csv_path, function($reader) {

			$reader->noHeading();
			$csv_data = $reader->toArray();

			if($this->isNoHeadings()) {

				$this->heading_row = $csv_data[0];
				$new_rules = [];

				foreach ($this->heading_keys as $heading_key) {

					$key_index = array_search($heading_key, $this->heading_row);

					if($key_index > -1) {

						$new_rules[$key_index] = $this->rules[$heading_key];

					} else {

						throw new \Exception('"'. $heading_key .'" not found.');

					}

				}

				$this->setRules($new_rules);
				unset($csv_data[0]);
				$csv_data = array_values($csv_data);

			}

			if(empty($csv_data)) {

				throw new \Exception('No data found.');

			}

			$new_csv_data = [];
			$rule_keys = array_keys($this->rules);

			foreach ($csv_data as $row_index => $csv_values) {

				foreach ($rule_keys as $rule_key_index) {

					$new_csv_data[$row_index][$rule_key_index] = $csv_values[$rule_key_index];

				}

			}
			
			$this->csv_data = $new_csv_data;

		}, $encoding);

		return $this;

	}

	public function fails() {

		$errors = [];

		foreach ($this->csv_data as $row_index => $csv_values) {

			$validator = \Validator::make($csv_values, $this->rules);

			if(!empty($this->heading_row)) {

				$validator->setAttributeNames($this->heading_row);

			}

			if($validator->fails()) {

				$errors[$row_index] = $validator->messages()->toArray();

			}

		}

		$this->errors = $errors;
		return (!empty($this->errors));

	}

	public function getErrors() {

		return $this->errors;

	}

	public function getData() {

		return $this->csv_data;

	}

	public function setAttributeNames($attribute_names) {

		$this->heading_row = $attribute_names;

	}

	private function setRules($rules) {

		$this->rules = $rules;
		$this->heading_keys = array_keys($rules);

	}

	private function isNoHeadings() {

		foreach ($this->heading_keys as $heading_key) {

			if(!is_int($heading_key)) {

				return true;

			}

		}

		return false;

	}

}