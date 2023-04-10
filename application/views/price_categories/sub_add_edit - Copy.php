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
						Product Management
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Product Management</li>
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
								<?php echo form_open('', array('name'=>'sub_categoryForm', 'id'=>'sub_categoryForm')); ?>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label>Order Type</label>
													<select class="form-control parent_id" name="parent_id" id="parent_id" required="">
														<option value="">--Select--</option>
														<?php foreach ( $price_categories as $price_category ){ ?>
														<option value="<?php echo $price_category['id']; ?>" <?php if(isset($id) && $id>0 && ($price_category['id']==$data['parent_id'])) echo 'selected="selected"'; ?>><?php echo $price_category['name']; ?></option>
														<?php }?>
													</select>
													<?php echo form_error('parent_id', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Name</label>
													<input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
													<?php echo form_error('name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>UK LIMS Name</label>
													<input type="text" class="form-control" name="lims_name" placeholder="Enter UK LIMS Name" value="<?php echo set_value('lims_name',isset($data['lims_name']) ? $data['lims_name'] : '');?>" <?php if(isset($id) && $id > 0 && ($data['parent_id'] == 1 || $data['parent_id'] == 2 || $data['parent_id'] == 22)){ echo 'required="required"'; } ?>>
													<?php echo form_error('lims_name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding-left: 0px;">
														<div class="form-group">
															<label>UK LIMS Code</label>
															<input type="text" class="form-control" name="lims_code" placeholder="Enter UK LIMS Code" value="<?php echo set_value('lims_code',isset($data['lims_code']) ? $data['lims_code'] : '');?>" <?php if(isset($id) && $id > 0 && ($data['parent_id'] == 1 || $data['parent_id'] == 2 || $data['parent_id'] == 22)){ echo 'required="required"'; } ?>>
															<?php echo form_error('lims_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
														<div class="form-group">
															<label>UK LIMS Test Code</label>
															<input type="text" class="form-control" name="lims_test_code" placeholder="Enter UK LIMS Test Code" value="<?php echo set_value('lims_test_code',isset($data['lims_test_code']) ? $data['lims_test_code'] : '');?>" <?php if(isset($id) && $id > 0 && ($data['parent_id'] == 1 || $data['parent_id'] == 2 || $data['parent_id'] == 22)){ echo 'required="required"'; } ?>>
															<?php echo form_error('lims_test_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
												</div>
												<div class="col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding-left: 0px;">
														<div class="form-group">
															<label>UK Sage Code</label>
															<input type="text" class="form-control" name="sage_code" placeholder="Enter UK Sage Code" value="<?php echo set_value('sage_code',isset($data['sage_code']) ? $data['sage_code'] : '');?>">
															<?php echo form_error('sage_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
														<div class="form-group">
															<label>UK Nominal Code</label>
															<input type="text" class="form-control" name="nominal_code" placeholder="Enter UK Nominal Code" value="<?php echo set_value('nominal_code',isset($data['nominal_code']) ? $data['nominal_code'] : '');?>">
															<?php echo form_error('nominal_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
												</div>
												<div class="col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding-left: 0px;">
														<div class="form-group">
															<label>Spain Sage Code</label>
															<input type="text" class="form-control" name="spain_sage_code" placeholder="Enter Spain Sage Code" value="<?php echo set_value('spain_sage_code',isset($data['spain_sage_code']) ? $data['spain_sage_code'] : '');?>">
															<?php echo form_error('spain_sage_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
														<div class="form-group">
															<label>Poland Sage Code</label>
															<input type="text" class="form-control" name="poland_sage_code" placeholder="Enter Poland Sage Code" value="<?php echo set_value('poland_sage_code',isset($data['poland_sage_code']) ? $data['poland_sage_code'] : '');?>">
															<?php echo form_error('poland_sage_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
												</div>
												<div class="col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding-left: 0px;">
														<div class="form-group">
															<label>Czech Republic Sage Code</label>
															<input type="text" class="form-control" name="czech_sage_code" placeholder="Enter Czech Republic Sage Code" value="<?php echo set_value('czech_sage_code',isset($data['czech_sage_code']) ? $data['czech_sage_code'] : '');?>">
															<?php echo form_error('czech_sage_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
														<div class="form-group">
															<label>Portugal Sage Code</label>
															<input type="text" class="form-control" name="portugal_sage_code" placeholder="Enter Portugal Sage Code" value="<?php echo set_value('portugal_sage_code',isset($data['portugal_sage_code']) ? $data['portugal_sage_code'] : '');?>">
															<?php echo form_error('portugal_sage_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
												</div>
												<div class="col-sm-12 col-md-12 col-lg-12" style="padding: 0px;">
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding-left: 0px;">
														<div class="form-group">
															<label>Slovenia Sage Code</label>
															<input type="text" class="form-control" name="slovenia_sage_code" placeholder="Enter Slovenia Sage Code" value="<?php echo set_value('slovenia_sage_code',isset($data['slovenia_sage_code']) ? $data['slovenia_sage_code'] : '');?>">
															<?php echo form_error('slovenia_sage_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
													<div class="col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
														<div class="form-group">
															<label>Rest of Europe Sage Code</label>
															<input type="text" class="form-control" name="europe_sage_code" placeholder="Enter Rest of Europe Sage Code" value="<?php echo set_value('europe_sage_code',isset($data['europe_sage_code']) ? $data['europe_sage_code'] : '');?>">
															<?php echo form_error('europe_sage_code', '<div class="error">', '</div>'); ?>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label>Display In Order</label>
													<input type="text" class="form-control" name="display_order" placeholder="Enter Display Order" value="<?php echo set_value('display_order',isset($data['display_order']) ? $data['display_order'] : '');?>">
													<?php echo form_error('display_order', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Product More Info</label>
													<textarea class="form-control" name="product_info" rows="3" placeholder="Enter Product More Info"><?php echo set_value('product_info',isset($data['product_info']) ? $data['product_info'] : '');?></textarea>
													<?php echo form_error('product_info', '<div class="error">', '</div>'); ?>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6" style="padding:0px">
												<div class="Article_Number" <?php if(isset($id) && $id > 0 && $data['parent_id'] == 32){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number</label>
														<input type="text" class="form-control" name="article_number" placeholder="Enter Article Number UK" value="<?php echo set_value('article_number',isset($data['article_number']) ? $data['article_number'] : '');?>">
														<?php echo form_error('article_number', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number Denmark</label>
														<input type="text" class="form-control" name="article_number_DK" placeholder="Enter Article Number Denmark" value="<?php echo set_value('article_number_DK',isset($data['article_number_DK']) ? $data['article_number_DK'] : '');?>">
														<?php echo form_error('article_number_DK', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number France</label>
														<input type="text" class="form-control" name="article_number_FR" placeholder="Enter Article Number France" value="<?php echo set_value('article_number_FR',isset($data['article_number_FR']) ? $data['article_number_FR'] : '');?>">
														<?php echo form_error('article_number_FR', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number Germany</label>
														<input type="text" class="form-control" name="article_number_DE" placeholder="Enter Article Number Germany" value="<?php echo set_value('article_number_DE',isset($data['article_number_DE']) ? $data['article_number_DE'] : '');?>">
														<?php echo form_error('article_number_DE', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number Italy</label>
														<input type="text" class="form-control" name="article_number_IT" placeholder="Enter Article Number Italy" value="<?php echo set_value('article_number_IT',isset($data['article_number_IT']) ? $data['article_number_IT'] : '');?>">
														<?php echo form_error('article_number_IT', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number Netherlands</label>
														<input type="text" class="form-control" name="article_number_NL" placeholder="Enter Article Number Netherlands" value="<?php echo set_value('article_number_NL',isset($data['article_number_NL']) ? $data['article_number_NL'] : '');?>">
														<?php echo form_error('article_number_NL', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number Norway</label>
														<input type="text" class="form-control" name="article_number_NO" placeholder="Enter Article Number Norway" value="<?php echo set_value('article_number_NO',isset($data['article_number_NO']) ? $data['article_number_NO'] : '');?>">
														<?php echo form_error('article_number_NO', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number Spain</label>
														<input type="text" class="form-control" name="article_number_ES" placeholder="Enter Article Number Spain" value="<?php echo set_value('article_number_ES',isset($data['article_number_ES']) ? $data['article_number_ES'] : '');?>">
														<?php echo form_error('article_number_ES', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group col-sm-4 col-md-4 col-lg-4">
														<label>Article Number Sweden</label>
														<input type="text" class="form-control" name="article_number_SE" placeholder="Enter Article Number Sweden" value="<?php echo set_value('article_number_SE',isset($data['article_number_SE']) ? $data['article_number_SE'] : '');?>">
														<?php echo form_error('article_number_SE', '<div class="error">', '</div>'); ?>
													</div>
												</div>
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
															<select class="form-control" name="se_price" id="se_price" style="padding:0px;height: 32px;">
																<option value="SEK" <?php if(isset($id) && $id>0 && ($data['se_price']== 'SEK')){ echo 'selected="selected"'; } ?>>SEK</option>
																<option value="€" <?php if(isset($id) && $id>0 && ($data['se_price']== '€')){ echo 'selected="selected"'; } ?>>Euro(€)</option>
																<option value="£" <?php if(isset($id) && $id>0 && ($data['se_price']== '£')){ echo 'selected="selected"'; } ?>>Pound(£)</option>
																<option value="$" <?php if(isset($id) && $id>0 && ($data['se_price']== '$')){ echo 'selected="selected"'; } ?>>Dollar($)</option>
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
														<input type="text" class="form-control" name="default_price" placeholder="Enter Default Export Price" value="<?php echo set_value('default_price',isset($data['default_price']) ? $data['default_price'] : '');?>" required="">
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
									</div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								<?php echo form_close(); ?>
							</div>
							<!-- product price form elements -->

							<!-- discount form elements -->
							<?php if($id>0){ ?>
								<div class="box box-primary">
									<div class="box-header with-border"><h3 class="box-title">Discount</h3></div><!-- /.box-header -->
										<div class="box-body">
											<!--filter form-->
											<form class="row" style="margin-bottom: 30px;" id="filterForm" method="POST" action="">
												<div class="col-sm-3">
													<div class="form-group">
														<label>Practices/Labs</label>
														<?php 
														$options = array();
														//$options[''] = '-- Select --';
														if(!empty($vatLabUsers)){
															foreach ($vatLabUsers as $user) {
																$user_id = $user['id'];
																$post_code = ($user['postcode']) ? ' - '.$user['postcode'] : '';
																$options[$user_id] = $user['name'].$post_code;
															}
														}
														$attr = 'class="form-control vet_user_id selectpicker" data-live-search="true" multiple=""';
														echo form_dropdown('vet_user_id[]',$options,set_value('vet_user_id',isset($sel_practice_id) ? explode(",",$sel_practice_id['practice_id']) : ''),$attr); ?>
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
															<!-- <th>Sage Code</th> -->
															<th>% Discount</th>
															<th>Action</th>
														</tr>
													</thead>
												</table>
												<div class="box-footer discount_class">
													<button type="submit" class="btn btn-primary">Save</button>
												</div>
											</form>
											<!--Discount Form-->
										</div><!-- /.box-body -->
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
					"url": "<?php echo base_url('priceCategories/discount_getTableData/'); ?>"+id,
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
					{ "data": "third_column" },
					{ "data": "id", render : function ( data, type, row, meta ) {
						return  '<div class="btn-group"> <a class="btn btn-sm btn-outline-light delDiscount" href="javascript:void(0);" data-href="<?php echo base_url('priceCategories/discount_delete/'); ?>'+data+'" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i></a>  </div>';
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
					//console.log(practice_id);
					if($(this).val()!=''){
						discount_arr.push({
							'product_id' : id,
							'discount_id' : discount_id,
							'practice_id' : practice_id,
							'uk_discount': $(this).val()
						});
					}
				});

				$.ajax({
					"type":"POST",
					"url":"<?php echo base_url('priceCategories/save_discount'); ?>",
					"data" : {"discount_arr":discount_arr},
					"dataType": "json",
					"async":false,
					success:function(data){
						if(data.status=='success'){
							alert('Discount has been set successfully.');
						}else if(data.status.trim()=='nothing_selected'){
							alert('Please select atleast one practice!');
						}else{
							alert('Something went wrong! please try again.');
							
						}
					}
				});
			});

			$(document).on('click','.delDiscount', function(){
				if(confirm('Are you sure you want to delete this discount?')){
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
								//dataTable.ajax.reload();
								window.location.reload();
							}
						}
					});
				}
			});
			
			$('select[name="parent_id"]').on('change', function() {
				var filtered_order_type = $(this).val();
				if(filtered_order_type){
					if(filtered_order_type == '32'){
						$(".Article_Number").show();
					} else {
						$(".Article_Number").hide();
					}

					if(filtered_order_type == '1' || filtered_order_type == '2' || filtered_order_type == '22'){
						$("#lims_name").attr("required","required");
						$("#lims_code").attr("required","required");
					} else {
						$("#lims_name").removeAttr("required");
						$("#lims_code").removeAttr("required");
					}
				}
			});
		});
		</script>
	</body>
</html>