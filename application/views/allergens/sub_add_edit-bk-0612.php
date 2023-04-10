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
								<div class="box-header with-border"><h3 class="box-title"><?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3></div><!-- /.box-header -->
								<!-- form start -->
								<?php echo form_open('', array('name'=>'sub_allergenForm', 'id'=>'sub_allergenForm')); ?>
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
												<div class="form-group">
													<label>Allergen Name</label>
													<input type="text" class="form-control" name="name" placeholder="Enter Allergen Name" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
													<?php echo form_error('name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group paxName" <?php echo $paxName; ?>>
													<label>PAX Name</label>
													<input type="text" class="form-control" name="pax_name" placeholder="Enter PAX Name" value="<?php echo set_value('pax_name',isset($data['pax_name']) ? $data['pax_name'] : '');?>">
													<?php echo form_error('pax_name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group paxLName" <?php echo $paxLName; ?>>
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
																							<td style="width:20%;"><select class="form-control em_allergen_0" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="2">Allergen Extract</option><option value="1">Allergen Description</option></select></td>
																							<td style="width:30%;"><input type="text" class="form-control raptor_code_0" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td>
																							<td style="width:40%;"><input type="text" class="form-control raptor_function_0" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td>
																							<td style="width:10%;text-align: center;"><a onclick="removeRCode(0)" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td>
																						</tr>
																					</thead>
																					<tbody id="addedrow_0">
																						<tr class="row_0_0">
																							<td colspan="3"><input type="text" class="form-control mrgbtm" name="raptor_header[0][]" placeholder="Allergen/Extract/Description"></td>
																							<td style="text-align: center;width:10%"><a onclick="removeRow(0,0)" style="color:#e30613" title="Remove Interpretation"><i class="fa fa-times-circle"></i></a></td>
																						</tr>
																					</tbody>
																					<tfoot>
																						<tr>
																							<td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="0" class="pull-right btn btn-primary"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_0" value="0"></td>
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
																									<select class="form-control em_allergen_<?php echo $x; ?>" name="em_allergen[<?php echo $x; ?>]">
																										<option value="">--Select E/M Allergen--</option>
																										<option value="3" <?php if($row->em_allergen == 3){ echo 'selected="selected"'; } ?>>Molecular Allergen</option>
																										<option value="2" <?php if($row->em_allergen == 2){ echo 'selected="selected"'; } ?>>Allergen Extract</option>
																										<option value="1" <?php if($row->em_allergen == 1){ echo 'selected="selected"'; } ?>>Allergen Description</option>
																									</select>
																								</td>
																								<td style="width:30%;"><input <?php if($row->em_allergen == 1){ echo 'type="hidden"'; }else{ echo 'type="text"'; } ?> class="form-control raptor_code_<?php echo $x; ?>" name="raptor_code[<?php echo $x; ?>]" placeholder="Enter Raptor Code" value="<?php echo $row->raptor_code;?>"></td>
																								<td style="width:40%;"><input <?php if($row->em_allergen == 1){ echo 'type="hidden"'; }else{ echo 'type="text"'; } ?> class="form-control raptor_function_<?php echo $x; ?>" name="raptor_function[<?php echo $x; ?>]" placeholder="Enter Raptor Function" value="<?php echo $row->raptor_function;?>"></td>
																								<td style="width:10%;text-align: center;"><a onclick="removeRCode(<?php echo $x; ?>)" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td>
																							</tr>
																						</thead>
																						<tbody id="addedrow_<?php echo $x; ?>">
																							<?php
																							$detailsArr = array(); $y=0; 
																							if($row->raptor_header != "" && $row->raptor_header != '[""]'){
																								$detailsArr = json_decode($row->raptor_header);
																							}
																							if(!empty($detailsArr)){
																								foreach($detailsArr as $key=>$rowd){
																								?>
																								<tr class="row_<?php echo $x; ?>_<?php echo $key; ?>">
																									<td colspan="3"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][]" value='<?php echo $rowd;?>'></td>
																									<td style="text-align: center;width:10%"><a onclick="removeRow(<?php echo $x; ?>,<?php echo $key; ?>)" style="color:#e30613" title="Remove Interpretation"><i class="fa fa-times-circle"></i></a></td>
																								</tr>
																								<?php 
																									$y++;
																								}
																							}else{ ?>
																								<tr class="row_<?php echo $x; ?>_0">
																									<td colspan="3"><input type="text" class="form-control mrgbtm" name="raptor_header[<?php echo $x; ?>][]" placeholder="Allergen/Extract/Description"></td>
																									<td style="text-align: center;width:10%"><a onclick="removeRow(<?php echo $x; ?>,0)" style="color:#e30613" title="Remove Interpretation"><i class="fa fa-times-circle"></i></a></td>
																								</tr>
																							<?php } ?>
																						</tbody>
																						<tfoot>
																							<tr>
																								<td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="<?php echo $x; ?>" class="pull-right btn btn-primary"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_<?php echo $x; ?>" value="<?php echo $y; ?>"></td>
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
																						<td style="width:20%;"><select class="form-control em_allergen_0" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="2">Allergen Extract</option><option value="1">Allergen Description</option></select></td>
																						<td style="width:30%;"><input type="text" class="form-control raptor_code_0" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td>
																						<td style="width:40%;"><input type="text" class="form-control raptor_function_0" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td>
																						<td style="width:10%;text-align: center;"><a onclick="removeRCode(0)" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td>
																					</tr>
																				</thead>
																				<tbody id="addedrow_0">
																					<tr class="row_0_0">
																						<td colspan="3"><input type="text" class="form-control mrgbtm" name="raptor_header[0][]" placeholder="Allergen/Extract/Description"></td>
																						<td style="text-align: center;width:10%"><a onclick="removeRow(0,0)" style="color:#e30613" title="Remove Interpretation"><i class="fa fa-times-circle"></i></a></td>
																					</tr>
																				</tbody>
																				<tfoot>
																					<tr>
																						<td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="0" class="pull-right btn btn-primary"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_0" value="0"></td>
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
					var content = '<tr id="row_'+ rowlent +'"><td colspan="4"><table class="mrgbtm" style="width:100%;"><thead><tr><td style="width:20%;"><select class="form-control em_allergen_'+ rowlent +'" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="1">Allergen Description</option></select></td><td style="width:30%;"><input type="text" class="form-control raptor_code_'+ rowlent +'" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td><td style="width:40%;"><input type="text" class="form-control raptor_function_'+ rowlent +'" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td><td style="width:10%;text-align: center;"><a onclick="removeRCode('+ rowlent +')" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td></tr></thead><tbody id="addedrow_'+ rowlent +'"><tr class="row_'+ rowlent +'_0"><td colspan="3"><input type="text" class="form-control mrgbtm" name="raptor_header['+ rowlent +'][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a onclick="removeRow('+ rowlent +',0)" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr></tbody><tfoot><tr><td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="'+ rowlent +'" class="pull-right btn btn-primary"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_'+ rowlent +'" value="0"></td></tr></tfoot></table></td></tr>';
				}else if(selected1 == 0 && selected2 == 1){
					var content = '<tr id="row_'+ rowlent +'"><td colspan="4"><table class="mrgbtm" style="width:100%;"><thead><tr><td style="width:20%;"><select class="form-control em_allergen_'+ rowlent +'" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="2">Allergen Extract</option></select></td><td style="width:30%;"><input type="text" class="form-control raptor_code_'+ rowlent +'" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td><td style="width:40%;"><input type="text" class="form-control raptor_function_'+ rowlent +'" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td><td style="width:10%;text-align: center;"><a onclick="removeRCode('+ rowlent +')" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td></tr></thead><tbody id="addedrow_'+ rowlent +'"><tr class="row_'+ rowlent +'_0"><td colspan="3"><input type="text" class="form-control mrgbtm" name="raptor_header['+ rowlent +'][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a onclick="removeRow('+ rowlent +',0)" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr></tbody><tfoot><tr><td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="'+ rowlent +'" class="pull-right btn btn-primary"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_'+ rowlent +'" value="0"></td></tr></tfoot></table></td></tr>';
				}else if(selected1 == 1 && selected2 == 1){
					var content = '<tr id="row_'+ rowlent +'"><td colspan="4"><table class="mrgbtm" style="width:100%;"><thead><tr><td style="width:20%;"><select class="form-control em_allergen_'+ rowlent +'" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option></select></td><td style="width:30%;"><input type="text" class="form-control raptor_code_'+ rowlent +'" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td><td style="width:40%;"><input type="text" class="form-control raptor_function_'+ rowlent +'" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td><td style="width:10%;text-align: center;"><a onclick="removeRCode('+ rowlent +')" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td></tr></thead><tbody id="addedrow_'+ rowlent +'"><tr class="row_'+ rowlent +'_0"><td colspan="3"><input type="text" class="form-control mrgbtm" name="raptor_header['+ rowlent +'][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a onclick="removeRow('+ rowlent +',0)" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr></tbody><tfoot><tr><td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="'+ rowlent +'" class="pull-right btn btn-primary"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_'+ rowlent +'" value="0"></td></tr></tfoot></table></td></tr>';
				}else{
					var content = '<tr id="row_'+ rowlent +'"><td colspan="4"><table class="mrgbtm" style="width:100%;"><thead><tr><td style="width:20%;"><select class="form-control em_allergen_'+ rowlent +'" name="em_allergen[]"><option value="">--Select E/M Allergen--</option><option value="3">Molecular Allergen</option><option value="2">Allergen Extract</option><option value="1">Allergen Description</option></select></td><td style="width:30%;"><input type="text" class="form-control raptor_code_'+ rowlent +'" name="raptor_code[]" placeholder="Enter Raptor Code" value=""></td><td style="width:40%;"><input type="text" class="form-control raptor_function_'+ rowlent +'" name="raptor_function[]" placeholder="Enter Raptor Function" value=""></td><td style="width:10%;text-align: center;"><a onclick="removeRCode('+ rowlent +')" title="Remove Code" style="color:#e30613;font-size: 20px;"><i class="fa fa-times-circle"></i></a></td></tr></thead><tbody id="addedrow_'+ rowlent +'"><tr class="row_'+ rowlent +'_0"><td colspan="3"><input type="text" class="form-control mrgbtm" name="raptor_header['+ rowlent +'][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a onclick="removeRow('+ rowlent +',0)" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr></tbody><tfoot><tr><td colspan="4"><a href="javascript:void(0)" id="btnAddMore" data-row="'+ rowlent +'" class="pull-right btn btn-primary"> Add Interpretation <i class="fa fa-plus"></i></a><input type="hidden" id="addedRows_'+ rowlent +'" value="0"></td></tr></tfoot></table></td></tr>';
				}
				$("#addedProduct").append(content);
				$('#addedRcodes').val(rowlent);
			});

			$(document).on('click','#btnAddMore',function(){
				var id = $(this).data('row');
				var rowCounts = $('#addedRows_'+id).val();
				var rowlents = parseFloat(rowCounts)+parseFloat(1);
				var content = '<tr class="row_'+ id +'_'+ rowlents +'"><td colspan="3" style="width:90%"><input type="text" class="form-control mrgbtm" name="raptor_header['+ id +'][]" placeholder="Allergen/Extract/Description"></td><td style="text-align: center;width:10%"><a onclick="removeRow('+ id +','+ rowlents +')" title="Remove Interpretation" style="color:#e30613"><i class="fa fa-times-circle"></i></a></td></tr>';
				$("tbody#addedrow_"+ id).append(content);
				$('#addedRows_'+id).val(rowlents);
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