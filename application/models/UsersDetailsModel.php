<?php
class UsersDetailsModel extends CI_model{

	public function __construct(){
		parent::__construct();
		$this->_table = 'ci_user_details';
		$this->user_table = 'ci_users';
		$this->petowners_to_vetusers = 'ci_petowners_to_vetusers';
		$this->branches_table = 'ci_branches';
		$this->petowners_to_branches = 'ci_petowners_to_branches';
	}

	public function getRecordArray($id, $role_id = ""){
		if ($role_id == 2 || $role_id == 6 || $role_id == 7 || $role_id == 8 || $role_id == 9) {
			$this->db->select("*");
			$this->db->from($this->_table);
			$this->db->where('ci_user_details.user_id', $id);
		} elseif ($id != '' && $role_id == 3) {
			$this->db->select('ci_users.*,GROUP_CONCAT(name) AS name');
			$this->db->from($this->user_table);
			$this->db->where('ci_users.id IN(' . $id . ')');
		} else {
			$this->db->select('*');
			$this->db->from($this->user_table);
			$this->db->where('ci_users.id', $id);
		}
		return $this->db->get()->result_array();
	}

	public function getRecord($id, $role_id = ""){
		if ($role_id == 2 || $role_id == 6 || $role_id == 7 || $role_id == 8 || $role_id == 9) {
			$this->db->select("ci_user_details.*,GROUP_CONCAT(IFNULL(column_field ,'') ORDER BY ci_user_details.id separator '|') AS column_field,ci_users.name,ci_users.last_name,ci_users.email,ci_users.country,ci_users.phone_number,ci_users.managed_by_id,ci_users.invoiced_by");
			$this->db->from($this->_table);
			$this->db->join('ci_users', 'ci_user_details.user_id = ci_users.id');
			$this->db->where('ci_user_details.user_id', $id);
			$this->db->group_by('ci_user_details.user_id');
		} elseif ($id != '' && $role_id == 3) {
			$this->db->select('ci_users.*,GROUP_CONCAT(name) AS name');
			$this->db->from($this->user_table);
			$this->db->where('ci_users.id IN(' . $id . ')');
		} else {
			$this->db->select('*');
			$this->db->from($this->user_table);
			$this->db->where('ci_users.id', $id);
		}
		return $this->db->get()->row_array();
	}

	public function getBranchRecord($id){
		$this->db->select('*');
		$this->db->from($this->branches_table);
		$this->db->where('vet_user_id', $id);
		return $this->db->get()->result_array();
	}

	public function getColumnField($userData = []){
		$this->db->select("GROUP_CONCAT(IFNULL(column_field ,'') ORDER BY ci_user_details.id separator '|') AS column_field");
		$this->db->from($this->_table);
		$this->db->where('column_name IN(' . $userData['column_name'] . ')');
		$this->db->where('user_id', $userData['user_id']);
		return $this->db->get()->row_array();
	}

	public function getColumnFieldArray($userData = []){
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where('column_name IN(' . $userData['column_name'] . ')');
		$this->db->where('user_id', $userData['user_id']);
		return $this->db->get()->result_array();
	}

	public function getColumnAllArray($id){
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where('user_id', $id);
		return $this->db->get()->result_array();
	}

	function getselectedBranches($id){
		$this->db->select('GROUP_CONCAT(branch_id) AS branch_id');
		$this->db->from($this->petowners_to_branches);
		$this->db->where('pet_owner_id', $id);
		$this->db->group_by('ci_petowners_to_branches.pet_owner_id');
		return $this->db->get()->row_array();
	}

