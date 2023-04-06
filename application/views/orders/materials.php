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
					<?php echo $this->lang->line("order_materials");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"><?php echo $this->lang->line("Orders_Management");?></a></li>
						<li class="active"><?php echo $this->lang->line("Orders");?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">
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
					<!--alert msg-->

					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- form start -->
							<?php echo form_open('', array('name'=>'serumReqForm', 'id'=>'serumReqForm')); ?>

								<!-- Animal and owner details -->
								<div class="box box-primary">
									<div class="box-header with-border">
										<h3 class="box-title"><?php echo $this->lang->line("details");?></h3>
										<p class="pull-right">
											<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line("back");?></a>
										</p>
									</div><!-- /.box-header -->

									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label><?php echo $this->lang->line("date_serum_drawn");?></label>
													<div class="input-group date">
														<div class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</div>
														<input type="text" class="form-control pull-right datepicker" name="serum_drawn_date" placeholder="<?php echo $this->lang->line
														("enter_date_serum_drawn");?>" value="<?php echo set_value('serum_drawn_date',isset($data['serum_drawn_date']) ? date("d/m/Y",strtotime($data['serum_drawn_date'])) : '');?>">
													</div>
													<?php echo form_error('serum_drawn_date', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("what_are_the_major_presenting_symptoms");?></label>
													<div class="checkbox">
														<label>
														<input type="checkbox" name="major_symptoms[]" value="1" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("pruritus");?>
														</label>

														<label>
														<input type="checkbox" name="major_symptoms[]" value="2" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("otitis");?>
														</label>

														<label>
														<input type="checkbox" name="major_symptoms[]" value="3" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("respiratory");?>
														</label>

														<label>
														<input type="checkbox" name="major_symptoms[]" value="0" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '0' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("other");?>
														</label>
													</div>
													<?php echo form_error('major_symptoms[]', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label><?php echo $this->lang->line("other_symptom");?></label>
													<input type="text" class="form-control" name="other_symptom" placeholder="<?php echo $this->lang->line("enter_other_symptom");?>" value="<?php echo set_value('other_symptom',isset($data['other_symptom']) ? $data['other_symptom'] : '');?>">
													<?php echo form_error('other_symptom', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label><?php echo $this->lang->line
													("at_what_age_did_these_symptoms_first_appear");?></label>
													<input type="text" class="form-control" name="symptom_appear_age" placeholder="<?php echo $this->lang->line("enter_age");?>" value="<?php echo set_value('symptom_appear_age',isset($data['symptom_appear_age']) ? $data['symptom_appear_age'] : '');?>">
													<?php echo form_error('symptom_appear_age', '<div class="error">', '</div>'); ?>
												</div>  
											</div><!-- /.col 6 -->

											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label><?php echo $this->lang->line("where_symptoms_most_obvious");?></label>
													<div class="checkbox">
														<label>
														<input type="checkbox" name="when_obvious_symptoms[]" value="1" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("spring");?>
														</label>

														<label>
														<input type="checkbox" name="when_obvious_symptoms[]" value="2" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("summer");?>
														</label>

														<label>
														<input type="checkbox" name="when_obvious_symptoms[]" value="3" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("autumn");?>
														</label>

														<label>
														<input type="checkbox" name="when_obvious_symptoms[]" value="4" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '4' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("autumn");?>
														</label>

														<label>
														<input type="checkbox" name="when_obvious_symptoms[]" value="5" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '5' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("all_year");?>
														</label>
													</div>
													<?php echo form_error('when_obvious_symptoms[]', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label><?php echo $this->lang->line("where_symptoms_most_obvious");?></label>
													<div class="checkbox">
														<label>
														<input type="checkbox" name="where_obvious_symptoms[]" value="1" <?php if( isset($data['where_obvious_symptoms']) && (strpos( $data['where_obvious_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("Indoors");?>
														</label>

														<label>
														<input type="checkbox" name="where_obvious_symptoms[]" value="2" <?php if( isset($data['where_obvious_symptoms']) && (strpos( $data['where_obvious_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("outdoors");?>
														</label>

														<label>
														<input type="checkbox" name="where_obvious_symptoms[]" value="3" <?php if( isset($data['where_obvious_symptoms']) && (strpos( $data['where_obvious_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line("no_difference");?>
														</label>
													</div>
													<?php echo form_error('where_obvious_symptoms[]', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label><?php echo $this->lang->line("medication");?></label>
													<div class="checkbox">
														<label>
														<input type="checkbox" name="medication" value="1" <?php echo ( isset($data['medication']) && $data['medication']==1) ? 'checked' : ''; ?> class="select_medication">
														<?php echo $this->lang->line("yes");?>
														</label>

														<label>
														<input type="checkbox" name="medication" value="2" <?php echo ( isset($data['medication']) && $data['medication']==2) ? 'checked' : ''; ?> class="select_medication">
														<?php echo $this->lang->line("no");?>
														</label>
													</div>
													<?php echo form_error('medication', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label><?php echo $this->lang->line("if_yes_please_specify");?></label>
													<textarea class="form-control" name="medication_desc" rows="3" placeholder="<?php echo $this->lang->line("spring");?>Enter Address"><?php echo set_value('medication_desc',isset($data['medication_desc']) ? $data['medication_desc'] : '');?></textarea>
													<?php echo form_error('medication_desc', '<div class="error">', '</div>'); ?>
												</div>
											</div><!-- /.col 6 -->
										</div><!-- /.row -->
									</div><!-- /.box-body -->
								</div><!-- /.box -->
								<!-- Animal and owner details -->

								<!--footer-->
								<div class="box-footer">
									<p class="pull-right">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line("next");?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
									</p>
								</div>
								<!--footer-->
							<?php echo form_close(); ?>
							<!-- form end -->
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
			$('#serumReqForm').parsley();
			//Date picker
			$('.datepicker').datepicker({
				format: "dd/mm/yyyy",
				todayHighlight: true,
				autoclose: true,
			});

			$(".select_one").change(function() {
				$(".select_one").not(this).prop('checked', false);
			});

			$(".select_medication").change(function() {
				$(".select_medication").not(this).prop('checked', false);
			});

		});
		</script>
	</body>
</html>