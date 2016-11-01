<?php

include_once "FilterInput.php";

class FilterText extends FilterInput {

	public function __construct($id,
								$captionText,
								$defaultValue,
								$value,
								$dbTable,
								$dbColumnName,
								$dbValueType,
								$rules,
								$autocomplete,
								$inlineEdit) {

		$this->type 		= "text";
		$this->id 			= $id;
		$this->captionText 	= $captionText;
		$this->defaultValue = $defaultValue;
		$this->value 		= $value;
		$this->dbTable 		= $dbTable;
		$this->dbColumnName = $dbColumnName;
		$this->dbValueType 	= $dbValueType;
		$this->rules 		= $rules;
		$this->autocomplete = $autocomplete;
		$this->inlineEdit 	= $inlineEdit; 
		$this->sortable		= true;
	}

	public function html($token, $isEven = false) {
		$even = '';
		if($isEven) {
			$even = ' class="filter-even"';
		}

		$rules = "<td".$even."></td>";
		if($this->rules) {
			$rules = $this->rules->html($this->id, $isEven);
		} 

		$autocomplete = '';
		if($this->autocomplete) {
			$autocomplete = $this->getJSAutocompleteScript($token);
		}

		$values= array();
		preg_match("/(.*[^%])/", $this->value, $values);
		$this->value = count($values) > 0 ? $values[0] : $this->value;

		$html = "<td".$even."><strong>" . $this->captionText . "</strong></td>";
		$html .= $rules . "<td".$even.">" . $this->editHtmlInput($this->id, $this->value) . $autocomplete . "</td>";

		return $html;
	}

	public function editHtmlInput($id, $value) {
		return '<input type="text" name="' . $id . '" id="' . $id . '" value="' . $value . '" style="width:100%" />';
	}
}
?>