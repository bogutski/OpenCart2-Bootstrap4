<?php
class ModelExtensionModuleProductFilter extends Model {
	public function createTable() {
		$sql_filters = 'CREATE TABLE ' . DB_PREFIX . 'advanced_product_filter(name varchar(40) primary key, type varchar(20) not null, entry_text varchar(50) not null, value varchar(50) null, options text null, autocomplete tinyint(1) not null, inline_edit tinyint(1) not null, rules integer not null, enable tinyint(1) not null, table_name varchar(30) not null, value_type varchar(10) not null, sort integer not null, alias varchar(30) not null, show_column tinyint(1) not null) ENGINE=MyISAM COLLATE=utf8_general_ci;';

		$sql_rules = 'CREATE TABLE ' . DB_PREFIX . 'advanced_product_filter_rules(rules_id integer primary key, type varchar(20) not null, rule text not null) ENGINE=MyISAM COLLATE=utf8_general_ci;';

		if($this->db->query($sql_filters) && $this->db->query($sql_rules)) {

			$this->language->load('extension/module/product_filter');

			$rules = array(
				0 => array(
					'type' => 'decimal',
					'rule' => serialize(array(
								'type' 		=> 'select',
								'options'	=> array(
									'eq' => '=', //equal
									'lt' => '>', //large than
									'st' => '<', //smaller than
									'le' => '>=', //larger or equal
									'se' => '<=', //smaller or equal
									'neq'=> '!=' //not equal
									),
								'enable'	=> true
								))
					),
				1 =>  array(
					'type' => 'text',
					'rule' => serialize(array(
								'type' 		=> 'select',
								'options'	=> array(
									'lk' => $this->language->get('text_like'), //like some string LIKE str%
									'nlk' => $this->language->get('text_not_like'), //not like string NOT LIKE str
									'btw' => $this->language->get('text_anywhere'),
									'nbtw' => $this->language->get('text_nowhere'),
									'regex' => $this->language->get('text_regex'),
									),
								'enable'	=> true
								))
					),
				2 =>  array(
					'type' => 'image',
					'rule' => serialize(array(
								'type' 		=> 'select',
								'options'	=> array(
									'eq' => $this->language->get('text_no_image'), //with no image
									'neq' => $this->language->get('text_with_image') //with image
									),
								'enable'	=> true
								))
					),
				);
			/*Filter fields*/
			/*
				name(name of the filter field, filter_(column_name_from_table)) =>
					type => type of the field
					entry_text => the name of the field which shows to user
					options	=> if type is select then this is select options
						-options is array where arr key is value of option and arr value is visible text of option tag
						-array must be serialized => serialize(array(key => value))
						-if source of select is in table then you can type => serialize(array('source' => table_name, 'key' => column_name of keys for option, 'value' => column_name of values for option))
					value => default value for text field
					autocomplete => try to autocomplete text
					inline_edit => enable inline editing
					rules => id of the rule or false
					enable => enable the field
					table_name	=> table name which the filter field is from,
					value_type	=> type of the value,
					sort => position of the filter field
			*/
			$fields = array(
				'filter_name' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_name'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> true,
						'table_name'	=> 'product_description',
						'value_type'	=> 'string',
						'sort'			=> 1
					),
				'filter_tag' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_tag'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> true,
						'table_name'	=> 'product_description',
						'value_type'	=> 'string',
						'sort'			=> 8
					),
				'filter_model' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_model'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'string',
						'sort'			=> 2
					),
				'filter_price' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_price'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'float',
						'sort'			=> 3
					),
				'filter_quantity' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_quantity'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 5
					),
				'filter_minimum' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_minimum'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 6
					),
				'filter_stock_status_id' => array(
						'type'			=> 'multiselect',
						'entry_text'	=> $this->language->get('column_stock_status_id'),
						'options'		=> serialize(array('source' => 'stock_status', 'key' => 'stock_status_id', 'value' => 'name')),
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 13
					),
				'filter_shipping' => array(
						'type'			=> 'select',
						'entry_text'	=> $this->language->get('column_shipping'),
						'options'		=> serialize(array(1 => $this->language->get('option_yes'), 0 => $this->language->get('option_no'))),
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 12
					),
				/*'filter_keyword' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_keyword'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'url_alias',
						'value_type'	=> 'string',
						'sort'			=> 7
					),*/
				'filter_date_available' => array(
						'type'			=> 'date',
						'entry_text'	=> $this->language->get('column_date_available'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'date',
						'sort'			=> 9
					),
				'filter_weight' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_weight'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'float',
						'sort'			=> 10
					),
				'filter_image' => array(
						'type'			=> 'image',
						'entry_text'	=> $this->language->get('column_image'),
						'options'		=> null,
						'value'			=> 1,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 2,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'none',
						'sort'			=> 0
					),
				'filter_location' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_location'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'string',
						'sort'			=> 11
					),
				'filter_status' => array(
						'type'			=> 'select',
						'entry_text'	=> $this->language->get('column_status'),
						'options'		=> serialize(array(1 => $this->language->get('option_enabled'), 0 => $this->language->get('option_disabled'))),
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 14
					),
				'filter_manufacturer_id' => array(
						'type'			=> 'multiselect',
						'entry_text'	=> $this->language->get('column_manufacturer_id'),
						'options'		=> serialize(array('source' => 'manufacturer', 'key' => 'manufacturer_id', 'value' => 'name')),
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 4
					),/*
				'filter_special' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_special'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> false,
						'rules'			=> -1,
						'enable'		=> false,
						'table_name'	=> 'product_special',
						'alias'			=> 'price',
						'value_type'	=> 'integer',
						'sort'			=> 13
					),*/
				'filter_upc' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_upc'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'string',
						'sort'			=> 15
					),
				'filter_sku' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_sku'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'string',
						'sort'			=> 16
					),
				'filter_ean' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_ean'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'string',
						'sort'			=> 17
					),
				'filter_jan' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_jan'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'string',
						'sort'			=> 18
					),
				'filter_isbn' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_isbn'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'string',
						'sort'			=> 19
					),
				'filter_mpn' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_mpn'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> true,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'string',
						'sort'			=> 20
					),
				'filter_points' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_points'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 21
					),
				'filter_tax_class_id' => array(
						'type'			=> 'multiselect',
						'entry_text'	=> $this->language->get('column_tax_class_id'),
						'options'		=> serialize(array('source' => 'tax_class', 'key' => 'tax_class_id', 'value' => 'title')),
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 22
					),
				'filter_length' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_length'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'float',
						'sort'			=> 23
					),
				'filter_width' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_width'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'float',
						'sort'			=> 24
					),
				'filter_height' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_height'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'float',
						'sort'			=> 25
					),
				'filter_subtract' => array(
						'type'			=> 'select',
						'entry_text'	=> $this->language->get('column_subtract'),
						'options'		=> serialize(array(1 => $this->language->get('option_yes'), 0 => $this->language->get('option_no'))),
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 26
					),
				'filter_weight_class_id' => array(
						'type'			=> 'multiselect',
						'entry_text'	=> $this->language->get('column_weight_class_id'),
						'options'		=> serialize(array('source' => 'weight_class_description', 'key' => 'weight_class_id', 'value' => 'title')),
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 27
					),
				'filter_length_class_id' => array(
						'type'			=> 'multiselect',
						'entry_text'	=> $this->language->get('column_length_class_id'),
						'options'		=> serialize(array('source' => 'length_class_description', 'key' => 'length_class_id', 'value' => 'title')),
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 28
					),
				'filter_viewed' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_viewed'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> false,
						'rules'			=> 0,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 29
					),
				'filter_category_id' => array(
						'type'			=> 'category',
						'entry_text'	=> $this->language->get('column_category'),
						'options'		=> true,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> -1,
						'enable'		=> true,
						'table_name'	=> 'product_to_category',
						'value_type'	=> 'integer',
						'sort'			=> 30
					),
				'filter_sort_order' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_sort_order'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 0,
						'enable'		=> false,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> 31
					),
				'filter_product_id' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_product_id'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> false,
						'rules'			=> 0,
						'enable'		=> true,
						'table_name'	=> 'product',
						'value_type'	=> 'integer',
						'sort'			=> -1
					),
				'filter_meta_keywords' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_meta_keywords'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product_description',
						'value_type'	=> 'string',
						'sort'			=> 1
					),
				'filter_meta_description' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_meta_description'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product_description',
						'value_type'	=> 'string',
						'sort'			=> 1
					),
				'filter_meta_title' => array(
						'type'			=> 'text',
						'entry_text'	=> $this->language->get('column_meta_title'),
						'options'		=> null,
						'value'			=> null,
						'autocomplete'	=> false,
						'inline_edit'	=> true,
						'rules'			=> 1,
						'enable'		=> false,
						'table_name'	=> 'product_description',
						'value_type'	=> 'string',
						'sort'			=> 1
					),
				);

			/*Insert rules*/
			foreach ($rules as $id => $rule) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "advanced_product_filter_rules VALUES('" . (int)$id . "','" . $rule['type'] . "','" . $rule['rule'] . "')");
			}

			/*Insert filter fields*/
			foreach ($fields as $name => $field) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "advanced_product_filter VALUES('" . $name . "','" . $field['type'] . "','" . $field['entry_text'] . "','" . $field['value'] . "','" . $field['options'] . "'," . (int)$field['autocomplete'] . "," . (int)$field['inline_edit'] . "," . (int)$field['rules'] . "," . (int)$field['enable'] . ",'" . DB_PREFIX . $field['table_name'] . "','" . $field['value_type'] . "'," . (int)$field['sort'] . ",'" . (isset($field['alias']) ? $field['alias'] : substr($name, strpos($name, '_') + 1)) . "', 0)");
			}

			$this->load->model("setting/setting");
			$this->model_setting_setting->editSetting("advanced_product_filter", array("advanced_product_filter_paypal" => 1));
		}
	}

	public function dropTable() {
		$sql_filters = 'DROP TABLE IF EXISTS ' . DB_PREFIX . 'advanced_product_filter';
		$sql_rules = 'DROP TABLE IF EXISTS ' . DB_PREFIX . 'advanced_product_filter_rules';
		$this->load->model("setting/setting");
		$this->model_setting_setting->deleteSetting("advanced_product_filter");
		return $this->db->query($sql_filters) && $this->db->query($sql_rules);
	}

	public function addFilterField($data) {

	}

	public function addFilterFields($data) {

	}

	public function removeFilterField($name) {

	}

	public function removeFilterFields($data) {

	}

	public function editFilterFields($data) {
		$this->db->query('UPDATE ' . DB_PREFIX . 'advanced_product_filter set enable = 0, show_column = 0 where 1');

		if(isset($data['status']) && is_array($data['status'])) {
			foreach ($data['status'] as $key => $state) {
				$enable = isset($state['enable']) ? 1 : 0;
				$column = isset($state['column']) ? 1 : 0;
				if(!$this->db->query("UPDATE " . DB_PREFIX . "advanced_product_filter set enable = " . $enable . ", show_column = " . $column . " where name = '" . $this->db->escape($key) . "'"))
					return false;
			}
		}

		$this->load->model("setting/setting");
		$this->model_setting_setting->editSetting("advanced_product_filter", array("advanced_product_filter_paypal" => isset($data['paypal']) ? 1 : 0));

		return true;
	}

	public function getFilterRules($filter = array()) {
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'advanced_product_filter_rules';

		$total = count($filter);
		if($total > 0) {
			$sql .= " WHERE ";

			$i = 0;
			foreach ($filter as $key => $value) {
				if(++$i != $total) {
					$sql .= $this->db->escape($key) . "='" . $this->db->escape($value) . "' AND ";
				} else {
					$sql .= $this->db->escape($key) . "='" . $this->db->escape($value) . "'";
				}
			}
		}

		$query = $this->db->query($sql);

		$rules = array();
		if($query) {
			foreach ($query->rows as $key => $rule) {
				$rules[$rule['rules_id']] = array(
					'type' => $rule['type'],
					'rule' => unserialize($rule['rule'])
					);
			}
		}

		return $rules;
	}

	public function getFilterFields($filter = array()) {
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'advanced_product_filter';

		$total = count($filter);
		if($total > 0) {
			$sql .= " WHERE ";

			$i = 0;
			foreach ($filter as $key => $value) {
				if(++$i != $total) {
					$sql .= $this->db->escape($key) . "='" . $this->db->escape($value) . "' AND ";
				} else {
					$sql .= $this->db->escape($key) . "='" . $this->db->escape($value) . "'";
				}
			}
		}

		$sql .= " ORDER BY sort ASC";

		$query = $this->db->query($sql);

		$results = array();
		if($query->rows) {
			$this->load->model('tool/image');
			$empty_image = $this->model_tool_image->resize('no_image.png', 100, 100);

			foreach ($query->rows as $row) {
				$options = null;
				if($row['type'] == 'select' || $row['type'] == 'multiselect') {
					$options = unserialize($row['options']);

					if(isset($options['source']) && isset($options['key']) && isset($options['value'])) {
						$sql = "SELECT " . $options['key'] . " as 'key', " . $options['value'] . " as 'value' FROM " . DB_PREFIX . $options['source'] . " " . (isset($options['where']) ? $options['where'] : '');

						$option_query = $this->db->query($sql);

						unset($options);
						if($option_query->rows) {
							foreach ($option_query->rows as $option_row) {
								$options[$option_row['key']] = $option_row['value'];
							}
						}
					}
				} else if($row['type'] == 'category') {
					$option_query = $this->getOptionCategories();

					unset($options);
					if($option_query) {
						foreach ($option_query as $option_row) {
							$options[$option_row['category_id']] = $option_row['name'];
						}
					}
				}

				$results[$row['name']] = array(
						'name'			=> $row['name'],
						'type'			=> $row['type'],
						'entry_text'	=> $row['entry_text'],
						'options'		=> $options,
						'value'			=> $row['value'],
						'value_type'	=> $row['value_type'],
						'table_name'	=> $row['table_name'],
						'alias'			=> $row['alias'],
						'autocomplete'	=> (boolean)$row['autocomplete'],
						'inline_edit'	=> (boolean)$row['inline_edit'],
						'rules'			=> $row['rules'],
						'enable'		=> (boolean)$row['enable'],
						'show_column'	=> (boolean)$row['show_column'],
						'empty_image'	=> ($row['type'] == 'image') ? array('href' => $empty_image, 'filename' => 'no-image.png') : '',
					);
			}
		}

		return $results;
	}

	public function getOptionCategories() {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sql .= " GROUP BY cp.category_id ORDER BY name";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function updateProduct($id, $input, $value) {
		if(is_null($input)) {
			return false;
		}

		$table = $input->getDbTable();
		$column = $table . "." . $input->getDbColumnName();

		if($input->isTypeOf('category')) {
			if(!$this->db->query("DELETE FROM " . $table . " WHERE product_id = " . $id)) {
				return false;
			}

			foreach ($value as $category) {
				$this->db->query("INSERT INTO " . $table . " VALUES('" . $id . "','" . $this->db->escape($category). "')");
			}

			$this->load->model('catalog/category');
			return $input->getCategoriesList($value, $this->model_catalog_category);
		}

		/*if($input->getDbValueType() == 'integer') {
			$value = $value;
		} else if($input->getDbValueType() == 'string' || $input->getDbValueType() == 'date') {*/
		$value = $this->db->escape($value);
		//}

		$where = $table . ".product_id = " . $id;

		$sql = "UPDATE " . $table . " SET " . $column . " = '" . $value . "' WHERE " . $where;

		if(!$this->db->query($sql)) {
			return false;
		}

		return $input->isTypeOf('select') ? $input->getOptionText($value) : $value;
	}
}
?>
