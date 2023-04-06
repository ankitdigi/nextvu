<?php
class PetsModel extends CI_model{
    public function __construct(){
		parent::__construct();
		$this->_table = 'ci_pets';
		$this->user_table = 'ci_users';
		$this->breed_table = 'ci_breeds';
		$this->petowners_to_vetusers = 'ci_petowners_to_vetusers';
		$this->userData = logged_in_user_data();
	}

    public function getRecord($id = ""){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('id', $id);

		return $this->db->get()->row_array();
	}

    public function getRecordVet($vet_user_id = ""){
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('vet_user_id', $vet_user_id);

        return $this->db->get()->result_array();
    }

    public function getRecordLab($lab_id = ""){
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('lab_id', $lab_id);
        return $this->db->get()->result_array();
    }

    public function add_edit($petData = []){
		if (isset($petData['id']) && is_numeric($petData['id']) > 0) {
			$this->db->where('id', $petData['id']);
			$update =  $this->db->update($this->_table, $petData);
            if ($update) {
                return $this->db->affected_rows();
            } else {
                return false;
            }
        } else {
			if (isset($petData) && count($petData) > 0) {
				$this->db->insert($this->_table, $petData);
				if (!empty($petData['other_breed'])) {
                    $breeddata = array(
                        'species_id' => $petData['type'],
                        'name' => $petData['other_breed'],
                        'created_by' => $petData['created_by']
                    );
                    $this->db->insert($this->breed_table, $breeddata);
                }
                return $this->db->insert_id();
			} else {
				return $pet_id = null;
            }
		}
	}

	function delete($data = []){
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
        }
        return $this->db->delete($this->_table);
    }

	public function get_pets_dropdown($petOwnerData = []){
		$this->db->select('ci_pets.id,ci_pets.name,ci_species.name AS species_name,ci_breeds.name AS breeds_name');
		$this->db->from($this->_table);
		$this->db->join('ci_species', 'ci_species.id = ci_pets.type', 'left');
		$this->db->join('ci_breeds', 'ci_breeds.id = ci_pets.breed_id', 'left');
		if (isset($petOwnerData['is_petOwner'])){
			if ($petOwnerData['is_petOwner'] == true){
				$this->db->where('ci_pets.pet_owner_id', $petOwnerData['pet_owner_id']);
				if (isset($petOwnerData['vet_user_id']) && $petOwnerData['vet_user_id'] >0) {
					$this->db->where('ci_pets.vet_user_id', $petOwnerData['vet_user_id']);
				}
				if (isset($petOwnerData['pet_id']) && $petOwnerData['pet_id'] >0){
					$this->db->or_where('ci_pets.id', $petOwnerData['pet_id']);
				}
			}

			if ($petOwnerData['is_petOwner'] == false){
				$this->db->where('ci_pets.vet_user_id', $petOwnerData['pet_owner_id']);
				if (isset($petOwnerData['pet_id']) && $petOwnerData['pet_id'] >0){
					$this->db->or_where('ci_pets.id', $petOwnerData['pet_id']);
				}
			}
            if ($this->userData['role'] == '2') {
				$this->db->where('ci_pets.vet_user_id', $this->userData['user_id']);
            }
			if ($petOwnerData['stype'] != "" && $petOwnerData['stype'] > 0){
				$this->db->where('ci_pets.type', $petOwnerData['stype']);
			}
        }
        return $this->db->get()->result_array();
    }

    public function getTableData($user_id, $user_role){
        $this->_get_datatables_query($user_id, $user_role);
        $row = $this->input->post('start');
        $rowperpage = $this->input->post('length');
        $this->db->limit($rowperpage, $row);
        $query = $this->db->get()->result();
        return $query;
    }

    private function _get_datatables_query($user_id, $user_role){
        $postData = $this->input->post();
        if ($user_role == 2) {
            //get petowners of loggedin vet/lab
            $this->db->select('GROUP_CONCAT(pet_owner_id) AS pet_owner_id');
            $this->db->from($this->petowners_to_vetusers);
            $this->db->where('vet_user_id', $user_id);
            $this->db->group_by('ci_petowners_to_vetusers.vet_user_id');
            $result = $this->db->get()->row_array();
            if (!empty($result)) {
                $this->db->select('ci_pets.*,ci_pets.name AS pet_name,ci_users.last_name AS name');
                $this->db->from($this->_table);
                $this->db->join($this->user_table, 'ci_pets.pet_owner_id = ci_users.id', 'left');
                $this->db->where('ci_pets.pet_owner_id IN(' . $result['pet_owner_id'] . ')');
            } else {
                return array();
            }
        } else {
            $this->db->select('ci_pets.*,ci_pets.name AS pet_name,ci_users.last_name AS name');
            $this->db->from($this->_table);
            $this->db->join($this->user_table, 'ci_pets.pet_owner_id = ci_users.id', 'left');
            if ($user_role != 1) {
                $this->db->where('ci_pets.pet_owner_id', $user_id);
            }
        }

        if (!empty($postData['search']['value'])) {
            $this->db->where('ci_pets.name', $postData['search']['value']);
        }

        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by($columnName, $columnSortOrder);
    }

    public function count_all(){
        return $this->db->count_all_results($this->_table);
    }

    public function count_filtered($user_id, $user_role){
        $this->_get_datatables_query($user_id, $user_role);
        $query = $this->db->get();
        return $query->num_rows();
    }
}
?>