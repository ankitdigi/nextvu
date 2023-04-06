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
		
		/* Environmental */
		$getEAllergenParent = $this->AllergensModel->getEnvAllergenParentbyName($order_details['allergens']);
		$totalGroup0 = count($getEAllergenParent);
		$totalGroup2 = $totalGroup0/2;
		$partA = ((round)($totalGroup2));
		$partB = $partA;

		$block1 = []; $blocks1 = []; $allengesIDsArr = array(); $dummytext = "";
		$subAllergnArr = $this->AllergensModel->getEnvAllergensByID($order_details['allergens']);
		if(!empty($subAllergnArr)){
			$allengesArr = []; $allenges3Arr = []; $allenges4Arr = [];
			foreach ($subAllergnArr as $svalue){
				$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
				if(!empty($subVlu->raptor_code)){
					$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
					if(!empty($raptrVlu)){
						if(round($raptrVlu->result_value) >= $cutoffs){
							if($svalue['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($svalue['id']) > 0){
								$block1[$svalue['id']] = $svalue['name'];
								$allenges3Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
							}else{
								$blocks1[$svalue['id']] = $svalue['name'];
								$allenges4Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
							}
							$allengesArr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
							$allengesIDsArr[] = $svalue['id'];
						}
					}
				}
			}

			if(count($allengesArr) > 1){
				$lastchnk = end($allengesArr);
				$lastchnkName = ', '.$lastchnk;
				$lastchnkCng = ' and '.$lastchnk;
				$allengesStr = implode(", ",$allengesArr);
				$allengeStr = str_replace($lastchnkName,$lastchnkCng,$allengesStr);
			}else{
				$allengeStr = implode(", ",$allengesArr);
			}

			if(count($allenges3Arr) > 1){
				$last3chnk = end($allenges3Arr);
				$lastchnk3Name = ', '.$last3chnk;
				$lastchnk3Cng = ' and '.$last3chnk;
				$allenges3Str = implode(", ",$allenges3Arr);
				$allenge3Str = str_replace($lastchnk3Name,$lastchnk3Cng,$allenges3Str);
			}else{
				$allenge3Str = implode(", ",$allenges3Arr);
			}

			if(count($allenges4Arr) > 1){
				$last4chnk = end($allenges4Arr);
				$lastchnk4Name = ', '.$last4chnk;
				$lastchnk4Cng = ' and '.$last4chnk;
				$allenges4Str = implode(", ",$allenges4Arr);
				$allenge4Str = str_replace($lastchnk4Name,$lastchnk4Cng,$allenges4Str);
			}else{
				$allenge4Str = implode(", ",$allenges4Arr);
			}

			$dummytext .= "This patient is sensitized to ". $allengeStr .".";
			$dummytext .= "\n\r";
			$dummytext .= "If the corresponding clinical signs occur, allergen-specific immunotherapy is recommended for: ". $allenge3Str .".";
			$dummytext .= "\n\r";
			$dummytext .= "Allergen-specific immunotherapy is currently not available for ". $allenge4Str ."; the treatment is symptomatic.";
			$dummytext .= "\n\r";
			$dummytext .= "Please find full interpretation and detailed results per allergen extract and component in the following pages.";
			$dummytext .= "\n\r";
			$dummytext .= "Based on these results we recommend the following immunotherapy composition(s):";
			$dummytext .= "\n\r";
		}
		if(array_key_exists("45994",$block1) && array_key_exists("73",$block1)){
			unset($block1['45994']);
		}elseif(array_key_exists("45994",$block1)){
			unset($block1['45994']);
			$block1['73'] = $this->AllergensModel->getAllergennameById(73);
		}
		if($order_details['treatment_1'] != "" && $order_details['treatment_1'] != "[]"){
			$subAllergnArr = $this->AllergensModel->getAllergensByID($order_details['treatment_1']);
			if(!empty($subAllergnArr)){
				foreach ($subAllergnArr as $svalue){
					$block1[$svalue['id']] = $svalue['name'];
				}
			}
		}
		asort($block1);

		$block2 = [];
		foreach($getEAllergenParent as $apvalue){
			$getGroupMixtures = $this->AllergensModel->getGroupMixturesbyParent($apvalue['parent_id']);
			if(!empty($getGroupMixtures)){
				$parentIdArr = [];
				foreach($getGroupMixtures as $mvalue){
					if($mvalue['mixture_allergens'] != "" && $mvalue['mixture_allergens'] != "null"){
						$parentIdArr[] = $mvalue['id'];
					}
				}
				if(!empty($parentIdArr)){
					if(count($parentIdArr) > 1){
						$emptyArr = [];
						foreach($parentIdArr as $makey=>$mavalue){
							$allergenArr = json_decode($getGroupMixtures[$makey]['mixture_allergens']);
							$testingArr = [];
							foreach($allergenArr as $amid){
								$rmcodes = $this->OrdersModel->getsubAllergensCode($amid);
								if(!empty($rmcodes->raptor_code)){
									$raptrmVlu = $this->OrdersModel->getRaptorValue($rmcodes->raptor_code,$raptorData->result_id);
									if(!empty($raptrmVlu)){
										if(round($raptrmVlu->result_value) >= $cutoffs){
											$testingArr[$mavalue] += 1;
										}
									}
								}
							}

							if($testingArr[$mavalue] >= 2){
								if($getGroupMixtures[$makey]['name'] != "N/A"){
									$block2[$getGroupMixtures[$makey]['id']] = $getGroupMixtures[$makey]['name'];
								}
								foreach(json_decode($getGroupMixtures[$makey]['mixture_allergens']) as $emtrow){
									$emptyArr[$apvalue['parent_id']][] = $emtrow;
								}
							}
						}

						if(!empty($emptyArr[$apvalue['parent_id']])){
							$sub1Allergens = $this->AllergensModel->get_subAllergens_dropdown_empty($apvalue['parent_id'],$order_details['allergens'], $emptyArr[$apvalue['parent_id']]);
							foreach($sub1Allergens as $s1value){
								$sub1Vlu = $this->OrdersModel->getsubAllergensCode($s1value['id']);
								if(!empty($sub1Vlu->raptor_code)){
									$raptr1Vlu = $this->OrdersModel->getRaptorValue($sub1Vlu->raptor_code,$raptorData->result_id);
									if(!empty($raptr1Vlu)){
										if(round($raptr1Vlu->result_value) >= $cutoffs){
											if($s1value['name'] != "N/A"){
												$block2[$s1value['id']] = $s1value['name'];
											}
										}
									}
								}
							}
						}else{
							$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $order_details['allergens']);
							foreach($sub2Allergens as $s2value){
								$sub2Vlu = $this->OrdersModel->getsubAllergensCode($s2value['id']);
								if(!empty($sub2Vlu->raptor_code)){
									$raptr2Vlu = $this->OrdersModel->getRaptorValue($sub2Vlu->raptor_code,$raptorData->result_id);
									if(!empty($raptr2Vlu)){
										if(round($raptr2Vlu->result_value) >= $cutoffs){
											if($s2value['name'] != "N/A"){
											$block2[$s2value['id']] = $s2value['name'];
											}
										}
									}
								}
							}
						}
					}else{
						$allergensArr = json_decode($getGroupMixtures[0]['mixture_allergens']);
						$tested = 0;
						foreach($allergensArr as $aid){
							$rcodes = $this->OrdersModel->getsubAllergensCode($aid);
							if(!empty($rcodes->raptor_code)){
								$raptrVlu = $this->OrdersModel->getRaptorValue($rcodes->raptor_code,$raptorData->result_id);
								if(!empty($raptrVlu)){
									if(round($raptrVlu->result_value) >= $cutoffs){
										$tested++;
									}
								}
							}
						}

						if($tested >= 2){
							if($getGroupMixtures[0]['name'] != "N/A"){
							$block2[$getGroupMixtures[0]['id']] = $getGroupMixtures[0]['name'];
							}
							$sub1Allergens = $this->AllergensModel->get_subAllergens_dropdown2($getGroupMixtures[0]['parent_id'],$order_details['allergens'], $getGroupMixtures[0]['mixture_allergens']);
							foreach($sub1Allergens as $s1value){
								$sub1Vlu = $this->OrdersModel->getsubAllergensCode($s1value['id']);
								if(!empty($sub1Vlu->raptor_code)){
									$raptr1Vlu = $this->OrdersModel->getRaptorValue($sub1Vlu->raptor_code,$raptorData->result_id);
									if(!empty($raptr1Vlu)){
										if(round($raptr1Vlu->result_value) >= $cutoffs){
											if($s1value['name'] != "N/A"){
											$block2[$s1value['id']] = $s1value['name'];
											}
										}
									}
								}
							}
						}else{
							$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $order_details['allergens']);
							foreach($sub2Allergens as $s2value){
								$sub2Vlu = $this->OrdersModel->getsubAllergensCode($s2value['id']);
								if(!empty($sub2Vlu->raptor_code)){
									$raptr2Vlu = $this->OrdersModel->getRaptorValue($sub2Vlu->raptor_code,$raptorData->result_id);
									if(!empty($raptr2Vlu)){
										if(round($raptr2Vlu->result_value) >= $cutoffs){
											if($s2value['name'] != "N/A"){
											$block2[$s2value['id']] = $s2value['name'];
											}
										}
									}
								}
							}
						}
					}
				}else{
					$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $order_details['allergens']);
					foreach($sub2Allergens as $s2value){
						$sub2Vlu = $this->OrdersModel->getsubAllergensCode($s2value['id']);
						if(!empty($sub2Vlu->raptor_code)){
							$raptr2Vlu = $this->OrdersModel->getRaptorValue($sub2Vlu->raptor_code,$raptorData->result_id);
							if(!empty($raptr2Vlu)){
								if(round($raptr2Vlu->result_value) >= $cutoffs){
									if($s2value['name'] != "N/A"){
									$block2[$s2value['id']] = $s2value['name'];
									}
								}
							}
						}
					}
				}
			}else{
				$sub3Allergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
				foreach($sub3Allergens as $s3value){
					$sub3Vlu = $this->OrdersModel->getsubAllergensCode($s3value['id']);
					if(!empty($sub3Vlu->raptor_code)){
						$raptr3Vlu = $this->OrdersModel->getRaptorValue($sub3Vlu->raptor_code,$raptorData->result_id);
						if(!empty($raptr3Vlu)){
							if(round($raptr3Vlu->result_value) >= $cutoffs){
								if($s3value['name'] != "N/A"){
								$block2[$s3value['id']] = $s3value['name'];
								}
							}
						}
					}
				}
			}
		}
		if(array_key_exists("45994",$block2) && array_key_exists("73",$block2)){
			unset($block2['45994']);
		}elseif(array_key_exists("45994",$block2)){
			unset($block2['45994']);
			$block2['73'] = $this->AllergensModel->getAllergennameById(73);
		}
		if($order_details['treatment_2'] != "" && $order_details['treatment_2'] != "[]"){
			$subAllergnArr = $this->AllergensModel->getAllergensByID($order_details['treatment_2']);
			if(!empty($subAllergnArr)){
				foreach ($subAllergnArr as $svalue){
					if($svalue['name'] != "N/A"){
					$block2[$svalue['id']] = $svalue['name'];
					}
				}
			}
		}
		asort($block2);

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
													<b><?php echo $this->lang->line('pax_env_scr'); ?></b>
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
		<?php if(empty($block1) && empty($blocks1)){ ?>
			<table cellspacing="0" cellpadding="0" border="0" width="100%" style="padding: 2mm 12mm 0mm;">
				<tr>
					<td width="100%">
						<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td valign="top" width="48%">
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
																	<b><?php echo $this->lang->line('environmental_screen_extracts_and_components'); ?></b>
																</td>
															</tr>
															<tr>
																<td valign="top" style="background:#e2f2f4; padding:10px 8px 0; height: 80px;">
																	<table cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="color:#1b3542; font-weight:400; font-size:11px;"><?php echo $this->lang->line('screening_results'); ?>:
																			</td>
																			<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b><?php echo $this->lang->line('Negative_n'); ?></b></td>
																		</tr>
																		<tr>
																			<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;"><?php echo $this->lang->line('this_patient_does_not_have_elevated_levels_of_IgE_antibodies_against_the_allergens_tested'); ?></td>
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
									<td valign="top" width="48%">
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
																	<b><?php echo $this->lang->line('flea_Cte_f_1'); ?></b>
																</td>
															</tr>
															<tr>
																<td valign="top" style="background:#e2f2f4; padding:10px 8px 0; height: 85px;">
																	<table cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" style="color:#1b3542; font-weight:400; font-size:11px;"><?php echo $this->lang->line('flea_results'); ?>:</td>
																			<?php
																				if(!empty($fleaResults)){
																					if(round($fleaResults->result_value) >= $cutoffs){
																						echo '<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b>'.$this->lang->line('positive_2').'</b></td>';
																					}else{
																						echo '<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b>'.$this->lang->line('negative').'</b></td>';
																					}
																				}else{
																					echo '<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b>'.$this->lang->line('negative').'</b></td>';
																				}
																			?>
																		</tr>
																		<tr>
																			<?php
																				if(!empty($fleaResults)){
																					if(round($fleaResults->result_value) >= $cutoffs){
																						echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;">'.$this->lang->line('patient_result_positive').'</td>';
																					}else{
																						echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;">'.$this->lang->line('patient_result_negative').'</td>';
																					}
																				}else{
																					echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;">'.$this->lang->line('patient_result_negative').'</td>';
																				}
																			?>
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
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0mm 12mm;">
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="margin: 0px auto 0 0;">
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0px 0 20px; margin: 15px 0px 0 0;  background-image: url(<?php echo base_url(); ?>assets/images/question-pdf-bg-img-alt.png); background-repeat: no-repeat; background-position: left top; background-size: 100%;">
										<tbody>
											<?php
												if(!empty($fleaResults)){
													if(round($fleaResults->result_value) >= $cutoffs){
														echo'<tr>
															<td style="padding: 52px 20px 10px 45px;">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:13px;">
																				<b>'.$this->lang->line('negative_for_allergens_what_now').'</b>
																			</td>
																		</tr>
																		<tr>
																			<td width="100%" style="height:2px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:11px; line-height: 18px;">'.$this->lang->line('negative_result_text1').'</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr>
															<td style="padding: 0 20px 25px 45px;">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:13px;">
																				<b>'.$this->lang->line('positive_for_flea_what_now').'</b>
																			</td>
																		</tr>
																		<tr>
																			<td width="100%" style="height:2px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:11px; line-height: 18px;">'.$this->lang->line('flea_treatment_recommended').'</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>';
													}else{
														echo '<tr>
															<td style="padding: 52px 20px 10px 45px;">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:13px;">
																				<b>'.$this->lang->line('negative_what_now').'</b>
																			</td>
																		</tr>
																		<tr>
																			<td width="100%" style="height:2px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:11px; line-height: 18px;">'.$this->lang->line('negative_result_text1').'</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr>
															<td style="padding: 0 20px 30px 45px;">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:13px;">
																				<b>'.$this->lang->line('negative_result_title2').'</b>
																			</td>
																		</tr>
																		<tr>
																			<td width="100%" style="height:2px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:11px; line-height: 18px;">'.$this->lang->line('negative_result_text2').'</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>';
														}
													}else{
														echo '<tr>
															<td style="padding: 52px 20px 10px 45px;">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:13px;">
																				<b>'.$this->lang->line('negative_what_now').'</b>
																			</td>
																		</tr>
																		<tr>
																			<td width="100%" style="height:2px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:11px; line-height: 18px;">'.$this->lang->line('negative_result_text1').'</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr>
															<td style="padding: 0 20px 30px 45px;">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:13px;">
																				<b>'.$this->lang->line('negative_result_title2').'</b>
																			</td>
																		</tr>
																		<tr>
																			<td width="100%" style="height:2px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="color:#1e3743; font-size:11px; line-height: 18px;">'.$this->lang->line('negative_result_text2').'</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>';
													}
												?>
										</tbody>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="10mm"></td></tr></table>
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
													<?php echo $this->lang->line('negative_a1'); ?>
													</td>
												</tr>
												<?php if($this->session->userdata('site_lang') != 'spanish'){ ?>
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
													<?php echo $this->lang->line('negative_a2'); ?>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</td>
									<td valign="top" width="4%">&nbsp;</td>
									<td valign="top" width="48%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="28">
														<img src="<?php echo base_url(); ?>assets/images/question.png" alt="" width="20" />
													</td>
													<td style="color:#1e3743; font-size:12px;">
														<b><?php echo $this->lang->line('negative_result_title4'); ?></b>
													</td>
												</tr>
												<tr><td height="5"></td></tr>
												<tr>
													<td colspan="2" style="color:#1e3743; font-size:11px; line-height: 16px;">
														<?php echo $this->lang->line('negative_result_text4'); ?>
													</td>
												</tr>
												<tr><td height="15"></td></tr>
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
													<?php echo $this->lang->line('short_term_raptor_scr'); ?>
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
				<tr><td height="60px"></td></tr>
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
																					<?php echo $this->lang->line('please_contact_our_veterinary_support_team_by_phone_+01494_629979_or_by_email'); ?>	 <a style="color:#203548;" href="mailto:<?php echo $this->lang->line('contact_email'); ?>"><?php echo $this->lang->line('contact_email'); ?></a>
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
			<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 1mm 12mm 0mm;">
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
														<td style="background:#326883; padding: 0 8px 7px; color:#ffffff; font-size:10px;"><b><?php echo $this->lang->line('environmental_screen_extracts_and_components'); ?></b></td>
													</tr>
													<tr>
														<td valign="top" style="background:#e2f2f4; padding:10px 8px 0; height: 85px;">
															<table cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td style="color:#1b3542; font-weight:400; font-size:11px;"><?php echo $this->lang->line('screening_results'); ?>:
																	</td>
																	<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b><?php echo $this->lang->line('positive_2'); ?></b></td>
																</tr>
																<tr>
																	<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;"><?php echo $this->lang->line('this_patient_has_an_elevated_level_of_IgE_antibodies_against_one_or_more_allergens_such_as_grasses_weeds_trees_mites_moulds_yeast_dander_or_insect_venoms'); ?></td>
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
										<td valign="top" width="48%">
											<table width="100%" cellspacing="0" cellpadding="0" border="0">
												<tbody>
													<tr>
														<td>
															<img src="<?php echo base_url(); ?>/assets/images/top-border-radius.png" width="340">
														</td>
													</tr>
													<tr>
														<td style="background:#326883; padding: 0 8px 7px; color:#ffffff; font-size:10px;">
															<b><?php echo $this->lang->line('flea_Cte_f_1'); ?></b>
														</td>
													</tr>
													<tr>
														<td valign="top" style="background:#e2f2f4; padding:10px 8px 0; height: 80px;">
															<table cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td valign="top" style="color:#1b3542; font-weight:400; font-size:11px;"><?php echo $this->lang->line('flea_results'); ?>:</td>
																	<?php
																	if(!empty($fleaResults)){
																		if(round($fleaResults->result_value) >= $cutoffs){
																			echo '<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b>'.$this->lang->line('positive_2').'</b></td>';
																		}else{
																			echo '<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b>'.$this->lang->line('negative').'</b></td>';
																		}
																	}else{
																		echo '<td style="color:#1b3542; text-transform:uppercase; font-size:11px;" align="right"><b>'.$this->lang->line('negative').'</b></td>';
																	}
																	?>
																</tr>
																<tr>
																	<?php
																	if(!empty($fleaResults)){
																		if(round($fleaResults->result_value) >= $cutoffs){
																			echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;">'.$this->lang->line('patient_result_positive').'</td>';
																		}else{
																			echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;">'.$this->lang->line('patient_result_negative').'</td>';
																		}
																	}else{
																		echo '<td colspan="2" style="color:#1b3542; font-weight:400; font-size:10px; line-height: 16px; padding:14px 0 0 0;">'.$this->lang->line('patient_result_negative').'</td>';
																	}
																	?>
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
													<td width="100%" style="padding: 47px 15px 5px 40px;">
														<table width="100%">
															<tbody>
																<tr>
																	<td style="color:#1e3743; font-size:12px;">
																		<b><?php echo $this->lang->line('positive_for_allergens_what_now'); ?></b>
																	</td>
																</tr>
																<tr>
																	<td style="color:#1e3743; font-size:11px; line-height: 16px;">
																	<?php echo $this->lang->line('spe_patient_sensitizes'); ?> 
																	<?php if($ordeType != 'PAX Food Screening'){ ?>
																	<?php echo $this->lang->line('only_after_raptor_scr'); ?>
																	<?php } ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="padding: 0px 15px 35px 40px;">
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
						<td width="100%" style="height:60px;"></td>
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