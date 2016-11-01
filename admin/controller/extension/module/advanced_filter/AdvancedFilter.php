<?php

require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterRules.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterSelect.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterMultiSelect.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterText.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterCheckbox.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterImage.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterRadio.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterDate.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/FilterCategory.php');


class AdvancedFilter {
	private $currentApp = '';

	private $registry = null;
	private $db = null;
	private $cache = null;
	private $config = null;

	private $defaultShownColumns = array();
	private $shownColumns = array();	//array of shown columns names
	private $inputs = array();	//array of filter inputs objects
	private $columns = array();	//array of columns objects that can be shown

	private $currentPage = 1;
	private $limitPerPage = 20;
	private $updateCachedProducts = false;
	private $sortByFields = array();

	public function __construct($app) {
		$this->currentApp = $app;
	}

	public function __sleep() {
		return array(
			'currentApp',
			'defaultShownColumns',
			'shownColumns',
			'inputs',
			'columns',
			'currentPage',
			'limitPerPage',
			'updateCachedProducts',
			'sortByFields',
		);
	}

	public function refresh($registry) {
		$this->registry = $registry;
		$this->db = $this->registry->get('db');
		$this->cache = $this->registry->get('cache');
		$this->config = $this->registry->get('config');
	}

	public function clean() {
		//$this->registry = null;
	}

	/*ADDING COLUMNS AND FILTER INPUTS*/
	public function chooseFilter($input, $rule) {
		$inputToAdd = null;

		if($rule) {
			$rule = new FilterRules($rule['type'], $rule['rule']);
		}

		switch($input['type']) {
			case 'multiselect':
				$inputToAdd = new FilterMultiSelect($input['name'], $input['entry_text'], '', false, $input['table_name'], $input['alias'], $input['value_type'],
													$rule, $input['inline_edit'], $input['options']);
				break;
			case 'select':
				$inputToAdd = new FilterSelect($input['name'], $input['entry_text'], '', false, $input['table_name'], $input['alias'], $input['value_type'],
													$rule, $input['inline_edit'], $input['options']);
				break;
			case 'text':
				$inputToAdd = new FilterText($input['name'], $input['entry_text'], '', false, $input['table_name'], $input['alias'], $input['value_type'],
													$rule, $input['autocomplete'], $input['inline_edit']);
				break;
			case 'date':
				$inputToAdd = new FilterDate($input['name'], $input['entry_text'], '', false, $input['table_name'], $input['alias'], $input['value_type'],
													$rule, $input['autocomplete'], $input['inline_edit']);
				break;
			case 'radio':
				$inputToAdd = new FilterRadio($input['name'], $input['entry_text'], '', false, $input['table_name'], $input['alias'], $input['value_type'],
													$rule, $input['inline_edit']);
				break;
			case 'checkbox':
				$inputToAdd = new FilterCheckbox($input['name'], $input['entry_text'], '', false, $input['table_name'], $input['alias'], $input['value_type'],
													$rule, $input['inline_edit']);
			case 'image':
				$inputToAdd = new FilterImage($input['name'], $input['entry_text'], '', false, $input['table_name'], $input['alias'], $input['value_type'],
													$rule, $input['inline_edit'], $input['empty_image']);
				break;
			case 'category':
				$inputToAdd = new FilterCategory($input['name'], $input['entry_text'], '', false, $input['table_name'], $input['alias'], $input['value_type'],
													$rule, $input['inline_edit'], $input['options']);
				break;
			default:
				break;
		}
		return $inputToAdd;
	}

	public function addInputs($inputsArray, $rulesArray, $asColumns = false) {
		if(is_null($inputsArray)) {
			return false;
		}

		foreach ($inputsArray as $input) {
			//if object exist, save only reference from other array
			if(isset($this->defaultShownColumns[$input['alias']])) {
				$this->addInput($this->defaultShownColumns[$input['alias']], $asColumns);
				continue;
			}elseif(isset($this->inputs[$input['name']])) {
				$this->addInput($this->inputs[$input['name']], $asColumns);
				continue;
			}elseif(isset($this->columns[$input['alias']])) {
				$this->addInput($this->columns[$input['alias']], $asColumns);
				continue;
			}

			$rule = isset($rulesArray[$input['rules']]) ? $rulesArray[$input['rules']] : false;

			$inputToAdd = $this->chooseFilter($input, $rule);

			$this->addInput($inputToAdd, $asColumns);
		}
	}

	public function addInput($input, $asColumns) {
		if(is_null($input)) {
			return null;
		}

		if($asColumns) {
			$this->columns[$input->getDbColumnName()] = $input;
		} else {
			$this->inputs[$input->getId()] = $input;
		}

		return $this;
	}

