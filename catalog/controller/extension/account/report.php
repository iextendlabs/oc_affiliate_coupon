<?php 
class ControllerExtensionAccountReport extends Controller{
	public function index(){

		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/account', '', true);

			$this->response->redirect($this->url->link('affiliate/login', '', true));
		}

		$this->load->language('extension/account/report');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_report'] = $this->language->get('text_report');
		$data['text_completed_orders'] = $this->language->get('text_completed_orders');
		$data['text_shipped_orders'] = $this->language->get('text_shipped_orders');
		$data['text_cancelled_orders'] = $this->language->get('text_cancelled_orders');
		$data['text_pending_orders'] = $this->language->get('text_pending_orders');
		$data['text_processing_orders'] = $this->language->get('text_processing_orders');
		$data['text_filter'] = $this->language->get('text_filter');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_order_subtotal'] = $this->language->get('column_order_subtotal');
		$data['column_commission'] = $this->language->get('column_commission');
		$data['column_balance'] = $this->language->get('column_balance');

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
			'text' => $this->language->get('text_report'),
			'href' => $this->url->link('extension/account/report', '', true)
		);

		$this->load->model('extension/account/report');

		// get last 30 months
		$months=array();

		for ($i = 0; $i <= 30; $i++) {
    		$months[] = array('label'=> date("M-Y", strtotime( date( 'Y-m-01' )." -$i months")),
    				'month'=> date("m", strtotime( date( 'Y-m-01' )." -$i months")) , 
    				'year' => date("Y", strtotime( date( 'Y-m-01' )." -$i months")) );
		}
		
		$data['months'] = $months;

		$data['filter_report_action'] = $this->url->link('extension/account/report');

		$filter_month = '';
		$filter_year = '';

		if(isset($this->request->get['month'])){
			$filter_month = $this->request->get['month'];
			$filter_year = $this->request->get['year'];
		}

		$customer_id  = $this->affiliate->getId();
		$data['transactions'] = array();

		// order status ids for report
		$order_status_ids = array(
			'order_pending_id'   => 1,   // pending order
			'order_processing_id'=> 2,	 // processing order
			'order_shipped_id'	 => 3,   // shipped order
			'order_completed_id' => 5,   // completed order
			'order_cancelled_id' => 7    // cancelled order
		); 

		foreach ($order_status_ids as $order_status_id) {
			// get orders agains each order status id
			$orders = $this->model_extension_account_report->getCustomerCommissionTransactions($customer_id, $order_status_id, $filter_month, $filter_year);
			// if orders exists, then store them into transations
			if($orders){
				foreach ($orders as $order) {
				// check transaction type against order status id
				switch ($order_status_id) {
					case 1:
						$transactions_type = 'pending_transactions';
						$commission_type   = 'pending_orders_commission';
						break;
					case 2:
						$transactions_type = 'processing_transactions';
						$commission_type   = 'processing_orders_commission';
						break;
					case 3:
						$transactions_type = 'shipped_transactions';
						$commission_type   = 'shipped_orders_commission';
						break;
					case 5:
						$transactions_type = 'completed_transactions';
						$commission_type   = 'completed_orders_commission';
						break;
					case 7:
						$transactions_type = 'cancelled_transactions';
						$commission_type   = 'cancelled_orders_commission';
						break;
					default:
						$transactions_type = '';
						break;
				}

				
				$order_id = $order['order_id'];
				// get order subtotal
				$order_subtotal = $this->model_extension_account_report->getOrderSubTotal($order_id);
				// store orders into transactions array
				$data[$transactions_type][] = array(
					'amount'      => $this->currency->format($order['commission'], $this->config->get('config_currency')),
					'description' => 'Order ID #'.$order['order_id'],
					'date_added'  => date($this->language->get('date_format_short'), strtotime($order['date_added'])),
					'date_modified'  => date($this->language->get('date_format_short'), strtotime($order['date_modified'])),
					'order_total' => $this->currency->format($order_subtotal, $this->config->get('config_currency')),
					'order_id'    => $order_id 
				);
				
				// get total commission against corresponding order status id
				$data[$commission_type] = $this->currency->format($this->model_extension_account_report->getCustomerTransactionTotal($customer_id, $order_status_id), $this->config->get('config_currency'));

				}

			}
			
		}


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		$this->response->setOutput($this->load->view('extension/account/report', $data));
	}
}