<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
			<style>
			.foo{color:#797676;text-size:smaller}
			.select2-container .select2-selection--single{height:35px}
			.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove:hover{color:#fff}
			.d-none{display:none}
			.select-width{width: 100%;}
			</style>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						<?php echo $this->lang->line("Order_Details");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("Orders_Management");?></a></li>
						<li class="active"><?php echo $this->lang->line("Orders");?></li>
					</ol>
				</section>

				<section class="content">
					<?php $this->load->view("orders/breadcrumbs"); ?>
					<?php if (!empty($this->session->flashdata('success'))) { ?>
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i> <?php echo $this->lang->line("alert");?></h4>
							<?php echo $this->session->flashdata('success'); ?>
						</div>
					<?php } ?>
					<?php if (!empty($this->session->flashdata('error'))) { ?>
						<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-warning"></i> <?php echo $this->lang->line("alert");?></h4>
							<?php echo $this->session->flashdata('error'); ?>
						</div>
					<?php } ?>

					<div class="row">
						<div class="col-xs-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line("back");?></a>
								</div>

								<?php echo form_open_multipart('', array('name' => 'orderForm', 'id' => 'orderForm')); ?>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<!--labs details-->
												<?php if ($userData['role'] == 1 || $userData['role'] == 5 || $userData['role'] == 6 || $userData['role'] == 7 || $userData['role'] == 11) { ?>
													<?php if ($this->session->userdata('plc_selection') == '2' || (isset($data['lab_id']) && $data['lab_id'] > 0)) { ?>
														<?php if(count($labs) == 1){ ?>
															<div class="form-group">
																<label><?php echo $this->lang->line("lab");?> <span class="required">*</span></label>
																<input type="text" class="form-control" value="<?=$labs[0]['name'];?>" readonly="readonly">
																<input type="hidden" class="lab_id" name="lab_id" value="<?=$labs[0]['id'];?>">
															</div>
														<?php }else{ ?>
															<div class="form-group">
																<label><?php echo $this->lang->line("lab");?> <span class="required">*</span></label>
																<?php
																if ($userData['role'] == '6' && $id == "") {
																	$data['lab_id'] = $userData['user_id'];
																}
																$lab_options = array();
																$lab_options[''] = '-- Select --';
																if (!empty($labs)) {
																	foreach ($labs as $lab) {
																		$user_id = $lab['id'];
																		$lab_options[$user_id] = $lab['name'];
																	}
																}
																$attr = 'class="form-control lab_id" data-live-search="true"';
																echo form_dropdown('lab_id', $lab_options, set_value('lab_id', isset($data['lab_id']) ? $data['lab_id'] : ''), $attr); ?>
																<?php echo form_error('lab_id', '<div class="error">', '</div>'); ?>
															</div>
														<?php } 
													}
												}
												?>

												<!--practice details-->
												<?php if ($this->session->userdata('plc_selection') == '1' || $this->session->userdata('plc_selection') == '2') { ?>
													<?php if ($userData['role'] == 2 || $userData['role'] == 5) { ?>
														<?php if(count($vatLabUsers) == 1){ ?>
															<div class="form-group">
																<label><?php echo $this->lang->line("practice_name");?></label>
																<select name="vet_user_id" class="form-control vet_user_id">
																	<option value="<?=$vatLabUsers[0]['id'];?>" selected="selected"><?=$vatLabUsers[0]['name'].$vatLabUsers[0]['postcode'].$vatLabUsers[0]['account_ref'];?></option>
																</select>
															</div>
														<?php }else{ ?>
															<div class="form-group">
																<label><?php echo $this->lang->line("practice_name");?></label>
																<?php
																$options = array(); $rdonly = 0;
																$options[''] = '-- Select --';
																if (!empty($vatLabUsers)) {
																	foreach ($vatLabUsers as $user) {
																		if($userData['user_id'] == $user['id']){
																			$rdonly = 1;
																		}
																		$user_id = $user['id'];
																		$post_code = ($user['postcode']) ? ' - ' . $user['postcode'] : '';
																		$account_ref = ($user['account_ref']) ? ' - ' . $user['account_ref'] : '';
																		$options[$user_id] = $user['name'] . $post_code . $account_ref;
																	}
																}
																if($rdonly == 1){
																	$attr = 'class="form-control vet_user_id" readonly="readonly"';
																}else{
																	$attr = 'class="form-control vet_user_id"';
																}
																if (isset($id) && $id > 0) {
																	$selectedId = isset($data['vet_user_id']) ? $data['vet_user_id'] : '';
																}else{
																	$selectedId = isset($data['vet_user_id']) ? $data['vet_user_id'] : $userData['user_id'];
																}
																echo form_dropdown('vet_user_id', $options, set_value('vet_user_id', $selectedId), $attr); ?>
																<?php echo form_error('vet_user_id', '<div class="error">', '</div>'); ?>
															</div>
														<?php } ?>
													<?php }else{ ?>
														<?php if ($userData['role'] == 1 || $userData['role'] == 11 || $userData['role'] == 5) { ?>
															<div class="form-group">
																<label><?php echo $this->lang->line("practice_name");?> <span class="required">*</span></label>
																<?php
																$options = array();
																$options[''] = '-- Select --';
																if (!empty($vatLabUsers)) {
																	foreach ($vatLabUsers as $user) {
																		$user_id = $user['id'];
																		$post_code = ($user['postcode']) ? ' - ' . $user['postcode'] : '';
																		$account_ref = ($user['account_ref']) ? ' - ' . $user['account_ref'] : '';
																		$options[$user_id] = $user['name'] . $post_code . $account_ref;
																	}
																}
																if($userData['role'] == 2 || $userData['role'] == 5 || $userData['role'] == 6 || $userData['role'] == 7) {
																	$attr = 'class="form-control vet_user_id select2" data-live-search="true" required="required"';
																}else{
																	$attr = 'class="form-control vet_user_id select2" data-live-search="true"';
																}
																echo form_dropdown('vet_user_id', $options, set_value('vet_user_id', isset($data['vet_user_id']) ? $data['vet_user_id'] : ''), $attr); ?>
																<?php echo form_error('vet_user_id', '<div class="error">', '</div>'); ?>
															</div>
														<?php } ?>
													<?php } ?>
													<?php if($userData['role'] == 5 && $this->session->userdata('user_type') == '2' && $labs[0]['id'] == '13786'){ ?>
														<div class="form-group">
															<button type="button" class="btn btn-primary addPracticeModal" data-toggle="modal" data-target="#addPracticeModal" style="width:27%;"><?php echo $this->lang->line("add_new_practice");?></button>
														</div>
													<?php } ?>

													<?php if ($userData['role'] != '2' && $userData['role'] != '5'){ ?>
														<?php if ($controller == 'orders' || ($controller == 'repeatOrder' && isset($id) && $id > 0 && isset($data['branch_id']) && $data['branch_id'] > 0) ) {  ?>
															<div class="form-group" style="display:none;">
																<label><?php echo $this->lang->line("branch_practice_postcode");?></label>
																<select name="branch_id" class="form-control order_branch_id" data-live-search="true">
																	<option value="" selected="selected">-- Select --</option>
																</select>
															</div>
														<?php } ?>
													<?php } ?>
													<div class="form-group">   
														<label><?php echo $this->lang->line("veterinary_surgeon_name_ordered_by");?><span class="required">*</span></label>
														<?php if ($userData['role'] == 1 || $userData['role'] == 11){ ?>
														<select id="customer_id" class="form-control">
															<option value="">-- Select --</option>
														</select>
														<?php } ?>
														<input type="text" class="form-control surgeon_name" name="name" placeholder="<?php echo $this->lang->line("enter_veterinary_surgeon_name");?>" value="<?php if ($userData['role'] != 1 && $userData['role'] != 11){ echo set_value('name', isset($data['name']) ? $data['name'] : $userData['name']); }else{ echo set_value('name', isset($data['name']) ? $data['name'] : ''); } ?>" required="">
														<span class="special_note"><?php echo $this->lang->line("note_order_authorised_by");?></span>
														<?php echo form_error('name', '<div class="error">', '</div>'); ?>
													</div>
													<?php 
													if($userData['role'] == 5 && $labs[0]['id'] == '13786'){
														$emailadr = isset($data['email'])?$data['email']:'immunotherapy@axiomvetlab.co.uk';
													}elseif($userData['role'] != 1 && $userData['role'] != 11){
														$emailadr = isset($data['email']) ? $data['email'] : $userData['email'];
													}else{
														$emailadr = isset($data['email']) ? $data['email'] : '';
													}

													if($order_type == '2'){ 
														$emailabel = 'Email Address for Results';
													}else{
														$emailabel = 'Email Address';
													}
													?>
													<div class="form-group">   
														<label><?=$emailabel?> <span class="required">*</span></label>
														<select id="customer_emails" class="form-control">
															<option value="">-- Select --</option>
														</select>
														<input type="text" class="form-control" name="email" id="order_email" placeholder="Enter <?=$emailabel?>" value="<?php echo set_value('email', $emailadr); ?>" <?php echo ($controller != 'repeatOrder') ? 'required=""' : ''; ?>>
														<span class="special_note"><?php echo $this->lang->line("note_1");?></span>
														<?php echo form_error('email', '<div class="error">', '</div>'); ?>
													</div>
													<?php
													if($order_type == '1' || $order_type == '2') {
													?>
													<div class="form-group">
														<label><?php echo $this->lang->line("email_two");?></label>
														<input type="text" class="form-control" name="email_two" placeholder="Enter <?php echo $this->lang->line("email_two");?>" value="<?php echo set_value('email_two', isset($data['email_two']) ? $data['email_two'] : ''); ?>">
														<?php echo form_error('email_two', '<div class="error">', '</div>'); ?>
													</div>
													<?php
													}
													?>
													<div class="form-group">
														<label><?php echo $this->lang->line("phone_number");?></label>
														<input type="text" class="form-control" name="phone_number" placeholder="<?php echo $this->lang->line("enter_phone_number");?>" value="<?php echo set_value('phone_number', isset($data['phone_number']) ? $data['phone_number'] : ''); ?>">
														<?php echo form_error('phone_number', '<div class="error">', '</div>'); ?>
													</div>
													<?php if ($order_type != '3'){ ?>
														<div class="form-group">
															<label><?php echo $this->lang->line("pet_owner");?> <span class="required">*</span></label>
															<select name="pet_owner_id" class="form-control select2" data-live-search="true" onChange="getPets();" <?php if($order_type == '1' || $order_type == '2' || $this->session->userdata('order_type') == '1' || $this->session->userdata('order_type') == '2'){ echo 'required="required"'; } ?>>
																<option value="" data-append="">--Select--</option>
																<?php
																if (!empty($pet_owners) && (isset($id) && $id > 0)) {
																	foreach ($pet_owners as $po_user) { ?>
																		<option value="<?php echo $po_user['id']; ?>" <?php if (isset($id) && $id > 0 && ($data['pet_owner_id'] == $po_user['id'])) echo 'selected="selected"'; ?> data-append=""><?php echo $po_user['name'] . " " . $po_user['last_name']; ?></option>
																<?php }
																}
																?>
															</select>
															<?php echo form_error('pet_owner_id', '<div class="error">', '</div>'); ?>
														</div>
														<div class="form-group">
															<button type="button" class="btn btn-primary petOwnerModal" data-toggle="modal" data-petowner_btn="Add" data-target="#petOwnerModal" style="width:27%;">
															<?php echo $this->lang->line("add_new_pet_owner");?>
															</button>
															<?php if(isset($id) && $id > 0){ $styls = ''; }else{ $styls = 'display:none;'; } ?>
															<button type="button" id="petownerEdit" class="btn btn-primary petOwnerModal" data-toggle="modal" data-petowner_btn="Edit" data-target="#petOwnerModal" data-petowner_id="<?php echo $data['pet_owner_id']; ?>" style="width:23%;<?=$styls?>"><?php echo $this->lang->line("edit_pet_owner");?></button>
														</div>
													<?php } ?>

													<?php if ($userData['role'] == 1 || $userData['role'] == 5 || $userData['role'] == 11 || ($userData['role'] == 2 && $order_type != 3)) { ?>
														<?php if ($order_type != 3) { ?>
															<div class="form-group">
																<label><?php echo $this->lang->line("pet");?> <span class="required">*</span></label>
																<select name="pet_id" class="form-control select2" data-live-search="true"  <?php if($order_type == '1' || $order_type == '2' || $this->session->userdata('order_type') == '1' || $this->session->userdata('order_type') == '2'){ echo 'required="required"'; } ?>>
																	<option value="" data-append="">--Select--</option>
																	<?php
																	$additional_details = "";
																	if ($controller == 'repeatOrder') {
																		$additional_details = ', Batch Number: ' . $data["batch_number"];
																	}
																	if (!empty($pets)) {
																		foreach ($pets as $p_user) { ?>
																			<option value="<?php echo $p_user['id']; ?>" <?php if (isset($id) && $id > 0 && ($data['pet_id'] == $p_user['id'])) echo 'selected="selected"'; ?> data-append="<?php echo "Species: " . $p_user['species_name'] . $additional_details;  ?>"><?php echo $p_user['name']; ?></option>
																	<?php }
																	}
																	?>
																</select>
																<?php if ($controller == 'repeatOrder') { ?>
																	<span class="special_note"><?php echo $this->lang->line("note_2");?></span>
																<?php } ?>
																<?php echo form_error('pet_id', '<div class="error">', '</div>'); ?>
															</div>
															<div class="form-group">
																<button type="button" class="btn btn-primary petModal" data-toggle="modal" data-target="#petModal" data-pet_btn="Add" style="width:23%;"><?php echo $this->lang->line("Add_New_Pet");?></button>
																<?php if(isset($id) && $id > 0){ $styls = ''; }else{ $styls = 'display:none;'; } ?>
																<button type="button" id="petEdit" class="btn btn-primary petModal" data-toggle="modal" data-target="#petModal"  data-pet_btn="Edit" data-pet_id="<?php echo $data['pet_id']; ?>" style="width:23%;<?=$styls?>"><?php echo $this->lang->line("edit_Pet");?></button>
															</div>
														<?php  } ?>
													<?php } ?>
													<?php if ($this->session->userdata('plc_selection') == '3') { ?>
														<div class="form-group">
															<label><?php echo $this->lang->line("Corporates");?></label>
															<?php
															if ($userData['role'] == '7' && $id == "") {
																$data['corporate_id'] = $userData['user_id'];
															}
															$corporate_options = array();
															$corporate_options[''] = '-- Select --';
															if (!empty($corporates)) {
																foreach ($corporates as $corporate) {
																	$user_id = $corporate['id'];
																	$corporate_options[$user_id] = $corporate['name'];
																}
															}
															$attr = 'class="form-control" data-live-search="true"';
															echo form_dropdown('corporate_id', $corporate_options, set_value('corporate_id', isset($data['corporate_id']) ? $data['corporate_id'] : ''), $attr); ?>
															<?php echo form_error('corporate_id', '<div class="error">', '</div>'); ?>
														</div>
													<?php } ?>
												<?php } ?>
												<!--practice details-->

												<!--active in uk-->
												<?php if ($sub_order_type == '2'){ ?>
													<div class="form-group">
														<label><?php echo $this->lang->line("are_you_active_in_the_UK");?></label>
														<div class="radio">
															<label><input type="radio" name="active_in_uk" id="active_in_uk1" value="1" <?php if ($id == "") { echo 'checked=""'; } elseif (isset($data['active_in_uk']) && $data['active_in_uk'] == '1') { echo 'checked=""'; } ?>><?php echo $this->lang->line("Yes_and_I_have_a_SIC_with_Vetruus_as_the_importer");?></label><br>
															<label><input type="radio" name="active_in_uk" id="active_in_uk2" value="2" <?php echo (isset($data['active_in_uk']) && $data['active_in_uk'] == '2') ? 'checked=""' : ""; ?>><?php echo $this->lang->line("Yes_but_I_do_not_have_a_SIC_with_Vetruus_as_the_importer");?></label><br>
															<label><input type="radio" name="active_in_uk" id="active_in_uk3" value="3" <?php echo (isset($data['active_in_uk']) && $data['active_in_uk'] == '3') ? 'checked=""' : ""; ?>><?php echo $this->lang->line("no");?></label>
														</div>
														<?php echo form_error('active_in_uk', '<div class="error">', '</div>'); ?>
													</div>
												<?php } ?>
												<!--active in uk-->
											</div>

											<div class="col-sm-6 col-md-6 col-lg-6">
												<?php if ($order_type == '2'){ ?>
													<div class="form-group">   
														<label><?php echo $this->lang->line("unique_ID_for_order");?></label>
														<input type="text" class="form-control" name="unique_ID_for_order" placeholder="<?php echo $this->lang->line("enter_unique_ID_for_order");?>" value="<?php echo set_value('unique_ID_for_order', isset($data['unique_ID_for_order']) ? $data['unique_ID_for_order'] : ''); ?>">
														<span class="special_note"><?php echo $this->lang->line("note_3");?>.</span>
														<?php echo form_error('unique_ID_for_order', '<div class="error">', '</div>'); ?>
													</div>
												<?php } ?>

												<div class="form-group" <?php if((isset($data['plc_selection']) && $data['plc_selection'] == '2')|| ($this->session->userdata('plc_selection') == '2')){ echo 'style="display:block"'; }else{ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("your_order_reference_number");?></label>
													<input type="text" class="form-control" name="reference_number" placeholder="<?php echo $this->lang->line("enter_your_order_reference_number");?>" value="<?php echo set_value('reference_number', isset($data['reference_number']) ? $data['reference_number'] : ''); ?>">
													<?php echo form_error('reference_number', '<div class="error">', '</div>'); ?>
												</div>
												<?php if ($order_type == '2' && ($userData['role'] == 1 || $userData['role'] == 11)){ ?>
													<div class="form-group">
														<label><?php echo $this->lang->line("nextmune_Lab_number");?></label>
														<input type="text" class="form-control" name="lab_order_number" placeholder="<?php echo $this->lang->line("add_Lab_number");?>" value="<?php echo set_value('lab_order_number', (isset($data['lab_order_number']) && $controller == 'orders') ? $data['lab_order_number'] : $this->session->userdata('lab_order_number')); ?>">
														<?php echo form_error('lab_order_number', '<div class="error">', '</div>'); ?>
													</div>
													<?php if((isset($data['serum_type']) && $data['serum_type'] == '2') || ($this->session->userdata('serum_type') == '2')){ ?>
														<div class="form-group">
															<label>Sample Volume <span class="required">*</span></label>
															<input type="text" onkeyup="sampleVolumeValue()" class="form-control" name="sample_volume" id="sample_volume" placeholder="Add Sample Volume" value="<?php echo set_value('sample_volume', (isset($data['sample_volume'])) ? $data['sample_volume'] : ''); ?>" required="required"/>
															<?php echo form_error('sample_volume', '<div class="error">', '</div>'); ?>
															<span class="sampleVolume error"></span>
														</div>
														<script>
														function sampleVolumeValue(){
															if($("#sample_volume").val() < '2000'){
																$(".sampleVolume").text("Added Sample Volume is not enough for the selected test!");
																$('.next_btn').attr('disabled', 'disabled');
															}else{
																$(".sampleVolume").text("");
																$('.next_btn').prop("disabled", false);
															}
														}
														</script>
													<?php } ?>
													<div class="form-group">
														<label for="requisition_form"><?php echo $this->lang->line("requisition_form");?></label>
														<input type="file" id="requisition_form" name="requisition_form" data-parsley-file_extension='pdf'>
														<?php if (isset($data['requisition_form']) && $data['requisition_form'] != '') { ?>
															<label for="requisition_form"><?php echo $this->lang->line("view_requisition_form");?></label>
															<a class="btn btn-sm btn-outline-light" href="<?php echo base_url() . REQUISITION_FORM_PATH; ?>/<?php echo $data['requisition_form']; ?>" download title="Download"><?php echo $this->lang->line("download");?></a>&nbsp;&nbsp;
															<a class="btn btn-sm btn-outline-light removeRequisition" href="javascript:void(0);" data-href="<?php echo base_url('orders/removeRequisition'); ?>" data-order_id="<?php echo $id; ?>" data-doc_name="<?php echo $data['requisition_form']; ?>" title="Remove"><?php echo $this->lang->line("remove");?></a>
														<?php } ?>
													</div>
												<?php } ?>

												<div class="form-group">
													<label><?php echo $this->lang->line("practice_purchase_order_number");?></label>
													<input type="text" class="form-control" name="purchase_order_number" placeholder="<?php echo $this->lang->line("enter_practice_purchase_order_number");?>" value="<?php echo set_value('purchase_order_number', isset($data['purchase_order_number']) ? $data['purchase_order_number'] : ''); ?>">
													<span class="special_note"><?php echo $this->lang->line("note_4");?></span>
													<?php echo form_error('purchase_order_number', '<div class="error">', '</div>'); ?>
												</div>

												<?php
												$order_date = isset($data['order_date']) ? date("d/m/Y", strtotime($data['order_date'])) : '';
												if ($controller == 'repeatOrder') {
													$order_date = date("d/m/Y");
												}
												?>
												<div class="form-group">
													<label><?php echo $this->lang->line("order_date");?></label>
													<div class="input-group date">
														<div class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</div>
														<input type="text" class="form-control pull-right" name="order_date" placeholder="<?php echo $this->lang->line("enter_order_date");?>" value="<?php echo set_value('order_date', $order_date); ?>" autocomplete="off" <?php if ($userData['role'] != 1 && $userData['role'] != 11) { echo 'disabled'; } ?>>
													</div>
													<?php echo form_error('order_date', '<div class="error">', '</div>'); ?>
												</div>

												<?php if (isset($id) && $id > 0 && $order_type != '2') { ?>
													<div class="form-group">
														<label><?php echo $this->lang->line("batch_number");?></label>
														<input type="text" class="form-control" name="batch_number" placeholder="<?php echo $this->lang->line("enter_batch_number");?>" value="<?php echo set_value('batch_number', (isset($data['batch_number']) && $controller == 'orders') ? $data['batch_number'] : ''); ?>">
														<?php echo form_error('batch_number', '<div class="error">', '</div>'); ?>
													</div>
												<?php } ?>

												<?php 
												if (isset($id) && $id > 0){
													if($data['lab_id'] > 0){
														$practiceLab = $this->UsersModel->practiceLabCountry($data['lab_id']);
													}else{
														$practiceLab = $this->UsersModel->practiceLabCountry($data['vet_user_id']);
													}
													if($practiceLab['name']=='UK'){
														echo '<div class="form-group sicDocument">';
													}else{
														echo '<div class="form-group sicDocument" style="display: none;">';
													}
												}else{
													echo '<div class="form-group sicDocument">';
												}
												?>
													<?php if ($order_type != '2') {  ?>
														<?php if ($controller == 'repeatOrder') { ?>
															<?php
															if ($this->session->userdata('plc_selection') == '2' || isset($data) && $data['plc_selection'] == '2') {
																$sic_label = "Order Form";
															} else {
																$sic_label = "SIC Document <span class='required'>*</span> <small>(File name should not include a . or symbols)</small>";
															}
															?>
															<label for="sic_document"><?php echo $sic_label; ?></label>
															<?php if($practiceLab['name']=='UK'){ ?>
															<input type="file" id="sic_document" name="sic_document" required="" data-parsley-file_extension='pdf'>
															<?php } ?>
														<?php } else { ?>
															<?php
															if ($this->session->userdata('plc_selection') == '2' || isset($data) && $data['plc_selection'] == '2') {
																$sic_label = "Order Form";
															} else {
																$sic_label = "SIC Document <small>(File name should not include a . or symbols)</small>";
															}
															?>
															<label for="sic_document"><?php echo $sic_label; ?></label>
															<input type="file" id="sic_document" name="sic_document" <?php echo ((empty($data)) || (isset($data) && isset($data['sic_document']) == "")) ? 'required=""' : ''; ?> data-parsley-file_extension='pdf' onchange="return fileValidation()">
															<?php if (isset($data['sic_document']) && $data['sic_document'] != '') { ?>
																<label for="sic_document"><?php echo $this->lang->line("view");?> <?php echo $sic_label; ?></label>
																<a class="btn btn-sm btn-outline-light" href="<?php echo base_url() . SIC_DOC_PATH; ?>/<?php echo $data['sic_document']; ?>" download title="Download"><?php echo $this->lang->line("download");?></a>&nbsp;&nbsp;
																<a class="btn btn-sm btn-outline-light removeSIC" href="javascript:void(0);" data-href="<?php echo base_url('orders/removeDoc'); ?>" data-order_id="<?php echo $id; ?>" data-doc_name="<?php echo $data['sic_document']; ?>" title="Remove"><?php echo $this->lang->line("remove");?></a>
															<?php } ?>
														<?php } ?>
													<?php } ?>
													<span class="special_note error_sic d-none"><?php echo $this->lang->line("file_name_is_invalid");?>!.</span>
												</div>
												<?php if (isset($data['email_upload']) && $data['email_upload'] != '') { ?>
													<div class="form-group">
														<label for="email_upload"><?php echo $this->lang->line("view_email_upload");?></label>
														<a class="btn btn-sm btn-outline-light" href="<?php echo base_url() . EMAIL_UPLOAD_PATH; ?>/<?php echo $data['email_upload']; ?>" download title="Download"><?php echo $this->lang->line("download");?></a>&nbsp;&nbsp;
													</div>
												<?php } ?>

												<!-- Delivery Address-->
												<div class="form-group" <?php if($order_type == '2'){ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("order_delivery_address");?></label>
													<div class="radio">
														<label><input type="radio" name="order_can_send_to" id="order_can_send_to1" value="0" <?php if ($id == "" || $data['order_can_send_to'] == NULL) { echo 'checked=""'; } elseif (isset($data['order_can_send_to']) && $data['order_can_send_to'] == '0') { echo 'checked=""'; } ?>><?php if ($this->session->userdata('plc_selection') == '2') { ?><?php echo $this->lang->line("delivery_to_Lab");?> <?php } else { ?><?php echo $this->lang->line("address_below");?> <?php } ?></label>
														<label><input type="radio" onchange="addrs()" name="order_can_send_to" id="order_can_send_to2" value="1" <?php echo (isset($data['order_can_send_to']) && $data['order_can_send_to'] == '1') ? 'checked=""' : ""; ?>><?php if ($this->session->userdata('plc_selection') == '2') { ?><?php echo $this->lang->line("delivery_to_practice");?> <?php } else { ?><?php echo $this->lang->line("deliver_to_different_practice_address");?> <?php } ?></label>
													</div>
													<?php echo form_error('order_can_send_to', '<div class="error">', '</div>'); ?>
												</div>
												<?php 
												if(isset($data['order_can_send_to']) && $data['order_can_send_to'] == '1'){
													$other_practices = "";
												}else{
													$other_practices = "hidden";
												}
												if (isset($data['order_can_send_to']) && $data['order_can_send_to'] == '0') {
													$hidden_cls = "";
													$readonly_cls = 'readonly="readonly"';
												} else {
													$hidden_cls = "";
													$readonly_cls = '';
												} ?>
												<!--practice and branches-->
												<div class="form-group other_add_field other_practices <?php echo $hidden_cls; ?> <?php echo $other_practices; ?>" <?php if($order_type == '2' || $this->session->userdata('order_type') == '2'){ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("other_practices");?></label><br>
													<?php
													$deli_options = array();
													$deli_options[''] = '-- Select --';
													if (!empty($deliveryPractices)) {
														foreach ($deliveryPractices as $deliveryPractice) {
															$deliveryPracticeID = $deliveryPractice['id'];
															$post_code = ($deliveryPractice['postcode']) ? ' - '.$deliveryPractice['postcode']:'';
															$account_ref = ($deliveryPractice['account_ref']) ? ' - '.$deliveryPractice['account_ref']:'';
															$deli_options[$deliveryPracticeID] = $deliveryPractice['name'] . $post_code. $account_ref;
														}
													}
													$attr = 'class="form-control delivery_practice_id" data-live-search="true"';
													echo form_dropdown('delivery_practice_id', $deli_options, set_value('delivery_practice_id', isset($data['delivery_practice_id']) ? $data['delivery_practice_id'] : ''), $attr); ?>
													<?php echo form_error('delivery_practice_id', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group other_add_field other_branches <?php echo $hidden_cls; ?>" style="display:none">
													<label><?php echo $this->lang->line("branches");?></label><br>
													<select name="delivery_practice_branch_id" class="form-control delivery_practice_branch_id">
														<option value="" selected="selected">-- Select --</option>
													</select>
													<?php echo form_error('delivery_practice_branch_id', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group other_add_field" <?php if($order_type == '2' || $this->session->userdata('order_type') == '2'){ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("Address_1");?></label>
													<input type="text" class="form-control"  id="address1" name="address1" placeholder="<?php echo $this->lang->line("enter_Address_1");?>" value="<?php echo set_value('address1',isset($data['address1']) ? $data['address1'] : '');?>" <?php echo $readonly_cls; ?>>
													<?php
													echo form_error('address1', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group other_add_field" <?php if($order_type == '2' || $this->session->userdata('order_type') == '2'){ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("Address_2");?></label>
													<input type="text" class="form-control" id="address2" name="address2" placeholder="<?php echo $this->lang->line("enter_Address_2");?>" value="<?php echo set_value('address2',isset($data['address2']) ? $data['address2'] : '');?>" <?php echo $readonly_cls; ?>>
													<?php
													echo form_error('address2', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group other_add_field" <?php if($order_type == '2' || $this->session->userdata('order_type') == '2'){ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("Address_3");?></label>
													<input type="text" class="form-control" id="address3" name="address3" placeholder="<?php echo $this->lang->line("enter_Address_3");?>" value="<?php echo set_value('address3',isset($data['address3']) ? $data['address3'] : '');?>" <?php echo $readonly_cls; ?>>
													<?php
													echo form_error('address3', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group other_add_field" <?php if($order_type == '2' || $this->session->userdata('order_type') == '2'){ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("town_city");?></label>
													<input type="text" class="form-control" id="town_city" name="town_city" placeholder="<?php echo $this->lang->line("enter_town_city");?>" value="<?php echo set_value('town_city',isset($data['town_city']) ? $data['town_city'] : '');?>" <?php echo $readonly_cls; ?>>
													<?php
													 echo form_error('town_city', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group other_add_field" <?php if($order_type == '2' || $this->session->userdata('order_type') == '2'){ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("county");?></label>
													<input type="text" class="form-control" id="county" name="county" placeholder="<?php echo $this->lang->line("enter_county");?>" value="<?php echo set_value('county',isset($data['county']) ? $data['county'] : '');?>" <?php echo $readonly_cls; ?>>
													<?php 
													echo form_error('county', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group other_add_field" <?php if($order_type == '2' || $this->session->userdata('order_type') == '2'){ echo 'style="display:none"'; } ?>>
													<label><?php echo $this->lang->line("postcode");?></label>
													<input type="text" id="postcode" class="form-control" name="postcode" placeholder="<?php echo $this->lang->line("enter_postcode");?>" value="<?php echo set_value('postcode',isset($data['postcode']) ? $data['postcode'] : '');?>" <?php echo $readonly_cls; ?>>
													<?php
													 echo form_error('postcode', '<div class="error">', '</div>'); ?>
												</div> 

												<div class="form-group other_add_field" <?php if($order_type == '2' || $this->session->userdata('order_type') == '2'){ echo 'style="display:none"'; } ?>>
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
													if (isset($data['order_can_send_to']) && $data['order_can_send_to'] == '1') {
														$attr = 'class="form-control country" data-live-search="true"';
													} else {
														$attr = 'class="form-control country" data-live-search="true" readonly="readonly"';
													}
													echo form_dropdown('country',$options,set_value('country',isset($data['country']) ? $data['country'] : ''),$attr); ?>
													<?php 
													echo form_error('country', '<div class="error">', '</div>'); ?>
												</div>
												<!-- Delivery Address-->
											</div>
										</div>
									</div>
								</div>
								<!--Order List-->

								<!-- order form elements -->
								<!-- order material elements -->
								<?php if ($sub_order_type == '2' || $this->session->userdata('order_type') == '2') { ?>
									<div class="box box-primary" style="display:none">
										<div class="box-header with-border">
											<h3 class="box-title"><?php echo $this->lang->line("order_materials");?></h3>
										</div><!-- /.box-header -->
										<div class="box-body">
											<div class="row">
												<div class="col-sm-6 col-md-6 col-lg-6">
													<dl class="dl-horizontal order_material">
														<dt><strong><?php echo $this->lang->line("for_veterinarians");?></strong></dt><br>
														<dd>&nbsp;</dd>
														<dt><?php echo $this->lang->line("next__serum_test_envelope");?></dt>
														<dd><input type="text" name="next_serum_test_envelope" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('next_serum_test_envelope', isset($data['next_serum_test_envelope']) ? $data['next_serum_test_envelope'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("Flow_Chart_Systematic_Approach_to_Allergy");?></dt>
														<dd><input type="text" name="flow_chart" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('flow_chart', isset($data['flow_chart']) ? $data['flow_chart'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("product_range");?></dt>
														<dd><input type="text" name="prod_range" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('prod_range', isset($data['prod_range']) ? $data['prod_range'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("equine_allergies");?></dt>
														<dd><input type="text" name="equine_allergies" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('equine_allergies', isset($data['equine_allergies']) ? $data['equine_allergies'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("atopic_dermatitis_brochure");?></dt>
														<dd><input type="text" name="atopic" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('atopic', isset($data['atopic']) ? $data['atopic'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("food_allergies");?></dt>
														<dd><input type="text" name="food_allergies" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('food_allergies', isset($data['food_allergies']) ? $data['food_allergies'] : ''); ?>"></dd>
													</dl>
												</div><!-- /.col -->

												<div class="col-sm-6 col-md-6 col-lg-6">
													<dl class="dl-horizontal order_material">
														<dt><strong><?php echo $this->lang->line("for_pet_owners");?></strong></dt><br>
														<dd>&nbsp;</dd>
														<dt><?php echo $this->lang->line("pet_allergies");?></dt>
														<dd><input type="text" name="pet_allergies" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('pet_allergies', isset($data['pet_allergies']) ? $data['pet_allergies'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("horse_allergies");?></dt>
														<dd><input type="text" name="horse_allergies" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('horse_allergies', isset($data['horse_allergies']) ? $data['horse_allergies'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("allergen_guide_for_dogs_and_cats");?></dt>
														<dd><input type="text" name="allergen_guide" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('allergen_guide', isset($data['allergen_guide']) ? $data['allergen_guide'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("treatment_diary_dogs_and_cats");?></dt>
														<dd><input type="text" name="treatment_diary_dogs_cats" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('treatment_diary_dogs_cats', isset($data['treatment_diary_dogs_cats']) ? $data['treatment_diary_dogs_cats'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("treatment_diary_horses");?></dt>
														<dd><input type="text" name="treatment_diary_horses" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('treatment_diary_horses', isset($data['treatment_diary_horses']) ? $data['treatment_diary_horses'] : ''); ?>"></dd>
														<dt><?php echo $this->lang->line("allergy_vet_flyer");?></dt>
														<dd><input type="text" name="flyer" placeholder="<?php echo $this->lang->line("Units");?>" value="<?php echo set_value('flyer', isset($data['flyer']) ? $data['flyer'] : ''); ?>"></dd>
													</dl>
												</div><!-- /.col -->
											</div><!-- /.row -->
										</div><!-- /.box-body -->
									</div><!-- /.box -->
								<?php } ?>
								<!-- order material elements -->

								<div class="box-footer">
									<?php if((isset($id) && $id > 0) && ($this->uri->segment(1))=='orders') {?>
									<p class="pull-left">
										<button type="submit" value="save" name="save" class="btn btn-primary next_btn" ><?php echo $this->lang->line("save");?></button>
									</p>
									<p class="pull-right">
									<button type="submit" value="next" name="next" class="btn btn-primary next_btn"><?php echo $this->lang->line("next");?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
									</p>
									<?php } else { ?>
									<p class="pull-right">
									<button type="submit" value="next" name="next" class="btn btn-primary next_btn"><?php echo $this->lang->line("next");?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
									</p>
									<?php } ?>
								</div>
							<?php echo form_close(); ?>
							<!-- form end -->
						</div>
						<!--/.col (left) -->
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>
		</div><!-- ./wrapper -->
		
		<!--pet owner modal-->
		<div class="modal fade" id="addPracticeModal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo $this->lang->line("practice_details");?></h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'newPracticeForm', 'id' => 'newPracticeForm')); ?>
						<div class="modal-body">
							<span id="message" class="text-danger"></span>
							<input type="hidden" name="practice_labid_modal" id="practice_labid_modal" value="">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<div class="form-group">
									<label><?php echo $this->lang->line("practice_name");?></label>
									<input type="text" class="form-control" name="name" id="practice_fname" placeholder="<?php echo $this->lang->line("enter_first_name");?>" value="" required="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("email");?></label>
									<input type="email" class="form-control" name="email" placeholder="<?php echo $this->lang->line("enter_email");?>" value="" required="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("phone_number");?></label>
									<input type="text" class="form-control" name="phone_number" placeholder="<?php echo $this->lang->line("enter_phone_number");?>" value="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("Address_1");?></label>
									<input type="text" class="form-control" name="add_1" placeholder="<?php echo $this->lang->line("Address_1");?>" value="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("Address_2");?></label>
									<input type="text" class="form-control" name="add_2" placeholder="<?php echo $this->lang->line("Address_2");?>" value="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("Address_3");?></label>
									<input type="text" class="form-control" name="add_3" placeholder="<?php echo $this->lang->line("Address_3");?>" value="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("town_city");?></label>
									<input type="text" class="form-control" name="add_4" placeholder="<?php echo $this->lang->line("enter_town_city");?>" value="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("county");?></label>
									<input type="text" class="form-control" name="address_2" placeholder="<?php echo $this->lang->line("enter_county");?>" value="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("postcode");?></label>
									<input type="text" class="form-control" name="address_3" id="postCode" placeholder="<?php echo $this->lang->line("enter_postcode");?>" value="" required="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("country");?></label>
									<select name="country" class="form-control" data-live-search="true" required="">
										<option value="" selected="selected">-- Select --</option>
										<?php 
										if(!empty($countries)){
											foreach ($countries as $country) {
												echo '<option value="'.$country['id'].'">'.$country['name'].'</option>';
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-6">
								<div class="form-group">
									<label><?php echo $this->lang->line("account_ref");?></label>
									<input type="text" class="form-control" name="account_ref" placeholder="<?php echo $this->lang->line("enter_account_ref");?>" value="">
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("comment");?></label>
									<textarea class="form-control" name="comment" rows="3" placeholder="<?php echo $this->lang->line("enter_comment");?>"></textarea>
								</div>
								<div class="form-group">
									<label><?php echo $this->lang->line("rcvs_number");?></label>
									<input type="text" class="form-control" name="rcds_number" placeholder="<?php echo $this->lang->line("enter_rcvs_number");?>" value="">
								</div>
								<div class="form-group">
									<div class="checkbox">
									<label><input type="checkbox" name="vat_applicable" value="1" data-parsley-multiple="vat_applicable"><?php echo $this->lang->line("vat_applicable");?></label>
									</div>
								</div>
								<input type="hidden" name="last_name" id="practice_lname" value="">
								<input type="hidden" name="password" id="password" value="">
								<input type="hidden" name="tax_code" value="">
								<input type="hidden" name="vat_reg" value="">
								<input type="hidden" name="country_code" value="">
								<input type="hidden" name="order_can_send_to" value="1">
								<input type="hidden" name="odelivery_address" value="">
								<input type="hidden" name="opostal_code" value="">
								<input type="hidden" name="ocity" value="">
								<input type="hidden" name="ocountry" value="">
							</div>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("save_changes");?></button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--pet owner modal-->
		<?php $this->load->view("script"); ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
		<!--pet owner modal-->
		<div class="modal fade" id="petOwnerModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo $this->lang->line("pet_owner_details");?></h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'petOwnerForm', 'id' => 'petOwnerForm')); ?>
						<div class="modal-body">
							<span id="message" class="text-danger"></span>
							<?php $userData = logged_in_user_data(); ?>
							<input type="hidden" name="is_modal" value="1">
							<input type="hidden" name="petOwner_id_modal" id="petOwner_id_modal" value="">
							<div class="form-group">
								<label><?php echo $this->lang->line("pet_owner_first_name");?></label>
								<input type="text" class="form-control petOwnerName" name="name" placeholder="<?php echo $this->lang->line("enter_name");?>" value="">
								<?php echo form_error('name', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group">
								<label><?php echo $this->lang->line("pet_owner_last_name");?></label>
								<input type="text" class="form-control petOwnerLastName" name="last_name" placeholder="<?php echo $this->lang->line("enter_last_name");?>" value="" required="">
								<?php echo form_error('last_name', '<div class="error">', '</div>'); ?>
							</div>
							<?php /* <div class="form-group">
								<label>Pet Owner Post Code</label>
								<input type="text" class="form-control petOwnerPostCode" name="post_code" placeholder="Enter Post Code" value="">
								<?php echo form_error('post_code', '<div class="error">', '</div>'); ?>
							</div> */ ?>
							<div class="form-group">
								<input type="hidden" class="form-control parent_id" name="parent_id[]" id="poFormParent" value="">
								<input type="hidden" class="form-control branch_id" name="branch_id[]" id="poFormBranch" value="">
								<input type="hidden" class="form-control" name="user_type" value="2">
							</div>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("save_changes");?></button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--pet owner modal-->

		<!--pet modal-->
		<div class="modal fade" id="petModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo $this->lang->line("pet_details");?></h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'petForm', 'id' => 'petForm')); ?>
						<div class="modal-body">
							<span id="message" class="text-danger"></span>
							<?php $userData = logged_in_user_data(); ?>
							<input type="hidden" name="is_modal" value="1">
							<input type="hidden" name="pet_id_modal" id="pet_id_modal" value="">
							<div class="form-group">
								<input type="hidden" class="form-control" name="vet_user_id" value="">
								<input type="hidden" class="form-control" name="branch_id" value="">
								<input type="hidden" class="form-control" name="pet_owner_id" value="">
							</div>
							<div class="form-group">
								<label><?php echo $this->lang->line("pet_name");?></label>
								<input type="text" class="form-control petName" name="name" placeholder="<?php echo $this->lang->line("enter_name");?>" value="" required="">
								<?php echo form_error('name', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group">
								<label><?php echo $this->lang->line("Species");?></label>
								<select name="type" class="form-control type" required="required">
									<option value="">-- Select --</option>
									<?php
									if (!empty($species)) {
										foreach ($species as $specie) {
											if ($this->session->userdata('order_type') == '2' && $this->session->userdata('species') == $specie['name']){
												echo '<option value="'. $specie['id']. '" selected="selected">'. $specie['name']. '</option>';
											}else{
												echo '<option value="'. $specie['id']. '">'. $specie['name']. '</option>';
											}
										}
									}
									?>
								</select>
								<?php echo form_error('type', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group">
								<label><?php echo $this->lang->line("Breeds");?></label>
								<?php
								$breed_options = array();
								$breed_options[''] = '-- Select --';
								if (!empty($breeds)) {
									foreach ($breeds as $breed) {
										$user_id = $breed['id'];
										$breed_options[$user_id] = $breed['name'];
									}
								}
								$breed_options[0] = 'Other';
								$breed_attr = 'class="form-control breed_id" data-live-search="true"';
								echo form_dropdown('breed_id', $breed_options, '', $breed_attr); ?>
								<?php echo form_error('breed_id', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group other_breed_cls hidden">
								<label><?php echo $this->lang->line("breed_type");?></label>
								<input type="text" class="form-control other_breed" name="other_breed" placeholder="<?php echo $this->lang->line("enter_breed_type");?>" value="">
								<?php echo form_error('other_breed', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group">
								<label><?php echo $this->lang->line("date_of_birth");?></label>
							</div>
							<div class="form-group col-xs-6">
								<label><?php echo $this->lang->line("age_in_years");?></label>
								<input type="number" class="form-control age_year" name="age_year" placeholder="<?php echo $this->lang->line("enter_age_in_years");?>" maxlength="4" min="0" max="100">
								<?php echo form_error('age_year', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group col-xs-6">
								<label><?php echo $this->lang->line("age_in_months");?></label>
								<input type="number" class="form-control age" name="age" placeholder="<?php echo $this->lang->line("enter_age_in_months");?>" maxlength="2" min="1" max="11">
								<?php echo form_error('age', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group">
								<label><?php echo $this->lang->line("gender");?></label>
								<div class="radio">
									<label><input type="radio" name="gender" id="gender1" value="1"><?php echo $this->lang->line("male");?></label>
									<label><input type="radio" name="gender" id="gender2" value="2"><?php echo $this->lang->line("female");?></label>
								</div>
								<?php echo form_error('gender', '<div class="error">', '</div>'); ?>
							</div>
							<!--col-xs-6-->
							<div class="form-group" <?php if($userData['role'] == 5 && $this->session->userdata('user_type') != '3'){ echo 'style="display:none"'; }?>>
								<label><?php echo $this->lang->line("practice_comments");?></label>
								<textarea class="form-control comment" name="comment" rows="3" placeholder="<?php echo $this->lang->line("enter_comment");?>"></textarea>
								<?php echo form_error('comment', '<div class="error">', '</div>'); ?>
							</div>
							<?php if ($userData['role'] == 1 || $userData['role'] == 11 || $userData['role'] == 2) { ?>
							<div class="form-group">
								<label><?php echo $this->lang->line("nextmune_comments");?></label>
								<textarea class="form-control nextmune_comment" name="nextmune_comment" rows="3" placeholder="<?php echo $this->lang->line("enter_comment");?>"></textarea>
								<?php echo form_error('nextmune_comment', '<div class="error">', '</div>'); ?>
							</div>
							<?php } ?>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
							<button type="submit" name="submit" id="pet_submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("save_changes");?></button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--pet modal-->
		<?php
		$stype = '';
		if ($this->session->userdata('order_type') == '2' && $this->session->userdata('species_selection') == '3' && $this->session->userdata('species') == 'Cat'){
			$stype = '1';
		}elseif ($this->session->userdata('order_type') == '2' && $this->session->userdata('species_selection') == '1' && $this->session->userdata('species') == 'Dog'){
			$stype = '2';
		}elseif ($this->session->userdata('order_type') == '2' && $this->session->userdata('species_selection') == '2' && $this->session->userdata('species') == 'Horse'){
			$stype = '3';
		}
		?>
		<?php if (isset($id) && $id > 0 && ($data['pet_owner_id'] > 0)){ ?>
		<input type="hidden" id="petownerId" value="<?php echo $data['pet_owner_id'];?>">
		<script>
		$( window ).on("load", function() {
			var selectedPetUser = $('#petownerId').val();
			<?php if($order_type == '2' && $this->session->userdata('plc_selection') == '2'){ ?>
			var filtered_vetUser = $('select[name="lab_id"]').val();
			<?php }else{ ?>
			var filtered_vetUser = $('select[name="vet_user_id"]').val();
			<?php } ?>
			if (typeof filtered_vetUser === "undefined") {
				var filtered_vetUser = '<?php echo $userData['user_id']; ?>';
			}

			if (filtered_vetUser) {
				$.ajax({
					url: "<?php echo base_url('Users/get_petOwner_dropdown'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': filtered_vetUser,
						'pet_owner_id': selectedPetUser,
						'branch_id': '',
						'order_form_dp': ''
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('#orderForm select[name="pet_owner_id"]').empty();
						$('#orderForm select[name="pet_owner_id"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							var first_name = ''
							if (value.name != null) {
								first_name = value.name+' ';
							}
							var last_name = ''
							if (value.last_name != null) {
								last_name = value.last_name;
							}
							if(selectedPetUser == value.id){
								$('#orderForm select[name="pet_owner_id"]').append('<option value="' + value.id + '" data-append="" selected="selected">' + first_name + '' + last_name + '</option>');
							}else{
								$('#orderForm select[name="pet_owner_id"]').append('<option value="' + value.id + '" data-append="">' + first_name + '' + last_name + '</option>');
							}
						});
					}
				});
			} else {
				$('#orderForm select[name="pet_owner_id"]').empty();
			}
		});
		</script>
		<?php } ?>
		<?php if(count($vatLabUsers) == 1){ ?>
		<script>
		$( window ).on("load", function() {
			onchng();
			getPetOwners();
			getPets();
			getPhoneNumber();
			addrs();
			getCustomerUsers();
		});
		</script>
		<?php } ?>
		<?php if (isset($data['order_can_send_to']) && $data['order_can_send_to'] == '0' && $data['plc_selection'] == '2') { ?>
		<script>
		$( window ).on("load", function() {
			labAddress();
		});
		</script>
		<?php } ?>
		<?php if ($this->session->userdata('order_type') == '2'){ ?>
		<script>
		$( window ).on("load", function() {
			getBreeds();
		});
		</script>
		<?php } ?>
		<script>
		$( window ).on("load", function() {
			onchng();
			/* var filtered_lab_id = [];
			filtered_lab_id.push($(".lab_id").val());
			if (filtered_lab_id) {
				$.ajax({
					url: "<?php echo base_url('UsersDetails/get_branch_dropdown'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': filtered_lab_id
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('select[name="lab_branch_id"]').empty();
						$('select[name="lab_branch_id"]').append('<option value="">-- Select --</option>');
						$.each(data, function(key, value) {
							$('select[name="lab_branch_id"]').append('<option value="' + value.id + '">' + value.name + '</option>');
						});
					}
				});
			} else {
				$('select[name="lab_branch_id"]').empty();
			} */
		});
		</script>
		<script>
		$(document).ready(function() {
			$(document).on('click', '.select_shipping_materials', function(event) {
				if($(this).is(":checked")) {
					$(".qty").show();
				} else {
					$(".qty").hide();
				}
			});

			$(document).on('click', '.addPracticeModal', function(event) {
				event.preventDefault();
				var lab_id = $('.lab_id').val();
				$('#practice_labid_modal').val(lab_id);
			});

			$(document).on('submit', '#newPracticeForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				if($('#newPracticeForm #postCode').val() != ''){
				var practiceName = $('#newPracticeForm #practice_fname').val()+' '+$('#newPracticeForm #practice_lname').val()+' - '+$('#newPracticeForm #postCode').val();
				}else{
				var practiceName = $('#newPracticeForm #practice_fname').val()+' '+$('#newPracticeForm #practice_lname').val();	
				}
				practiceName = practiceName.trim();
				$.ajax({
					url: "<?php echo base_url('UsersDetails/practice_addEdit'); ?>",
					method: "POST",
					data: $(this).serialize(),
					dataType: "JSON",
					beforeSend: function() {
						$('#submit').val('wait...');
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						if (data.status == 'success') {
							$('#addPracticeModal').modal('hide');
							$('#orderForm select[name="vet_user_id"]').append('<option value="' + data.practiceId + '" data-append="" selected="selected">' + practiceName + '</option>');
						}else{
							$('#message').text("Something's went wrong please try again.");
						}
					}
				});
			});

			/* On change delivery adress */
			$('#orderForm input[type=radio][name=order_can_send_to]').change(function() {
				$('#address1').val('');
				$('#address2').val('');
				$('#address3').val('');
				$('#town_city').val('');
				$('#county').val('');
				$('#postcode').val('');
				$('#orderForm select[name="country"] option').removeAttr('selected');
				order_can_send_to = this.value;
				if (order_can_send_to == 0) {
					<?php if ($this->session->userdata('plc_selection') == '2') { ?>
					labAddress();
					<?php }else{ ?>
					addrs();
					<?php } ?>
					$('.other_practices').addClass('hidden');
					$('#address1').attr('readonly','readonly');
					$('#address2').attr('readonly','readonly');
					$('#address3').attr('readonly','readonly');
					$('#town_city').attr('readonly','readonly');
					$('#county').attr('readonly','readonly');
					$('#postcode').attr('readonly','readonly');
					$('.country').attr('style','pointer-events: none;');
					$('.country').attr('readonly','readonly');
				}else if (order_can_send_to == 1) {
					$('#address1').removeAttr('readonly');
					$('#address2').removeAttr('readonly');
					$('#address3').removeAttr('readonly');
					$('#town_city').removeAttr('readonly');
					$('#county').removeAttr('readonly');
					$('#postcode').removeAttr('readonly');
					$('.country').removeAttr('readonly');
					$('.country').removeAttr('style');
					$('.other_practices').removeAttr('readonly');
					$('.other_practices').removeClass('hidden');
				}else if (order_can_send_to == 2) {
					$('.other_practices').addClass('hidden');
					$('#address1').removeAttr('readonly');
					$('#address2').removeAttr('readonly');
					$('#address3').removeAttr('readonly');
					$('#town_city').removeAttr('readonly');
					$('#county').removeAttr('readonly');
					$('#postcode').removeAttr('readonly');
					$('.country').removeAttr('readonly');
					$('.country').removeAttr('style');
					$('.other_practices').removeAttr('readonly');
				}
			});
			/* On change delivery adress */
		});
		</script>
		<script>
		function addrs() {
			let vet_user_id = $(".vet_user_id").val();
			<?php if($userData['role'] != 11){ ?>
			$('select[name="delivery_practice_id"]').select2();
			$('.select2.select2-container').addClass('select-width');
			$('.select2-selection.select2-selection--single').addClass('select-width');
			<?php } ?>
			$(".delivery_practice_id option[value="+vet_user_id+"]").attr("selected", "selected");
			onchng();
		}

		$(document).on('change', 'select[name="delivery_practice_id"]',function(){
			onchng();
		})

		$(document).on('change', 'select[name="delivery_practice_branch_id"]', function(){
			onchng();
		});

		function onchng(order_branch_id){
			if($("#order_can_send_to2").is(':checked')){
				let vet_user_id = $('select[name="delivery_practice_id"]').val();
				<?php if(isset($id) && $id > 0){ ?>
				<?php }else{ ?>
				$('select[name="delivery_practice_id"]').select2();
				$('.select2.select2-container').addClass('select-width');
				$('.select2-selection.select2-selection--single').addClass('select-width');
				<?php } ?>
				if(order_branch_id == ""){
					let order_branch_id = $('select[name="delivery_practice_branch_id"]').val();
				}

				$.ajax({
					url: "<?php echo base_url('UsersDetails/get_practice_address'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': vet_user_id,
						'id':order_branch_id
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$("#address1").val(data.address);
						$("#address1").attr('value',data.address);
						$("#address2").val(data.address1);
						$("#address2").attr('value',data.address1);
						$("#address3").val(data.address2);
						$("#address3").attr('value',data.address2);
						$("#address4").val(data.address3);
						$("#town_city").val(data.town_city);
						$("#town_city").attr('value',data.town_city);
						$("#county").val(data.county);
						$("#county").attr('value',data.county);
						$("#postcode").val(data.postcode);
						$("#postcode").attr('value',data.postcode);
						$("#country").val(data.country);
						$(".country option[value=" + data.country + "]").attr("selected", "selected");
						if(data.country == '1'){
							<?php if(isset($id) && $id > 0){ ?>
							<?php }else{ ?>
							$("#sic_document").attr("required","required");
							$('.sicDocument').show();
							<?php } ?>
						} else if(data.country != '1' && data.country > 0){
							$('.sicDocument').hide();
							$("#sic_document").removeAttr("required");
						}
						<?php if(isset($id) && $id > 0){ ?>
						<?php }else{ ?>
						$("#order_email").val(data.email);
						$("#order_email").attr('value',data.email);
						getPracticemails(vet_user_id);
						<?php } ?>
					}
				});
			}else{
				<?php if ($this->session->userdata('plc_selection') == '2') { ?>
					labAddress();
				<?php }else{ ?>
				let vet_user_id = $('select[name="vet_user_id"]').val();
				$.ajax({
					url: "<?php echo base_url('UsersDetails/get_practice_address'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': vet_user_id
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$("#address1").val(data.address);
						$("#address1").attr('value',data.address);
						$("#address2").val(data.address1);
						$("#address2").attr('value',data.address1);
						$("#address3").val(data.address2);
						$("#address3").attr('value',data.address2);
						$("#address4").val(data.address3);
						$("#town_city").val(data.town_city);
						$("#town_city").attr('value',data.town_city);
						$("#county").val(data.county);
						$("#county").attr('value',data.county);
						$("#postcode").val(data.postcode);
						$("#postcode").attr('value',data.postcode);
						$("#country").val(data.country);
						$(".country option[value=" + data.country + "]").attr("selected", "selected");
						if(data.country == '1'){
							<?php if(isset($id) && $id > 0){ ?>
							<?php }else{ ?>
							$("#sic_document").attr("required","required");
							$('.sicDocument').show();
							<?php } ?>
						} else if(data.country != '1' && data.country > 0){
							$('.sicDocument').hide();
							$("#sic_document").removeAttr("required");
						}
						<?php if(isset($id) && $id > 0){ ?>
						<?php }else{ ?>
						$("#order_email").val(data.email);
						$("#order_email").attr('value',data.email);
						getPracticemails(vet_user_id);
						<?php } ?>
					}
				});
				<?php } ?>
			}
		}
		
		function getPracticemails(vet_user_id){
			if(vet_user_id > 0){
				$.ajax({
					url: "<?php echo base_url('UsersDetails/get_practice_emails'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': vet_user_id
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('#orderForm select[id="customer_emails"]').empty();
						$('#orderForm select[id="customer_emails"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							$('#orderForm select[id="customer_emails"]').append('<option value="' + value.email + '" data-append="">' + value.email + '</option>');
						});
					}
				});
			} else {
				$('#orderForm select[id="customer_emails"]').empty();
			}
		}

		$(document).on('change', 'select[name="lab_id"]', function(){
			let lab_id = $('select[name="lab_id"]').val();
			if(lab_id == '' || lab_id == null || lab_id == undefined){
				lab_id = $('input[name="lab_id"]').val();
			}
			$.ajax({
				url: "<?php echo base_url('UsersDetails/get_lab_address'); ?>",
				type: 'POST',
				data: {
					'lab_id': lab_id
				},
				dataType: "json",
				success: function(data) {
					$('#cover-spin').hide();
					if(data.deliver_to_practice == 1){
						$("#order_can_send_to2").click();
						$(".other_practices").removeClass(' hidden');
						$('select[name="delivery_practice_id"]').select2();
						$('.select2.select2-container').addClass('select-width');
						$('.select2-selection.select2-selection--single').addClass('select-width');
					}else{
						$("#order_can_send_to1").click();
						labAddress();
					}
				}
			});
		});

		function labAddress(){
			let lab_id = $('select[name="lab_id"]').val();
			if(lab_id == '' || lab_id == null || lab_id == undefined){
				lab_id = $('input[name="lab_id"]').val();
			}
			$.ajax({
				url: "<?php echo base_url('UsersDetails/get_lab_address'); ?>",
				type: 'POST',
				data: {
					'lab_id': lab_id
				},
				dataType: "json",
				success: function(data) {
					$('#cover-spin').hide();
					$("#address1").val(data.address);
					$("#address1").attr('value',data.address);
					$("#address2").val(data.address1);
					$("#address2").attr('value',data.address1);
					$("#address3").val(data.address2);
					$("#address3").attr('value',data.address2);
					$("#address4").val(data.address3);
					$("#town_city").val(data.town_city);
					$("#town_city").attr('value',data.town_city);
					$("#county").val(data.county);
					$("#county").attr('value',data.county);
					$("#postcode").val(data.postcode);
					$("#postcode").attr('value',data.postcode);
					$("#country").val(data.country);
					$(".country option[value=" + data.country + "]").attr("selected", "selected");
					<?php if(isset($id) && $id > 0){ ?>
					<?php }else{ ?>
					$("#order_email").val(data.email);
					$("#order_email").attr('value',data.email);
					getLabemails(lab_id);
					<?php } ?>
				}
			});
		}

		function getLabemails(lab_id){
			if(lab_id > 0){
				$.ajax({
					url: "<?php echo base_url('UsersDetails/get_lab_emails'); ?>",
					type: 'POST',
					data: {
						'lab_id': lab_id
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('#orderForm select[id="customer_emails"]').empty();
						$('#orderForm select[id="customer_emails"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							$('#orderForm select[id="customer_emails"]').append('<option value="' + value.email + '" data-append="">' + value.email + '</option>');
						});
					}
				});
			} else {
				$('#orderForm select[id="customer_emails"]').empty();
			}
		}

		$(document).ready(function() {
			$('#orderForm').parsley();
			$('#sic_document').change(function() {
				var fakePath = document.getElementById('sic_document').value;
				var fileName = fakePath.split("\\").pop();
				if ((fileName.split(".") || []).length > 2) {
					if ($('.error_sic').hasClass('d-none')) {
						$('.error_sic').removeClass('d-none')
					}
					$('.next_btn').prop('disabled', true);
				} else {
					if (!$('.error_sic').hasClass('d-none')) {
						$('.error_sic').addClass('d-none')
					}
					$('.next_btn').prop('disabled', false);
				}
			});

			$(document).on('click', '.removeSIC', function() {
				if (confirm('Are you sure you want to remove this SIC document?')) {
					var href = $(this).data('href');
					var doc_name = $(this).data('doc_name');
					var order_id = $(this).data('order_id');
					$('#cover-spin').show();
					$.ajax({
						url: href,
						type: 'POST',
						data: {
							"doc_name": doc_name,
							"order_id": order_id
						},
						success: function(data) {
							$('#cover-spin').hide();
							if (data == 'failed') {
								alert('Something went wrong!');
							} else {
								location.reload();
							}
						}
					});
				}
			});

			window.ParsleyValidator.addValidator('file_extension', function(value, requirement) {
					var fileExtension = value.split('.').pop();
					return fileExtension === requirement;
			}, 32).addMessage('en', 'file_extension', 'Please upload pdf file only.');

			//Date picker
			var todayDate = new Date();
			var id = '<?php echo $id; ?>';
			//console.log(id);
			$('input[name="order_date"]').datepicker({
				format: "dd/mm/yyyy",
				todayHighlight: true,
				autoclose: true,
				setDate: todayDate,
			});
			if (id == '') {
				$('input[name="order_date"]').datepicker('setDate', todayDate).datepicker('fill');
			}

			$('input[name="sampling_date"]').datepicker({
				format: "dd/mm/yyyy",
				todayHighlight: true,
				autoclose: true,
			});

			$(".select2").select2({
				width: '100%',
				templateResult: formatCustom
			});

			$(".pet_search, .breed_id").select2({
				width: '100%',
			});

			$(".vet_user_id, .delivery_practice_id").select2({
				width: '100%',
			});

			$(document).on('change', 'input:radio[name="order_type"]', function(event) {
				$('.sub_order_type').removeClass('hidden');
				if ($(this).is(':checked')) {
					if ($(this).val() == '1') {
						$('.sub_order_type1').removeClass('hidden');
						$('.sub_order_type2').removeClass('hidden');
						$('.sub_order_type3').addClass('hidden');
						$('.sub_order_type4').addClass('hidden');
					} else if ($(this).val() == '2') {
						$('.sub_order_type1').addClass('hidden');
						$('.sub_order_type2').addClass('hidden');
						$('.sub_order_type3').removeClass('hidden');
						$('.sub_order_type4').addClass('hidden');
					} else if ($(this).val() == '3') {
						$('.sub_order_type1').addClass('hidden');
						$('.sub_order_type2').addClass('hidden');
						$('.sub_order_type3').addClass('hidden');
						$('.sub_order_type4').removeClass('hidden');
					}
				} else {
					$('.sub_order_type').addClass('hidden');
				}
			});

			$('#orderForm select[name="vet_user_id"]').on('change', function() {
				getPetOwners();
				getPets();
				getPhoneNumber();
				getCustomerUsers();
				addrs();
			}); //select vet_user_id

			$('#orderForm select[name="branch_id"]').on('change', function() {
				getPetOwners();
				addrs();
			});

			$('#petForm select[name="vet_user_id"]').on('change', function() {
				/* var filtered_vet_user_id = [];
				filtered_vet_user_id.push($(this).val());
				if (filtered_vet_user_id) {
					$.ajax({
						url: "<?php echo base_url('UsersDetails/get_branch_dropdown'); ?>",
						type: 'POST',
						data: {
							'vet_user_id': filtered_vet_user_id
						},
						dataType: "json",
						success: function(data) {
							$('#cover-spin').hide();
							$('#petForm select[name="branch_id"]').empty();
							$('#petForm select[name="branch_id"]').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								var branch_postcode = '';
								if (value.postcode != '') {
									branch_postcode = ' - ' + value.postcode;
								}
								$('#petForm select[name="branch_id"]').append('<option value="' + value.id + '">' + value.name + branch_postcode + '</option>');
							});
						}
					});
				} else {
					$('#petForm select[name="branch_id"]').empty();
				} */
				getPetOwnersModal();
			}); //select vet_user_id

			$('select[name="corporate_id"]').on('change', function() {
				/* var filtered_corporate_id = [];
				filtered_corporate_id.push($(this).val());
				if (filtered_corporate_id) {
					$.ajax({
						url: "<?php echo base_url('UsersDetails/get_branch_dropdown'); ?>",
						type: 'POST',
						data: {
							'vet_user_id': filtered_corporate_id
						},
						dataType: "json",
						success: function(data) {
							$('#cover-spin').hide();
							$('select[name="corporate_branch_id"]').empty();
							$('select[name="corporate_branch_id"]').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								$('select[name="corporate_branch_id"]').append('<option value="' + value.id + '">' + value.name + '</option>');
							});
						}
					});
				} else {
					$('select[name="corporate_branch_id"]').empty();
				} */
			}); //select corporate_id

			/* modal script */
			$('input[type=radio][name=user_type]').change(function() {
				user_type = this.value;
				if (user_type == 2) {
					$('.branch_cls').removeClass('hidden');
				} else {
					$('.branch_cls').addClass('hidden');
				}
				if (user_type) {
					$.ajax({
						url: "<?php echo base_url('Users/get_users_dropdown'); ?>",
						type: 'POST',
						data: {
							'user_type': user_type
						},
						dataType: "json",
						success: function(data) {
							$('#cover-spin').hide();
							$('.parent_id').selectpicker('destroy');
							$('.parent_id').empty();
							$('.parent_id').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								$('.parent_id').append('<option value="' + value.id + '">' + value.name + '</option>');
							});
							$('.parent_id').addClass('selectpicker').selectpicker('refresh');
						}
					});
				} else {
					$('.parent_id').selectpicker('destroy');
					$('.parent_id').empty();
				}
			});

			$('select[name="parent_id[]"]').on('change', function() {
				/* var filtered_vet_user_id = $(this).val();
				if (filtered_vet_user_id) {
					$.ajax({
						url: "<?php echo base_url('UsersDetails/get_branch_dropdown'); ?>",
						type: 'POST',
						data: {
							'vet_user_id': filtered_vet_user_id
						},
						dataType: "json",
						success: function(data) {

							$('#cover-spin').hide();
							$('.branch_id').selectpicker('destroy');
							$('.branch_id').empty();
							$('.branch_id').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								var branch_postcode = '';
								if (value.postcode != '') {
									branch_postcode = ' - ' + value.postcode;
								}
								$('.branch_id').append('<option value="' + value.id + '">' + value.name + branch_postcode + '</option>');
							});
							$('.branch_id').addClass('selectpicker').selectpicker('refresh');
						}
					});
				} else {
					$('.branch_id').selectpicker('destroy');
					$('.branch_id').empty();
				} */
			}); //select vet_user_id

			$(document).on('click', '.petOwnerModal', function(event) {
				event.preventDefault();
				<?php if($order_type == '2' && $this->session->userdata('plc_selection') == '2'){ ?>
				var practice = $('#orderForm select[name="lab_id"]').val();
				<?php }else{ ?>
				var practice = $('#orderForm select[name="vet_user_id"]').val();
				<?php } ?>
				var petOwner_id = $(this).data('petowner_id');
				$('#petOwner_id_modal').val(petOwner_id);
				$('#petOwnerForm input[name="parent_id[]"]').val(practice);
				var btnType = $(this).data('petowner_btn');
				if(btnType == "Add"){
					$('#petOwnerForm .petOwnerName').val("");
					$('#petOwnerForm .petOwnerLastName').val("");
				}else{
					if (practice == '') {
						alert('Please select PO Number/Practice Reference Number.');
						return false;
					}
					if (petOwner_id > 0) {
						$.ajax({
							url: "<?php echo base_url('UsersDetails/getPetOwnerDetails'); ?>",
							data: {
								'petOwner_id': petOwner_id
							},
							method: "POST",
							dataType: "json",
							success: function(data) {
								if (data != '') {
									$('#petOwnerForm .petOwnerName').val(data.name);
									$('#petOwnerForm .petOwnerLastName').val(data.last_name);
									$('#petOwnerForm .petOwnerPostCode').val(data.post_code);
								}
							}, //success
						}); //ajax
					}
				}
			});

			$(document).on('submit', '#petOwnerForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var petOwnerName = $('#petOwnerForm .petOwnerName').val()+' '+$('#petOwnerForm .petOwnerLastName').val();
				petOwnerName = petOwnerName.trim();
				var petOwnerID = $('#petOwnerForm #petOwner_id_modal').val();
				var extra = '';
				if (petOwnerID > 0) {
					extra = '/' + petOwnerID;
				}

				$.ajax({
					url: "<?php echo base_url('UsersDetails/petOwners_addEdit'); ?>" + extra,
					method: "POST",
					data: $(this).serialize(),
					dataType: "JSON",
					beforeSend: function() {
						$('#submit').val('wait...');
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						var dp_postcode = data.postCode;
						//console.log(data);
						$('#submit').attr('disabled', false);
						if (data.status == 'fail') {
							$('#message').text(data.error);
						} else {
							$('#petOwnerModal').modal('hide');
							$('#petownerEdit').show();
							$('#orderForm select[name="pet_owner_id"]').append('<option value="' + data.petOwnerId + '" data-append="" selected="selected">' + petOwnerName + '</option>');
						}
					}
				});
			});

			$('select[name="pet_id"]').on('change', function() {
				var petUser = $('select[name="pet_id"]').val();
				if(petUser > 0){
					$("button.petModal").data('pet_id',petUser);
					$("button.petModal").show();
				}
			});

			$(document).on('change', 'select[name="type"]', function(event) {
				var filtered_type = [];
				filtered_type.push($(this).val());
				if (filtered_type) {
					$.ajax({
						url: "<?php echo base_url('Breeds/get_breeds_dropdown'); ?>",
						type: 'POST',
						data: {
							'species_id': filtered_type
						},
						dataType: "json",
						success: function(data) {
							$('#cover-spin').hide();
							$('.breed_id').empty();
							$('.breed_id').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								$('.breed_id').append('<option value="' + value.id + '">' + value.name + '</option>');
							});
							$('.breed_id').append('<option value="0">Other</option>');
						}
					});
				} else {
					$('.breed_id').empty();
				}
			}); //select type

			$('select[name="breed_id"]').on('change', function() {
				var value = $(this).val();
				//console.log(value);
				$('#petForm .other_breed_cls').addClass('hidden');
				if (value != '' && value == 0) {
					$('#petForm .other_breed_cls').removeClass('hidden');
				}
			}); //select breed_id

			$(document).on('click', '.petModal', function(event) {
				event.preventDefault();
				<?php if($order_type == '2' && $this->session->userdata('plc_selection') == '2'){ ?>
				var practice = $('#orderForm select[name="lab_id"]').val();
				<?php }else{ ?>
				var practice = $('#orderForm select[name="vet_user_id"]').val();
				<?php } ?>
				var pet_owner = $('#orderForm select[name="pet_owner_id"]').val();
				var pet_id = $(this).data('pet_id');
				$('#pet_id_modal').val(pet_id);
				$('#petForm input[name="vet_user_id"]').val(practice);
				$('#petForm input[name="pet_owner_id"]').val(pet_owner);
				var petbtnType = $(this).data('pet_btn');
				if(petbtnType == "Add"){
					$('#petForm .petName').val('');
					//$('#petForm .type').val('');
					$('#petForm .breed_id').val('');
					$('#petForm .other_breed').val('');
					$('#petForm .age').val('');
					$('#petForm .age_year').val('');
					$('#petForm #gender1').removeAttr('checked');
					$('#petForm #gender2').removeAttr('checked');
					$('#petForm .comment').val('');
					$('#petForm .nextmune_comment').val('');
				}else{
					if (practice == '' || pet_owner == '') {
						alert('Please select PO Number/Practice Reference Number and Pet Owner.');
						return false;
					}
					if (pet_id > 0) {
						$.ajax({
							url: "<?php echo base_url('Pets/getPetDetails'); ?>",
							data: {
								'pet_id': pet_id
							},
							method: "POST",
							dataType: "json",
							success: function(data) {
								//console.log(data);
								if (data != '') {
									$('#petForm .petName').val(data.name);
									$('#petForm .type').val(data.type);
									$('#petForm .breed_id').val(data.breed_id);
									if (data.other_breed != '') {
										$('#petForm .other_breed_cls').removeClass('hidden');
									}
									$('#petForm .other_breed').val(data.other_breed);
									$('#petForm .age').val(data.age);
									$('#petForm .age_year').val(data.age_year);
									if (data.gender != 'NULL' && data.gender != '' && data.gender == '1') {
										$('#petForm #gender1').attr('checked','checked');
									} else if (data.gender != 'NULL' && data.gender != '' && data.gender == '2') {
										$('#petForm #gender2').attr('checked','checked');
									}else{
										$('#petForm #gender1').removeAttr('checked');
										$('#petForm #gender2').removeAttr('checked');
									}
									$('#petForm .comment').val(data.comment);
									$('#petForm .nextmune_comment').val(data.nextmune_comment);
									getBreedsedit(data.type,data.breed_id);
								}
							}, //success
						}); //ajax
					}else{
						$('#petForm .petName').val('');
						//$('#petForm .type').val('');
						$('#petForm .breed_id').val('');
						$('#petForm .other_breed').val('');
						$('#petForm .age').val('');
						$('#petForm .age_year').val('');
						$('#petForm #gender1').removeAttr('checked');
						$('#petForm #gender2').removeAttr('checked');
						$('#petForm .comment').val('');
						$('#petForm .nextmune_comment').val('');
					}
				}
			});

			$(document).on('submit', '#petForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var petName = $('.petName').val();
				var species = $('#petForm select[name="type"] option:selected').text();
				var breed = $('#petForm select[name="breed_id"] option:selected').text();
				var petID = $('#petForm #pet_id_modal').val();
				var extra = '';
				if (petID > 0) {
					extra = '/' + petID;
				}
				//console.log(petID);
				$.ajax({
					url: "<?php echo base_url('Pets/addEdit'); ?>" + extra,
					method: "POST",
					data: $(this).serialize(),
					dataType: "JSON",
					async: false,
					beforeSend: function() {
						$('#submit').val('wait...');
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						$('#submit').attr('disabled', false);
						if (data.status == 'fail') {
							$('#message').text(data.error);
						} else {
							if(data.petId=='' || data.petId==null || data.petId==undefined){
								var petid = petID
							}else{
								var petid = data.petId
								if(petid==true){
									var petid = petID
								}
							}
							$('#petModal').modal('hide');
							$('#orderForm select[name="pet_id"]').append('<option value="' + petid + '" data-append="Species: ' + species + '" selected="selected">' + petName + '</option>');
							var petUser = $('select[name="pet_id"]').val();
							if(petUser > 0){
								$("button.petModal").data('pet_id',petUser);
								$('#petEdit').show();
							}
						}
					}
				});
			}); //petForm

			/* modal script */
			/* onchange delivery practice */
			$('select[name="delivery_practice_id"]').on('change', function() {
				/* var filtered_delivery_practice_id = [];
				filtered_delivery_practice_id.push($(this).val());

				if (filtered_delivery_practice_id) {
					$.ajax({
						url: "<?php echo base_url('UsersDetails/get_branch_dropdown'); ?>",
						type: 'POST',
						data: {
							'vet_user_id': filtered_delivery_practice_id
						},
						dataType: "json",
						success: function(data) {
							$('#cover-spin').hide();
							$('select[name="delivery_practice_branch_id"]').empty();
							$('select[name="delivery_practice_branch_id"]').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								var branch_postcode = '';
								if (value.postcode != '') {
									branch_postcode = ' - ' + value.postcode;
								}
								$('select[name="delivery_practice_branch_id"]').append('<option value="' + value.id + '">' + value.name + branch_postcode + '</option>');
							});
						}
					});
				} else {
					$('select[name="delivery_practice_branch_id"]').empty();
				} */
			});
			/* onchange delivery practice */

			$('select[id="customer_id"]').on('change', function() {
				var value = $('select[id="customer_id"] :selected').text();
				var slctedId = $(this).val();
				if (value != '' && slctedId > 0) {
					$('.surgeon_name').val(value);
					$('.surgeon_name').attr('value',value);
				}else{
					$('.surgeon_name').val("");
					$('.surgeon_name').attr('value',"");
				}
			});

			$('select[id="customer_emails"]').on('change', function() {
				var value = $('select[id="customer_emails"] :selected').text();
				if (value != '') {
					$('#order_email').val(value);
					$('#order_email').attr('value',value);
				}else{
					$('#order_email').val("");
					$('#order_email').attr('value',"");
				}
			});
		});

		function getCustomerUsers() {
			var filteredVetUser = $('select[name="vet_user_id"]').val();
			if (typeof filteredVetUser === "undefined") {
				var filteredVetUser = '<?php echo $userData['user_id']; ?>';
			}

			if (filteredVetUser) {
				$.ajax({
					url: "<?php echo base_url('Users/get_customer_users'); ?>",
					type: 'POST',
					data: {'vet_user_id': filteredVetUser},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('#orderForm select[id="customer_id"]').empty();
						$('#orderForm select[id="customer_id"]').append('<option value="0">-- Select --</option>');
						$.each(data, function(key, value) {
							$('#orderForm select[id="customer_id"]').append('<option value="' + value.id + '">'+ value.name +'</option>');
						});
					}
				});
			} else {
				$('#orderForm select[id="customer_id"]').empty();
			}
		}

		function getPetOwners() {
			var filtered_vetUser = $('select[name="vet_user_id"]').val();
			if (typeof filtered_vetUser === "undefined") {
				var filtered_vetUser = '<?php echo $userData['user_id']; ?>';
			}

			//console.log(filtered_vetUser);
			var filtered_branch = $('select[name="branch_id"]').val();
			if (filtered_vetUser) {
				$.ajax({
					url: "<?php echo base_url('Users/get_petOwner_dropdown'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': filtered_vetUser,
						'branch_id': filtered_branch,
						'order_form_dp': 'yes'
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('#orderForm select[name="pet_owner_id"]').empty();
						$('#orderForm select[name="pet_owner_id"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							var first_name = ''
							if (value.name != null) {
								first_name = value.name+' ';
							}
							var last_name = ''
							if (value.last_name != null) {
								last_name = value.last_name;
							}
							$('#orderForm select[name="pet_owner_id"]').append('<option value="' + value.id + '" data-append="">' + first_name + '' + last_name + '</option>');
						});
					}
				});
			} else {
				$('#orderForm select[name="pet_owner_id"]').empty();
			}
			getPets();
		}

		function formatCustom(state) {
			return $(
				'<div><div>' + state.text + '</div><div class="foo">' +
				$(state.element).attr('data-append') +
				'</div></div>'
			);
		}

		function getPetOwnersModal() {
			var filtered_vetUser = $('#petForm select[name="vet_user_id"]').val();
			var filtered_branch = $('#petForm select[name="branch_id"]').val();
			if (filtered_vetUser) {
				$.ajax({
					url: "<?php echo base_url('Users/get_petOwner_dropdown'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': filtered_vetUser,
						'branch_id': filtered_branch
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('#petForm select[name="pet_owner_id"]').empty();
						$('#petForm select[name="pet_owner_id"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							var first_name = ''
							if (value.name != null) {
								first_name = value.name+' ';
							}
							var last_name = ''
							if (value.last_name != null) {
								last_name = value.last_name;
							}
							$('#petForm select[name="pet_owner_id"]').append('<option value="' + value.id + '" data-append="">' + first_name + "" + last_name + '</option>');
						});
					}
				});
			} else {
				$('#petForm select[name="pet_owner_id"]').empty();
			}
			getPets();
		}

		function getPets() {
			var filtered_id = $('select[name="pet_owner_id"]').val();
			if(filtered_id > 0){
				$("button.petOwnerModal").data('petowner_id',filtered_id);
				$("button.petOwnerModal").show();
			}
			var vet_user_id = $('select[name="vet_user_id"]').val();
			var is_petOwner = true;
			var user_role = '<?php echo $userData['role']; ?>';
			var is_repeat = '<?php echo $controller; ?>';
			var stype = '<?php echo $stype; ?>';
			var batch_number = '<?php echo (isset($data['batch_number']) ? $data['batch_number'] : '') ?>';
			var additional_details = '';
			if (batch_number != '' && is_repeat == 'repeatOrder') {
				additional_details = ', Breed: ' + batch_number;
			}

			if (user_role == '5') {
				vet_user_id = '<?php echo (isset($tm_vet_user)) ? $tm_vet_user : ''; ?>';
			}
			if (typeof vet_user_id === "undefined") {
				vet_user_id = '<?php echo $userData['user_id']; ?>';
			}

			if (!filtered_id) {
				filtered_id = vet_user_id = $('select[name="vet_user_id"]').val();
				if (typeof filtered_id === "undefined") {
					filtered_id = '<?php echo $userData['user_id']; ?>';
				}
				is_petOwner = false;
			}
			//console.log(filtered_id);
			if (filtered_id) {
				$.ajax({
					url: "<?php echo base_url('Pets/get_pets_dropdown'); ?>",
					type: 'POST',
					data: {
						'pet_owner_id': filtered_id,
						'is_petOwner': is_petOwner,
						'vet_user_id': vet_user_id,
						'stype': stype
					},
					dataType: "json",
					async: false,
					success: function(data) {
						$('#cover-spin').hide();
						$('select[name="pet_id"]').empty();
						$('select[name="pet_id"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							$('select[name="pet_id"]').append('<option value="' + value.id + '" data-append="Species: ' + value.species_name + additional_details + '">' + value.name + '</option>');
						});
					}
				});
			} else {
				$('select[name="pet_id"]').empty();
			}
		}

		function getPhoneNumber() {
			var filtered_vetUser = $('#orderForm select[name="vet_user_id"]').val();
			if (typeof filtered_vetUser === "undefined") {
				var filtered_vetUser = '<?php echo $userData['user_id']; ?>';
			}

			if (filtered_vetUser) {
				$.ajax({
					url: "<?php echo base_url('Users/get_phone_number'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': filtered_vetUser
					},
					dataType: "json",
					success: function(data) {
						//console.log(data.phone_number);
						$('#cover-spin').hide();
						$('#orderForm input[name="phone_number"]').val(data.phone_number);

					}
				});
			} else {
				$('#orderForm input[name="phone_number"]').val();
			}
			getPets();
		}

		function validateFile(fileInput) {
			var files = fileInput.files;
			// if (files.length === 0) {
			//     return;
			// }

			var fileName = files[0].name;
			var return_flag = true;
			$('.next_btn').prop("disabled", false);
			if (fileName.length >= 60) {
				alert('File input name to long.');
				return_flag = false;
				$('.next_btn').attr('disabled', 'disabled');
			}
			return return_flag;
		}

		function getBreedsedit(tId,bId){
			var filtered_type = [];
			filtered_type.push(tId);
			if (filtered_type) {
				$.ajax({
					url: "<?php echo base_url('Breeds/get_breeds_dropdown'); ?>",
					type: 'POST',
					data: {
						'species_id': filtered_type
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('.breed_id').empty();
						$('.breed_id').append('<option value="">-- Select --</option>');
						$.each(data, function(key, value) {
							if(bId == value.id){
							$('.breed_id').append('<option value="' + value.id + '" selected="selected">' + value.name + '</option>');
							}else{
							$('.breed_id').append('<option value="' + value.id + '">' + value.name + '</option>');
							}
						});
						$('.breed_id').append('<option value="0">Other</option>');
					}
				});
			} else {
				$('.breed_id').empty();
			}
		}

		function getBreeds(){
			var filtered_type = [];
			filtered_type.push($('select[name="type"]').find(":selected").val());
			if (filtered_type) {
				$.ajax({
					url: "<?php echo base_url('Breeds/get_breeds_dropdown'); ?>",
					type: 'POST',
					data: {
						'species_id': filtered_type
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('.breed_id').empty();
						$('.breed_id').append('<option value="">-- Select --</option>');
						$.each(data, function(key, value) {
							$('.breed_id').append('<option value="' + value.id + '">' + value.name + '</option>');
						});
						$('.breed_id').append('<option value="0">Other</option>');
					}
				});
			} else {
				$('.breed_id').empty();
			}
		}
		</script>
		<?php if($order_type == '2' && $this->session->userdata('plc_selection') == '2'){ ?>
		<script>
		$(document).ready(function() {
			$('select[name="lab_id"]').on('change', function(){
				$('#petownerEdit').hide();
				$('#petEdit').hide();
				getLabPetOwners();
				getLabPetOwnersModal();
			});
		});

		function getLabPetOwners() {
			var filtered_vetUser = $('select[name="lab_id"]').val();
			if (typeof filtered_vetUser === "undefined") {
				var filtered_vetUser = '<?php echo $userData['user_id']; ?>';
			}
			if (filtered_vetUser) {
				$.ajax({
					url: "<?php echo base_url('Users/get_petOwner_dropdown'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': filtered_vetUser,
						'branch_id': '',
						'order_form_dp': 'yes'
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('#orderForm select[name="pet_owner_id"]').empty();
						$('#orderForm select[name="pet_owner_id"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							var first_name = ''
							if (value.name != null) {
								first_name = value.name+' ';
							}
							var last_name = ''
							if (value.last_name != null) {
								last_name = value.last_name;
							}
							$('#orderForm select[name="pet_owner_id"]').append('<option value="' + value.id + '" data-append="">' + first_name + '' + last_name + '</option>');
						});
					}
				});
			} else {
				$('#orderForm select[name="pet_owner_id"]').empty();
			}
			getLabPets();
		}

		function getLabPets() {
			var filtered_id = $('select[name="pet_owner_id"]').val();
			if(filtered_id > 0){
				$("button.petOwnerModal").data('petowner_id',filtered_id);
				$("button.petOwnerModal").show();
			}
			var vet_user_id = $('select[name="lab_id"]').val();
			var is_petOwner = true;
			var user_role = '<?php echo $userData['role']; ?>';
			var is_repeat = '<?php echo $controller; ?>';
			var stype = '<?php echo $stype; ?>';
			var batch_number = '<?php echo (isset($data['batch_number']) ? $data['batch_number'] : '') ?>';
			var additional_details = '';
			if (batch_number != '' && is_repeat == 'repeatOrder') {
				additional_details = ', Breed: ' + batch_number;
			}

			if (user_role == '5') {
				vet_user_id = '<?php echo (isset($tm_vet_user)) ? $tm_vet_user : ''; ?>';
			}
			if (typeof vet_user_id === "undefined") {
				vet_user_id = '<?php echo $userData['user_id']; ?>';
			}

			if (!filtered_id) {
				filtered_id = vet_user_id = $('select[name="lab_id"]').val();
				if (typeof filtered_id === "undefined") {
					filtered_id = '<?php echo $userData['user_id']; ?>';
				}
				is_petOwner = false;
			}
			if (filtered_id) {
				$.ajax({
					url: "<?php echo base_url('Pets/get_pets_dropdown'); ?>",
					type: 'POST',
					data: {
						'pet_owner_id': filtered_id,
						'is_petOwner': is_petOwner,
						'vet_user_id': vet_user_id,
						'stype': stype
					},
					dataType: "json",
					async: false,
					success: function(data) {
						$('#cover-spin').hide();
						$('select[name="pet_id"]').empty();
						$('select[name="pet_id"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							$('select[name="pet_id"]').append('<option value="' + value.id + '" data-append="Species: ' + value.species_name + additional_details + '">' + value.name + '</option>');
						});
					}
				});
			} else {
				$('select[name="pet_id"]').empty();
			}
		}

		function getLabPetOwnersModal() {
			var filtered_vetUser = $('#petForm select[name="vet_user_id"]').val();
			var filtered_branch = $('#petForm select[name="branch_id"]').val();
			if (filtered_vetUser) {
				$.ajax({
					url: "<?php echo base_url('Users/get_petOwner_dropdown'); ?>",
					type: 'POST',
					data: {
						'vet_user_id': filtered_vetUser,
						'branch_id': filtered_branch
					},
					dataType: "json",
					success: function(data) {
						$('#cover-spin').hide();
						$('#petForm select[name="pet_owner_id"]').empty();
						$('#petForm select[name="pet_owner_id"]').append('<option value="" data-append="">-- Select --</option>');
						$.each(data, function(key, value) {
							var first_name = ''
							if (value.name != null) {
								first_name = value.name+' ';
							}
							var last_name = ''
							if (value.last_name != null) {
								last_name = value.last_name;
							}
							$('#petForm select[name="pet_owner_id"]').append('<option value="' + value.id + '" data-append="">' + first_name + "" + last_name + '</option>');
						});
					}
				});
			} else {
				$('#petForm select[name="pet_owner_id"]').empty();
			}
			getLabPets();
		}
		</script>
		<?php } ?>
	</body>
</html>
