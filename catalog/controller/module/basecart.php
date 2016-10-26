<?php
class ControllerModuleBasecart extends Controller {
	protected function index($setting) {

		$this->language->load('module/basecart');
		$data['heading_title'] = $this->language->get('heading_title');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/basecart.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/basecart.tpl', $data);
		} else {
			return $this->load->view('default/template/module/basecart.tpl', $data);
		}
	}
}
