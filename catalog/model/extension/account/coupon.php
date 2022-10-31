<?php 
class ModelExtensionAccountCoupon extends Model
{
	public function getAffiliateCoupons($affiliate_id) {
		$sql = "SELECT ca.*, SUM(ch.amount) as total, c.coupon_id, name, code, discount, date_start, date_end, status FROM " . DB_PREFIX . "coupon c JOIN ".DB_PREFIX."coupon_affiliate ca ON(c.coupon_id = ca.coupon_id) JOIN ".DB_PREFIX."coupon_history ch ON(c.coupon_id = ch.coupon_id) WHERE ca.affiliate_id = '".$affiliate_id."' GROUP BY ca.coupon_id;";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
}