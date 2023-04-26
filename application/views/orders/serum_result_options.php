<!DOCTYPE html>
<html class="js">
	<head>
		<title><?php echo $this->lang->line('treatment_advice'); ?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		*{font-family: 'Open Sans', sans-serif;}
		.header th{text-align:left;}
		.green_strip{background:#bed600; padding:5px 10px; color:#ffffff; font-size:18px;}
		.green_bordered{border:1px solid #bed600; padding:10px; color:#333333; font-size:18px;}
		</style>
	</head>
	<body bgcolor="#cccccc">
		<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="1040px" style="width:100%; max-width:1040px; padding:0; background:#ffffff;">
			<tr>
				<td>
					<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url(); ?>assets/images/next-header2.jpg) left center no-repeat; background-size:cover;">
						<tr>
							<td valign="middle" width="430" style="padding:60px 30px 60px 50px;">
								<img src="<?php echo base_url(); ?>assets/images/nextlab-logo.jpg" alt="" style="max-height:100px; max-width:280px; border-radius:4px;" />
								<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;"><?php echo $this->lang->line('serum_test'); ?> <br><?php echo $this->lang->line('Treatment_advice'); ?></h5>
							</td>
							<td valign="middle"></td>
						</tr>
					</table>
					<table width="100%"><tr><td height="20"></td></tr></table>

					<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
						<tr>
							<td>
								<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('step_1_select_a_treatment_option'); ?></h4>
							</td>
							<td align="right">
								<p style="margin:0; color:#333333; font-size:13px;"><?php echo $this->lang->line('testnumber_30310429_client_number_15850'); ?></p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="20"></td></tr></table>

					<table cellspacing="0" cellpadding="0" border="0" width="272px" align="left" style="margin-left:30px; min-width:272px;">
						<tr>
							<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><input type="checkbox" /> &nbsp;
							 <?php echo $this->lang->line('Treatment_option');?>1</th>
						</tr>
						<tr>
							<td bgcolor="#e2f2f4" style="padding:20px;">
								<p style="color:#184359; font-size:13px; margin:0; padding:0;"><?php echo $this->lang->line('dual_allergens'); ?></p>
								<ol style="color:#184359; font-size:13px; margin:15px 0 0 20px; padding:0;">
									<li><?php echo $this->lang->line('willow'); ?></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
								</ol>
							</td>
						</tr>
						<tr>
							<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
								<table cellpadding="0" cellspacing="0" border="0" width="100%;">
									<tr>
										<td height="20"></td>
									</tr>
									<tr>
										<th colspan="3" align="left" style="color:#303846;">
										<?php echo $this->lang->line('option_results'); ?></th>
									</tr>
									<tr>
										<td width="30%"><input type="text" placeholder="" style="background:#e4eaed; padding:0 10px; height:40px; border:1px solid #4d5d67; width:60px;" /></td>
										<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('subcutaneous'); ?> <br><?php echo $this->lang->line('immuno_therapy'); ?> </td>
										<td width="30%"><img src="<?php echo base_url(); ?>assets/images/needle.png" alt="" width="60px" /></td>
									</tr>
									<tr>
										<td height="40"></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" width="272px" align="left" style="margin-left:30px; min-width:272px;">
						<tr>
							<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><input type="checkbox" /> &nbsp; 
							<?php echo $this->lang->line('Treatment_option');?>2</th>
						</tr>
						<tr>
							<td bgcolor="#e2f2f4" style="padding:20px;">
								<p style="color:#184359; font-size:13px; margin:0; padding:0;"><?php echo $this->lang->line('alternative_treatment_option_1'); ?></p>
								<ol style="color:#184359; font-size:13px; margin:15px 0 0 20px; padding:0;">
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
								</ol>
							</td>
						</tr>
						<tr>
							<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
								<table cellpadding="0" cellspacing="0" border="0" width="100%;">
									<tr><td height="20"></td></tr>
									<tr>
										<th colspan="3" align="left" style="color:#303846;">	<?php echo $this->lang->line('option_results'); ?></th>
									</tr>
									<tr>
										<td width="30%"><input type="text" placeholder="" style="background:#e4eaed; padding:0 10px; height:40px; border:1px solid #4d5d67; width:60px;" /></td>
										<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('subcutaneous'); ?>
 <br><?php echo $this->lang->line('immuno_therapy'); ?>  </td>
										<td width="30%"><img src="<?php echo base_url(); ?>assets/images/needle.png" alt="" width="60px" /></td>
									</tr>
									<tr>
										<td height="40"></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" width="272px" align="left" style="margin-left:30px; min-width:304px;">
						<tr>
							<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><input type="checkbox" /> &nbsp; 	<?php echo $this->lang->line('compose_it_yourself'); ?></th>
						</tr>
						<tr>
							<td bgcolor="#e2f2f4" style="padding:20px;">
								<p style="color:#184359; font-size:13px; margin:0; padding:0;">	<?php echo $this->lang->line('own_therapy_based'); ?></p>
								<textarea style="resize:none; background:#e4eaed; padding:10px; height:330px; border:1px solid #4d5d67; width:70px; width:100%; box-sizing:border-box; margin:20px 0 0 0; outline:none;"></textarea>
							</td>
						</tr>
						<tr>
							<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
								<table cellpadding="0" cellspacing="0" border="0" width="100%;">
									<tr>
										<td height="20"></td>
									</tr>
									<tr>
										<td><p style="color:#184359; font-size:13px; margin:0; padding:0;"><?php echo $this->lang->line('artuvetrin_subcutaneous_immunotherapy'); ?> 
</p></td>
									</tr>
									<tr>
										<td height="40"></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="30"></td></tr></table>

					<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url(); ?>assets/images/next-header2.jpg) left center no-repeat; background-size:cover;">
						<tr>
							<td valign="middle" width="430" style="padding:60px 30px 60px 50px;">
								<img src="<?php echo base_url(); ?>assets/images/nextlab-logo.jpg" alt="" style="max-height:100px; max-width:280px; border-radius:4px;" />
								<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;"><?php echo $this->lang->line('serum_test'); ?> 
 <br><?php echo $this->lang->line('Treatment_advice'); ?> 