	public function add_edit($postUser = [], $postUserDetails = [], $branchDetails = []){
		$current_date = date("Y-m-d H:i:s");
		$update = $insert = '';
		if (isset($postUserDetails['id']) && is_numeric($postUserDetails['id']) > 0) {
			$this->db->select('column_name');
			$this->db->from($this->_table);
			$this->db->where('user_id', $postUserDetails['id']);
			$existing_fields =  $this->db->get()->result_array();
			$existing_fields_arr = [];
			foreach ($existing_fields as $fkey => $fval) {
				$existing_fields_arr[] = $fval['column_name'];
			}

			$details = $ins_details = [];
			foreach($postUserDetails as $key => $val){
				if($key != 'id'){
					if(in_array($key, $existing_fields_arr)){
						$detail = array(
							"column_name" => $key,
							"column_field" => $val,
							"updated_at" => $current_date
						);
						if (isset($postUserDetails['parent_id']) && $postUserDetails['parent_id'] != '') {
							$detail["parent_id"] = $postUserDetails['parent_id'];
						}
						$details[] = $detail;
					} else {
						$ins_detail = array(
							"user_id" => $postUserDetails['id'],
							"column_name" => $key,
							"column_field" => $val,
							"created_at" => $current_date
						);
						if (isset($postUserDetails['parent_id']) && $postUserDetails['parent_id'] != '') {
							$ins_detail["parent_id"] = $postUserDetails['parent_id'];
						}
						$ins_details[] = $ins_detail;
					}
				}
			}

			if (isset($postUser) && count($postUser) > 0) {
				$this->db->where('id', $postUserDetails['id']);
				$this->db->update($this->user_table, $postUser);
			}
			if (isset($postUser) && count($postUser) > 0) {
				$this->db->where('user_id', $postUserDetails['id']);
			} else {
				$this->db->where('id', $postUserDetails['id']);
			}
			$update = $this->db->update_batch($this->_table, $details, 'column_name');
			if (!empty($ins_details)) {
				$this->db->insert_batch($this->_table, $ins_details);
			}
		} else {
			if (isset($postUser) && count($postUser) > 0) {
				$this->db->insert($this->user_table, $postUser);
				$user_id = $this->db->insert_id();
			} else {
				$user_id = null;
			}

			$details = [];
			foreach ($postUserDetails as $key => $val) {
				if ($key != 'id') {
					$detail = array(
						"user_id" => $user_id,
						"column_name" => $key,
						"column_field" => $val,
						"created_at" => $current_date
					);
					if (isset($postUserDetails['parent_id']) && $postUserDetails['parent_id'] != '') {
						$detail["parent_id"] = $postUserDetails['parent_id'];
					}
					$details[] = $detail;
				}
			}
			$insert = $this->db->insert_batch($this->_table, $details);
		}

		if (isset($branchDetails) && count($branchDetails) > 0) {
			$branch = [];
			foreach ($branchDetails['name'] as $key => $value) {
				if ($branchDetails['name'][$key] != '') {
					$branch_id = $branchDetails['id'][$key];
					$branch = array(
						"tm_user_id" => isset($branchDetails['tm_user_id'][$key]) ? $branchDetails['tm_user_id'][$key] : NULL,
						"customer_number" => isset($branchDetails['customer_number'][$key]) ? $branchDetails['customer_number'][$key] : NULL,
						"name" => isset($branchDetails['name'][$key]) ? $branchDetails['name'][$key] : NULL,
						"address" => isset($branchDetails['address'][$key]) ? $branchDetails['address'][$key] : NULL,
						"address1" => isset($branchDetails['address1'][$key]) ? $branchDetails['address1'][$key] : NULL,
						"address2" => isset($branchDetails['address2'][$key]) ? $branchDetails['address2'][$key] : NULL,
						"address3" => isset($branchDetails['address3'][$key]) ? $branchDetails['address3'][$key] : NULL,
						"town_city" => isset($branchDetails['town_city'][$key]) ? $branchDetails['town_city'][$key] : NULL,
						"county" => isset($branchDetails['county'][$key]) ? $branchDetails['county'][$key] : NULL,
						"country" => isset($branchDetails['country'][$key]) ? $branchDetails['country'][$key] : NULL,
						"postcode" => isset($branchDetails['postcode'][$key]) ? $branchDetails['postcode'][$key] : NULL,
						"number" => isset($branchDetails['number'][$key]) ? $branchDetails['number'][$key] : NULL,
						"email" => isset($branchDetails['email'][$key]) ? $branchDetails['email'][$key] : NULL,
						"acc_contact" => isset($branchDetails['acc_contact'][$key]) ? $branchDetails['acc_contact'][$key] : NULL,
						"acc_email" => isset($branchDetails['acc_email'][$key]) ? $branchDetails['acc_email'][$key] : NULL,
						"acc_number" => isset($branchDetails['acc_number'][$key]) ? $branchDetails['acc_number'][$key] : NULL,
						"part_of_corpo" => isset($branchDetails['part_of_corpo'][$key]) ? $branchDetails['part_of_corpo'][$key] : NULL,
						"corpo_name" => isset($branchDetails['corpo_name'][$key]) ? $branchDetails['corpo_name'][$key] : NULL,
						"buying_group" => isset($branchDetails['buying_group'][$key]) ? $branchDetails['buying_group'][$key] : NULL,
						"group_name" => isset($branchDetails['group_name'][$key]) ? $branchDetails['group_name'][$key] : NULL,
						"ivc_clinic_number" => isset($branchDetails['ivc_clinic_number'][$key]) ? $branchDetails['ivc_clinic_number'][$key] : NULL,
					);

					if ($branch_id > 0) {
						$this->db->set('updated_by', $branchDetails['updated_by']);
						$this->db->set('updated_at', $branchDetails['updated_at']);
						$this->db->where('id', $branch_id);
						$branch_id = $this->db->update($this->branches_table, $branch);
					} else {
						$this->db->set('vet_user_id', $user_id);
						$this->db->set('created_by', $branchDetails['created_by']);
						$this->db->set('created_at', $branchDetails['created_at']);
						$branch_id = $this->db->insert($this->branches_table, $branch);
					}
				}
			}
		} else {
			$branch_id = null;
		}

		if (isset($branchDetails) && !empty($branchDetails) && $branchDetails['deleted_branch_id'] != '') {
			$this->db->where("id IN(" . $branchDetails['deleted_branch_id'] . ")");
			$this->db->delete($this->branches_table);
		}

		if ($update) {
			return true;
		} else if ($insert) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	function delete($data = []){
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
			return $this->db->delete($this->user_table);
		}
	}

