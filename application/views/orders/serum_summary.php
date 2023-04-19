<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<style>
			#canvasDiv{position:relative;border:2px dashed grey;height:300px;width:746px}
			.pdn0{padding:0px}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line('summary'); ?>
						<small><?php echo $this->lang->line('Control_Panel'); ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"> <?php echo $this->lang->line('Orders_Management'); ?></a></li>
						<li class="active"><?php echo $this->lang->line('Orders'); ?></li>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content">
					<!--breadcrumb-->
					<?php $this->load->view("orders/breadcrumbs"); ?>
					<!--breadcrumb-->
					<!--alert msg-->
					<?php if(!empty($this->session->flashdata('success'))){ ?>
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> <?php echo $this->lang->line('alert'); ?></h4>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>
					<?php if(!empty($this->session->flashdata('error'))){ ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-warning"></i> <?php echo $this->lang->line('alert'); ?></h4>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php } ?>
					<!--alert msg-->
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- form start -->
							<?php echo form_open('', array('name'=>'signatureForm', 'id'=>'signatureForm')); ?>
								<!-- general form elements -->
								<div class="box box-primary">
									<?php 
									if($order_details['pet_id']>0){
										$this->db->select('type,breed_id,other_breed,gender,age,age_year');
										$this->db->from('ci_pets');
										$this->db->where('id', $order_details['pet_id']);
										$petinfo = $this->db->get()->row_array();
										if($petinfo['breed_id']>0){
											$this->db->select('name');
											$this->db->from('ci_breeds');
											$this->db->where('id', $petinfo['breed_id']);
											$breedinfo = $this->db->get()->row_array();
										}else{
											if($petinfo['other_breed']!=""){
												$breedinfo = array("name"=>$petinfo['other_breed']);
											}else{
												$breedinfo = array();
											}
										}
									}else{
										$petinfo = array();
										$breedinfo = array();
									}

									if($order_details['vet_user_id']>0){
										$refDatas = $this->UsersDetailsModel->getColumnAllArray($order_details['vet_user_id']);
										$refDatas = array_column($refDatas, 'column_field', 'column_name');
										$add_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'].', ' : '';
										$add_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'].', ' : '';
										$add_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
										$city = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
										$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
										$fulladdress = $add_1.$add_2.$add_3;
									}else{
										$fulladdress = '';
										$city = '';
										$postcode = '';
									}

									$serumdata = $this->OrdersModel->getSerumTestRecord($id);
									//echo "<pre>";print_r($order_details);die;
									if(!empty($order_details['product_code_selection'])){
										$this->db->select('name');
										$this->db->from('ci_price');
										$this->db->where('id', $order_details['product_code_selection']);
										$ordeType = $this->db->get()->row()->name;
										if(in_array($order_details['product_code_selection'],array(33,34))) {
											$ordeType = $ordeType." Screening Expanded";
										} elseif($order_details['product_code_selection'] == 38) {
											$ordeType = "PAX Environmental & Food Screening Expanded";
										}
									}else{
										$ordeType = 'Serum Testing';
									}
									?>
									<div class="box-header with-border">
										<p class="pull-right"><?php echo $this->lang->line('Order_Type'); ?>: <b><?php echo $ordeType; ?></b></p>
										<p class="pull-left">
											<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line('back'); ?></a>
											<?php if($userData['role']==1 || $userData['role']==11){ ?> &nbsp;&nbsp;
											<button type="button" class="btn btn-primary editSummery"><i class="fa fa-pencil-square-o" style="font-size:initial;"></i>
											<?php echo $this->lang->line('edit_your_order'); ?></button>
											<?php } ?>
											<?php if( ( $controller=='repeatOrder' && $userData['role']!=1 && $userData['role']!=11) || ($userData['role']==2 && $data['signature']=='') || ($userData['role']==5 && $data['signature']=='')  || ($userData['role']==6 && $data['signature']=='') || ($userData['role']==7 && $data['signature']=='') ) { ?> &nbsp;&nbsp;
											<a class="btn btn-primary signatureModal" data-order_id="<?php echo $id; ?>" data-toggle="modal" data-target="#signatureModal" title="Submit" id="btn-save"><?php echo $this->lang->line('submit_order'); ?></a>
											<?php }else{ ?>
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('submit_order'); ?></button>
											<?php } ?>
										</p>
									</div><!-- /.box-header -->
									<div class="box-body">
										<div class="row">
											<div class="col-sm-12 col-md-12 col-lg-12">
												<h3 style="color:#346a7e;"><?php echo $this->lang->line('Practice_details'); ?></h3>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-2 col-md-2 col-lg-2">
													<div class="form-group">
														<label><?php echo $this->lang->line('order_date'); ?>:</label>
														<input type="text" class="form-control" value="<?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?>" readonly /> 
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="form-group">
														<label><?php echo $this->lang->line('veterinary_surgeon'); ?>:</label>
														<input type="text" class="form-control practiceCls" name="veterinary_surgeon" value="<?php echo $order_details['name']; ?>" readonly /> 
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="form-group">
														<label><?php echo $this->lang->line('phone'); ?>:</label>
														<input type="text" class="form-control practiceCls" name="phone_number" value="<?php echo $order_details['phone_number']; ?>" readonly /> 
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="form-group">
														<label><?php echo $this->lang->line('Veterinary_practice'); ?>:</label>
														<input type="text" class="form-control" value="<?php echo $order_details['practice_name']; ?>" readonly /> 
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="form-group">
														<label><?php echo $this->lang->line('Email'); ?>:</label>
														<input type="text" class="form-control practiceCls" name="email" value="<?php echo $order_details['email']; ?>" readonly /> 
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="form-group">
														<label><?php echo $this->lang->line('practice_details'); ?>:</label>
														<input type="text" class="form-control" value="<?php echo $fulladdress; ?>" readonly /> 
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="form-group">
														<label>
															<?php echo $this->lang->line('results_will_be_delivered_by_email'); ?></label>
														<?php /* <div class="checkbox">
															<label><input type="checkbox" class="practiceCls" name="shipping_materials" <?php if($order_details['shipping_materials'] == '1'){ echo 'checked="checked"'; } ?> disabled> I would like to order more serum test shipping materials</label>
														</div> */ ?>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-2 col-md-2 col-lg-2">
													<div class="form-group">
														<label><?php echo $this->lang->line('city'); ?>:</label>
														<input type="text" class="form-control" value="<?php echo $city; ?>" readonly /> 
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="form-group">
														<label><?php echo $this->lang->line('post_code'); ?>:</label>
														<input type="text" class="form-control" value="<?php echo $postcode; ?>" readonly /> 
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5"></div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<h3 style="color:#346a7e;">
												<?php echo $this->lang->line('pet_and_pet_owner_details'); ?></h3>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7 pdn0">
													<div class="col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label><?php echo $this->lang->line('pet_owners_first_name'); ?>:</label>
															<input type="text" class="form-control" value="<?php echo $order_details['pet_owner_name']; ?>" readonly /> 
														</div>
													</div>
													<div class="col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label><?php echo $this->lang->line('pet_owners_last_name'); ?>:</label>
															<input type="text" class="form-control" value="<?php echo $order_details['po_last']; ?>" readonly /> 
														</div>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="form-group">
														<label><?php echo $this->lang->line('Animal_Name'); ?>:</label>
														<input type="text" class="form-control" value="<?php echo $order_details['pet_name']; ?>" readonly /> 
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12" style="height:15px"></div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7 pdn0">
													<div class="col-sm-6 col-md-6 col-lg-6">
														<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
															<label style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?></label>
														</div>
														<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="checkbox">
																		<label><input type="checkbox" <?php if($order_details['species_name'] == 'Dog'){ echo 'checked="checked"'; } ?> disabled> <?php echo $this->lang->line('dog'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="checkbox">
																		<label><input type="checkbox" <?php if($order_details['species_name'] == 'Cat'){ echo 'checked="checked"'; } ?> disabled> <?php echo $this->lang->line('cat'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="checkbox">
																		<label><input type="checkbox" <?php if($order_details['species_name'] == 'Horse'){ echo 'checked="checked"'; } ?> disabled><?php echo $this->lang->line('horse'); ?> </label>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label><?php echo $this->lang->line('breed'); ?>:</label>
															<input type="text" class="form-control" value="<?php echo $breedinfo['name']; ?>" readonly /> 
														</div>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
															<label style="color:#346a7e;">&nbsp;</label>
														</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="gender" <?php if($petinfo['gender'] == '1'){ echo 'checked="checked"'; } ?> disabled><?php echo $this->lang->line('male'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="gender" <?php if($petinfo['gender'] == '2'){ echo 'checked="checked"'; } ?> disabled><?php echo $this->lang->line('female'); ?> </label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="form-group">
														<label><?php echo $this->lang->line('age_month_and_year'); ?>:</label>
														<?php
														$years = !empty($petinfo['age_year'])?$petinfo['age_year'].'Year, ':'';
														$months = !empty($petinfo['age'])?$petinfo['age'].'Month':'';
														?>
														<input type="text" class="form-control" value="<?php echo $years.$months; ?>" readonly />
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="form-group">
														<label><?php echo $this->lang->line('date_serum_drawn'); ?></label>
														<input type="text" class="form-control practiceCls" name="serum_drawn_date" value="<?php echo !empty($serumdata['serum_drawn_date'])?date('d/m/Y',strtotime($serumdata['serum_drawn_date'])):''; ?>" readonly />
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<h3 style="color:#346a7e;"><?php echo $this->lang->line('medical_history'); ?></h3>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"><?php echo $this->lang->line('patient_affected'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="major_symptoms[]" value="1" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('pruritus_itch'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="major_symptoms[]" value="5" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '5' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('skin_lesions'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="major_symptoms[]" value="2" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?> disabled>  <?php echo $this->lang->line('otitis'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="major_symptoms[]" value="3" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?> disabled>  <?php echo $this->lang->line('respiratory_signs'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="major_symptoms[]" value="6" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '6' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('ocular_signs'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="major_symptoms[]" value="7" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '7' ) !== false) ){ echo 'checked'; } ?> disabled>  <?php echo $this->lang->line('anaphylaxis'); ?></label>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="major_symptoms[]" value="4" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '4' ) !== false) ){ echo 'checked'; } ?> disabled>  <?php echo $this->lang->line('gastro_intestinal_signs'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="major_symptoms[]" value="0" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '0' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('other'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="form-group">
																<input type="text" class="form-control practiceCls" name="other_symptom" value="<?php echo isset($serumdata['other_symptom']) ? $serumdata['other_symptom'] : ''; ?>" readonly /> 
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;">&nbsp;</label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="form-group">
															<label><?php echo $this->lang->line('at_what_age_did_these_symptoms_first_appear'); ?></label>
															<input type="text" class="form-control practiceCls" name="symptom_appear" value="<?php echo $serumdata['symptom_appear_age'].' years '.$serumdata['symptom_appear_age_month'].' months'; ?>" readonly /> 
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12" style="height:15px"></div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"> <?php echo $this->lang->line('symptoms_most_obvious'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="when_obvious_symptoms[]" value="1" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('spring'); ?> Spring</label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="when_obvious_symptoms[]" value="2" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('summer'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="when_obvious_symptoms[]" value="3" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('fall'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="when_obvious_symptoms[]" value="4" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '4' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('winter'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="when_obvious_symptoms[]" value="5" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '5' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('Year_round'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0"></div>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;">
														<?php echo $this->lang->line('where_symptoms_most_obvious'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="where_obvious_symptoms[]" value="1" <?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('indoors'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="where_obvious_symptoms[]" value="2" <?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('outdoors'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="where_obvious_symptoms[]" value="3" <?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?> disabled> 
																	<?php echo $this->lang->line('no_difference'); ?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12" style="height:15px"></div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"><?php echo $this->lang->line('clinical_diagnosis'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('food_s'); ?>: </b></label>
															</div>
															<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="diagnosis_food" value="1" <?php if( isset($serumdata['diagnosis_food']) && (strpos( $serumdata['diagnosis_food'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('yes'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="diagnosis_food" value="2" <?php if( isset($serumdata['diagnosis_food']) && (strpos( $serumdata['diagnosis_food'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('no'); ?></label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>: </b></label>
															</div>
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<div class="form-group">
																	<input type="text" class="form-control practiceCls" name="other_diagnosis_food" placeholder="Please Specify" value="<?php echo set_value('other_diagnosis_food',isset($serumdata['other_diagnosis_food']) ? $serumdata['other_diagnosis_food'] : '');?>" readonly>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('hymenoptera_stings'); ?> </b></label>
															</div>
															<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="diagnosis_hymenoptera" value="1" <?php if( isset($serumdata['diagnosis_hymenoptera']) && (strpos( $serumdata['diagnosis_hymenoptera'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('yes'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="diagnosis_hymenoptera" value="2" <?php if( isset($serumdata['diagnosis_hymenoptera']) && (strpos( $serumdata['diagnosis_hymenoptera'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('no'); ?></label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>: </b></label>
															</div>
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<div class="form-group">
																	<input type="text" class="form-control practiceCls" name="other_diagnosis_hymenoptera" placeholder="Please Specify" value="<?php echo set_value('other_diagnosis_hymenoptera',isset($serumdata['other_diagnosis_hymenoptera']) ? $serumdata['other_diagnosis_hymenoptera'] : '');?>" readonly>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;">&nbsp;</label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"><?php echo $this->lang->line('food_challenge'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox"  class="practiceCls" name="food_challenge[]" value="1" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '1' ) !== false) ){ echo 'checked'; } ?> disabled> &lt; 3 <?php echo $this->lang->line('hours'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox"  class="practiceCls" name="food_challenge[]" value="2" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('3_12_hours'); ?>  </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox"  class="practiceCls" name="food_challenge[]" value="3" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('12_24_hours'); ?>  </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox"  class="practiceCls" name="food_challenge[]" value="4" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '4' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('24_48_h'); ?>  </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox"  class="practiceCls" name="food_challenge[]" value="5" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '5' ) !== false) ){ echo 'checked'; } ?> disabled> &gt; <?php echo $this->lang->line('48_h'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0"></div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('other_s'); ?></b></label>
															</div>
															<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="diagnosis_other" value="1" <?php if( isset($serumdata['diagnosis_other']) && (strpos( $serumdata['diagnosis_other'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('yes'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="diagnosis_other" value="2" <?php if( isset($serumdata['diagnosis_other']) && (strpos( $serumdata['diagnosis_other'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('no'); ?></label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('specify_which_one_s_if_known'); ?> </b></label>
															</div>
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<div class="form-group">
																	<input type="text" class="form-control practiceCls" name="other_diagnosis" placeholder="Please Specify" value="<?php echo set_value('other_diagnosis',isset($serumdata['other_diagnosis']) ? $serumdata['other_diagnosis'] : '');?>" readonly>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12" style="height:15px"></div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"><?php echo $this->lang->line('exposed_following_animals'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="regularly_exposed[]" value="1" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('cats'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="regularly_exposed[]" value="2" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '2' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('dogs'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="regularly_exposed[]" value="3" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('horses'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="regularly_exposed[]" value="4" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '4' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('cattle'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="regularly_exposed[]" value="5" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '5' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('mice'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="regularly_exposed[]" value="6" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '6' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('guinea_pigs'); ?> </label>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="regularly_exposed[]" value="7" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '7' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('rabbits'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="regularly_exposed[]" value="0" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '0' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('other_s'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0">
															<div class="form-group">
																<input type="text" class="form-control practiceCls" name="other_exposed" value="<?php echo isset($serumdata['other_exposed']) ? $serumdata['other_exposed'] : ''; ?>" readonly /> 
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;">&nbsp;</label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"><?php echo $this->lang->line('malassezia_infections'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="malassezia_infections[]" value="1" <?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('malassezia_otitis'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="malassezia_infections[]" value="2" <?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('malassezia_dermatitis'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0"></div>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12" style="height:15px"></div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"><?php echo $this->lang->line('receiving_drugs'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="receiving_drugs[]" value="1" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '1' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('glucocorticoids_oral_topical_injectable'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0">
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_1" value="1" <?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '1' ) !== false) ){ echo 'checked'; } ?> disabled>
																		<?php echo $this->lang->line('no_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_1" value="2" <?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('fair_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_1" value="3" <?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="receiving_drugs[]" value="2" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('ciclosporin'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0">
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_2" value="1" <?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '1' ) !== false) ){ echo 'checked'; } ?> disabled>
																		<?php echo $this->lang->line('no_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_2" value="2" <?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('fair_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_2" value="3" <?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="receiving_drugs[]" value="3" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('oclacitinib'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0">
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_3" value="1" <?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '1' ) !== false) ){ echo 'checked'; } ?> disabled>
																		<?php echo $this->lang->line('no_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_3" value="2" <?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('fair_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_3" value="3" <?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="receiving_drugs[]" value="4" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '4' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('lokivetmab'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0">
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_4" value="1" <?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('no_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_4" value="2" <?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('fair_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_4" value="3" <?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="receiving_drugs[]" value="5" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '5' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('antibiotics'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0">
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_5" value="1" <?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('no_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_5" value="2" <?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('fair_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_5" value="3" <?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="receiving_drugs[]" value="6" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '6' ) !== false) ){ echo 'checked'; } ?> disabled> <?php echo $this->lang->line('antifungals'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0">
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_6" value="1" <?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('no_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_6" value="2" <?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('fair_response'); ?></label>
																	</div>
																</div>
															</div>
															<div class="col-sm-4 col-md-4 col-lg-4 pdn0">
																<div class="form-group">
																	<div class="radio">
																		<label><input type="radio" class="practiceCls" name="receiving_drugs_6" value="3" <?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '3' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
															<label style="color:#346a7e;"><b><?php echo $this->lang->line('treatment_ectoparasites'); ?></b></label>
														</div>
														<div class="col-sm-3 col-md-3 col-lg-3 pdn0">
															<div class="form-group">
																<label>&nbsp;</label>
																<div class="radio">
																	<label><input type="radio" class="practiceCls" name="treatment_ectoparasites" value="1" <?php if( isset($serumdata['treatment_ectoparasites']) && (strpos( $serumdata['treatment_ectoparasites'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('yes'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-3 col-md-3 col-lg-3 pdn0">
															<div class="form-group">
																<label>&nbsp;</label>
																<div class="radio">
																	<label><input type="radio" class="practiceCls" name="treatment_ectoparasites" value="2" <?php if( isset($serumdata['treatment_ectoparasites']) && (strpos( $serumdata['treatment_ectoparasites'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('no'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('specify_which_one_s_if_known'); ?> </b></label>
															</div>
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<div class="form-group">
																	<input type="text" class="form-control practiceCls" name="other_ectoparasites" placeholder="Please Specify" value="<?php echo set_value('other_ectoparasites',isset($serumdata['other_ectoparasites']) ? $serumdata['other_ectoparasites'] : '');?>" readonly>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
															<label style="color:#346a7e;"><b><?php echo $this->lang->line('elimination_diet'); ?></b></label>
														</div>
														<div class="col-sm-3 col-md-3 col-lg-3 pdn0">
															<div class="form-group">
																<label>&nbsp;</label>
																<div class="radio">
																	<label><input type="radio" class="practiceCls" name="elimination_diet" value="1" <?php if( isset($serumdata['elimination_diet']) && (strpos( $serumdata['elimination_diet'], '1' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('yes'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-3 col-md-3 col-lg-3 pdn0">
															<div class="form-group">
																<label>&nbsp;</label>
																<div class="radio">
																	<label><input type="radio" class="practiceCls" name="elimination_diet" value="2" <?php if( isset($serumdata['elimination_diet']) && (strpos( $serumdata['elimination_diet'], '2' ) !== false) ){ echo 'checked'; } ?> disabled><?php echo $this->lang->line('no'); ?></label>
																</div>
															</div>
														</div>
														<div class="col-sm-6 col-md-6 col-lg-6 pdn0">
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('specify_which_one_s_if_known'); ?> </b></label>
															</div>
															<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
																<div class="form-group">
																	<input type="text" class="form-control practiceCls" name="other_elimination" placeholder="Please Specify" value="<?php echo set_value('other_elimination',isset($serumdata['other_elimination']) ? $serumdata['other_elimination'] : '');?>" readonly>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
															<div class="form-group">
																<label style="color:#346a7e;"><b><?php echo $this->lang->line('additional_information'); ?></b></label>
																<textarea class="form-control practiceCls" name="additional_information" readonly><?php echo isset($serumdata['additional_information']) ? $serumdata['additional_information'] : '';?></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12" style="height:15px"></div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"><?php echo $this->lang->line('zoonotic_disease'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="zoonotic_disease" value="1" <?php echo ( isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease']==1) ? 'checked' : ''; ?> disabled><?php echo $this->lang->line('yes'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" class="practiceCls" name="zoonotic_disease" value="0" <?php echo ( isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease']==0) ? 'checked' : ''; ?> disabled><?php echo $this->lang->line('no'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0"></div>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<label style="color:#346a7e;"><?php echo $this->lang->line('medication'); ?></label>
													</div>
													<div class="col-sm-12 col-md-12 col-lg-12 pdn0">
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" value="1" class="practiceCls" name="medication" <?php echo ( isset($serumdata['medication']) && $serumdata['medication']==1) ? 'checked' : ''; ?> disabled><?php echo $this->lang->line('yes'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-2 col-md-2 col-lg-2 pdn0">
															<div class="form-group">
																<div class="checkbox">
																	<label><input type="checkbox" value="0" class="practiceCls" name="medication" <?php echo ( isset($serumdata['medication']) && $serumdata['medication']==0) ? 'checked' : ''; ?> disabled><?php echo $this->lang->line('no'); ?> </label>
																</div>
															</div>
														</div>
														<div class="col-sm-8 col-md-8 col-lg-8 pdn0"></div>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-7 col-md-7 col-lg-7">
													<div class="form-group">
														<label><?php echo $this->lang->line('if_yes_please_specify'); ?></label>
														<textarea class="form-control practiceCls" name="zoonotic_disease_dec" readonly><?php echo isset($serumdata['zoonotic_disease_dec']) ? $serumdata['zoonotic_disease_dec'] : '';?></textarea>
													</div>
												</div>
												<div class="col-sm-5 col-md-5 col-lg-5">
													<div class="form-group">
														<label><?php echo $this->lang->line('if_yes_please_specify'); ?></label>
														<textarea class="form-control practiceCls" name="medication_desc" readonly><?php echo isset($serumdata['medication_desc']) ? $serumdata['medication_desc'] : '';?></textarea>
													</div>
												</div>
											</div>
										</div><!-- /.row -->
									</div><!-- /.box-body -->
								</div><!-- /.box -->

								<div class="box box-primary collapsed-box">
									<div class="box-header with-border">
										<h3 class="box-title"><?php echo $this->lang->line('allergens_tested_for_with'); ?> <?php echo $ordeType; ?> for <?php echo $order_details['species_name']; ?></h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
											</button>
										</div>
									</div>
									<div class="box-body" style="display: none;">
										<div class="row select">
											<?php
											if($order_details['serum_type'] == '1'){
												$getAllergenParent = $this->AllergensModel->getAllergenParentPax($order_details['allergens']);
												foreach ($getAllergenParent as $apkey => $apvalue){
													$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
													?>
													<div class="col-sm-12 col-md-12 col-lg-12">
														<div class="tab-content">
															<div class="tab-pane active">
																<section id="hand">
																	<h4 class="page-header allergen_header" style="font-size:15px; font-weight:700;"><?php echo $apvalue['pax_name']; ?></h4>
																	<div class="row fontawesome-icon-list">
																		<?php foreach($subAllergens as $skey => $svalue){ ?>
																			<div class="col-md-4 col-sm-4">
																				<div class="checkbox">
																					<label><input name="allergens[]" value="<?php echo $svalue['id']; ?>" type="checkbox" class="allergensCls" checked="checked" disabled><?php echo $svalue['pax_name']; ?></label>
																				</div>
																			</div>
																		<?php } ?>
																	</div><!--row-->
																</section>
															</div><!--tab-pane-->
														</div><!--tab-content-->
													</div><!-- /.col -->
												<?php 
												}
											}else{
												$getAllergenParent = $this->AllergensModel->getAllergenParent($order_details['allergens']);
												foreach ($getAllergenParent as $apkey => $apvalue){
													$subAllergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $order_details['allergens']);
													?>
													<div class="col-sm-12 col-md-12 col-lg-12">
														<div class="tab-content">
															<div class="tab-pane active">
																<section id="hand">
																	<h4 class="page-header allergen_header" style="font-size:15px; font-weight:700;"><?php echo $apvalue['name']; ?></h4>
																	<div class="row fontawesome-icon-list">
																		<?php foreach($subAllergens as $skey => $svalue){ ?>
																			<div class="col-md-4 col-sm-4">
																				<div class="checkbox">
																					<label><input name="allergens[]" value="<?php echo $svalue['id']; ?>" type="checkbox" class="allergensCls" checked="checked" disabled><?php echo $svalue['name']; ?></label>
																				</div>
																			</div>
																		<?php } ?>
																	</div><!--row-->
																</section>
															</div><!--tab-pane-->
														</div><!--tab-content-->
													</div><!-- /.col -->
												<?php 
												}
											} ?>
										</div><!-- /.row -->
									</div><!-- /.box-body -->
								</div><!-- /.box -->

								<!-- order price and order discount elements -->
								<?php
								$zonesIds = $this->OrdersModel->checkZones($id);
								if(!empty($zonesIds) && in_array("5", $zonesIds) && $order_details['lab_id'] > 0 && $order_details['serum_type'] == '1'){
								?>
									<input type="hidden" name="unit_price" id="unit_price" value="<?php echo set_value('unit_price',isset($order_details['unit_price']) ? $order_details['unit_price'] : '');?>">
									<input type="hidden" id="default_unit_price" value="<?php echo ($final_price-$shipping_cost); ?>" >
									<input type="hidden" name="price_currency" value="<?php echo isset($price_currency) ? $price_currency:'€'; ?>" >
									<input type="hidden" name="order_discount" id="order_discount" value="0">
									<input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
								<?php }else{ ?>
									<div class="box box-primary">
										<div class="box-header with-border"><h3 class="box-title">
											<?php echo $this->lang->line('price_details'); ?></h3></div>
										<!-- /.box-header -->
										<div class="box-body">
											<div class="row">
												<?php if($userData['role']!=1 && $userData['role']!=11 && $id > 0){ $readonly = "readonly"; }else{ $readonly = ""; } ?>
												<div class="col-sm-4 col-md-4 col-lg-4">
													<div class="form-group">
														<label><?php echo $this->lang->line('order_price'); ?></label>
														<div class="input-group">
															<div class="input-group-addon">
																<?php echo isset($price_currency) ? $price_currency:'£'; ?>
															</div>
															<input type="text" class="form-control" name="unit_price" id="unit_price" placeholder="<?php echo $this->lang->line('enter_order_price'); ?>" value="<?php echo set_value('unit_price',isset($final_price) ? $final_price : '');?>"  <?php echo $readonly; ?>>
															<input type="hidden" id="default_unit_price" value="<?php echo ($final_price-$shipping_cost); ?>" >
															<input type="hidden" name="price_currency" value="<?php echo isset($price_currency) ? $price_currency:'€'; ?>" >
														</div>
														<?php echo form_error('unit_price', '<div class="error">', '</div>'); ?>
													</div>
												</div><!-- /.col -->
												<div class="col-sm-4 col-md-4 col-lg-4">
													<?php if( ($userData['role']==1 ) || ($userData['role']==11) || ($userData['role']==2) || $id==''  || ($userData['role']==6 && isset($data['order_discount']) && $data['order_discount']!='0.00') || ($userData['role']==5 && isset($order_discount) && $order_discount!='0.00') || ($userData['role']==7 && isset($data['order_discount']) && $data['order_discount']!='0.00') ){ ?>
														<div class="form-group">
															<label><?php echo $this->lang->line('order_discount'); ?></label>
															<div class="input-group">
																<div class="input-group-addon">
																	<?php echo isset($price_currency) ? $price_currency:'£'; ?>
																</div>
																<input type="text" class="form-control" name="order_discount" placeholder="<?php echo $this->lang->line('enter_order_discount'); ?>Enter Order Discount" value="<?php echo set_value('order_discount',isset($order_discount) ? $order_discount : '');?>"  <?php echo $readonly; ?>>
																<?php echo form_error('order_discount', '<div class="error">', '</div>'); ?>
																<input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
															</div>
														</div>
													<?php } ?>
												</div><!-- /.col -->
											</div><!-- /.row -->
										</div><!-- /.box-body -->
									</div><!-- /.box -->
									<!-- order price and order discount elements -->
								<?php } ?>

								<?php if(($order_details['is_repeat_order'] && $order_details['cep_id'] && $userData['role'] != 1 && $userData['role'] != 11) || ($userData['role'] == 2 && $data['signature'] == '') || ($userData['role'] == 5 && $data['signature'] == '') || ($userData['role'] == 6 && $data['signature'] == '') || ($userData['role'] == 7 && $data['signature'] == '')){ ?>
									<input type="hidden" id="signature" name="signature">
									<input type="hidden" name="signaturesubmit" value="1">
									<div class="box-footer">
										<p class="pull-right">
											<a class="btn btn-primary signatureModal" data-order_id="<?php echo $id; ?>" data-toggle="modal" data-target="#signatureModal" title="Submit" id="btn-save"><?php echo $this->lang->line('submit_order'); ?></a>
										</p>
									</div>
								<?php }else{ ?>
									<input type="hidden" name="signaturesubmit" value="0">
									<input type="hidden" name="edit_summery" id="edit_summery" value="0">
									<div class="box-footer">
										<?php
										if(isset($this->zones) && !empty($this->zones)){
											$zoneby = explode(",",$this->zones);
										}else{
											$zoneby = array();
										}
										if((!empty($zoneby) && in_array("6", $zoneby))) { ?>
										<p class="pull-left">
											<a class="btn btn-primary" target="_blank" href="<?php echo base_url('orders/print_form/'.$order_details['id']); ?>" title="Submit" id="btn-save"><?php echo $this->lang->line('print_requisition_form'); ?></a>
										</p>
										<?php } ?>
										<p class="pull-right">
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('submit_order'); ?></button>
										</p>
									</div>
								<?php } ?>
							<?php echo form_close(); ?>   
							<!-- form end -->
						</div><!--/.col (left) -->
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
		<!--signature modal-->
		<div class="modal fade" id="signatureModal">
			<div class="modal-dialog" style="width:65%">
				<div class="modal-content">
					<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo $this->lang->line('signature'); ?></h4>
					</div>
					<?php echo form_open('', array('name'=>'signatureModalForm', 'id'=>'signatureModalForm')); ?>
						<div class="modal-body">
							<label><?php echo $this->lang->line('signature'); ?></label>
							<div class="form-control" id="canvasDiv"></div>
							<button type="button" class="btn btn-danger" id="reset-btn"><?php echo $this->lang->line('clear'); ?></button>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line('confirm'); ?></button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		<!--signature modal-->
		<script>
		$(document).ready(function(){
			$("#shipping_cost").keyup(function(){
				if($("#default_unit_price").val() != ''){
					var price = $("#default_unit_price").val();
					var scost = $(this).val();
					if(parseFloat(scost) > 0){
						var disc = parseFloat(price) + parseFloat(scost);
						$("#unit_price").attr('value',parseFloat(disc.toFixed(2)));
					}else{
						$("#unit_price").attr('value',parseFloat(price));
					}
				}
			});

			$(".editSummery").click(function() {
				$("input.practiceCls").attr("style","background-color:#fff!important");
				$("input.practiceCls").removeAttr("readonly");
				$("textarea.practiceCls").removeAttr("readonly");
				$("input.practiceCls").removeAttr("disabled");
				$("input.allergensCls").removeAttr("readonly");
				$("input.allergensCls").removeAttr("disabled");
				$("#edit_summery").val(1);
				$(".editSummery").attr("id","submitSummery");
				$(".editSummery").text("Update your order");
				$("#submitSummery").removeClass("editSummery");
			});
		});
		</script>
		<?php if( $userData['role']==2 || $userData['role']==5 || $userData['role']==6 || $userData['role']==7 ) { ?>
		<script>
		$(document).ready(() => {
			var canvasDiv = document.getElementById('canvasDiv');
			var canvas = document.createElement('canvas');
			canvas.setAttribute('id', 'canvas');
			canvasDiv.appendChild(canvas);
			$("#canvas").attr('height', $("#canvasDiv").outerHeight());
			$("#canvas").attr('width', $("#canvasDiv").width());
			if (typeof G_vmlCanvasManager != 'undefined') {
				canvas = G_vmlCanvasManager.initElement(canvas);
			}

			context = canvas.getContext("2d");
			$('#canvas').mousedown(function(e) {
				var offset = $(this).offset()
				var mouseX = e.pageX - this.offsetLeft;
				var mouseY = e.pageY - this.offsetTop;

				paint = true;
				addClick(e.pageX - offset.left, e.pageY - offset.top);
				redraw();
			});

			$('#canvas').mousemove(function(e) {
				if (paint) {
					var offset = $(this).offset()
					//addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
					addClick(e.pageX - offset.left, e.pageY - offset.top, true);
					console.log(e.pageX, offset.left, e.pageY, offset.top);
					redraw();
				}
			});

			$('#canvas').mouseup(function(e) {
				paint = false;
			});

			$('#canvas').mouseleave(function(e) {
				paint = false;
			});

			var clickX = new Array();
			var clickY = new Array();
			var clickDrag = new Array();
			var paint;
			function addClick(x, y, dragging) {
				clickX.push(x);
				clickY.push(y);
				clickDrag.push(dragging);
			}

			$("#reset-btn").click(function() {
				context.clearRect(0, 0, window.innerWidth, window.innerWidth);
				clickX = [];
				clickY = [];
				clickDrag = [];
			});

			var drawing = false;
			var mousePos = {
				x: 0,
				y: 0
			};
			var lastPos = mousePos;
			canvas.addEventListener("touchstart", function(e) {
				mousePos = getTouchPos(canvas, e);
				var touch = e.touches[0];
				var mouseEvent = new MouseEvent("mousedown", {
					clientX: touch.clientX,
					clientY: touch.clientY
				});
				canvas.dispatchEvent(mouseEvent);
			}, false);

			canvas.addEventListener("touchend", function(e) {
				var mouseEvent = new MouseEvent("mouseup", {});
				canvas.dispatchEvent(mouseEvent);
			}, false);

			canvas.addEventListener("touchmove", function(e) {
				var touch = e.touches[0];
				var offset = $('#canvas').offset();
				var mouseEvent = new MouseEvent("mousemove", {
					clientX: touch.clientX,
					clientY: touch.clientY
				});
				canvas.dispatchEvent(mouseEvent);
			}, false);

			// Get the position of a touch relative to the canvas
			function getTouchPos(canvasDiv, touchEvent) {
				var rect = canvasDiv.getBoundingClientRect();
				return {
					x: touchEvent.touches[0].clientX - rect.left,
					y: touchEvent.touches[0].clientY - rect.top
				};
			}

			var elem = document.getElementById("canvas");
			var defaultPrevent = function(e) {
				e.preventDefault();
			}
			elem.addEventListener("touchstart", defaultPrevent);
			elem.addEventListener("touchmove", defaultPrevent);
			function redraw() {
				lastPos = mousePos;
				for (var i = 0; i < clickX.length; i++) {
					context.beginPath();
					if (clickDrag[i] && i) {
						context.moveTo(clickX[i - 1], clickY[i - 1]);
					} else {
						context.moveTo(clickX[i] - 1, clickY[i]);
					}
					context.lineTo(clickX[i], clickY[i]);
					context.closePath();
					context.stroke();
				}
			}

			//signature
			$(document).on('click','.signatureModal', function(){
				var order_id = $(this).data('order_id'); 
				$('#order_id_modal').val(order_id);
			});

			$(document).on('submit', '#signatureModalForm', function(event) {
				event.preventDefault();
				var mycanvas = document.getElementById('canvas');
				var img = mycanvas.toDataURL("image/png");
				anchor = $("#signature");
				anchor.val(img);
				$("#signatureForm").submit();

			});
			//signature
		});
		</script>
		<?php } ?>
	</body>
</html>
