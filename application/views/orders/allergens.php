<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js" integrity="sha512-5CYOlHXGh6QpOFA/TeTylKLWfB3ftPsde7AnmhuitiTX4K5SqCLBeKro6sPS8ilsz1Q4NRx3v8Ko2IBiszzdww==" crossorigin="anonymous"></script>
			<style>
			mark {color: black;background: yellow;padding: 5px;}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line("Allergens");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("Orders_Management");?></a></li>
						<li class="active"><?php echo $this->lang->line("Orders");?></li>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content">
					<!--breadcrumb-->
					<?php $this->load->view("orders/breadcrumbs"); ?>
					<!--breadcrumb-->
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
						<h4><i class="icon fa fa-warning"></i> <?php echo $this->lang->line("alert");?></h4>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php } ?>

					<?php if(!empty($this->session->flashdata('info'))){ ?>
					<div class="alert alert-info alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-info"></i> <?php echo $this->lang->line("info");?></h4>
						<?php echo $this->session->flashdata('info'); ?>
					</div>
					<?php } ?>
					<!--alert msg-->

					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<div class="box-header with-border">
									<!-- <p class="pull-right"> -->
									<a href="<?php echo site_url('orders/addEdit/'.$id) ?>" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line("back");?></a>
									<!-- </p> -->
									<br><h3 class="box-title"><?php //echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?><br><span style="font-size:smaller;color: #4fb7f3; font-weight:700;"><?php echo $this->lang->line("please_indicate_which_allergens_are_required");?></span></h3>
									<input class="pull-right" type="text" size="30" 
									placeholder="<?php echo $this->lang->line("search");?>..." id="searched" >
								</div><!-- /.box-header -->

								<!-- form start -->
								<?php echo form_open('', array('name'=>'allergensForm', 'id'=>'allergensForm')); ?>
									<input type="hidden" id="allergen_total" name="allergen_total" value="<?php echo $allergen_total; ?>">
									<!--Allergens List-->
									<?php if(count($allergens_group)>0){ ?>
										<div class="box-body">
											<div class="row select">
												<?php 
												if($data['order_type'] == '2'){ $chk = 'checked="checked"'; }else{ $chk = ''; }
												foreach ($allergens_group as $key => $value) {  
													$CI =& get_instance();
													$CI->load->model('AllergensModel');
													$subAllergens = $CI->AllergensModel->get_subAllergens_dropdown($value['id'],'',$data['sub_order_type']);
													if( !empty($subAllergens) ){
													?>
														<div class="col-sm-12 col-md-12 col-lg-12">
															<div class="tab-content">
																<div class="tab-pane active">
																	<section id="hand">
																		<h4 class="page-header allergen_header" style="font-size:15px; font-weight:700;"><?php echo $value['name']; ?></h4>
																		<div class="row fontawesome-icon-list">
																			<?php 
																			foreach ($subAllergens as $skey => $svalue) {     
																				$allergen_font = ''; 
																				$note = '';
																				if($svalue['is_unavailable']=='1'){
																					$allergen_font = 'style="color:red"';
																					if( $svalue['is_unavailable']=='' ){
																						$note = "Note: Unavailable at this time";
																					}else{
																						$note = "Note: Unavailable at this time, estimated ".date('d/m/Y',strtotime($svalue['due_date']));
																					}
																				}
																				?>
																				<div class="col-md-4 col-sm-4" <?php echo $allergen_font; ?>>
																					<div class="checkbox">
																						<label><input type="checkbox" name="allergens[]" value="<?php echo $svalue['id']; ?>" <?php echo ( (!empty($data['allergens'])) &&  (in_array($svalue['id'],json_decode($data['allergens']))) ) ? 'checked' : ''; ?> <?=$chk?>><?php echo $svalue['name']; ?></label>
																						<?php echo ($note!='') ? "<br>".$note : ''; ?>
																					</div>
																				</div>
																			<?php } ?>
																		</div><!--row-->
																	</section>
																</div><!--tab-pane-->
															</div><!--tab-content-->
														</div><!-- /.col -->
													<?php }//if ?>
												<?php }//foreach ?>
											</div><!-- /.row -->
											<?php
											if(isset($this->zones) && !empty($this->zones)){
												$zoneby = explode(",",$this->zones);
											}else{
												$zoneby = array();
											}
											if ($userData['role'] == '5' && (empty($zoneby) || in_array("1", $zoneby))) { ?>
												<div class="row">
													<div class="col-sm-12 col-md-12 col-lg-12">
														<div class="form-group">
															<label><?php echo $this->lang->line("practice_comments");?></label>
															<textarea class="form-control" name="practice_lab_comment" rows="3" placeholder="<?php echo $this->lang->line("enter_comment");?>"><?php echo set_value('practice_lab_comment', isset($data['practice_lab_comment']) ? $data['practice_lab_comment'] : ''); ?></textarea>
															<?php echo form_error('practice_lab_comment', '<div class="error">', '</div>'); ?>
														</div>
													</div>
												</div>
											<?php } ?>
										</div><!-- /.box-body -->
									<?php } ?>
									<!--Allergens List-->

									<div class="box-footer">
										<?php ($data['sub_order_type']=='3') ? $but_label = 'Next' :  $but_label = 'Next'; ?>
										<p class="pull-right">
											<button type="submit" class="btn btn-primary" id="submit_btn"><?php echo $but_label; ?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
										</p>
									</div>
								<?php echo form_close(); ?>
								<!-- form end -->
							</div><!-- /.box -->
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
			$("#submit_btn").click(function(){
				if($('[name="allergens[]"]').filter(':checked').length >= 1){
					$('#allergen_total').val(1);
				}else{
					$('#allergen_total').val(0);
				}
				return true;
			});
		});

		$('#searched').bind("change keyup input",function() {
			// Select the whole paragraph
			var ob = new Mark(document.querySelector(".select"));

			// First unmark the highlighted word or letter
			ob.unmark();

			// Highlight letter or word
			ob.mark(
				document.getElementById("searched").value,
				{ className: 'a' + 0 }
			);
		});
		</script>
	</body>
</html>
