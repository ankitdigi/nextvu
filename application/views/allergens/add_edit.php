<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Allergen Groups
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Allergen Groups</li>
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
						if((in_array("8",json_decode($data['order_type']))) || (in_array("9",json_decode($data['order_type']))) || (in_array("11",json_decode($data['order_type'])))){
							$paxName = '';
						}else{
							$paxName = 'style="display:none"';
						}
					}else{
						$paxName = 'style="display:none"';
					}
					?>
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<div class="box-header with-border"><h3 class="box-title"><?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3></div><!-- /.box-header -->
								<!-- form start -->
								<?php echo form_open('', array('name'=>'allergenForm', 'id'=>'allergenForm')); ?>
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
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name="is_mixtures" value="1" <?php echo (isset($data['is_mixtures']) && $data['is_mixtures'] == 1) ? 'checked="checked"' : ''; ?>>
															This allergen is a Mixture?
														</label>
													</div>
													<?php echo form_error('is_mixtures', '<div class="error">', '</div>'); ?>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>English Name (Default)</label>
														<input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
														<?php echo form_error('name', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>Danish Name</label>
														<input type="text" class="form-control" name="name_danish" placeholder="Enter Danish Name" value="<?php echo set_value('name_danish',isset($data['name_danish']) ? $data['name_danish'] : '');?>">
														<?php echo form_error('name_danish', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>French Name</label>
														<input type="text" class="form-control" name="name_french" placeholder="Enter French Name" value="<?php echo set_value('name_french',isset($data['name_french']) ? $data['name_french'] : '');?>">
														<?php echo form_error('name_french', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>German Name</label>
														<input type="text" class="form-control" name="name_german" placeholder="Enter German Name" value="<?php echo set_value('name_german',isset($data['name_german']) ? $data['name_german'] : '');?>">
														<?php echo form_error('name_german', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>Italian Name</label>
														<input type="text" class="form-control" name="name_italian" placeholder="Enter Italian Name" value="<?php echo set_value('name_italian',isset($data['name_italian']) ? $data['name_italian'] : '');?>">
														<?php echo form_error('name_italian', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>Dutch Name</label>
														<input type="text" class="form-control" name="name_dutch" placeholder="Enter Dutch Name" value="<?php echo set_value('name_dutch',isset($data['name_dutch']) ? $data['name_dutch'] : '');?>">
														<?php echo form_error('name_dutch', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>Norwegian Name</label>
														<input type="text" class="form-control" name="name_norwegian" placeholder="Enter Norwegian Name" value="<?php echo set_value('name_norwegian',isset($data['name_norwegian']) ? $data['name_norwegian'] : '');?>">
														<?php echo form_error('name_norwegian', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>Spanish Name</label>
														<input type="text" class="form-control" name="name_spanish" placeholder="Enter Spanish Name" value="<?php echo set_value('name_spanish',isset($data['name_spanish']) ? $data['name_spanish'] : '');?>">
														<?php echo form_error('name_spanish', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group">
														<label>Swedish Name</label>
														<input type="text" class="form-control" name="name_swedish" placeholder="Enter Swedish Name" value="<?php echo set_value('name_swedish',isset($data['name_swedish']) ? $data['name_swedish'] : '');?>">
														<?php echo form_error('name_swedish', '<div class="error">', '</div>'); ?>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12"><hr style="border-width:2px;border-color:#367fa9;"></div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group English Name (Default)</label>
														<input type="text" class="form-control" name="pax_name" placeholder="Enter PAX Group Name" value="<?php echo set_value('pax_name',isset($data['pax_name']) ? $data['pax_name'] : '');?>">
														<?php echo form_error('pax_name', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group Danish Name</label>
														<input type="text" class="form-control" name="pax_name_danish" placeholder="Enter PAX Group Danish Name" value="<?php echo set_value('pax_name_danish',isset($data['pax_name_danish']) ? $data['pax_name_danish'] : '');?>">
														<?php echo form_error('pax_name_danish', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group French Name</label>
														<input type="text" class="form-control" name="pax_name_french" placeholder="Enter PAX Group French Name" value="<?php echo set_value('pax_name_french',isset($data['pax_name_french']) ? $data['pax_name_french'] : '');?>">
														<?php echo form_error('pax_name_french', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group German Name</label>
														<input type="text" class="form-control" name="pax_name_german" placeholder="Enter PAX Group German Name" value="<?php echo set_value('pax_name_german',isset($data['pax_name_german']) ? $data['pax_name_german'] : '');?>">
														<?php echo form_error('pax_name_german', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group Italian Name</label>
														<input type="text" class="form-control" name="pax_name_italian" placeholder="Enter PAX Group Italian Name" value="<?php echo set_value('pax_name_italian',isset($data['pax_name_italian']) ? $data['pax_name_italian'] : '');?>">
														<?php echo form_error('pax_name_italian', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group Dutch Name</label>
														<input type="text" class="form-control" name="pax_name_dutch" placeholder="Enter PAX Group Dutch Name" value="<?php echo set_value('pax_name_dutch',isset($data['pax_name_dutch']) ? $data['pax_name_dutch'] : '');?>">
														<?php echo form_error('pax_name_dutch', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group Norwegian Name</label>
														<input type="text" class="form-control" name="pax_name_norwegian" placeholder="Enter PAX Group Norwegian Name" value="<?php echo set_value('pax_name_norwegian',isset($data['pax_name_norwegian']) ? $data['pax_name_norwegian'] : '');?>">
														<?php echo form_error('pax_name_norwegian', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group Spanish Name</label>
														<input type="text" class="form-control" name="pax_name_spanish" placeholder="Enter PAX Group Spanish Name" value="<?php echo set_value('pax_name_spanish',isset($data['pax_name_spanish']) ? $data['pax_name_spanish'] : '');?>">
														<?php echo form_error('pax_name_spanish', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2" style="padding:0px 10px 0px 0px">
													<div class="form-group paxName" <?php echo $paxName; ?>>
														<label>PAX Group Swedish Name</label>
														<input type="text" class="form-control" name="pax_name_swedish" placeholder="Enter PAX Group Swedish Name" value="<?php echo set_value('pax_name_swedish',isset($data['pax_name_swedish']) ? $data['pax_name_swedish'] : '');?>">
														<?php echo form_error('pax_name_swedish', '<div class="error">', '</div>'); ?>
													</div>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->
									</div><!-- /.box-body -->
									<div class="box-footer">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								<?php echo form_close(); ?>
								<!-- form end -->
							</div><!-- /.box -->
						</div><!--/.col (left) -->
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div><!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			$('#allergenForm').parsley();
			$('select[name="order_type[]"]').on('change', function() {
				var filtered_order_type = $(this).val();
				if(filtered_order_type){
					if((jQuery.inArray("8", filtered_order_type) != -1) || (jQuery.inArray("9", filtered_order_type) != -1) || (jQuery.inArray("11", filtered_order_type) != -1)) {
						$(".paxName").show();
					} else {
						$(".paxName").hide();
					}
				}
			});
		});
		</script>
	</body>
</html>