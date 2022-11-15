<?php
class ModelExtensionTotalCoupon extends Model {
	public function getCoupon($code) {
		$status = true;

		$coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE code = '" . $this->db->escape($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

		if ($coupon_query->num_rows) {
			if ($coupon_query->row['total'] > $this->cart->getSubTotal()) {
				$status = false;
			}

			$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
				$status = false;
			}

			if ($coupon_query->row['logged'] && !$this->customer->getId()) {
				$status = false;
			}

			if ($this->customer->getId()) {
				$coupon_history_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND ch.customer_id = '" . (int)$this->customer->getId() . "'");

				if ($coupon_query->row['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_customer'])) {
					$status = false;
				}
			}

			// Products
			$coupon_product_data = array();

			$coupon_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_product_query->rows as $product) {
				$coupon_product_data[] = $product['product_id'];
			}

			// Categories
			$coupon_category_data = array();

			$coupon_category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_category_query->rows as $category) {
				$coupon_category_data[] = $category['category_id'];
			}

			$product_data = array();

			if ($coupon_product_data || $coupon_category_data) {
				foreach ($this->cart->getProducts() as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$product_data[] = $product['product_id'];

						continue;
					}

					foreach ($coupon_category_data as $category_id) {
						$coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND category_id = '" . (int)$category_id . "'");

						if ($coupon_category_query->row['total']) {
							$product_data[] = $product['product_id'];

							continue;
						}
					}
				}

				if (!$product_data) {
					$status = false;
				}
			}
		} else {
			$status = false;
		}

		if ($status) {
			return array(
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'product'       => $product_data,
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added']
			);
		}
	}

	public function getTotal($total) {
		if (isset($this->session->data['coupon'])) {
			$this->load->language('extension/total/coupon');

			$coupon_info = $this->getCoupon($this->session->data['coupon']);

			if ($coupon_info) {
				$discount_total = 0;

				if (!$coupon_info['product']) {
					$sub_total = $this->cart->getSubTotal();
				} else {
					$sub_total = 0;

					foreach ($this->cart->getProducts() as $product) {
						if (in_array($product['product_id'], $coupon_info['product'])) {
							$sub_total += $product['total'];
						}
					}
				}

				if ($coupon_info['type'] == 'F') {
					$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
				}

				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;

					if (!$coupon_info['product']) {
						$status = true;
					} else {
						$status = in_array($product['product_id'], $coupon_info['product']);
					}

					if ($status) {
						if ($coupon_info['type'] == 'F') {
							$discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
						} elseif ($coupon_info['type'] == 'P') {
							$discount = $product['total'] / 100 * $coupon_info['discount'];
						}

						if ($product['tax_class_id']) {
							$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
								}
							}
						}
					}

					$discount_total += $discount;
				}

				if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
					if (!empty($this->session->data['shipping_method']['tax_class_id'])) {
						$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if ($tax_rate['type'] == 'P') {
								$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}

					$discount_total += $this->session->data['shipping_method']['cost'];
				}

				// If discount greater than total
				if ($discount_total > $total) {
					$discount_total = $total;
				}

				if ($discount_total > 0) {
					$total['totals'][] = array(
						'code'       => 'coupon',
						'title'      => sprintf($this->language->get('text_coupon'), $this->session->data['coupon']),
						'value'      => -$discount_total,
						'sort_order' => $this->config->get('coupon_sort_order')
					);

					$total['total'] -= $discount_total;
				}
			}
		}
	}

	public function confirm($order_info, $order_total) {
		$code = '';

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {
			$code = substr($order_total['title'], $start, $end - $start);
		}

		if ($code) {
			$coupon_info = $this->getCoupon($code);
			// get coupon affiliate commision
			$coupon_affiliate = $this->db->query("SELECT affiliate_id,affiliate_commission FROM ".DB_PREFIX."coupon_affiliate  WHERE coupon_id=".$coupon_info['coupon_id']);

			if($coupon_affiliate->num_rows){
				
				$affiliate_id = $coupon_affiliate->row['affiliate_id'];

				if($affiliate_id !=0){

					$sub_total_by_product = $this->db->query("select sum(op.total) as sum , c.discount from ".DB_PREFIX."coupon_product cp left join ".DB_PREFIX."order_product op on (cp.product_id = op.product_id) left join ".DB_PREFIX."coupon c on (cp.coupon_id = c.coupon_id) WHERE cp.coupon_id = '".$coupon_info['coupon_id']."' AND order_id = '".$order_info['order_id']."' ");

					$sub_total_by_category =  $this->db->query("select sum(op.total) as sum, c.discount from ".DB_PREFIX."coupon_category cc left join ".DB_PREFIX."product_to_category pc on (cc.category_id = pc.category_id) left join ".DB_PREFIX."order_product op on (pc.product_id = op.product_id) left join ".DB_PREFIX."coupon c on (cc.coupon_id = c.coupon_id) WHERE cc.coupon_id = '".$coupon_info['coupon_id']."' AND order_id = '".$order_info['order_id']."' ");

					$sub_total = $this->db->query("SELECT value FROM ".DB_PREFIX."order_total WHERE code = 'sub_total' AND order_id = '".$order_info['order_id']."' ");

					$sub_discount = $this->db->query("SELECT value FROM ".DB_PREFIX."order_total WHERE code = 'coupon' AND order_id = '".$order_info['order_id']."' ");
					
					if($this->config->get('commission_on_discount_product') == 1){
						if($sub_total_by_product->row['sum'] != null){
							$discount = ($sub_total_by_product->row['sum'] * $sub_total_by_product->row['discount']) / 100;
							$commission = ($coupon_affiliate->row['affiliate_commission'] / 100 ) * ($sub_total_by_product->row['sum'] - $discount) ;
						}elseif($sub_total_by_category->row['sum'] != null){
							$discount = ($sub_total_by_category->row['sum'] * $sub_total_by_category->row['discount']) / 100;
							$commission = ($coupon_affiliate->row['affiliate_commission'] / 100 ) * ($sub_total_by_category->row['sum'] - $discount) ;
						}else{
							$commission = ($coupon_affiliate->row['affiliate_commission'] / 100 ) * ($sub_total->row['value'] - abs($sub_discount->row['value'] )) ;
						}
					}else{
						$commission = ($coupon_affiliate->row['affiliate_commission'] / 100 ) * ($sub_total->row['value'] - abs($sub_discount->row['value'] )) ;
					}
 
					$this->db->query("UPDATE ".DB_PREFIX."order SET affiliate_id = '" . (int)$affiliate_id . "',commission = '" . (float)$commission . "' WHERE order_id = '". $order_info['order_id']."'");

				}
			}
			if ($coupon_info) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_id = '" . (int)$coupon_info['coupon_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', customer_id = '" . (int)$order_info['customer_id'] . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
			} else {
				return $this->config->get('config_fraud_status_id');
			}
		}
	}

	public function unconfirm($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_history` WHERE order_id = '" . (int)$order_id . "'");
	}
	
}
