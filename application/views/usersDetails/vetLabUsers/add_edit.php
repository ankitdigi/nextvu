<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line("practice");?>
						<small><?php echo $this->lang->line("control_panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line("home");?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("users_management");?></a></li>
						<li class="active"><?php echo $this->lang->line("practice");?></li>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content">
					<!--alert msg-->
					<?php if(!empty($this->session->flashdata('success'))){ ?>
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> <?php echo $this->lang->line("alert");?></h4>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>
					<?php if(!empty($this->session->flashdata('error'))){ ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-warning"></i><?php echo $this->lang->line("alert");?></h4>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php } ?>
					<!--alert msg-->
					<?php $userData = logged_in_user_data(); ?>
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- form start -->
							<?php echo form_open('', array('name'=>'vluForm', 'id'=>'vluForm')); ?>
								<!-- vet/Lab form elements start-->
								<div class="box box-primary">
									<div class="box-header with-border">
										<h3 class="box-title"><?php echo $this->lang->line("practice");?> <?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3>
									</div><!-- /.box-header -->
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label><?php echo $this->lang->line("practice_name");?> <span class="required">*</span></label>
													<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line("enter_first_name");?>" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
													<?php echo form_error('name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group" style="display:none">
													<label><?php echo $this->lang->line("last_name");?></label>
													<input type="text" class="form-control" name="last_name" placeholder="<?php echo $this->lang->line("enter_last_name");?>" value="<?php echo set_value('last_name',isset($data['last_name']) ? $data['last_name'] : '');?>">
													<?php echo form_error('last_name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("email");?> <span class="required">*</span></label>
													<input type="email" class="form-control" name="email" placeholder="<?php echo $this->lang->line("enter_email");?>" value="<?php echo set_value('email',isset($data['email']) ? $data['email'] : '');?>" required="">
													<?php echo form_error('email', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group" style="display:none">
													<label><?php echo $this->lang->line("password");?></label>
													<input type="password" class="form-control" name="password" placeholder="<?php echo $this->lang->line("enter_password");?>" value="<?php echo set_value('password');?>" <?php echo (isset($id) && $id>0) ? '' : '' ?>>
													<?php echo form_error('password', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("phone_number");?></label>
													<input type="text" class="form-control" name="phone_number" placeholder="<?php echo $this->lang->line("enter_phone_number");?>" value="<?php echo set_value('phone_number',isset($data['phone_number']) ? $data['phone_number'] : '');?>">
													<?php echo form_error('phone_number', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("Address_1");?></label>
													<input type="text" class="form-control" name="add_1" placeholder="<?php echo $this->lang->line("Address_1");?>" value="<?php echo set_value('add_1',isset($data['add_1']) ? $data['add_1'] : '');?>">
													<?php echo form_error('add_1', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("Address_2");?></label>
													<input type="text" class="form-control" name="add_2" placeholder="<?php echo $this->lang->line("Address_2");?>" value="<?php echo set_value('add_2',isset($data['add_2']) ? $data['add_2'] : '');?>">
													<?php echo form_error('add_2', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("Address_3");?></label>
													<input type="text" class="form-control" name="add_3" placeholder="<?php echo $this->lang->line("Address_3");?>" value="<?php echo set_value('add_3',isset($data['add_3']) ? $data['add_3'] : '');?>">
													<?php echo form_error('add_3', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("Address_4");?></label>
													<input type="text" class="form-control" name="add_4" placeholder="<?php echo $this->lang->line("enter_Address_4");?>" value="<?php echo set_value('add_4',isset($data['add_4']) ? $data['add_4'] : '');?>">
													<?php echo form_error('add_4', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("town_city");?></label>
													<input type="text" class="form-control" name="address_2" placeholder="<?php echo $this->lang->line("enter_city");?>" value="<?php echo set_value('address_2',isset($data['address_2']) ? $data['address_2'] : '');?>">
													<?php echo form_error('address_2', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("post_code");?></label>
													<input type="text" class="form-control" name="address_3" placeholder="<?php echo $this->lang->line("enter_post_code");?>" value="<?php echo set_value('address_3',isset($data['address_3']) ? $data['address_3'] : '');?>">
													<?php echo form_error('address_3', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("country");?> <span class="required">*</span></label>
													<?php 
													$options = array();
													$options[''] = '-- Select --';
													if(!empty($countries)){
														foreach ($countries as $country) {
															$country_id = $country['id'];
															$options[$country_id] = $country['name'];
														}
													}
													$attr = 'class="form-control" data-live-search="true" required=""';
													echo form_dropdown('country',$options,set_value('country',isset($data['country']) ? $data['country'] : ''),$attr); ?>
													<?php echo form_error('country', '<div class="error">', '</div>'); ?>
												</div>
											</div><!-- /.col -->
											<div class="col-sm-6 col-md-6 col-lg-6">
												<?php if(($userData['role'] == 1) || ($userData['role'] == 11 && count(explode(",",$this->zones)) > 1)){ ?>
													<div class="form-group">
														<label><?php echo $this->lang->line("managed_by");?> <span class="required">*</span></label>
														<select class="form-control selectpicker" data-live-search="true" name="managed_by_id[]" id="managed_by_id" multiple="multiple" required="required">
															<?php
															echo '<option value="0">Select '.$this->lang->line("managed_by").'</option>';
															if(!empty($staff_members)){
																foreach($staff_members as $row){
																	if(isset($data['managed_by_id']) && in_array($row['id'],explode(",",$data['managed_by_id']))){
																		echo '<option value="'. $row['id'] .'" selected="selected">'. $row['managed_by_name'] .'</option>';
																	}else{
																		echo '<option value="'. $row['id'] .'">'. $row['managed_by_name'] .'</option>';
																	}
																}
															}
															?>
														</select>
														<?php echo form_error('managed_by_id', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group">
														<label>Invoiced By <span class="required">*</span></label>
														<select class="form-control" name="invoiced_by" id="invoiced_by" required="required">
															<?php
															echo '<option value="">Select Invoiced By</option>';
															if(!empty($staff_members)){
																foreach($staff_members as $row){
																	if(isset($data['invoiced_by']) && in_array($row['id'],explode(",",$data['invoiced_by']))){
																		echo '<option value="'. $row['id'] .'" selected="selected">'. $row['managed_by_name'] .'</option>';
																	}else{
																		echo '<option value="'. $row['id'] .'">'. $row['managed_by_name'] .'</option>';
																	}
																}
															}
															?>
														</select>
														<?php echo form_error('invoiced_by', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group">
														<label>Report by <span class="required">*</span></label>
														<select class="form-control" name="report_by" id="report_by" required="required">
															<?php
															echo '<option value="">Select Report by</option>';
															if(!empty($staff_members)){
																foreach($staff_members as $row){
																	if(isset($data['report_by']) && ($row['id'] == $data['report_by'])){
																		echo '<option value="'. $row['id'] .'" selected="selected">'. $row['managed_by_name'] .'</option>';
																	}else{
																		echo '<option value="'. $row['id'] .'">'. $row['managed_by_name'] .'</option>';
																	}
																}
															}
															?>
														</select>
														<?php echo form_error('report_by', '<div class="error">', '</div>'); ?>
													</div>
												<?php }else{ ?> 
													<div class="form-group">
														<?php if(isset($id) && $id > 0){ ?>
														<input type="hidden" name="managed_by_id[]" value="<?php echo isset($data['managed_by_id']) ? $data['managed_by_id'] : ''; ?>">
														<input type="hidden" name="invoiced_by" value="<?php echo isset($data['invoiced_by']) ? $data['invoiced_by'] : ''; ?>">
														<input type="hidden" name="report_by" value="<?php echo isset($data['report_by']) ? $data['report_by'] : ''; ?>">
														<?php }else{ ?>
														<input type="hidden" name="managed_by_id[]" value="<?php echo isset($this->zones) ? $this->zones : ''; ?>">
														<input type="hidden" name="invoiced_by" value="">
														<input type="hidden" name="report_by" value="">
														<?php } ?>
													</div>
												<?php } ?>
												<div class="form-group">
													<label><?php echo $this->lang->line("tm_users");?></label>
													<?php 
													$options = array();
													$options[''] = "Nothing selected";
													if(!empty($tmUsers)){
														foreach ($tmUsers as $user) {
															$user_id = $user['id'];
															$options[$user_id] = $user['name'];
														}
													}
													$attr = 'class="form-control selectpicker default_one" data-live-search="true"';
													echo form_dropdown('tm_user_id[]', $options,  isset($data['tm_user_id']) ? json_decode($data['tm_user_id']) : '', $attr); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("account_ref");?> <span class="required">*</span></label>
													<input type="text" class="form-control" name="account_ref" placeholder="<?php echo $this->lang->line("enter_account_ref");?>" value="<?php echo set_value('account_ref',isset($data['account_ref']) ? $data['account_ref'] : '');?>" <?php echo ($userData['role']==5) ? "readonly" : "" ?> required="required">
													<?php echo form_error('account_ref', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group uk_sageCode" <?php if((isset($data['invoiced_by']) && $data['invoiced_by'] == 1) || ($userData['role'] == 11 && in_array('1',explode(",",$this->zones)) && count(explode(",",$this->zones)) == 1)){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
													<label>UK Sage 200 Code <span class="required">*</span></label>
													<input type="text" class="form-control" id="uk_sage_code" name="uk_sage_code" placeholder="Enter UK Sage 200 Code" value="<?php echo set_value('uk_sage_code',isset($data['uk_sage_code']) ? $data['uk_sage_code'] : '');?>" <?php echo ($userData['role']==5) ? "readonly" : "" ?> <?php if((isset($data['invoiced_by']) && $data['invoiced_by'] == 1) || ($userData['role'] == 11 && in_array('1',explode(",",$this->zones)) && count(explode(",",$this->zones)) == 1)){ echo 'required="required"'; } ?>>
													<?php echo form_error('uk_sage_code', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group" style="display:none">
													<label><?php echo $this->lang->line("tax_code");?></label>
													<input type="text" class="form-control" name="tax_code" placeholder="<?php echo $this->lang->line("enter_tax_code");?>" value="<?php echo set_value('tax_code',isset($data['tax_code']) ? $data['tax_code'] : '');?>">
													<?php echo form_error('tax_code', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group" style="display:none">
													<label><?php echo $this->lang->line("customer_vat_reg");?></label>
													<input type="text" class="form-control" name="vat_reg" placeholder="<?php echo $this->lang->line("enter_vat_reg");?>" value="<?php echo set_value('vat_reg',isset($data['vat_reg']) ? $data['vat_reg'] : '');?>">
													<?php echo form_error('vat_reg', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group" style="display:none">
													<label><?php echo $this->lang->line("customer_country_code");?></label>
													<input type="text" class="form-control" name="country_code" placeholder="<?php echo $this->lang->line("enter_country_code");?>" value="<?php echo set_value('country_code',isset($data['country_code']) ? $data['country_code'] : '');?>">
													<?php echo form_error('country_code', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Comments Only for Nextmune Users</label>
													<textarea class="form-control" name="comment" rows="3" placeholder="<?php echo $this->lang->line("enter_comment");?>"><?php echo set_value('comment',isset($data['comment']) ? $data['comment'] : '');?></textarea>
													<?php echo form_error('comment', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("Corporates");?></label>
													<?php
													$options = array();
													$options[''] = "Nothing selected";
													if(!empty($corporates)){
														foreach ($corporates as $key => $value) {
															$options[$value['id']] = $value['name'];
														}
													}
													$attr = 'class="form-control selectpicker default_one" data-live-search="true"';
													echo form_dropdown('corporates[]', $options,  isset($data['corporates']) ? json_decode($data['corporates']) : '', $attr);
													?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("buying_groups");?></label>
													<?php
													$options = array();
													$options[''] = "Nothing selected";
													if(!empty($buying_groups)){
														foreach ($buying_groups as $key => $value) {
															$options[$value['id']] = $value['name'];
														}
													}
													$attr = 'class="form-control selectpicker default_one" data-live-search="true"';
													echo form_dropdown('buying_groups[]', $options,  isset($data['buying_groups']) ? json_decode($data['buying_groups']) : '', $attr);
													?>
												</div>
												<div class="form-group" style="display:none">
													<label><?php echo $this->lang->line("referral_specialist");?></label>
													<?php
													$options = array();
													$options[''] = "Nothing selected";
													if(!empty($referrals)){
														foreach ($referrals as $key => $value) {
															$options[$value['id']] = $value['name'];
														}
													}
													$attr = 'class="form-control selectpicker" data-live-search="true"';
													echo form_dropdown('referrals[]', $options,  isset($data['referrals']) ? json_decode($data['referrals']) : '', $attr);
													?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("rcvs_number");?></label>
													<input type="text" class="form-control" name="rcds_number" placeholder="<?php echo $this->lang->line("enter_rcvs_number");?>" value="<?php echo set_value('rcds_number',isset($data['rcds_number']) ? $data['rcds_number'] : '');?>">
													<?php echo form_error('rcds_number', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<div class="checkbox">
														<label><input type="checkbox" name="vat_applicable" value="1" <?php echo (isset($data['vat_applicable']) && $data['vat_applicable'] == 1) ? 'checked="checked"' : ''; ?>><?php echo $this->lang->line("VAT_applicable");?></label>
													</div>
													<?php echo form_error('vat_applicable', '<div class="error">', '</div>'); ?>
												</div>
												<div class="col-sm-6 col-md-6 col-lg-6">
													<div class="form-group managed_by_spain" <?php if(isset($data['managed_by_id']) && in_array('8',explode(",",$data['managed_by_id']))){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
														<label>Labsuite Entidad Code</label>
														<input type="text" class="form-control" name="labsuite_entidad_code" placeholder="Enater Labsuite Entidad Code" value="<?php echo set_value('labsuite_entidad_code',isset($data['labsuite_entidad_code']) ? $data['labsuite_entidad_code'] : '');?>" <?php echo ($userData['role']==5) ? "readonly" : "" ?>>
														<?php echo form_error('labsuite_entidad_code', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-6 col-md-6 col-lg-6">
													<div class="form-group managed_by_spain" <?php if(isset($data['managed_by_id']) && in_array('8',explode(",",$data['managed_by_id']))){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
														<label>SAGE account</label>
														<input type="text" class="form-control" name="sage_account" placeholder="Enater Spain SAGE account" value="<?php echo set_value('sage_account',isset($data['sage_account']) ? $data['sage_account'] : '');?>" <?php echo ($userData['role']==5) ? "readonly" : "" ?>>
														<?php echo form_error('sage_account', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-6 col-md-6 col-lg-6">
													<div class="form-group managed_by_spain" <?php if(isset($data['managed_by_id']) && in_array('8',explode(",",$data['managed_by_id']))){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
														<label>Intercompany</label>
														<div class="radio">
															<label><input type="radio" name="intercompany" value="Y" <?php if(isset($data['intercompany']) && $data['intercompany'] == 'Y'){ echo 'checked'; } ?>>Yes</label>
															<label><input type="radio" name="intercompany" value="N" <?php if(isset($data['intercompany']) && $data['intercompany'] == 'N'){ echo 'checked'; } ?>><?php echo $this->lang->line('no'); ?></label>
														</div>
														<?php echo form_error('intercompany', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-6 col-md-6 col-lg-6">
													<div class="form-group managed_by_spain" <?php if(isset($data['managed_by_id']) && in_array('8',explode(",",$data['managed_by_id']))){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
														<label>Monthly Invoice</label>
														<div class="radio">
															<label><input type="radio" name="monthly_invoice" value="Y" <?php if(isset($data['monthly_invoice']) && $data['monthly_invoice'] == 'Y'){ echo 'checked'; } ?>>Yes</label>
															<label><input type="radio" name="monthly_invoice" value="N" <?php if(isset($data['monthly_invoice']) && $data['monthly_invoice'] == 'N'){ echo 'checked'; } ?>><?php echo $this->lang->line('no'); ?></label>
														</div>
														<?php echo form_error('monthly_invoice', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group" style="display:none">
													<label><?php echo $this->lang->line("order_can_be_sent");?></label> 
													<div class="radio">
														<label><input type="radio" name="order_can_send_to" id="order_can_send_to1" value="1" <?php if($id=="" || $data['order_can_send_to']==NULL){echo 'checked=""';}elseif(isset($data['order_can_send_to']) && $data['order_can_send_to']=='1'){echo 'checked=""';} ?>><?php echo $this->lang->line("same_address");?> </label>
														<label><input type="radio" name="order_can_send_to" id="order_can_send_to2" value="2" <?php echo (isset($data['order_can_send_to']) && $data['order_can_send_to']=='2') ? 'checked=""' : "" ;?>><?php echo $this->lang->line("other_clinic_address");?></label>
													</div>
													<?php echo form_error('order_can_send_to', '<div class="error">', '</div>'); ?>
												</div>
												<?php if(isset($data['order_can_send_to']) && $data['order_can_send_to']=='2'){$hidden_cls = ""; }else{ $hidden_cls = "hidden"; } ?>
												<div class="form-group other_add_field <?php echo $hidden_cls; ?>">
													<label><?php echo $this->lang->line("delivery_address");?></label>
													<textarea class="form-control" name="odelivery_address" rows="3" placeholder="<?php echo $this->lang->line("enter_delivery_address");?>"><?php echo set_value('odelivery_address',isset($data['odelivery_address']) ? $data['odelivery_address'] : '');?></textarea>
													<?php echo form_error('odelivery_address', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group other_add_field <?php echo $hidden_cls; ?>">
													<label><?php echo $this->lang->line("postal_code");?></label>
													<input type="text" class="form-control" name="opostal_code" placeholder="<?php echo $this->lang->line("enter_postal_code");?>" value="<?php   echo set_value('opostal_code',isset($data['opostal_code']) ? $data['opostal_code'] : '');?>">
													<?php echo form_error('opostal_code', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group other_add_field <?php echo $hidden_cls; ?>">
													<label><?php echo $this->lang->line("city");?></label>
													<input type="text" class="form-control" name="ocity" placeholder="<?php echo $this->lang->line("enter_city");?>" value="<?php echo set_value('ocity',isset($data['ocity']) ? $data['ocity'] : '');?>">
													<?php echo form_error('ocity', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group other_add_field <?php echo $hidden_cls; ?>">
													<label><?php echo $this->lang->line("country");?></label>
													<select class="form-control" name="ocountry" id="ocountry">
														<option value="">--Select--</option>
														<option value="1" <?php if(isset($id) && $id>0 && ($data['ocountry']==1)) echo 'selected="selected"'; ?>><?php echo $this->lang->line("UK");?></option>
														<option value="2" <?php if(isset($id) && $id>0 && ($data['ocountry']==2)) echo 'selected="selected"'; ?>><?php echo $this->lang->line("netherlands");?></option>
													</select>
													<?php echo form_error('ocountry', '<div class="error">', '</div>'); ?>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->
									</div><!-- /.box-body -->
								</div><!-- /.box -->
								<!-- vet/Lab form elements end-->

								<!-- Branch form elements start-->
								<div class="box box-primary" style="display:none">
									<div class="box-header with-border<?php echo $id; ?>"><h3 class="box-title"><?php echo $this->lang->line("branches");?></h3></div><!-- /.box-header -->
									<div class="box-body">
										<div id="duplicate_tm" class="error"></div>
										<input type="hidden" class="form-control deleted_branch_id" name="deleted_branch_id" value="">
										<div class="multi-field-wrapper">
											<div class="multi-fields">
												<?php
												if(!empty($branches)){
													foreach ($branches as $key => $branch) {
														$count = $key+1;
														?>
														<div class="multi-field post">
															<div class="row">
																<div class="col-sm-6 col-md-6 col-lg-6">
																	<input type="hidden" class="form-control branch_id" name="branch_id[]" value="<?php echo set_value('branch_id',isset($branch['id']) ? $branch['id'] : '');?>">
																	<div class="form-group">
																		<label><?php echo $this->lang->line("tm_users");?></label>
																		<?php 
																		$options = array();
																		$options[''] = '-- Select --';
																		if(!empty($tmUsers)){
																			foreach ($tmUsers as $user) {
																				$user_id = $user['id'];
																				$options[$user_id] = $user['name'];
																			}
																		}
																		$attr = 'class="form-control" data-live-search="true"';
																		echo form_dropdown('tm_user_id[]',$options,set_value('tm_user_id',isset($branch['tm_user_id']) ? $branch['tm_user_id'] : ''),$attr); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("Customer_number");?></label>
																		<input type="text" class="form-control" name="customer_number[]" placeholder="<?php echo $this->lang->line("enter_customer_number");?>" value="<?php echo set_value('customer_number',isset($branch['customer_number']) ? $branch['customer_number'] : '');?>">
																		<?php echo form_error('customer_number[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("name");?></label>
																		<input type="text" class="form-control" name="branch_name[]" placeholder="<?php echo $this->lang->line("enter_name");?>" value="<?php echo set_value('branch_name',isset($branch['name']) ? $branch['name'] : '');?>">
																		<?php echo form_error('branch_name[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("Address_1");?></label>
																		<input type="text" class="form-control" name="branch_add[]" placeholder="<?php echo $this->lang->line("enter_Address_1");?>" value="<?php echo set_value('branch_add',isset($branch['address']) ? $branch['address'] : '');?>">
																		<?php echo form_error('branch_add[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("Address_2");?></label>
																		<input type="text" class="form-control" name="branch_add1[]" placeholder="<?php echo $this->lang->line("enter_Address_2");?>" value="<?php echo set_value('branch_add1',isset($branch['address1']) ? $branch['address1'] : '');?>">
																		<?php echo form_error('branch_add1[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("Address_3");?></label>
																		<input type="text" class="form-control" name="branch_add2[]" placeholder="<?php echo $this->lang->line("enter_Address_3");?>" value="<?php echo set_value('branch_add2',isset($branch['address2']) ? $branch['address2'] : '');?>">
																		<?php echo form_error('branch_add2[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("Address_4");?></label>
																		<input type="text" class="form-control" name="branch_add3[]" placeholder="<?php echo $this->lang->line("enter_Address_4");?>" value="<?php echo set_value('branch_add3',isset($branch['address3']) ? $branch['address3'] : '');?>">
																		<?php echo form_error('branch_add3[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("town_city");?></label>
																		<input type="text" class="form-control" name="branch_town_city[]" placeholder="<?php echo $this->lang->line("enter_town_city");?>" value="<?php echo set_value('branch_town_city',isset($branch['town_city']) ? $branch['town_city'] : '');?>">
																		<?php echo form_error('branch_town_city[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("country");?></label>
																		<input type="text" class="form-control" name="branch_county[]" placeholder="<?php echo $this->lang->line("enter_country");?>" value="<?php echo set_value('branch_county',isset($branch['county']) ? $branch['county'] : '');?>">
																		<?php echo form_error('branch_county[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("country");?></label>
																		<?php 
																		$options = array();
																		$options[''] = '-- Select --';
																		if(!empty($countries)){
																			foreach ($countries as $country) {
																				$country_id = $country['id'];
																				$options[$country_id] = $country['name'];
																			}
																		}
																		$attr = 'class="form-control" data-live-search="true"';
																		echo form_dropdown('branch_country[]',$options,set_value('branch_country',isset($branch['country']) ? $branch['country'] : ''),$attr); ?>
																		<?php echo form_error('branch_country[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("postal_code");?></label>
																		<input type="text" class="form-control" name="branch_postcode[]" placeholder="<?php echo $this->lang->line("enter_postal_code");?>" value="<?php echo set_value('branch_postcode',isset($branch['postcode']) ? $branch['postcode'] : '');?>">
																		<?php echo form_error('branch_postcode[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("general_telephone_number");?></label>
																		<input type="text" class="form-control" name="branch_number[]" placeholder="<?php echo $this->lang->line("enter_general_telephone_number");?>" value="<?php echo set_value('branch_number',isset($branch['number']) ? $branch['number'] : '');?>">
																		<?php echo form_error('branch_number[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("email_general_clinic_email");?></label>
																		<input type="text" class="form-control" name="branch_email[]" placeholder="<?php echo $this->lang->line("enter_general_clinic_email");?>" value="<?php echo set_value('branch_email',isset($branch['email']) ? $branch['email'] : '');?>">
																		<?php echo form_error('branch_email[]', '<div class="error">', '</div>'); ?>
																	</div>
																</div><!-- /.col -->
																<div class="col-sm-6 col-md-6 col-lg-6">
																	<div class="form-group">
																		<label><?php echo $this->lang->line("contact_in_accounts");?></label>
																		<input type="text" class="form-control" name="branch_acc_contact[]" placeholder="<?php echo $this->lang->line("enter_contact_in_accounts");?>" value="<?php echo set_value('branch_acc_contact',isset($branch['acc_contact']) ? $branch['acc_contact'] : '');?>">
																		<?php echo form_error('branch_acc_contact[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("accounts_email");?></label>
																		<input type="text" class="form-control" name="branch_acc_email[]" placeholder="<?php echo $this->lang->line("enter_accounts_email");?>" value="<?php echo set_value('branch_acc_email',isset($branch['acc_email']) ? $branch['acc_email'] : '');?>">
																		<?php echo form_error('branch_acc_email[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("accounts_telephone");?></label>
																		<input type="text" class="form-control" name="branch_acc_number[]" placeholder="<?php echo $this->lang->line("accounts_telephone");?>" value="<?php echo set_value('branch_acc_number',isset($branch['acc_number']) ? $branch['acc_number'] : '');?>">
																		<?php echo form_error('branch_acc_number[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<div class="checkbox">
																			<label><input type="checkbox" name="branch_part_of_corpo_<?php echo $count; ?>" value="1" <?php echo ( isset($branch['part_of_corpo']) && $branch['part_of_corpo']) ? 'checked' : ''; ?> class="corpo_cls"><?php echo $this->lang->line("part_of_a_corporate");?></label>
																		</div>
																		<?php echo form_error('branch_part_of_corpo_'.$count, '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("name_of_corporate");?></label>
																		<input type="text" class="form-control" name="branch_corpo_name[]" placeholder="<?php echo $this->lang->line("enter_name_of_corporate");?>" value="<?php echo set_value('branch_corpo_name',isset($branch['corpo_name']) ? $branch['corpo_name'] : '');?>">
																		<?php echo form_error('branch_corpo_name[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<div class="checkbox">
																			<label><input type="checkbox" name="branch_buying_group_<?php echo $count; ?>" value="1" <?php echo (isset($branch['buying_group']) && $branch['buying_group']) ? 'checked' : ''; ?> class="buying_cls"><?php echo $this->lang->line("part_of_a_buying_group");?></label>
																		</div>
																		<?php echo form_error('branch_buying_group_'.$count, '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("name_of_buying_group");?></label>
																		<input type="text" class="form-control" name="branch_group_name[]" placeholder="<?php echo $this->lang->line("name_of_buying_group");?>" value="<?php echo set_value('branch_group_name',isset($branch['group_name']) ? $branch['group_name'] : '');?>">
																		<?php echo form_error('branch_group_name[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<div class="form-group">
																		<label><?php echo $this->lang->line("IVC_clinic_number");?></label>
																		<input type="text" class="form-control" name="ivc_clinic_number[]" placeholder="<?php echo $this->lang->line("name_of_buying_group");?>" value="<?php echo set_value('ivc_clinic_number',isset($branch['ivc_clinic_number']) ? $branch['ivc_clinic_number'] : '');?>">
																		<?php echo form_error('ivc_clinic_number[]', '<div class="error">', '</div>'); ?>
																	</div>
																	<button type="button" class="btn btn-warning remove-field" ><?php echo $this->lang->line("remove");?></button>
																</div><!-- /.col -->
															</div><!-- /.row -->
														</div><!--multi-field post-->
													<?php
													}//foreach
												}else{
												?>
													<div class="multi-field post">
														<div class="row">
															<div class="col-sm-6 col-md-6 col-lg-6">
																<input type="hidden" class="form-control" name="branch_id[]" placeholder="<?php echo $this->lang->line("enter_name");?>" value="<?php echo set_value('branch_id',isset($branch['id']) ? $branch['id'] : '');?>">
																<div class="form-group">
																	<label><?php echo $this->lang->line("tm_users");?></label>
																	<?php 
																	$options = array();
																	$options[''] = '-- Select --';
																	if(!empty($tmUsers)){
																		foreach ($tmUsers as $user) {
																			$user_id = $user['id'];
																			$options[$user_id] = $user['name'];
																		}
																	}
																	$attr = 'class="form-control" data-live-search="true"';
																	echo form_dropdown('tm_user_id[]',$options,set_value('tm_user_id',isset($branch['tm_user_id']) ? $branch['tm_user_id'] : ''),$attr); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("Customer_number");?></label>
																	<input type="text" class="form-control" name="customer_number[]" placeholder="<?php echo $this->lang->line("enter_customer_number");?>" value="<?php echo set_value('customer_number',isset($branch['customer_number']) ? $branch['customer_number'] : '');?>">
																	<?php echo form_error('customer_number[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("name");?></label>
																	<input type="text" class="form-control" name="branch_name[]" placeholder="<?php echo $this->lang->line("enter_name");?>" value="<?php echo set_value('branch_name',isset($branch['name']) ? $branch['name'] : '');?>">
																	<?php echo form_error('branch_name[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("Address_1");?></label>
																	<input type="text" class="form-control" name="branch_add[]" placeholder="<?php echo $this->lang->line("enter_Address_1");?>" value="<?php echo set_value('branch_add',isset($branch['address']) ? $branch['address'] : '');?>">
																	<?php echo form_error('branch_add[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("Address_2");?></label>
																	<input type="text" class="form-control" name="branch_add1[]" placeholder="<?php echo $this->lang->line("enter_Address_2");?>" value="<?php echo set_value('branch_add1',isset($branch['address1']) ? $branch['address1'] : '');?>">
																	<?php echo form_error('branch_add1[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("Address_3");?></label>
																	<input type="text" class="form-control" name="branch_add2[]" placeholder="<?php echo $this->lang->line("enter_Address_3");?>" value="<?php echo set_value('branch_add2',isset($branch['address2']) ? $branch['address2'] : '');?>">
																	<?php echo form_error('branch_add2[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("Address_4");?></label>
																	<input type="text" class="form-control" name="branch_add3[]" placeholder="<?php echo $this->lang->line("enter_Address_4");?>" value="<?php echo set_value('branch_add3',isset($branch['address3']) ? $branch['address3'] : '');?>">
																	<?php echo form_error('branch_add3[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("town_city");?></label>
																	<input type="text" class="form-control" name="branch_town_city[]" placeholder="<?php echo $this->lang->line("enter_town_city");?>" value="<?php echo set_value('branch_town_city',isset($branch['town_city']) ? $branch['town_city'] : '');?>">
																	<?php echo form_error('branch_town_city[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("country");?></label>
																	<input type="text" class="form-control" name="branch_county[]" placeholder="<?php echo $this->lang->line("enter_country");?>" value="<?php echo set_value('branch_county',isset($branch['county']) ? $branch['county'] : '');?>">
																	<?php echo form_error('branch_county[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("country");?></label>
																	<?php 
																	$options = array();
																	$options[''] = '-- Select --';
																	if(!empty($countries)){
																		foreach ($countries as $country) {
																			$country_id = $country['id'];
																			$options[$country_id] = $country['name'];
																		}
																	}
																	$attr = 'class="form-control" data-live-search="true"';
																	echo form_dropdown('branch_country[]',$options,set_value('branch_country',isset($branch['country']) ? $branch['country'] : ''),$attr); ?>
																	<?php echo form_error('branch_country[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("postal_code");?></label>
																	<input type="text" class="form-control" name="branch_postcode[]" placeholder="<?php echo $this->lang->line("enter_postal_code");?>" value="<?php echo set_value('branch_postcode',isset($branch['postcode']) ? $branch['postcode'] : '');?>">
																	<?php echo form_error('branch_postcode[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("general_telephone_number");?></label>
																	<input type="text" class="form-control" name="branch_number[]" placeholder="<?php echo $this->lang->line("enter_general_telephone_number");?>" value="<?php echo set_value('branch_number',isset($branch['number']) ? $branch['number'] : '');?>">
																	<?php echo form_error('branch_number[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("email_general_clinic_email");?></label>
																	<input type="text" class="form-control" name="branch_email[]" placeholder="<?php echo $this->lang->line("enter_general_clinic_email");?>" value="<?php echo set_value('branch_email',isset($branch['email']) ? $branch['email'] : '');?>">
																	<?php echo form_error('branch_email[]', '<div class="error">', '</div>'); ?>
																</div>
															</div><!-- /.col -->
															<div class="col-sm-6 col-md-6 col-lg-6">
																<div class="form-group">
																	<label><?php echo $this->lang->line("contact_in_accounts");?></label>
																	<input type="text" class="form-control" name="branch_acc_contact[]" placeholder="<?php echo $this->lang->line("enter_contact_in_accounts");?>" value="<?php echo set_value('branch_acc_contact',isset($branch['acc_contact']) ? $branch['acc_contact'] : '');?>">
																	<?php echo form_error('branch_acc_contact[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("accounts_email");?></label>
																	<input type="email" class="form-control" name="branch_acc_email[]" placeholder="<?php echo $this->lang->line("enter_accounts_email");?>" value="<?php echo set_value('branch_acc_email',isset($branch['acc_email']) ? $branch['acc_email'] : '');?>">
																	<?php echo form_error('branch_acc_email[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("accounts_telephone");?></label>
																	<input type="text" class="form-control" name="branch_acc_number[]" placeholder="<?php echo $this->lang->line("accounts_telephone");?>" value="<?php echo set_value('branch_acc_number',isset($branch['acc_number']) ? $branch['acc_number'] : '');?>">
																	<?php echo form_error('branch_acc_number[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<div class="checkbox">
																		<label><input type="checkbox" name="branch_part_of_corpo_1" value="1" <?php echo ( isset($branch['part_of_corpo']) && $branch['part_of_corpo']) ? 'checked' : ''; ?> class="corpo_cls"><?php echo $this->lang->line("part_of_a_corporate");?></label>
																	</div>
																	<?php echo form_error('branch_part_of_corpo_1', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("name_of_corporate");?></label>
																	<input type="text" class="form-control" name="branch_corpo_name[]" placeholder="<?php echo $this->lang->line("enter_name_of_corporate");?>" value="<?php echo set_value('branch_corpo_name',isset($branch['corpo_name']) ? $branch['corpo_name'] : '');?>">
																	<?php echo form_error('branch_corpo_name[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<div class="checkbox">
																		<label><input type="checkbox" name="branch_buying_group_1" value="1" <?php echo (isset($branch['buying_group']) && $branch['buying_group']) ? 'checked' : ''; ?> class="buying_cls"><?php echo $this->lang->line("part_of_a_buying_group");?></label>
																	</div>
																	<?php echo form_error('branch_buying_group_1', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("name_of_buying_group");?></label>
																	<input type="text" class="form-control" name="branch_group_name[]" placeholder="<?php echo $this->lang->line("name_of_buying_group");?>" value="<?php echo set_value('branch_group_name',isset($branch['group_name']) ? $branch['group_name'] : '');?>">
																	<?php echo form_error('branch_group_name[]', '<div class="error">', '</div>'); ?>
																</div>
																<div class="form-group">
																	<label><?php echo $this->lang->line("IVC_clinic_number");?></label>
																	<input type="text" class="form-control" name="ivc_clinic_number[]" placeholder="<?php echo $this->lang->line("name_of_buying_group");?>" value="<?php echo set_value('ivc_clinic_number',isset($branch['ivc_clinic_number']) ? $branch['ivc_clinic_number'] : '');?>">
																	<?php echo form_error('ivc_clinic_number[]', '<div class="error">', '</div>'); ?>
																</div>
																<button type="button" class="btn btn-warning remove-field hidden" ><?php echo $this->lang->line("remove");?></button>
															</div><!-- /.col -->
														</div><!-- /.row -->
													</div><!--multi-field post-->
												<?php }//if ?>
											</div><!--multi-fields-->
											<button type="button" class="btn btn-info pull-right add-field"><?php echo $this->lang->line("add_more");?></button>
										</div><!--multi-field-wrapper-->
									</div><!-- /.box-body -->
								</div><!-- /.box -->
								<?php if($userData['role']==1 || $userData['role']==2 || $userData['role']==11){ ?>
								<div class="box-footer">
									<button type="submit" name="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("submit");?></button>
								</div>
								<?php } ?>
								<!-- Branch form elements end-->
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
		<script>
		$(document).ready(function(){
			$('#vluForm').parsley();
			var count = ($('.multi-field').length)+1;
			var branch_ids = [];
			$('.multi-field-wrapper').each(function() {
				var $wrapper = $('.multi-fields', this);
				$(".add-field", $(this)).click(function(e) {
					$('.remove-field').removeClass('hidden');
					$('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input, textarea').val('').focus();
					//reset value of checkbox
					$('.multi-field:last-child', $wrapper).find(':checkbox:checked').prop('checked', false);
					$('.multi-field:last-child', $wrapper).find('.corpo_cls').attr('name', 'branch_part_of_corpo_'+count);
					$('.multi-field:last-child', $wrapper).find('.buying_cls').attr('name', 'branch_buying_group_'+count);
					count++;
				});

				$('.multi-field .remove-field', $wrapper).click(function() {
					branch_ids.push( $(this).parents('.multi-field').find('.branch_id').val() );
					$('input[name="deleted_branch_id"]').val(branch_ids);
					if ($('.multi-field', $wrapper).length > 1){
						$(this).parents('.multi-field').remove();
					}else{
						$('.remove-field').addClass('hidden');
					}
				});
			});

			$('select[name="tm_user_id[]"]').on('change', function() {
				var tm_user_id = $(this).val();
				var practice_id = '<?php echo $id; ?>';
				if(tm_user_id){
					$.ajax({
						url:      "<?php echo base_url('UsersDetails/duplicate_tm'); ?>",
						type:     'POST',
						data:     {'tm_user_id':tm_user_id,'practice_id':practice_id},
						success:  function (data) {
							if(data!=''){
								$('#duplicate_tm').html(data);
								return false;
							}else{
								$('#duplicate_tm').html('');
								return true;
							}
						}
					});
				}
			});//select tm_user_id

			$('input[type=radio][name=order_can_send_to]').change(function() {
				order_can_send_to = this.value;
				console.log(order_can_send_to);
				if(order_can_send_to==2){
					$('.other_add_field').removeClass('hidden');
				}else{
					$('.other_add_field').addClass('hidden');
				}
			});//order_can_send_to

			//allow to select any one
			$(document).on('submit', '#vluForm', function(event) {
				var corporate = $('select[name="corporates[]"]').val();
				var buying_groups = $('select[name="buying_groups[]"]').val();
				if (  corporate!= '' && buying_groups!= '' ) {
					alert('You can select a Corporate OR Buying Group but not both.');
					return false;
				} else {
					return true;
				}
			});

			$('select[name=invoiced_by]').change(function() {
				invoiced_by = this.value;
				if(invoiced_by == 1){
					$('.uk_sageCode').show();
					$('#uk_sage_code').attr("required","required");
				}else{
					$('.uk_sageCode').hide();
					$('#uk_sage_code').val('');
					$('#uk_sage_code').removeAttr("required");
				}
			});

			$('select#managed_by_id').change(function() {
				var managed_by=[];
				$('#managed_by_id :selected').each(function(){
					managed_by.push($(this).val());
				});
				if( $.inArray("8", managed_by) !== -1){
					$('.managed_by_spain').show();
				} else {
					$('.managed_by_spain').hide();
				}
			});
		});
		</script>
	</body>
</html>