<?php
class ModifyExcelModel extends CI_model{
	public function __construct(){
		parent::__construct();
		$this->_table = 'ci_modify_excel';
		$this->userData = logged_in_user_data();
	}

	public function getRecord($id = 0){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('id', $id);

		return $this->db->get()->row_array();
	}


	public function checkRecord($orderId = 0, $column = "", $value = ""){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('id', $orderId);
		$this->db->where($column, $value);

		return $this->db->get()->row_array();
	}

	/**
	 * @param int $orderId
	 * @return mixed
	 */
	public function getRecordByOrderId($orderId = 0){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('order_id', $orderId);

		return $this->db->get()->result_array();
	}

	/**
	 * @param int $orderId
	 * @return mixed
	 */
	public function getOneRecordByOrderId($orderId = 0){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('order_id', $orderId);

		return $this->db->get()->row_array();
	}

	/**
	 * @param array $data
	 * @return false|void
	 */
	public function add_edit($data = []){
		if (isset($data['id']) && is_numeric($data['id']) > 0) {
			$this->db->where('id', $data['id']);
			$update = $this->db->update($this->_table, $data);
			if ($update) {
				return $this->db->affected_rows();
			} else {
				return false;
			}
		} elseif (isset($data) && count($data) > 0) {
			$this->db->insert($this->_table, $data);
			return $this->db->insert_id();
		}
	}

	/**
	 * @param array $data
	 * @return mixed
	 */
	function delete($data = []){
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
		}
		return $this->db->delete($this->_table);
	}

	/**
	 * @param int $orderId
	 * @return mixed
	 */
	function deleteByOrderId($orderId = 0){
		$this->db->where('order_id', $orderId);
		return $this->db->delete($this->_table);
	}

}
?>