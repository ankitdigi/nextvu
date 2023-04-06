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
					<?php echo $this->lang->line('PANEL'); ?>
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
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<div class="box-header with-border">
									<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line('back'); ?></a>
								</div>

								<?php echo form_open('', array('name'=>'screeningForm', 'id'=>'screeningForm')); ?>
									<div class="box-body">
										<div class="row">
											<?php
											if(!empty($product_codes)){ 
												foreach($product_codes as $product_code){
													?>
													<div class="col-sm-4 col-md-4 col-lg-4">
														<div class="middle">
															<label>
																<input type="radio" name="product_code_selection" value="<?php echo $product_code['id']; ?>" <?php if($id==""){echo 'checked=""';}elseif(isset($data['product_code_selection']) && $data['product_code_selection']==$product_code['id']){echo 'checked=""';} ?>/>
																<div class="front-end box">
																	<span><?php echo $product_code['name']; ?></span>
																</div>
																<input type="hidden" name="screening" value="<?php echo $product_code['display_order'];?>" <?php echo (isset($data['screening']) && $data['screening']==$product_code['display_order']) ? 'checked=""' : "" ;?>/>
															</label>
														</div>
													</div>
												<?php
												}
											} ?>
										</div>
									</div>
									<div class="box-footer">
										<p class="pull-right">
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('next'); ?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
										</p>
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
	</body>
</html>