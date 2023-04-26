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
					<?php echo $this->lang->line('Serum_Request'); ?>
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
					<!--alert msg-->

					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- form start -->
							<?php echo form_open_multipart('', array('name'=>'serumReqForm', 'id'=>'serumReqForm')); ?>
								<!-- Animal and owner details -->
								<div class="box box-primary">
									<div class="box-header with-border">
										<?php if(isset($order_details['order_type']) && $order_details['order_type'] == 2){ ?>
										<a href="<?php echo site_url('orders/addEdit/'.$order_details['id'].''); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line('back'); ?></a>
										<?php }else{ ?>
										<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line('back'); ?></a>
										<?php } ?>
									</div><!-- /.box-header -->
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label><?php echo $this->lang->line('date_serum_drawn'); ?></label>
													<div class="input-group date">
														<div class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</div>
														<input type="text" class="form-control pull-right datepicker" name="serum_drawn_date" placeholder="<?php echo $this->lang->line('enter_date_serum_drawn'); ?>" value="<?php echo set_value('serum_drawn_date',isset($data['serum_drawn_date']) ? date("d/m/Y",strtotime($data['serum_drawn_date'])) : '');?>" autocomplete="off">
													</div>
													<?php echo form_error('serum_drawn_date', '<div class="error">','</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('patient_affected'); ?></label>
													<div class="checkbox">
														<label><input type="checkbox" name="major_symptoms[]" value="1" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('pruritus_itch'); ?></label>
														<label><input type="checkbox" name="major_symptoms[]" value="5" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '5' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('skin_lesions'); ?></label>
														<label><input type="checkbox" name="major_symptoms[]" value="2" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('otitis'); ?></label>
														<label><input type="checkbox" name="major_symptoms[]" value="3" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('respiratory_signs'); ?></label>
														<label><input type="checkbox" name="major_symptoms[]" value="6" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '6' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('ocular_signs'); ?></label>
														<label><input type="checkbox" name="major_symptoms[]" value="4" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '4' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('gastro_intestinal_signs'); ?></label>
														<label><input type="checkbox" name="major_symptoms[]" value="7" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '7' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('anaphylaxis'); ?></label>
														<label><input type="checkbox" name="major_symptoms[]" id="majorSymptomsOther" value="0" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '0' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('other'); ?></label>
													</div>
													<?php echo form_error('major_symptoms[]', '<div class="error">','</div>'); ?>
												</div>
												<div class="form-group otherSymptoms" <?php if( isset($data['major_symptoms']) && (strpos( $data['major_symptoms'], '0' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label><?php echo $this->lang->line('other_symptom'); ?></label>
													<input type="text" class="form-control" name="other_symptom" placeholder="<?php echo $this->lang->line('enter_other_symptoms'); ?>" value="<?php echo set_value('other_symptom',isset($data['other_symptom']) ? $data['other_symptom'] : '');?>">
													<?php echo form_error('other_symptom', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('at_what_age_did_these_symptoms_first_appear'); ?></label>
													<div class="row">
														<div class="col-xs-6">
															<label><?php echo $this->lang->line('years'); ?></label>
															<input type="number" class="form-control" name="symptom_appear_age" placeholder="<?php echo $this->lang->line('enter_years'); ?>" value="<?php echo set_value('symptom_appear_age',isset($data['symptom_appear_age']) ? $data['symptom_appear_age'] : '');?>" maxlength="4">
															<?php echo form_error('symptom_appear_age', '<div class="error">', '</div>'); ?>
														</div><!--col-xs-6-->
														<div class="col-xs-6">
															<label><?php echo $this->lang->line('months'); ?></label>
															<input type="number" class="form-control" name="symptom_appear_age_month" placeholder="<?php echo $this->lang->line('enter_months'); ?>" value="<?php echo set_value('symptom_appear_age_month',isset($data['symptom_appear_age_month']) ? $data['symptom_appear_age_month'] : '');?>" maxlength="2" min="1" max="11">
															<?php echo form_error('symptom_appear_age_month', '<div class="error">', '</div>'); ?>
														</div><!--col-xs-6-->
													</div><!--row-->
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('signs_obvious_serum_req'); ?></label>
													<div class="checkbox">
														<label><input type="checkbox" name="when_obvious_symptoms[]" value="1" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('spring'); ?></label>
														<label><input type="checkbox" name="when_obvious_symptoms[]" value="2" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('summer'); ?></label>
														<label><input type="checkbox" name="when_obvious_symptoms[]" value="3" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('fall'); ?></label>
														<label><input type="checkbox" name="when_obvious_symptoms[]" value="4" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '4' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('winter'); ?></label>
														<label><input type="checkbox" name="when_obvious_symptoms[]" value="5" <?php if( isset($data['when_obvious_symptoms']) && (strpos( $data['when_obvious_symptoms'], '5' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('year_round'); ?></label>
													</div>
													<?php echo form_error('when_obvious_symptoms[]', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('signs_obvious_serum_req_2'); ?></label>
													<div class="checkbox">
														<label><input type="checkbox" name="where_obvious_symptoms[]" value="1" <?php if( isset($data['where_obvious_symptoms']) && (strpos( $data['where_obvious_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('indoors'); ?></label>
														<label><input type="checkbox" name="where_obvious_symptoms[]" value="2" <?php if( isset($data['where_obvious_symptoms']) && (strpos( $data['where_obvious_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('outdoors'); ?></label>
														<label><input type="checkbox" name="where_obvious_symptoms[]" value="3" <?php if( isset($data['where_obvious_symptoms']) && (strpos( $data['where_obvious_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no_difference'); ?></label>
													</div>
													<?php echo form_error('where_obvious_symptoms[]', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('clinical_diagnosis'); ?></label>
													<div class="radio">
														<label><b><?php echo $this->lang->line('food'); ?> </b></label> &nbsp;&nbsp;
														<label><input type="radio" name="diagnosis_food" value="1" <?php if( isset($data['diagnosis_food']) && (strpos( $data['diagnosis_food'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('yes'); ?></label>
														<label><input type="radio" name="diagnosis_food" value="2" <?php if( isset($data['diagnosis_food']) && (strpos( $data['diagnosis_food'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no'); ?></label>
													</div>
													<?php echo form_error('diagnosis_food', '<div class="error">', '</div>'); ?>
													<div class="form-group diagnosisFood" <?php if( isset($data['diagnosis_food']) && (strpos( $data['diagnosis_food'], '1' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
														<input type="text" class="form-control" name="other_diagnosis_food" placeholder="<?php echo $this->lang->line('please_specify'); ?>" value="<?php echo set_value('other_diagnosis_food',isset($data['other_diagnosis_food']) ? $data['other_diagnosis_food'] : '');?>">
														<?php echo form_error('other_diagnosis_food', '<div class="error">', '</div>'); ?>
													</div>
													<div class="form-group foodChallenge" <?php if( isset($data['diagnosis_food']) && (strpos( $data['diagnosis_food'], '1' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><?php echo $this->lang->line('food_challenge'); ?>:</label>
														<div class="checkbox">
															<label><input type="checkbox" name="food_challenge[]" value="1" <?php if( isset($data['food_challenge']) && (strpos( $data['food_challenge'], '1' ) !== false) ){ echo 'checked'; } ?>> &lt;<?php echo $this->lang->line('3_hours'); ?></label>
															<label><input type="checkbox" name="food_challenge[]" value="2" <?php if( isset($data['food_challenge']) && (strpos( $data['food_challenge'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('3_12_hours'); ?></label>
															<label><input type="checkbox" name="food_challenge[]" value="3" <?php if( isset($data['food_challenge']) && (strpos( $data['food_challenge'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('12_24_hours'); ?></label>
															<label><input type="checkbox" name="food_challenge[]" value="4" <?php if( isset($data['food_challenge']) && (strpos( $data['food_challenge'], '4' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('24_48_h'); ?> </label>
															<label><input type="checkbox" name="food_challenge[]" value="5" <?php if( isset($data['food_challenge']) && (strpos( $data['food_challenge'], '5' ) !== false) ){ echo 'checked'; } ?>>&gt;<?php echo $this->lang->line('48_h'); ?></label>
														</div>
														<?php echo form_error('food_challenge[]', '<div class="error">', '</div>'); ?>
													</div>
													<div class="radio">
														<label><b><?php echo $this->lang->line('hymenoptera_stings'); ?> </b></label> &nbsp;&nbsp;
														<label><input type="radio" name="diagnosis_hymenoptera" value="1" <?php if( isset($data['diagnosis_hymenoptera']) && (strpos( $data['diagnosis_hymenoptera'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('yes'); ?></label>
														<label><input type="radio" name="diagnosis_hymenoptera" value="2" <?php if( isset($data['diagnosis_hymenoptera']) && (strpos( $data['diagnosis_hymenoptera'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no'); ?></label>
													</div>
													<?php echo form_error('diagnosis_hymenoptera', '<div class="error">', '</div>'); ?>
													<div class="form-group diagnosisHymenoptera" <?php if( isset($data['diagnosis_hymenoptera']) && (strpos( $data['diagnosis_hymenoptera'], '1' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
														<input type="text" class="form-control" name="other_diagnosis_hymenoptera" placeholder="<?php echo $this->lang->line('please_specify'); ?>" value="<?php echo set_value('other_diagnosis_hymenoptera',isset($data['other_diagnosis_hymenoptera']) ? $data['other_diagnosis_hymenoptera'] : '');?>">
														<?php echo form_error('other_diagnosis_hymenoptera', '<div class="error">', '</div>'); ?>
													</div>
													<div class="radio">
														<label><b><?php echo $this->lang->line('other_s'); ?> </b></label> &nbsp;&nbsp;
														<label><input type="radio" name="diagnosis_other" value="1" <?php if( isset($data['diagnosis_other']) && (strpos( $data['diagnosis_other'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('yes'); ?></label>
														<label><input type="radio" name="diagnosis_other" value="2" <?php if( isset($data['diagnosis_other']) && (strpos( $data['diagnosis_other'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no'); ?></label>
													</div>
													<?php echo form_error('diagnosis_other', '<div class="error">', '</div>'); ?>
													<div class="form-group diagnosisOther" <?php if( isset($data['diagnosis_other']) && (strpos( $data['diagnosis_other'], '1' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
														<input type="text" class="form-control" name="other_diagnosis" placeholder="<?php echo $this->lang->line('please_specify'); ?>" value="<?php echo set_value('other_diagnosis',isset($data['other_diagnosis']) ? $data['other_diagnosis'] : '');?>">
														<?php echo form_error('other_diagnosis', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('exposed_following_animals'); ?>:</label>
													<div class="checkbox">
														<label><input type="checkbox" name="regularly_exposed[]" value="1" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('cats'); ?></label>
														<label><input type="checkbox" name="regularly_exposed[]" value="2" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('dogs'); ?></label>
														<label><input type="checkbox" name="regularly_exposed[]" value="3" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('horses'); ?></label>
														<label><input type="checkbox" name="regularly_exposed[]" value="4" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '4' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('cattle'); ?></label>
														<label><input type="checkbox" name="regularly_exposed[]" value="5" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '5' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('mice'); ?></label>
														<label><input type="checkbox" name="regularly_exposed[]" value="6" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '6' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('guinea_pigs'); ?></label>
														<label><input type="checkbox" name="regularly_exposed[]" value="7" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '7' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('rabbits'); ?></label>
														<label><input type="checkbox" name="regularly_exposed[]" id="regularlyExposed" value="0" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '0' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('other_s'); ?></label>
													</div>
													<?php echo form_error('regularly_exposed[]', '<div class="error">','</div>'); ?>
												</div>
												<div class="form-group regularlyExposed" <?php if( isset($data['regularly_exposed']) && (strpos( $data['regularly_exposed'], '0' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
													<label><?php echo $this->lang->line('which_ones'); ?>:</label>
													<input type="text" class="form-control" name="other_exposed" placeholder="<?php echo $this->lang->line('enter_other_exposed'); ?>" value="<?php echo set_value('other_exposed',isset($data['other_exposed']) ? $data['other_exposed'] : '');?>">
													<?php echo form_error('other_exposed', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('malassezia_infections'); ?></label>
													<div class="checkbox">
														<label><input type="checkbox" name="malassezia_infections[]" value="1" <?php if( isset($data['malassezia_infections']) && (strpos( $data['malassezia_infections'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('malassezia_otitis'); ?></label>
														<label><input type="checkbox" name="malassezia_infections[]" value="2" <?php if( isset($data['malassezia_infections']) && (strpos( $data['malassezia_infections'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('malassezia_dermatitis'); ?></label>
													</div>
													<?php echo form_error('malassezia_infections[]', '<div class="error">','</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('receiving_drugs'); ?></label>
													<div class="checkbox">
														<label><input type="checkbox" name="receiving_drugs[]" id="receivingDrugs1" value="1" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('glucocorticoids_oral_topical_injectable'); ?></label>
													</div>
													<div class="radio receivingDrugs1" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '1' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><input type="radio" name="receiving_drugs_1" value="1" <?php if( isset($data['receiving_drugs_1']) && (strpos( $data['receiving_drugs_1'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_1" value="2" <?php if( isset($data['receiving_drugs_1']) && (strpos( $data['receiving_drugs_1'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('fair_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_1" value="3" <?php if( isset($data['receiving_drugs_1']) && (strpos( $data['receiving_drugs_1'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
													</div>
													<div class="checkbox">
														<label><input type="checkbox" name="receiving_drugs[]" id="receivingDrugs2" value="2" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('ciclosporin'); ?></label>
													</div>
													<div class="radio receivingDrugs2" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '2' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><input type="radio" name="receiving_drugs_2" value="1" <?php if( isset($data['receiving_drugs_2']) && (strpos( $data['receiving_drugs_2'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_2" value="2" <?php if( isset($data['receiving_drugs_2']) && (strpos( $data['receiving_drugs_2'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('fair_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_2" value="3" <?php if( isset($data['receiving_drugs_2']) && (strpos( $data['receiving_drugs_2'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
													</div>
													<div class="checkbox">
														<label><input type="checkbox" name="receiving_drugs[]" id="receivingDrugs3" value="3" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('oclacitinib_apoquel'); ?></label>
													</div>
													<div class="radio receivingDrugs3" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '3' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><input type="radio" name="receiving_drugs_3" value="1" <?php if( isset($data['receiving_drugs_3']) && (strpos( $data['receiving_drugs_3'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_3" value="2" <?php if( isset($data['receiving_drugs_3']) && (strpos( $data['receiving_drugs_3'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('fair_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_3" value="3" <?php if( isset($data['receiving_drugs_3']) && (strpos( $data['receiving_drugs_3'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
													</div>
													<div class="checkbox">
														<label><input type="checkbox" name="receiving_drugs[]" id="receivingDrugs4" value="4" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '4' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('lokivetmab_cytopoint'); ?></label>
													</div>
													<div class="radio receivingDrugs4" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '4' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><input type="radio" name="receiving_drugs_4" value="1" <?php if( isset($data['receiving_drugs_4']) && (strpos( $data['receiving_drugs_4'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_4" value="2" <?php if( isset($data['receiving_drugs_4']) && (strpos( $data['receiving_drugs_4'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('fair_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_4" value="3" <?php if( isset($data['receiving_drugs_4']) && (strpos( $data['receiving_drugs_4'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
													</div>
													<div class="checkbox">
														<label><input type="checkbox" name="receiving_drugs[]" id="receivingDrugs5" value="5" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '5' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('antibiotics'); ?></label>
													</div>
													<div class="radio receivingDrugs5" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '5' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><input type="radio" name="receiving_drugs_5" value="1" <?php if( isset($data['receiving_drugs_5']) && (strpos( $data['receiving_drugs_5'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_5" value="2" <?php if( isset($data['receiving_drugs_5']) && (strpos( $data['receiving_drugs_5'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('fair_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_5" value="3" <?php if( isset($data['receiving_drugs_5']) && (strpos( $data['receiving_drugs_5'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
													</div>
													<div class="checkbox">
														<label><input type="checkbox" name="receiving_drugs[]" id="receivingDrugs6" value="6" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '6' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('antifungals'); ?></label>
													</div>
													<div class="radio receivingDrugs6" <?php if( isset($data['receiving_drugs']) && (strpos( $data['receiving_drugs'], '6' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><input type="radio" name="receiving_drugs_6" value="1" <?php if( isset($data['receiving_drugs_6']) && (strpos( $data['receiving_drugs_6'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_6" value="2" <?php if( isset($data['receiving_drugs_6']) && (strpos( $data['receiving_drugs_6'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('fair_response'); ?></label>
														<label><input type="radio" name="receiving_drugs_6" value="3" <?php if( isset($data['receiving_drugs_6']) && (strpos( $data['receiving_drugs_6'], '3' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('good_to_excellent_response'); ?></label>
													</div>
													<?php echo form_error('receiving_drugs[]', '<div class="error">','</div>'); ?>
												</div>
											</div>	
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label><?php echo $this->lang->line('treatment_ectoparasites'); ?></label>
													<div class="radio">
														<label><input type="radio" name="treatment_ectoparasites" value="1" <?php if( isset($data['treatment_ectoparasites']) && (strpos( $data['treatment_ectoparasites'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('yes'); ?></label>
														<label><input type="radio" name="treatment_ectoparasites" value="2" <?php if( isset($data['treatment_ectoparasites']) && (strpos( $data['treatment_ectoparasites'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no'); ?></label>
													</div>
													<?php echo form_error('treatment_ectoparasites', '<div class="error">', '</div>'); ?>
													<div class="form-group treatmentEctoparasites" <?php if( isset($data['treatment_ectoparasites']) && (strpos( $data['treatment_ectoparasites'], '1' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
														<input type="text" class="form-control" name="other_ectoparasites" placeholder="<?php echo $this->lang->line('please_specify'); ?>" value="<?php echo set_value('other_ectoparasites',isset($data['other_ectoparasites']) ? $data['other_ectoparasites'] : '');?>">
														<?php echo form_error('other_ectoparasites', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('elimination_diet'); ?></label>
													<div class="radio">
														<label><input type="radio" name="elimination_diet" value="1" <?php if( isset($data['elimination_diet']) && (strpos( $data['elimination_diet'], '1' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('yes'); ?></label>
														<label><input type="radio" name="elimination_diet" value="2" <?php if( isset($data['elimination_diet']) && (strpos( $data['elimination_diet'], '2' ) !== false) ){ echo 'checked'; } ?>><?php echo $this->lang->line('no'); ?></label>
													</div>
													<?php echo form_error('elimination_diet', '<div class="error">', '</div>'); ?>
													<div class="form-group eliminationDiet" <?php if( isset($data['elimination_diet']) && (strpos( $data['elimination_diet'], '1' ) !== false) ){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?>>
														<label><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
														<input type="text" class="form-control" name="other_elimination" placeholder="<?php echo $this->lang->line('please_specify'); ?>" value="<?php echo set_value('other_elimination',isset($data['other_elimination']) ? $data['other_elimination'] : '');?>">
														<?php echo form_error('other_elimination', '<div class="error">', '</div>'); ?>
													</div>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('additional_information'); ?></label>
													<textarea class="form-control" name="additional_information" rows="3" placeholder="<?php echo $this->lang->line('enter_add_rele_info'); ?>"><?php echo set_value('additional_information',isset($data['additional_information']) ? $data['additional_information'] : '');?></textarea>
													<?php echo form_error('additional_information', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('zoonotic_disease'); ?></label>
													<div class="checkbox">
														<label><input type="checkbox" name="zoonotic_disease" value="1" <?php echo ( isset($data['zoonotic_disease']) && $data['zoonotic_disease']==1) ? 'checked' : ''; ?> class="select_zoonotic_disease"><?php echo $this->lang->line('yes'); ?></label>
														<label><input type="checkbox" name="zoonotic_disease" value="0" <?php echo ( isset($data['zoonotic_disease']) && $data['zoonotic_disease']==0) ? 'checked' : ''; ?> class="select_zoonotic_disease"><?php echo $this->lang->line('no'); ?></label>
													</div>
													<?php echo form_error('zoonotic_disease', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('if_yes_please_specify'); ?></label>
													<textarea class="form-control zoonotic_disease_dec" name="zoonotic_disease_dec" rows="3" placeholder="<?php echo $this->lang->line('enter_suff_zoon_dis'); ?>"><?php echo set_value('zoonotic_disease_dec',isset($data['zoonotic_disease_dec']) ? $data['zoonotic_disease_dec'] : '');?></textarea>
													<?php echo form_error('zoonotic_disease_dec', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('is_the_animal_receiving_any_other_medication_at_the_moment'); ?></label>
													<div class="checkbox">
														<label><input type="checkbox" name="medication" value="1" <?php echo ( isset($data['medication']) && $data['medication']==1) ? 'checked' : ''; ?> class="select_medication"><?php echo $this->lang->line('yes'); ?></label>
														<label><input type="checkbox" name="medication" value="0" <?php echo ( isset($data['medication']) && $data['medication']==0) ? 'checked' : ''; ?> class="select_medication"><?php echo $this->lang->line('no'); ?></label>
													</div>
													<?php echo form_error('medication', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('if_yes_please_specify'); ?></label>
													<textarea class="form-control medication_desc" name="medication_desc" rows="3" placeholder="<?php echo $this->lang->line('enter_all_medic_animal_curre'); ?>"><?php echo set_value('medication_desc',isset($data['medication_desc']) ? $data['medication_desc'] : '');?></textarea>
													<?php echo form_error('medication_desc', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('laboratory_comment'); ?></label>
													<textarea class="form-control internal_comment" name="internal_comment" rows="3" placeholder="<?php echo $this->lang->line('enter_laboratory_comment'); ?>"><?php echo set_value('internal_comment',isset($order_details['internal_comment']) ? $order_details['internal_comment'] : '');?></textarea>
													<?php echo form_error('internal_comment', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group" <?php if(isset($order_details['serum_type']) && $order_details['serum_type'] == '2'){ echo 'style="display:none;"'; }else{ echo 'style="display:block;"'; } ?>>
													<div class="checkbox">
														<label>
															<input type="checkbox" name="immunotherapy_recommendation" value="1" <?php echo (isset($data['immunotherapy_recommendation']) && $data['immunotherapy_recommendation'] == 1) ? 'checked' : ''; ?> class="immunotherapy_recommendation">
															<?php echo $this->lang->line('serum_immu_reco_order'); ?>	
														</label>
													</div>
													<?php echo form_error('immunotherapy_recommendation', '<div class="error">', '</div>'); ?>
												</div>
												<?php if($userData['role'] == '1'){ ?>
													<div class="form-group">
														<?php if (isset($order_details['requisition_form']) && $order_details['requisition_form'] != '') { ?>
															<label for="requisition_form"><?php echo $this->lang->line('view_requisition_form'); ?></label>
															<a class="btn btn-primary mrgnbtm10" onclick="window.open('<?php echo base_url() . REQUISITION_FORM_PATH; ?>/<?php echo $order_details['requisition_form']; ?>','Requisition Form','width=1200,height=9000')" title="View Order Requisition"> <?php echo $this->lang->line('view_uploaded_order_requisition_form'); ?></a>
														<?php } ?>
													</div>
												<?php } ?>
											</div><!-- /.col 6 -->
										</div><!-- /.row -->
									</div><!-- /.box-body -->
								</div><!-- /.box -->
								<!-- Animal and owner details -->
								<!--footer-->
								<div class="box-footer">
									<p class="pull-right">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('next'); ?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
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

			$('input[id="majorSymptomsOther"]').change(function() {
				if($(this).prop('checked')){
					$(".otherSymptoms").show();
				}else{
					$(".otherSymptoms").hide();
				}
			});

			$('input[name="diagnosis_food"]').change(function() {
				if($(this).val() == 1){
					$(".diagnosisFood").show();
					$(".foodChallenge").show();
				}else{
					$(".diagnosisFood").hide();
					$(".foodChallenge").hide();
				}
			});

			$('input[name="diagnosis_hymenoptera"]').change(function() {
				if($(this).val() == 1){
					$(".diagnosisHymenoptera").show();
				}else{
					$(".diagnosisHymenoptera").hide();
				}
			});

			$('input[name="diagnosis_other"]').change(function() {
				if($(this).val() == 1){
					$(".diagnosisOther").show();
				}else{
					$(".diagnosisOther").hide();
				}
			});

			$('input[id="regularlyExposed"]').change(function() {
				if($(this).prop('checked')){
					$(".regularlyExposed").show();
				}else{
					$(".regularlyExposed").hide();
				}
			});

			$('input[id="receivingDrugs1"]').change(function() {
				if($(this).prop('checked')){
					$(".receivingDrugs1").show();
				}else{
					$(".receivingDrugs1").hide();
				}
			});

			$('input[id="receivingDrugs2"]').change(function() {
				if($(this).prop('checked')){
					$(".receivingDrugs2").show();
				}else{
					$(".receivingDrugs2").hide();
				}
			});

			$('input[id="receivingDrugs3"]').change(function() {
				if($(this).prop('checked')){
					$(".receivingDrugs3").show();
				}else{
					$(".receivingDrugs3").hide();
				}
			});

			$('input[id="receivingDrugs4"]').change(function() {
				if($(this).prop('checked')){
					$(".receivingDrugs4").show();
				}else{
					$(".receivingDrugs4").hide();
				}
			});

			$('input[id="receivingDrugs5"]').change(function() {
				if($(this).prop('checked')){
					$(".receivingDrugs5").show();
				}else{
					$(".receivingDrugs5").hide();
				}
			});

			$('input[id="receivingDrugs6"]').change(function() {
				if($(this).prop('checked')){
					$(".receivingDrugs6").show();
				}else{
					$(".receivingDrugs6").hide();
				}
			});

			$('input[name="treatment_ectoparasites"]').change(function() {
				if($(this).val() == 1){
					$(".treatmentEctoparasites").show();
				}else{
					$(".treatmentEctoparasites").hide();
				}
			});

			$('input[name="elimination_diet"]').change(function() {
				if($(this).val() == 1){
					$(".eliminationDiet").show();
				}else{
					$(".eliminationDiet").hide();
				}
			});

			$(".select_medication").change(function() {
				if($(this).val() == '1'){
					$(".medication_desc").attr('required', true);
				}else{
					$(".medication_desc").attr('required', false);
				}
				$(".select_medication").not(this).prop('checked', false);
			});

			$(".select_zoonotic_disease").change(function() {
				if($(this).val() == '1'){
					$(".zoonotic_disease_dec").attr('required', true);
				}else{
					$(".zoonotic_disease_dec").attr('required', false);
				}
				$(".select_zoonotic_disease").not(this).prop('checked', false);
			});
		});
		</script>
	</body>
</html>