<?php
error_reporting(E_ERROR | E_PARSE);
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportAllPet extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->load->model('PracticeModel');
        $this->load->model('OrdersModel');
        $this->load->model('AllergensModel');
        $this->load->model('PriceCategoriesModel');
    }

	function import_data(){
        
        
        
            
            
            
        $allDataInSheet = $this->PracticeModel->allPets();
        
        
          
        
        
                
        
    
            

    }
    

}

?>