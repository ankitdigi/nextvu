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
					<?php echo $this->lang->line('Allergens'); ?>
						<small>	<?php echo $this->lang->line('Control_Panel'); ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#">	<?php echo $this->lang->line('Orders_Management'); ?> </a></li>
						<li class="active">	<?php echo $this->lang->line('Orders'); ?></li>
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
						<h4><i class="icon fa fa-check"></i> 	<?php echo $this->lang->line('alert'); ?></h4>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>

					<?php if(!empty($this->session->flashdata('error'))){ ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-warning"></i> 	<?php echo $this->lang->line('alert'); ?></h4>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php } ?>

					<?php if(!empty($this->session->flashdata('info'))){ ?>
					<div class="alert alert-info alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-info"></i> <?php echo $this->lang->line('info'); ?></h4>
						<?php echo $this->session->flashdata('info'); ?>
					</div>
					<?php } ?>
					<!--alert msg-->

					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<?php echo form_open('', array('name'=>'allergensForm', 'id'=>'allergensForm')); ?>
									<div class="box-header with-border">
										<div class="pull-left">
											<button type="submit" value="save" name="save" class="btn btn-primary" id="submit_btn"><?php echo $this->lang->line('save_new_recommendation'); ?></button>
											<input id="allergen_array" type="hidden" name="allergenArr" value='<?php echo $data['allergens']; ?>'>
											<div id="removed_allergens">
												<?php
												if($slected_treatment == '1'){
													$removed_allergen = $data['removed_treatment_1'];
												}
												if($slected_treatment == '2'){
													$removed_allergen = $data['removed_treatment_2'];
												}
												if(!empty($removed_allergen)){
													$removed_allergen = json_decode($removed_allergen);
													foreach($removed_allergen as $rid){
														?>
														<input class="remove_option" type="hidden" name="removed_allergens[]" value=<?=$rid?>> 
														<?php
													}
												}
												?>
											</div>
											<input type="hidden" name="slected_treatment" value='<?php echo $slected_treatment; ?>'>
											<?php if($data['serum_type'] == 1){ ?>
											<a href="<?php echo site_url('orders/interpretation/'.$id.''); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line('back'); ?></a>
											<?php }else{ ?>
												<a href="<?php echo site_url('orders/treatment/'.$id.''); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line('back'); ?></a>
											<?php } ?>
											<br><br><h3 class="box-title"><span style="font-size:smaller;color: #4fb7f3; font-weight:700;"><?php echo $this->lang->line('please_indicate_which_allergens_are_required'); ?></span></h3>
											<?php /* <p style="color: #346a7e;"><label><input type="checkbox" id="checkAll"/> Check/Uncheck all</label></p> */ ?>
										</div>
										<div class="pull-right">
											<?php if($data['serum_type'] == 1 && $slected_treatment == 2){ ?>
											<a onclick="return confirm('Are you sure you want to deleted Treatment Option 2? Once deleted, the treatment option can not be re-instated.')" href="<?php echo site_url('orders/remove_pax_treatment2/'.$id.''); ?>" class="btn btn-primary">Remove Treatment 2</a><br><br>
											<?php }elseif($data['serum_type'] == 2 && $slected_treatment == 2){ ?>
											<a onclick="return confirm('Are you sure you want to deleted Treatment Option 2? Once deleted, the treatment option can not be re-instated.')" href="<?php echo site_url('orders/remove_nextlab_treatment2/'.$id.''); ?>" class="btn btn-primary">Remove Treatment 2</a><br><br>
											<?php } ?>
											<input class="pull-right" type="text" size="30" placeholder="search..." id="searched" >
										</div>
									</div>

									<!-- form start -->
									<input type="hidden" id="allergen_total" name="allergen_total" value="<?php echo $allergen_total; ?>">
									<input type="hidden" name="is_interpretation" value="1" />
									<!--Allergens List-->
									<?php if(count($allergens_group)>0){ ?>
										<div class="box-body">
											<div class="row select">
												<?php 
												foreach ($allergens_group as $key => $value) {  
													$CI =& get_instance();
													$CI->load->model('AllergensModel');
													$subAllergens = $CI->AllergensModel->get_subAllergens_recommendation($value['id'],'',$data['sub_order_type']);
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
																						<label><input type="checkbox" name="allergens[]" value="<?php echo $svalue['id']; ?>" <?php echo ( (!empty($data['allergens'])) &&  (in_array($svalue['id'],json_decode($data['allergens']))) ) ? 'checked' : ''; ?>><?php echo $svalue['name']; ?></label>
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
											<?php $userData = logged_in_user_data(); ?>
											<?php if ($userData['role'] == '5') { ?>
												<div class="row">
													<div class="col-sm-12 col-md-12 col-lg-12">
														<div class="form-group">
															<label>	<?php echo $this->lang->line('practice_comments'); ?></label>
															<textarea class="form-control" name="practice_lab_comment" rows="3" placeholder="	<?php echo $this->lang->line('enter_comment'); ?>"><?php echo set_value('practice_lab_comment', isset($data['practice_lab_comment']) ? $data['practice_lab_comment'] : ''); ?></textarea>
															<?php echo form_error('practice_lab_comment', '<div class="error">', '</div>'); ?>
														</div>
													</div>
												</div>
											<?php } ?>
										</div><!-- /.box-body -->
									<?php } ?>
									<!--Allergens List-->
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

			//removed treatment
			$('input[name="allergens[]"]').on("click",function() {
				var a_value = $(this).val();
			    is_allergen = 0;
			    var allergen_array = $('#allergen_array').val();
				if(allergen_array.indexOf(a_value) != -1){
					is_allergen = 1;
				}
				if ($(this).not(':checked').length) {
					if(is_allergen == 1){
						var cnt = 0;
						$( ".remove_option" ).each(function( index ) {
						 	rval = $( this ).val();
						 	if(rval == a_value)
						 	{
						 		cnt = 1;
						 	}
						});
						if(cnt == 0){
							var html_all = '<input class="remove_option" type="hidden" name="removed_allergens[]" value='+a_value+'>';
					    	$('#removed_allergens').append(html_all);
						}
					}
				}else{
					var cnt = 0;
					$( ".remove_option" ).each(function( index ) {
						rval = $( this ).val();
						if(rval == a_value)
						{
							$(this).remove();
						}
					});
				}
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
	
		/* $("#checkAll").change(function () {
			$("input:checkbox").prop('checked', $(this).prop("checked"));
		}); */
		</script>
	</body>
</html>