<?php
	abstract class FilterInput {
		protected $type = "none";
		protected $id = "";
		protected $captionText = "";
		protected $defaultValue = "";
		protected $value = false;
		protected $dbTable = "";
		protected $dbColumnName = "";
		protected $dbValueType = "";
		protected $rules = false;
		protected $autocomplete = false;
		protected $inlineEdit = false;
		protected $sortable = false;
		private $sortChecked = false;

		//Returns an html interpretation of filter input
		abstract public function html($token, $isEven = false);
		//Returns an html interpretation of editable input with value for inline editing
		abstract public function editHtmlInput($id, $value);

		//Returns sql condition according to inner state of this object
		public function sql($autocomplete = false) {
			if(is_bool($this->value) && $this->value == false) {
				return '';
			}

			$rule = $this->dbValueType == 'string' ? FilterRules::sqlInterpretation('lk') : FilterRules::sqlInterpretation('eq');
			if(!$autocomplete && $this->rules) {
				$rule = $this->rules->sql();
			}

			if(!$rule) {
				return '';
			}

			$rule = FilterRules::replaceValue($rule, $this->value);

			$dbFieldName = $this->dbTable . '.' . $this->dbColumnName;

			return " AND " . $dbFieldName . " " . $rule;
		}

		//Returns html checkbox for column show control
		public function shownCheckboxHtml($checked) {
			return '<option value="' . $this->dbColumnName . '" ' . ($checked ? 'selected' : '') . '>' . $this->captionText . '</option>';
		}

		//Returns js script for input autocompletation
		protected function getJSAutocompleteScript($token) {
			$sc = '<script type="text/javascript">';
			$sc .= 	"$('input[name=" . $this->id . "]').autocomplete({"
    				. "delay: 500,"
    				. "source: function(request, response) { $.ajax({"
       				. "url: 'index.php?route=extension/module/product_filter/autocomplete&token=" . $token . "&" . $this->id . "=' +  encodeURIComponent(request),"
        			. "dataType: 'json',"
        			. "success: function(json) {"
				        . "var col = '" . $this->dbColumnName . "';"
				        . "response($.map(json, function(item) {"
				            . "return {label: item[col], value: item.product_id}}));"
        				."}"
      				. "});"
   					. "}, select: function(item) {"
      					. "$('input[name=" . $this->id . "]').val(item['label']);"
    				. "}});";
			$sc .= "</script>";
			return $sc;
		}

		/*SETTERS*/
		public function setCaptionText($captionText) {
			$this->captionText = $captionText;
		}

		public function setDefaultValue($defaultValue) {
			$this->defaultValue = $defaultValue;
		}

		public function setValue($value) {
			$this->value = $value;

			if(is_bool($value) && !$value && $this->rules) {
				$this->rules->setValue(false);
			}
		}

		public function setRules($rules) {
			$this->rules = $rules;
		}

		public function setAutocomplete($autocomplete) {
			$this->autocomplete = $autocomplete;
		}

		public function setInlineEdit($inlineEdit) {
			$this->inlineEdit = $inlineEdit;
		}

		public function setSortChecked($checked) {
			$this->sortChecked = $checked;
		}

		/*GETTERS*/
		public function getId() {
			return $this->id;
		}

		public function getCaptionText() {
			return $this->captionText;
		}

		public function getDefaultValue() {
			return $this->defaultValue;
		}

		public function getValue() {
			return $this->value;
		}

		public function getDbTable() {
			return $this->dbTable;
		}

		public function getDbColumnName() {
			return $this->dbColumnName;
		}

		public function getDbValueType() {
			return $this->dbValueType;
		}

		public function getRules() {
			return $this->rules;
		}

		public function isAutocomplete() {
			return $this->autocomplete;
		}

		public function isInlineEdit() {
			return $this->inlineEdit;
		}

		public function isTypeOf($type) {
			return $this->type == $type;
		}

		public function isColumn($column) {
			return $this->dbColumnName == $column;
		}

		public function isSortable() {
			return $this->sortable ? $this->dbColumnName : false;
		}

		public function isSortChecked() {
			return $this->sortChecked ? $this->dbColumnName : false;
		}
	}
?>
