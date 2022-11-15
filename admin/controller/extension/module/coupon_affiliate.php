<?php
class ControllerExtensionModuleCouponAffiliate extends Controller {
	private $error = array();
	
	public function install() {
		$this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."coupon_affiliate` (
          `coupon_affiliate_id` int(11) NOT NULL AUTO_INCREMENT,
		  `coupon_id` int(11) NOT NULL,
		  `affiliate_id` int(11) NOT NULL,
		  `affiliate_commission` double NOT NULL,
		  PRIMARY KEY (`coupon_affiliate_id`)
        )");
	}

	public function index() {
		
		$this->install();

		$this->load->language('extension/module/coupon_affiliate');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('coupon_affiliate', $this->request->post);
			$this->model_setting_setting->editSetting('commission_on_discount_product', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_commission_on_product'] = $this->language->get('entry_commission_on_product');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/coupon_affiliate', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/coupon_affiliate', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['coupon_affiliate_status'])) {
			$data['coupon_affiliate_status'] = $this->request->post['coupon_affiliate_status'];
		} else {
			$data['coupon_affiliate_status'] = $this->config->get('coupon_affiliate_status');
		}

		if (isset($this->request->post['commission_on_discount_product'])) {
			$data['commission_on_discount_product'] = $this->request->post['commission_on_discount_product'];
		} else {
			$data['commission_on_discount_product'] = $this->config->get('commission_on_discount_product');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/coupon_affiliate', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/coupon_affiliate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}