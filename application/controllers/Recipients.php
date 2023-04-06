<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Recipients extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
	    $this->load->model('RecipientsModel');
        
    }

	function list(){

        $this->load->view('recipients/index');
    }

    function getTableData(){
        
        $Recipients = $this->RecipientsModel->getTableData(); 
        
            if(!empty($Recipients)){
                foreach ($Recipients as $key => $value) {
                    if(!empty($value->email)){
                        $Recipients[$key]->first_name = $value->first_name." ".$value->last_name;
                        $Recipients[$key]->email = $value->email;
                    }
                }
            }
          
        $total = $this->RecipientsModel->count_all();
        $totalFiltered = $this->RecipientsModel->count_filtered();
        
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $Recipients;
        echo json_encode($ajax); exit();
    }

    function addEdit($id= ''){
        
        $recipientData = [];
        
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        
        $data = $this->RecipientsModel->getRecord($id);
        
        if ($this->input->post('email')) {
            
            
            //set unique value
            $is_email_unique = "";
            $current_email = $data['email']; 
            
            if($this->input->post('email') != $current_email){
                $is_email_unique = "|is_unique[ci_artuvetrin_recipients.email]";
            }
            

            //set rules
            $this->form_validation->set_rules('order_type[]', 'order_type', 'required');
            $this->form_validation->set_rules('first_name', 'first_name', 'required');
            $this->form_validation->set_rules('last_name', 'last_name', 'required');
            $this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
            

            if ($this->form_validation->run() == FALSE){
                $this->load->view('recipients/add_edit','',TRUE);
            }else{
                
                $recipientData = $this->input->post();
                $recipientData['order_type'] = ( !empty($this->input->post('order_type')) && $this->input->post('order_type')[0]!='') ? json_encode($this->input->post('order_type')) : NULL;
                $recipientData['id'] = $id;
                
                

                if(is_numeric($id)>0){
                    $recipientData['updated_by'] = $this->user_id;
                    $recipientData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->RecipientsModel->add_edit($recipientData)>0) {
                        $this->session->set_flashdata('success','recipient data has been updated successfully.');
                        redirect('recipients/list');
                    }
                }else{
                    $recipientData['created_by'] = $this->user_id;
                    $recipientData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->RecipientsModel->add_edit($recipientData)) {
                        $this->session->set_flashdata('success','recipient data has been added successfully.');
                        redirect('recipients/list');
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
          //print_r($this->_data); exit;
  		$this->load->view("recipients/add_edit", $this->_data);
        
    }

    function delete($id){
        
        if ($id != '' && is_numeric($id)) {

            $dataWhere['id'] = $id;
            $delete = $this->RecipientsModel->delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
    
        }
        echo "failed"; exit;
    }

}

?>