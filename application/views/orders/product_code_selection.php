<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<link rel="stylesheet" href='<?php echo base_url("assets/dist/css/radio_box.css"); ?>' />
			<style>
			.middle .box span:before{transform: translateY(-60px);}
			.middle .box span{transform:translate(0,40px)}
			.middle input[type=radio]:checked + .box span{color:#fff;transform:translateY(30px)}
			label{margin-bottom:0}
			.box{margin-bottom:0}
			.box_footer{width:100%;text-align:center;margin-bottom:20px;margin-top:-6px}
			.box_footer .info_link{width:370px;display:inline-block;max-width:100%;margin-bottom:5px;font-weight:700;background-color:#ebf0f3}
			.box_footer .info_link a{font-size:1.5em}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line('panel'); ?>
						<small><?php echo $this->lang->line('product_code'); ?></small>
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
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<div class="box-header with-border">
									<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i>	<?php echo $this->lang->line('back'); ?></a>
								</div><!-- /.box-header -->

								<!-- form start -->
								<?php echo form_open('', array('name'=>'Product CodeForm', 'id'=>'Product CodeForm')); ?>
									<!--Product Code-->
									<div class="box-body">
										<div class="row">
											<?php
											if( !empty($product_codes) ){ 
												foreach ($product_codes as $product_code) {
													if(in_array("1", $zoneby)){
														if(!preg_match('/\bExpansion\b/', $product_code['name']) && !preg_match('/\bExpanded\b/', $product_code['name']) && !preg_match('/\bPAX Environmental Screening\b/', $product_code['name']) && !preg_match('/\bPAX Food Screening\b/', $product_code['name'])){
														?>
															<div class="col-sm-4 col-md-4 col-lg-4">
																<div class="middle">
																	<label>
																		<input type="radio" name="product_code_selection" value="<?php echo $product_code['id']; ?>" <?php if($id==""){echo 'checked=""';}elseif(isset($data['product_code_selection']) && $data['product_code_selection']==$product_code['id']){echo 'checked=""';} ?>/>
																		<div class="front-end box">
																			<span><?php echo $product_code['name']; ?></span>
																		</div>
																	</label>
																</div>
																<?php if($product_code['product_info']!=""){ ?>
																<div class="box_footer">
																	<div class="info_link">
																	<a href="javascript:void(0);" id="more_info" data-id="<?php echo $product_code['id']; ?>">	<?php echo $this->lang->line('more_info'); ?></a>
																	</div>
																</div>
																<?php } ?>
															</div>
														<?php
														}
													}elseif(isset($this->zones) && !empty($this->zones) && $this->zones == '5'){
														if(!preg_match('/\bExpansion\b/', $product_code['name']) && !preg_match('/\bExpanded\b/', $product_code['name']) && !preg_match('/\bScreening\b/', $product_code['name'])){
														?>
															<div class="col-sm-4 col-md-4 col-lg-4">
																<div class="middle">
																	<label>
																		<input type="radio" name="product_code_selection" value="<?php echo $product_code['id']; ?>" <?php if($id==""){echo 'checked=""';}elseif(isset($data['product_code_selection']) && $data['product_code_selection']==$product_code['id']){echo 'checked=""';} ?>/>
																		<div class="front-end box">
																			<span><?php echo $product_code['name']; ?></span>
																		</div>
																	</label>
																</div>
																<?php if($product_code['product_info']!=""){ ?>
																<div class="box_footer">
																	<div class="info_link">
																	<a href="javascript:void(0);" id="more_info" data-id="<?php echo $product_code['id']; ?>">	<?php echo $this->lang->line('more_info'); ?></a>
																	</div>
																</div>
																<?php } ?>
															</div>
														<?php
														}
													}else{
														if(!preg_match('/\bExpansion\b/', $product_code['name']) && !preg_match('/\bExpanded\b/', $product_code['name'])){
														?>
															<div class="col-sm-4 col-md-4 col-lg-4">
																<div class="middle">
																	<label>
																		<input type="radio" name="product_code_selection" value="<?php echo $product_code['id']; ?>" <?php if($id==""){echo 'checked=""';}elseif(isset($data['product_code_selection']) && $data['product_code_selection']==$product_code['id']){echo 'checked=""';} ?>/>
																		<div class="front-end box">
																			<span><?php echo $product_code['name']; ?></span>
																		</div>
																	</label>
																</div>
																<?php if($product_code['product_info']!=""){ ?>
																<div class="box_footer">
																	<div class="info_link">
																	<a href="javascript:void(0);" id="more_info" data-id="<?php echo $product_code['id']; ?>"><?php echo $this->lang->line('more_info'); ?></a>
																	</div>
																</div>
																<?php } ?>
															</div>
														<?php
														}
													}
												}
											} ?>
										</div><!-- /.row -->
									</div><!-- /.box-body -->
									<!--Product Code-->
									<div class="box-footer">
										<p class="pull-right">
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('next'); ?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
										</p>
									</div>
								<?php echo form_close(); ?>
							</div><!-- /.box -->
						</div><!--/.col (left) -->
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<div class="modal fade" id="productInfo">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<input type="hidden" name="product_id_modal" id="product_id_modal" value="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo $this->lang->line('product_information'); ?></h4>
					</div>
					<div class="modal-body">
						<span id="message" class="text-danger"></span>
						<div class="productInformation"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<script>
		$(document).ready(function() {
			$(document).on('click', '#more_info', function() {
				var product_id = $(this).data("id");
				$('#product_id_modal').val(product_id);
				if (product_id) {
					$.ajax({
						url: "<?php echo base_url('Orders/getProductInfo'); ?>",
						data: {
							'product_id': product_id
						},
						method: "POST",
						success: function(data) {
							if (data != '') {
								$('.productInformation').html(data);
								$('#productInfo').modal('show');
							} else {
								$('.productInformation').html('Something went wrong!');
							}
						},
					});
				}
			});
		});
		</script>
	</body>
</html>