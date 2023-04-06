<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ERROR | E_PARSE);
class PaxResult extends CI_Controller{

	public function __construct(){
		parent::__construct();
	}

	function interpretation($id = ''){
		if ($this->session->userdata('logged_in') !== TRUE) {
			$session_data = array(
				'redirect_link'   => 'orders/interpretation/',
				'redirect_id'     => $id
			);
			$this->session->set_userdata($session_data);
			$this->load->view("users/login");
		}else{
			redirect('orders/interpretation/'.$id);
		}
	}

	function treatment($id = ''){
		if ($this->session->userdata('logged_in') !== TRUE) {
			$session_data = array(
				'redirect_link'   => 'orders/treatment/',
				'redirect_id'     => $id
			);
			$this->session->set_userdata($session_data);
			$this->load->view("users/login");
		}else{
			redirect('orders/treatment/'.$id);
		}
	}

}