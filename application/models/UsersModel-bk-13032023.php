<?php
class UsersModel extends CI_model{

    public function __construct() { 
		parent::__construct();
		$this->_table = 'ci_users';
		$this->user_details = 'ci_user_details';
		$this->petowners_to_vetusers = 'ci_petowners_to_vetusers';
		$this->branches_table = 'ci_branches';
		$this->petowners_to_branches = 'ci_petowners_to_branches';
    }

    public function getRecord($id="",$role_id="") {
		if($role_id==5){
			$this->db->select("ci_user_details.*,GROUP_CONCAT(IFNULL(column_field,'') separator '|') AS column_field,ci_users.name,ci_users.email,ci_users.country,ci_users.user_type,ci_users.managed_by_id");
			$this->db->from($this->user_details);
			$this->db->join('ci_users', 'ci_user_details.user_id = ci_users.id');
			$this->db->where('ci_user_details.user_id', $id);
			$this->db->group_by('ci_user_details.user_id');
			$result = $this->db->get()->row_array();
			if(empty($result)){
				$this->db->select('*');
				$this->db->from($this->_table);
				$this->db->where('id', $id);
				return $this->db->get()->row_array();
			}else{
				return $result;
			}
		}else{
			$this->db->select('*');
			$this->db->from($this->_table);
			$this->db->where('id', $id);
			return $this->db->get()->row_array(); 
		}
	}

	function getRecordAll($role_id='',$user_id='',$user_role='',$column_name='',$user_country='') {
		$this->userData = logged_in_user_data();
		if($this->userData['role'] == '5' && $this->session->userdata('user_type') == '1'){
			$this->db->select('column_field');
			$this->db->from($this->user_details);
			$this->db->where('user_id',$this->user_id);
			$this->db->where('column_name LIKE','practices');
			$results = $this->db->get()->row_array();
			$vetData = implode(",",json_decode($results['column_field']));
		}elseif($this->userData['role'] == '5' && $this->session->userdata('user_type') == '3'){
			$this->db->select('column_field');
			$this->db->from($this->user_details);
			$this->db->where('user_id',$this->user_id);
			$this->db->where('column_name LIKE','practices');
			$results = $this->db->get()->row_array();
			$vetData = implode(",",json_decode($results['column_field']));
		}
		if($user_role==2){
			$this->db->select('GROUP_CONCAT(pet_owner_id) AS pet_owner_id');
			$this->db->from($this->petowners_to_vetusers);
			$this->db->where('vet_user_id', $user_id);
			$this->db->group_by('ci_petowners_to_vetusers.vet_user_id');
			$result = $this->db->get()->row_array();
		}
		if($user_role==5){
			$this->db->select('GROUP_CONCAT(column_field) AS column_field');
			$this->db->from($this->user_details);
			$this->db->where('user_id', $user_id);
			$this->db->where('column_name',$column_name);
			$this->db->group_by('ci_user_details.user_id');
			$tm_result = $this->db->get()->row_array();
		}

		if($role_id=='2'){
			$this->db->select("ci_users.id,ci_users.name, ci_users.last_name, ci_users.email, ci_users.role, ci_users.country, ci_users.managed_by_id, ci_users.preferred_language, ci_users.post_code, ci_users.phone_number, ci_users.is_admin, ci_users.user_type, GROUP_CONCAT(IF(column_name = 'address_3', column_field, NULL)) AS postcode,GROUP_CONCAT(IF(column_name = 'account_ref', column_field, NULL)) AS account_ref");
			$this->db->from($this->_table);
			$this->db->join($this->user_details, 'ci_user_details.user_id = ci_users.id', 'left');
		}else{
			$this->db->select("ci_users.id,ci_users.name, ci_users.last_name, ci_users.email, ci_users.role, ci_users.country, ci_users.managed_by_id, ci_users.preferred_language, ci_users.post_code, ci_users.phone_number, ci_users.is_admin, ci_users.user_type");
			$this->db->from($this->_table);
		}

		$this->db->where('ci_users.role!=1');
		if($role_id != ''){
			$this->db->where('ci_users.role',$role_id);
		}
		if($role_id=='2'){
			if (!empty($vetData) && $vetData != ''){
			$this->db->where('ci_users.id IN('. $vetData .')');
			}
		}
		if($role_id=='5'){
			$this->db->where('ci_users.user_type','3');
		}
		if(!empty($result)){
			$this->db->where('ci_users.id IN('.$result['pet_owner_id'].')');
		}
		if(!empty($tm_result['column_field']) && $tm_result['column_field']!=''){
			$str_data = implode(",",json_decode($tm_result['column_field']));
			$this->db->where('ci_users.id IN('.$str_data.')');
		}
		if($this->zones != ""){
			$this->db->where('CONCAT(",", ci_users.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .'),"');
		}
		if($role_id=='2'){
			$this->db->group_by('ci_users.id'); 
		}
		return $this->db->get()->result_array();    
    }

