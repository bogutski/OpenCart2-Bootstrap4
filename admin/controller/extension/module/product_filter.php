<?php
/*
	Advanced Product list
	Author: Reemon
	Email: reemon@3dshops.cz
*/

require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/AdvancedFilter.php');
require_once(DIR_APPLICATION . 'controller/extension/module/advanced_filter/ProductTableBuilder.php');

class ControllerExtensionModuleProductFilter extends Controller {

	public function index() {
		$this->language->load('extension/module/product_filter');

		$this->getSetting();
	}

	protected function getSetting() {
		$this->load->model('extension/module/product_filter');
		$this->load->model('setting/setting');

		$modal = false;
		if((isset($this->request->get['mode']) && $this->request->get['mode'] == "modal") || (isset($this->request->post['mode']) && $this->request->post['mode'] == "modal")) {
			$modal = true;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if($modal) {
				$paypal = $this->model_setting_setting->getSetting("advanced_product_filter");
				$this->request->post['paypal'] = isset($paypal['advanced_product_filter_paypal']) ? $paypal['advanced_product_filter_paypal'] : true;
			}

			if($this->model_extension_module_product_filter->editFilterFields($this->request->post)) {
				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				$this->session->data['warning'] = $this->language->get('text_warning');
			}

			unset($this->session->data['advanced_filter']);

			if($modal) {
				$this->response->redirect($this->url->link('extension/module/product_filter/getList', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');

		$data['column_field_name'] = $this->language->get('column_field_name');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_value'] = $this->language->get('column_value');
		$data['column_options'] = $this->language->get('column_options');
		$data['column_entry_text'] = $this->language->get('column_entry_text');
		$data['column_rules'] = $this->language->get('column_rules');
		$data['column_autocomplete'] = $this->language->get('column_autocomplete');
		$data['column_inline_edit'] = $this->language->get('column_inline_edit');
		$data['column_enable'] = $this->language->get('column_enable');
		$data['column_show_column'] = $this->language->get('column_show_column');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$data['warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/product_filter', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/module/product_filter', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

		$data['fields'] = $this->model_extension_module_product_filter->getFilterFields();
		$data['rules'] = $this->model_extension_module_product_filter->getFilterRules();

		if($modal) {
			$data['action'] = $this->url->link('extension/module/product_filter', 'token=' . $this->session->data['token'].'&mode=modal', 'SSL');
			$view = 'extension/module/advanced_filter/product_filter_modal';
			$this->response->setOutput($this->load->view($view, $data));
			return;
		}

		$data['paypal_button'] = $this->getPayPalDonateButton(false);

		$paypal = $this->model_setting_setting->getSetting("advanced_product_filter");
		$data['paypal_enabled'] = isset($paypal['advanced_product_filter_paypal']) ? $paypal['advanced_product_filter_paypal'] : true;

		$view = 'extension/module/advanced_filter/product_filter';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($view, $data));
	}

	public function getList() {
		$this->language->load('catalog/product');
		$this->language->load('extension/module/product_filter');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'last' 		=> false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/product_filter/getList', 'token=' . $this->session->data['token'] . $this->filter_url, 'SSL'),
			'last' 		=> true
		);

		$data['add'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'], true);
		$data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'], true);

		$this->document->addStyle('view/stylesheet/advanced_filter/multiple-select.css');
		$this->document->addStyle('view/stylesheet/advanced_filter/advanced-filter.css');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['count_selected_text'] = $this->language->get('count_selected_text');
    	$data['select_placeholder_text'] = $this->language->get('select_placeholder_text');
    	$data['all_selected_text'] = $this->language->get('all_selected_text');
    	$data['button_filter_setting'] = $this->language->get('button_filter_setting');
    	$data['clear_all_filters_text'] = $this->language->get('clear_all_filters_text');
    	$data['show_filters_text'] = $this->language->get('show_filters_text');
    	$data['hide_filters_text'] = $this->language->get('hide_filters_text');
    	$data['asc_text'] = $this->language->get('asc_text');
    	$data['desc_text'] = $this->language->get('desc_text');
    	$data['text_confirm'] = $this->language->get('text_confirm');

    	$data['column_field_name'] = $this->language->get('column_field_name');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_value'] = $this->language->get('column_value');
		$data['column_options'] = $this->language->get('column_options');
		$data['column_entry_text'] = $this->language->get('column_entry_text');
		$data['column_rules'] = $this->language->get('column_rules');
		$data['column_autocomplete'] = $this->language->get('column_autocomplete');
		$data['column_inline_edit'] = $this->language->get('column_inline_edit');
		$data['column_enable'] = $this->language->get('column_enable');
		$data['column_show_column'] = $this->language->get('column_show_column');

		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		//unset($this->session->data['advanced_filter']);

		$advancedFilter = isset($this->session->data['advanced_filter']) ? $this->fixObject($this->session->data['advanced_filter']) : null;

		//if AdvancedFilter is not initialized yet (firstime on this page)
		$save = false;

		if(is_null($advancedFilter) || !$advancedFilter->checkApplication(DIR_APPLICATION)) {
			unset($advancedFilter);
			$advancedFilter = null;

			$advancedFilter = new AdvancedFilter(DIR_APPLICATION);

			/*Advanced filter model*/
			$this->load->model('extension/module/product_filter');
			/*Load default shown columns*/
			$advancedFilter->loadDefaultColumns($this->model_extension_module_product_filter);

			/*FilterInputs fields*/
			$inputs = $this->model_extension_module_product_filter->getFilterFields(array('enable' => 1));
			$columns = $this->model_extension_module_product_filter->getFilterFields(array('show_column' => 1));
			$rules = $this->model_extension_module_product_filter->getFilterRules();

			$advancedFilter->addInputs($inputs, $rules);
			$advancedFilter->addInputs($columns, $rules, true);

			$data['rules'] = $rules;
			$data['inputs'] = $inputs;

			$save = true;
		}

		$advancedFilter->refresh($this->registry);

		$data['advanced_filter'] = $advancedFilter;

		$page = $advancedFilter->getPage();
		$limit = $advancedFilter->getLimitPerPage();
		$sort = $advancedFilter->getSortBy();

		$filterData = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'page' => $page,
			'filter' => true,
			'sort' => $sort,
		);

		$data['limit_per_page'] = $limit;
		$data['product_table'] = $this->getProductTable($advancedFilter, $filterData);

		$data['paypal_button'] = $this->getPayPalDonateButton();

		$advancedFilter->clean();

		if($save) {
			unset($this->session->data['advanced_filter']);
			$this->session->data['advanced_filter'] = $advancedFilter;
		}

		$view = 'extension/module/advanced_filter/product_list.tpl';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($view, $data));
	}

	public function filterProducts() {
		if(!$this->request->post || !isset($this->session->data['advanced_filter'])) {
			$this->language->load('extension/module/product_filter');
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_filter_products'))));
			return;
		}

		$json = array();
		$advancedFilter = $this->fixObject($this->session->data['advanced_filter']);
		$advancedFilter->refresh($this->registry);

		$data = array(
			'start' => 0,
			'limit' => $this->config->get('config_admin_limit'),
			'page' => 1,
			'filter' => true,
			'sort' => array(),
		);

		if(isset($this->request->post['limit']) && is_numeric($this->request->post['limit']) && $this->request->post['limit'] > 0) {
			$data['limit'] =  (int)$this->request->post['limit'];
			$advancedFilter->setLimitPerPage($data['limit']);
		}

		if(isset($this->request->post['page']) && is_numeric($this->request->post['page'])) {
			$data['page'] = (int)$this->request->post['page'];
			$data['start'] = ($data['page'] - 1) * $data['limit'];
			$advancedFilter->setPage($data['page']);
		}

		if(isset($this->request->post['sort']) && isset($this->request->post['sort_type'])) {
			$data['sort'] = array(
				'type' => $this->request->post['sort_type'],
				'fields' => $this->request->post['sort'],
			);
		}
		$advancedFilter->setSortBy($data['sort']);

		unset($this->request->post['limit']);
		unset($this->request->post['page']);

		if(!$advancedFilter->updateInputs($this->request->post)) {
			$this->language->load('extension/module/product_filter');
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_update_filter_inputs'))));
			return;
		}

		$json = $this->getProductTable($advancedFilter, $data);

		//Save updated advancedFilter object to session for another use
		$advancedFilter->clean();
		$this->session->data['advanced_filter'] = $advancedFilter;

		$this->response->setOutput(json_encode($json));
	}

	public function changeShownColumns() {
		if(!$this->request->post || !isset($this->session->data['advanced_filter']) || !isset($this->request->post['columns_shown'])) {
			$this->language->load('extension/module/product_filter');
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_change_shown_columns'))));
			return;
		}

		$json = array();
		$advancedFilter = $this->fixObject($this->session->data['advanced_filter']);
		$advancedFilter->refresh($this->registry);

		$data = array(
			'start' => 0,
			'limit' => $this->config->get('config_admin_limit'),
			'page' => 1,
			'filter' => false,
			'sort' => array(),
		);


		if(isset($this->request->post['limit']) && is_numeric($this->request->post['limit']) && $this->request->post['limit'] > 0) {
			$data['limit'] =  (int)$this->request->post['limit'];
			$advancedFilter->setLimitPerPage($data['limit']);
		}

		if(isset($this->request->post['page']) && is_numeric($this->request->post['page'])) {
			$data['page'] = (int)$this->request->post['page'];
			$data['start'] = ($data['page'] - 1) * $data['limit'];
			$advancedFilter->setPage($data['page']);
		}

		if(isset($this->request->post['sort']) && isset($this->request->post['sort_type'])) {
			$data['sort'] = array(
				'type' => $this->request->post['sort_type'],
				'fields' => $this->request->post['sort'],
			);
		}
		$advancedFilter->setSortBy($data['sort']);

		unset($this->request->post['limit']);
		unset($this->request->post['page']);

		$advancedFilter->updateShownColumns($this->request->post['columns_shown']);

		$json = $this->getProductTable($advancedFilter, $data);

		//Save updated advancedFilter object to session for another use
		$advancedFilter->clean();
		$this->session->data['advanced_filter'] = $advancedFilter;

		$this->response->setOutput(json_encode($json));
	}

	/*Get HTML of current product table list*/
	private function getProductTable($advancedFilter, $data) {
		$this->language->load('catalog/product');

		/*Get filtered products*/
		$products = $this->getListOfProducts($advancedFilter,$data);

		/*Pagination*/
		$pagination = new Pagination();
		$pagination->total = $products['total'];
		$pagination->page = $data['page'];
		$pagination->limit = $data['limit'];
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = '{page}';
		$pagination = $pagination->render();

		$tableBuilder = new ProductTableBuilder();
		$tableBuilder->setFormAction($this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'], 'SSL'));

		$order_type = '';
		if(isset($data['sort']['type'])) {
			$order_type = $data['sort']['type'];
		}

		/*Dynamic header*/
		$shownColumns = $advancedFilter->getShownColumns();
		$shownColumns['action'] = null;
		foreach ($shownColumns as $key => $column) {
			if(!is_null($column)) {
				$tableBuilder->addHeaderColumn($column->getCaptionText(), $column->isInlineEdit(), $column->isSortable(), $order_type, $column->isSortChecked());
				continue;
			}
			$tableBuilder->addHeaderColumn($this->language->get('column_' . $key), false, false, '');
		}
		/*End of dynamic header, start table body*/

		$products = $products['products'];
		if ($products) {
		    foreach ($products as $product) {
		       	$tableBuilder->addSelectedColumn($product['product_id'], $product['selected']);

		        foreach ($shownColumns as $key => $column) {
		        	$inlineEdit = false;

		        	if(!is_null($column)) {
		        		$inlineEdit = $column->isInlineEdit();
		        	}

		        	$tableBuilder->addCustomColumn($inlineEdit, $key, isset($product[$key]) ? $product[$key] : null);
		        }

		    	$tableBuilder->endOfProductRecord();
		    }
		} else {
			//No results
			$tableBuilder->setNoResults(count($shownColumns) + 2, $this->language->get('text_no_results'));
		}

		return $tableBuilder->table() . '<div class="pagination">' . $pagination . '</div>';
	}

	/*Get list of product according to filter data*/
	private function getListOfProducts($advancedFilter, $data) {
		$this->load->model('catalog/product');
		$this->load->model('extension/module/product_filter');
		$this->load->model('tool/image');

		//Filter products
		$products = $advancedFilter->filterProducts($data['start'], $data['limit'], $data['sort'], false, !$data['filter']);

		//Get total number of products and information about products
		$total = $products['total'];
		$products = $products['products'];

		//Get shown columns
		$shownColumns = $advancedFilter->getShownColumns();

		//Returned list of products
		$listOfProducts = array();

		/*Get products*/
		foreach ($products as $product) {
			$action = array();

			//Available actions for product
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
			);

			//One record of product
			$temp = array(
				'product_id' => $product['product_id'],
				'selected'   => isset($this->request->post['selected']) && in_array($product['product_id'], $this->request->post['selected'])
			);

			//Save data about product according to shown columns
			foreach ($shownColumns as $key => $column) {
				$temp[$key] = isset($product[$key]) ? $product[$key] : '';

				//Special cases of columns
				if($column->isTypeOf('select')) {
					$temp[$key] = $column->getOptionText($product[$key]);
				}

				if($column->isTypeOf('category')) {
					$this->load->model('catalog/category');
					$temp[$key] = $column->getCategoriesListByProductId($product['product_id'], $this->model_catalog_product, $this->model_catalog_category);
				}

				if($key == 'image') {
					$temp[$key] = array('name' => $product[$key], 'filename' => '');
					if ($product[$key] && file_exists(DIR_IMAGE . $product[$key])) {
						$temp[$key]['filename'] = $this->model_tool_image->resize($product[$key], 40, 40);
					} else {
						$temp[$key]['filename'] = $this->model_tool_image->resize('no_image.png', 40, 40);
					}
				}

				/*if($key == 'special') {
					$temp['price'] = $product['price'];
					$temp['special'] = false;

					$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

					foreach ($product_specials  as $product_special) {
						if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
							$temp[$key] = $product_special['price'];
							break;
						}
					}
				}*/
			}

			//Add actions to the end of the record
			$temp['action'] = $action;

			//Save record to the list
			$listOfProducts[] = $temp;
		}

		//Return altered list of products and total number of filtered products
		return array('products' => $listOfProducts, 'total' => $total);
	}

	public function createEditField() {
		$this->language->load('extension/module/product_filter');

		if(!isset($this->session->data['advanced_filter']) || !isset($this->request->post['name']) || !isset($this->request->post['content'])) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_edit_field'))));
			return;
		}

		if(!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_product_permission'))));
			return;
		}

		$json = null;

		$advancedFilter = $this->fixObject($this->session->data['advanced_filter']);
		$advancedFilter->refresh($this->registry);

		$columnName = $this->request->post['name'];
		$content = $this->request->post['content'];

		if(!is_string($columnName) || empty($columnName) || strlen($columnName) > 300) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_field_name'))));
			return;
		}

		/*if(!is_numeric($content) && is_string($content) && (empty($content) || strlen($content) > 300)) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_content'))));
			return;
		}*/

		if(is_float($content)) {
			$content = (float)$content;
		}elseif(is_integer($content)) {
			$content = (int)$content;
		}

		$column = $advancedFilter->getColumn($columnName);

		if($column->isTypeOf('image')) {
			$this->load->model('tool/image');

			if ($content && file_exists(DIR_IMAGE . $content)) {
				$content = $this->model_tool_image->resize($content, 100, 100);
			} else {
				$content = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
		}

		$json = '<div id="edit-field" class="well"><div class="row"><div class="col-sm-12">' . $column->editHtmlInput('filter_' . $columnName . '_edit', $content) . '</div></div><div class="row"><div class="col-sm-6"><button id="field_save" class="js-inline-edit-button btn btn-sm btn-primary" style="width:100%">' . $this->language->get('text_save_field') . '</button></div><div class="col-sm-6"><button id="field_cancel" class="js-inline-edit-button btn btn-sm btn-default" style="width:100%">' . $this->language->get('text_cancel_field') . '</button></div></div></div>';

		if($column->isTypeOf('category')) {
			$json .= $column->getJsScript($this->session->data['token']);
		}

		$this->response->setOutput(json_encode($json));
	}

	public function updateEditField() {
		$this->language->load('extension/module/product_filter');

		if(!isset($this->session->data['advanced_filter']) || !isset($this->request->post['inputName']) || !isset($this->request->post['content']) || !isset($this->request->post['productId'])) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_update_field'))));
			return;
		}

		if(!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_product_permission'))));
			return;
		}

		$json = false;

		$advancedFilter = $this->fixObject($this->session->data['advanced_filter']);
		$advancedFilter->refresh($this->registry);

		$inputName = $this->request->post['inputName'];
		$content = $this->request->post['content'];
		$productId = substr($this->request->post['productId'], strpos($this->request->post['productId'], '_') + 1);

		if(!is_string($inputName) || empty($inputName) || strlen($inputName) > 300) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_input_name'))));
			return;
		}

