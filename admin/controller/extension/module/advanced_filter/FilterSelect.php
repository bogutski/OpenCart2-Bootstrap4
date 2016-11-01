<?php

include_once "FilterInput.php";

class FilterSelect extends FilterInput {

	protected $options = array();

	public function __construct($id,
								$captionText,
								$defaultValue,
								$value,
								$dbTable,
								$dbColumnName,
								$dbValueType,
								$rules,
								$inlineEdit,
								$options) {

		$this->type 		= "select";
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
		$this->options 		= $options;
		$this->sortable   	= false;
	}

	/*Return html interpretation of this input*/
	public function html($token, $isEven = false) {
		$even = '';
		if($isEven) {
			$even = ' class="filter-even"';
		}

		$html = "<td".$even."><strong>" . $this->captionText . "</strong></td>";
		$html .= "<td".$even." colspan=\"2\">" . $this->editHtmlInput($this->id, $this->value) . "</td>";
		
		return $html;
	}

	public function editHtmlInput($id, $value) {
		$selection = is_bool($value) ? false : true;

		$select = '<select name="' . $id . '" id="' . $id . '" class="select" style="width:100%">';
		$select .= '<option value="" ' . (!$selection ? 'selected' : '') . '></option>';

		//Option value to key conversion
		//TODO: Possible problem: occurence of the same key as value
		if($selection && !isset($this->options[$value])) {
			$key = $this->getOptionKey($value);
			$value = empty($key) ? $value : $key;
		}

		if(is_array($this->options)) {
			foreach ($this->options as $optId => $optValue) {
				if($selection && $value == $optId) {
					$select .= '<option value="' . $optId . '" selected>' . $optValue . '</option>';
				} else {
					$select .= '<option value="' . $optId . '">' . $optValue . '</option>';
				}
			}
		}

		return $select . '</select>';
	}

	public function getOptionText($key) {
		if(isset($this->options[$key])) {
			return $this->options[$key];
		}
		return "";
	}

	public function getOptionKey($value) {
		$key = array_search($value, $this->options);
		return $key ? $key : "";
	}
}

?>