	/*FILTER INPUTS*/
	public function updateInputs($inputs) {
		if(!is_array($inputs)) {
			return false;
		}

		$rules = false;
		if(isset($inputs['rules'])) {
			$rules = $inputs['rules'];
			unset($inputs['rules']);
		}

		//Set all filter inputs and their rules values to false
		foreach ($this->inputs as $input) {
			$input->setValue(false);
		}

		foreach ($inputs as $id => $value) {

			if(preg_match('/filter_\w+/', $id, $matches) && isset($matches[0])) {
				//var_dump($value);
				$id = $matches[0];

			}


			$input = isset($this->inputs[$id]) ? $this->inputs[$id] : null;

			if(is_null($input)) {
				continue;
			}

			//Check type of actuale value($value) with type of expected value($dbValueType)
			if($input->getDbValueType() == 'string') {
				if(is_string($value) && (empty($value) || strlen($value) > 300)) {
					continue;
				}
				$value = $this->db->escape($value);
			} elseif($input->getDbValueType() == 'integer') {
				if(is_array($value)) {
					$temp = array();
					foreach ($value as $tval) {
						if(!is_numeric($tval)) {
							continue;
						}
						$temp[] = (int)$tval;
					}
					$value = $temp;
				} else {
					if(!is_numeric($value)) {
						continue;
					}
					$value = (int)$value;
				}
			} elseif($input->getDbValueType() == 'float') {
				if(!is_numeric($value)) {
					continue;
				}
				$value = (float)$value;
			} elseif($input->getDbValueType() == 'date') {
				$value = $this->db->escape($value);
			} elseif($input->getDbValueType() == 'none') {
				$value = '';
			} else {
				continue;
			}

			if($rules && isset($rules[$id])) {
				$input->getRules()->setValue($rules[$id]);
			}

			//Save value for later use
			$input->setValue($value);
		}

		return true;
	}

	public function renderInputs($token) {
		$html = '<table class="table filters"><tr>';

		$i = 0;
		foreach ($this->inputs as $input) {
			$imod3 = $i%3;
			if($imod3 == 0 && $i != 0) {
				$html .= "</tr><tr>";
			}

			$html .= $input->html($token, ($imod3 == 0 || ($i - 2)%3 == 0));
			$i += 1;
		}

		$html .= "</tr></table>";

		return $html;
	}

	/*SHOWN PRODUCT COLUMNS*/
	public function loadDefaultColumns($model_product_filter) {
		if(is_null($model_product_filter)) {
			return false;
		}

		$rules = $model_product_filter->getFilterRules();

		$image = $model_product_filter->getFilterFields(array('alias' => 'image'));
		$name = $model_product_filter->getFilterFields(array('alias' => 'name'));
		$model = $model_product_filter->getFilterFields(array('alias' => 'model'));
		$price = $model_product_filter->getFilterFields(array('alias' => 'price'));
		$quantity = $model_product_filter->getFilterFields(array('alias' => 'quantity'));
		$status = $model_product_filter->getFilterFields(array('alias' => 'status'));

		if(!$rules || !$image || !$name || !$model || !$price || !$quantity || !$status) {
			return false;
		}

		$image = $image['filter_image'];
		$name = $name['filter_name'];
		$model = $model['filter_model'];
		$price = $price['filter_price'];
		$quantity = $quantity['filter_quantity'];
		$status = $status['filter_status'];

		$this->defaultShownColumns = array(
			'image' 	=> $this->chooseFilter($image, isset($rules[$image['rules']]) ? $rules[$image['rules']] : false),
			'name'  	=> $this->chooseFilter($name, isset($rules[$name['rules']]) ? $rules[$name['rules']] : false),
			'model' 	=> $this->chooseFilter($model, isset($rules[$model['rules']]) ? $rules[$model['rules']] : false),
			'price' 	=> $this->chooseFilter($price, isset($rules[$price['rules']]) ? $rules[$price['rules']] : false),
			'quantity'  => $this->chooseFilter($quantity, isset($rules[$quantity['rules']]) ? $rules[$quantity['rules']] : false),
			'status'    => $this->chooseFilter($status, isset($rules[$status['rules']]) ? $rules[$status['rules']] : false)
		);

		$this->shownColumns = $this->columns = $this->defaultShownColumns;

		return true;
	}

	public function updateShownColumns($columns) {
		if(!is_array($columns) || count($columns) == 0) {
			$this->shownColumns = $this->defaultShownColumns;
			return;
		}

		unset($this->shownColumns);
		foreach ($columns as $value) {
			if(isset($this->columns[$value])) {
				$this->shownColumns[$value] = $this->columns[$value];
			}
		}

		if(is_null($this->shownColumns)) {
			$this->shownColumns = $this->defaultShownColumns;
		}
	}

	public function renderShownColumnsInputs() {
		$html = '<select id="column-show" name="columns_shown[]" multiple>';

		foreach ($this->columns as $column) {
			$html .= $column->shownCheckboxHtml(isset($this->shownColumns[$column->getDbColumnName()]));
		}

		$html .= "</select>";

		return $html;
	}

