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
			$this->model_setting_setting->editSetting('module_coupon_affiliate', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/coupon_affiliate', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/coupon_affiliate', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_coupon_affiliate_status'])) {
			$data['module_coupon_affiliate_status'] = $this->request->post['module_coupon_affiliate_status'];
		} else {
			$data['module_coupon_affiliate_status'] = $this->config->get('module_coupon_affiliate_status');
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