<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportPetOwners extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
    }

	
    function import_data(){
        
        $inputFileName = FCPATH.'uploaded_files/orderData/Book2.xlsx';
        
        require_once APPPATH . "/libraries/PHPExcel-1.8/Classes/PHPExcel.php";
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $flag = true;
            
            // echo "<pre>";
            // print_r($allDataInSheet);
            $result = [];
            $i=2;
            $date = date("Y-m-d H:i:s");
            $practiceData = [];
            $practiceDetailsData = [];
            $subAllerString = '';
            foreach ($allDataInSheet as $value) {
                if($flag){
                $flag =false;
                continue;
                }

                //get pet owner id from name
                $petOwner_name = $value['S'];
                if($petOwner_name!=''){
                    $petOwner_name = trim($petOwner_name);
                    $this->db->select('id,last_name');
                    $this->db->from("ci_users");
                    $this->db->where('name', $petOwner_name); 
                    $this->db->or_where('last_name', $petowner_name);
                    $this->db->where('role', '3');  
                    //echo $this->db->last_query(); 
                    //$petOwner_details = $this->db->get()->row_array();
                    $petOwner_exists = $this->db->get()->num_rows();
                    
                    if( $petOwner_exists==0 ){

                        $petOwnerData['last_name'] = $petOwner_name;
                        $petOwnerData['role'] = '3';
                        $petOwnerData['country'] = '1';
                        $petOwnerData['post_code'] = NULL;
                        $petOwnerData['created_by'] = '1';
                        $petOwnerData['created_at'] = $date;
    
                        $this->db->insert("ci_users",$petOwnerData);
                        $petOwner_id = $this->db->insert_id();

                        //get practice id
                        $practice_name = trim($value['D']);
                        $this->db->select('id,name');
                        $this->db->from("ci_users");
                        $this->db->where('name', $practice_name); 
                        $this->db->where('role', '2');  
                        //echo $this->db->last_query(); 
                        $practice_details = $this->db->get()->row_array();
                        //$practice_exists = $this->db->get()->num_rows();

                        if( !empty($practice_details) ){

                            //insert to ci_petowners_to_vetusers
                            $petownersToVetUsersData['pet_owner_id'] = $petOwner_id;
                            $petownersToVetUsersData['vet_user_id'] = $practice_details['id'];
                            $petownersToVetUsersData['user_type'] = '2';

                            $this->db->insert("ci_petowners_to_vetusers",$petownersToVetUsersData);
                            $petownersToVetUsers_id = $this->db->insert_id();
                        }

                    }
                    

                }
                
                
                
                $i++;

            }//foreach  
              
             
               
            if($petownersToVetUsers_id){
              echo "Imported successfully";
            }else{
              echo "ERROR1 !";
            }             
            
        } catch (Exception $e) {
            echo "ERROR2 !";
        }
                

    }

}

?>