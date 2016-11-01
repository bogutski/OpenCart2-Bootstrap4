<?php

class ProductTableBuilder {
	private $header = "";
	private $body = "";
	private $formAction = "";

	public function __construct() {
		$this->header = '<table class="table table-hover"><thead><tr><th><input type="checkbox" id="select-all" /></th>';
		$this->body = '</tr></thead><tbody>';
	}

	public function setFormAction($action) {
		$this->formAction = $action;
	}

	public function addHeaderColumn($text, $inlineEdit, $sortable, $order_type, $checked = false) {
		$sort_checkbox = '';
		if($sortable !== false) {
			$sort_checkbox = '<input name="sort[]" style="display:none" type="checkbox" id="'.$sortable.'_sort" value="'.$sortable.'" ';

			if($sortable == $checked) {
				$sort_checkbox .= 'checked /><label for="'.$sortable.'_sort"><i class="fa fa-' . ($order_type == 'ASC' ? 'sort-asc' : 'sort-desc') . ' fa-lg" aria-hidden="true"></i>';
			} else {
				$sort_checkbox .= '/><label for="'.$sortable.'_sort"><i class="fa fa-sort fa-lg" aria-hidden="true"></i>';
			} 	
		}

		$this->header .= '<th>' . $sort_checkbox;
		$this->header .= $text . ($inlineEdit ? ' <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>' : '');
		$this->header .= ($sortable !== false ? '</label>' : '') . '</th>';
	}

	public function addSelectedColumn($id, $selected) {
		$this->body .= '<tr id="product_' . $id . '"><td>';
        $this->body .= '<input type="checkbox" name="selected[]" value="' . $id . '"' . ($selected ? ' checked="checked"' : '') . ' /></td>';
	}

	public function addCustomColumn($inlineEdit, $key, $value) {
		$method = $key . "Column";
		if(method_exists($this, $method)) {
			$this->body .= $this->{$method}($inlineEdit, $key, $value);
			return;
		}        		

		$this->body .= $this->defaultColumn($inlineEdit, $key, $value);
	}

	public function endOfProductRecord() {
		$this->body .= '</tr>';
	}

	public function setNoResults($colspan, $text) {
		$this->body .= '<tr><td colspan="' . $colspan . '">' . $text . '</td></tr>';
	}

	public function table() {
		$table = '<form action="' . $this->formAction . '" method="post" enctype="multipart/form-data" id="form">';
		return $table . $this->header . $this->body . '</tbody></table></form>';
	}

	public static function getColumnRenderedValue($key, $value) {
		$method = $key . "Value";
		if(method_exists(get_called_class(), $method)) {
			return call_user_func_array(array(get_called_class(), $method), array($value));
		}  
		return $value;
	}

	/*Custom columns*/
	private function imageColumn($inlineEdit, $key, $value) {
		$editable = $inlineEdit ? ' class="editable" data-field="' . $key .'"' : '';

		return '<td' . $editable . '>'.self::imageValue($value).'</td>';
	}

	private function quantityColumn($inlineEdit, $key, $value) {
		$editable = $inlineEdit ? ' class="editable" data-field="' . $key .'"' : '';
		$value = self::quantityValue($value);

		return '<td><div' . $editable . '>' . $value . '</div></td>';
	}

	private function actionColumn($inlineEdit, $key, $value) {
		$html = '<td>';
		foreach ($value as $action) {
			$html .= '<a href="' . $action['href'] . '" data-toggle="tooltip" data-original-title="'.$action['text'].'" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
		}
		$html .= '</td>';
		return $html;
	}

	private function defaultColumn($inlineEdit, $key, $value) {
		$editable = $inlineEdit ? ' class="editable js-edited-value" data-field="' . $key .'"' : '';

		return '<td' . $editable . '>' . $value . '</td>';
	}

	/*Custom value rendering*/
	private static function quantityValue($value) {
		return (int)$value > 0 ? ('<span class="label label-success js-edited-value">'.$value.'</span>') : ('<span class="label label-danger js-edited-value">'.$value.'</span>');
	}

	private static function imageValue($value) {
		return '<img src="' . $value['filename'] . '" alt="" /><span style="display:none" class="js-edited-value">'.$value['name'].'</span>';
	}
}

?>