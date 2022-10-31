<?php 
class ModelExtensionAccountReport extends Model{
	
	public function getOrderSubTotal($order_id){
		$query = $this->db->query("SELECT value FROM ".DB_PREFIX."order_total WHERE order_id = '".$order_id."' AND code = 'sub_total' ");

		return $query->row['value'];
	}


	public function getCustomerCommissionTransactions($customer_id, $order_status_id, $filter_month='', $filter_year='') {
		
		$query = "SELECT order_id, order_status_id, affiliate_id , commission, date_added, date_modified FROM " . DB_PREFIX . "order  WHERE affiliate_id = '" . (int)$customer_id . "' AND order_status_id = '" . (int)$order_status_id . "' ";

		if(!empty($filter_month)){
			$query .= "AND  YEAR(date_added) = '".$filter_year."' AND MONTH(date_added) = '".$filter_month."'"; 
		}
		
		$query .= "ORDER BY order_id DESC";

		$sql = $this->db->query($query);

		return $sql->rows;
	}


	public function getCustomerTransactionTotal($customer_id, $order_status_id) {
		$query = $this->db->query("SELECT SUM(commission) AS total FROM " . DB_PREFIX . "order  WHERE affiliate_id = '" . (int)$customer_id . "' AND order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}

	
}