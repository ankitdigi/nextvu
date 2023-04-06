<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class UsersDetails extends CI_Controller {
  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
        $this->user_role = $this->session->userdata('role');
		$this->zones = $this->session->userdata('managed_by_id');
        $this->load->model('UsersDetailsModel');
        $this->load->model('UsersModel');
        $this->load->model('OrdersModel');
        $this->load->model('PetsModel');
        $this->load->model('PracticeModel');
        $this->load->model('StaffCountriesModel');
		$this->load->model('StaffMembersModel');
        $this->_data['countries'] = $this->StaffCountriesModel->getRecordAll();
		$this->_data['staff_members'] = $this->StaffMembersModel->getManagedbyRecordAll();
		$this->load->library('excel');
    }

	function getPracticeUsers(){
		$this->db->select('ci_user_details.user_id AS id,GROUP_CONCAT(column_field) as column_field,ci_users.name, ci_user_details.column_field as postal_code, ci_users.last_name,ci_users.email');
		$this->db->from('ci_users');
		$this->db->join('ci_user_details', 'ci_user_details.user_id = ci_users.id', 'left');
		$this->db->where('ci_users.role', 2);
		$this->db->group_by('ci_user_details.user_id');
		$this->db->order_by('ci_users.id', 'ASC');
		$VetLabUsers = $this->db->get()->result();
		if(!empty($VetLabUsers)){
			$details = [];
            foreach ($VetLabUsers as $key => $value) {
				$ins_detail = array(
					"user_id" => $value->id,
					"column_name" => 'vat_applicable',
					"column_field" => 1,
					"created_at" => date("Y-m-d H:i:s")
				);
				$details[] = $ins_detail;
			}
		}
	}

    function vetLabUsers(){
		$this->load->view('usersDetails/vetLabUsers/index');
    }

    function petOwners(){
        $this->load->view('usersDetails/petOwners/index');
    }

    function labs(){
        $this->load->view('usersDetails/labs/index');
    }

    function corporates(){
        $this->load->view('usersDetails/corporates/index');
    }

    function buying_groups(){
        $this->load->view('usersDetails/buyingGroup/index');
    }

    function referrals(){
        $this->load->view('usersDetails/referrals/index');
    }

	function vlu_getTableData(){
		$role = 2;
		$VetLabUsers = $this->UsersDetailsModel->getTableData($role,$this->user_id,$this->user_role);
		if(!empty($VetLabUsers)){
            foreach ($VetLabUsers as $key => $value) {
                $VetLabUsers[$key]->name = $value->name;
                $VetLabUsers[$key]->email = $value->email;
				$refDatas = $this->UsersDetailsModel->getColumnAllArray($value->id);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
                $VetLabUsers[$key]->account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : NULL;
                $VetLabUsers[$key]->tax_code = !empty($refDatas['tax_code']) ? $refDatas['tax_code'] : NULL;
                $VetLabUsers[$key]->address_1 = !empty($refDatas['address_1']) ? $refDatas['address_1'] : NULL;
                $VetLabUsers[$key]->address_2 = !empty($refDatas['address_2']) ? $refDatas['address_2'] : NULL;
                $VetLabUsers[$key]->address_3 = !empty($refDatas['address_3']) ? $refDatas['address_3'] : NULL;
                $VetLabUsers[$key]->vat_reg = !empty($refDatas['vat_reg']) ? $refDatas['vat_reg'] : NULL;
                $VetLabUsers[$key]->country_code = !empty($refDatas['country_code']) ? $refDatas['country_code'] : NULL;
                $VetLabUsers[$key]->comment = !empty($refDatas['comment']) ? $refDatas['comment'] : NULL;
				$VetLabUsers[$key]->vat_applicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
            }
        }

        $total = $this->UsersDetailsModel->count_all();
        $totalFiltered = $this->UsersDetailsModel->count_filtered($role,$this->user_id,$this->user_role);
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $VetLabUsers;
        echo json_encode($ajax); exit();
    }

    function petOwner_getTableData(){
		$role = 3;
		$petOwners = $this->UsersDetailsModel->getTableData($role,$this->user_id,$this->user_role); 
		if(!empty($petOwners)){
			foreach ($petOwners as $key => $value) {
				$vetLabUserData = $this->UsersDetailsModel->getRecord($value->vet_user_id,$role);
				$petOwners[$key]->name = $value->name." ".$value->last_name;
				$petOwners[$key]->email = $value->email;
				$petOwners[$key]->vetlab_user = $vetLabUserData['name'];
			}
		}
        $total = $this->UsersDetailsModel->count_all();
        $totalFiltered = $this->UsersDetailsModel->count_filtered($role,$this->user_id,$this->user_role);

        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $petOwners;
        echo json_encode($ajax); exit();
    }

    function pets_getTableData(){
		$role = 4;
		$pets = $this->UsersDetailsModel->getTableData($role,$this->user_id,$this->user_role); 
		if(!empty($pets)){
			foreach ($pets as $key => $value) {
				$customerData = $this->UsersDetailsModel->getRecord($value->parent_id);
				$column_field = explode(',',$value->column_field);

				$pets[$key]->pet_owner = $customerData['name'];
				$pets[$key]->pet_comment = $column_field[0];
			}
		}
        $total = $this->UsersDetailsModel->count_all();
        $role = 4;
        $totalFiltered = $this->UsersDetailsModel->count_filtered($role,$this->user_id,$this->user_role);

        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $pets;
        echo json_encode($ajax); exit();
    }

    function vlu_addEdit($id= ''){
        $postUser = [];
        $postUserDetails = [];
        $branchDetails = [];
        $this->_data['data'] = [];
        $this->_data['branches'] = [];
  		$this->_data['id'] = $id;
        $role_id = 2;
        $tm_role_id = '5';
        $this->_data['tmUsers'] = $this->UsersModel->getRecordAll($tm_role_id);
        $this->_data['corporates'] = $this->UsersModel->getRecordAll("7");
        $this->_data['buying_groups'] = $this->UsersModel->getRecordAll("9");
        $this->_data['labs'] = $this->UsersModel->getRecordAll("6");
        $this->_data['referrals'] = $this->UsersModel->getRecordAll("8");
        $data = $this->UsersDetailsModel->getRecord($id,$role_id);
        $branch_data = $this->UsersDetailsModel->getBranchRecord($id);
  		if ($this->input->post('submit')) {
            $is_email_unique = "";
            if(!empty($branch_data)){
                $current_tm_user_id = $branch_data[0]['tm_user_id'];
            }
            if(!empty($data)){
                $current_email = !empty($data['email'])?$data['email']:'';
                if( $this->input->post('email') != $current_email ){
                    $is_email_unique = "is_unique[ci_users.email]";
                }
            }

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('usersDetails/vetLabUsers/add_edit','',TRUE);
            }else{
				if(is_numeric($id)>0){
					if(!empty($this->input->post('tm_user_id')) && $this->input->post('tm_user_id')[0]!=''){
						$refDatas = $this->UsersDetailsModel->getColumnAllArray($id);
						$refDatas = array_column($refDatas, 'column_field', 'column_name');
						$tm_userID = !empty($refDatas['tm_user_id']) ? $refDatas['tm_user_id'] : NULL;
						if(!empty($tm_userID) && $tm_userID != '[""]'){
							$tmuserArr = json_decode($tm_userID);
							$tmDatas = $this->UsersDetailsModel->getColumnAllArray($tmuserArr[0]);
							$tmDatas = array_column($tmDatas, 'column_field', 'column_name');
							$practiceIDs = !empty($tmDatas['practices']) ? $tmDatas['practices'] : NULL;
							if(!empty($practiceIDs) && $practiceIDs != '[""]'){
								$newpracticeIDs = [];
								foreach(json_decode($practiceIDs) as $pval){
									if($pval != $id){
										$newpracticeIDs[] = $pval;
									}
								}
								$newpracticeIDs[] = 
								$newtmuserArr = json_encode($newpracticeIDs);
								$this->db->where('column_name','practices');
								$this->db->where('user_id',$tmuserArr[0]);
								$this->db->update("ci_user_details", array("column_field" => $newtmuserArr));
							}
						}
					}
				}

                //user post data
                $postUser['name'] = $this->input->post('name');
                $postUser['last_name'] = $this->input->post('last_name');
                $postUser['email'] = $this->input->post('email');
                if($this->input->post('password')){
                    $postUser['password'] = md5($this->input->post('password'));
                }
                $postUser['country'] = $this->input->post('country');
                $postUser['phone_number'] = $this->input->post('phone_number');
                $postUser['role'] = 2;
				$postUser['managed_by_id'] = !empty($this->input->post('managed_by_id'))?implode(",",$this->input->post('managed_by_id')):'';
				$postUser['invoiced_by'] = !empty($this->input->post('invoiced_by'))?$this->input->post('invoiced_by'):'';

                $postUserDetails['id'] = $id;
                $postUserDetails['address_1'] = $this->input->post('address_1');
                $postUserDetails['address_2'] = $this->input->post('address_2');
                $postUserDetails['address_3'] = $this->input->post('address_3');
                $postUserDetails['account_ref'] = $this->input->post('account_ref');
				$postUserDetails['uk_sage_code'] = !empty($this->input->post('uk_sage_code'))?$this->input->post('uk_sage_code'):NULL;
                $postUserDetails['tax_code'] = $this->input->post('tax_code');
                $postUserDetails['vat_reg'] = $this->input->post('vat_reg');
                $postUserDetails['country_code'] = $this->input->post('country_code');
                $postUserDetails['comment'] = $this->input->post('comment');
                $postUserDetails['corporates'] = ( !empty($this->input->post('corporates')) && $this->input->post('corporates')[0]!='') ? json_encode($this->input->post('corporates')) : NULL;
                $postUserDetails['labs'] = ( !empty($this->input->post('labs')) && $this->input->post('labs')[0]!='') ? json_encode($this->input->post('labs')) : NULL;
                $postUserDetails['referrals'] = ( !empty($this->input->post('referrals')) && $this->input->post('referrals')[0]!='') ? json_encode($this->input->post('referrals')) : NULL;
                $postUserDetails['rcds_number'] = $this->input->post('rcds_number');
                $postUserDetails['add_1'] = $this->input->post('add_1');
                $postUserDetails['add_2'] = $this->input->post('add_2');
                $postUserDetails['add_3'] = $this->input->post('add_3');
                $postUserDetails['add_4'] = $this->input->post('add_4');
                $postUserDetails['order_can_send_to'] = $this->input->post('order_can_send_to');
                $postUserDetails['odelivery_address'] = ( $this->input->post('odelivery_address')!='' ) ? $this->input->post('odelivery_address') : NULL;
                $postUserDetails['opostal_code'] = ( $this->input->post('opostal_code')!='' ) ? $this->input->post('opostal_code') : NULL;
                $postUserDetails['ocity'] = ( $this->input->post('ocity')!='' ) ? $this->input->post('ocity') : NULL;
                $postUserDetails['ocountry'] = ( $this->input->post('ocountry')!='' ) ? $this->input->post('ocountry') : NULL;
                $postUserDetails['buying_groups'] = ( !empty($this->input->post('buying_groups')) && $this->input->post('buying_groups')[0]!='') ? json_encode($this->input->post('buying_groups')) : NULL;
				$postUserDetails['vat_applicable'] = !empty($this->input->post('vat_applicable'))?$this->input->post('vat_applicable'):0;
				$postUserDetails['tm_user_id'] = ( !empty($this->input->post('tm_user_id')) && $this->input->post('tm_user_id')[0]!='') ? '["'.$this->input->post('tm_user_id')[0].'"]' : NULL;
				$postUserDetails['labsuite_entidad_code'] = !empty($this->input->post('labsuite_entidad_code'))?$this->input->post('labsuite_entidad_code'):NULL;
				$postUserDetails['sage_account'] = !empty($this->input->post('sage_account'))?$this->input->post('sage_account'):NULL;
				$postUserDetails['intercompany'] = !empty($this->input->post('intercompany'))?$this->input->post('intercompany'):NULL;
				$postUserDetails['monthly_invoice'] = !empty($this->input->post('monthly_invoice'))?$this->input->post('monthly_invoice'):NULL;

                //branch post data
                $part_of_corpo = [];
                $buying_group = [];
                $total_count = count($this->input->post('branch_name'));
                for($i=1; $i<=$total_count; $i++){
                    $part_of_corpo[] = ($this->input->post('branch_part_of_corpo_'.$i)!='') ? '1' : '0';  
                    $buying_group[] = ($this->input->post('branch_buying_group_'.$i)!='') ? '1' : '0';
                }
                
                $branchDetails['vet_user_id'] = $id;
                $branchDetails['id'] = $this->input->post('branch_id');
                $branchDetails['tm_user_id'] = $this->input->post('tm_user_id');
                $branchDetails['customer_number'] = $this->input->post('customer_number');
                $branchDetails['name'] = $this->input->post('branch_name');
                $branchDetails['address'] = $this->input->post('branch_add');
                $branchDetails['address1'] = $this->input->post('branch_add1');
                $branchDetails['address2'] = $this->input->post('branch_add2');
                $branchDetails['address3'] = $this->input->post('branch_add3');
                $branchDetails['town_city'] = $this->input->post('branch_town_city');
                $branchDetails['county'] = $this->input->post('branch_county');
                $branchDetails['country'] = $this->input->post('branch_country');
                $branchDetails['postcode'] = $this->input->post('branch_postcode');
                $branchDetails['number'] = $this->input->post('branch_number');
                $branchDetails['email'] = $this->input->post('branch_email');
                $branchDetails['acc_contact'] = $this->input->post('branch_acc_contact');
                $branchDetails['acc_email'] = $this->input->post('branch_acc_email');
                $branchDetails['acc_number'] = $this->input->post('branch_acc_number');
                $branchDetails['part_of_corpo'] = $part_of_corpo;
                $branchDetails['corpo_name'] = $this->input->post('branch_corpo_name');
                $branchDetails['buying_group'] = $buying_group;
                $branchDetails['group_name'] = $this->input->post('branch_group_name');
                $branchDetails['ivc_clinic_number'] = $this->input->post('ivc_clinic_number');
                $branchDetails['created_by'] = $this->user_id;
                $branchDetails['created_at'] = date("Y-m-d H:i:s");
                $branchDetails['updated_by'] = $this->user_id;
                $branchDetails['updated_at'] = date("Y-m-d H:i:s");
                $branchDetails['deleted_branch_id'] = $this->input->post('deleted_branch_id');
                if(is_numeric($id)>0){
                    $postUser['updated_at'] = date("Y-m-d H:i:s");
                    $postUser['updated_by'] = $this->user_id;
					if ($upid = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails,$branchDetails)) {
						if(!empty($this->input->post('tm_user_id')) && $this->input->post('tm_user_id')[0]!=''){
							$tmDatas = $this->UsersDetailsModel->getColumnAllArray($this->input->post('tm_user_id')[0]);
							$tmDatas = array_column($tmDatas, 'column_field', 'column_name');
							$practiceIDs = !empty($tmDatas['practices']) ? $tmDatas['practices'] : NULL;
							if(!empty($practiceIDs) && $practiceIDs != '[""]'){
								$newpracticeIDs = [];
								foreach(json_decode($practiceIDs) as $pval){
									$newpracticeIDs[] = $pval;
								}
								$newpracticeIDs[] = $id;
								$newtmuserArr = json_encode($newpracticeIDs);
								$this->db->where('column_name','practices');
								$this->db->where('user_id',$this->input->post('tm_user_id')[0]);
								$this->db->update("ci_user_details", array("column_field" => $newtmuserArr));
							}else{
								$newpracticeIDs[] = $id;
								$newtmuserArr = json_encode($newpracticeIDs);
								$this->db->where('column_name','practices');
								$this->db->where('user_id',$this->input->post('tm_user_id')[0]);
								$this->db->update("ci_user_details", array("column_field" => $newtmuserArr));
							}
						}
                        if( $this->user_role=='2' ){
                            $this->session->set_flashdata('success','Profile has been updated successfully.');
                            redirect('vet_lab_users/edit/'.$this->user_id);
                        }else{
                            $this->session->set_flashdata('success','User data has been updated successfully.');
                            redirect('usersDetails/vetLabUsers');
                        }
                    }
                }else{
                    $postUser['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails,$branchDetails)) {
                        $this->session->set_flashdata('success','User data has been added successfully.');
                        redirect('usersDetails/vetLabUsers');
                    }
                }
            }
        }

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
				$refDatas = $this->UsersDetailsModel->getColumnAllArray($id);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$data['address_1'] = !empty($refDatas['address_1']) ? $refDatas['address_1'] : NULL;
				$data['address_2'] = !empty($refDatas['address_2']) ? $refDatas['address_2'] : NULL;
				$data['address_3'] = !empty($refDatas['address_3']) ? $refDatas['address_3'] : NULL;
				$data['account_ref'] = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : NULL;
				$data['uk_sage_code'] = !empty($refDatas['uk_sage_code']) ? $refDatas['uk_sage_code'] : NULL;
				$data['tax_code'] = !empty($refDatas['tax_code']) ? $refDatas['tax_code'] : NULL;
				$data['vat_reg'] = !empty($refDatas['vat_reg']) ? $refDatas['vat_reg'] : NULL;
				$data['country_code'] = !empty($refDatas['country_code']) ? $refDatas['country_code'] : NULL;
				$data['comment'] = !empty($refDatas['comment']) ? $refDatas['comment'] : NULL;
				$data['corporates'] = !empty($refDatas['corporates']) ? $refDatas['corporates'] : NULL;
				$data['labs'] = !empty($refDatas['labs']) ? $refDatas['labs'] : NULL;
				$data['referrals'] = !empty($refDatas['referrals']) ? $refDatas['referrals'] : NULL;
				$data['rcds_number'] = !empty($refDatas['rcds_number']) ? $refDatas['rcds_number'] : NULL;
				$data['add_1'] = !empty($refDatas['add_1']) ? $refDatas['add_1'] : NULL;
				$data['add_2'] = !empty($refDatas['add_2']) ? $refDatas['add_2'] : NULL;
				$data['add_3'] = !empty($refDatas['add_3']) ? $refDatas['add_3'] : NULL;
				$data['add_4'] = !empty($refDatas['add_4']) ? $refDatas['add_4'] : NULL;
				$data['order_can_send_to'] = !empty($refDatas['order_can_send_to']) ? $refDatas['order_can_send_to'] : NULL;
				$data['odelivery_address'] = !empty($refDatas['odelivery_address']) ? $refDatas['odelivery_address'] : NULL;
				$data['opostal_code'] = !empty($refDatas['opostal_code']) ? $refDatas['opostal_code'] : NULL;
				$data['ocity'] = !empty($refDatas['ocity']) ? $refDatas['ocity'] : NULL;
				$data['ocountry'] = !empty($refDatas['ocountry']) ? $refDatas['ocountry'] : NULL;
				$data['buying_groups'] = !empty($refDatas['buying_groups']) ? $refDatas['buying_groups'] : NULL;
				$data['vat_applicable'] = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
				$data['tm_user_id'] = !empty($refDatas['tm_user_id']) ? $refDatas['tm_user_id'] : NULL;
				$data['labsuite_entidad_code'] = !empty($refDatas['labsuite_entidad_code']) ? $refDatas['labsuite_entidad_code'] : NULL;
				$data['sage_account'] = !empty($refDatas['sage_account']) ? $refDatas['sage_account'] : NULL;
				$data['intercompany'] = !empty($refDatas['intercompany']) ? $refDatas['intercompany'] : NULL;
				$data['monthly_invoice'] = !empty($refDatas['monthly_invoice']) ? $refDatas['monthly_invoice'] : NULL;
            }
            //branches data
            $this->_data['data'] = $data;
            $this->_data['branches'] = $branch_data;
  		}
  		$this->load->view("usersDetails/vetLabUsers/add_edit", $this->_data);
    }

	function practice_addEdit(){
        $postUser = [];
        $postUserDetails = [];
        $branchDetails = [];
        $role_id = 2;
        $tm_role_id = '5';
		$output = array('status' => 'fail','practiceId' => 0);
  		if ($this->input->post('name') !="" && $this->input->post('email') !=""){
			$postUser['name'] = $this->input->post('name');
			$postUser['last_name'] = $this->input->post('last_name');
			$postUser['email'] = $this->input->post('email');
			if($this->input->post('password')){
				$postUser['password'] = md5($this->input->post('password'));
			}
			$postUser['country'] = $this->input->post('country');
			$postUser['phone_number'] = $this->input->post('phone_number');
			$postUser['role'] = 2;
			$postUser['managed_by_id'] = !empty($this->input->post('managed_by_id'))?implode(",",$this->input->post('managed_by_id')):'';
			$postUser['invoiced_by'] = !empty($this->input->post('invoiced_by'))?$this->input->post('invoiced_by'):'';
			$postUserDetails['id'] = '';
			$postUserDetails['address_1'] = $this->input->post('address_1');
			$postUserDetails['address_2'] = $this->input->post('address_2');
			$postUserDetails['address_3'] = $this->input->post('address_3');
			$postUserDetails['account_ref'] = $this->input->post('account_ref');
			$postUserDetails['tax_code'] = $this->input->post('tax_code');
			$postUserDetails['vat_reg'] = $this->input->post('vat_reg');
			$postUserDetails['country_code'] = $this->input->post('country_code');
			$postUserDetails['comment'] = $this->input->post('comment');
			$postUserDetails['corporates'] = ( !empty($this->input->post('corporates')) && $this->input->post('corporates')[0]!='') ? json_encode($this->input->post('corporates')) : NULL;
			$postUserDetails['labs'] = ( !empty($this->input->post('practice_labid_modal')) && $this->input->post('practice_labid_modal')!='') ? json_encode($this->input->post('practice_labid_modal')) : NULL;
			$postUserDetails['referrals'] = ( !empty($this->input->post('referrals')) && $this->input->post('referrals')[0]!='') ? json_encode($this->input->post('referrals')) : NULL;
			$postUserDetails['rcds_number'] = $this->input->post('rcds_number');
			$postUserDetails['add_1'] = $this->input->post('add_1');
			$postUserDetails['add_2'] = $this->input->post('add_2');
			$postUserDetails['add_3'] = $this->input->post('add_3');
			$postUserDetails['add_4'] = $this->input->post('add_4');
			$postUserDetails['order_can_send_to'] = $this->input->post('order_can_send_to');
			$postUserDetails['odelivery_address'] = ( $this->input->post('odelivery_address')!='' ) ? $this->input->post('odelivery_address') : NULL;
			$postUserDetails['opostal_code'] = ( $this->input->post('opostal_code')!='' ) ? $this->input->post('opostal_code') : NULL;
			$postUserDetails['ocity'] = ( $this->input->post('ocity')!='' ) ? $this->input->post('ocity') : NULL;
			$postUserDetails['ocountry'] = ( $this->input->post('ocountry')!='' ) ? $this->input->post('ocountry') : NULL;
			$postUserDetails['buying_groups'] = ( !empty($this->input->post('buying_groups')) && $this->input->post('buying_groups')[0]!='') ? json_encode($this->input->post('buying_groups')) : NULL;
			$postUserDetails['vat_applicable'] = !empty($this->input->post('vat_applicable'))?$this->input->post('vat_applicable'):0;
			$postUser['created_at'] = date("Y-m-d H:i:s");
			if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails,$branchDetails)) {
				$output = array(
					'status' => 'success',
					'practiceId' => $id
				);
			}
        }
		echo json_encode($output); exit;
    }

    function petOwners_addEdit($id= ''){
        $postUser = [];
        $petowners_to_vetusers = [];
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        $data['ids'] = "";
        $this->_data['ids']= $this->UsersModel->getvatLabUsers($id);
        if($id>0){
            $role_id = $this->_data['ids']['user_type'];
        }else{
            $role_id = "2";
        }

        $this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id);
        if($this->user_role==1 || $this->user_role==3){
            $this->_data['branches'] = $this->UsersDetailsModel->get_petowner_branch($id,$this->_data['ids']);
        }else{
            $this->_data['branches'] = $this->UsersDetailsModel->get_petowner_branch('',array('ids'=>$this->user_id));
        }
        $this->_data['branch_ids']= $this->UsersDetailsModel->getselectedBranches($id);
        $data = $this->UsersDetailsModel->getRecord($id);
        $data['user_type'] = ($id>0 ) ? $this->_data['ids']['user_type'] : array();
  		if ( !empty($this->input->post()) ) {
            //set unique value
            $is_email_unique = "";
            if( $this->input->post('is_modal')=='1'){
                $is_email_unique = "|is_unique[ci_users.email]";
            }else{
                $current_email = !empty($data['email'])?$data['email']:'';
                if($this->input->post('email') != $current_email){
                    $is_email_unique = "|is_unique[ci_users.email]";
                }
            }

            //set rules
			if (!empty($this->input->post('parent_id')[0])){
                $this->form_validation->set_rules('parent_id[]', 'parent_id', 'required');
            }
            $this->form_validation->set_rules('last_name', 'last_name', 'required');
            if ($this->form_validation->run() == FALSE){
                $error = validation_errors();
                if( $this->input->post('is_modal')=='1'){
                    $output = array(
                        'error'	=>	strip_tags($error),
                        'status' => 'fail',
                        'postCode' => $this->input->post('post_code')
                    );
                    echo json_encode($output); exit;
                }else{
                    $this->load->view('usersDetails/petOwners/add_edit','',TRUE);
                }
            }else{
                //user post data
                if($this->user_role==1){
                    $petowners_to_vetusers['parent_id'] = $this->input->post('parent_id');
                }else{
                    $petowners_to_vetusers['parent_id'] = array($this->user_id);
                }
                $petowners_to_vetusers['user_type'] = $this->input->post('user_type');
                $petowners_to_vetusers['branch_id'] = $this->input->post('branch_id');
                $postUser['name'] = $this->input->post('name');
                $postUser['last_name'] = $this->input->post('last_name');
                $postUser['email'] = $this->input->post('email');
                if($this->input->post('password')){
                    $postUser['password'] = md5($this->input->post('password'));
                }
                $postUser['age'] = $this->input->post('age');
                $postUser['post_code'] = $this->input->post('post_code');
                $postUser['role'] = 3;
                $postUser['country'] = 1;
                $is_from_modal = 0;
				if( $this->input->post('is_modal')=='1' ){
					$is_from_modal = 1;
				} 

				if(is_numeric($id)>0){
                    $postUser['updated_at'] = date("Y-m-d H:i:s");
                    $postUser['updated_by'] = $this->user_id;
                    $postUser['id'] = $id;
                    if( $is_from_modal=='1'){
                        unset($postUser["email"]);
                        unset($postUser["age"]);
                        unset($postUser["role"]);
                        unset($postUser["country"]);
                    }
                    if ($this->UsersModel->petOwners_add_edit($postUser,$petowners_to_vetusers,$this->user_id,$this->user_role,$is_from_modal)){
						if( $is_from_modal=='1'){
							$output = array(
                                'status' => 'success',
                                'petOwnerId' => $id,
                                'postCode' => $this->input->post('post_code')
                            );
                            echo json_encode($output); exit;
                        }else if( $this->user_role=='3' ){
                            $this->session->set_flashdata('success','Profile has been updated successfully.');
                            redirect('pet_owners/edit/'.$this->user_id);
                        }else{
                            $this->session->set_flashdata('success','Pet Owner data has been updated successfully.');
                            redirect('usersDetails/petOwners');
                        }
                    }
                }else{
                    $postUser['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->UsersModel->petOwners_add_edit($postUser,$petowners_to_vetusers,$this->user_id,$this->user_role,$is_from_modal)) {
                        if( $this->input->post('is_modal')=='1'){
                            $output = array(
                                'status' => 'success',
                                'petOwnerId' => $id,
                                'postCode' => $this->input->post('post_code')
                            );
                            echo json_encode($output); exit;
                        }else{
                            $this->session->set_flashdata('success','Pet Owner data has been added successfully.');
                            redirect('usersDetails/petOwners');
                        }
                    }
                }
            }
        }

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
  		$this->load->view("usersDetails/petOwners/add_edit", $this->_data);
    }

    function pets_addEdit($id= ''){
        $postUser = [];
        $postUserDetails = [];
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        $role_id = "3";
        $this->_data['customers'] = $this->UsersModel->getRecordAll($role_id);
        $data = $this->UsersDetailsModel->getRecord($id,"4");
  		if ($this->input->post('submit')) {
            //set rules
            $this->form_validation->set_rules('parent_id', 'parent_id', 'required');
            $this->form_validation->set_rules('pet_comment', 'pet_comment', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('usersDetails/pets/add_edit','',TRUE);
            }else{
				$postUserDetails = $this->input->post();
				$postUserDetails['id'] = $id;
                if(is_numeric($id)>0){
                    if ($this->UsersDetailsModel->add_edit($postUser,$postUserDetails)>0) {
                        $this->session->set_flashdata('success','Pet data has been updated successfully.');
                        redirect('usersDetails/pets');
                    }
                }else{
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        $this->session->set_flashdata('success','Pet data has been added successfully.');
                        redirect('usersDetails/pets');
                    }
                }
            }
        }

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
                $column_field = explode('|',$data['column_field']);
                $data['pet_comment'] = $column_field[0];
                $this->_data['data'] = $data;
            }
  		}
  		$this->load->view("usersDetails/pets/add_edit", $this->_data);
    }

    function delete($id){
		$orders= [];
		$pets= [];
		$petsOwner= [];
		$type = $this->input->get('type');
		if(isset($type) && $type=="vetLabUser"){
			$orders = $this->OrdersModel->getRecordByvet($id);
			$pets = $this->PetsModel->getRecordVet($id);
			$petsOwner = $this->UsersDetailsModel->getPetsOwenerData($id,$this->user_role,$this->user_id,$this->input->get('role'));
		}else if(isset($type) && $type=="lab"){
			$orders = $this->OrdersModel->getRecordByLab($id);
		}
		if(!empty($orders) || !empty($pets) || !empty($petsOwner)){
            $data['status']=1;
            $data['message']="success";
            $data['orders']=$orders;
            $data['pets']=$pets;
            $data['petsOwner']=$petsOwner;
            echo json_encode($data); exit();
            // return $data;
		}
        if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			if($this->input->get('role')){
				$dataWhere['role'] = $this->input->get('role');
			}
			$delete = $this->UsersDetailsModel->delete($dataWhere);
			if($delete){
				$data['status']=0;
				$data['message']="success";
				echo json_encode($data); exit();
			}
        }

        $data['status']=0;
        $data['message']="success";
        echo json_encode($data); exit();
    }

    function get_branch_dropdown(){ 
        $vetUserData = $this->input->post();
        $vetUserData['vet_user_id'] = implode(",", $vetUserData['vet_user_id']);
        $branchesData = $this->UsersDetailsModel->get_branch_dropdown($vetUserData);
        echo json_encode($branchesData); exit();
    }

	function get_branch_dropdownjss(){ 
        $vetUserData = $this->input->post();
        // $vetUserData['vet_user_id'] = implode(",", $vetUserData['vet_user_id']);
        $branchesData = $this->UsersDetailsModel->get_branch_dropdown($vetUserData);
        echo json_encode($branchesData); exit();
    }

    function get_branch_dropdownjs(){ 
        $vetUserData = $this->input->post();
        $branchesData = $this->PracticeModel->allRecordAddrs($vetUserData);
        echo json_encode($branchesData); exit();
    }

	function get_practice_address(){ 
        $vetUserData = $this->input->post();
		$data = array();
		if($vetUserData['vet_user_id'] > 0){
			$refDatas = $this->UsersDetailsModel->getColumnAllArray($vetUserData['vet_user_id']);
			$refDatas = array_column($refDatas, 'column_field', 'column_name');
			$data['address'] = !empty($refDatas['add_1']) ? $refDatas['add_1'] : NULL;
			$data['address1'] = !empty($refDatas['add_2']) ? $refDatas['add_2'] : NULL;
			$data['address2'] = !empty($refDatas['add_3']) ? $refDatas['add_3'] : NULL;
			$data['address3'] = !empty($refDatas['add_4']) ? $refDatas['add_4'] : NULL;
			$data['town_city'] = !empty($refDatas['add_4']) ? $refDatas['add_4'] : NULL;
			$data['county'] = !empty($refDatas['address_2']) ? $refDatas['address_2'] : NULL;
			$data['postcode'] = !empty($refDatas['address_3']) ? $refDatas['address_3'] : NULL;
			$this->db->select('country,email');
			$this->db->from('ci_users');
			$this->db->where('id',$vetUserData['vet_user_id']);
			$userData = $this->db->get()->row();
			$data['country'] = $userData->country;
			$data['email'] = $userData->email;
		}
        echo json_encode($data); exit();
    }

	function get_practice_emails(){ 
        $pracData = $this->input->post();
		$this->db->select('email');
		$this->db->from('ci_orders');
		$this->db->where('vet_user_id',$pracData['vet_user_id']);
		$this->db->where('email !=','');
		$this->db->group_by('email');
		$pracEmails = $this->db->get()->result_array();
        echo json_encode($pracEmails); exit();
    }

	function get_lab_address(){ 
        $vetUserData = $this->input->post();
		$data = array();
		if($vetUserData['lab_id'] > 0){
			$refDatas = $this->UsersDetailsModel->getColumnAllArray($vetUserData['lab_id']);
			$refDatas = array_column($refDatas, 'column_field', 'column_name');
			$data['address'] = !empty($refDatas['address_1']) ? $refDatas['address_1'] : NULL;
			$data['address1'] = !empty($refDatas['address_2']) ? $refDatas['address_2'] : NULL;
			$data['address2'] = !empty($refDatas['address_3']) ? $refDatas['address_3'] : NULL;
			$data['address3'] = !empty($refDatas['address_4']) ? $refDatas['address_4'] : NULL;
			$data['town_city'] = !empty($refDatas['town_city']) ? $refDatas['town_city'] : NULL;
			$data['county'] = NULL;
			$data['postcode'] = !empty($refDatas['post_code']) ? $refDatas['post_code'] : NULL;
			$data['deliver_to_practice'] = !empty($refDatas['deliver_to_practice']) ? $refDatas['deliver_to_practice'] : 0;
			$this->db->select('country,email');
			$this->db->from('ci_users');
			$this->db->where('id',$vetUserData['lab_id']);
			$userData = $this->db->get()->row();
			$data['country'] = $userData->country;
			$data['email'] = $userData->email;
		}
        echo json_encode($data); exit();
    }

	function get_lab_emails(){ 
        $labsData = $this->input->post();
		$this->db->select('email');
		$this->db->from('ci_orders');
		$this->db->where('lab_id',$labsData['lab_id']);
		$this->db->where('email !=','');
		$this->db->group_by('email');
		$labEmails = $this->db->get()->result_array();
        echo json_encode($labEmails); exit();
    }

    function getPetOwnerDetails(){
        $petOwnerData = $this->input->post();
        $petOwnerID = $petOwnerData['petOwner_id'];
        $getPetOwnerData = $this->UsersDetailsModel->getRecord($petOwnerID);
        echo json_encode($getPetOwnerData); exit();
    }

    //labs listing
    function labs_getTableData(){
        $role = 6;
        $Labs = $this->UsersDetailsModel->getTableData($role,$this->user_id,$this->user_role); 
		if(!empty($Labs)){
			foreach ($Labs as $key => $value) {
				//$column_field = explode(',',$value->column_field);
				$Labs[$key]->name = $value->name;
				$Labs[$key]->email = $value->email;
				$refDatas = $this->UsersDetailsModel->getColumnAllArray($value->id);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$Labs[$key]->account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : NULL;
				$Labs[$key]->address_1 = !empty($refDatas['address_1']) ? $refDatas['address_1'] : NULL;
				$Labs[$key]->address_2 = !empty($refDatas['address_2']) ? $refDatas['address_2'] : NULL;
				$Labs[$key]->address_3 = !empty($refDatas['address_3']) ? $refDatas['address_3'] : NULL;
				$Labs[$key]->vat_applicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
			}
		}
		$total = $this->UsersDetailsModel->count_all();
		$totalFiltered = $this->UsersDetailsModel->count_filtered($role,$this->user_id,$this->user_role);
		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $Labs;
		echo json_encode($ajax); exit();
	}

    //lab add/edit
    function labs_addEdit($id= ''){
        $postUser = [];
        $postUserDetails = [];
        $this->_data['data'] = [];
        $this->_data['branches'] = [];
  		$this->_data['id'] = $id;
        $role_id = 6;
        $data = $this->UsersDetailsModel->getRecord($id,$role_id);
		$this->_data['practices'] = $this->UsersModel->getRecordAll("2");
  		if ($this->input->post('submit')) {
            //set unique value
			/* $is_email_unique = "";
            $current_email = !empty($data['email'])?$data['email']:'';
            if($this->input->post('email') != $current_email){
				$this->db->select('email');
				$this->db->from('ci_users');
				$this->db->where('role', 6);
				$this->db->where('email LIKE', $this->input->post('email'));
				$res2 = $this->db->get();
				if($res2->num_rows() > 0){
					$is_email_unique = "|is_unique[ci_users.email]";
				}
            } */

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required');
            /* $this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique); */
			$this->form_validation->set_rules('email', 'email', 'required');
            if($this->input->post('password')!='') { $this->form_validation->set_rules('password', 'password', 'required'); }
            $this->form_validation->set_rules('address_1', 'address_1', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('usersDetails/labs/add_edit','',TRUE);
            }else{
                //user post data
                $postUser['name'] = $this->input->post('name');
                $postUser['email'] = $this->input->post('email');
                if($this->input->post('password')){
                    $postUser['password'] = md5($this->input->post('password'));
                }
                $postUser['country'] = $this->input->post('country');
                $postUser['phone_number'] = $this->input->post('phone_number');
                $postUser['role'] = 6;
				$postUser['managed_by_id'] = !empty($this->input->post('managed_by_id'))?implode(",",$this->input->post('managed_by_id')):'';
				$postUser['invoiced_by'] = !empty($this->input->post('invoiced_by'))?$this->input->post('invoiced_by'):'';

                //user details post data
                $postUserDetails['id'] = $id;
                $postUserDetails['address_1'] = $this->input->post('address_1');
                $postUserDetails['practices'] = ( !empty($this->input->post('practices')) && $this->input->post('practices')[0]!='') ? json_encode($this->input->post('practices')) : NULL;
                $postUserDetails['deliver_to_practice'] = ($this->input->post('deliver_to_practice')!='') ? '1' : '0';
                $postUserDetails['address_2'] = $this->input->post('address_2');
                $postUserDetails['address_3'] = $this->input->post('address_3');
                $postUserDetails['address_4'] = $this->input->post('address_4');
				$postUserDetails['comment'] = $this->input->post('comment');
				$postUserDetails['account_ref'] = $this->input->post('account_ref') ?? null;
				$postUserDetails['uk_sage_code'] = !empty($this->input->post('uk_sage_code'))?$this->input->post('uk_sage_code'):NULL;
                $postUserDetails['town_city'] = $this->input->post('town_city');
                $postUserDetails['post_code'] = $this->input->post('post_code');
				$postUserDetails['vat_applicable'] = !empty($this->input->post('vat_applicable'))?$this->input->post('vat_applicable'):0;
				$postUserDetails['results_to_practice'] = !empty($this->input->post('results_to_practice'))?$this->input->post('results_to_practice'):0;
				$postUserDetails['invoice_to_practice'] = !empty($this->input->post('invoice_to_practice'))?$this->input->post('invoice_to_practice'):0;
				$postUserDetails['invoice_to_practice_immu'] = !empty($this->input->post('invoice_to_practice_immu'))?$this->input->post('invoice_to_practice_immu'):0;
				$postUserDetails['labsuite_entidad_code'] = !empty($this->input->post('labsuite_entidad_code'))?$this->input->post('labsuite_entidad_code'):NULL;
				$postUserDetails['sage_account'] = !empty($this->input->post('sage_account'))?$this->input->post('sage_account'):NULL;
				$postUserDetails['intercompany'] = !empty($this->input->post('intercompany'))?$this->input->post('intercompany'):NULL;
				$postUserDetails['monthly_invoice'] = !empty($this->input->post('monthly_invoice'))?$this->input->post('monthly_invoice'):NULL;

                //branch post data
                $part_of_corpo = [];
                $buying_group = [];
                if(is_numeric($id)>0){
                    $postUser['updated_at'] = date("Y-m-d H:i:s");
                    $postUser['updated_by'] = $this->user_id;
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        if( $this->user_role=='6' ){
                            $this->session->set_flashdata('success','Profile has been updated successfully.');
                            redirect('labs/edit/'.$this->user_id);
                        }else{
                            $this->session->set_flashdata('success','Lab data has been updated successfully.');
                            redirect('usersDetails/labs'); 
                        }
                    }
                }else{
                    $postUser['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        $this->session->set_flashdata('success','Lab data has been added successfully.');
                        redirect('usersDetails/labs');
                    }
                }
            }
        }

        //load data edit time
        if(is_numeric($id)>0){
			$userDetailsdata = $this->UsersDetailsModel->getRecordArray($id,$role_id);
			$userDetails = array_column($userDetailsdata, 'column_field', 'column_name');
			if(!empty($data)){
                $column_field = explode('|',$data['column_field']);
                $data['address_1'] = isset($userDetails['address_1']) ? $userDetails['address_1'] : NULL;
                $data['practices'] = isset($column_field[1]) ? $column_field[1] : NULL;
                $data['deliver_to_practice'] = isset($column_field[2]) ? $column_field[2] : NULL;
                $data['address_2'] = isset($userDetails['address_2']) ? $userDetails['address_2'] : NULL;
                $data['address_3'] = isset($userDetails['address_3']) ? $userDetails['address_3'] : NULL;
                $data['address_4'] = isset($userDetails['address_4']) ? $userDetails['address_4'] : NULL;
				$data['account_ref'] = isset($userDetails['account_ref']) ? $userDetails['account_ref'] : NULL;
				$data['uk_sage_code'] = !empty($userDetails['uk_sage_code']) ? $userDetails['uk_sage_code'] : NULL;
                $data['town_city'] = isset($userDetails['town_city']) ? $userDetails['town_city'] : NULL;
                $data['post_code'] = isset($userDetails['post_code']) ? $userDetails['post_code'] : NULL;
				$data['vat_applicable'] = isset($userDetails['vat_applicable']) ? $userDetails['vat_applicable'] : 0;
				$data['invoice_to_practice'] = isset($userDetails['invoice_to_practice']) ? $userDetails['invoice_to_practice'] : 0;
				$data['results_to_practice'] = isset($userDetails['results_to_practice']) ? $userDetails['results_to_practice'] : 0;
				$data['invoice_to_practice_immu'] = isset($userDetails['invoice_to_practice_immu']) ? $userDetails['invoice_to_practice_immu'] : 0;
				$data['labsuite_entidad_code'] = !empty($userDetails['labsuite_entidad_code']) ? $userDetails['labsuite_entidad_code'] : NULL;
				$data['sage_account'] = !empty($userDetails['sage_account']) ? $userDetails['sage_account'] : NULL;
				$data['intercompany'] = !empty($userDetails['intercompany']) ? $userDetails['intercompany'] : NULL;
				$data['monthly_invoice'] = !empty($userDetails['monthly_invoice']) ? $userDetails['monthly_invoice'] : NULL;
				$data['comment'] = !empty($userDetails['comment']) ? $userDetails['comment'] : NULL;
            }
            $this->_data['data'] = $data;
  		}
  		$this->load->view("usersDetails/labs/add_edit", $this->_data);
    }

    //corporates listing
    function corporates_getTableData(){
        $role = 7;
        $Labs = $this->UsersDetailsModel->getTableData($role,$this->user_id,$this->user_role); 
		if(!empty($Labs)){
			foreach ($Labs as $key => $value) {
				$column_field = explode(',',$value->column_field);
				$Labs[$key]->name = $value->name;
				$Labs[$key]->email = $value->email;
				$Labs[$key]->address_1 = isset($column_field[0]) ? $column_field[0] : NULL;
				$Labs[$key]->address_2 = isset($column_field[1]) ? $column_field[1] : NULL;
				$Labs[$key]->address_3 = isset($column_field[2]) ? $column_field[2] : NULL;
			}
		}
		$total = $this->UsersDetailsModel->count_all();
		$totalFiltered = $this->UsersDetailsModel->count_filtered($role,$this->user_id,$this->user_role);

		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $Labs;
		echo json_encode($ajax); exit();
    }

    //corporates add/edit
    function corporates_addEdit($id= ''){
		$postUser = [];
		$postUserDetails = [];
		$this->_data['data'] = [];
		$this->_data['branches'] = [];
		$this->_data['id'] = $id;
		$role_id = 7;
		$data = $this->UsersDetailsModel->getRecord($id,$role_id);
		$this->_data['practices'] = $this->UsersModel->getRecordAll("2");
		if ($this->input->post('submit')) {
            $is_email_unique = "";
            $current_email = !empty($data['email'])?$data['email']:'';
            if($this->input->post('email') != $current_email){
                $is_email_unique = "|is_unique[ci_users.email]";
            }

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
            if($this->input->post('password')!='') {$this->form_validation->set_rules('password', 'password', 'required');}
            $this->form_validation->set_rules('address_1', 'address_1', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('usersDetails/corporates/add_edit','',TRUE);
            }else{
                //user post data
                $postUser['name'] = $this->input->post('name');
                $postUser['email'] = $this->input->post('email');
                if($this->input->post('password')){
                    $postUser['password'] = md5($this->input->post('password'));
                }
                $postUser['role'] = 7;
                $postUser['country'] = 1;

                //user details post data
                $postUserDetails['id'] = $id;
                $postUserDetails['address_1'] = $this->input->post('address_1');
                $postUserDetails['practices'] = ( !empty($this->input->post('practices')) && $this->input->post('practices')[0]!='') ? json_encode($this->input->post('practices')) : NULL;
                $postUserDetails['address_2'] = $this->input->post('address_2');
                $postUserDetails['address_3'] = $this->input->post('address_3');
                $postUserDetails['address_4'] = $this->input->post('address_4');
                $postUserDetails['town_city'] = $this->input->post('town_city');
                $postUserDetails['post_code'] = $this->input->post('post_code');
                $postUserDetails['county'] = $this->input->post('county');

                //branch post data
                $part_of_corpo = [];
                $buying_group = [];
                if(is_numeric($id)>0){
                    $postUser['updated_at'] = date("Y-m-d H:i:s");
                    $postUser['updated_by'] = $this->user_id;
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        if( $this->user_role=='7' ){
                            $this->session->set_flashdata('success','Profile has been updated successfully.');
                            redirect('corporates/edit/'.$this->user_id);
                        }else{
                            $this->session->set_flashdata('success','Corporate data has been updated successfully.');
                            redirect('usersDetails/corporates');
                        }
                    }
                }else{
                    $postUser['created_at'] = date("Y-m-d H:i:s");
                    $postUser['created_by'] = $this->user_id;
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        $this->session->set_flashdata('success','Corporate data has been added successfully.');
                        redirect('usersDetails/corporates');
                    }
                }
            }
        }

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
                $column_field = explode('|',$data['column_field']);
                $data['address_1'] = isset($column_field[0]) ? $column_field[0] : NULL;
                $data['practices'] = isset($column_field[1]) ? $column_field[1] : NULL;
                $data['address_2'] = isset($column_field[2]) ? $column_field[2] : NULL;
                $data['address_3'] = isset($column_field[3]) ? $column_field[3] : NULL;
                $data['address_4'] = isset($column_field[4]) ? $column_field[4] : NULL;
                $data['town_city'] = isset($column_field[5]) ? $column_field[5] : NULL;
                $data['post_code'] = isset($column_field[6]) ? $column_field[6] : NULL;
                $data['county'] = isset($column_field[7]) ? $column_field[7] : NULL;
            }
            $this->_data['data'] = $data;
  		}
  		$this->load->view("usersDetails/corporates/add_edit", $this->_data);
    }

    //buying group listing
    function buying_groups_getTableData(){
		$role = 9;
		$Buyings = $this->UsersDetailsModel->getTableData($role,$this->user_id,$this->user_role); 
		if(!empty($Buyings)){
			foreach ($Buyings as $key => $value) {
				$column_field = explode(',',$value->column_field);

				$Buyings[$key]->name = $value->name;
				$Buyings[$key]->email = $value->email;
				$Buyings[$key]->address_1 = isset($column_field[0]) ? $column_field[0] : NULL;
				$Buyings[$key]->address_2 = isset($column_field[1]) ? $column_field[1] : NULL;
				$Buyings[$key]->address_3 = isset($column_field[2]) ? $column_field[2] : NULL;
			}
		}

		$total = $this->UsersDetailsModel->count_all();
		$totalFiltered = $this->UsersDetailsModel->count_filtered($role,$this->user_id,$this->user_role);

		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $Buyings;
		echo json_encode($ajax); exit();
    }

    //buying group add/edit
    function buying_groups_addEdit($id= ''){
        $postUser = [];
        $postUserDetails = [];
        $this->_data['data'] = [];
        $this->_data['branches'] = [];
  		$this->_data['id'] = $id;
        $role_id = 9;

        $data = $this->UsersDetailsModel->getRecord($id,$role_id);
        $this->_data['practices'] = $this->UsersModel->getRecordAll("2");
  		if ($this->input->post('submit')) {
            $is_email_unique = "";
            $current_email = !empty($data['email'])?$data['email']:'';
            if($this->input->post('email') != $current_email){
                $is_email_unique = "|is_unique[ci_users.email]";
            }

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
            if($this->input->post('password')!='') {$this->form_validation->set_rules('password', 'password', 'required');}
            $this->form_validation->set_rules('address_1', 'address_1', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('usersDetails/buyingGroup/add_edit','',TRUE);
            }else{
                //user post data
                $postUser['name'] = $this->input->post('name');
                $postUser['email'] = $this->input->post('email');
                if($this->input->post('password')){
                    $postUser['password'] = md5($this->input->post('password'));
                }
                $postUser['role'] = 9;
                $postUser['country'] = 1;

                //user details post data
                $postUserDetails['id'] = $id;
                $postUserDetails['address_1'] = $this->input->post('address_1');
                $postUserDetails['practices'] = ( !empty($this->input->post('practices')) && $this->input->post('practices')[0]!='') ? json_encode($this->input->post('practices')) : NULL;
                $postUserDetails['address_2'] = $this->input->post('address_2');
                $postUserDetails['address_3'] = $this->input->post('address_3');
                $postUserDetails['address_4'] = $this->input->post('address_4');
                $postUserDetails['town_city'] = $this->input->post('town_city');
                $postUserDetails['post_code'] = $this->input->post('post_code');
                $postUserDetails['county'] = $this->input->post('county');

                //branch post data
                $part_of_corpo = [];
                $buying_group = [];
                if(is_numeric($id)>0){
                    $postUser['updated_at'] = date("Y-m-d H:i:s");
                    $postUser['updated_by'] = $this->user_id;
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        if( $this->user_role=='9' ){
                            $this->session->set_flashdata('success','Profile has been updated successfully.');
                            redirect('buying_groups/edit/'.$this->user_id);
                        }else{
                            $this->session->set_flashdata('success','Buying Group data has been updated successfully.');
                            redirect('usersDetails/buying_groups');
                        }
                    }
                }else{
                    $postUser['created_at'] = date("Y-m-d H:i:s");
                    $postUser['created_by'] = $this->user_id;
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        $this->session->set_flashdata('success','Buying Group data has been added successfully.');
                        redirect('usersDetails/buying_groups');
                    }
                }
            }
        }

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
                $column_field = explode('|',$data['column_field']);
                $data['address_1'] = isset($column_field[0]) ? $column_field[0] : NULL;
                $data['practices'] = isset($column_field[1]) ? $column_field[1] : NULL;
                $data['address_2'] = isset($column_field[2]) ? $column_field[2] : NULL;
                $data['address_3'] = isset($column_field[3]) ? $column_field[3] : NULL;
                $data['address_4'] = isset($column_field[4]) ? $column_field[4] : NULL;
                $data['town_city'] = isset($column_field[5]) ? $column_field[5] : NULL;
                $data['post_code'] = isset($column_field[6]) ? $column_field[6] : NULL;
                $data['county'] = isset($column_field[7]) ? $column_field[7] : NULL;
            }
            $this->_data['data'] = $data;
  		}
  		$this->load->view("usersDetails/buyingGroup/add_edit", $this->_data);
    }

    //referrals listing
    function referrals_getTableData(){
		$role = 8;
		$Labs = $this->UsersDetailsModel->getTableData($role,$this->user_id,$this->user_role); 
		if(!empty($Labs)){
			foreach ($Labs as $key => $value) {
				$column_field = explode(',',$value->column_field);

				$Labs[$key]->name = $value->name;
				$Labs[$key]->email = $value->email;
				$Labs[$key]->address_1 = isset($column_field[0]) ? $column_field[0] : NULL;
				$Labs[$key]->address_2 = isset($column_field[1]) ? $column_field[1] : NULL;
				$Labs[$key]->address_3 = isset($column_field[2]) ? $column_field[2] : NULL;
			}
		}
		$total = $this->UsersDetailsModel->count_all();
		$totalFiltered = $this->UsersDetailsModel->count_filtered($role,$this->user_id,$this->user_role);

		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $Labs;
		echo json_encode($ajax); exit();
    }

    //referrals add/edit
    function referrals_addEdit($id= ''){
        $postUser = [];
        $postUserDetails = [];
        $this->_data['data'] = [];
        $this->_data['branches'] = [];
  		$this->_data['id'] = $id;
        $role_id = 8;
        $data = $this->UsersDetailsModel->getRecord($id,$role_id);
        $this->_data['practices'] = $this->UsersModel->getRecordAll("2");
  		if ($this->input->post('submit')) {
            $is_email_unique = "";
            $current_email = !empty($data['email'])?$data['email']:'';
            if($this->input->post('email') != $current_email){
                $is_email_unique = "|is_unique[ci_users.email]";
            }

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
            if($this->input->post('password')!='') {$this->form_validation->set_rules('password', 'password', 'required');}
            $this->form_validation->set_rules('address_1', 'address_1', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('usersDetails/referrals/add_edit','',TRUE);
            }else{
                //user post data
                $postUser['name'] = $this->input->post('name');
                $postUser['email'] = $this->input->post('email');
                if($this->input->post('password')){
                    $postUser['password'] = md5($this->input->post('password'));
                }
                $postUser['country'] = $this->input->post('country');
                $postUser['role'] = 8;

                //user details post data
                $postUserDetails['id'] = $id;
                $postUserDetails['address_1'] = $this->input->post('address_1');
                $postUserDetails['practices'] = ( !empty($this->input->post('practices')) && $this->input->post('practices')[0]!='') ? json_encode($this->input->post('practices')) : NULL;
                $postUserDetails['deliver_to_referral_practice'] = ($this->input->post('deliver_to_referral_practice')!='') ? '1' : '0';
                $postUserDetails['address_2'] = $this->input->post('address_2');
                $postUserDetails['address_3'] = $this->input->post('address_3');
                $postUserDetails['address_4'] = $this->input->post('address_4');
                $postUserDetails['town_city'] = $this->input->post('town_city');
                $postUserDetails['post_code'] = $this->input->post('post_code');

                //branch post data
                $part_of_corpo = [];
                $buying_group = [];
                if(is_numeric($id)>0){
                    $postUser['updated_at'] = date("Y-m-d H:i:s");
                    $postUser['updated_by'] = $this->user_id;
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        $this->session->set_flashdata('success','Referral data has been updated successfully.');
                        redirect('usersDetails/referrals');
                    }
                }else{
                    $postUser['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails)) {
                        $this->session->set_flashdata('success','Referral data has been added successfully.');
                        redirect('usersDetails/referrals');
                    }
                }
            }
        }

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
                $column_field = explode('|',$data['column_field']);
                $data['address_1'] = isset($column_field[0]) ? $column_field[0] : NULL;
                $data['practices'] = isset($column_field[1]) ? $column_field[1] : NULL;
                $data['deliver_to_referral_practice'] = isset($column_field[2]) ? $column_field[2] : NULL;
                $data['address_2'] = isset($column_field[3]) ? $column_field[3] : NULL;
                $data['address_3'] = isset($column_field[4]) ? $column_field[4] : NULL;
                $data['address_4'] = isset($column_field[5]) ? $column_field[5] : NULL;
                $data['town_city'] = isset($column_field[6]) ? $column_field[6] : NULL;
                $data['post_code'] = isset($column_field[7]) ? $column_field[7] : NULL;
            }
            $this->_data['data'] = $data;
  		}
  		$this->load->view("usersDetails/referrals/add_edit", $this->_data);
    }

    function duplicate_tm(){
        $tm_id = $this->input->post('tm_user_id');
        $practice_id = $this->input->post('practice_id');
        $total = $this->UsersDetailsModel->duplicate_tm($practice_id,$tm_id);
        if($total>0){
            echo "One TM can only be assigned to one practice/labs/corporates.";
        }else{
            echo "";
        }
        exit;
    }

	function exportPractice($id=''){
		ini_set('memory_limit', '256M');
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Practice ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Account Ref');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Practice Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Phone Number');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Address 1');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Address 2');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Address 3');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Town/City');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'County');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Country');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Postcode');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'VAT Applicable?');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'RCVS Number');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Preferred Language');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Managed By');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Invoiced By');

        $this->db->select('u.id, u.name, u.email, u.country, u.phone_number, u.managed_by_id, u.invoiced_by, u.preferred_language');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '2');
		if(!empty($id)){
			$this->db->where('(CONCAT(",", u.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$id) .'),")');
		}
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($datas as $row){
			$first_name	= !empty($row['name']) ? $row['name'] : '';
			$email		= !empty($row['email']) ? $row['email'] : '';
			$country	= !empty($row['country']) ? $row['country'] : '';
			$preferredLanguage	= !empty($row['preferred_language']) ? ucfirst($row['preferred_language']) : '';
			$countryName	= $this->getCountryName($country);
			if(!empty($row['managed_by_id']) && $row['managed_by_id'] != 0){
				$managed_by = $this->getManagedbyName($row['managed_by_id']);
			}else{
				$managed_by = '';
			}
			if(!empty($row['invoiced_by']) && $row['invoiced_by'] != 0){
				$invoiced_by = $this->getManagedbyName($row['invoiced_by']);
			}else{
				$invoiced_by = '';
			}
			$phoneNumber= !empty($row['phone_number']) ? $row['phone_number'] : '';
			$userData = array("user_id" => $row['id'], "column_name" => "'add_1', 'add_2', 'add_3', 'add_4', 'address_2', 'address_3', 'vat_applicable', 'account_ref', 'tax_code', 'vat_reg', 'country_code', 'rcds_number'");
			$practDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
			$practDetails = array_column($practDetails, 'column_field', 'column_name');
			$address_1	= !empty($practDetails['add_1']) ? $practDetails['add_1'] : '';
			$address_2	= !empty($practDetails['add_2']) ? $practDetails['add_2'] : '';
			$address_3	= !empty($practDetails['add_3']) ? $practDetails['add_3'] : '';
			$address_4	= !empty($practDetails['add_4']) ? $practDetails['add_4'] : '';
			$town_city	= !empty($practDetails['address_2']) ? $practDetails['address_2'] : '';
			$postcode	= !empty($practDetails['address_3']) ? $practDetails['address_3'] : '';
			$vatApplicable	= !empty($practDetails['vat_applicable']) ? $practDetails['vat_applicable'] : '';
			$account_ref= !empty($practDetails['account_ref']) ? $practDetails['account_ref'] : '';
			$rcds_number= !empty($practDetails['rcds_number']) ? $practDetails['rcds_number'] : '';

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $account_ref);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $first_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $email);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $phoneNumber);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $address_1);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $address_2);
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $address_3);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $address_4);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $town_city);
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $countryName);
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $postcode);
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $vatApplicable);
            $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $rcds_number);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $preferredLanguage);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $managed_by);
			$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $invoiced_by);
            $rowCount++;
		}
		$fileName = 'Nextmune_Practice_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }

	function exportPracticeBranches(){
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Branch ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Practice ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Practice Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Customer Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Email - General Clinic Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'General Telephone Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Accounts Email');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Accounts Telephone');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Address 1');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Address 2');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Address 3');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Address 4');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Town/City');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Country');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Postcode');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Contact in Accounts');
		$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'IVC Clinic Number');

        $this->db->select('id, vet_user_id, customer_number, name, address, address1, address2, address3, town_city, country, postcode, number, email, acc_contact, acc_email, acc_number, ivc_clinic_number');
		$this->db->from('ci_branches');
		$this->db->order_by('vet_user_id', 'ASC');
		$datas = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($datas as $row){
			$branchID	= $row['id'];
			$practiceID	= $row['vet_user_id'];
			$name		= !empty($row['name']) ? $row['name'] : '';
			$address	= !empty($row['address']) ? $row['address'] : '';
			$address1	= !empty($row['address1']) ? $row['address1'] : '';
			$address2	= !empty($row['address2']) ? $row['address2'] : '';
			$address3	= !empty($row['address3']) ? $row['address3'] : '';
			$town_city	= !empty($row['town_city']) ? $row['town_city'] : '';
			$country	= !empty($row['country']) ? $row['country'] : '';
			$postcode	= !empty($row['postcode']) ? $row['postcode'] : '';
			$number		= !empty($row['number']) ? $row['number'] : '';
			$email		= !empty($row['email']) ? $row['email'] : '';
			$acc_contact= !empty($row['acc_contact']) ? $row['acc_contact'] : '';
			$acc_email	= !empty($row['acc_email']) ? $row['acc_email'] : '';
			$acc_number	= !empty($row['acc_number']) ? $row['acc_number'] : '';
			$customer_number	= !empty($row['customer_number']) ? $row['customer_number'] : '';
			$ivc_clinic_number	= !empty($row['ivc_clinic_number']) ? $row['ivc_clinic_number'] : '';
			if($country == 2){
				$countryName= 'Ireland';
			}else{
				$countryName= 'UK';
			}
			$practiceName	= $this->getPracticeName($practiceID);

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $branchID);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $practiceID);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $practiceName);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $customer_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $name);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $email);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $number);
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $acc_email);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $acc_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $address);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $address1);
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $address2);
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $address3);
            $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $town_city);
            $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $countryName);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $postcode);
            $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $acc_contact);
            $objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $ivc_clinic_number);
            $rowCount++;
		}
		$fileName = 'Nextmune_Practice_Branches_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }

	function getPracticeName($id){
		$this->db->select('name,last_name');
		$this->db->from('ci_users');
		$this->db->where('id', $id);
		$datas = $this->db->get()->row_array();
		if($datas['last_name']!=''){
			return $datas['name'].''.$datas['last_name'];
		}else{
			return $datas['name'];
		}
	}
	
	function getCountryName($id){
		$this->db->select('name');
		$this->db->from('ci_staff_countries');
		$this->db->where('id', $id);
		$datas = $this->db->get()->row_array();
		return $datas['name'];
	}

	function getManagedbyName($ids){
		$this->db->select('GROUP_CONCAT(managed_by_name) as managedby_name');
		$this->db->from('ci_managed_by_members');
		$this->db->where('id IN('.$ids.')');
		$datas = $this->db->get()->row_array();
		return $datas['managedby_name'];
	}

	function exportLab($id=''){
		ini_set('memory_limit', '256M');
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Lab ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Account Ref');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Phone Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Address 1');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Address 2');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Address 3');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Address 4');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Town/City');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Country');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Postcode');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'VAT Applicable?');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Preferred Language');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Managed By');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Invoiced By');

        $this->db->select('u.id, u.name, u.email, u.country, u.phone_number, u.managed_by_id, u.invoiced_by, u.preferred_language');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '6');
		if(!empty($id)){
			$this->db->where('(CONCAT(",", u.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$id) .'),")');
		}
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($datas as $row){
			$first_name	= !empty($row['name']) ? $row['name'] : '';
			$email		= !empty($row['email']) ? $row['email'] : '';
			$country	= !empty($row['country']) ? $row['country'] : '';
			$preferredLanguage	= !empty($row['preferred_language']) ? ucfirst($row['preferred_language']) : '';
			$countryName	= $this->getCountryName($country);
			if(!empty($row['managed_by_id']) && $row['managed_by_id'] != 0){
				$managed_by = $this->getManagedbyName($row['managed_by_id']);
			}else{
				$managed_by = '';
			}
			if(!empty($row['invoiced_by']) && $row['invoiced_by'] != 0){
				$invoiced_by = $this->getManagedbyName($row['invoiced_by']);
			}else{
				$invoiced_by = '';
			}
			$phoneNumber= !empty($row['phone_number']) ? $row['phone_number'] : '';
			$userData = array("user_id" => $row['id'], "column_name" => "'account_ref', 'address_1', 'address_2', 'address_3', 'address_4', 'town_city', 'post_code', 'vat_applicable'");
			$labDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
			$labDetails = array_column($labDetails, 'column_field', 'column_name');
			$account_ref= !empty($labDetails['account_ref']) ? $labDetails['account_ref'] : '';
			$address_1	= !empty($labDetails['address_1']) ? $labDetails['address_1'] : '';
			$address_2	= !empty($labDetails['address_2']) ? $labDetails['address_2'] : '';
			$address_3	= !empty($labDetails['address_3']) ? $labDetails['address_3'] : '';
			$address_4	= !empty($labDetails['address_4']) ? $labDetails['address_4'] : '';
			$town_city	= !empty($labDetails['town_city']) ? $labDetails['town_city'] : '';
			$postcode	= !empty($labDetails['post_code']) ? $labDetails['post_code'] : '';
			$vatApplicable	= !empty($labDetails['vat_applicable']) ? $labDetails['vat_applicable'] : '';

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $account_ref);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $first_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $email);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $phoneNumber);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $address_1);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $address_2);
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $address_3);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $address_4);
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $town_city);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $countryName);
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $postcode);
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $vatApplicable);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $preferredLanguage);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $managed_by);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $invoiced_by);
            $rowCount++;
		}
		$fileName = 'Nextmune_Lab_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output'); 
    }

	function updateBranchesTable($oldID,$newID){
		$this->db->where('vet_user_id', $oldID);
		$this->db->update('ci_branches', array("vet_user_id"=>$newID));
	}
	
	function updateDiscountTable($oldID,$newID){
		$this->db->where('practice_id', $oldID);
		$this->db->update('ci_discount', array("practice_id"=>$newID));
	}
	
	function updateOrdersTable($oldID,$newID){
		$this->db->where('vet_user_id', $oldID);
		$this->db->update('ci_orders', array("vet_user_id"=>$newID));
	}
	
	function updateOrdersTable2($oldID,$newID){
		$this->db->where('delivery_practice_id', $oldID);
		$this->db->update('ci_orders', array("delivery_practice_id"=>$newID));
	}
	
	function updatePetsTable($oldID,$newID){
		$this->db->where('vet_user_id', $oldID);
		$this->db->update('ci_pets', array("vet_user_id"=>$newID));
	}

	function deleteUserTable($id){
		$this->db->where('id', $id);
		$delete = $this->db->delete('ci_users');
	}

	function deleteUserDetailTable($id){
		$this->db->where('user_id', $id);
		$delete = $this->db->delete('ci_user_details');
	}

	function updatepracticeManagedby(){
		ini_set('memory_limit', '256M');
        $this->db->select('u.id, u.name, u.country');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '2');
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$practiceArr = []; $postData = [];
		foreach($datas as $row){
			if($row['country'] > 0 && $row['country'] != '31'){
				$this->db->select('managed_by_id');
				$this->db->from('ci_staff_countries');
				$this->db->where('id', $row['country']);
				$res1 = $this->db->get();
				if($res1->num_rows() > 0){
					$postData['managed_by_id'] = $res1->row()->managed_by_id;
					$this->db->where('id', $row['id']);
					$this->db->update('ci_users',$postData);
				}
			}else{
				$practiceArr[$row['id']] = $row['name'];
			}
		}
		echo 'Below Practice not updated.';
		echo '<prE>';
		print_r($practiceArr);
		exit;
    }

	function updatepracticeInvoicedby(){
		ini_set('memory_limit', '256M');
        $this->db->select('u.id, u.name, u.country');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '2');
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$practiceArr = []; $postData = [];
		foreach($datas as $row){
			if($row['country'] > 0 && $row['country'] != '31'){
				$this->db->select('invoiced_by');
				$this->db->from('ci_staff_countries');
				$this->db->where('id', $row['country']);
				$res1 = $this->db->get();
				if($res1->num_rows() > 0){
					$postData['invoiced_by'] = $res1->row()->invoiced_by;
					$this->db->where('id', $row['id']);
					$this->db->update('ci_users',$postData);
				}
			}else{
				$practiceArr[$row['id']] = $row['name'];
			}
		}
		echo 'Below Practice not updated.';
		echo '<prE>';
		print_r($practiceArr);
		exit;
    }

	function updateFracePracticesManagedby(){
		ini_set('memory_limit', '256M');
        $this->db->select('id, name, country, managed_by_id');
		$this->db->from('ci_users');
		$this->db->where('role', '2');
		$this->db->where('CONCAT(",", managed_by_id, ",") REGEXP ",('. str_replace(",","|",3) .'),"');
		$this->db->order_by('id', 'ASC');
		$datas = $this->db->get()->result_array();
		$totalupdated = 0; $postData = [];
		foreach($datas as $row){
			if($row['country'] > 0 && $row['country'] == '4'){
				if($row['managed_by_id'] != '' && count(explode(",",$row['managed_by_id'])) > 0){
					if(!in_array('8',explode(",",$row['managed_by_id']))){
						$postData['managed_by_id'] = $row['managed_by_id'].',8';
						$this->db->where('id', $row['id']);
						$this->db->update('ci_users',$postData);
						$totalupdated++;
					}
				}else{
					$postData['managed_by_id'] = '8';
					$this->db->where('id', $row['id']);
					$this->db->update('ci_users',$postData);
					$totalupdated++;
				}
			}
		}
		echo $totalupdated. 'Practice updated.';
		exit;
    }

}
?>