	function getDeliveryPractices($role_id) {
		$this->db->select("ci_users.id,ci_users.name, ci_users.last_name, ci_users.email, ci_users.role, ci_users.country, ci_users.managed_by_id, ci_users.preferred_language, ci_users.post_code, ci_users.phone_number, ci_users.is_admin, ci_users.user_type, GROUP_CONCAT(IF(column_name = 'address_3', column_field, NULL)) AS postcode,GROUP_CONCAT(IF(column_name = 'account_ref', column_field, NULL)) AS account_ref");
		$this->db->from($this->_table);
		$this->db->join($this->user_details, 'ci_user_details.user_id = ci_users.id', 'left');
		$this->db->where('ci_users.role!=1');
		$this->db->where('ci_users.role',$role_id);
		$this->db->group_by('ci_users.id');
		if($this->zones != ""){
			$this->db->where('CONCAT(",", ci_users.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .'),"');
		}
		return $this->db->get()->result_array();    
    }

    function getPracticeLab() {
		$this->db->select("ci_users.*,column_field AS postcode");
		$this->db->from($this->_table);
		$this->db->join($this->user_details, 'ci_user_details.user_id = ci_users.id', 'left');
		$this->db->where('(ci_users.role=2 OR ci_users.role=6)');
		$this->db->where('ci_user_details.column_name = IF(ci_users.role=2, "address_3", "post_code")');
		$this->db->group_by('ci_user_details.user_id'); 

		return $this->db->get()->result_array();
    }

    public function practiceLabCountry($practice_id){
		$this->db->select('user.id,country.name');
		$this->db->from('ci_users AS user');
		$this->db->join('ci_staff_countries AS country', 'country.id=user.country','left');
		$this->db->where('user.id',$practice_id);  
		return $this->db->get()->row_array();
    }

    function getvatLabUsers($id) {
		$this->db->select('GROUP_CONCAT(vet_user_id) AS ids,user_type');
		$this->db->from($this->petowners_to_vetusers);
		$this->db->where('pet_owner_id',$id);
		$this->db->group_by('ci_petowners_to_vetusers.pet_owner_id');

		return $this->db->get()->row_array();
    }

    function validate($email,$password){
		$this->db->where('email',$email);
		$this->db->where('password',$password);
		$result = $this->db->get($this->_table,1);
		return $result;
	}

