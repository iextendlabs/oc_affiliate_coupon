<?php
class ControllerExtensionAccountCoupon extends Controller{
	public function index(){
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/account/coupon');

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

		$affiliate_id = $this->customer->getId();

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
