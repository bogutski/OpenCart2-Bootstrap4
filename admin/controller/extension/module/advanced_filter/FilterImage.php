<?php

include_once "FilterCheckbox.php";

class FilterImage extends FilterCheckbox {

	private $emptyImagePlaceholder = '';

	public function __construct($id,
								$captionText,
								$defaultValue,
								$value,
								$dbTable,
								$dbColumnName,
								$dbValueType,
								$rules,
								$inlineEdit,
								$noImage) {

		$this->type 		= "image";
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
		$this->emptyImagePlaceholder = $noImage;
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
		$html .= $rules . "<td".$even."><input type=\"checkbox\" name=\"" . $this->id . "\" id=\"" . $this->id . "\" " . ($this->value ? "checked" : "") . " /></td>";

		return $html;
	}

	public function editHtmlInput($id, $value) {
		$html = '<div>';
		$html .= '<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail" style="width:100%">';
		$html .= '<img src="' . $value . '" alt="" data-placeholder="'.$this->emptyImagePlaceholder['href'].'" />';
		$html .= '</a>';
		$html .= '<input type="hidden" id="' . $id . '" value="" data-empty-image="'.$this->emptyImagePlaceholder['filename'].'" />';
		$html .= '</div>';
		return $html;
	}
}

?>