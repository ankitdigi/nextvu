<!DOCTYPE html>
<html class="js">
	<head>
		<title><?php echo $this->lang->line('pax_scr_serum_test_result'); ?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page{margin:0}
		*{font-family:'calibri',sans-serif}
		table{font-family:'calibri'}
		table {page-break-inside: avoid;}
		table tr {page-break-inside: avoid;}
		div{font-family:'calibri'}
		.header th{text-align:left; padding-right: 5px;}
		.green_strip{background:#bed600;padding:5px 10px;color:#fff;font-size:18px}
		.green_bordered{border:1px solid #bed600;padding:10px;color:#333;font-size:18px}
		.blob1{background:#becedb}
		.blob2{background:#9acfdb}
		.blob3{background:#59c0d3}
		.blob4{background:#366784}
		.blob5{background:#273945}
		.light-grey-bg{background:#f2f5f8}
		.grey-bg{background:#becedb}
		.aqua-bg{background:#9acfdb}
		.cgreen-bg{background:#366784}
		.cgreen-dark-bg{background:#273945}
		.meter{min-width:120px;max-width:120px}
		.meter tr td{font-size:13px;line-height:11px}
		.meter tr td:nth-child(2){border-radius:10px 0 0 10px}
		.meter tr td:last-child{border-radius:0 10px 10px 0}
		.index-capsule{width:140px;height:24px;border-radius:40px 40px 40px 40px}
		.light-lemon-bg{background:#eaf4e3}
		.lemon-bg{background:#9fd08a}
		.military-bg{background:#666c3e}
		.red-bg{background:#d1232a}
		.mehroon-bg{background:#b02a2f}
		.green-bg{background:#40ae49}
		.orange-bg{background:#f58220}
		.diets tr th{font-weight:400;font-size:13px;padding:20px 5px 10px;width:20px;border-left:1px solid #9acfdb;border-bottom:3px solid #9acfdb;position:relative;height:100px;text-align:left}
		.diets tr th:first-child{white-space:nowrap;font-weight:700;font-size:18px}
		.diets tr th:first-child,.diets tr td:first-child{border-left:0}
		.diets tr td:first-child{text-align:left}
		.diets tr th .rotate{transform:rotate(270deg);display:block;transform-origin:center bottom;text-align:left;position:absolute;white-space:nowrap;left:2px;bottom:40px;text-align:left}
		.diets tr td{border-left:1px solid #9acfdb;border-bottom:1px solid #9acfdb;font-size:13px;text-align:center;padding:5px}
		.filled-checkbox{display: none;}
		</style>
	</head>
	<body bgcolor="#fff">
		<?php 
		if($order_details['pax_cutoff_version'] == 1){
			$cutoffs = '30';
		}else{
			$cutoffs = '28';
		}
		if($order_details['vet_user_id']>0){
			$refDatas = $this->UsersDetailsModel->getColumnAllArray($order_details['vet_user_id']);
			$refDatas = array_column($refDatas, 'column_field', 'column_name');
			$add_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'].', ' : '';
			$add_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'].', ' : '';
			$add_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
			$city = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
			$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
			$fulladdress = $add_1.$add_2.$add_3.$city.$postcode;
		}else{
			$fulladdress = '';
		}

		$serumdata = $this->OrdersModel->getSerumTestRecord($id);
		$respnedn = $this->OrdersModel->getProductInfo($order_details['product_code_selection']);
		$ordeType = $respnedn->name;
		$ordeTypeID = $respnedn->id;

		/* Food */
		$getFAllergenParent = $this->AllergensModel->getFoodAllergenParentbyName($order_details['allergens']);
		$totalfGroup = count($getFAllergenParent);
		$totalf1Group = $totalfGroup/2;
		$partPF = ((round)($totalf1Group));
		$partF = $partPF;

		$allengesIDFArr = array(); $dummyFtext = ""; $foodtotal = 0;
		$subAllergnFArr = $this->AllergensModel->getFoodAllergensByID($order_details['allergens']);
		if(!empty($subAllergnFArr)){
			$allengesFArr = []; $allengesF3Arr = []; $allengesF4Arr = []; $foodtotal = 0;
			foreach ($subAllergnFArr as $svalue){
				$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
				if(!empty($subVlu->raptor_code)){
					$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
					if(!empty($raptrVlu)){
						if(floor($raptrVlu->result_value) >= $cutoffs){
							if($svalue['name'] != "N/A"){
								$allengesF3Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
							}else{
								$allengesF4Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
							}
							$allengesFArr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
							$allengesIDFArr[] = $svalue['id'];
							$foodtotal++;
						}
					}
				}
			}

			if(count($allengesFArr) > 1){
				$lastchnk = end($allengesFArr);
				$lastchnkName = ', '.$lastchnk;
				$lastchnkCng = ' and '.$lastchnk;
				$allengesStr = implode(", ",$allengesFArr);
				$allengeStr = str_replace($lastchnkName,$lastchnkCng,$allengesStr);
			}else{
				$allengeStr = implode(", ",$allengesFArr);
			}

			if(count($allengesF3Arr) > 1){
				$last3chnk = end($allengesF3Arr);
				$lastchnk3Name = ', '.$last3chnk;
				$lastchnk3Cng = ' and '.$last3chnk;
				$allenges3Str = implode(", ",$allengesF3Arr);
				$allenge3Str = str_replace($lastchnk3Name,$lastchnk3Cng,$allenges3Str);
			}else{
				$allenge3Str = implode(", ",$allengesF3Arr);
			}

			if(count($allengesF4Arr) > 1){
				$last4chnk = end($allengesF4Arr);
				$lastchnk4Name = ', '.$last4chnk;
				$lastchnk4Cng = ' and '.$last4chnk;
				$allenges4Str = implode(", ",$allengesF4Arr);
				$allenge4Str = str_replace($lastchnk4Name,$lastchnk4Cng,$allenges4Str);
			}else{
				$allenge4Str = implode(", ",$allengesF4Arr);
			}

			$dummyFtext .= "This patient is sensitized to ". $allengeStr .".";
			$dummyFtext .= "\n\r";
			$dummyFtext .= "If the corresponding clinical signs occur, allergen-specific immunotherapy is recommended for: ". $allenge3Str .".";
			$dummyFtext .= "\n\r";
			$dummyFtext .= "Allergen-specific immunotherapy is currently not available for ". $allenge4Str ."; the treatment is symptomatic.";
			$dummyFtext .= "\n\r";
			$dummyFtext .= "Please find full interpretation and detailed results per allergen extract and component in the following pages.";
			$dummyFtext .= "\n\r";
			$dummyFtext .= "Based on these results we recommend the following immunotherapy composition(s):";
			$dummyFtext .= "\n\r";
		}
		$this->db->select('result_value');
		$this->db->from('ci_raptor_result_allergens');
		$this->db->where('result_id',$raptorData->result_id);
		$this->db->where('name LIKE','Cte f 1');
		$this->db->order_by('result_value', 'DESC');
		$fleaResults = $this->db->get()->row();

		if($order_details['species_name'] == 'Horse'){
			$speciesName = $this->lang->line('horse');
		}elseif($order_details['species_name'] == 'Cat'){
			$speciesName = $this->lang->line('cat');
		}else{
			$speciesName = $this->lang->line('dog');
		}
		?>
		<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%; padding-top: 10mm; border-bottom:2px solid #366784;">
			<tr>
		        <td width="100%">
		            <table width="100%" align="left" cellspacing="0" cellpadding="0" border="0" style="padding-bottom: 3mm; margin: 0 12mm;">
                    	<tbody>
                    		<tr>
                        		<td valign="top" width="50%">
                        			<table width="100%" cellspacing="0" cellpadding="0" border="0">
                        				<tbody>
                        					<tr>
                        						<td width="100%">
                        							<img src="assets/images/pax-logo.png" alt="Logo" style="max-height:100px; max-width:300px;" />
                        						</td>
                        					</tr>
                        					<tr>
                        						<td width="100%" style="height: 100px;"></td>
                        					</tr>
                        					<tr>
                        						<td valign="bottom" style="color:#31688a; font-size:17px;">
													<b><?php echo $this->lang->line('pax_food_scr'); ?></b>
												</td>
                        					</tr>
                        				</tbody>
                        			</table>
								</td>
                        		<td width="50%" valign="top" align="left">
									<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:10px; line-height:15px;font-family:'calibri'; text-align: left;">
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
											<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
											<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('Species'); ?>:</th>
											<td style="color:#000000;"><?php echo $speciesName;?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;vertical-align: baseline;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
											<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
										</tr>
										<?php if($order_details['vet_user_id'] != '24927'){ ?>
										<tr>
											<th style="color:#1e3743;vertical-align: baseline;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
											<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
											<td style="color:#000000;"><?php echo $fulladdress; ?></td>
										</tr>
										<?php } ?>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
											<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('Email'); ?>:</th>
											<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
											<td style="color:#000000;"><?php echo $ordeType; ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
											<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('lab_number'); ?></th>
											<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('order_number'); ?>:</th>
											<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
										</tr>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
											<td style="color:#000000;"><?php echo $account_ref; ?></td>
										</tr>
										<?php if($order_details['lab_id'] > 0){ ?>
										<tr>
											<th style="color:#1e3743;"><?php echo $this->lang->line('Lab'); ?>:</th>
											<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
										</tr>
										<?php } ?>
									</table>
								</td>
                    		</tr>
                		</tbody>
                	</table>
		        </td>
		    </tr>
		</table>
		<table width="100%"><tr><td height="5"></td></tr></table>
		<?php if($foodtotal == 0){ ?>
			<table cellspacing="0" cellpadding="0" border="0" width="100%" style="padding: 2mm 12mm 0mm;">
				<tr>
					<td width="100%">
						<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="48%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tr>
												<td width="100%">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td>
																	<img src="<?php echo base_url(); ?>/assets/images/top-border-radius.png" width="340">
																</td>
															</tr>
															<tr>
																<td style="background:#326883; padding: 0 8px 7px; color:#ffffff; font-size:10px;">
																	<b><?php echo $this->lang->line('food_screen_extracts_and_components'); ?></b>
																</td>
															</tr>
															<tr>
																<td valign="top" style="background:#e2f2f4; padding:10px 8px 0; height: 85px;">
																	<table cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="color:#1b3542; font-weight:400; font-size:11px;"><?php echo $this->lang->line('screening_results'); ?>:
																			</td>
																			<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b><?php echo $this->lang->line('Negative_n'); ?></b></td>
																		</tr>
																		<tr>
																			<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;"><?php echo $this->lang->line('food_negative_result'); ?></td>
																		</tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td>
																	<img src="<?php echo base_url(); ?>/assets/images/bottom-border-radius.png" width="340">
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</table>
									</td>
									<td width="4%">&nbsp;</td>
									<td valign="top" width="48%">&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
			<table><tr><td width="100%" style="height:30px;"></td></tr></table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0mm 12mm;">
				<tr>
					<td width="100%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
							<tbody>
								<tr>
									<td style="color:#2a5b74; font-size:16px;"><?php echo $this->lang->line('faq_title'); ?></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" style="height: 10px;"></td>
				</tr>
				<tr>
					<td width="100%">
						<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td valign="top" width="48%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="28">
														<img src="<?php echo base_url(); ?>assets/images/question.png" alt="" width="20" />
													</td>
													<td style="color:#1e3743; font-size:12px;">
														<b><?php echo $this->lang->line('negative_result_title3'); ?></b>
													</td>
												</tr>
												<tr><td height="5"></td></tr>
												<tr>
													<td colspan="2" style="color:#1e3743; font-size:11px; line-height: 16px;">
													<?php echo $this->lang->line('negative_a1_food'); ?>
													</td>
												</tr>
												<tr><td height="15"></td></tr>
												<tr>
													<td width="28" style="line-height:0;">
														<img src="<?php echo base_url(); ?>assets/images/question.png" alt="" width="20" />
													</td>
													<td style="color:#1e3743; font-size:12px;">
														<b><?php echo $this->lang->line('negative_q2'); ?></b>
													</td>
												</tr>
												<tr><td height="5"></td></tr>
												<tr>
													<td colspan="2" style="color:#1e3743; font-size:11px; line-height: 16px;">
													<?php echo $this->lang->line('cross_reactivities_raptor_pdf_food'); ?>	
													</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td valign="top" width="4%">&nbsp;</td>
									<td valign="top" width="48%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<?php if($this->session->userdata('site_lang') != 'spanish'){ ?>
												<tr>
													<td width="28">
														<img src="<?php echo base_url(); ?>assets/images/question.png" alt="" width="20" />
													</td>
													<td style="color:#1e3743; font-size:12px;">
														<b><?php echo $this->lang->line('negative_q4'); ?></b>
													</td>
												</tr>
												<tr><td height="5"></td></tr>
												<tr>
													<td colspan="2" style="color:#1e3743; font-size:11px; line-height: 16px;">
													<?php echo $this->lang->line('There_is_currently_no_evidence_suggesting_that_such_approach_would_be_useful_Exceptionally_in_rare_cases_in_which_a_natural_provocation_suggests_the_relevance_of_a_unique_environmental_allergen'); ?>
													</td>
												</tr>
												<tr><td height="15"></td></tr>
												<?php } ?>
												<tr>
													<td width="28" style="line-height:0;">
														<img src="<?php echo base_url(); ?>assets/images/question.png" alt="" width="20" />
													</td>
													<td style="color:#1e3743; font-size:12px;">
														<b><?php echo $this->lang->line('can_symptomatic_medication_give_anegative_result'); ?></b>
													</td>
												</tr>
												<tr><td height="5"></td></tr>
												<tr>
													<td colspan="2" style="color:#1e3743; font-size:11px; line-height: 16px;">
													<?php echo $this->lang->line('short_term_raptor_scre_food'); ?>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr><td height="50px"></td></tr>
				<tr>
					<td width="100%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="50%">&nbsp;</td>
													<td width="50%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="100%" style="color:#31688a; font-size:12px;">
																						<b><?php echo $this->lang->line('do_you_need_any_help'); ?></b>
																					</td>
																				</tr>
																				<tr>
																					<td width="100%" style="height: 2px; line-height: 2px;"></td>
																				</tr>
																				<tr>
																					<td width="100%" style="color:#203548; font-size:11px; line-height: 16px;">
																					<?php echo $this->lang->line('please_contact_our_veterinary_support_team_by_phone_+01494_629979_or_by_email'); ?> <a style="color:#203548;" href="mailto:<?php echo $this->lang->line('contact_email'); ?>"><?php echo $this->lang->line('contact_email'); ?></a>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		<?php }else{ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 1mm 12mm 2mm;">
				<tbody>
					<tr>
						<td width="100%">
							<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr>
										<td valign="top" width="48%">
											<table width="100%" cellspacing="0" cellpadding="0" border="0">
												<tbody>
													<tr>
														<td>
															<img src="<?php echo base_url(); ?>/assets/images/top-border-radius.png" width="340">
														</td>
													</tr>
													<tr>
														<td style="background:#326883; padding: 0 8px 7px; color:#ffffff; font-size:10px;"><b><?php echo $this->lang->line('food_screen_extracts_and_components'); ?></b></td>
													</tr>
													<tr>
														<td valign="top" style="background:#e2f2f4; padding:10px 8px 0; height: 75px;">
															<table cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td style="color:#1b3542; font-weight:400; font-size:11px;"><?php echo $this->lang->line('screening_results'); ?>:
																	</td>
																	<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b><?php echo $this->lang->line('positive_2'); ?></b></td>
																</tr>
																<tr>
																	<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;"><?php echo $this->lang->line('patient_anti_foods_tested'); ?></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td>
															<img src="<?php echo base_url(); ?>/assets/images/bottom-border-radius.png" width="340">
														</td>
													</tr>
												</tbody>
											</table>
										</td>
										<td valign="top" width="4%">&nbsp;</td>
										<td valign="top" width="48%">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 1mm 12mm 0mm;">
				<tbody>
					<tr>
						<td width="100%">
							<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="margin: 0px auto;">
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0mm 0.5mm 2mm; margin: 10px 0px 0;  background-image: url(<?php echo base_url(); ?>assets/images/question-pdf-bg-img-new.png); background-repeat: no-repeat; background-position: left top; background-size: 100%;">
											<tbody>
												<tr>
													<td width="100%" style="padding: 47px 15px 7px 40px;">
														<table width="100%">
															<tbody>
																<tr>
																	<td style="color:#1e3743; font-size:12px;">
																		<b><?php echo $this->lang->line('positive_for_allergens_what_now'); ?></b>
																	</td>
																</tr>
																<tr>
																	<td style="color:#1e3743; font-size:11px; line-height: 16px;">
																	<?php echo $this->lang->line('next_step_ex_scr_raptor_food'); ?>	
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="padding: 0px 15px 37px 40px;">
														<table width="100%">
															<tbody>
																<tr>
																	<td style="color:#1e3743; font-size:12px;">
																		<b><?php echo $this->lang->line('do_i_need_to_send_new_serum_to_expand_the_results'); ?></b>
																	</td>
																</tr>
																<tr>
																	<td style="color:#1e3743; font-size:11px; line-height: 16px;">
																	<?php echo $this->lang->line('no_it_is_not_necessary_to_send_new_serum'); ?>
																	</td>
																</tr>
																<tr>
																	<td style="color:#1e3743; font-size:11px; line-height: 16px;">
																	<?php echo $this->lang->line('to_expand_the_screen_simply_complete_the_boxes_below_and_send_via_email_once_we_receive_your_request_we_will_report_the_expanded_results_to_you_within_few_days_via_email'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="100%" style="height: 25px;"></td>
					</tr>
					<tr>
						<td width="100%">
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr>
										<td width="100%">
											<table class="" cellspacing="0" cellpadding="0" border="0" align="center" style="width:35%; padding:0;margin: 0px auto;">
												<tr>
													<td width="100%" align="center">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="32px" height="32px">
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/pax-blank-checkbox.png"); ?>" alt="NextVu"/>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/pax-filled-checkbox.png"); ?>" alt="NextVu"/>
																	</td>
																	<?php if($ordeType == 'PAX Environmental Screening'){ ?>
																	<td valign="middle" align="left" width="100%" style="color:#31688a; font-size: 14px; text-transform: uppercase; padding-left: 3mm;"><b><?php echo $this->lang->line('expand_pax_env_scr'); ?></b></td>
																	<?php }elseif($ordeType == 'PAX Food Screening'){ ?>
																	<td valign="middle" align="left" width="85%" style="color:#31688a; font-size: 14px; text-transform: uppercase; padding-left: 3mm;"><b><?php echo $this->lang->line('expand_pax_food_screen'); ?></b></td>
																	<?php }else{ ?>
																	<td valign="middle" align="left" width="85%" style="color:#31688a; font-size: 18px; text-transform: uppercase; padding-left: 3mm;"><b><?php echo $this->lang->line('pax_expand'); ?></b></td>
																	<?php } ?>
																</tr>
															</tbody>
														</table>
														
													</td>
												</tr>
												<tr>
													<td width="100%" style="height:10px;"></td>
												</tr>
												<tr>
													<td valign="middle" align="left" width="100%" style="color:#1b3542; font-size: 12px;"><?php echo $this->lang->line('i_would_like_to_expand_the_screening_results'); ?></td>
												</tr>
												<tr>
													<td width="100%" style="height:10px;"></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td width="100%">
											<table class="" cellspacing="0" cellpadding="0" border="0" align="center" style="width:50%; padding:0;margin: 0px auto;">
												<tr>
													<td width="100%" align="center">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td align="left" width="100%" style="font-size: 12px; color:#1b3542;"><?php echo $this->lang->line('date'); ?>:</td>
																</tr>
																<tr>
																	<td width="100%" style="border: 1px solid #316783; background-color: #edf2f4; height: 24px;"></td>
																</tr>
															</tbody>
														</table>
														
													</td>
												</tr>
												<tr>
													<td width="100%" style="height:5px;"></td>
												</tr>
												<tr>
													<td width="100%" align="center">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td align="left" width="100%" style="font-size: 12px; color:#1b3542;"><?php echo $this->lang->line('signature'); ?>:</td>
																</tr>
																<tr>
																	<td width="100%" style="border: 1px solid #316783; background-color: #edf2f4; height: 60px;"></td>
																</tr>
															</tbody>
														</table>
														
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td width="100%" style="height:50px;"></td>
					</tr>
					<tr>
						<td width="100%">
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr>
										<td width="100%">
											<table width="100%" cellspacing="0" cellpadding="0" border="0">
												<tbody>
													<tr>
														<td width="50%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-image: url(<?php echo base_url(); ?>assets/images/reliable-bg-img.png); background-repeat: no-repeat; background-position: left top; background-size: 100%;">
																<tbody>
																	<tr>
																		<td width="65%" style="padding: 45px 5px 25px 50px;">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" style="color:#1e3743; font-size:13px;">
																							<b><?php echo $this->lang->line('100%_reliable'); ?></b>
																						</td>
																					</tr>
																					<tr>
																						<td width="100%" style="height: 2px; line-height: 2px;"></td>
																					</tr>
																					<tr>
																						<td width="100%" style="color:#1e3743; font-size:11px; line-height: 15px;">
																						<?php echo $this->lang->line('exp_panels_scr_rpt'); ?> <?php echo $this->lang->line('have_100_corre'); ?> 
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="35%">&nbsp;</td>
																	</tr>
																</tbody>
															</table>
														</td>
														<td width="50%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tbody>
																	<tr>
																		<td width="100%" style="height: 10px; line-height: 10px;"></td>
																	</tr>
																	<tr>
																		<td width="100%">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" style="color:#31688a; font-size:12px;">
																							<b><?php echo $this->lang->line('do_you_need_any_help'); ?></b>
																						</td>
																					</tr>
																					<tr>
																						<td width="100%" style="height: 2px; line-height: 2px;"></td>
																					</tr>
																					<tr>
																						<td width="100%" style="color:#203548; font-size:11px; line-height: 16px;">
																						<?php echo $this->lang->line('please_contact_our_veterinary_support_team_by_phone_+01494_629979_or_by_email'); ?> <a style="color:#203548;" href="mailto:<?php echo $this->lang->line('contact_email'); ?>"><?php echo $this->lang->line('contact_email'); ?></a>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		<?php } ?>