	public function get_branch_dropdown($vetUserData = []){
		if (isset($vetUserData['vet_user_id']) && $vetUserData['vet_user_id'] != '') {
			$this->db->select('id,name,postcode');
			$this->db->from($this->branches_table);
			$this->db->where('vet_user_id IN(' . $vetUserData['vet_user_id'] . ')');
			return $this->db->get()->result_array();
		} else {
			return array();
		}
	}

	public function get_petowner_branch($petowner_id = '', $vetLab_ids = ''){
		if ($petowner_id != '') {
			$this->db->select('GROUP_CONCAT(branch_id) AS branch_id');
			$this->db->from($this->petowners_to_branches);
			$this->db->where('pet_owner_id', $petowner_id);
			$this->db->group_by('ci_petowners_to_branches.pet_owner_id');
			$result = $this->db->get()->row_array();
			if (!empty($result)) {
				$this->db->select('id,name,postcode');
				$this->db->from($this->branches_table);
				$this->db->where('id IN(' . $result['branch_id'] . ')');
				return $this->db->get()->result_array();
			} else if (isset($vetLab_ids) && $vetLab_ids['ids'] != '') {
				$this->db->select('id,name,postcode');
				$this->db->from($this->branches_table);
				$this->db->where('vet_user_id IN(' . $vetLab_ids['ids'] . ')');
				return $this->db->get()->result_array();
			} else {
				return array();
			}
		} else if (isset($vetLab_ids) && $vetLab_ids['ids'] != '') {
			$this->db->select('id,name,postcode');
			$this->db->from($this->branches_table);
			$this->db->where('vet_user_id IN(' . $vetLab_ids['ids'] . ')');
			return $this->db->get()->result_array();
		} else {
			return array();
		}
	}

	public function duplicate_tm($practice_id = '', $tm_id = ''){
		$total = '';
		if ($practice_id > 0) {
			$this->db->select('id');
			$this->db->from($this->branches_table);
			$this->db->where('tm_user_id', $tm_id);
			$this->db->where('vet_user_id !=', $practice_id);
			$total = $this->db->get()->num_rows();
		} else {
			$this->db->select('id');
			$this->db->from($this->branches_table);
			$this->db->where('tm_user_id', $tm_id);
			$total = $this->db->get()->num_rows();
		}
		return $total;
	}

	/* data table petsOweners functions */
	public function getPetsOwenerData($vet_user_id, $user_role, $user_id, $role){
		$this->__get_datatables_query($role, $user_id, $user_role, $vet_user_id);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');

		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
	}

	private function __get_datatables_query($role, $user_id, $user_role, $vet_user_id){
		$this->db->select('ci_users.name,ci_users.last_name,ci_users.email');
		$this->db->from($this->user_table);
		$this->db->join('ci_petowners_to_vetusers', 'ci_petowners_to_vetusers.pet_owner_id = ' . $this->user_table . '.id');
		$this->db->where('ci_petowners_to_vetusers.vet_user_id', $vet_user_id);
		$this->db->order_by('ci_users.id', 'ASC');
	}

	/* data table functions */
	public function getTableData($role, $user_id, $user_role){
		$this->_get_datatables_query($role, $user_id, $user_role);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
	}

