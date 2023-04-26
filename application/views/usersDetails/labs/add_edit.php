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
					<?php echo $this->lang->line("Labs");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('Dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line("home");?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("users_management");?></a></li>
						<li class="active"><?php echo $this->lang->line("Labs");?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">
					<!--alert msg-->
					<?php if(!empty($this->session->flashdata('success'))){ ?>
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i><?php echo $this->lang->line("alert");?></h4>
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
						<div class="col-xs-12">
							<?php echo form_open('', array('name'=>'labForm', 'id'=>'labForm')); ?>
								<div class="box box-primary">
									<div class="box-header with-border"><h3 class="box-title"><?php echo $this->lang->line("Labs");?> <?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3></div>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<?php /* <div class="form-group">
													<label>Practices</label>
													<?php
													$options = array();
													$options[''] = "Nothing selected";
													if(!empty($practices)){
														foreach ($practices as $key => $value) {
															$options[$value['id']] = $value['name'];
														}
													}
													$attr = 'class="form-control selectpicker" data-live-search="true"';
													echo form_dropdown('practices[]', $options,  isset($data['practices']) ? json_decode($data['practices']) : '', $attr);
													?>
												</div> */ ?>
												<div class="form-group">
													<div class="checkbox">
														<label>
														<input type="checkbox" name="deliver_to_practice" value="1" <?php echo ( isset($data['deliver_to_practice']) && $data['deliver_to_practice']) ? 'checked' : ''; ?>>
														<?php echo $this->lang->line("deliver_to_practice");?>
														</label>
													</div>
													<?php echo form_error('deliver_to_practice', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<div class="checkbox">
														<label>
														<input type="checkbox" name="results_to_practice" value="1" <?php echo ( isset($data['results_to_practice']) && $data['results_to_practice']) ? 'checked' : ''; ?>>
														<?php echo $this->lang->line("send_results_to_practice");?>
														</label>
													</div>
													<?php echo form_error('results_to_practice', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<div class="checkbox">
														<label>
														<input type="checkbox" name="invoice_to_practice" value="1" <?php echo ( isset($data['invoice_to_practice']) && $data['invoice_to_practice']) ? 'checked' : ''; ?>>
														<?php echo $this->lang->line("invoice_to_practice");?> 
														</label>
													</div>
													<?php echo form_error('invoice_to_practice', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<div class="checkbox">
														<label>
														<input type="checkbox" name="invoice_to_practice_immu" value="1" <?php echo ( isset($data['invoice_to_practice_immu']) && $data['invoice_to_practice_immu']) ? 'checked' : ''; ?>>
														<?php echo $this->lang->line("invoice_to_practice_artuvetrin");?>  
														</label>
													</div>
													<?php echo form_error('invoice_to_practice_immu', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name="vat_applicable" value="1" <?php echo (isset($data['vat_applicable']) && $data['vat_applicable'] == 1) ? 'checked="checked"' : ''; ?>>
															<?php echo $this->lang->line("VAT_applicable");?>  
														</label>
													</div>
													<?php echo form_error('vat_applicable', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("name");?> <span class="required">*</span></label>
													<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line("enter_name");?>" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
													<?php echo form_error('name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("email");?> <span class="required">*</span></label>
													<input type="email" class="form-control" name="email" placeholder="<?php echo $this->lang->line("enter_email");?>" value="<?php echo set_value('email',isset($data['email']) ? $data['email'] : '');?>" required="">
													<?php echo form_error('email', '<div class="error">', '</div>'); ?>
												</div>
												<?php /* <div class="form-group">
													<label>Password</label>
													<input type="password" class="form-control" name="password" placeholder="Enter Password" value="<?php echo set_value('password');?>" <?php echo (isset($id) && $id>0) ? '' : 'required=""' ?>>
													<?php echo form_error('password', '<div class="error">', '</div>'); ?>
												</div> */ ?>
												<div class="form-group">
													<label><?php echo $this->lang->line("phone_number");?> </label>
													<input type="text" class="form-control" name="phone_number" placeholder="<?php echo $this->lang->line("enter_phone_number");?> " value="<?php echo set_value('phone_number',isset($data['phone_number']) ? $data['phone_number'] : '');?>">
													<?php echo form_error('phone_number', '<div class="error">', '</div>'); ?>
												</div>
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
													<label><?php echo $this->lang->line("account_ref");?> <span class="required">*</span></label>
													<input type="text" class="form-control" name="account_ref" placeholder="<?php echo $this->lang->line("enter_account_ref");?>" value="<?php echo set_value('account_ref',isset($data['account_ref']) ? $data['account_ref'] : '');?>" required="">
													<?php echo form_error('address_1', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group uk_sageCode" <?php if((isset($data['invoiced_by']) && $data['invoiced_by'] == 1) || ($userData['role'] == 11 && in_array('1',explode(",",$this->zones)) && count(explode(",",$this->zones)) == 1)){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
													<label>UK Sage 200 Code <span class="required">*</span></label>
													<input type="text" class="form-control" id="uk_sage_code" name="uk_sage_code" placeholder="Enter UK Sage 200 Code" value="<?php echo set_value('uk_sage_code',isset($data['uk_sage_code']) ? $data['uk_sage_code'] : '');?>" <?php echo ($userData['role']==5) ? "readonly" : "" ?> <?php if((isset($data['invoiced_by']) && $data['invoiced_by'] == 1) || ($userData['role'] == 11 && in_array('1',explode(",",$this->zones)) && count(explode(",",$this->zones)) == 1)){ echo 'required="required"'; } ?>>
													<?php echo form_error('uk_sage_code', '<div class="error">', '</div>'); ?>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label><?php echo $this->lang->line("Address_1");?> <span class="required">*</span></label>
													<input type="text" class="form-control" name="address_1" placeholder="<?php echo $this->lang->line("enter_Address_1");?>" value="<?php echo set_value('address_1',isset($data['address_1']) ? $data['address_1'] : '');?>" required="">
													<?php echo form_error('address_1', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("Address_2");?></label>
													<input type="text" class="form-control" name="address_2" placeholder="<?php echo $this->lang->line("enter_Address_2");?>" value="<?php echo set_value('address_2',isset($data['address_2']) ? $data['address_2'] : '');?>">
													<?php echo form_error('address_2', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("Address_3");?></label>
													<input type="text" class="form-control" name="address_3" placeholder="<?php echo $this->lang->line("enter_Address_3");?>" value="<?php echo set_value('address_3',isset($data['address_3']) ? $data['address_3'] : '');?>">
													<?php echo form_error('address_3', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("Address_4");?></label>
													<input type="text" class="form-control" name="address_4" placeholder="Address 4" value="<?php echo set_value('address_4',isset($data['address_4']) ? $data['address_4'] : '');?>">
													<?php echo form_error('address_4', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("town_city");?></label>
													<input type="text" class="form-control" name="town_city" placeholder="<?php echo $this->lang->line("enter_town_city");?>" value="<?php echo set_value('town_city',isset($data['town_city']) ? $data['town_city'] : '');?>">
													<?php echo form_error('town_city', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("post_code");?></label>
													<input type="text" class="form-control" name="post_code" placeholder="<?php echo $this->lang->line("post_code");?>" value="<?php echo set_value('post_code',isset($data['post_code']) ? $data['post_code'] : '');?>">
													<?php echo form_error('post_code', '<div class="error">', '</div>'); ?>
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
												<div class="form-group">
													<label>Comments Only for Nextmune Users</label>
													<textarea class="form-control" name="comment" rows="3" placeholder="<?php echo $this->lang->line("enter_comment");?>"><?php echo set_value('comment',isset($data['comment']) ? $data['comment'] : '');?></textarea>
													<?php echo form_error('comment', '<div class="error">', '</div>'); ?>
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
											</div>
										</div>
									</div>
									<?php if($userData['role'] == 1 || $userData['role'] == 6 || $userData['role'] == 11){ ?>
									<div class="box-footer">
										<button type="submit" name="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("submit");?></button>
									</div>
									<?php } ?>
								</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>  
		</div>
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			$('#labForm').parsley();
			var count = ($('.multi-field').length)+1;
			var branch_ids = [];
			$('.multi-field-wrapper').each(function() {
				var $wrapper = $('.multi-fields', this);
				$(".add-field", $(this)).click(function(e) {
					$('.remove-field').removeClass('hidden');
					$('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input, textarea').val('').focus();
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