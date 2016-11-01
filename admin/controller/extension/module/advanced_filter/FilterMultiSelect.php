<?php

include_once "FilterSelect.php";

class FilterMultiSelect extends FilterSelect {

	//Returns sql condition according to inner state of this object
	public function sql($autocomplete = false) {
		if(is_bool($this->value) && $this->value == false) {
			return '';
		}

		if( ! is_array($this->value) || count($this->value) == 0) {
			return '';
		}

		$dbFieldName = $this->dbTable . '.' . $this->dbColumnName;

		return " AND " . $dbFieldName . " IN(" . implode(',',$this->value) . ")";
	}

	public function html($token, $isEven = false) {
		$even = '';
		if($isEven) {
			$even = ' class="filter-even"';
		}

		$html = "<td".$even."><strong>" . $this->captionText . "</strong></td>";
		$html .= "<td".$even." colspan=\"2\">" . $this->filterHtmlInput($this->id, $this->value) . "</td>";
		
		return $html;
	}

	protected function filterHtmlInput($id, $values) {
		$selection = is_bool($values) ? false : true;

		$select = '<select name="' . $id . '[]" id="' . $id . '" multiple>';

		//Option value to key conversion
		//TODO: Possible problem: occurence of the same key as value
		$value = array();
		if($selection && is_array($values)) {
			foreach ($values as $val) {
				if(!isset($this->options[$val])) {
					$key = $this->getOptionKey($val);
					$temp = empty($key) ? $val : $key;
					$value[$temp] = $temp;
				} else {
					$value[$val] = $val;
				}
			}
		}

		if(is_array($this->options)) {
			foreach ($this->options as $optId => $optValue) {
				if($selection && isset($value[$optId])) {
					$select .= '<option value="' . $optId . '" selected>' . $optValue . '</option>';
				} else {
					$select .= '<option value="' . $optId . '">' . $optValue . '</option>';
				}
			}
		}

		$js = "<script type=\"text/javascript\">" .
			  "$('#" . $id . "').multipleSelect({" .
			    "filter: true," .
			    "width: '100%'" .
			  "});" .
			  "</script>";

		return $select . '</select>' . $js;
	}

}