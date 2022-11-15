<?php
class ControllerExtensionAccountCoupon extends Controller{
	public function index(){
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/account', '', true);

			$this->response->redirect($this->url->link('affiliate/login', '', true));
		}

		$this->load->language('extension/account/coupon');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_code'] = $this->language->get('column_code');
		$data['column_discount'] = $this->language->get('column_discount');
		$data['column_start_date'] = $this->language->get('column_start_date');
		$data['column_end_date'] = $this->language->get('column_end_date');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_commission'] = $this->language->get('column_commission');
		$data['column_commission_amount'] = $this->language->get('column_commission_amount');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);


		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_coupons'),
			'href' => $this->url->link('extension/account/coupon', '', true)
		);


		$affiliate_id = $this->affiliate->getId();

		$this->load->model('extension/account/coupon');

		$data['coupons'] = $this->model_extension_account_coupon->getAffiliateCoupons($affiliate_id);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/account/coupon_list', $data));
	}
}
