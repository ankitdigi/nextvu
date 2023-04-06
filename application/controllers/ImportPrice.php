<?php
error_reporting(E_ERROR | E_PARSE);
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportPrice extends CI_Controller {

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
        
        
        
            
            
            
            $allDataInSheet = $this->PracticeModel->allRecord();
            
            foreach ($allDataInSheet as $value) {
                // echo "<pre>";
                // print_r($value); exit;
                
                $id = $value['id'];
                
                
                //pricing and discounts
                $price_data = [];
                $data = $this->OrdersModel->getRecord($id);
                $order_details = $this->OrdersModel->allData($data['id'],"");
                $allergens = $this->AllergensModel->order_allergens($order_details['allergens']);

                $selected_allergen = json_decode($order_details['allergens']);
                $total_allergen = ($order_details['allergens']!='') ? count(json_decode($order_details['allergens'])) : 0;
                if($data['lab_id']!=0){
                    $practice_lab = $data['lab_id'];
                }else{
                    $practice_lab = $data['vet_user_id'];
                }
                
                if( $total_allergen > 0 ){
                    
                    //Skin Test Pricing
                    if( $data['order_type']=='3' ){
            
                        $single_order_discount = 0.00;
                        $insects_order_discount = 0.00;
                        $selected_allergen_ids = implode(",",$selected_allergen);
                        $insects_allergen = $this->AllergensModel->insect_allergen($selected_allergen_ids);
                        $skin_test_price = $this->PriceCategoriesModel->skin_test_price($practice_lab);
                        $single_price = $skin_test_price[0]['uk_price'];
                        $single_insect_price = $skin_test_price[1]['uk_price'];
                        $single_allergen = $total_allergen - $insects_allergen;
            
                        /**single allergen discount **/
                        $single_discount = $this->PriceCategoriesModel->get_discount("14",$practice_lab);
                        if( !empty($single_discount) ){
                            $single_order_discount = ( $skin_test_price[0]['uk_price'] * $single_discount['uk_discount'] )/100;
                            $single_order_discount = sprintf("%.2f", $single_order_discount);
                        }
                        /**single allergen discount **/
            
                        /**insects allergen discount **/
                        if($insects_allergen > 0){
                            $insects_discount = $this->PriceCategoriesModel->get_discount("15",$practice_lab);
                            if( !empty($insects_discount) ){
                                $insects_order_discount = ( $skin_test_price[1]['uk_price'] * $insects_discount['uk_discount'] )/100;
                                $insects_order_discount = sprintf("%.2f", $insects_order_discount);
                            }
                        }
                        /**insects allergen discount **/
            
                        $final_price = ($single_price * $single_allergen) + ($single_insect_price * $insects_allergen);
                        $price_data['unit_price'] = $final_price - ($single_order_discount + $insects_order_discount);
                        $price_data['order_discount'] = $single_order_discount + $insects_order_discount;
                    } 
            
                    //Serum Test Pricing 
                    if( $data['order_type']=='2' ){
                        $order_discount = 0.00;
                        $product_code_id = $this->session->userdata('product_code_selection');
                        $serum_test_price = $this->PriceCategoriesModel->serum_test_price($product_code_id,$practice_lab);
                        $final_price = $total_allergen * ($serum_test_price[0]['uk_price']);
            
                        /**discount **/
                        $serum_discount = $this->PriceCategoriesModel->get_discount($data['product_code_selection'],$practice_lab);
                        //print_r($serum_discount);
                        if( !empty($serum_discount) ){
                            $order_discount = ( $serum_test_price[0]['uk_price'] * $serum_discount['uk_discount'] )/100;
                            $order_discount = sprintf("%.2f", $order_discount);
                        }
                        /**discount **/
            
                        $price_data['unit_price'] = $final_price - $order_discount;
                        $price_data['order_discount'] = $order_discount;
                    }
            
                    //Immunotherapy Artuvetrin Test Pricing
                    if( $data['order_type']=='1' && $data['sub_order_type']=='1' ){
            
                        $artuvetrin_test_price = $this->PriceCategoriesModel->artuvetrin_test_price($practice_lab);
            
                        //Artuvetrin Therapy 1 – 4 allergens
                        if( $total_allergen <=4 ){
            
                            $order_discount = 0.00;
                            /**discount **/
                            $artuvetrin_discount = $this->PriceCategoriesModel->get_discount("16",$practice_lab);
                            if( !empty($artuvetrin_discount) ){
                                $order_discount = ( $artuvetrin_test_price[0]['uk_price'] * $artuvetrin_discount['uk_discount'] )/100;
                                $order_discount = sprintf("%.2f", $order_discount);
                            }
                            /**discount **/
            
                            $price_data['unit_price'] = $artuvetrin_test_price[0]['uk_price'] - $order_discount;
                            $price_data['order_discount'] = $order_discount;
            
                        //Artuvetrin Therapy 5 – 8 allergens
                        }elseif( $total_allergen >4 && $total_allergen <=8 ){
            
                            $order_discount = 0.00;
                            /**discount **/
                            $artuvetrin_discount = $this->PriceCategoriesModel->get_discount("17",$practice_lab);
                            if( !empty($artuvetrin_discount) ){
                                $order_discount = ( $artuvetrin_test_price[1]['uk_price'] * $artuvetrin_discount['uk_discount'] )/100;
                                $order_discount = sprintf("%.2f", $order_discount);
                            }
                            /**discount **/
            
                            $price_data['unit_price'] = $artuvetrin_test_price[1]['uk_price'] - $order_discount;
                            $price_data['order_discount'] = $order_discount;
            
                        //Artuvetrin Therapy more than 8 allergens
                        }elseif( $total_allergen >8 ){
                            $final_price = 0.00;
                            $first_range_price = 0.00;
                            $order_first_discount = 0.00;
                            $order_second_discount = 0.00;
                            $quotient = (int)($total_allergen/8);
                            $remainder = (int)(fmod($total_allergen, 8));
            
                            /**discount **/
                            $artuvetrin_second_discount = $this->PriceCategoriesModel->get_discount("17",$practice_lab);
                            
                            if( !empty($artuvetrin_second_discount) ){
                                $order_second_discount = ( $artuvetrin_test_price[1]['uk_price'] * $artuvetrin_second_discount['uk_discount'] )/100;
                                $order_second_discount = sprintf("%.2f", $order_second_discount);
                            }
                            /**discount **/
            
                            $second_range_price = ( $quotient * ($artuvetrin_test_price[1]['uk_price']) ) -$order_second_discount;
                            if($remainder>0){
            
                                /**discount **/
                                $artuvetrin_first_discount = $this->PriceCategoriesModel->get_discount("16",$practice_lab);
                                if( !empty($artuvetrin_first_discount) ){
                                    $order_first_discount = ( $artuvetrin_test_price[0]['uk_price'] * $artuvetrin_first_discount['uk_discount'] )/100;
                                    $order_first_discount = sprintf("%.2f", $order_first_discount);
                                }
                                /**discount **/
            
                                $first_range_price = $artuvetrin_test_price[0]['uk_price'] - $order_first_discount;
                            }
                            $final_price = $first_range_price + $second_range_price;
            
                            $price_data['unit_price'] = $final_price;
                            $price_data['order_discount'] = $order_first_discount + $order_second_discount;
                        }
            
                    }//if
            
                    //Sublingual Immunotherapy (SLIT) Pricing
                    if( $data['order_type']=='1' && $data['sub_order_type']=='2' ){
            
                        //Sublingual Single Price
                        $selected_allergen_ids = implode(",",$selected_allergen);
                        $culicoides_allergen = $this->AllergensModel->culicoides_allergen($selected_allergen_ids);
                        $slit_test_price = $this->PriceCategoriesModel->slit_test_price($practice_lab);
                        $single_price = $slit_test_price[0]['uk_price'];
                        $double_price = $slit_test_price[1]['uk_price'];
                        $single_with_culicoides = $slit_test_price[2]['uk_price'];
                        $double_with_culicoides = $slit_test_price[3]['uk_price'];
                        $single_allergen = $total_allergen - $culicoides_allergen;
                        $order_discount = 0.00;
            
                        if( $data['single_double_selection']=='1' && $culicoides_allergen==0 ){
            
                            /**discount **/
                            $slit_discount = $this->PriceCategoriesModel->get_discount("18",$practice_lab);
                            if( !empty($slit_discount) ){
                                $order_discount = ( $slit_test_price[0]['uk_price'] * $slit_discount['uk_discount'] )/100;
                                $order_discount = sprintf("%.2f", $order_discount);
                            }
                            /**discount **/
            
                            $final_price = $total_allergen * $single_price;
                            $final_price = $final_price - $order_discount;
            
                        }else if( $data['single_double_selection']=='2' && $culicoides_allergen==0 ){
            
                            /**discount **/
                            $slit_discount = $this->PriceCategoriesModel->get_discount("19",$practice_lab);
                            if( !empty($slit_discount) ){
                                $order_discount = ( $slit_test_price[1]['uk_price'] * $slit_discount['uk_discount'] )/100;
                                $order_discount = sprintf("%.2f", $order_discount);
                            }
                            /**discount **/
            
                            $final_price = $total_allergen * $double_price;
                            $final_price = $final_price - $order_discount;
            
                        }else if( $data['single_double_selection']=='1' && $culicoides_allergen>0 ){
            
                            /**discount **/
                            $slit_discount = $this->PriceCategoriesModel->get_discount("20",$practice_lab);
                            if( !empty($slit_discount) ){
                                $order_discount = ( $slit_test_price[2]['uk_price'] * $slit_discount['uk_discount'] )/100;
                                $order_discount = sprintf("%.2f", $order_discount);
                            }
                            /**discount **/
            
                            $final_price = ($single_price * $single_allergen) + ($single_with_culicoides * $culicoides_allergen);
                            $final_price = $final_price - $order_discount;
            
                        }else if( $data['single_double_selection']=='2' && $culicoides_allergen>0 ){
            
                            /**discount **/
                            $slit_discount = $this->PriceCategoriesModel->get_discount("21",$practice_lab);
                            //print_r($slit_discount);
                            if( !empty($slit_discount) ){
                                $order_discount = ( $slit_test_price[3]['uk_price'] * $slit_discount['uk_discount'] )/100;
                                $order_discount = sprintf("%.2f", $order_discount);
                            }
                            /**discount **/
            
                            $final_price = ($double_price * $single_allergen) + ($double_with_culicoides * $culicoides_allergen);
                            $final_price = $final_price - $order_discount;
                        }
            
                        
                        $price_data['unit_price'] = $final_price;
                        $price_data['order_discount'] = $order_discount;
            
                    }//if
            
                } 
                //pricing and discounts
                
                $price_data['updated_by'] = 1;
                $price_data['updated_at'] = date("Y-m-d H:i:s");
                
                $this->PracticeModel->order_edit($price_data,$id);
                print_r($price_data);
                
                
                
                $i++;

            }//foreach  
              
            
            
                    
            
        
                

    }

}

?>