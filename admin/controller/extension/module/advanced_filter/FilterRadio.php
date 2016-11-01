<?php

include_once "FilterInput.php";

class FilterRadio extends FilterInput {

	public function __construct($id,
								$captionText,
								$defaultValue,
								$value,
								$dbTable,
								$dbColumnName,
								$dbValueType,
								$rules,
								$inlineEdit) {

		$this->type 		= "radio";
		$this->id 			= $id;
		$this->captionText 	= $captionText;
		$this->defaultValue = $defaultValue;
		$this->value 		= $value;
		$this->dbTable 		= $dbTable;
		$this->dbColumnName = $dbColumnName;
		$this->dbValueType 	= $dbValueType;
		$this->rules 		= $rules;
		$this->autocomplete = false;
		$this->inlineEdit 	= $inlineEdit; 
		$this->sortable		= false;
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

		$html = "<td".$even."><strong>" . $this->captionText . "</strong></td>";
		$html .= $rules . "<td".$even.">" . $this->editHtmlInput($this->id, $this->value) . "</td>";

		return $html;
	}

	public function editHtmlInput($id, $value) {
		return '<input type="radio" name="' . $id . '" ' . ($value ? 'checked' : '') . ' />';
	}
}

?>