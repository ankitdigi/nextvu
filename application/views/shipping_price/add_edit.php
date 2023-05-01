<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet"/>
			<style>
			.foo { color: #797676; text-size: smaller; }
			.select2-container .select2-selection--single{height: 35px;}
			.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove:hover{color:#fff;}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Shipping Price Management
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Shipping Price Management</li>
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

					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- product price form elements -->
							<div class="box box-primary">
								<div class="box-header with-border"><h3 class="box-title"><?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3></div><!-- /.box-header -->

								<!-- form start -->
								<?php echo form_open('', array('name'=>'shipping_priceForm', 'id'=>'shipping_priceForm')); ?>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label>Order Type</label>
													<select class="form-control product_type" name="product_type" id="product_type" required="">
														<option value="">--Select--</option>
														<option value="1" <?= isset($data['product_type']) ? ($data['product_type'] == 1 ? 'selected="selected"' : '') : ''; ?> >Immunotherapy</option>
														<option value="2" <?= isset($data['product_type']) ? ($data['product_type'] == 2 ? 'selected="selected"' : '') : ''; ?> >Serum test</option>
														<option value="3" <?= isset($data['product_type']) ? ($data['product_type'] == 3 ? 'selected="selected"' : '') : ''; ?> >Skin Test</option>
													</select>
													<?php echo form_error('product_type', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Sub Type</label>
													<select class="form-control parent_id" name="parent_id" id="parent_id" required="">
														<option value="">--Select--</option>
														<?php foreach($shipping_prices as $category){ ?>
															<option value="<?php echo $category['id']; ?>"<?php if(isset($id) && $id>0 && ($category['id']==$data['parent_id'])) echo 'selected="selected"'; ?>><?php echo $category['name']; ?></option>
														<?php }?>
													</select>
													<?php echo form_error('parent_id', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Product Code Selection</label>
													<input type="text" class="form-control" name="child_ids" placeholder="Enter Product Code Selection (e.g.: 1,2,3,4,5)" value="<?php echo set_value('child_ids',isset($data['child_ids']) ? $data['child_ids'] : '');?>" required="">
													<?php echo form_error('parent_id', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Name</label>
													<input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
													<?php echo form_error('name', '<div class="error">', '</div>'); ?>
												</div>

											</div>
											<!-- /.col -->
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="col-sm-12 col-md-12 col-lg-12"><h3 style="margin: 0px;color: #2f5c79;border-bottom: 1px solid;margin-bottom: 5px;">Global and NL Prices</h3></div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>UK Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="uk_currency" id="uk_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['uk_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['uk_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['uk_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="uk_price" placeholder="Enter UK Price" value="<?php echo set_value('uk_price',isset($data['uk_price']) ? $data['uk_price'] : '');?>">
														<?php echo form_error('uk_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Ireland Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="roi_currency" id="roi_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['roi_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['roi_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['roi_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="roi_price" placeholder="Enter Ireland Price" value="<?php echo set_value('roi_price',isset($data['roi_price']) ? $data['roi_price'] : '');?>">
														<?php echo form_error('roi_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Denmark Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="dk_currency" id="dk_currency" style="padding:0px;height: 32px;">
																<option value="DKK" <?php if(isset($id) && $id>0 && ($data['se_price']== 'DKK')){ echo 'selected="selected"'; } ?>>DKK</option>
																<option value="€" <?php if(isset($id) && $id>0 && ($data['dk_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['dk_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['dk_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="dk_price" placeholder="Enter Denmark Price" value="<?php echo set_value('dk_price',isset($data['dk_price']) ? $data['dk_price'] : '');?>">
														<?php echo form_error('dk_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>France Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="fr_currency" id="fr_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['fr_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['fr_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['fr_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="fr_price" placeholder="Enter France Price" value="<?php echo set_value('fr_price',isset($data['fr_price']) ? $data['fr_price'] : '');?>">
														<?php echo form_error('fr_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Germany Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="de_currency" id="de_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['de_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['de_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['de_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="de_price" placeholder="Enter Germany Price" value="<?php echo set_value('de_price',isset($data['de_price']) ? $data['de_price'] : '');?>">
														<?php echo form_error('de_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Italy Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="it_currency" id="it_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['it_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['it_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['it_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="it_price" placeholder="Enter Italy Price" value="<?php echo set_value('it_price',isset($data['it_price']) ? $data['it_price'] : '');?>">
														<?php echo form_error('it_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Netherland Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="nl_currency" id="nl_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['nl_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['nl_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['nl_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="nl_price" placeholder="Enter Netherland Price" value="<?php echo set_value('nl_price',isset($data['nl_price']) ? $data['nl_price'] : '');?>">
														<?php echo form_error('nl_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Norway Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="no_currency" id="no_currency" style="padding:0px;height: 32px;">
																<option value="NOK" <?php if(isset($id) && $id>0 && ($data['se_price']== 'NOK')){ echo 'selected="selected"'; } ?>>NOK</option>
																<option value="€" <?php if(isset($id) && $id>0 && ($data['no_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['no_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['no_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="no_price" placeholder="Enter Norway Price" value="<?php echo set_value('no_price',isset($data['no_price']) ? $data['no_price'] : '');?>">
														<?php echo form_error('no_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Sweden Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="se_currency" id="se_price" style="padding:0px;height: 32px;">
																<option value="SEK" <?php if(isset($id) && $id>0 && ($data['se_currency']== 'SEK')){ echo 'selected="selected"'; } ?>>SEK</option>
																<option value="€" <?php if(isset($id) && $id>0 && ($data['se_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['se_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['se_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="se_price" placeholder="Enter Sweden Price" value="<?php echo set_value('se_price',isset($data['se_price']) ? $data['se_price'] : '');?>">
														<?php echo form_error('se_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>NL Default Export Price (Ex shipping):</label>
													<div class="input-group">
														<div class="input-group-addon">
															<b>(€)</b>
														</div>
														<input type="text" class="form-control" name="" placeholder="Enter Default Export Price" value="<?php echo set_value('default_price',isset($data['default_price']) ? $data['default_price'] : '');?>" required="">
														<?php echo form_error('default_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-12 col-md-12 col-lg-12"><h3 style="margin: 0px;color: #2f5c79;border-bottom: 1px solid;margin-bottom: 5px;">ES Prices</h3></div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Spain Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="es_currency" id="es_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['es_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['es_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['es_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="es_price" placeholder="Enter Spain Price" value="<?php echo set_value('es_price',isset($data['es_price']) ? $data['es_price'] : '');?>">
														<?php echo form_error('es_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Poland Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="pl_currency" id="pl_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['pl_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['pl_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['pl_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="pl_price" placeholder="Enter Poland Price" value="<?php echo set_value('pl_price',isset($data['pl_price']) ? $data['pl_price'] : '');?>">
														<?php echo form_error('pl_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Czech Republic Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="cz_currency" id="cz_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['cz_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['cz_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['cz_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="cz_price" placeholder="Enter Czech Republic Price" value="<?php echo set_value('cz_price',isset($data['cz_price']) ? $data['cz_price'] : '');?>">
														<?php echo form_error('cz_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>

												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Portugal Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="pt_currency" id="pt_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['pt_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['pt_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['pt_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="pt_price" placeholder="Enter Portugal Price" value="<?php echo set_value('pt_price',isset($data['pt_price']) ? $data['pt_price'] : '');?>">
														<?php echo form_error('pt_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Slovenia Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="si_currency" id="si_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['si_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['si_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['si_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="si_price" placeholder="Enter Slovenia Price" value="<?php echo set_value('si_price',isset($data['si_price']) ? $data['si_price'] : '');?>">
														<?php echo form_error('si_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group col-sm-4 col-md-4 col-lg-4">
													<label>Rest of Europe Price:</label>
													<div class="input-group">
														<div class="input-group-addon" style="padding:0px;width:90px;">
															<select class="form-control" name="default_es_currency" id="default_es_currency" style="padding:0px;height: 32px;">
																<option value="€" <?php if(isset($id) && $id>0 && ($data['default_es_currency']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['default_es_currency']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['default_es_currency']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
															</select>
														</div>
														<input type="text" class="form-control" name="default_es_price" placeholder="Enter Rest of Europe Price" value="<?php echo set_value('default_es_price',isset($data['default_es_price']) ? $data['default_es_price'] : '');?>">
														<?php echo form_error('default_es_price', '<div class="error">', '</div>'); ?>
													</div>
												</div>
											</div>
										</div>
										<!-- /.row -->
									</div>
									<!-- /.box-body -->
									<div class="box-footer">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								<?php echo form_close(); ?>
								<!-- form end -->
							</div><!-- /.box -->
							<!-- product price form elements -->

							<!-- discount form elements -->
							<?php if($id>0){ ?>
								<div class="box box-primary">
									<div class="box-header with-border">
										<h3 class="box-title">Shipping price of Practices/Labs</h3>
									</div><!-- /.box-header -->
									<div class="box-body">
										<!--filter form-->
										<form class="row" style="margin-bottom: 30px;" id="filterForm" method="POST" action="">
											<div class="col-sm-3">
												<div class="form-group">
													<label>Practices/Labs</label>
													<?php 
													$options = array();
													if(!empty($vatLabUsers)){
														foreach ($vatLabUsers as $user) {
															$user_id = $user['id'];
															$post_code = ($user['postcode']) ? ' - '.$user['postcode'] : '';
															$options[$user_id] = $user['name'].$post_code;
														}
													}
													$attr = 'class="form-control vet_user_id selectpicker" data-live-search="true" multiple=""';
													echo form_dropdown('vet_user_id[]',$options,set_value('vet_user_id',isset($sel_practice_id) ? explode(",",$sel_practice_id['practice_id']) : ''),$attr);
													?>
												</div>
											</div>
											<div class="col-sm-12">
												<a href="javascript:void(0);" class="btn btn-success btn-sm" id="filterBtn">Add</a>
											</div>
										</form>
										<!--filter form-->

										<!--Discount Form-->
										<form id="discountForm" name="discountForm" method="POST" action="" class="discount_class">
											<table id="discount" class="table table-bordered table-striped">
												<thead>
													<tr>
														<th>Practice Name</th>
														<th>Price(£)</th>
														<th>Price(€)</th>
														<th>Action</th>
													</tr>
												</thead>
											</table>
											<div class="box-footer discount_class">
												<button type="submit" class="btn btn-primary">Save</button>
											</div>
										</form>
										<!--Discount Form-->
									</div>
									<!-- /.box-body -->
									<?php echo form_close(); ?>
									<!-- form end -->
								</div><!-- /.box -->
							<?php } ?>
							<!-- discount form elements -->
						</div><!--/.col (left) -->
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
		<script>
		$(document).ready(function(){
			$('#sub_categoryForm').parsley();
			var id = '<?php echo (isset($id) && $id>0) ? $id : '' ; ?>';
			var dataTable = $('#discount').DataTable({
				"processing": true,
				"serverSide": true,
				"paging": false,
				"columnDefs": [
					{ orderable: false }
				],
				"fixedColumns": true,
				"language": {
					"infoFiltered": ""
				},
				"ajax": {
					"url": "<?php echo base_url('shippingPrice/discount_getTableData/'); ?>"+id,
					"type": "POST",
					"async" : false,
					"data": {
						formData: function() {
							return $('#filterForm').serialize();
						},
					},
				},
				"columns": [  
					{ "data": "first_column" },
					{ "data": "second_column" },
					{ "data": "third_column" },
					{ "data": "id", render : function ( data, type, row, meta ) {
						return  '<div class="btn-group"> <a class="btn btn-sm btn-outline-light delDiscount" href="javascript:void(0);" data-href="<?php echo base_url('shippingPrice/discount_delete/'); ?>'+data+'" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i></a>  </div>';
					} }
				]
			});
			dataTable.columns(1).visible(true);
			$('#filterBtn').on('click', function(){
				dataTable.search('').draw(); 
				dataTable.ajax.reload();
			});

			//save the discounts
			$('#discountForm').submit(function(e){
				e.preventDefault();
				var discount_arr = [];
				var practice_id = '';
				$(".uk_discount").each(function() {
					practice_id = $(this).data('practice_id');
					discount_id = $(this).data('discount_id');
					if($(this).val()!=''){
						discount_arr.push({
							'shipping_id' : id,
							'discount_id' : discount_id,
							'practice_id' : practice_id,
							'uk_discount': $(this).val(),
							'roi_discount': $("#roi_discount_"+practice_id).val()
						});
					}
				});

				$.ajax({
					"type":"POST",
					"url":"<?php echo base_url('shippingPrice/save_discount'); ?>",
					"data" : {"discount_arr":discount_arr},
					"dataType": "json",
					"async":false,
					success:function(data){
						if(data.status=='success'){
							alert('Shipping Price has been set successfully.');
						}else if(data.status.trim()=='nothing_selected'){
							alert('Please select atleast one practice!');
						}else{
							alert('Something went wrong! please try again.');
							
						}
					}
				});
			});

			$(document).on('click','.delDiscount', function(){
				if(confirm('Are you sure you want to delete this Shipping Price?')){
					var href = $(this).data('href');
					$('#cover-spin').show();
					$.ajax({
						url: href,
						type: 'GET',
						success: function (data) {
							$('#cover-spin').hide();
							if(data == 'failed'){
								alert('Something went wrong!');
							}else{
								window.location.reload();
							}
						}
					});
				}
			});
		});
		</script>
	</body>
</html>