	public function getUser($data = []) {
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->select('*');
			$this->db->from($this->_table);
			$this->db->where('id', $data['id']);

			return $this->db->get()->row();    
		}
	}

	public function add_edit($postUser = []) {
		if(isset($postUser) && count($postUser)>0){
			$this->db->where('id', $postUser['id']);
			$update =  $this->db->update($this->_table,$postUser);
		}

		//echo $this->db->last_query(); exit;
		if($update){
			return $this->db->affected_rows();
		}else{
			return false;
		}
	}

	public function petOwners_add_edit($postUser = [],$petowners_to_vetusers = [],$user_id = '',$user_role = '',$is_from_modal=0){
		if(isset($postUser['id']) && $postUser['id']>0){
			$this->db->where('id', $postUser['id']);
			$update =  $this->db->update($this->_table,$postUser);
            //if coming from modal then no need to update 
			if( $is_from_modal!=1 ){
				//ci_petowners_to_vetusers
				if($user_role==1){
					$this->db->where('pet_owner_id', $postUser['id']);
					$this->db->delete($this->petowners_to_vetusers);

					//insert into ci_petowners_to_vetusers
					$pet_to_vet = [];
					foreach($petowners_to_vetusers['parent_id'] as $key => $val){
						$pet_to_vet['pet_owner_id'] = $postUser['id'];
						$pet_to_vet['vet_user_id'] = $val;
						$pet_to_vet['user_type'] = $petowners_to_vetusers['user_type'];

						$this->db->insert($this->petowners_to_vetusers,$pet_to_vet);
					}
				}//end of if

				//ci_petowners_to_branches
				$this->db->where('pet_owner_id', $postUser['id']);
				$this->db->delete($this->petowners_to_branches);

				//insert into ci_petowners_to_branches
				if(isset($petowners_to_vetusers['branch_id']) && $petowners_to_vetusers['branch_id']!=''){
					$pet_to_branch = [];
					foreach($petowners_to_vetusers['branch_id'] as $b_key => $b_val){
						$pet_to_branch['pet_owner_id'] = $postUser['id'];
						$pet_to_branch['branch_id'] = $b_val;
						$this->db->insert($this->petowners_to_branches,$pet_to_branch);
					}
				}
			}// is_modal

			//echo $this->db->last_query(); exit;
			if($update){
				//return $this->db->affected_rows();
				return true;
			}else{
				return false;
			}
		}else{
			$insert = $this->db->insert($this->_table,$postUser);
			$petOwner_id = $this->db->insert_id();
			if($petOwner_id){
				//insert into ci_petowners_to_vetusers
				$pet_to_vet = [];
				foreach($petowners_to_vetusers['parent_id'] as $key => $val){
					$pet_to_vet['pet_owner_id'] = $petOwner_id;
					$pet_to_vet['vet_user_id'] = $val;
					$pet_to_vet['user_type'] = $petowners_to_vetusers['user_type'];

					$this->db->insert($this->petowners_to_vetusers,$pet_to_vet);
				}

				//insert into ci_petowners_to_branches
				if(isset($petowners_to_vetusers['branch_id']) && $petowners_to_vetusers['branch_id']!=''){
					$pet_to_branch = [];
					foreach($petowners_to_vetusers['branch_id'] as $b_key => $b_val){
						if($b_val!=''){
							$pet_to_branch['pet_owner_id'] = $petOwner_id;
							$pet_to_branch['branch_id'] = $b_val;
							$this->db->insert($this->petowners_to_branches,$pet_to_branch);
						}
					}
				}
			}//if petOwner_id

			if($insert){
				return $petOwner_id;
			}else{
				return false;
			}
		}
	}

	public function get_petOwner_dropdown($vetUserData = []) {
		if(!empty($vetUserData)){
			if(isset($vetUserData['branch_id']) && $vetUserData['branch_id']>0){
				$this->db->select('GROUP_CONCAT(pet_owner_id) AS pet_owner_id');
				$this->db->from($this->petowners_to_branches);
				$this->db->where('branch_id', $vetUserData['branch_id']);
				$this->db->group_by('ci_petowners_to_branches.branch_id');
				$pToBResult = $this->db->get()->row_array(); 
				if(!empty($pToBResult)){
					if(isset($vetUserData['order_form_dp']) && $vetUserData['order_form_dp']!='yes'){
						$this->db->select('ci_users.id as id,ci_users.name,ci_users.last_name,ci_users.email,ci_users.post_code,GROUP_CONCAT(ci_pets.id) AS pet_id');
						$this->db->from($this->_table);
						$this->db->join('ci_pets', 'ci_pets.pet_owner_id = ci_users.id');
						if( isset($vetUserData['pet_owner_id']) && $vetUserData['pet_owner_id'] > 0){
						$this->db->where('ci_users.id', $vetUserData['pet_owner_id']);
						}
						$this->db->where('ci_users.id IN('.$pToBResult['pet_owner_id'].')');
						$this->db->group_by('ci_users.id');
						return $this->db->get()->result_array();
					}else{
						$this->db->select('ci_users.id as id,ci_users.name,ci_users.last_name,ci_users.email,ci_users.post_code');
						$this->db->from($this->_table);
						if( isset($vetUserData['pet_owner_id']) && $vetUserData['pet_owner_id'] > 0){
						$this->db->where('ci_users.id', $vetUserData['pet_owner_id']);
						}
						$this->db->where('ci_users.id IN('.$pToBResult['pet_owner_id'].')');
						$this->db->group_by('ci_users.id');
						return $this->db->get()->result_array();
					}
				}
			}

			if( isset($vetUserData['vet_user_id']) && $vetUserData['vet_user_id'] > 0){
				$this->db->select('GROUP_CONCAT(pet_owner_id) AS pet_owner_id');
				$this->db->from($this->petowners_to_vetusers);
				$this->db->where('vet_user_id', $vetUserData['vet_user_id']);
				if( isset($vetUserData['pet_owner_id']) && $vetUserData['pet_owner_id'] > 0){
					$this->db->or_where('pet_owner_id', $vetUserData['pet_owner_id']);
				}
				$this->db->group_by('ci_petowners_to_vetusers.vet_user_id');
				$pToVResult = $this->db->get()->row_array();
				if(!empty($pToVResult)){
					$this->db->select('id,name,last_name,post_code');
					$this->db->from($this->_table);
					$this->db->where('ci_users.id IN('.$pToVResult['pet_owner_id'].')');
					return $this->db->get()->result_array();
				}
			}
		}else{
			return array();
		}
    }

    public function getTableData($role=""){
		$this->_get_datatables_query($role);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
    }

    private function _get_datatables_query($role){
		$postData = $this->input->post();
		$this->db->select('ci_users.*');
		$this->db->from($this->_table);
		if($role!=''){
			$this->db->where('ci_users.role', $role);
		}

		if(!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->group_end();
		}
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		//$this->db->order_by('ci_users.id', 'DESC');
		$this->db->order_by('ci_users.'.$columnName, $columnSortOrder);
	}

    public function count_all(){
        return $this->db->count_all_results($this->_table);
    }

    public function count_filtered($role){
		$this->_get_datatables_query($role);
		$query = $this->db->get();
		return $query->num_rows();
    }

	public function getAdminTableData($role=""){
		$this->_get_admin_datatables_query($role);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();

		return $query;
	}

    private function _get_admin_datatables_query($role){
		$postData = $this->input->post();
		$this->db->select('ci_users.*');
		$this->db->from($this->_table);
		if($role!=''){
			$this->db->where('ci_users.role IN('.$role.')');
		}
		if(!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->group_end();
		}
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		// $this->db->order_by('ci_users.id', 'DESC');
		$this->db->order_by('ci_users.'.$columnName, $columnSortOrder);
	}

    public function count_admin_filtered($role){
        $this->_get_admin_datatables_query($role);
        $query = $this->db->get();
        return $query->num_rows();
    }

	function admin_user_delete($data = []) {
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
			return $this->db->delete($this->_table);
		}
	}

	public function admin_add_edit($usersData = []) {
		if (isset($usersData['id']) && is_numeric($usersData['id'])>0) {
            $this->db->where('id', $usersData['id']);
            $update =  $this->db->update($this->_table,$usersData);
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }
        }else{
            if(isset($usersData) && count($usersData)>0){
                $this->db->insert($this->_table,$usersData);
                return $user_id = $this->db->insert_id();
            }else{
                return $user_id = null; 
            }
        }
    }

	public function tmUsers_add_edit($userData = [], $postUserDetails = []) {
		$current_date = date("Y-m-d H:i:s");
		$update = $insert = '';
		if (isset($postUserDetails['id']) && is_numeric($postUserDetails['id'])>0) {
			$details = [];
			foreach($postUserDetails as $key => $val){
				if($key!= 'id'){
					$detail = array(
						"column_name" => $key,
						"column_field" => $val,
						"updated_at" => $current_date
					);
					$details[] = $detail;
				}//key
			}

			//update user data
			$this->db->where('id', $userData['id']);
			$update =  $this->db->update($this->_table,$userData);

			//update user details
			$this->db->where('user_id', $postUserDetails['id']);
			$update = $this->db->update_batch($this->user_details,$details,'column_name');
			if($update){
				return true;
			}else{
				return false;
			}
		}else{
			if(isset($userData) && count($userData)>0){
				$this->db->insert($this->_table,$userData);
				$user_id = $this->db->insert_id();
			}else{
				$user_id = null; 
			}

			//add vat/lab details
			$details = [];
			foreach($postUserDetails as $key => $val){
				if($key!= 'id'){
					$detail = array(
						"user_id" => $user_id,
						"column_name" => $key,
						"column_field" => $val,
						"created_at" => $current_date
					);
					$details[] = $detail;
				}//key
			}//foreach
			$insert = $this->db->insert_batch($this->user_details,$details);
			if($insert){
				return $user_id;
			}else{
				return false;    
			}
		}
	}

	public function updateTMUsers_practice($id, $practices = []) {
		$practiceArr = json_decode($practices);
		if(!empty($practiceArr)){
			$this->db->set('column_field', NULL);
			$this->db->where('column_field', '["'.$id.'"]');
			$this->db->update($this->user_details);
			foreach($practiceArr as $val){
				$this->db->select('*');
				$this->db->from($this->user_details);
				$this->db->where('user_id', $val);
				$this->db->where('column_name', 'tm_user_id');
				$existing_fields =  $this->db->get()->result_array();
				$details = $ins_details = [];
				if(count($existing_fields) == 0){
					$ins_detail = array(
						"user_id" => $val,
						"column_name" => 'tm_user_id',
						"column_field" => '["'.$id.'"]',
						"created_at" => date("Y-m-d H:i:s")
					);
					$ins_details[] = $ins_detail;
				}else{
					$detail = array(
						"column_name" => 'tm_user_id',
						"column_field" => '["'.$id.'"]',
						"updated_at" => date("Y-m-d H:i:s")
					);
					$details[] = $detail;
				}
				if (!empty($details)) {
					$this->db->where('user_id', $val);
					$this->db->update_batch($this->user_details, $details, 'column_name');
				}
				if (!empty($ins_details)) {
					$this->db->insert_batch($this->user_details, $ins_details);
				}
			}
			return true;
		}else{
			$this->db->set('column_field', NULL);
			$this->db->where('column_field', '["'.$id.'"]');
			$this->db->update($this->user_details);
			return true;
		}
    }

    function tm_users_delete($data = []) {
      if (isset($data['id']) && $data['id'] != '') {
          //set null to branch table
          /* $this->db->set('tm_user_id', NULL);
          $this->db->where('tm_user_id', $data['id']);
          $update =  $this->db->update($this->branches_table); */

          $this->db->where('id', $data['id']);
          return $this->db->delete($this->_table);
      }
    }

	public function getTmUsersTableData($role=""){
		$this->_get_tm_users_datatables_query($role);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
    }

	private function _get_tm_users_datatables_query($role){
		$postData = $this->input->post();
		$sql = "SELECT * FROM ci_user_details WHERE column_name IN('practices','branches','labs','lab_branches','corporates','corporate_branches') AND user_id = '". $this->user_id ."'";
		$responce = $this->db->query($sql);
		$userIds = $responce->result_array();
		if(!empty($userIds)){
			$LabDetails = array_column($userIds, 'column_field', 'column_name');
			$practices = !empty($LabDetails['practices']) ? json_decode($LabDetails['practices']) : 0;
			$branches = !empty($LabDetails['branches']) ? json_decode($LabDetails['branches']) : 0;
			$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : 0;

			$this->db->select('ci_users.*');
			$this->db->from($this->_table);
			$this->db->join($this->user_details, 'ci_user_details.user_id = ci_users.id', 'left');
			if($practices != 0){
				$this->db->where('ci_user_details.column_field LIKE','%'.$practices[0].'%');
			}elseif($branches != 0){
				$this->db->where('ci_user_details.column_field LIKE','%'.$branches[0].'%');
			}elseif($labs != 0){
				$this->db->where('ci_user_details.column_field LIKE','%'.$labs[0].'%');
			}
			if($role!=''){
				$this->db->where('ci_users.role', $role);
			}
			if(!empty($postData['search']['value'])) {
				$this->db->group_start();
				$this->db->like('ci_users.name', $postData['search']['value']);
				$this->db->or_like('ci_users.last_name', $postData['search']['value']);
				$this->db->group_end();
			}
		}else{
			$this->db->select('ci_users.*');
			$this->db->from($this->_table);
			if($role!=''){
			  $this->db->where('ci_users.role', $role);
			}
			
			if(!empty($postData['search']['value'])) {
			  $this->db->group_start();
			  $this->db->like('ci_users.name', $postData['search']['value']);
			  $this->db->or_like('ci_users.last_name', $postData['search']['value']);
			  $this->db->group_end();
			}
		}
		if($this->zones != ""){
			$this->db->where('CONCAT(",", ci_users.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .'),"');
		}
		$this->db->where('ci_users.user_type','3');
		if(!empty($postData['order'][0]['column']))
		{
			$columnIndex = $postData['order'][0]['column'];
	        $columnName = $postData['columns'][$columnIndex]['data'];
	        $columnSortOrder = $postData['order'][0]['dir'];
	        $this->db->order_by('ci_users.'.$columnName, $columnSortOrder);
		}
        
    }

	public function count_tm_users_filtered($role){
        $this->_get_tm_users_datatables_query($role);
        $query = $this->db->get();
        return $query->num_rows();
    }

	public function customerUsers_add_edit($userData = [], $postUserDetails = []) {
		$current_date = date("Y-m-d H:i:s");
		$update = $insert = '';
		if (isset($postUserDetails['id']) && is_numeric($postUserDetails['id'])>0) {
			//update user details
			$details = [];
			foreach($postUserDetails as $key => $val){
				if($key!= 'id'){
					$detail = array(
						"column_name" => $key,
						"column_field" => $val,
						"updated_at" => $current_date
					);
					$details[] = $detail;
				}//key
			}

			//update user data
			$this->db->where('id', $userData['id']);
			$update =  $this->db->update($this->_table,$userData);

			//update user details
			$this->db->where('user_id', $postUserDetails['id']);
			$update = $this->db->update_batch($this->user_details,$details,'column_name');
			  if($update){
				  return true;
			  }else{
				  return false;
			  }
		}else{
          if(isset($userData) && count($userData)>0){
              $this->db->insert($this->_table,$userData);
              $user_id = $this->db->insert_id();
          }else{
              $user_id = null; 
          }

          //add vat/lab details
          $details = [];
          foreach($postUserDetails as $key => $val){
              
              if($key!= 'id'){
                  $detail = array(
                      "user_id" => $user_id,
                      "column_name" => $key,
                      "column_field" => $val,
                      "created_at" => $current_date
                  );

                  $details[] = $detail;
              }//key
              
          }//foreach

          //print_r($details); exit;
          $insert = $this->db->insert_batch($this->user_details,$details);

          if($insert){
            return true;
          }else{
            return false;    
          }

      }
    }

    function customer_users_delete($data = []) {
      if (isset($data['id']) && $data['id'] != '') {
          //set null to branch table
          /* $this->db->set('tm_user_id', NULL);
          $this->db->where('tm_user_id', $data['id']);
          $update =  $this->db->update($this->branches_table); */

          $this->db->where('id', $data['id']);
          return $this->db->delete($this->_table);
      }
    }

	public function getCustomerUsersTableData($role=""){
		$this->_get_customer_users_datatables_query($role);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
    }

	private function _get_customer_users_datatables_query($role){
		$postData = $this->input->post();
		$sql = "SELECT * FROM ci_user_details WHERE column_name IN('practices','branches','labs','lab_branches','corporates','corporate_branches') AND user_id = '". $this->user_id ."'";
		$responce = $this->db->query($sql);
		$userIds = $responce->result_array();
		if(!empty($userIds)){
			$LabDetails = array_column($userIds, 'column_field', 'column_name');
			$practices = !empty($LabDetails['practices']) ? json_decode($LabDetails['practices']) : 0;
			$branches = !empty($LabDetails['branches']) ? json_decode($LabDetails['branches']) : 0;
			$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : 0;

			$this->db->select('ci_users.*');
			$this->db->from($this->_table);
			$this->db->join($this->user_details, 'ci_user_details.user_id = ci_users.id', 'left');
			if($practices != 0){
				$this->db->where('ci_user_details.column_field LIKE','%'.$practices[0].'%');
			}elseif($branches != 0){
				$this->db->where('ci_user_details.column_field LIKE','%'.$branches[0].'%');
			}elseif($labs != 0){
				$this->db->where('ci_user_details.column_field LIKE','%'.$labs[0].'%');
			}
			if($role!=''){
				$this->db->where('ci_users.role', $role);
			}
			if(!empty($postData['search']['value'])) {
				$this->db->group_start();
				$this->db->like('ci_users.name', $postData['search']['value']);
				$this->db->or_like('ci_users.last_name', $postData['search']['value']);
				$this->db->group_end();
			}
		}else{
			$this->db->select('ci_users.*');
			$this->db->from($this->_table);
			if($role!=''){
			  $this->db->where('ci_users.role', $role);
			}
			
			if(!empty($postData['search']['value'])) {
			  $this->db->group_start();
			  $this->db->like('ci_users.name', $postData['search']['value']);
			  $this->db->or_like('ci_users.last_name', $postData['search']['value']);
			  $this->db->group_end();
			}
		}
		$this->db->where('ci_users.user_type IN(1,2)');
		if($this->zones != ""){
			$this->db->where('CONCAT(",", ci_users.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .'),"');
		}
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by('ci_users.'.$columnName, $columnSortOrder);
    }

	public function count_customer_users_filtered($role){
        $this->_get_customer_users_datatables_query($role);
        $query = $this->db->get();
        return $query->num_rows();
    }

	/* Start LIMS Users Functions */
	public function getLIMSTableData($role=""){
		$this->_get_lims_datatables_query($role);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();

		return $query;
	}

    private function _get_lims_datatables_query($role){
		$postData = $this->input->post();
		$this->db->select('ci_users.*');
		$this->db->from($this->_table);
		if($role!=''){
			$this->db->where('ci_users.role IN('.$role.')');
		}
		if(!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->group_end();
		}
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		// $this->db->order_by('ci_users.id', 'DESC');
		$this->db->order_by('ci_users.'.$columnName, $columnSortOrder);
	}

    public function count_lims_filtered($role){
        $this->_get_lims_datatables_query($role);
        $query = $this->db->get();
        return $query->num_rows();
    }

	function lims_user_delete($data = []) {
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
			return $this->db->delete($this->_table);
		}
	}

	public function lims_add_edit($usersData = []) {
		if (isset($usersData['id']) && is_numeric($usersData['id'])>0) {
            $this->db->where('id', $usersData['id']);
            $update =  $this->db->update($this->_table,$usersData);
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }
        }else{
            if(isset($usersData) && count($usersData)>0){
                $this->db->insert($this->_table,$usersData);
                return $user_id = $this->db->insert_id();
            }else{
                return $user_id = null; 
            }
        }
    }
	/* End LIMS Users Functions */

	/* Start Country Admin Users Functions */
	function getCountryUsersRecord($user_id='',$role_id='') {
		$this->userData = logged_in_user_data();
		$this->db->select("ci_users.*");
		$this->db->from($this->_table);
		$this->db->where('ci_users.id', $user_id);
		if($role_id != ''){
			$this->db->where('ci_users.role',$role_id);
		}
		return $this->db->get()->row_array();    
    }

	public function countryUsers_add_edit($userData = [],$id='') {
		$current_date = date("Y-m-d H:i:s");
		$update = $insert = '';
		if (isset($id) && is_numeric($id)>0) {
			$this->db->where('id', $id);
			$update =  $this->db->update($this->_table,$userData);
			if($update){
				return true;
			}else{
				return false;
			}
		}else{
			if(isset($userData) && count($userData)>0){
				$this->db->insert($this->_table,$userData);
				$user_id = $this->db->insert_id();
			}else{
				$user_id = null; 
			}
			if($user_id){
				return true;
			}else{
				return false;
			}
		}
	}

    function country_users_delete($data = []) {
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
			return $this->db->delete($this->_table);
		}
	}

	public function getCountryUsersTableData($role=""){
		$this->_get_country_users_datatables_query($role);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
    }

	private function _get_country_users_datatables_query($role){
		$postData = $this->input->post();
		$this->db->select('ci_users.*');
		$this->db->from($this->_table);
		if($role!=''){
			$this->db->where('ci_users.role', $role);
		}

		if($this->zones != ""){
			$this->db->where('CONCAT(",", ci_users.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .'),"');
		}

		if(!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->or_like('ci_users.email', $postData['search']['value']);
			$this->db->group_end();
		}
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by('ci_users.'.$columnName, $columnSortOrder);
    }

	public function country_users_count_all(){
		$this->db->where('role', 11);
        return $this->db->count_all_results($this->_table);
    }

	public function count_country_users_filtered($role){
        $this->_get_country_users_datatables_query($role);
        $query = $this->db->get();
        return $query->num_rows();
    }
	/* End Country Admin Users Functions */

	public function getPracticeAccountRef($practice_id){
		$this->db->select('column_field');
		$this->db->from('ci_user_details');
		$this->db->where('user_id',$practice_id);
		$this->db->where('column_name','account_ref');
		return $this->db->get()->row();
    }

}