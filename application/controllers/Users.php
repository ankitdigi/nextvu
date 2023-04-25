<?php
require_once(APPPATH . 'libraries/geoip/geoip2.phar');
use GeoIp2\Database\Reader;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ERROR | E_PARSE);
class Users extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->user_id = $this->session->userdata('user_id');
		$this->user_role = $this->session->userdata('role');
		$this->zones = $this->session->userdata('managed_by_id');
		$this->load->model('UsersModel');
		$this->load->model('UsersDetailsModel');
		$this->load->model('StaffCountriesModel');
		$this->load->model('StaffMembersModel');
	}

	function getVisIpAddr() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	function validateIPs ($input) {
		foreach(explode(',', $input) as $ip) {
			if (!filter_var($ip, FILTER_VALIDATE_IP)) {
				return false;
			} else {
				return $ip;
			}
		}
	}

	function getGeoLocation() {
		$reader = new Reader(APPPATH . 'libraries/geoip/GeoLite2-City.mmdb');
		$ip = $this->getVisIpAddr(); //109.71.56.0, 157.42.6.169
		$ip = ($ip == "::1") ? '101.167.184.0' : $ip;
		if (strstr($ip, ",")) {
			$ip = $this->validateIPs ($ip);
			if ($ip) {
				$record = $reader->city($ip);
			}
		} else {
			$record = $reader->city($ip);
		}
		$countryCode = $record->country->isoCode;
		$language = $this->StaffCountriesModel->getPreferLanguage($countryCode);

		return !empty($language['prefer_language']) ? $language['prefer_language'] : "english";
	}

	public function registration_form(){
		$language = $this->getGeoLocation();
		$this->lang->load($language.'_lang.php', $language);
		if (!empty($this->input->post())) {
			$postData = $this->input->post();
			$checkEmail = $this->UsersModel->check_email($postData['email']);

			$error = "";
			$success = "";
			$data = [];
			$errorNum = 0;
			if(!empty($checkEmail)){
				$error = "This email ID is already registered.";
				$errorNum = 1;
			} else if ($postData['password'] != $postData['confirm_password']) {
				$error = "Password & Confirm Password doesn't match.";
				$errorNum = 2;
			}

			if (empty($error)) {
				unset($postData['confirm_password']);
				$postData['password'] = md5($postData['password']);

				$usersData['name'] = $postData['name'];
				$usersData['last_name'] = $postData['last_name'];
				$usersData['country'] = $postData['country'];
				$usersData['phone_number'] = $postData['phone_number'];
				$usersData['email'] = $postData['email'];
				$usersData['password'] = $postData['password'];
				$usersData['role'] = explode(",", $postData['role'])[0];
				$usersData['user_type'] = explode(",", $postData['role'])[1];

				$userDetailData['ivc_clinic_number'] = $postData['clinic'];
				$userDetailData['add_1'] = $postData['street'];
				$userDetailData['address_3'] = $postData['post_code'];
				$userDetailData['address_2'] = $postData['city'];
				$userDetailData['vat_reg'] = $postData['vat'];
				$this->UsersDetailsModel->add_edit($usersData, $userDetailData);

				$from_email = FROM_EMAIL;
				$content_data['recipient_name'] = "Dear Admin";
				$content_data['content_body'] = '<b>First Name: </b>' . $postData['name'] . '<br><br><b>Last Name: </b>' . $postData['last_name'] . '<br><br><b>Clinic: </b>' . $postData['clinic'] . '<br><br><b>Email Address: </b>' . $postData['email'] . '<br><br><b>Post Code: </b>' . $postData['post_code'] . '';
				$to_email = 'stewart@webbagency.co.uk';
				//$to_email = 'reports.uk@nextmune.com';
				$config = array(
					'mailtype' => 'html',
					'charset' => 'utf-8'
				);
				$this->load->library('email', $config);
				$this->email->from($from_email, "NextVu");
				$this->email->to($to_email);
				$this->email->set_header('Content-Type', 'application/pdf');
				$this->email->set_header('Content-Disposition', 'attachment');
				$this->email->set_header('Content-Transfer-Encoding', 'base64');
				$this->email->subject('Registration Page Details');
				$msg_content = $this->load->view('users/registration_mail_template', $content_data, true);
				$this->email->message($msg_content);
				$this->email->set_mailtype("html");
				$is_send = $this->email->send();
				if ($is_send) {
					$success = "Thanks for registering to Nextview!<br><br><br>Before you are granted access to NextView, we have to validate that the created profile is linked to a veterinary clinic. We typically validate all registrations within 1-2 business days. We will send you an e-mail with your log on details as fast as the profile is validated. Please make sure to check your spam folder if you haven't received a confirmation in 2 business days or reach out to [local email address].";
				} else {
					$error = $this->email->print_debugger();
					$errorNum = 3;
				}
			}
		}
		$data = array(
			'error' => $error,
			'success' => $success,
			'errorNum' => $errorNum
		);
		if (!empty($error)) {
			$this->session->set_flashdata("error", $error);
		}
		if (!empty($success)) {
			$this->session->set_flashdata("success", $success);
		}

		$data['data'] = $postData;
		$data['controller'] = $this;
		$data['staffCountries'] = $this->StaffCountriesModel->getRecordAll();
		$this->load->view("users/registration", $data);
	}

	public function login(){
		if($this->session->userdata('logged_in')) {
			$email = get_cookie('vetordmgmt_email');
			$password = get_cookie('vetordmgmt_password');
			if(!empty($email) && !empty($password)){
				$validate = $this->UsersModel->validate($email,md5($password));
				if($validate->num_rows() > 0){
					$data  = $validate->row_array();
					$email = $data['email'];
					$role  = $data['role'];
					$country  = $data['country'];
					$is_admin  = $data['is_admin'];
					$user_type  = $data['user_type'];
					$managed_by_id  = $data['managed_by_id'];
					$preferred_language  = !empty($data['preferred_language'])?$data['preferred_language']:'english';
					$id    = $data['id'];
					$session_data = array(
						'user_id'     => $id,
						'email'       => $email,
						'role'        => $role,
						'country'     => $country,
						'is_admin'	  => $is_admin,
						'user_type'   => $user_type,
						'managed_by_id'   => $managed_by_id,
						'site_lang'   => $preferred_language,
						'export_site_lang'   => $preferred_language,
						'logged_in'   => TRUE
					);
					$this->session->set_userdata($session_data);
					redirect('dashboard');
				}else{
					$this->session->set_flashdata('msg','Username or Password is wrong');
					redirect('users/login');
				}
			}else{
				redirect('dashboard');
			}
		}else{
			if($this->session->userdata('logged_in') !== TRUE){
				$this->load->view("users/login");
			}else{
				redirect('dashboard');
			}
		}
	}

	function auth(){
		$email = $this->input->post('email',TRUE);
		$password = md5($this->input->post('password',TRUE));
		$validate = $this->UsersModel->validate($email,$password);
		if($validate->num_rows() > 0){
			if ($this->input->post('remember')=='on') {
				$cookie = array(
					'name'   => 'vetordmgmt_email',
					'value'  => $email,
					'expire' => (3600 * 24 * 7)
				);
				set_cookie($cookie);
				$cookie2 = array(
					'name'   => 'vetordmgmt_password',
					'value'  => $this->input->post('password',TRUE),
					'expire' => (3600 * 24 * 7)
				);
				set_cookie($cookie2);
			} else {
				delete_cookie("vetordmgmt_email");
				delete_cookie("vetordmgmt_password");
			}
			$data  = $validate->row_array();
			if(!empty($data)){
				$email = $data['email'];
				$role  = $data['role'];
				$country  = $data['country'];
				$is_admin  = $data['is_admin'];
				$user_type  = $data['user_type'];
				$managed_by_id  = $data['managed_by_id'];
				$preferred_language  = !empty($data['preferred_language'])?$data['preferred_language']:'english';
				$id    = $data['id'];
				$session_data = array(
					'user_id'   => $id,
					'email'     => $email,
					'role'      => $role,
					'country'   => $country,
					'is_admin'	  => $is_admin,
					'user_type'   => $user_type,
					'managed_by_id'   => $managed_by_id,
					'site_lang'   => $preferred_language,
					'export_site_lang'   => $preferred_language,
					'logged_in' => TRUE
				);
				$this->session->set_userdata($session_data);
				if(!empty($this->session->userdata('redirect_link')) && !empty($this->session->userdata('redirect_id'))){
					redirect($this->session->userdata('redirect_link').$this->session->userdata('redirect_id'));
				}else{
					redirect('dashboard');
				}
			}else{
				$this->session->set_flashdata('msg','You can not able to login this area.');
				redirect('users/login');
			}
		}else{
			$this->session->set_flashdata('msg','Email or Password is wrong');
			redirect('users/login');
		}
	}

	function logout(){
		$this->session->sess_destroy();
		unset($_SESSION);
		redirect('users/login');
	}

	function index(){
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/login');
			exit();
		}
		redirect('dashboard');
	}

	function admin_users_list(){
		$this->load->view('users/admin_users/index');
	}

	function admin_users_getTableData(){
		$role = '1';
		$admin_users = $this->UsersModel->getAdminTableData($role);
		if(!empty($admin_users)){
			foreach ($admin_users as $key => $value) {
				if(!empty($value->name)){
					$admin_users[$key]->name = $value->name;
					$admin_users[$key]->email = $value->email;
					if($value->role ==1 && $value->is_admin ==0){
						$admin_users[$key]->role = 'Super Admin';
					}else{
						$admin_users[$key]->role = 'Admin';
					}
				}
			}
		}

		$total = $this->UsersModel->count_all();
		$totalFiltered = $this->UsersModel->count_admin_filtered($role);
		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $admin_users;
		echo json_encode($ajax); exit();
	}

	function admin_users_addEdit($id= ''){
		$postUser = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $id;
		$this->_data['staff_countries'] = $this->StaffCountriesModel->getRecordAll();
		$data = $this->UsersModel->getRecord($id,"1");
		if ($this->input->post('submit')) {
			$is_email_unique = "";
			if( isset($id) && $id>0 ){
				$current_email = $data['email'];
				if($this->input->post('email') != $current_email){
					$is_email_unique = "|is_unique[ci_users.email]";
				}
			}

			//set rules
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
			if($this->input->post('password')!='') {
				$this->form_validation->set_rules('password', 'password', 'required');
			}

			if ($this->form_validation->run() == FALSE){
				$error = validation_errors();
				$this->load->view('users/admin_users/add_edit','',TRUE);
			}else{
				//user post data
				$postUser['name'] = $this->input->post('name');
				$postUser['email'] = $this->input->post('email');
				if($this->input->post('password')){
					$postUser['password'] = md5($this->input->post('password'));
				}
				$postUser['country'] = $this->input->post('country');
				if($this->input->post('role') == '10'){
					$postUser['role'] = '1';
					$postUser['is_admin'] = '1';
				}else{
					$postUser['role'] = $this->input->post('role');
					$postUser['is_admin'] = '0';
				}
				$postUser['id'] = $id;
				if(is_numeric($id)>0){
					$postUser['updated_by'] = $this->user_id;
					$postUser['updated_at'] = date("Y-m-d H:i:s");
					if ($id = $this->UsersModel->admin_add_edit($postUser)>0) {
						$this->session->set_flashdata('success','Admin data has been updated successfully.');
						redirect('users/admin_users_list');
					}
				}else{
					$postUser['created_by'] = $this->user_id;
					$postUser['created_at'] = date("Y-m-d H:i:s");
					if ($id = $this->UsersModel->admin_add_edit($postUser)) {
						$this->session->set_flashdata('success','Admin data has been added successfully.');
						redirect('users/admin_users_list');
					}
				}
			}
		}

		//load data edit time
		if(is_numeric($this->user_id)>0){
			if(!empty($data)){
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("users/admin_users/add_edit", $this->_data);
	}

	function admin_user_delete($id){
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->UsersModel->admin_user_delete($dataWhere);
			if($delete){
				echo "success"; exit;
			}
		}
		echo "failed"; exit;
	}

	function profile(){
		$postUser = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $this->user_id;
		$this->_data['staff_countries'] = $this->StaffCountriesModel->getRecordAll();
		$data = $this->UsersModel->getUser($this->_data);
		$data = (array)$data;
		if ($this->input->post('submit')) {
			//set unique value
			$is_email_unique = "";
			$current_email = $data['email'];
			if($this->input->post('email') != $current_email){
				$is_email_unique = "|is_unique[ci_users.email]";
			}

			//set rules
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
			if($this->input->post('password')!='') {
				$this->form_validation->set_rules('password', 'password', 'required');
			}

			if ($this->form_validation->run() == FALSE){
				$this->load->view('users/profile','',TRUE);
			}else{
				//user post data
				$postUser['name'] = $this->input->post('name');
				$postUser['email'] = $this->input->post('email');
				if($this->input->post('password')){
					$postUser['password'] = md5($this->input->post('password'));
				}
				$postUser['country'] = $this->input->post('country');
				$postUser['preferred_language'] = $this->input->post('preferred_language');
				if(is_numeric($this->user_id)>0){
					$postUser['updated_at'] = date("Y-m-d H:i:s");
					$postUser['updated_by'] = $this->user_id;
					$postUser['id'] = $this->user_id;
					if ($id = $this->UsersModel->add_edit($postUser)>0) {
						$this->session->set_flashdata('success','Profile has been updated successfully.');
						redirect('users/profile');
					}
				}
			}
		}

		//load data edit time
		if(is_numeric($this->user_id)>0){
			if(!empty($data)){
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("users/profile", $this->_data);
	}

	function get_users_dropdown(){
		$userData = $this->input->post();
		$users_Data = $this->UsersModel->getRecordAll($userData['user_type']);
		echo json_encode($users_Data); exit();
	}

	function get_petOwner_dropdown(){
		$vetUserData = $this->input->post();
		$petOwnerData = $this->UsersModel->get_petOwner_dropdown($vetUserData);
		echo json_encode($petOwnerData); exit();
	}

	function get_phone_number(){
		$vetUserData = $this->input->post();
		$phoneNumberData = $this->UsersModel->getRecord($vetUserData['vet_user_id'],'2');
		echo json_encode($phoneNumberData); exit();
	}

	function get_customer_users(){
		$vetUserData = $this->input->post();
		if($vetUserData['vet_user_id'] > 0){
			$this->db->select('ci_users.id,ci_users.name,ci_users.country');
			$this->db->from('ci_users');
			$this->db->join('ci_user_details', 'ci_user_details.user_id = ci_users.id', 'left');
			$this->db->where("JSON_CONTAINS(ci_user_details.column_field, '[\"".$vetUserData['vet_user_id']."\"]')");
			$this->db->where('ci_users.role', '5');
			$this->db->where('ci_users.user_type IN(1,2)');
			$cUsersData = $this->db->get()->result();
		}else{
			$cUsersData = array();
		}
		echo json_encode($cUsersData); exit();
	}

	function tm_users_list(){
		$this->load->view('users/tm_users/index');
	}

	function tm_users_getTableData(){
		$role = '5';
		$tm_users = $this->UsersModel->getTmUsersTableData($role);
		if(!empty($tm_users)){
			foreach ($tm_users as $key => $value) {
				if(!empty($value->name)){
					$tm_users[$key]->name = $value->name;
					$tm_users[$key]->email = $value->email;
					if($value->user_type == '1'){
						$tm_users[$key]->user_type = 'Practice User';
					}elseif($value->user_type == '2'){
						$tm_users[$key]->user_type = 'Lab User';
					}elseif($value->user_type == '3'){
						$tm_users[$key]->user_type = 'Territory Manager User';
					}
					$tm_users[$key]->preferred_language = ucfirst($value->preferred_language);
				}
			}
		}
		$total = $this->UsersModel->count_all();
		$totalFiltered = $this->UsersModel->count_tm_users_filtered($role);
		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $tm_users;
		echo json_encode($ajax); exit();
	}

	function tm_users_addEdit($id= ''){
		$postUser = [];
		$postUserDetails = [];
		$this->_data['data'] = [];
		$this->_data['countries'] = $this->StaffCountriesModel->getRecordAll();
		$this->_data['staff_members'] = $this->StaffMembersModel->getManagedbyRecordAll();
		$this->_data['id'] = $id;
		$data = $this->UsersModel->getRecord($id,"5");
		$role_id = "2";
		$this->_data['practices'] = $this->UsersModel->getRecordAll($role_id);
		$this->_data['labs'] = $this->UsersModel->getRecordAll("6");
		$this->_data['corporates'] = $this->UsersModel->getRecordAll("7");
		if($id>0 && isset($data['column_field']) && $data['column_field']!=''){
			$tmDatas = $this->UsersDetailsModel->getColumnAllArray($id);
			$tmDatas = array_column($tmDatas, 'column_field', 'column_name');
			$this->practice_data['ids'] = !empty($tmDatas['practices']) ? implode(",",json_decode($tmDatas['practices'])) : '';
			$this->lab_data['ids'] = !empty($tmDatas['labs']) ? implode(",",json_decode($tmDatas['labs'])) : '';
			$this->corporate_data['ids']= !empty($tmDatas['corporates']) ? implode(",",json_decode($tmDatas['corporates'])) : '';
		}else{
			$this->practice_data['ids'] = "";
			$this->lab_data['ids'] = "";
			$this->corporate_data['ids'] = "";
		}

		$this->_data['branches'] = $this->UsersDetailsModel->get_petowner_branch("",$this->practice_data);
		$this->_data['lab_branches'] = $this->UsersDetailsModel->get_petowner_branch("",$this->lab_data);
		$this->_data['corporate_branches'] = $this->UsersDetailsModel->get_petowner_branch("",$this->corporate_data);
		if ($this->input->post('name')!='') {
			//set unique value
			$is_email_unique = "";
			$current_email = $data['email'];
			if($this->input->post('email') != $current_email){
				$this->db->select('email');
				$this->db->from('ci_users');
				$this->db->where('role', 5);
				$this->db->where('email LIKE', $this->input->post('email'));
				$res2 = $this->db->get();
				if($res2->num_rows() > 0){
					$is_email_unique = "|is_unique[ci_users.email]";
				}
			}

			//set rules
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
			if($this->input->post('password')!='') {
				$this->form_validation->set_rules('password', 'password', 'required');
			}
			if ($this->form_validation->run() == FALSE){
				$this->load->view('users/tm_users/add_edit','',TRUE);
			}else{
				//user post data
				$postUser['name'] = $this->input->post('name');
				$postUser['email'] = $this->input->post('email');
				$postUser['country'] = $this->input->post('country');
				$postUser['preferred_language'] = $this->input->post('preferred_language');
				if($this->input->post('password')){
					$postUser['password'] = md5($this->input->post('password'));
				}
				$postUser['user_type'] = $this->input->post('user_type');
				$postUser['managed_by_id'] = !empty($this->input->post('managed_by_id'))?implode(",",$this->input->post('managed_by_id')):'';
				$postUser['id'] = $id;

				$postUserDetails['id'] = $id;
				$postUserDetails['practices'] = ($this->input->post('tmpractices')[0]!='') ? json_encode($this->input->post('tmpractices')) : NULL;
				$postUserDetails['branches'] = NULL;
				$postUserDetails['corporates'] = ($this->input->post('corporates')[0]!='') ? json_encode($this->input->post('corporates')) : NULL;
				$postUserDetails['corporate_branches'] = NULL;
				$postUserDetails['labs'] = NULL;
				$postUserDetails['lab_branches'] = NULL;
				if(is_numeric($id)>0){
					$postUser['updated_by'] = $this->user_id;
					$postUser['updated_at'] = date("Y-m-d H:i:s");
					if ($updid = $this->UsersModel->tmUsers_add_edit($postUser,$postUserDetails)>0) {
						$this->UsersModel->updateTMUsers_practice($id,$postUserDetails['practices']);
						if( $this->user_role=='5' ){
							$this->session->set_flashdata('success','Profile has been updated successfully.');
							redirect('tm_users/edit/'.$this->user_id);
						}else{
							$this->session->set_flashdata('success','TM user data has been updated successfully.');
							redirect('users/tm_users_list');
						}
					}
				}else{
					$postUser['role'] = '5';
					$postUser['created_by'] = $this->user_id;
					$postUser['created_at'] = date("Y-m-d H:i:s");
					if ($insrtid = $this->UsersModel->tmUsers_add_edit($postUser,$postUserDetails)) {
						$this->UsersModel->updateTMUsers_practice($insrtid,$postUserDetails['practices']);
						$this->session->set_flashdata('success','TM user data has been added successfully.');
						redirect('users/tm_users_list');
					}
				}
			}
		}

		//load data edit time
		if(is_numeric($id)>0){
			if(!empty($data)){
				$tmDatas = $this->UsersDetailsModel->getColumnAllArray($id);
				$tmDatas = array_column($tmDatas, 'column_field', 'column_name');
				$data['practices'] = !empty($tmDatas['practices']) ? $tmDatas['practices'] : '';
				$data['labs'] = !empty($tmDatas['labs']) ? $tmDatas['labs'] : '';
				$data['corporates'] = !empty($tmDatas['corporates']) ? $tmDatas['corporates'] : '';
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("users/tm_users/add_edit", $this->_data);
	}

	function tm_users_delete($id){
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->UsersModel->tm_users_delete($dataWhere);
			if($delete){
				echo "success"; exit;
			}
		}
		echo "failed"; exit;
	}

	function customer_users_list(){
		$this->load->view('users/customer_users/index');
	}

	function customer_users_getTableData(){
		$role = '5';
		$tm_users = $this->UsersModel->getCustomerUsersTableData($role);
		if(!empty($tm_users)){
			foreach ($tm_users as $key => $value) {
				if(!empty($value->name)){
					$tm_users[$key]->name = $value->name;
					$tm_users[$key]->email = $value->email;
					if($value->user_type == '1'){
						$tm_users[$key]->user_type = 'Practice User';
					}elseif($value->user_type == '2'){
						$tm_users[$key]->user_type = 'Lab User';
					}else{
						$tm_users[$key]->user_type = 'Not assign user type';
					}
					$tm_users[$key]->preferred_language = ucfirst($value->preferred_language);
				}
			}
		}
		$total = $this->UsersModel->count_all();
		$totalFiltered = $this->UsersModel->count_customer_users_filtered($role);
		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $tm_users;
		echo json_encode($ajax); exit();
	}

	function customer_users_addEdit($id= ''){
		$postUser = [];
		$postUserDetails = [];
		$this->_data['data'] = [];
		$this->_data['countries'] = $this->StaffCountriesModel->getRecordAll();
		$this->_data['staff_members'] = $this->StaffMembersModel->getManagedbyRecordAll();
		$this->_data['id'] = $id;
		$data = $this->UsersModel->getRecord($id,"5");
		$role_id = "2";
		$this->_data['practices'] = $this->UsersModel->getRecordAll($role_id);
		$this->_data['labs'] = $this->UsersModel->getRecordAll("6");
		$this->_data['corporates'] = $this->UsersModel->getRecordAll("7");
		if($id > 0 && isset($data['column_field']) && $data['column_field']!=''){
			$this->db->select("*");
			$this->db->from('ci_user_details');
			$this->db->where("column_name IN('labs','practices','corporates')");
			$this->db->where('user_id', $id);
			$refDetails = $this->db->get()->result_array();
			$columnField = array_column($refDetails, 'column_field', 'column_name');
			if(isset($columnField['practices']) && !empty($columnField['practices'])){
				$this->practice_data['ids']= implode(",",json_decode($columnField['practices']));
			}else{
				$this->practice_data['ids'] = "";
			}
			if(isset($columnField['labs']) && !empty($columnField['labs'])){
				$this->lab_data['ids']= implode(",",json_decode($columnField['labs']));
			}else{
				$this->lab_data['ids'] = "";
			}
			if(isset($columnField['corporates']) && !empty($columnField['corporates'])){
				$this->corporate_data['ids']= implode(",",json_decode($columnField['corporates']));
			}else{
				$this->corporate_data['ids'] = "";
			}
		}else{
			$this->practice_data['ids'] = "";
			$this->lab_data['ids'] = "";
			$this->corporate_data['ids'] = "";
		}

		$this->_data['branches'] = $this->UsersDetailsModel->get_petowner_branch("",$this->practice_data);
		$this->_data['lab_branches'] = $this->UsersDetailsModel->get_petowner_branch("",$this->lab_data);
		$this->_data['corporate_branches'] = $this->UsersDetailsModel->get_petowner_branch("",$this->corporate_data);
		if ($this->input->post('name')!='') {
			//set unique value
			$is_email_unique = "";
			$current_email = $data['email'];
			if($this->input->post('email') != $current_email){
				$this->db->select('email');
				$this->db->from('ci_users');
				$this->db->where('role', 5);
				$this->db->where('email LIKE', $this->input->post('email'));
				$res2 = $this->db->get();
				if($res2->num_rows() > 0){
					$is_email_unique = "|is_unique[ci_users.email]";
				}
			}

			//set rules
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
			if($this->input->post('password')!='') {
				$this->form_validation->set_rules('password', 'password', 'required');
			}
			if ($this->form_validation->run() == FALSE){
				$this->load->view('users/customer_users/add_edit','',TRUE);
			}else{
				//user post data
				$postUser['name'] = $this->input->post('name');
				$postUser['email'] = $this->input->post('email');
				$postUser['country'] = $this->input->post('country');
				$postUser['preferred_language'] = $this->input->post('preferred_language');
				if($this->input->post('password')){
					$postUser['password'] = md5($this->input->post('password'));
				}
				$postUser['user_type'] = $this->input->post('user_type');
				$postUser['managed_by_id'] = !empty($this->input->post('managed_by_id'))?implode(",",$this->input->post('managed_by_id')):'';
				$postUser['id'] = $id;
				$postUserDetails['id'] = $id;
				if($this->input->post('user_type') == 1){
					$postUserDetails['practices'] = ($this->input->post('practices')[0]!='') ? json_encode($this->input->post('practices')) : NULL;
					$postUserDetails['branches'] = ($this->input->post('branches')[0]!='') ? json_encode($this->input->post('branches')) : NULL;
					$postUserDetails['labs'] = NULL;
					$postUserDetails['lab_branches'] = NULL;
					$postUserDetails['corporates'] = NULL;
					$postUserDetails['corporate_branches'] = NULL;
				}elseif($this->input->post('user_type') == 2){
					$postUserDetails['labs'] = ($this->input->post('labs')[0]!='') ? json_encode($this->input->post('labs')) : NULL;
					$postUserDetails['lab_branches'] = ($this->input->post('lab_branches')[0]!='') ? json_encode($this->input->post('lab_branches')) : NULL;
					$postUserDetails['practices'] = NULL;
					$postUserDetails['branches'] = NULL;
					$postUserDetails['corporates'] = NULL;
					$postUserDetails['corporate_branches'] = NULL;
				}
				if(is_numeric($id)>0){
					$postUser['updated_by'] = $this->user_id;
					$postUser['updated_at'] = date("Y-m-d H:i:s");
					if ($id = $this->UsersModel->customerUsers_add_edit($postUser,$postUserDetails)>0) {
						if( $this->user_role=='5' ){
							$this->session->set_flashdata('success','Profile has been updated successfully.');
							redirect('customer_users/edit/'.$this->user_id);
						}else{
							$this->session->set_flashdata('success','Customer user data has been updated successfully.');
							redirect('customer_users');
						}
					}
				}else{
					$postUser['role'] = '5';
					$postUser['created_by'] = $this->user_id;
					$postUser['created_at'] = date("Y-m-d H:i:s");
					if ($id = $this->UsersModel->customerUsers_add_edit($postUser,$postUserDetails)) {
						$this->session->set_flashdata('success','Customer user data has been added successfully.');
						redirect('customer_users');
					}
				}
			}
		}

		//load data edit time
		if(is_numeric($id)>0){
			if(!empty($data)){
				$this->db->select("*");
				$this->db->from('ci_user_details');
				$this->db->where("column_name IN('labs','practices','corporates')");
				$this->db->where('user_id', $id);
				$refDetails = $this->db->get()->result_array();
				$columnField = array_column($refDetails, 'column_field', 'column_name');
				if(!empty($columnField)){
					if(isset($columnField['practices']) && !empty($columnField['practices'])){
						$data['practices'] = $columnField['practices'];
					}else{
						$data['practices'] = "";
					}
					if(isset($columnField['labs']) && !empty($columnField['labs'])){
						$data['labs'] = $columnField['labs'];
					}else{
						$data['labs'] = "";
					}
					if(isset($columnField['corporates']) && !empty($columnField['corporates'])){
						$data['corporates'] = $columnField['corporates'];
					}else{
						$data['corporates'] = "";
					}
				}
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("users/customer_users/add_edit", $this->_data);
	}

	function customer_users_delete($id){
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->UsersModel->customer_users_delete($dataWhere);
			if($delete){
				echo "success"; exit;
			}
		}
		echo "failed"; exit;
	}

	function lims_users_list(){
		$this->load->view('users/lims_users/index');
	}

	function lims_users_getTableData(){
		$role = '10';
		$lims_users = $this->UsersModel->getLIMSTableData($role);
		if(!empty($lims_users)){
			foreach ($lims_users as $key => $value) {
				if(!empty($value->name)){
					$lims_users[$key]->name = $value->name;
					$lims_users[$key]->email = $value->email;
				}
			}
		}

		$total = $this->UsersModel->count_all();
		$totalFiltered = $this->UsersModel->count_lims_filtered($role);
		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $lims_users;
		echo json_encode($ajax); exit();
	}

	function lims_users_addEdit($id= ''){
		$postUser = [];
		$this->_data['data'] = [];
		$this->_data['countries'] = $this->StaffCountriesModel->getRecordAll();
		$this->_data['id'] = $id;
		$data = $this->UsersModel->getRecord($id,"10");
		if ($this->input->post('submit')) {
			$is_email_unique = "";
			if( isset($id) && $id>0 ){
				$current_email = !empty($data['email'])?$data['email']:'';
				if($this->input->post('email') != $current_email){
					$is_email_unique = "|is_unique[ci_users.email]";
				}
			}

			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
			if($this->input->post('password')!='') {
				$this->form_validation->set_rules('password', 'password', 'required');
			}

			if ($this->form_validation->run() == FALSE){
				$error = validation_errors();
				$this->load->view('users/lims_users/add_edit','',TRUE);
			}else{
				$postUser['name'] = $this->input->post('name');
				$postUser['email'] = $this->input->post('email');
				$postUser['country'] = 1;
				if($this->input->post('password')){
					$postUser['password'] = md5($this->input->post('password'));
				}
				$postUser['role'] = '10';
				$postUser['is_admin'] = '0';
				$postUser['id'] = $id;
				if(is_numeric($id)>0){
					$postUser['updated_by'] = $this->user_id;
					$postUser['updated_at'] = date("Y-m-d H:i:s");
					if ($id = $this->UsersModel->lims_add_edit($postUser)>0) {
						$this->session->set_flashdata('success','LIMS User has been updated successfully.');
						redirect('users/lims_users_list');
					}
				}else{
					$postUser['created_by'] = $this->user_id;
					$postUser['created_at'] = date("Y-m-d H:i:s");
					if ($id = $this->UsersModel->lims_add_edit($postUser)) {
						$this->session->set_flashdata('success','LIMS User has been added successfully.');
						redirect('users/lims_users_list');
					}
				}
			}
		}

		if(is_numeric($this->user_id)>0){
			if(!empty($data)){
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("users/lims_users/add_edit", $this->_data);
	}

	function lims_user_delete($id){
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->UsersModel->lims_user_delete($dataWhere);
			if($delete){
				echo "success"; exit;
			}
		}
		echo "failed"; exit;
	}

	function country_users_list(){
		$this->load->view('users/country_users/index');
	}

	function country_users_getTableData(){
		$role = '11';
		$cu_users = $this->UsersModel->getCountryUsersTableData($role);
		if(!empty($cu_users)){
			foreach ($cu_users as $key => $value) {
				if(!empty($value->name)){
					$cu_users[$key]->name = $value->name;
					$cu_users[$key]->email = $value->email;
				}
			}
		}

		$total = $this->UsersModel->country_users_count_all();
		$totalFiltered = $this->UsersModel->count_country_users_filtered($role);
		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $cu_users;
		echo json_encode($ajax); exit();
	}

	function country_users_addEdit($id= ''){
		$postUser = [];
		$this->_data['data'] = [];
		$this->_data['countries'] = $this->StaffCountriesModel->getRecordAll();
		$this->_data['staff_members'] = $this->StaffMembersModel->getManagedbyRecordAll();
		$this->_data['id'] = $id;
		$data = $this->UsersModel->getCountryUsersRecord($id,"11");
		if ($this->input->post('name')!='') {
			//set unique value
			$is_email_unique = "";
			$current_email = $data['email'];
			if($this->input->post('email') != $current_email){
				$this->db->select('email');
				$this->db->from('ci_users');
				$this->db->where('role', 11);
				$this->db->where('email LIKE', $this->input->post('email'));
				$res2 = $this->db->get();
				if($res2->num_rows() > 0){
					$is_email_unique = "|is_unique[ci_users.email]";
				}
			}

			//set rules
			$this->form_validation->set_rules('name', 'name', 'required');
			$this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
			if($this->input->post('password')!='') {
				$this->form_validation->set_rules('password', 'password', 'required');
			}
			if ($this->form_validation->run() == FALSE){
				$this->load->view('users/country_users/add_edit','',TRUE);
			}else{
				//user post data
				$postUser['name'] = $this->input->post('name');
				$postUser['email'] = $this->input->post('email');
				$postUser['country'] = $this->input->post('country');
				if($this->input->post('password')){
					$postUser['password'] = md5($this->input->post('password'));
				}
				$postUser['managed_by_id'] = !empty($this->input->post('managed_by_id'))?implode(",",$this->input->post('managed_by_id')):'';
				if(is_numeric($id)>0){
					$postUser['updated_by'] = $this->user_id;
					$postUser['updated_at'] = date("Y-m-d H:i:s");
					if ($id = $this->UsersModel->countryUsers_add_edit($postUser,$id)>0) {
						if( $this->user_role=='11' ){
							$this->session->set_flashdata('success','Profile has been updated successfully.');
							redirect('country_users/edit/'.$this->user_id);
						}else{
							$this->session->set_flashdata('success','Country Admin User data has been updated successfully.');
							redirect('country_users');
						}
					}
				}else{
					$postUser['role'] = '11';
					$postUser['created_by'] = $this->user_id;
					$postUser['created_at'] = date("Y-m-d H:i:s");
					if ($id = $this->UsersModel->countryUsers_add_edit($postUser,'')) {
						$this->session->set_flashdata('success','Country Admin User data has been added successfully.');
						redirect('country_users');
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
		$this->load->view("users/country_users/add_edit", $this->_data);
	}

	function country_users_delete($id){
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->UsersModel->country_users_delete($dataWhere);
			if($delete){
				echo "success"; exit;
			}
		}
		echo "failed"; exit;
	}

}
?>