		if(!is_numeric($content) && is_string($content) && (empty($content) || strlen($content) > 300)) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_content'))));
			return;
		}

		if(!is_numeric($productId) || $productId < 0) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_product_id'))));
			return;
		}

		$productId = (int)$productId;

		if(is_float($content)) {
			$content = (float)$content;
		}elseif(is_integer($content)) {
			$content = (int)$content;
		}

		//If user will change shown columns, cached products wont be returned but updated
		$advancedFilter->enableUpdateCachedProducts();

		$this->load->model('extension/module/product_filter');

		$column = $advancedFilter->getColumn($inputName);

		$json = $this->model_extension_module_product_filter->updateProduct($productId, $column, $content);

		$advancedFilter->clean();
		$this->session->data['advanced_filter'] = $advancedFilter;

		if($column->isTypeOf('image')) {
			$this->load->model('tool/image');

			$temp = array('name' => $json, 'filename' => '');
			if ($json && file_exists(DIR_IMAGE . $json)) {
				$temp['filename'] = $this->model_tool_image->resize($json, 40, 40);
			} else {
				$temp['filename'] = $this->model_tool_image->resize('no_image.png', 40, 40);
			}
			$json = $temp;
		}

		if(is_bool($json) && !$json) {
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_updating_product'))));
		} else {
			$json = ProductTableBuilder::getColumnRenderedValue($inputName, $json);
			$this->response->setOutput(json_encode((string)$json));
		}
	}

	public function autocomplete() {
		if(!isset($this->session->data['advanced_filter'])) {
			$this->language->load('extension/module/product_filter');
			$this->response->setOutput(json_encode(array("error" => $this->language->get('error_autocomplete'))));
			return;
		}

		$this->load->model('extension/module/product_filter');

		$json = array();

		$advancedFilter = $this->fixObject($this->session->data['advanced_filter']);
		$advancedFilter->refresh($this->registry);

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 20;
		}

		$inputs = $advancedFilter->getInputs();

		foreach ($inputs as $id => $input) {
			if(!isset($this->request->get[$id]) || !$input->isAutocomplete() || !$advancedFilter->updateInputs($this->request->get)) {
				continue;
			}

			$results = $advancedFilter->filterProducts(0, $limit, array(), true);

			$column = $input->getDbColumnName();

			foreach ($results['products'] as $product) {
				$json[] = array(
					'product_id' => $product['product_id'],
					$column => $product[$column]
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/product_filter')) {
			$this->session->data['warning'] = $this->language->get('error_permission');
			return false;
		}

		return true;
	}

	public function install() {
		$this->load->model('extension/module/product_filter');
		$this->model_extension_module_product_filter->createTable();
	}

	public function uninstall() {
		$this->load->model('extension/module/product_filter');

		unset($this->session->data['advanced_filter']);
		$this->cache->delete('cachedFilteredProducts');

		$this->model_extension_module_product_filter->dropTable();
	}

	//Dirty helper
	private function fixObject (&$object)
	{
		if (!is_object ($object) && gettype ($object) == 'object')
			return ($object = unserialize (serialize ($object)));
		return $object;
	}

	public function getPayPalDonateButton($condition = true) {
		$this->load->model("setting/setting");
		$data = $this->model_setting_setting->getSetting("advanced_product_filter");

		if($condition && (!isset($data['advanced_product_filter_paypal']) || (int)$data["advanced_product_filter_paypal"] == 0)) {
			return '';
		}

		return "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_top\"><input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\"><input type=\"hidden\" name=\"encrypted\" value=\"-----BEGIN PKCS7-----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-----END PKCS7-----\"><input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\"><img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\"></form>";
	}
}
?>