	/*FILTER*/
	public function filterProducts($start = 0, $limit = 20, $orderBy = array(), $autocomplete = false, $cache = false) {
		if(is_null($this->db) || is_null($this->config) || is_null($this->cache)) {
			return array();
		}

		if(!$this->updateCachedProducts && $cache) {
			return $this->cache->get('cachedFilteredProducts');
		}

		if($this->updateCachedProducts) {
			$this->updateCachedProducts = false;
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "product LEFT JOIN " . DB_PREFIX . "product_description ON (" . DB_PREFIX . "product.product_id = " . DB_PREFIX . "product_description.product_id)";

		$filterCategorySql = '';
		if (isset($this->inputs['filter_category_id']) && is_array($this->inputs['filter_category_id']->getValue())) {
			$filterCategorySql = " LEFT JOIN " . DB_PREFIX . "product_to_category ON (" . DB_PREFIX . "product.product_id = " . DB_PREFIX . "product_to_category.product_id)";
		}

		$where = $filterCategorySql . " WHERE " . DB_PREFIX . "product_description.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		foreach ($this->inputs as $id => $input) {
			$where .= $input->sql($autocomplete);
		}

		$orderBySql = '';
		if(isset($orderBy['fields']) && isset($orderBy['type']) && count($orderBy['fields']) > 0 && ($orderBy['type'] == 'ASC' || $orderBy['type'] == 'DESC')) {
			$orderBySql = ' ORDER BY ';
			$lastField = end($orderBy['fields']);
			$empty = true;
			foreach ($orderBy['fields'] as $fieldId) {
				if( ! isset($this->shownColumns[$fieldId]) || ! $this->shownColumns[$fieldId]->isSortable()) {
					continue;
				}
				$input = $this->shownColumns[$fieldId];
				$empty = false;
				$orderBySql .= $input->getDbTable() . '.' . $input->getDbColumnName() . ($lastField != $fieldId ? ', ' : ' ');
			}
			$orderBySql .= $orderBy['type'];

			$orderBySql = $empty ? '' : $orderBySql;
		}

		/*Products query*/
		$sql .= $where . " GROUP BY " . DB_PREFIX . "product.product_id" . $orderBySql;

		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = $this->config->get('config_admin_limit');
		}

		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;

		$query = $this->db->query($sql);

		$products = $query ? $query->rows : array();

		$total = 0;
		if(!$autocomplete) {
			/*Count total products*/
			$sqlTotal = "SELECT COUNT(DISTINCT " . DB_PREFIX . "product.product_id) AS total FROM " . DB_PREFIX . "product LEFT JOIN " . DB_PREFIX . "product_description ON (" . DB_PREFIX . "product.product_id = " . DB_PREFIX . "product_description.product_id)" . $where;

			$queryTotal = $this->db->query($sqlTotal);

			$total = ($queryTotal && isset($queryTotal->row['total'])) ? $queryTotal->row['total'] : 0;

			//Save filtered products
			$this->cache->set('cachedFilteredProducts', array('products' => $products, 'total' => $total));
		}

		return array('products' => $products, 'total' => $total);
	}

	/*GETTERS/SETTERS*/
	public function setPage($page) {
		$page = (int)$page;
		$this->currentPage = (!is_numeric($page) || $page < 1) ? 1 : $page;
	}

	public function setLimitPerPage($limit) {
		$limit = (int)$limit;
		$this->limitPerPage = (!is_numeric($limit) || $limit < 1) ? 20 : $limit;
	}

	public function setSortBy($sort) {
		if(isset($this->sortByFields['fields'][0]) && isset($this->shownColumns[$this->sortByFields['fields'][0]])) {
			$this->shownColumns[$this->sortByFields['fields'][0]]->setSortChecked(false);
		}

		if(isset($sort['fields'][0]) && isset($this->shownColumns[$sort['fields'][0]])) {
			$this->shownColumns[$sort['fields'][0]]->setSortChecked(true);
		}

		$this->sortByFields = $sort;
	}

	public function checkApplication($app) {
		return $this->currentApp === $app;
	}

	public function enableUpdateCachedProducts() {
		$this->updateCachedProducts = true;
	}

	public function getInput($id) {
		if(!isset($this->inputs[$id])) {
			return null;
		}

		return $this->inputs[$id];
	}

	public function getColumn($id) {
		if(!isset($this->columns[$id])) {
			return null;
		}

		return $this->columns[$id];
	}

	public function getInputs() {
		return $this->inputs;
	}

	public function hasInputs() {
		return count($this->inputs) != 0;
	}

	public function hasInput($id) {
		return $this->getInput($id) != null;
	}

	public function getNumberOfInputs() {
		return count($this->inputs);
	}

	public function getShownColumns() {
		return $this->shownColumns;
	}

	public function getPage() {
		return $this->currentPage;
	}

	public function getLimitPerPage() {
		return $this->limitPerPage;
	}

	public function getSortBy() {
		return $this->sortByFields;
	}
}

?>
