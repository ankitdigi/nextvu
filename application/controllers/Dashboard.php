<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller{

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/login');
		}
		$this->zones = $this->session->userdata('managed_by_id');
		$this->user_role = $this->session->userdata('role');
	}

	function index(){
		if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2' || $this->session->userdata('user_type') == '3')){
			$this->load->view('dashboard/index_vet');
		}else{
			$this->load->view('dashboard/index');
		}
	}

	public function export_database() {
		ini_set('max_execution_time', 3000);
		ini_set('memory_limit','-1');
		$this->load->dbutil();
		$prefs = array(
			'format' => 'zip',
			'filename' => 'dtmdata.sql'
		);
		$backup = $this->dbutil->backup($prefs);
		$db_name = 'dtm-on-' . date("Y-m-d-H-i-s") . '.zip';
		$save = 'uploaded_files/' . $db_name;
		$this->load->helper('file');
		write_file($save, $backup);
		//$this->load->helper('download');
		//force_download($db_name, $backup);
		echo 'Done';
		exit;
	}

}