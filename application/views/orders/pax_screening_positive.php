<?php
$this->db->select('result_value');
$this->db->from('ci_raptor_result_allergens');
$this->db->where('result_id',$raptorData->result_id);
$this->db->where('name LIKE','Cte f 1');
$this->db->order_by('result_value', 'DESC');
$fleaResults = $this->db->get()->row();
?>
<style>
	.hexa_que_img {
	    max-height: 85px;
	}
	.roundedborder_div {
	    border: 2px solid #a8d9e0;
	    border-radius: 15px;
	    padding: 20px 20px 15px;
	    margin-top: -37px;
	    margin-left: 44px;
	    width: calc(100% - 44px);
	}
</style>
<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="1050px" style="width:100%;max-width:1050px; padding:0; background-color:#ffffff;padding:30px;">
	<tr>
		<td style="padding: 0px 15px;">
			<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="48%" style="width:48%;">
				<tr>
					<td style="background:#326883; border-radius:12px 12px 0 0; padding:13px 15px; font-weight:700; color:#ffffff; font-size:16px;"><?php echo $this->lang->line('environmental_screen_extracts_and_components'); ?></td>
				</tr>
				<tr>
					<td style="background:#e2f2f4; border-radius:0 0 12px 12px; padding:15px;">
						<table>
							<tr>
								<td style="color:#1b3542; font-weight:400; font-size:15px;"><?php echo $this->lang->line('screening_results'); ?>:</td>
								<td style="color:#1b3542; font-weight:700; text-transform:uppercase; font-size:15px;" align="right"><?php echo $this->lang->line('positive_2'); ?></td>
							</tr>
							<tr>
								<td colspan="2" style="color:#1b3542; font-weight:400; font-size:14px; padding:12px 0 0 0;"><?php echo $this->lang->line('this_patient_has_an_elevated_level_of_IgE_antibodies_against_one_or_more_allergens_such_as_grasses_weeds_trees_mites_moulds_yeast_dander_or_insect_venoms'); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="48%" style="width:48%;">
				<tr>
					<td style="background:#326883; border-radius:12px 12px 0 0; padding:13px 15px; font-weight:700; color:#ffffff; font-size:16px;"><?php echo $this->lang->line('flea_Cte_f_1'); ?></td>
				</tr>
				<tr>
					<td style="background:#e2f2f4; border-radius:0 0 12px 12px; padding:15px;">
						<table>
							<tr>
								<td style="color:#1b3542; font-weight:400; font-size:15px;">
								<?php echo $this->lang->line('flea_results'); ?>:</td>
								<?php
								if(!empty($fleaResults)){
									if(round($fleaResults->result_value) >= $cutoffs){
										echo '<td style="color:#1b3542; font-weight:700; text-transform:uppercase; font-size:15px;" align="right">'.$this->lang->line('positive_2').'</td>';
									}else{
										echo '<td style="color:#1b3542; font-weight:700; text-transform:uppercase; font-size:15px;" align="right">'.$this->lang->line('negative').'</td>';
									}
								}else{
									echo '<td style="color:#1b3542; font-weight:700; text-transform:uppercase; font-size:15px;" align="right">'.$this->lang->line('negative').'</td>';
								}
								?>
							</tr>
							<tr>
								<?php
								if(!empty($fleaResults)){
									if(round($fleaResults->result_value) >= $cutoffs){
										echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:14px; padding:12px 0 0 0;">'.$this->lang->line('patient_result_positive').'</td>';
									}else{
										echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:14px; padding:12px 0 0 0;">'.$this->lang->line('patient_result_negative').'</td>';
									}
								}else{
									echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:14px; padding:12px 0 0 0;">'.$this->lang->line('patient_result_negative').'</td>';
								}
								?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>
			<div width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0mm 0.5mm 2mm; margin: 10px 0px 0; ">
				<img src="<?php echo base_url(); ?>assets/images/question-hexagon-img.png" class="hexa_que_img" >
				<div class="roundedborder_div">
					<div>
						<div style="padding: 0px 0px 5px 0px;">
							<h6 style="color:#000; font-size:16px; margin:0;font-weight:600;"><?php echo $this->lang->line('positive_for_allergens_what_now'); ?></h6>
							<p style="color:#1e3743; font-size:13px; margin:4px 0 0 0;"><?php echo $this->lang->line('the_next_step_is_to_expand_the_screening_to_identify_to_dentify_the_specific_allergens_against_which_this_patient_is_sensitized_only_after_this_can_an_allergen_specific_immunotherapy_the_only_etiologic_treatment_of_allergies_be_produced_for_this_patient'); ?></p>
						</div>
					</div>
					<div>
						<div style="padding: 0px 0px 5px 0px;">
							<h6 style="color:#000; font-size:16px; margin:0;font-weight:600;"><?php echo $this->lang->line('do_i_need_to_send_new_serum_to_expand_the_results'); ?></h6>
							<p style="color:#1e3743; font-size:13px; margin:4px 0 0 0;"><?php echo $this->lang->line('no_it_is_not_necessary_to_send_new_serum'); ?></p>
							<p style="color:#1e3743; font-size:13px; margin:4px 0 0 0;"><?php echo $this->lang->line('to_expand_the_screen_simply_complete_the_boxes_below_and_send_via_email_once_we_receive_your_request_we_will_report_the_expanded_results_to_you_within_few_days_via_email'); ?></p>
						</div>
					</div>
				</div>
			</div>
			<table width="100%"><tr><td height="30"></td></tr></table>
			<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="40%" style="width:48%; padding:0;margin: 0px auto;">
				<tr>
					<td>
						<h6 style="color:#316783; font-size:24px; margin:0; text-align:center;"><input type="checkbox" style="margin-right:10px;transform: scale(2);accent-color: #326883;line-height: 16px;" /><?php echo $this->lang->line('pax_expand'); ?> </h6>
						<label style="margin:15px 0 7px 0; display:block; text-align:center;">	<?php echo $this->lang->line('i_would_like_to_expand_the_screening_results'); ?></label>
						<label style="display:block;"> <?php echo $this->lang->line('date'); ?>:</label>
						<input type="textbox" style="margin:2px 0 10px 0; background:#edf2f4; border:1px solid #62899d; height:34px; padding:0 3%; width:94%; outline:none;" />
						<label style="display:block;">	<?php echo $this->lang->line('signature'); ?>:</label>
						<textarea style="margin:2px 0 10px 0; background:#edf2f4; border:1px solid #62899d; height:60px; padding:10px 3%; width:94%; outline:none;"></textarea>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>

			<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="32.5%" style="padding:15px;">				
				<tr>
					<td style="padding: 0px;">
						<div style="background-image: url('<?php echo base_url(); ?>assets/images/reliable-bg-img.png'); background-repeat: no-repeat; background-position: left 0 top 0px; background-size: 300px; padding: 58px 30px 28px 56px; ">
							<h6 style="color:#1e3743; font-size:18px; margin:0;"> <?php echo $this->lang->line('100%_reliable'); ?></h6>
							<p style="color:#1e3743; font-size:13px; margin:4px 0 0 0;"> <?php echo $this->lang->line('expanding_the_results_from_the_screen_has_100%_correlation'); ?></p>
						</div>
					</td>
				</tr>
			</table>
			<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="40%" style="width:40%; padding:0;">
				<tr>
					<td style="padding-top: 50px;">
						<h6 style="color:#31688a; font-size:16px; margin:0;"> <?php echo $this->lang->line('do_you_need_any_help'); ?></h6>
						<p style="color:#203548; font-size:13px; margin:4px 0 0 0;"><?php echo $this->lang->line('please_contact_our_veterinary_support_team_by_phone_+01494_629979_or_by_email'); ?> <a style="color:#203548;" href="mailto:<?php echo $this->lang->line('contact_email'); ?>"> <?php echo $this->lang->line('contact_email'); ?></a></p>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="60"></td></tr></table>
		</td>
	</tr>
</table>