</h5>
							</td>
							<td valign="middle"></td>
						</tr>
					</table>
					<table width="100%"><tr><td height="30"></td></tr></table>

					<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
						<tr>
							<td>
								<h4 style="margin:0; color:#2a5b74; font-size:24px;">
							<?php echo $this->lang->line('step_3_starting_the_treatment'); ?> </h4>
								<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;"><?php echo $this->lang->line('frequently_asked_questions'); ?> </p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="30"></td></tr></table>

					<table width="100%">
						<tr>
							<td style="padding:0 30px;">
								<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="47%">
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q1'); ?> </h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a1'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td colspan="2">
											<table align="center" width="360">
												<tr bgcolor="#326883">
													<th align="left" height="25" style="color:#ffffff; font-size:13px; padding:0 0 0 20px;"><?php echo $this->lang->line('adviced_schedule'); ?> 
</th>
													<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;"><?php echo $this->lang->line('dosage'); ?> 
</th>
												</tr>
												<tr bgcolor="#326883">
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('week_1'); ?> 
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('0_2_ml'); ?> 
</td>
												</tr>
												<tr bgcolor="#326883">
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('2_weeks_later_week_3'); ?> 
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('0_4_ml'); ?> 
</td>
												</tr>
												<tr bgcolor="#326883">
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('2_weeks_later_week_5'); ?> 
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('0_6_ml'); ?> 
</td>
												</tr>
												<tr bgcolor="#326883">
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('2_weeks_later_week_7'); ?> 
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('0_8_ml'); ?> 
</td>
												</tr>
												<tr bgcolor="#326883">
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('3_weeks_later_week_10'); ?> 
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('1_0_ml'); ?> 
</td>
												</tr>
												<tr bgcolor="#326883">
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('3_weeks_later_week_13'); ?> 
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('1_0_m'); ?> 
</td>
												</tr>
												<tr bgcolor="#326883">
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('4_weeks_later_week_17'); ?> 
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('1_0_m'); ?> 
</td>
												</tr>
												<tr bgcolor="#326883">
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('4_weeks_later_week_21'); ?> 
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('1_0_m'); ?> 
</td>
												</tr>
												<tr bgcolor="#326883">
													<td colspan="2" align="center" bgcolor="#b8c6d6" style="padding:15px; font-size:12px; color:#1f4964;"><?php echo $this->lang->line('positive_a2');?> 