	private function _get_datatables_query($role, $user_id, $user_role){
		$postData = $this->input->post();
		if ($user_role == '5' && $this->session->userdata('user_type') == 1){
			$this->db->select('column_field');
			$this->db->from($this->_table);
			$this->db->where('user_id',$user_id);
			$this->db->where('column_name LIKE','practices');
			$results = $this->db->get()->row_array();
			$result['vet_user_id'] = implode(",",json_decode($results['column_field']));
		}elseif ($user_role == '5' && $this->session->userdata('user_type') == 3){
			$this->db->select('column_field');
			$this->db->from($this->_table);
			$this->db->where('user_id',$user_id);
			$this->db->where('column_name LIKE','practices');
			$results = $this->db->get()->row_array();
			$result['vet_user_id'] = implode(",",json_decode($results['column_field']));
		}else{
			$this->db->select('GROUP_CONCAT(DISTINCT(vet_user_id)) as vet_user_id');
			$this->db->from($this->branches_table);
			$result = $this->db->get()->row_array();
		}
		if ($role == 2 || $role == 6 || $role == 7 || $role == 8 || $role == 9) {
			$this->db->select('ci_user_details.user_id AS id,GROUP_CONCAT(column_field) as column_field,ci_users.name, ci_user_details.column_field as postal_code, ci_users.last_name,ci_users.email');
			$this->db->from($this->user_table);
			$this->db->join($this->_table, 'ci_user_details.user_id = ci_users.id', 'left');
		} elseif ($role == 3) {
			$this->db->select('ci_users.*,GROUP_CONCAT(vet_user_id) as vet_user_id');
			$this->db->from($this->user_table);
			$this->db->join($this->petowners_to_vetusers, 'ci_petowners_to_vetusers.pet_owner_id = ci_users.id');
		} else {
			$this->db->select('*');
			$this->db->from($this->user_table);
		}

		if ($role == 2 || $role == 6) {
			parse_str($postData['formData'], $filterData);
		}
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		if (!empty($postData['search']['value']) && $role != 3) {
			$this->db->select('ci_user_details.user_id AS id,GROUP_CONCAT(column_field) as column_field, ci_user_details.column_field as postal_code, ci_users.name,ci_users.last_name,ci_users.email');
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->or_where('ci_users.email LIKE', $postData['search']['value']);
			$this->db->or_like('ci_user_details.column_field', $postData['search']['value']);
			$this->db->group_end();
		}elseif (!empty($postData['search']['value']) && $role == 3) {
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->or_where('ci_users.email LIKE', $postData['search']['value']);
		}

		if ($role == 2 || $role == 6) {
			if (!empty($filterData['managed_by_id'])){
				$this->db->where('(CONCAT(",", ci_users.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$filterData['managed_by_id']) .'),")');
			}
		}

		if ($role == 2 || $role == 6 || $role == 7 || $role == 8 || $role == 9) {
			if ($user_role == 5) {
				if (!empty($result) && $result['vet_user_id'] != '') {
					$this->db->where('ci_users.id IN(' . $result['vet_user_id'] . ')');
				} else {
					$this->db->where('ci_users.id', $result['vet_user_id']);
				}
			}
			$this->db->where('ci_users.role', $role);
			$this->db->group_by('ci_user_details.user_id');
			$this->db->order_by('ci_users.' . $columnName, $columnSortOrder);
			/* if ($role == 2) {
				$this->db->order_by('ci_users.id', 'ASC');
			} else {
				$this->db->order_by('ci_users.' . $columnName, $columnSortOrder);
			} */
		} elseif ($role == 3) {
			if ($user_role != 1) {
				$this->db->where('ci_petowners_to_vetusers.vet_user_id', $user_id);
			}
			$this->db->where('ci_users.role', $role);
			$this->db->group_by('ci_petowners_to_vetusers.pet_owner_id');
			$this->db->order_by('ci_users.' . $columnName, $columnSortOrder);
		} else {
			$this->db->where('ci_users.role', $role);
			$this->db->order_by('ci_users.' . $columnName, $columnSortOrder);
		}
		if($this->zones != ""){
			$this->db->where('CONCAT(",", ci_users.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .'),"');
		}
	}

	public function count_all(){
		return $this->db->count_all_results($this->_table);
	}

	public function count_filtered($role, $user_id, $user_role){
		$this->_get_datatables_query($role, $user_id, $user_role);
		$query = $this->db->get();
		return $query->num_rows();
	}
}