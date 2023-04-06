<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<style>
			.removeRbtn{color:#e30613;font-size: 20px;}
			.removebtn{color:#e30613}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Allergens
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Allergens</li>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content">
					<!--alert msg-->
					<?php if(!empty($this->session->flashdata('success'))){ ?>
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Alert!</h4>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>
					<?php if(!empty($this->session->flashdata('error'))){ ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-warning"></i> Alert!</h4>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php } ?>
					<!--alert msg-->
					<?php
					if(isset($data['id']) && $data['id'] > 0){
						if(isset($data['is_mixtures']) && $data['is_mixtures'] == 1){
							$raptorcls = 'style="display:none"';
							$mixturecls = '';
						}else{
							$raptorcls = '';
							$mixturecls = 'style="display:none"';
						}

						if((in_array("8",json_decode($data['order_type']))) || (in_array("9",json_decode($data['order_type']))) || (in_array("11",json_decode($data['order_type'])))){
							$paxName = '';
							$paxLName = '';
							$raptorcls = '';
						}else{
							$paxName = 'style="display:none"';
							$paxLName = 'style="display:none"';
							$raptorcls = 'style="display:none"';
						}
					}else{
						$raptorcls = '';
						$mixturecls = 'style="display:none"';
						$paxName = 'style="display:none"';
						$paxLName = 'style="display:none"';
					}
					?>
					<style>
					.mrgbtm{margin-bottom:3px;}
					</style>
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<?php echo form_open('', array('name'=>'sub_allergenForm', 'id'=>'sub_allergenForm')); ?>
									<div class="box-header with-border">
										<div class="pull-left">
											<h3 class="box-title"><?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3>
										</div>
										<?php if(isset($data['id']) && $data['id'] > 0){ ?>
											<div class="pull-right">
												<div class="col-sm-9 col-md-9 col-lg-9" style="padding:0px;padding-top: 7px;">
													<p><b>Please select the language you want to manage</b></p>
												</div>
												<div class="col-sm-3 col-md-3 col-lg-3" style="padding:0px">
												<div class="form-group">
													<select class="form-control language_id" name="language_id" id="language_id">
														<option value="english" <?php if($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
														<option value="danish" <?php if($this->session->userdata('site_lang') == 'danish') echo 'selected="selected"'; ?>>Danish</option>
														<option value="french" <?php if($this->session->userdata('site_lang') == 'french') echo 'selected="selected"'; ?>>French</option>
														<option value="german" <?php if($this->session->userdata('site_lang') == 'german') echo 'selected="selected"'; ?>>German</option>
														<option value="italian" <?php if($this->session->userdata('site_lang') == 'italian') echo 'selected="selected"'; ?>>Italian</option>
														<option value="dutch" <?php if($this->session->userdata('site_lang') == 'dutch') echo 'selected="selected"'; ?>>Dutch</option>
														<option value="norwegian" <?php if($this->session->userdata('site_lang') == 'norwegian') echo 'selected="selected"'; ?>>Norwegian</option>
														<option value="spanish" <?php if($this->session->userdata('site_lang') == 'spanish') echo 'selected="selected"'; ?>>Spanish</option>
														<option value="swedish" <?php if($this->session->userdata('site_lang') == 'swedish') echo 'selected="selected"'; ?>>Swedish</option>
													</select>
												</div>
												</div>
											</div>
										<?php } ?>
									</div>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label>Order Type</label>
													<?php
													$options = array('1'=>'Artuvetrin immunotherapy','2'=>'Sublingual immunotherapy (SLIT)','3'=>'NextLab - Dog - Environmental','31'=>'NextLab - Cat - Environmental','6'=>'NextLab - Horse - Environmental','5'=>'NextLab - Dog - Food','51'=>'NextLab - Cat - Food','7'=>'NextLab - Horse - Food','8'=>'PAX - Environmental','9'=>'PAX - Food','11'=>'PAX - Environmental - US','12'=>'PAX - Food - US','4'=>'Skin Test','13'=>'Vet-Goid','14'=>'Pet SLIT','15'=>'Immucept SLIT','16'=>'Immucept SCIT');
													$attr = 'class="form-control selectpicker" required="" data-live-search="true" multiple="" ';
													echo form_multiselect('order_type[]', $options, isset($data['order_type']) ? json_decode($data['order_type']) : '', $attr);
													?>
													<?php echo form_error('order_type[]', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Allergen Group</label>
													<select class="form-control parent_id" name="parent_id" id="parent_id">
														<option value="">--Select--</option>
														<?php foreach ( $allergens as $allergen ){ ?>
															<option value="<?php echo $allergen['id']; ?>" <?php if(isset($id) && $id>0 && ($allergen['id']==$data['parent_id'])) echo 'selected="selected"'; ?>><?php echo $allergen['name']; ?></option>
														<?php  } ?>
													</select>
													<?php echo form_error('parent_id', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group pax_parentID" <?php if(isset($id) && $id > 0 && (in_array("8",json_decode($data['order_type'])) || in_array("9",json_decode($data['order_type'])) || in_array("11",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>PAX Group Name</label>
													<select class="form-control pax_parent_id" name="pax_parent_id" id="pax_parent_id">
														<option value="">--Select--</option>
														<?php foreach($paxallergens as $pallergen){ ?>
															<option value="<?php echo $pallergen['id']; ?>" <?php if(isset($id) && $id>0 && ($pallergen['id']==$data['pax_parent_id'])) echo 'selected="selected"'; ?>><?php echo !empty($pallergen['pax_name'])?$pallergen['pax_name']:$pallergen['name']; ?></option>
														<?php } ?>
													</select>
													<?php echo form_error('pax_parent_id', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name="is_mixtures" value="1" <?php echo (isset($data['is_mixtures']) && $data['is_mixtures'] == 1) ? 'checked="checked"' : ''; ?>>
															This allergen is a Mixture?
														</label>
													</div>
												</div>
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name="available_as_mixtures" value="1" <?php echo (isset($data['available_as_mixtures']) && $data['available_as_mixtures'] == 1) ? 'checked="checked"' : ''; ?>>
															Only available as part of a Mixture?
														</label>
													</div>
												</div>
												<div class="form-group mixture_orderType" <?php echo (isset($data['available_as_mixtures']) && $data['available_as_mixtures'] == 1) ? 'style="display:block"' : 'style="display:none"'; ?>>
													<label>Mixture available on Order Type</label>
													<?php
													$attr = 'class="form-control selectpicker" data-live-search="true" multiple="" ';
													echo form_multiselect('mixture_order_type[]', $options, isset($data['mixture_order_type']) ? json_decode($data['mixture_order_type']) : '', $attr);
													?>
													<?php echo form_error('mixture_order_type[]', '<div class="error">', '</div>'); ?>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group aName" id="englishName">
													<label>English Allergen Name (Default)</label>
													<input type="text" class="form-control" name="name" placeholder="Enter English Allergen Name" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
													<?php echo form_error('name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group aName" id="danishName" style="display:none">
													<label>Danish Allergen Name</label>
													<input type="text" class="form-control" name="name_danish" placeholder="Enter Danish Allergen Name" value="<?php echo set_value('name_danish',isset($data['name_danish']) ? $data['name_danish'] : '');?>">
													<?php echo form_error('name_danish', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group aName" id="frenchName" style="display:none">
													<label>French Allergen Name</label>
													<input type="text" class="form-control" name="name_french" placeholder="Enter French Allergen Name" value="<?php echo set_value('name_french',isset($data['name_french']) ? $data['name_french'] : '');?>">
													<?php echo form_error('name_french', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group aName" id="germanName" style="display:none">
													<label>German Allergen Name</label>
													<input type="text" class="form-control" name="name_german" placeholder="Enter German Allergen Name" value="<?php echo set_value('name_german',isset($data['name_german']) ? $data['name_german'] : '');?>">
													<?php echo form_error('name_german', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group aName" id="italianName" style="display:none">
													<label>Italian Allergen Name</label>
													<input type="text" class="form-control" name="name_italian" placeholder="Enter Italian Allergen Name" value="<?php echo set_value('name_italian',isset($data['name_italian']) ? $data['name_italian'] : '');?>">
													<?php echo form_error('name_italian', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group aName" id="dutchName" style="display:none">
													<label>Dutch Allergen Name</label>
													<input type="text" class="form-control" name="name_dutch" placeholder="Enter Dutch Allergen Name" value="<?php echo set_value('name_dutch',isset($data['name_dutch']) ? $data['name_dutch'] : '');?>">
													<?php echo form_error('name_dutch', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group aName" id="norwegianName" style="display:none">
													<label>Norwegian Allergen Name</label>
													<input type="text" class="form-control" name="name_norwegian" placeholder="Enter Norwegian Allergen Name" value="<?php echo set_value('name_norwegian',isset($data['name_norwegian']) ? $data['name_norwegian'] : '');?>">
													<?php echo form_error('name_norwegian', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group aName" id="spanishName" style="display:none">
													<label>Spanish AllergenName</label>
													<input type="text" class="form-control" name="name_spanish" placeholder="Enter Spanish Allergen Name" value="<?php echo set_value('name_spanish',isset($data['name_spanish']) ? $data['name_spanish'] : '');?>">
													<?php echo form_error('name_spanish', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group aName" id="swedishName" style="display:none">
													<label>Swedish Allergen Name</label>
													<input type="text" class="form-control" name="name_swedish" placeholder="Enter Swedish Allergen Name" value="<?php echo set_value('name_swedish',isset($data['name_swedish']) ? $data['name_swedish'] : '');?>">
													<?php echo form_error('name_swedish', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group paxName apName" id="englishPAXName" <?php echo $paxName; ?>>
													<label>English PAX Name (Default)</label>
													<input type="text" class="form-control" name="pax_name" placeholder="Enter English PAX Name" value="<?php echo set_value('pax_name',isset($data['pax_name']) ? $data['pax_name'] : '');?>">
													<?php echo form_error('pax_name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group apName" id="danishPAXName" style="display:none">
													<label>Danish PAX Name</label>
													<input type="text" class="form-control" name="pax_name_danish" placeholder="Enter Danish PAX Name" value="<?php echo set_value('pax_name_danish',isset($data['pax_name_danish']) ? $data['pax_name_danish'] : '');?>">
													<?php echo form_error('pax_name_danish', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group apName" id="frenchPAXName" style="display:none">
													<label>French PAX Name</label>
													<input type="text" class="form-control" name="pax_name_french" placeholder="Enter French PAX Name" value="<?php echo set_value('pax_name_french',isset($data['pax_name_french']) ? $data['pax_name_french'] : '');?>">
													<?php echo form_error('pax_name_french', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group apName" id="germanPAXName" style="display:none">
													<label>German PAX Name</label>
													<input type="text" class="form-control" name="pax_name_german" placeholder="Enter German PAX Name" value="<?php echo set_value('pax_name_german',isset($data['pax_name_german']) ? $data['pax_name_german'] : '');?>">
													<?php echo form_error('pax_name_german', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group apName" id="italianPAXName" style="display:none">
													<label>Italian PAX Name</label>
													<input type="text" class="form-control" name="pax_name_italian" placeholder="Enter Italian PAX Name" value="<?php echo set_value('pax_name_italian',isset($data['pax_name_italian']) ? $data['pax_name_italian'] : '');?>">
													<?php echo form_error('pax_name_italian', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group apName" id="dutchPAXName" style="display:none">
													<label>Dutch PAX Name</label>
													<input type="text" class="form-control" name="pax_name_dutch" placeholder="Enter PAX Dutch Name" value="<?php echo set_value('pax_name_dutch',isset($data['pax_name_dutch']) ? $data['pax_name_dutch'] : '');?>">
													<?php echo form_error('pax_name_dutch', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group apName" id="norwegianPAXName" style="display:none">
													<label>Norwegian PAX Name</label>
													<input type="text" class="form-control" name="pax_name_norwegian" placeholder="Enter Norwegian PAX Name" value="<?php echo set_value('pax_name_norwegian',isset($data['pax_name_norwegian']) ? $data['pax_name_norwegian'] : '');?>">
													<?php echo form_error('pax_name_norwegian', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group apName" id="spanishPAXName" style="display:none">
													<label>Spanish PAX Name</label>
													<input type="text" class="form-control" name="pax_name_spanish" placeholder="Enter Spanish PAX Name" value="<?php echo set_value('pax_name_spanish',isset($data['pax_name_spanish']) ? $data['pax_name_spanish'] : '');?>">
													<?php echo form_error('pax_name_spanish', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group apName" id="swedishPAXName" style="display:none">
													<label>Swedish PAX Name</label>
													<input type="text" class="form-control" name="pax_name_swedish" placeholder="Enter Swedish PAX Name" value="<?php echo set_value('pax_name_swedish',isset($data['pax_name_swedish']) ? $data['pax_name_swedish'] : '');?>">
													<?php echo form_error('pax_name_swedish', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group paxName" <?php echo $paxLName; ?>>
													<label>PAX Latin Name</label>
													<input type="text" class="form-control" name="pax_latin_name" placeholder="Enter PAX Latin Name" value="<?php echo set_value('pax_latin_name',isset($data['pax_latin_name']) ? $data['pax_latin_name'] : '');?>">
													<?php echo form_error('pax_latin_name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Exact Code</label>
													<input type="text" class="form-control" name="code" id="exactCode" placeholder="Enter Code" value="<?php echo set_value('code',isset($data['code']) ? $data['code'] : '');?>">
													<?php echo form_error('code', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDCanine" <?php if(isset($id) && $id > 0 && (in_array("3",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Canine</label>
													<input type="text" class="form-control" name="can_allgy_env" id="can_allgy_env" placeholder="Enter LIMS Allergen ID Canine" value="<?php echo set_value('can_allgy_env',isset($data['can_allgy_env']) ? $data['can_allgy_env'] : '');?>">
													<?php echo form_error('can_allgy_env', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDFeline" <?php if(isset($id) && $id > 0 && (in_array("31",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Feline</label>
													<input type="text" class="form-control" name="fel_allgy_env" id="fel_allgy_env" placeholder="Enter LIMS Allergen ID Feline" value="<?php echo set_value('fel_allgy_env',isset($data['fel_allgy_env']) ? $data['fel_allgy_env'] : '');?>">
													<?php echo form_error('fel_allgy_env', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDEquine" <?php if(isset($id) && $id > 0 && (in_array("6",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Equine</label>
													<input type="text" class="form-control" name="equ_allgy_env" id="equ_allgy_env" placeholder="Enter LIMS Allergen ID Equine" value="<?php echo set_value('equ_allgy_env',isset($data['equ_allgy_env']) ? $data['equ_allgy_env'] : '');?>">
													<?php echo form_error('equ_allgy_env', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDCanineIgE" <?php if(isset($id) && $id > 0 && (in_array("5",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Food IgE Canine</label>
													<input type="text" class="form-control" name="can_allgy_food_ige" id="can_allgy_food_ige" placeholder="Enter LIMS Allergen ID Food IgE Canine" value="<?php echo set_value('can_allgy_food_ige',isset($data['can_allgy_food_ige']) ? $data['can_allgy_food_ige'] : '');?>">
													<?php echo form_error('can_allgy_food_ige', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDCanineIgG" <?php if(isset($id) && $id > 0 && (in_array("5",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Food IgG Canine</label>
													<input type="text" class="form-control" name="can_allgy_food_igg" id="can_allgy_food_igg" placeholder="Enter LIMS Allergen ID Food IgG Canine" value="<?php echo set_value('can_allgy_food_igg',isset($data['can_allgy_food_igg']) ? $data['can_allgy_food_igg'] : '');?>">
													<?php echo form_error('can_allgy_food_igg', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDFelineIgE" <?php if(isset($id) && $id > 0 && (in_array("51",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Food IgE Feline</label>
													<input type="text" class="form-control" name="fel_allgy_food_ige" id="fel_allgy_food_ige" placeholder="Enter LIMS Allergen ID Food IgE Feline" value="<?php echo set_value('fel_allgy_food_ige',isset($data['fel_allgy_food_ige']) ? $data['fel_allgy_food_ige'] : '');?>">
													<?php echo form_error('fel_allgy_food_ige', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDFelineIgG" <?php if(isset($id) && $id > 0 && (in_array("51",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Food IgG Feline</label>
													<input type="text" class="form-control" name="fel_allgy_food_igg" id="fel_allgy_food_igg" placeholder="Enter LIMS Allergen ID Food IgG Feline" value="<?php echo set_value('fel_allgy_food_igg',isset($data['fel_allgy_food_igg']) ? $data['fel_allgy_food_igg'] : '');?>">
													<?php echo form_error('fel_allgy_food_igg', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDEquineIgE" <?php if(isset($id) && $id > 0 && (in_array("7",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Food IgE Equine</label>
													<input type="text" class="form-control" name="equ_allgy_food_ige" id="equ_allgy_food_ige" placeholder="Enter LIMS Allergen ID Food IgE Equine" value="<?php echo set_value('equ_allgy_food_ige',isset($data['equ_allgy_food_ige']) ? $data['equ_allgy_food_ige'] : '');?>">
													<?php echo form_error('equ_allgy_food_ige', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group limsIDEquineIgG" <?php if(isset($id) && $id > 0 && (in_array("7",json_decode($data['order_type'])))){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label>LIMS Allergen ID Food IgG Equine</label>
													<input type="text" class="form-control" name="equ_allgy_food_igg" id="equ_allgy_food_igg" placeholder="Enter LIMS Allergen ID Food IgG Equine" value="<?php echo set_value('equ_allgy_food_igg',isset($data['equ_allgy_food_igg']) ? $data['equ_allgy_food_igg'] : '');?>">
													<?php echo form_error('equ_allgy_food_igg', '<div class="error">', '</div>'); ?>
												</div>
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-sm-12 col-md-12 col-lg-12" id="raptor_block" <?=$raptorcls?>>
												<div class="form-group">
													<label>Raptor Code</label>
													<div class="box-body table-responsive no-padding" style="overflow:revert;">
														<table class="table table-hover">
															<thead>
																<tr>
																	<th style="width:20%;text-align:center;">E/M Allergen</th>
																	<th style="width:30%;text-align:center;">Code</th>
																	<th style="width:40%;text-align:center;">Allergen Family</th>
																	<th style="width:10%;text-align:center;">Action</th>
																</tr>
															</thead>
															<tbody id="addedProduct">
																<?php 
																$totalCunt = 0;
																if(isset($data['id']) && $data['id'] > 0){
																	$this->db->select('*');
																	$this->db->from('ci_allergens_raptor');
																	$this->db->where('allergens_id', $data['id']);
																	$res1 = $this->db->get();
																	if($res1->num_rows() == 0){ ?>
																		<tr id="row_0">
																			<td colspan="4">
																				<table class="mrgbtm" style="width:100%;">
																					<thead>
																						<tr>
																							<td style="width:20%;"><select class="form-control emAllergen em_allergen_0" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="2">Allergen Extract</option><option value="1">Allergen Description</option></select></td>
																							<td style="width:30%;"><input type="text" class="form-control raptorCode raptor_code_0" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td>
																							<td style="width:40%;"><input type="text" class="form-control raptorFunction raptor_function_0" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td>
																							<td style="width:10%;text-align: center;"><a class="removeRbtn" onclick="removeRCode(0)" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td>
																						</tr>
																					</thead>
																					<tbody id="addedrow_0">
																						<tr class="row_0_0">
																							<td colspan="3"><input type="text" class="form-control mrgbtm " name="raptor_header[0][]" placeholder="Allergen/Extract/Description"></td>
																							<td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow(0,0)" style="color:#e30613" title="Remove Interpretation"><i class="fa fa-times-circle"></i></a></td>
																						</tr>
																					</tbody>
																					<tfoot>
																						<tr>
																							<td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="0" class="pull-right btn btn-primary btnAddMore"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_0" value="0"></td>
																						</tr>
																					</tfoot>
																				</table>
																			</td>
																		</tr>
																	<?php }else{
																		$rcodeArr = $res1->result();
																		$x=0; $totalCunt = count($rcodeArr)-1;
																		foreach($rcodeArr as $row){
																		?>
																			<tr id="row_<?php echo $x; ?>">
																				<td colspan="4">
																					<table class="mrgbtm" style="width:100%;">
																						<thead>
																							<tr>
																								<td style="width:20%;">
																									<select class="form-control emAllergen em_allergen_<?php echo $x; ?>" name="em_allergen[<?php echo $x; ?>]">
																										<option value="">--Select E/M Allergen--</option>
																										<option value="3" <?php if($row->em_allergen == 3){ echo 'selected="selected"'; } ?>>Molecular Allergen</option>
																										<option value="2" <?php if($row->em_allergen == 2){ echo 'selected="selected"'; } ?>>Allergen Extract</option>
																										<option value="1" <?php if($row->em_allergen == 1){ echo 'selected="selected"'; } ?>>Allergen Description</option>
																									</select>
																								</td>
																								<td style="width:30%;"><input <?php if($row->em_allergen == 1){ echo 'type="hidden"'; }else{ echo 'type="text"'; } ?> class="form-control raptorCode raptor_code_<?php echo $x; ?>" name="raptor_code[<?php echo $x; ?>]" placeholder="Enter Raptor Code" value="<?php echo $row->raptor_code;?>"></td>
																								<td style="width:40%;"><input <?php if($row->em_allergen == 1){ echo 'type="hidden"'; }else{ echo 'type="text"'; } ?> class="form-control raptorFunction raptor_function_<?php echo $x; ?>" name="raptor_function[<?php echo $x; ?>]" placeholder="Enter Raptor Function" value="<?php echo $row->raptor_function;?>"></td>
																								<td style="width:10%;text-align: center;"><a class="removeRbtn" onclick="removeRCode(<?php echo $x; ?>)" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td>
																							</tr>
																						</thead>
																						<tbody id="addedrow_<?php echo $x; ?>">
																							<?php
																							$detailsArr = array(); $detailsdanArr = $detailsfreArr = $detailsgerArr = $detailsitaArr = $detailsdutArr = $detailsnorArr = $detailsspaArr = $detailssweArr = []; $y=0; 
																							if($row->raptor_header != "" && $row->raptor_header != '[""]'){
																								$detailsArr = json_decode($row->raptor_header);
																							}
																							if($row->raptor_header_danish != "" && $row->raptor_header_danish != '[""]'){
																								$detailsdanArr = json_decode($row->raptor_header_danish);
																							}
																							if($row->raptor_header_french != "" && $row->raptor_header_french != '[""]'){
																								$detailsfreArr = json_decode($row->raptor_header_french);
																							}
																							if($row->raptor_header_german != "" && $row->raptor_header_german != '[""]'){
																								$detailsgerArr = json_decode($row->raptor_header_german);
																							}
																							if($row->raptor_header_italian != "" && $row->raptor_header_italian != '[""]'){
																								$detailsitaArr = json_decode($row->raptor_header_italian);
																							}
																							if($row->raptor_header_dutch != "" && $row->raptor_header_dutch != '[""]'){
																								$detailsdutArr = json_decode($row->raptor_header_dutch);
																							}
																							if($row->raptor_header_norwegian != "" && $row->raptor_header_norwegian != '[""]'){
																								$detailsnorArr = json_decode($row->raptor_header_norwegian);
																							}
																							if($row->raptor_header_spanish != "" && $row->raptor_header_spanish != '[""]'){
																								$detailsspaArr = json_decode($row->raptor_header_spanish);
																							}
																							if($row->raptor_header_swedish != "" && $row->raptor_header_swedish != '[""]'){
																								$detailssweArr = json_decode($row->raptor_header_swedish);
																							}
																							
																							if(!empty($detailsArr)){
																								foreach($detailsArr as $key=>$rowd){
																								?>
																								<tr class="row_<?php echo $x; ?>_<?php echo $key; ?>">
																									<td colspan="3"><span class="raptorHeader raptor_header_english"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][1][]" value="<?php echo $rowd;?>"></span><span class="raptorHeader raptor_header_danish" style="display:none;"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][2][]" value="<?php echo !empty($detailsdanArr[$key])?$detailsdanArr[$key]:''; ?>"></span><span class="raptorHeader raptor_header_french" style="display:none;"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][3][]" value="<?php echo !empty($detailsfreArr[$key])?$detailsfreArr[$key]:''; ?>"></span><span class="raptorHeader raptor_header_german" style="display:none;"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][4][]" value="<?php echo !empty($detailsgerArr[$key])?$detailsgerArr[$key]:''; ?>"></span><span class="raptorHeader raptor_header_italian" style="display:none;"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][5][]" value="<?php echo !empty($detailsitaArr[$key])?$detailsitaArr[$key]:''; ?>"></span><span class="raptorHeader raptor_header_dutch" style="display:none;"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][6][]" value="<?php echo !empty($detailsdutArr[$key])?$detailsdutArr[$key]:''; ?>"></span><span class="raptorHeader raptor_header_norwegian" style="display:none;"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][7][]" value="<?php echo !empty($detailsnorArr[$key])?$detailsnorArr[$key]:''; ?>"></span><span class="raptorHeader raptor_header_spanish" style="display:none;"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][8][]" value="<?php echo !empty($detailsspaArr[$key])?$detailsspaArr[$key]:''; ?>"></span><span class="raptorHeader raptor_header_swedish" style="display:none;"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][9][]" value="<?php echo !empty($detailssweArr[$key])?$detailssweArr[$key]:''; ?>"></span></td>
																									<td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow(<?php echo $x; ?>,<?php echo $key; ?>)" style="color:#e30613" title="Remove Interpretation"><i class="fa fa-times-circle"></i></a></td>
																								</tr>
																								<?php 
																									$y++;
																								}
																							}else{ ?>
																								<tr class="row_<?php echo $x; ?>_0">
																									<td colspan="3"><input type="text" class="form-control mrgbtm " name="raptor_header[<?php echo $x; ?>][]" placeholder="Allergen/Extract/Description"></td>
																									<td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow(<?php echo $x; ?>,0)" style="color:#e30613" title="Remove Interpretation"><i class="fa fa-times-circle"></i></a></td>
																								</tr>
																							<?php } ?>
																						</tbody>
																						<tfoot>
																							<tr>
																								<td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="<?php echo $x; ?>" class="pull-right btn btn-primary btnAddMore"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_<?php echo $x; ?>" value="<?php echo $y; ?>"></td>
																							</tr>
																						</tfoot>
																					</table>
																				</td>
																			</tr>
																		<?php 
																			$x++;
																		}
																	}	
																}else{ ?>
																	<tr id="row_0">
																		<td colspan="4">
																			<table class="mrgbtm" style="width:100%;">
																				<thead>
																					<tr>
																						<td style="width:20%;"><select class="form-control emAllergen em_allergen_0" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="2">Allergen Extract</option><option value="1">Allergen Description</option></select></td>
																						<td style="width:30%;"><input type="text" class="form-control raptorCode raptor_code_0" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td>
																						<td style="width:40%;"><input type="text" class="form-control raptorFunction raptor_function_0" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td>
																						<td style="width:10%;text-align: center;"><a class="removeRbtn" onclick="removeRCode(0)" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td>
																					</tr>
																				</thead>
																				<tbody id="addedrow_0">
																					<tr class="row_0_0">
																						<td colspan="3"><input type="text" class="form-control mrgbtm " name="raptor_header[0][]" placeholder="Allergen/Extract/Description"></td>
																						<td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow(0,0)" style="color:#e30613" title="Remove Interpretation"><i class="fa fa-times-circle"></i></a></td>
																					</tr>
																				</tbody>
																				<tfoot>
																					<tr>
																						<td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="0" class="pull-right btn btn-primary btnAddMore"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_0" value="0"></td>
																					</tr>
																				</tfoot>
																			</table>
																		</td>
																	</tr>
																<?php } ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6" id="mixture_block" <?=$mixturecls?>>
												<?php 
												if(isset($data['id']) && $data['id'] > 0){
													$slctedArr = !empty($data['mixture_allergens'])?json_decode($data['mixture_allergens']):array();
													$this->db->select('id,name');
													$this->db->from('ci_allergens');
													$this->db->where('parent_id', $data['parent_id']);
													$allgnsArr = $this->db->get()->result_array();
													?>
													<div class="form-group col-lg-10">
														<label>Allergens</label>
														<select class="form-control allergens_id selectpicker" id="allergens_id" multiple="multiple">
															<option value="">-- Select --</option>
															<?php 
															foreach($allgnsArr as $row){
																if(in_array($row['id'],$slctedArr)){
																	echo '<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';
																}else{
																	echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
																}
															}
															?>
														</select>
													</div>
													<div class="form-group col-lg-2">
														<label></label><br>
														<a href="javascript:void(0);" class="btn btn-success btn-sm" id="filterBtn">Add</a>
													</div>
													<table id="mixtureAllergens" class="table table-bordered table-striped">
														<thead>
															<tr>
																<th>Allergen</th>
																<th></th>
															</tr>
														</thead>
														<tbody id="addAllergens">
															<?php 
															if(!empty($slctedArr)){
																foreach($allgnsArr as $row){
																	if(in_array($row['id'],$slctedArr)){
																		echo '<tr id="row_'.$row['id'].'"><td><input type="hidden" name="mixture_allergens[]" value="'.$row['id'].'">'.$row['name'].'</td><td><a onclick="removeMCode('.$row['id'].')" style="color:#e30613"><i class="fa fa-times-circle" aria-hidden="true"></i></a></td></tr>';
																	}
																}
															}
															?>
														</tbody>
													</table>
												<?php }else{ ?>
													<div class="form-group col-lg-10">
														<label>Allergens</label>
														<select class="form-control allergens_id selectpicker" id="allergens_id" multiple="multiple">
															<option value="">-- Select --</option>
														</select>
													</div>
													<div class="form-group col-lg-2">
														<label></label><br>
														<a href="javascript:void(0);" class="btn btn-success btn-sm" id="filterBtn">Add</a>
													</div>
													<table id="mixtureAllergens" class="table table-bordered table-striped">
														<thead>
															<tr>
																<th>Allergen</th>
																<th></th>
															</tr>
														</thead>
														<tbody id="addAllergens"></tbody>
													</table>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<div class="pull-left">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
										<div class="pull-right">
											<a href="javascript:void(0)" id="btnAddProduct" class="pull-right btn btn-info"> Add New Code <i class="fa fa-plus"></i></a><input type="hidden" id="addedRcodes" value="<?php echo $totalCunt;?>">
										</div>
									</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>  
		</div>
		<?php $this->load->view("script"); ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
		<input type="hidden" id="selectedAGroup" name="selectedAGroup" value="<?php if(isset($id) && $id>0){ echo empty($data['parent_id'])?$data['pax_parent_id']:$data['parent_id']; }else{ echo '0'; } ?>"/>
		<script>
		$(document).ready(function(){
			checkEMAllergen();
			$('#sub_allergenForm').parsley();
			$(document).on('change','select[name="em_allergen[]"]',function(){
				var clsName = $(this).attr('class');
				clsName = clsName.replace("form-control ", "");
				var codeclsName = clsName.replace("em_allergen_", "raptor_code_");
				var fucnclsName = clsName.replace("em_allergen_", "raptor_function_");
				if($(this).val() != 1){
					$('input.'+codeclsName+'').prop("type", "text");
					$('input.'+fucnclsName+'').prop("type", "text");
				}
				if($(this).val() == 2){
					$('select.'+clsName+' option[value="2"]').attr('selected','selected');
				}
				if($(this).val() == 1){
					$('input.'+codeclsName+'').prop("type", "hidden");
					$('input.'+fucnclsName+'').prop("type", "hidden");
					$('select.'+clsName+' option[value="1"]').attr('selected','selected');
				}
				checkEMAllergen();
			});

			$('select[name="order_type[]"]').on('change', function() {
				var filtered_order_type = $(this).val();
				var selectedGroup = $("#selectedAGroup").val();
				if(filtered_order_type){
					if((jQuery.inArray("1", filtered_order_type) != -1) || (jQuery.inArray("4", filtered_order_type) != -1)) {
						$("#exactCode").attr("required","required");
					} else {
						$("#exactCode").removeAttr("required");
					}

					if(jQuery.inArray("3", filtered_order_type) != -1) {
						$(".limsIDCanine").show();
					} else {
						$(".limsIDCanine").hide();
					}

					if(jQuery.inArray("31", filtered_order_type) != -1) {
						$(".limsIDFeline").show();
					} else {
						$(".limsIDFeline").hide();
					}

					if(jQuery.inArray("6", filtered_order_type) != -1) {
						$(".limsIDEquine").show();
					} else {
						$(".limsIDEquine").hide();
					}

					if(jQuery.inArray("5", filtered_order_type) != -1) {
						$(".limsIDCanineIgE").show();
						$(".limsIDCanineIgG").show();
					} else {
						$(".limsIDCanineIgE").hide();
						$(".limsIDCanineIgG").hide();
					}

					if(jQuery.inArray("51", filtered_order_type) != -1) {
						$(".limsIDFelineIgE").show();
						$(".limsIDFelineIgG").show();
					} else {
						$(".limsIDFelineIgE").hide();
						$(".limsIDFelineIgG").hide();
					}

					if(jQuery.inArray("7", filtered_order_type) != -1) {
						$(".limsIDEquineIgE").show();
						$(".limsIDEquineIgG").show();
					} else {
						$(".limsIDEquineIgE").hide();
						$(".limsIDEquineIgG").hide();
					}

					if((jQuery.inArray("8", filtered_order_type) != -1) || (jQuery.inArray("9", filtered_order_type) != -1) || (jQuery.inArray("11", filtered_order_type) != -1)) {
						//$("#parent_id").removeAttr("required");
						//$("#pax_parent_id").attr("required","required");
						$(".pax_parentID").show();
						$(".paxName").show();
						$(".paxLName").show();
						$("#raptor_block").show();
						$.ajax({
							url:      "<?php echo base_url('Allergens/get_allergens_dropdown_pax'); ?>",
							type:     'POST',
							data:     {'order_type':filtered_order_type},
							dataType: "json",
							success:  function (data) {
								$('#cover-spin').hide();
								$('.pax_parent_id').selectpicker('destroy');
								$('.pax_parent_id').empty();
								$('.pax_parent_id').append('<option value="">-- Select --</option>');
								$.each(data, function(key, value) {
									if(value.pax_name){
										var allergensName = value.pax_name;
									}else{
										var allergensName = value.name;
									}
									if (value.id == selectedGroup) {
										$('.pax_parent_id').append('<option value="'+value.id+'" data-pax="'+value.pax_name+'" selected="selected">'+allergensName+'</option>');
									} else {
										$('.pax_parent_id').append('<option value="'+value.id+'" data-pax="'+value.pax_name+'">'+allergensName+'</option>');
									}
								});
								$('.pax_parent_id').addClass('selectpicker').selectpicker('refresh');
							}
						});
					} else{
						//$("#pax_parent_id").removeAttr("required");
						//$("#parent_id").attr("required","required");
						$(".pax_parentID").hide();
						$(".paxName").hide();
						$(".paxLName").hide();
						$("#raptor_block").hide();
						$('.pax_parent_id').selectpicker('destroy');
						$('.pax_parent_id').empty();
					}
					$.ajax({
						url:      "<?php echo base_url('Allergens/get_allergens_dropdown'); ?>",
						type:     'POST',
						data:     {'order_type':filtered_order_type},
						dataType: "json",
						success:  function (data) {
							$('#cover-spin').hide();
							$('.parent_id').selectpicker('destroy');
							$('.parent_id').empty();
							$('.parent_id').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								if (value.id == selectedGroup) {
									$('.parent_id').append('<option value="'+value.id+'" selected="selected">'+value.name+'</option>');
								} else {
									$('.parent_id').append('<option value="'+value.id+'">'+value.name+'</option>');
								}
							});
							$('.parent_id').addClass('selectpicker').selectpicker('refresh');
						}
					});
				}else{
					$('.parent_id').selectpicker('destroy');
					$('.parent_id').empty();
					$('.pax_parent_id').selectpicker('destroy');
					$('.pax_parent_id').empty();
				}
			});

			$('input[name="is_mixtures"]').on('click', function() {
				if($(this).is(':checked')){
					var selectedGroup = $("select#parent_id :selected").val();
					if(selectedGroup){
						$.ajax({
							url:      "<?php echo base_url('Allergens/get_sub_allergens_dropdown'); ?>",
							type:     'POST',
							data:     {'parent_id':selectedGroup},
							dataType: "json",
							success:  function (data) {
								$("#raptor_block").hide();
								$("#mixture_block").show();
								$('.allergens_id').selectpicker('destroy');
								$('.allergens_id').empty();
								$('.allergens_id').append('<option value="">-- Select --</option>');
								$.each(data, function(key, value) {
									$('.allergens_id').append('<option value="'+value.id+'">'+value.name+'</option>');
								});
								$('.allergens_id').addClass('selectpicker').selectpicker('refresh');
							}
						});
					}
				}else{
					$("#mixture_block").hide();
					$("#raptor_block").show();
				}
			});

			$('input[name="available_as_mixtures"]').on('click', function() {
				if($(this).is(':checked')){
					$(".mixture_orderType").show();
				}else{
					$(".mixture_orderType").hide();
				}
			});

			$(document).on('click','#filterBtn',function(){
				$("#addAllergens").empty();
				$('select#allergens_id :selected').each(function(){
					var content = '<tr id="row_'+ $(this).val() +'"><td><input type="hidden" name="mixture_allergens[]" value="'+ $(this).val() +'">'+ $(this).text() +'</td><td><a onclick="removeMCode('+ $(this).val() +')" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr>';
					$("#addAllergens").append(content);
				});
			});

			$(document).on('click','#btnAddProduct',function(){
				var rowCount = $('#addedRcodes').val();
				var rowlent = parseFloat(rowCount)+parseFloat(1);
				var totalRecord = $("#addedRcodes").val();
				totalRecord = parseFloat(totalRecord)+parseFloat(1);
				var selected1 = 0; var selected2 = 0;
				for(var i = 0; i < totalRecord; i++){
					if($('select.em_allergen_'+i+' option:selected').val()  == 2){
						selected1 += parseFloat(1);
					}
					if($('select.em_allergen_'+i+' option:selected').val()  == 1){
						selected2 = parseFloat(1);
					}
				}
				if(selected1 == 1 && selected2 == 0){
					var content = '<tr id="row_'+ rowlent +'"><td colspan="4"><table class="mrgbtm" style="width:100%;"><thead><tr><td style="width:20%;"><select class="form-control emAllergen em_allergen_'+ rowlent +'" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="1">Allergen Description</option></select></td><td style="width:30%;"><input type="text" class="form-control raptorCode raptor_code_'+ rowlent +'" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td><td style="width:40%;"><input type="text" class="form-control raptorFunction raptor_function_'+ rowlent +'" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td><td style="width:10%;text-align: center;"><a class="removeRbtn" onclick="removeRCode('+ rowlent +')" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td></tr></thead><tbody id="addedrow_'+ rowlent +'"><tr class="row_'+ rowlent +'_0"><td colspan="3"><input type="text" class="form-control mrgbtm " name="raptor_header['+ rowlent +'][1][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow('+ rowlent +',0)" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr></tbody><tfoot><tr><td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="'+ rowlent +'" class="pull-right btn btn-primary btnAddMore"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_'+ rowlent +'" value="0"></td></tr></tfoot></table></td></tr>';
				}else if(selected1 == 0 && selected2 == 1){
					var content = '<tr id="row_'+ rowlent +'"><td colspan="4"><table class="mrgbtm" style="width:100%;"><thead><tr><td style="width:20%;"><select class="form-control emAllergen em_allergen_'+ rowlent +'" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="2">Allergen Extract</option></select></td><td style="width:30%;"><input type="text" class="form-control raptorCode raptor_code_'+ rowlent +'" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td><td style="width:40%;"><input type="text" class="form-control raptorFunction raptor_function_'+ rowlent +'" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td><td style="width:10%;text-align: center;"><a class="removeRbtn" onclick="removeRCode('+ rowlent +')" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td></tr></thead><tbody id="addedrow_'+ rowlent +'"><tr class="row_'+ rowlent +'_0"><td colspan="3"><input type="text" class="form-control mrgbtm " name="raptor_header['+ rowlent +'][1][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow('+ rowlent +',0)" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr></tbody><tfoot><tr><td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="'+ rowlent +'" class="pull-right btn btn-primary btnAddMore"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_'+ rowlent +'" value="0"></td></tr></tfoot></table></td></tr>';
				}else if(selected1 == 1 && selected2 == 1){
					var content = '<tr id="row_'+ rowlent +'"><td colspan="4"><table class="mrgbtm" style="width:100%;"><thead><tr><td style="width:20%;"><select class="form-control emAllergen em_allergen_'+ rowlent +'" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option></select></td><td style="width:30%;"><input type="text" class="form-control raptorCode raptor_code_'+ rowlent +'" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td><td style="width:40%;"><input type="text" class="form-control raptorFunction raptor_function_'+ rowlent +'" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td><td style="width:10%;text-align: center;"><a class="removeRbtn" onclick="removeRCode('+ rowlent +')" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td></tr></thead><tbody id="addedrow_'+ rowlent +'"><tr class="row_'+ rowlent +'_0"><td colspan="3"><input type="text" class="form-control mrgbtm " name="raptor_header['+ rowlent +'][1][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow('+ rowlent +',0)" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr></tbody><tfoot><tr><td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="'+ rowlent +'" class="pull-right btn btn-primary btnAddMore"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_'+ rowlent +'" value="0"></td></tr></tfoot></table></td></tr>';
				}else{
					var content = '<tr id="row_'+ rowlent +'"><td colspan="4"><table class="mrgbtm" style="width:100%;"><thead><tr><td style="width:20%;"><select class="form-control emAllergen em_allergen_'+ rowlent +'" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="2">Allergen Extract</option><option value="1">Allergen Description</option></select></td><td style="width:30%;"><input type="text" class="form-control raptorCode raptor_code_'+ rowlent +'" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td><td style="width:40%;"><input type="text" class="form-control raptorFunction raptor_function_'+ rowlent +'" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td><td style="width:10%;text-align: center;"><a class="removeRbtn" onclick="removeRCode('+ rowlent +')" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td></tr></thead><tbody id="addedrow_'+ rowlent +'"><tr class="row_'+ rowlent +'_0"><td colspan="3"><input type="text" class="form-control mrgbtm " name="raptor_header['+ rowlent +'][1][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow('+ rowlent +',0)" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr></tbody><tfoot><tr><td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="'+ rowlent +'" class="pull-right btn btn-primary btnAddMore"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_'+ rowlent +'" value="0"></td></tr></tfoot></table></td></tr>';
				}
				$("#addedProduct").append(content);
				$('#addedRcodes').val(rowlent);
			});

			$(document).on('click','#btnAddMore',function(){
				var id = $(this).data('row');
				var rowCounts = $('#addedRows_'+id).val();
				var rowlents = parseFloat(rowCounts)+parseFloat(1);
				var content = '<tr class="row_'+ id +'_'+ rowlents +'"><td colspan="3" style="width:90%"><input type="text" class="form-control mrgbtm " name="raptor_header['+ id +'][1][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a class="removebtn" onclick="removeRow('+ id +','+ rowlents +')" title="Remove Interpretation" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr>';
				$("tbody#addedrow_"+ id).append(content);
				$('#addedRows_'+id).val(rowlents);
			});

			$(document).on('change','select[id="language_id"]',function(){
				var selectedType=[];
				$('select[name="order_type[]"] :selected').each(function(){
					selectedType.push($(this).val());
				});
				var languageName = $(this).val();
				if(languageName != ''){
					if(languageName != 'english'){
						$('select[name="order_type[]"]').attr('style','pointer-events: none;cursor: not-allowed;');
						$('button[class="btn dropdown-toggle btn-default"]').attr('style','pointer-events: none;cursor: not-allowed;');
						$('#parent_id').attr('style','pointer-events: none;cursor: not-allowed;');
						$('#pax_parent_id').attr('style','pointer-events: none;cursor: not-allowed;');
						$('div[class="checkbox"]').attr('style','pointer-events: none;cursor: not-allowed;');
						$('select[name="mixture_order_type[]"]').attr('style','pointer-events: none;cursor: not-allowed;');
						$('button[class="btn dropdown-toggle btn-default bs-placeholder"]').attr('style','pointer-events: none;cursor: not-allowed;');
						$('input[name="pax_latin_name"]').attr('readonly','readonly');
						$('input[id="exactCode"]').attr('readonly','readonly');
						$('input[id="can_allgy_env"]').attr('readonly','readonly');
						$('input[id="fel_allgy_env"]').attr('readonly','readonly');
						$('input[id="equ_allgy_env"]').attr('readonly','readonly');
						$('input[id="can_allgy_food_ige"]').attr('readonly','readonly');
						$('input[id="can_allgy_food_igg"]').attr('readonly','readonly');
						$('input[id="fel_allgy_food_ige"]').attr('readonly','readonly');
						$('input[id="fel_allgy_food_igg"]').attr('readonly','readonly');
						$('input[id="equ_allgy_food_ige"]').attr('readonly','readonly');
						$('input[id="equ_allgy_food_igg"]').attr('readonly','readonly');
						$('select[id="allergens_id"]').attr('style','pointer-events: none;cursor: not-allowed;');
						$('#addAllergens').attr('style','pointer-events: none;cursor: not-allowed;');
						$('.btnAddMore').attr('style','display:none');
						$('#btnAddProduct').attr('style','display:none');
						$('.removeRbtn').attr('style','display:none');
						$('.removebtn').attr('style','display:none');
						$('select.emAllergen').attr('style','pointer-events: none;cursor: not-allowed;');
						$('.raptorCode').attr('readonly','readonly');
						$('.raptorFunction').attr('readonly','readonly');
					}else{
						$('select[name="order_type[]"]').removeAttr('style');
						$('button[class="btn dropdown-toggle btn-default"]').removeAttr('style');
						$('#parent_id').removeAttr('style');
						$('#pax_parent_id').removeAttr('style');
						$('div[class="checkbox"]').removeAttr('style');
						$('select[name="mixture_order_type[]"]').removeAttr('style');
						$('button[class="btn dropdown-toggle btn-default bs-placeholder"]').removeAttr('style');
						$('input[name="pax_latin_name"]').removeAttr('readonly');
						$('input[id="exactCode"]').removeAttr('readonly');
						$('input[id="can_allgy_env"]').removeAttr('readonly');
						$('input[id="fel_allgy_env"]').removeAttr('readonly');
						$('input[id="equ_allgy_env"]').removeAttr('readonly');
						$('input[id="can_allgy_food_ige"]').removeAttr('readonly');
						$('input[id="can_allgy_food_igg"]').removeAttr('readonly');
						$('input[id="fel_allgy_food_ige"]').removeAttr('readonly');
						$('input[id="fel_allgy_food_igg"]').removeAttr('readonly');
						$('input[id="equ_allgy_food_ige"]').removeAttr('readonly');
						$('input[id="equ_allgy_food_igg"]').removeAttr('readonly');
						$('select[id="allergens_id"]').removeAttr('style');
						$('#addAllergens').removeAttr('style');
						$('.btnAddMore').removeAttr('style');
						$('#btnAddProduct').removeAttr('style');
						$('.removeRbtn').removeAttr('style');
						$('.removebtn').removeAttr('style');
						$('select.emAllergen').removeAttr('style');
						$('.raptorCode').removeAttr('readonly');
						$('.raptorFunction').removeAttr('readonly');
					}
					$(".aName").hide();
					$("#"+languageName+"Name").show();
					if((jQuery.inArray("8", selectedType) != -1) || (jQuery.inArray("9", selectedType) != -1) || (jQuery.inArray("11", selectedType) != -1) || (jQuery.inArray("12", selectedType) != -1)) {
						$(".apName").hide();
						$("#"+languageName+"PAXName").show();
						$(".raptorHeader").hide();
						$(".raptor_header_"+languageName+"").show();
					}
				}else{
					$(".aName").hide();
					$("#englishName").show();
					if((jQuery.inArray("8", selectedType) != -1) || (jQuery.inArray("9", selectedType) != -1) || (jQuery.inArray("11", selectedType) != -1) || (jQuery.inArray("12", selectedType) != -1)) {
						$(".apName").hide();
						$("#englishPAXName").show();
						$(".raptorHeader").hide();
						$(".raptor_header_english").show();
					}
					$('select[name="order_type[]"]').removeAttr('style');
					$('button[class="btn dropdown-toggle btn-default"]').removeAttr('style');
					$('#parent_id').removeAttr('style');
					$('#pax_parent_id').removeAttr('style');
					$('div[class="checkbox"]').removeAttr('style');
					$('select[name="mixture_order_type[]"]').removeAttr('style');
					$('button[class="btn dropdown-toggle btn-default bs-placeholder"]').removeAttr('style');
					$('input[name="pax_latin_name"]').removeAttr('readonly');
					$('input[id="exactCode"]').removeAttr('readonly');
					$('input[id="can_allgy_env"]').removeAttr('readonly');
					$('input[id="fel_allgy_env"]').removeAttr('readonly');
					$('input[id="equ_allgy_env"]').removeAttr('readonly');
					$('input[id="can_allgy_food_ige"]').removeAttr('readonly');
					$('input[id="can_allgy_food_igg"]').removeAttr('readonly');
					$('input[id="fel_allgy_food_ige"]').removeAttr('readonly');
					$('input[id="fel_allgy_food_igg"]').removeAttr('readonly');
					$('input[id="equ_allgy_food_ige"]').removeAttr('readonly');
					$('input[id="equ_allgy_food_igg"]').removeAttr('readonly');
					$('select[id="allergens_id"]').removeAttr('style');
					$('#addAllergens').removeAttr('style');
					$('.btnAddMore').removeAttr('style');
					$('#btnAddProduct').removeAttr('style');
					$('.removeRbtn').removeAttr('style');
					$('.removebtn').removeAttr('style');
					$('select.emAllergen').removeAttr('style');
					$('.raptorCode').removeAttr('readonly');
					$('.raptorFunction').removeAttr('readonly');
				}
			});
		});

		function checkEMAllergen(){
			var totalRecord = $("#addedRcodes").val();
			totalRecord = parseFloat(totalRecord)+parseFloat(1);
			var selected1 = 0; var selected2 = 0;
			for(var i = 0; i < totalRecord; i++){
				if($('select.em_allergen_'+i+' option:selected').val()  == 2){
					selected1 += parseFloat(1);
				}
				if($('select.em_allergen_'+i+' option:selected').val()  == 1){
					selected2 = parseFloat(1);
				}
			}

			if(selected1 == 1){
				for(var x = 0; x < totalRecord; x++){
					if($('select.em_allergen_'+x+' option:selected').val() != 2){
						$('select.em_allergen_'+x+' option[value="2"]').remove();
					}
				}
			}else{
				for(var x = 0; x < totalRecord; x++){
					if($('select.em_allergen_'+x+' option[value="2"]').length == 0){
						$('select.em_allergen_'+x+'').append('<option value="2">Allergen Extract</option>');
					}
				}
			}

			if(selected2 == 1){
				for(var y = 0; y < totalRecord; y++){
					if($('select.em_allergen_'+y+' option:selected').val() != 1){
						$('select.em_allergen_'+y+' option[value="1"]').remove();
					}
				}
			}else{
				for(var y = 0; y < totalRecord; y++){
					if($('select.em_allergen_'+y+' option[value="1"]').length == 0){
						$('select.em_allergen_'+y+'').append('<option value="1">Allergen Description</option>');
					}
				}
			}
		}

		function removeMCode(id){
			if(confirm("Are you sure you want to remove this Allergen?")){
				$("#allergens_id option[value='"+id+"']").removeAttr("selected");
				$("#allergens_id").trigger("change");
				$("#row_"+id).remove();
			} else {
				return false;
			}
		}

		function removeRCode(id){
			if(confirm("Are you sure you want to delete this Code?")){
				$("#row_"+id).remove();
			} else {
				return false;
			}
		}

		function removeRow(id,rid){
			if(confirm("Are you sure you want to delete this Row?")){
				$(".row_"+id+"_"+rid).remove();
			} else {
				return false;
			}
		}
		</script>
	</body>
</html>