</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td colspan="2">
											<table style="border:1px solid #9bd4dc; border-radius:0 10px 10px 10px; padding:15px;" width="100%">
												<tr>
													<td>
														<p style="margin:0 0 4px 0; padding:0; color:#1b3856; font-size:14px;"><?php echo $this->lang->line('positive_a2a'); ?></p>
														
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q3'); ?></h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('contact_our_medical_department_2'); ?>  </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q4'); ?>  </h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q4'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q5'); ?> </h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('if_the_patient_did_not_show_any_improvement_at_all_after_12_months_please_contact_our_medical_department_on_+_31_320_783_100_there_can_be_several_reasons_for_a_0%_response_concomitant_food_allergy_reaction_to_new_allergens_or_not_effective_we_are_happy_to_evaluate_each_case_and_help_you_with_the_relevant_follow_up'); ?> </p></td>
									</tr>
								</table>
								<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="47%">
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('administer_only'); ?> </h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('administered_continuously_and_lifelong'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q7'); ?> </h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q7'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q8'); ?> </h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q8'); ?> </p></td>						
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q9'); ?> </h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('in_general_improvement_can_be_noticed_after_4_to_6_months_in_some_cases_after_1_month_if_there_is_no_improvement_at_all_after_6_months_please_contact_us_at_+31_320_783_100_we_are_happy_to_help_you_with_your_case'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q10'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2">
											<p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q10'); ?></p>
											<ul style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">
												<li><?php echo $this->lang->line('positive_faq_option1'); ?></li>
												<li><?php echo $this->lang->line('positive_faq_option2'); ?></li>
												<li><?php echo $this->lang->line('positive_faq_option3'); ?></li>
											</ul>
											<p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"> <?php echo $this->lang->line('positive_faq_option4'); ?></p>
										</td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q11'); ?> </h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_option5'); ?> </p></td>						
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="30"></td></tr></table>

					<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url(); ?>assets/images/next-header2.jpg) left center no-repeat; background-size:cover;">
						<tr>
							<td valign="middle" width="430" style="padding:60px 30px 60px 50px;">
								<img src="<?php echo base_url(); ?>assets/images/nextlab-logo.jpg" alt="" style="max-height:100px; max-width:280px; border-radius:4px;" />
								<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;"><?php echo $this->lang->line('serum_test'); ?>  <br><?php echo $this->lang->line('Treatment_advice'); ?> </h5>
							</td>
							<td valign="middle"></td>
						</tr>
					</table>
					<table width="100%"><tr><td height="30"></td></tr></table>

					<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
						<tr>
							<td>
								<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('about_next_+'); ?> </h4>
								<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;"><?php echo $this->lang->line('frequently_asked_questions'); ?> </p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="30"></td></tr></table>

					<table width="100%">
						<tr>
							<td style="padding:0 30px;">
								<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="47%">
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('posi_immunotherapy'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('high_number_of_positive'); ?></p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('ex_to_the_posi_allergens'); ?></h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('brochure_contains_tips'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('what_if_malassezia_is_positive'); ?> </h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('malassezia_secondary_problem'); ?></p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('what_if_moulds_are_positive'); ?></h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('moulds_may_be_only_clinically'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('tested_positive'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('IV_hypersensitivity_reaction_as_immunotherapy'); ?></p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;">
										<?php echo $this->lang->line('correlate_clinical_signs'); ?></h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('necessarily_correlate'); ?></p><td>						
									</tr>
									<tr><td height="30"></td></tr>

									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
										<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('frequently_asked_questions'); ?></h6></td>	
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('oral_medication'); ?></p></td>
									</tr>
								</table>
								<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="47%">
									<tr>
										<td>
											<table style="background:#edf2f4; padding:20px; border-radius:10px;">
												<tr>
													<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
													<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('what_are_CCDs'); ?></h6></td>	
												</tr>
												<tr>
													<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('reactive_carbohydrate_determinant'); ?></p></td>
												</tr>

												<tr><td height="30"></td></tr>
												<tr>
													<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
													<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('how_are_CCDs_involved_in_the_allergic_reaction'); ?></h6></td>
												</tr>
												<tr>
													<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('the_allergen_proteins_studies'); ?></p></td>
												</tr>
												<tr><td height="30"></td></tr>

												<tr>
													<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
													<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('why_is_it_important_to_block_CCDs'); ?></h6></td>	
												</tr>
												<tr>
													<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('blocking_CCDs'); ?><sup>3</sup>.</p></td>	
												</tr>
											</table>
										</td>
									</tr>
									<tr><td height="30"></td></tr>

									<tr>
										<td height="30">
											<table align="center" width="460">
												<tr bgcolor="#326883">
													<th align="left" height="45" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;"><?php echo $this->lang->line('Allergens'); ?>
</th>
													<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;"><?php echo $this->lang->line('without'); ?>
<br><?php echo $this->lang->line('CCD_blocker'); ?>
</th>
													<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;"><?php echo $this->lang->line('without'); ?><br><?php echo $this->lang->line('CCD_blocker'); ?></th>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('phleum_pratense'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?>
</td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('poa_pratensis'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?>
</td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('dactylis_glomerata'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('486_0'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?>
</td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('lolium_perenne'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?>
</td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('rumex_acetosella'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?>
</td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('urtica_spp'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('486_0'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('375_0'); ?>
</td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('chenopodium_album'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('486_0'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?>
</td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('artemisa_vulgaris'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?>
</td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?>
</td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('ambrosia_eliator'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('betula_pendula'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('486_0'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('corylus_avellana'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('salix_viminalis'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('ulmus_americana'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
												</tr>
												<tr>
													<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><strong><?php echo $this->lang->line('positive_allergens'); ?></strong></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><strong><?php echo $this->lang->line('486_0'); ?>
</strong></td>
													<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><strong><?php echo $this->lang->line('375_0'); ?>
</strong></td>
												</tr>
												<tr>
													<td colspan="3" bgcolor="#ffffff" style="padding:15px 0 15px 0; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('figure_1'); ?></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="30"></td></tr></table>

					<table width="100%"><tr><td height="30"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 30px 30px 30px;" >	
						<tr>
							<td style="">
								<h5 style="margin:0 0 3px 0; padding:0; color:#326883; font-size:15px;"><?php echo $this->lang->line('do_you_have_any_additional_questions'); ?></h5>
								<p style="margin:0 0 0 0; padding:0; color:#326883; font-size:13px;"><?php echo $this->lang->line('please_call_our_medical_department_on_+_31_320_783_100_or_send_an_email_to_info_eu@nextmune_com'); ?></p>
							</td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td style="padding:0 0 0 20px;">
								<ol style="color:#19455c; margin:0; padding:0; font-size:12px; line-height:20px;">
									<li><?php echo $this->lang->line('ubiquitous_structures_responsible'); ?></li>
									<li><?php echo $this->lang->line('vitro_diagnosis_of_allergic_diseases'); ?></li>
									<li><?php echo $this->lang->line('gedon_NKY_et_al_agreement'); ?></li>
								</ol>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>