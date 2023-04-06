<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"
			integrity=
			"sha512-5CYOlHXGh6QpOFA/TeTylKLWfB3ftPsde7AnmhuitiTX4K5SqCLBeKro6sPS8ilsz1Q4NRx3v8Ko2IBiszzdww=="
			crossorigin="anonymous">
			</script>
			<style>
			mark{color: black;background: yellow;padding: 5px;}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line('vials'); ?>
						<small><?php echo $this->lang->line('Control_Panel'); ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"><?php echo $this->lang->line('Orders_Management'); ?> </a></li>
						<li class="active"><?php echo $this->lang->line('Orders'); ?></li>
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
						<h4><i class="icon fa fa-check"></i> <?php echo $this->lang->line('alert'); ?></h4>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>
					<?php if(!empty($this->session->flashdata('error'))){ ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-warning"></i> <?php echo $this->lang->line('alert'); ?></h4>
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
								<!-- form start -->
								<?php echo form_open('', array('name'=>'vialsForm', 'id'=>'vialsForm')); ?>
									<div class="box-body">
										<div class="row select">
											<?php 
											$quotient = ($total_allergens/8);
											$totalVials = ((round)($quotient));
											$demimal = $quotient-$totalVials;
											if($demimal > 0){
												$totalVials = $totalVials+1;
											}
											$allergensIds = json_decode($order_details['allergens']);
											if($totalVialsdb > 0){
												$vialsListAllenges = $this->OrdersModel->getVialslistAllenges($id);
												$selectedAllenge = explode(",",$vialsListAllenges['allergens']);
											}
											?>
											<input type="hidden" name="total_vials" value="<?=$totalVials?>">
											<div class="col-sm-12 col-md-12 col-lg-12">
												<p><b><?php echo $this->lang->line('you_have_selected'); ?> <?php echo $total_allergens;?><?php echo $this->lang->line('allergens_please_select_which_vial_each_allergen_belongs_to'); ?> </b></p>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="col-sm-6 col-md-6 col-lg-6" style="padding:5px;">
													<select class="form-control required" name="mainList" multiple="multiple" style="min-height: 200px;height: 100%;">
														<?php
														if($totalVialsdb == 0){
															$allergensName = '';
															foreach($allergensIds as $row){
																$this->db->select('name,code');
																$this->db->from("ci_allergens");
																$this->db->where("id",$row);
																$responce = $this->db->get();
																$allergensName = $responce->row();
																echo '<option value="'.$row.'">'.$allergensName->name .' ['.$allergensName->code .']</option>';
															}
														}else{
															$allergensName = '';
															foreach($allergensIds as $row){
																if(!in_array($row, $selectedAllenge)){
																	$this->db->select('name,code');
																	$this->db->from("ci_allergens");
																	$this->db->where("id",$row);
																	$responce = $this->db->get();
																	$allergensName = $responce->row();
																	echo '<option value="'.$row.'">'.$allergensName->name .' ['.$allergensName->code .']</option>';
																}
															}
														}
														?>
													</select>
												</div>
												<div class="col-sm-6 col-md-6 col-lg-6" style="padding:5px;">
													<?php 
													for ($x = 1; $x <= $totalVials; $x++) {
														echo '<button type="button" class="btn btn-primary" onclick="movetoVials('.$x.')">Move to Vial '.$x.'</button> &nbsp;&nbsp;';
													}
													?>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-12">
												<?php 
												if($totalVialsdb == 0){
													for ($x = 1; $x <= $totalVials; $x++) { ?>
														<div class="col-sm-4 col-md-4 col-lg-4" style="padding:5px;">
															<div class="form-group">
																<label><?php echo $this->lang->line('vial'); ?> <?=$x?></label>
																<select name="vials[<?=$x?>][]" id="vials_<?=$x?>" multiple="multiple" class="form-control" style="min-height: 200px;height: 100%;">
																	
																</select>
															</div>
															<button type="button" class="btn btn-primary" onclick="movetoMain(<?=$x?>)"><?php echo $this->lang->line('reset_selected'); ?></button>
														</div>
													<?php 
													}
												}else{
													for ($x = 1; $x <= $totalVials; $x++) {
														$vialsList = $this->OrdersModel->getVialslist($x,$id);
														$vialsAllenges = explode(",",$vialsList['allergens']);
														?>
														<input type="hidden" name="vial_id[<?=$x?>][]" value="<?=$vialsList['vial_id']?>">
														<div class="col-sm-4 col-md-4 col-lg-4" style="padding:5px;">
															<div class="form-group">
																<label><?php echo $this->lang->line('vial'); ?> <?=$x?></label>
																<select name="vials[<?=$x?>][]" id="vials_<?=$x?>" multiple="multiple" class="form-control" style="min-height: 200px;height: 100%;">
																	<?php
																	foreach($vialsAllenges as $row){
																		if(in_array($row, $allergensIds)){
																			$this->db->select('name,code');
																			$this->db->from("ci_allergens");
																			$this->db->where("id",$row);
																			$responce = $this->db->get();
																			$allergensName = $responce->row();
																			echo '<option value="'.$row.'" selected="selected">'.$allergensName->name .' ['.$allergensName->code .']</option>';
																		}
																	}
																	?>
																</select>
															</div>
															<button type="button" class="btn btn-primary" onclick="movetoMain(<?=$x?>)"><?php echo $this->lang->line('reset_selected'); ?></button>
														</div>
													<?php 
													}
												}
												?>
											</div>
										</div><!-- /.row -->
									</div><!-- /.box-body -->
									<div class="box-footer">
										<p class="pull-right">
											<button type="button" class="btn btn-primary" id="submit_btn"><?php echo $this->lang->line('next'); ?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
										</p>
									</div>
								<?php echo form_close(); ?>
								<!-- form end -->
							</div>
						</div><!--/.col (left) -->
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>  
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<script>
		function movetoVials(id){
			var slctlength = $('select[name=mainList]').find('option:selected').length;
			var length = $('#vials_'+id+' > option').length;
			var totllength = parseFloat(slctlength)+parseFloat(length);
			if(slctlength <= 8 && slctlength > 0){
				if(totllength <= 8){
					$('select[name=mainList]').find('option:selected').each(function() {
						$('<option value="'+$(this).val()+'" selected="selected">'+$(this).text()+'</option>').appendTo('#vials_'+id);
						$(this).remove();
					});
				}else{
					var remain = 8 - parseFloat(length);
					alert("Please select only "+ remain +" allergens for Vial "+id+".");
				}
			}else{
				alert("Please select below or equal 8 allergens for any vials.");
			}
		}

		function movetoMain(id){
			var slctlength = $('#vials_'+id).find('option:selected').length;
			if(slctlength > 0){
				$('#vials_'+id).find('option:selected').each(function() {
					$('<option value="'+$(this).val()+'">'+$(this).text()+'</option>').appendTo('select[name=mainList]');
					$(this).remove();
				});
			}else{
				alert("Please select allergens first, Which are you want to changed.");
			}
		}

		$(document).ready(function(){
			$("#submit_btn").click(function(){
				var slctlength = $('select[name=mainList]').find('option').length;
				if(slctlength == 0){
					$("form#vialsForm").submit();
				}else{
					alert("please select which Vial each Allergen belongs to?");
					return false;
				}
			});
		});
		</script>
	</body>
</html>