<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="1050px" style="width:100%;max-width:1050px; padding:0; background-color:#ffffff;padding:30px;">
	<tr>
		<td style="padding: 0px 15px;">
			<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="46%" style="width:46%;">
				<tr>
					<td style="background:#326883; border-radius:12px 12px 0 0; padding:13px 15px; font-weight:700; color:#ffffff; font-size:16px;"><?php echo $this->lang->line('food_screen_extracts_and_components'); ?></td>
				</tr>
				<tr>
					<td style="background:#e2f2f4; border-radius:0 0 12px 12px; padding:15px;">
						<table>
							<tr>
								<td style="color:#1b3542; font-weight:400; font-size:15px;"><?php echo $this->lang->line('screening_results'); ?>:</td>
								<td style="color:#1b3542; font-weight:700; text-transform:uppercase; font-size:15px;" align="right"><?php echo $this->lang->line('negative'); ?></td>
							</tr>
							<tr>
								<td colspan="2" style="color:#1b3542; font-weight:400; font-size:14px; padding:12px 0 0 0;"><?php echo $this->lang->line('food_negative_result'); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>

			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
				<tbody>
					<tr>
						<td>
							<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;">
							<?php echo $this->lang->line('frequently_asked_questions'); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>

			<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="47%">			
				<tr>
					<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
					<td><h6 style="color:#2a5b74; font-size:16px; margin:0;font-weight: 600;">
					<?php echo $this->lang->line('negative_result_title3'); ?></h6></td>
				</tr>
				<tr>
					<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('negative_a1_food'); ?></p></td>
				</tr>
				<tr><td height="30"></td></tr>
				<tr>
					<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
					<td><h6 style="color:#2a5b74; font-size:16px; margin:0;font-weight: 600;">
					<?php echo $this->lang->line('negative_q2'); ?></h6></td>
				</tr>
				<tr>
					<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('negative_a2'); ?></p></td>
				</tr>
			</table>

			<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="47%">
				<?php if($this->session->userdata('site_lang') != 'spanish'){ ?>
				<tr>
					<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
					<td><h6 style="color:#2a5b74; font-size:16px; margin:0;font-weight: 600;">
					<?php echo $this->lang->line('negative_q4'); ?></h6></td>	
				</tr>
				<tr>
					<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('There_is_currently_no_evidence_suggesting_that_such_approach_would_be_useful_Exceptionally_in_rare_cases_in_which_a_natural_provocation_suggests_the_relevance_of_a_unique_environmental_allergen'); ?></p></td>
				</tr>
				<tr><td height="30"></td></tr>
				<?php } ?>
				<tr>
					<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
					<td><h6 style="color:#2a5b74; font-size:16px; margin:0;font-weight: 600;">
					<?php echo $this->lang->line('can_symptomatic_medication_give_anegative_result'); ?></h6></td>
				</tr>
				<tr>
					<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('the_short_term_treatment_with_glucocorticoids_ciclosporin_JAK_inhibitors_or_monoclonal_antibodies_does_not_appear_to_affect_the_results_of_IgE_serology_however_the_effect_long_term_more_than_one_month_duration_has_not_been_evaluated'); ?></p></td>
				</tr>
			</table>
			<table width="100%"><tr><td height="60"></td></tr></table>
			<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="40%" style="width:40%; padding:0;">
				<tr>
					<td>
						<h6 style="color:#31688a; font-size:16px; margin:0;"> <?php echo $this->lang->line('do_you_need_any_help'); ?></h6>
						<p style="color:#203548; font-size:13px; margin:4px 0 0 0;"><?php echo $this->lang->line('please_contact_our_veterinary_support_team_by_phone_+01494_629979_or_by_email'); ?> <a style="color:#203548;" href="mailto:<?php echo $this->lang->line('contact_email'); ?>"> <?php echo $this->lang->line('contact_email'); ?></a></p>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="60"></td></tr></table>
		</td>
	</tr>
</table>