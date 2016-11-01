<?php 

class FilterRules {
	private $type = "";
	private $options = array();
	private static $sqlInterpretations = array(
		'eq' => '= \'[:value]\'', //equal
		'lt' => '> \'[:value]\'', //large than
		'st' => '< \'[:value]\'', //smaller than
		'le' => '>= \'[:value]\'', //larger or equal
		'se' => '<= \'[:value]\'', //smaller or equal
		'neq'=> '!= \'[:value]\'', //not equal 
		'nlk'=> 'NOT LIKE \'[:value]%\'',
		'lk' => 'LIKE \'[:value]%\'',
		'btw' => 'LIKE \'%[:value]%\'',
		'nbtw' => 'NOT LIKE \'%[:value]%\'',
		'regex' => 'REGEXP \'[:value]\'',
	);

	private $value = false;
	private $enabled = false;

	public function __construct($type, $rules) {
		$this->type = $type;
		$this->options = $rules['options'];
		$this->enabled = $rules['enable'];
	}

	public function html($id, $isEven = false) {
		$even = '';
		if($isEven) {
			$even = ' class="filter-even"';
		}

		if(!$this->enabled) {
			return "";
		}

		$selection = is_bool($this->value) ? false : true;

		$select = '<td'.$even.'><select name="rules[' . $id . ']" id="' . $id . '_rules" class="rule" style="width:100%">';
		$select .= '<option value="" ' . (!$selection ? 'selected' : '') . '>Ignore this</option>';

		if(is_array($this->options)) {
			foreach ($this->options as $optId => $optValue) {
				if($selection && $this->value === $optId) {
					$select .= '<option value="' . $optId . '" selected>' . $optValue . '</option>';
				} else {
					$select .= '<option value="' . $optId . '">' . $optValue . '</option>';
				}
			}
		}

		$select .= '</select></td>';

		return $select;
	}

	public function sql() {
		if(!$this->enabled) {
			return false;
		}

		return self::sqlInterpretation($this->value);
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function getValue() {
		return $this->value;
	}

	public function isTypeOf($type) {
		return $this->type == $type;
	}

	public static function sqlInterpretation($key) {
		if(empty($key)) {
			return '';
		}

		if(!isset(self::$sqlInterpretations[$key])) {
			return 'RULE NOT SET FOR KEY ' . $key;
		}
		return self::$sqlInterpretations[$key];
	}

	public static function replaceValue($rule, $value) {
		return str_replace('[:value]', $value, $rule);
	}
}

?>