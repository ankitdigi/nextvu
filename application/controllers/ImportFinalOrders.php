<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportFinalOrders extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->load->model('OrdersModel');
    }

	
    function import_data(){
        
        $inputFileName = FCPATH.'uploaded_files/orderData/SBook2.xlsx';
        
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
            $orderData = [];
            $subAllerString = '';
            
            foreach ($allDataInSheet as $value) {
                if($flag){
                $flag =false;
                continue;
                }

                //get practice id
                $practice_name = trim($value['D']);
                $this->db->select('id,name');
                $this->db->from("ci_users");
                $this->db->where('name', $practice_name); 
                $this->db->where('role', '2');  
                //echo $this->db->last_query(); 
                $practice_details = $this->db->get()->row_array();
                //$practice_exists = $this->db->get()->num_rows();


                //get petowner id
                $petowner_name = '';
                $this->db->select('id,last_name');
                $this->db->from("ci_users");
                $this->db->where('name', $petowner_name); 
                $this->db->or_where('last_name', $petowner_name); 
                $this->db->where('role', '3');  
                //echo $this->db->last_query(); 
                $petowner_details = $this->db->get()->row_array();
                //$petowner_exists = $this->db->get()->num_rows();

                //get pet id
                $pet_name = '';
                $this->db->select('id,name');
                $this->db->from("ci_pets");
                $this->db->where('name', $pet_name); 
                //echo $this->db->last_query(); 
                $pet_details = $this->db->get()->row_array();
                //$pet_exists = $this->db->get()->num_rows();

                if( !empty($practice_details) ){

                    $orderData['vet_user_id'] = $practice_details['id']; 
                    //$orderData['lab_id'] = $practice_details['id']; 
                }

                if( !empty($petowner_details) ){

                    $orderData['pet_owner_id'] = 0; 
                }

                if( !empty($pet_details) ){

                    $orderData['pet_id'] = 0; 
                }

                //allergen
                $allergen_name = trim($value['N']);
                if($allergen_name!=''){

                    $this->db->select('id,name');
                    $this->db->from("ci_allergens");
                    $this->db->where('name', $allergen_name); 
                    $this->db->where('parent_id', '0'); 
                    $allergen_details = $this->db->get()->row_array();

                    if( !empty($allergen_details) ){

                        //sub allergen
                        $subAllerString = trim($value['O']);
                        $subAllergens = preg_split('/(?=[A-Z])/', $subAllerString);
                        $order_allergen = array();
                        foreach ($subAllergens as $sakey => $savalue) {
                            $subAllergen_exists  = '';
                            if($savalue!=''){
                                $savalue = trim($savalue);

                                $this->db->select('id,name');
                                $this->db->from("ci_allergens");
                                $this->db->where('name', $savalue); 
                                $this->db->where('parent_id!=0'); 
                                $subAllergen_details = $this->db->get()->row_array();
                                $order_allergen[] = $subAllergen_details['id'];
                                
                            }//if savalue
                            
                        }//foreach
                    }//if empty($allergen_details)
                }//allergen_name

                

                foreach ($subAllergens as $skey => $svalue) {
                    $subAllergen_exists  = '';
                    if($svalue!=''){
                        $svalue = trim($svalue);

                        $this->db->select('id,name');
                        $this->db->from("ci_allergens");
                        $this->db->where('name', $svalue); 
                        $this->db->where('parent_id!=0'); 
                        //echo $this->db->last_query(); 
                        //echo "<br>";
                        $subAllergen_exists = $this->db->get()->num_rows();

                    }
                    
                }

                //auto generate order number
                $this->db->select('MAX(order_number) AS order_number');
                $this->db->from("ci_orders");
                $order_number = $this->db->get()->row_array(); 
                
                if($order_number['order_number']=='' || $order_number['order_number']==NULL || $order_number['order_number']==0){
                    $final_order_number = 1001;
                }else{
                    $final_order_number = $order_number['order_number']+1;
                }
                

                //store order data
                if( !empty($order_allergen) ){
                    $allergen_json = json_encode($order_allergen);
                }else{
                    $allergen_json = '';
                }

                //get delivery practice id
                $vetlab_name = trim($value['G']);
                $this->db->select('id,name');
                $this->db->from("ci_users");
                $this->db->where('name', $vetlab_name); 
                $this->db->where('role', '2'); 
                $practice_d = $this->db->get()->row_array(); 

                $order_date = trim($value['B']);
                $order_date = date("Y-m-d",strtotime($order_date));
                $orderData['batch_number'] = trim($value['A']);
                $orderData['order_date'] = $order_date;
                $orderData['reference_number'] = trim($value['C']);
                $orderData['allergens'] = $allergen_json;
                $orderData['order_type'] = '3';
                $orderData['sub_order_type'] = '4';
                $orderData['plc_selection'] = '1';
                $orderData['order_number'] = $final_order_number;
                $orderData['delivery_cust_ref'] = trim($value['F']);
                $orderData['customer_order_ref'] = trim($value['L']);
                $orderData['delivery_practice_id'] = (!empty($practice_d)) ? $practice_d['id'] : 0;
                $orderData['order_can_send_to'] = (!empty($practice_d)) ? 1 : 0;
                $orderData['created_by'] = '1';
                $orderData['created_at'] = $date;


                
                echo "<pre>";
                print_r($orderData);
                $this->db->insert("ci_orders",$orderData);
                $order_id = $this->db->insert_id();
                
                
                $i++;

            }//foreach  
              
             
               
            if($order_id){
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