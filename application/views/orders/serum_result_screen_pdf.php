<?php
$serumdata = $this->OrdersModel->getSerumTestRecord($id);
if(!empty($serumType)){
	$stypeIDArr = array(); $sresultIDArr = array(); 
	foreach($serumType as $stype){
		$stypeIDArr[] = $stype->type_id;
		$sresultIDArr[] = $stype->result_id;
	}
}

$stypeID = implode(",",$stypeIDArr);
$sresultID = implode(",",$sresultIDArr);

$serumResultsenv = $this->OrdersModel->getSerumTestResultEnv($sresultID,$stypeID);
if(!empty($serumResultsenv)){
	$optn2Arr = $optnenvArr = $optionenv = []; $block1 = 0;
	foreach($serumResultsenv as $row1){
		$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
		if(!empty($algName)){
			$optionenv['algid'] = $algName->id;
			$optionenv['name'] = $algName->name;
			$optionenv['result'] = $row1->result;
			if($row1->result > 5){
				$optn2Arr[] = $algName->id;
				$block1++;
			}
			$optnenvArr[] = $optionenv;
		}
	}
}else{
	$block1 = 0;
	$optnenvArr = [];
	$optn2Arr = [];
}

$this->db->select('name');
$this->db->from('ci_price');
$this->db->where('id', $order_details['product_code_selection']);
$respnedn = $this->db->get()->row();
$ordeType = $respnedn->name;
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
?>
<!DOCTYPE html>
<html class="js">
	<head>
		<title>Serum Test Result</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page {margin:0mm;}
		html {margin:5px 15px;padding:0px}
		*{font-family:'Open Sans',sans-serif}
		.header th{text-align:left}
		.bargraph{list-style:none;width:100%;position:relative;margin:0;padding:0}
		.bargraph li{position:relative;height:21px;margin-bottom:6px;transition:width 2s;-webkit-transition:width 2s;background:#abd084}
		.bargraph li.grey{background:#ccc}
		.bargraph li.red{background:red}
		.bargraph li span{display:block}
		.foodbargraph{list-style:none;width:100%;position:relative;margin:0;padding:0}
		.foodbargraph li{position:relative;height:19.6px;margin-bottom:5px;transition:width 2s;-webkit-transition:width 2s;background:#abd084}
		.foodbargraph li.grey{background:#ccc}
		.foodbargraph li.red{background:red}
		.foodbargraph li span{display:block}
		</style>
	</head>
	<body bgcolor="#fff">
		<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;background-image:url(<?php echo base_url("/assets/images/next-header.jpg"); ?>); background-color: #ffffff; background-repeat: no-repeat; background-position: -190px 0px; margin:0mm;">
			<tr>
				<td valign="middle" width="430" style="padding:60px 30px 20px 10px;">
					<img src="<?php echo base_url("/assets/images/nextlab-logo.jpg"); ?>" alt="Logo" style="max-height:130px; max-width:360px; border-radius:4px;" />
					<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;">Serum Test results</h5>
				</td>
				<td valign="middle">
					<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
						<tr>
							<th style="color:#346a7e;">Owner name:</th>
							<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Animal Name:</th>
							<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Species:</th>
							<td style="color:#000000;"><?php echo $order_details['species_name'];?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Veterinarian:</th>
							<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Veterinary practice:</th>
							<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Address:</th>
							<td style="color:#000000;"><?php echo $fulladdress; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Phone / Fax:</th>
							<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Email:</th>
							<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Test type:</th>
							<td style="color:#000000;"><?php echo $ordeType; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Date tested:</th>
							<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Customer number:</th>
							<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
						</tr>
						<tr>
							<th style="color:#346a7e;">Lab:</th>
							<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="30"></td></tr></table>
		<?php
		if(!empty($respnedn)){
			if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bComplete Food\b/', $respnedn->name))){
			?>
				<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;margin-left: 15px;">
					<tbody>
						<tr>
							<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;">SCREEN Environmental Panel</h6></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<?php
						/* Start Grasses */
						$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $order_details['allergens']);
						$countergN = $countergB = $countergP = 0;
						foreach($grassesAllergens as $gvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$countergP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$countergB++;
								}else{
									$countergN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Grasses</td>';
							if($countergP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countergB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Grasses */

						/* Start Weeds */
						$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $order_details['allergens']);
						$counterwN = $counterwB = $counterwP = 0;
						foreach($weedsAllergens as $wvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$counterwP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$counterwB++;
								}else{
									$counterwN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Weeds</td>';
							if($counterwP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterwB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Weeds */

						/* Start Trees */
						$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $order_details['allergens']);
						$countertN = $countertB = $countertP = 0;
						foreach($treesAllergens as $tvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$countertP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$countertB++;
								}else{
									$countertN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Trees</td>';
							if($countertP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countertB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Trees */

						/* Start Crops */
						$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $order_details['allergens']);
						$countercN = $countercB = $countercP = 0;
						foreach($cropsAllergens as $cvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumcResults = $this->db->get()->row();
							if(!empty($serumcResults)){
								if($serumcResults->result >= 10){
									$countercP++;
								}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
									$countercB++;
								}else{
									$countercN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Crops</td>';
							if($countercP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countercB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Crops */

						/* Start Indoor(Mites/Moulds/Epithelia) */
						$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $order_details['allergens']);
						$counteriN = $counteriB = $counteriP = 0;
						foreach($indoorAllergens as $ivalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$counteriP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$counteriB++;
								}else{
									$counteriN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Indoor</td>';
							if($counteriP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counteriB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Indoor(Mites/Moulds/Epithelia) */

						if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994')){
							/* Start Flea */
							echo '<tr>
								<td style="width:300px">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('result', 'DESC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										echo '<td>POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								}else{
									echo '<td>NEGATIVE</td>';
								}
							echo '</tr>';
							echo '<tr><td colspan="2">&nbsp;</td></tr>';
							/* End Flea */

							/* Start Malassezia */
							echo '<tr>
								<td style="width:300px">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										echo '<td>POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								}else{
									echo '<td>NEGATIVE</td>';
								}
							echo '</tr>';
							echo '<tr><td colspan="2">&nbsp;</td></tr>';
							/* End Malassezia */
						}
						?>
					</tbody>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
								<tr>
									<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
									<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">IgE Level</th>
									<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;"></th>
								</tr>
								<tr bgcolor="#eaf6f7">
									<td align="left" style="padding:5px 10px 0px 15px;"></td>
									<td align="right" style="padding:5px 15px 0px 10px;"></td>
									<td align="right" style="padding:5px 15px 0px 10px;"></td>
								</tr>
								<?php
								$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
								if(!empty($serumResultsfod)){
									$rsultFIge = $rsultFIgg = [];
									foreach($serumResultsfod as $rowf){
										$algName = $this->OrdersModel->getAllergensName($rowf->lims_allergens_id);
										if(!empty($algName)){
											$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
											echo '<tr bgcolor="#d0ebef">
												<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">'. $algName->name .'</td>
												<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;vertical-align: baseline;">'.$rsultFIge->result.'</td>
											</tr>
											<tr bgcolor="#d0ebef">
												<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;vertical-align: baseline;">'.$rsultFIgg->result.'</td>
											</tr>';
										}
									}
								}
								?>
								<tr>
									<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%" border="0">
											<tr>
												<th height="35" width="30%"></th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: left;">5</th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: center;">10</th>
												<th></th>
											</tr>
											<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
												<td>
													<ul class="foodbargraph" style="margin-top:10px;">
														<?php
														$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
														if(!empty($serumResultsfod)){
															$resultperIge = 0; $resultperIgg = 0;
															foreach($serumResultsfod as $row1){
																$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
																if(!empty($algName)){
																	$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																	if($rsultFIge->result <= 5){
																		$resultperIge = 20*$rsultFIge->result;
																	}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 7){
																		$resultperIge = (20*$rsultFIge->result)-20;
																	}elseif($rsultFIge->result == 8){
																		$resultperIge = (20*$rsultFIge->result)-30;
																	}elseif($rsultFIge->result == 9){
																		$resultperIge = (20*$rsultFIge->result)-40;
																	}elseif($rsultFIge->result == 10){
																		$resultperIge = (20*$rsultFIge->result)-50;
																	}elseif($rsultFIge->result > 10){
																		$resultperIge = (20*$rsultFIge->result)-60;
																	}
																	if($resultperIge > 330){
																		$resultperIge = 330;
																	}else{
																		$resultperIge = $resultperIge;
																	}
																	if($rsultFIge->result <= 5){
																		echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																		echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}elseif($rsultFIge->result > 10){
																		echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}

																	$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																	if($rsultFIgg->result <= 5){
																		$resultperIgg = 20*$rsultFIgg->result;
																	}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 7){
																		$resultperIgg = (20*$rsultFIgg->result)-20;
																	}elseif($rsultFIgg->result == 8){
																		$resultperIgg = (20*$rsultFIgg->result)-30;
																	}elseif($rsultFIgg->result == 9){
																		$resultperIgg = (20*$rsultFIgg->result)-40;
																	}elseif($rsultFIgg->result == 10){
																		$resultperIgg = (20*$rsultFIgg->result)-50;
																	}elseif($rsultFIgg->result > 10){
																		$resultperIgg = (20*$rsultFIgg->result)-60;
																	}
																	if($resultperIgg > 330){
																		$resultperIgg = 330;
																	}else{
																		$resultperIgg = $resultperIgg;
																	}
																	if($rsultFIgg->result <= 5){
																		echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																		echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}elseif($rsultFIgg->result > 10){
																		echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}
																}
															}
														}
														?>
													</ul>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) left center repeat-y;"></td>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
													<td></td>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 5</strong> </p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 5 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
						</td>
						<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>5-10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
						</td>
						<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">here there is a suspicion of atopic dermatitis elevated levels of mould-specific IgE may not be clinically relevant unless they are very high. An Additional borderline parameter is used for this group.</p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
					<tr>
						<td>
							<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
						</td>
					</tr>
				</table>
			<?php
			}elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){
			?>
				<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;margin-left: 15px;">
					<tbody>
						<tr>
							<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;">SCREEN Environmental Panel</h6></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<?php
						/* Start Grasses */
						$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $order_details['allergens']);
						$countergN = $countergB = $countergP = 0;
						foreach($grassesAllergens as $gvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$countergP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$countergB++;
								}else{
									$countergN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Grasses</td>';
							if($countergP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countergB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Grasses */

						/* Start Weeds */
						$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $order_details['allergens']);
						$counterwN = $counterwB = $counterwP = 0;
						foreach($weedsAllergens as $wvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$counterwP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$counterwB++;
								}else{
									$counterwN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Weeds</td>';
							if($counterwP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterwB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Weeds */

						/* Start Trees */
						$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $order_details['allergens']);
						$countertN = $countertB = $countertP = 0;
						foreach($treesAllergens as $tvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$countertP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$countertB++;
								}else{
									$countertN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Trees</td>';
							if($countertP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countertB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Trees */

						/* Start Crops */
						$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $order_details['allergens']);
						$countercN = $countercB = $countercP = 0;
						foreach($cropsAllergens as $cvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumcResults = $this->db->get()->row();
							if(!empty($serumcResults)){
								if($serumcResults->result >= 10){
									$countercP++;
								}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
									$countercB++;
								}else{
									$countercN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Crops</td>';
							if($countercP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countercB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Crops */

						/* Start Indoor(Mites/Moulds/Epithelia) */
						$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $order_details['allergens']);
						$counteriN = $counteriB = $counteriP = 0;
						foreach($indoorAllergens as $ivalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$counteriP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$counteriB++;
								}else{
									$counteriN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Indoor</td>';
							if($counteriP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counteriB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Indoor(Mites/Moulds/Epithelia) */

						if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994')){
							/* Start Flea */
							echo '<tr>
								<td style="width:300px">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('result', 'DESC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										echo '<td>POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								}else{
									echo '<td>NEGATIVE</td>';
								}
							echo '</tr>';
							echo '<tr><td colspan="2">&nbsp;</td></tr>';
							/* End Flea */

							/* Start Malassezia */
							echo '<tr>
								<td style="width:300px">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										echo '<td>POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								}else{
									echo '<td>NEGATIVE</td>';
								}
							echo '</tr>';
							echo '<tr><td colspan="2">&nbsp;</td></tr>';
							/* End Malassezia */
						}
						?>
					</tbody>
				</table>
			<?php
			}elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){
			?>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;margin-left: 15px;">
					<tbody>
						<tr>
							<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;">SCREEN Food Panel</h6></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<?php
						/* Start Food Proteins */
						$grassesAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $order_details['allergens']);
						$counterFPN = $counterFPB = $counterFPP = 0;
						foreach($grassesAllergens as $fpvalue){
							$this->db->select('result');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('result', 'DESC');
							$fpResults = $this->db->get()->row();
							if(!empty($fpResults)){
								if($fpResults->result >= 10){
									$counterFPP++;
								}elseif($fpResults->result >= 5 && $fpResults->result < 10){
									$counterFPB++;
								}else{
									$counterFPN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Food Proteins</td>';
							if($counterFPP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterFPB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Food Proteins */

						/* Start Food Carbohydrates */
						$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $order_details['allergens']);
						$counterFCN = $counterFCB = $counterFCP = 0;
						foreach($carbohyAllergens as $fcvalue){
							$this->db->select('result');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('result', 'DESC');
							$fcResults = $this->db->get()->row();
							if(!empty($fcResults)){
								if($fcResults->result >= 10){
									$counterFCP++;
								}elseif($fcResults->result >= 5 && $fcResults->result < 10){
									$counterFCB++;
								}else{
									$counterFCN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Food Carbohydrates</td>';
							if($counterFCP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterFCB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Food Carbohydrates */
						?>
					</tbody>
				</table>
			<?php
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (!preg_match('/\bFood Panel\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name))){
			?>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
								<tr>
									<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
									<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">EA Units*</th>
								</tr>
								<tr bgcolor="#eaf6f7">
									<td align="left" style="padding:5px 10px 5px 15px;"></td>
									<td align="right" style="padding:5px 15px 5px 10px;"></td>
								</tr>
								<?php
								if(!empty($optnenvArr)){
									foreach($optnenvArr as $row1){
										echo '<tr bgcolor="#d0ebef">
											<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">'. $row1['name'] .'</td>
											<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
										</tr>';
									}
								}
								?>
								<tr>
									<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%" border="0">
											<tr>
												<th height="35" width="30%"></th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: left;">5</th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: center;">10</th>
												<th></th>
											</tr>
											<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
												<td>
													<ul class="bargraph" style="margin-top:15px;">
														<?php
														if(!empty($optnenvArr)){
															$resultper = 0;
															foreach($optnenvArr as $row2){
																if($row2['result'] <= 5){
																	$resultper = 20*$row2['result'];
																}elseif($row2['result'] > 5 && $row2['result'] <= 7){
																	$resultper = (20*$row2['result'])-20;
																}elseif($row2['result'] == 8){
																	$resultper = (20*$row2['result'])-30;
																}elseif($row2['result'] == 9){
																	$resultper = (20*$row2['result'])-40;
																}elseif($row2['result'] == 10){
																	$resultper = (20*$row2['result'])-50;
																}elseif($row2['result'] > 10){
																	$resultper = (20*$row2['result'])-60;
																}
																if($resultper > 330){
																	$resultper = 330;
																}else{
																	$resultper = $resultper;
																}
																if($row2['result'] <= 5){
																	echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																}elseif($row2['result'] > 5 && $row2['result'] <= 10){
																	echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																}elseif($row2['result'] > 10){
																	echo '<li class="red" style="width:'. $resultper .'%;" class=""><span></span></li>';
																}
															}
														}
														?>
													</ul>
												</td>
												<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) left center repeat-y;"></td>
												<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
												<td></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 5</strong> </p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 5 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
						</td>
						<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>5-10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
						</td>
						<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">here there is a suspicion of atopic dermatitis elevated levels of mould-specific IgE may not be clinically relevant unless they are very high. An Additional borderline parameter is used for this group.</p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
					<tr>
						<td>
							<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
						</td>
					</tr>
				</table>
			<?php
			}elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name)){
			?>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
								<tr>
									<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
									<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">IgE Level</th>
									<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;"></th>
								</tr>
								<tr bgcolor="#eaf6f7">
									<td align="left" style="padding:5px 10px 0px 15px;"></td>
									<td align="right" style="padding:5px 15px 0px 10px;"></td>
									<td align="right" style="padding:5px 15px 0px 10px;"></td>
								</tr>
								<?php
								$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
								if(!empty($serumResultsfod)){
									$rsultFIge = $rsultFIgg = [];
									foreach($serumResultsfod as $rowf){
										$algName = $this->OrdersModel->getAllergensName($rowf->lims_allergens_id);
										if(!empty($algName)){
											$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
											echo '<tr bgcolor="#d0ebef">
												<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">'. $algName->name .'</td>
												<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;vertical-align: baseline;">'.$rsultFIge->result.'</td>
											</tr>
											<tr bgcolor="#d0ebef">
												<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;vertical-align: baseline;">'.$rsultFIgg->result.'</td>
											</tr>';
										}
									}
								}
								?>
								<tr>
									<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%" border="0">
											<tr>
												<th height="35" width="30%"></th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: left;">5</th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: center;">10</th>
												<th></th>
											</tr>
											<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
												<td>
													<ul class="foodbargraph" style="margin-top:10px;">
														<?php
														$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
														if(!empty($serumResultsfod)){
															$resultperIge = 0; $resultperIgg = 0;
															foreach($serumResultsfod as $row1){
																$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
																if(!empty($algName)){
																	$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																	if($rsultFIge->result <= 5){
																		$resultperIge = 20*$rsultFIge->result;
																	}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 7){
																		$resultperIge = (20*$rsultFIge->result)-20;
																	}elseif($rsultFIge->result == 8){
																		$resultperIge = (20*$rsultFIge->result)-30;
																	}elseif($rsultFIge->result == 9){
																		$resultperIge = (20*$rsultFIge->result)-40;
																	}elseif($rsultFIge->result == 10){
																		$resultperIge = (20*$rsultFIge->result)-50;
																	}elseif($rsultFIge->result > 10){
																		$resultperIge = (20*$rsultFIge->result)-60;
																	}
																	if($resultperIge > 330){
																		$resultperIge = 330;
																	}else{
																		$resultperIge = $resultperIge;
																	}
																	if($rsultFIge->result <= 5){
																		echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																		echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}elseif($rsultFIge->result > 10){
																		echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}

																	$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																	if($rsultFIgg->result <= 5){
																		$resultperIgg = 20*$rsultFIgg->result;
																	}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 7){
																		$resultperIgg = (20*$rsultFIgg->result)-20;
																	}elseif($rsultFIgg->result == 8){
																		$resultperIgg = (20*$rsultFIgg->result)-30;
																	}elseif($rsultFIgg->result == 9){
																		$resultperIgg = (20*$rsultFIgg->result)-40;
																	}elseif($rsultFIgg->result == 10){
																		$resultperIgg = (20*$rsultFIgg->result)-50;
																	}elseif($rsultFIgg->result > 10){
																		$resultperIgg = (20*$rsultFIgg->result)-60;
																	}
																	if($resultperIgg > 330){
																		$resultperIgg = 330;
																	}else{
																		$resultperIgg = $resultperIgg;
																	}
																	if($rsultFIgg->result <= 5){
																		echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																		echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}elseif($rsultFIgg->result > 10){
																		echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}
																}
															}
														}
														?>
													</ul>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) left center repeat-y;"></td>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
													<td></td>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 5</strong> </p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 5 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
						</td>
						<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>5-10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
						</td>
						<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">here there is a suspicion of atopic dermatitis elevated levels of mould-specific IgE may not be clinically relevant unless they are very high. An Additional borderline parameter is used for this group.</p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
					<tr>
						<td>
							<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
						</td>
					</tr>
				</table>
			<?php
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood Panel\b/', $respnedn->name))){
			?>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
								<tr>
									<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
									<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">EA Units*</th>
								</tr>
								<tr bgcolor="#eaf6f7">
									<td align="left" style="padding:5px 10px 5px 15px;"></td>
									<td align="right" style="padding:5px 15px 5px 10px;"></td>
								</tr>
								<?php
								if(!empty($optnenvArr)){
									foreach($optnenvArr as $row1){
										echo '<tr bgcolor="#d0ebef">
											<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">'. $row1['name'] .'</td>
											<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
										</tr>';
									}
								}
								?>
								<tr>
									<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%" border="0">
											<tr>
												<th height="35" width="30%"></th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: left;">5</th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: center;">10</th>
												<th></th>
											</tr>
											<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
												<td>
													<ul class="bargraph" style="margin-top:15px;">
														<?php
														if(!empty($optnenvArr)){
															$resultper = 0;
															foreach($optnenvArr as $row2){
																if($row2['result'] <= 5){
																	$resultper = 20*$row2['result'];
																}elseif($row2['result'] > 5 && $row2['result'] <= 7){
																	$resultper = (20*$row2['result'])-20;
																}elseif($row2['result'] == 8){
																	$resultper = (20*$row2['result'])-30;
																}elseif($row2['result'] == 9){
																	$resultper = (20*$row2['result'])-40;
																}elseif($row2['result'] == 10){
																	$resultper = (20*$row2['result'])-50;
																}elseif($row2['result'] > 10){
																	$resultper = (20*$row2['result'])-60;
																}
																if($resultper > 330){
																	$resultper = 330;
																}else{
																	$resultper = $resultper;
																}
																if($row2['result'] <= 5){
																	echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																}elseif($row2['result'] > 5 && $row2['result'] <= 10){
																	echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																}elseif($row2['result'] > 10){
																	echo '<li class="red" style="width:'. $resultper .'%;" class=""><span></span></li>';
																}
															}
														}
														?>
													</ul>
												</td>
												<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) left center repeat-y;"></td>
												<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
												<td></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
								<tr>
									<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
									<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">IgE Level</th>
									<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;"></th>
								</tr>
								<tr bgcolor="#eaf6f7">
									<td align="left" style="padding:5px 10px 0px 15px;"></td>
									<td align="right" style="padding:5px 15px 0px 10px;"></td>
									<td align="right" style="padding:5px 15px 0px 10px;"></td>
								</tr>
								<?php
								$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
								if(!empty($serumResultsfod)){
									$rsultFIge = $rsultFIgg = [];
									foreach($serumResultsfod as $rowf){
										$algName = $this->OrdersModel->getAllergensName($rowf->lims_allergens_id);
										if(!empty($algName)){
											$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
											echo '<tr bgcolor="#d0ebef">
												<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">'. $algName->name .'</td>
												<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;vertical-align: baseline;">'.$rsultFIge->result.'</td>
											</tr>
											<tr bgcolor="#d0ebef">
												<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;vertical-align: baseline;">'.$rsultFIgg->result.'</td>
											</tr>';
										}
									}
								}
								?>
								<tr>
									<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%" border="0">
											<tr>
												<th height="35" width="30%"></th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: left;">5</th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: center;">10</th>
												<th></th>
											</tr>
											<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
												<td>
													<ul class="foodbargraph" style="margin-top:10px;">
														<?php
														$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
														if(!empty($serumResultsfod)){
															$resultperIge = 0; $resultperIgg = 0;
															foreach($serumResultsfod as $row1){
																$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
																if(!empty($algName)){
																	$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																	if($rsultFIge->result <= 5){
																		$resultperIge = 20*$rsultFIge->result;
																	}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 7){
																		$resultperIge = (20*$rsultFIge->result)-20;
																	}elseif($rsultFIge->result == 8){
																		$resultperIge = (20*$rsultFIge->result)-30;
																	}elseif($rsultFIge->result == 9){
																		$resultperIge = (20*$rsultFIge->result)-40;
																	}elseif($rsultFIge->result == 10){
																		$resultperIge = (20*$rsultFIge->result)-50;
																	}elseif($rsultFIge->result > 10){
																		$resultperIge = (20*$rsultFIge->result)-60;
																	}
																	if($resultperIge > 330){
																		$resultperIge = 330;
																	}else{
																		$resultperIge = $resultperIge;
																	}
																	if($rsultFIge->result <= 5){
																		echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																		echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}elseif($rsultFIge->result > 10){
																		echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}

																	$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																	if($rsultFIgg->result <= 5){
																		$resultperIgg = 20*$rsultFIgg->result;
																	}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 7){
																		$resultperIgg = (20*$rsultFIgg->result)-20;
																	}elseif($rsultFIgg->result == 8){
																		$resultperIgg = (20*$rsultFIgg->result)-30;
																	}elseif($rsultFIgg->result == 9){
																		$resultperIgg = (20*$rsultFIgg->result)-40;
																	}elseif($rsultFIgg->result == 10){
																		$resultperIgg = (20*$rsultFIgg->result)-50;
																	}elseif($rsultFIgg->result > 10){
																		$resultperIgg = (20*$rsultFIgg->result)-60;
																	}
																	if($resultperIgg > 330){
																		$resultperIgg = 330;
																	}else{
																		$resultperIgg = $resultperIgg;
																	}
																	if($rsultFIgg->result <= 5){
																		echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																		echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}elseif($rsultFIgg->result > 10){
																		echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}
																}
															}
														}
														?>
													</ul>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) left center repeat-y;"></td>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
													<td></td>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 5</strong> </p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 5 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
						</td>
						<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>5-10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
						</td>
						<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">here there is a suspicion of atopic dermatitis elevated levels of mould-specific IgE may not be clinically relevant unless they are very high. An Additional borderline parameter is used for this group.</p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
					<tr>
						<td>
							<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
						</td>
					</tr>
				</table>
			<?php
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood SCREEN\b/', $respnedn->name))){
			?>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
								<tr>
									<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
									<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">EA Units*</th>
								</tr>
								<tr bgcolor="#eaf6f7">
									<td align="left" style="padding:5px 10px 5px 15px;"></td>
									<td align="right" style="padding:5px 15px 5px 10px;"></td>
								</tr>
								<?php
								if(!empty($optnenvArr)){
									foreach($optnenvArr as $row1){
										echo '<tr bgcolor="#d0ebef">
											<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">'. $row1['name'] .'</td>
											<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
										</tr>';
									}
								}
								?>
								<tr>
									<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%" border="0">
											<tr>
												<th height="35" width="30%"></th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: left;">5</th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: center;">10</th>
												<th></th>
											</tr>
											<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
												<td>
													<ul class="bargraph" style="margin-top:15px;">
														<?php
														if(!empty($optnenvArr)){
															$resultper = 0;
															foreach($optnenvArr as $row2){
																if($row2['result'] <= 5){
																	$resultper = 20*$row2['result'];
																}elseif($row2['result'] > 5 && $row2['result'] <= 7){
																	$resultper = (20*$row2['result'])-20;
																}elseif($row2['result'] == 8){
																	$resultper = (20*$row2['result'])-30;
																}elseif($row2['result'] == 9){
																	$resultper = (20*$row2['result'])-40;
																}elseif($row2['result'] == 10){
																	$resultper = (20*$row2['result'])-50;
																}elseif($row2['result'] > 10){
																	$resultper = (20*$row2['result'])-60;
																}
																if($resultper > 330){
																	$resultper = 330;
																}else{
																	$resultper = $resultper;
																}
																if($row2['result'] <= 5){
																	echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																}elseif($row2['result'] > 5 && $row2['result'] <= 10){
																	echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																}elseif($row2['result'] > 10){
																	echo '<li class="red" style="width:'. $resultper .'%;" class=""><span></span></li>';
																}
															}
														}
														?>
													</ul>
												</td>
												<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) left center repeat-y;"></td>
												<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
												<td></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 5</strong> </p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 5 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
						</td>
						<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>5-10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
						</td>
						<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">here there is a suspicion of atopic dermatitis elevated levels of mould-specific IgE may not be clinically relevant unless they are very high. An Additional borderline parameter is used for this group.</p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
					<tr>
						<td>
							<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;margin-left: 15px;">
					<tbody>
						<tr>
							<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;">SCREEN Food Panel</h6></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<?php
						/* Start Food Proteins */
						$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $order_details['allergens']);
						$counterFPN = $counterFPB = $counterFPP = 0;
						foreach($proteinsAllergens as $fpvalue){
							$this->db->select('result');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('result', 'DESC');
							$fpResults = $this->db->get()->row();
							if(!empty($fpResults)){
								if($fpResults->result >= 10){
									$counterFPP++;
								}elseif($fpResults->result >= 5 && $fpResults->result < 10){
									$counterFPB++;
								}else{
									$counterFPN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Food Proteins</td>';
							if($counterFPP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterFPB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Food Proteins */

						/* Start Food Carbohydrates */
						$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $order_details['allergens']);
						$counterFCN = $counterFCB = $counterFCP = 0;
						foreach($carbohyAllergens as $fcvalue){
							$this->db->select('result');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('result', 'DESC');
							$fcResults = $this->db->get()->row();
							if(!empty($fcResults)){
								if($fcResults->result >= 10){
									$counterFCP++;
								}elseif($fcResults->result >= 5 && $fcResults->result < 10){
									$counterFCB++;
								}else{
									$counterFCN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Food Carbohydrates</td>';
							if($counterFCP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterFCB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Food Carbohydrates */
						?>
					</tbody>
				</table>
			<?php
			}elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
			?>
				<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;margin-left: 15px;">
					<tbody>
						<tr>
							<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;">SCREEN Environmental Panel</h6></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<?php
						/* Start Grasses */
						$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $order_details['allergens']);
						$countergN = $countergB = $countergP = 0;
						foreach($grassesAllergens as $gvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$countergP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$countergB++;
								}else{
									$countergN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Grasses</td>';
							if($countergP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countergB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Grasses */

						/* Start Weeds */
						$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $order_details['allergens']);
						$counterwN = $counterwB = $counterwP = 0;
						foreach($weedsAllergens as $wvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$counterwP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$counterwB++;
								}else{
									$counterwN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Weeds</td>';
							if($counterwP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterwB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Weeds */

						/* Start Trees */
						$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $order_details['allergens']);
						$countertN = $countertB = $countertP = 0;
						foreach($treesAllergens as $tvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$countertP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$countertB++;
								}else{
									$countertN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Trees</td>';
							if($countertP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countertB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Trees */

						/* Start Crops */
						$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $order_details['allergens']);
						$countercN = $countercB = $countercP = 0;
						foreach($cropsAllergens as $cvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumcResults = $this->db->get()->row();
							if(!empty($serumcResults)){
								if($serumcResults->result >= 10){
									$countercP++;
								}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
									$countercB++;
								}else{
									$countercN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Crops</td>';
							if($countercP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($countercB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Crops */

						/* Start Indoor(Mites/Moulds/Epithelia) */
						$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $order_details['allergens']);
						$counteriN = $counteriB = $counteriP = 0;
						foreach($indoorAllergens as $ivalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$counteriP++;
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$counteriB++;
								}else{
									$counteriN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Indoor</td>';
							if($counteriP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counteriB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Indoor(Mites/Moulds/Epithelia) */

						if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994')){
							/* Start Flea */
							echo '<tr>
								<td style="width:300px">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('result', 'DESC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										echo '<td>POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								}else{
									echo '<td>NEGATIVE</td>';
								}
							echo '</tr>';
							echo '<tr><td colspan="2">&nbsp;</td></tr>';
							/* End Flea */

							/* Start Malassezia */
							echo '<tr>
								<td style="width:300px">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										echo '<td>POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								}else{
									echo '<td>NEGATIVE</td>';
								}
							echo '</tr>';
							echo '<tr><td colspan="2">&nbsp;</td></tr>';
							/* End Malassezia */
						}
						?>
					</tbody>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;margin-left: 15px;">
					<tbody>
						<tr>
							<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;">SCREEN Food Panel</h6></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<?php
						/* Start Food Proteins */
						$grassesAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $order_details['allergens']);
						$counterFPN = $counterFPB = $counterFPP = 0;
						foreach($grassesAllergens as $fpvalue){
							$this->db->select('result');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('result', 'DESC');
							$fpResults = $this->db->get()->row();
							if(!empty($fpResults)){
								if($fpResults->result >= 10){
									$counterFPP++;
								}elseif($fpResults->result >= 5 && $fpResults->result < 10){
									$counterFPB++;
								}else{
									$counterFPN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Food Proteins</td>';
							if($counterFPP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterFPB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Food Proteins */

						/* Start Food Carbohydrates */
						$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $order_details['allergens']);
						$counterFCN = $counterFCB = $counterFCP = 0;
						foreach($carbohyAllergens as $fcvalue){
							$this->db->select('result');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('result', 'DESC');
							$fcResults = $this->db->get()->row();
							if(!empty($fcResults)){
								if($fcResults->result >= 10){
									$counterFCP++;
								}elseif($fcResults->result >= 5 && $fcResults->result < 10){
									$counterFCB++;
								}else{
									$counterFCN++;
								}
							}
						}
						echo '<tr>
							<td style="width:300px">Food Carbohydrates</td>';
							if($counterFCP > 0){
								echo '<td>POSITIVE</td>';
							}elseif($counterFCB > 0){
								echo '<td>BORDER LINE</td>';
							}else{
								echo '<td>NEGATIVE</td>';
							}
						echo '</tr>';
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						/* End Food Carbohydrates */
						?>
					</tbody>
				</table>
			<?php 
			}elseif(preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name)){
			?>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
								<tr>
									<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
									<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">EA Units*</th>
								</tr>
								<tr bgcolor="#eaf6f7">
									<td align="left" style="padding:5px 10px 5px 15px;"></td>
									<td align="right" style="padding:5px 15px 5px 10px;"></td>
								</tr>
								<?php
								if(!empty($optnenvArr)){
									foreach($optnenvArr as $row1){
										echo '<tr bgcolor="#d0ebef">
											<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">'. $row1['name'] .'</td>
											<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
										</tr>';
									}
								}
								?>
								<tr>
									<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%" border="0">
											<tr>
												<th height="35" width="30%"></th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: left;">5</th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: center;">10</th>
												<th></th>
											</tr>
											<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
												<td>
													<ul class="bargraph" style="margin-top:15px;">
														<?php
														if(!empty($optnenvArr)){
															$resultper = 0;
															foreach($optnenvArr as $row2){
																if($row2['result'] <= 5){
																	$resultper = 20*$row2['result'];
																}elseif($row2['result'] > 5 && $row2['result'] <= 7){
																	$resultper = (20*$row2['result'])-20;
																}elseif($row2['result'] == 8){
																	$resultper = (20*$row2['result'])-30;
																}elseif($row2['result'] == 9){
																	$resultper = (20*$row2['result'])-40;
																}elseif($row2['result'] == 10){
																	$resultper = (20*$row2['result'])-50;
																}elseif($row2['result'] > 10){
																	$resultper = (20*$row2['result'])-60;
																}
																if($resultper > 330){
																	$resultper = 330;
																}else{
																	$resultper = $resultper;
																}
																if($row2['result'] <= 5){
																	echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																}elseif($row2['result'] > 5 && $row2['result'] <= 10){
																	echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																}elseif($row2['result'] > 10){
																	echo '<li class="red" style="width:'. $resultper .'%;" class=""><span></span></li>';
																}
															}
														}
														?>
													</ul>
												</td>
												<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) left center repeat-y;"></td>
												<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
												<td></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<?php if(preg_match('/\bFood\b/', $respnedn->name)){ ?>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
								<tr>
									<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
									<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">IgE Level</th>
									<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;"></th>
								</tr>
								<tr bgcolor="#eaf6f7">
									<td align="left" style="padding:5px 10px 0px 15px;"></td>
									<td align="right" style="padding:5px 15px 0px 10px;"></td>
									<td align="right" style="padding:5px 15px 0px 10px;"></td>
								</tr>
								<?php
								$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
								if(!empty($serumResultsfod)){
									$rsultFIge = $rsultFIgg = [];
									foreach($serumResultsfod as $rowf){
										$algName = $this->OrdersModel->getAllergensName($rowf->lims_allergens_id);
										if(!empty($algName)){
											$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
											echo '<tr bgcolor="#d0ebef">
												<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">'. $algName->name .'</td>
												<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;vertical-align: baseline;">'.$rsultFIge->result.'</td>
											</tr>
											<tr bgcolor="#d0ebef">
												<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
												<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;vertical-align: baseline;">'.$rsultFIgg->result.'</td>
											</tr>';
										}
									}
								}
								?>
								<tr>
									<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%" border="0">
											<tr>
												<th height="35" width="30%"></th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: left;">5</th>
												<th width="10%" style="color:#326883; font-size:15px;text-align: center;">10</th>
												<th></th>
											</tr>
											<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
												<td>
													<ul class="foodbargraph" style="margin-top:10px;">
														<?php
														$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
														if(!empty($serumResultsfod)){
															$resultperIge = 0; $resultperIgg = 0;
															foreach($serumResultsfod as $row1){
																$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
																if(!empty($algName)){
																	$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																	if($rsultFIge->result <= 5){
																		$resultperIge = 20*$rsultFIge->result;
																	}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 7){
																		$resultperIge = (20*$rsultFIge->result)-20;
																	}elseif($rsultFIge->result == 8){
																		$resultperIge = (20*$rsultFIge->result)-30;
																	}elseif($rsultFIge->result == 9){
																		$resultperIge = (20*$rsultFIge->result)-40;
																	}elseif($rsultFIge->result == 10){
																		$resultperIge = (20*$rsultFIge->result)-50;
																	}elseif($rsultFIge->result > 10){
																		$resultperIge = (20*$rsultFIge->result)-60;
																	}
																	if($resultperIge > 330){
																		$resultperIge = 330;
																	}else{
																		$resultperIge = $resultperIge;
																	}
																	if($rsultFIge->result <= 5){
																		echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																		echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}elseif($rsultFIge->result > 10){
																		echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																	}

																	$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																	if($rsultFIgg->result <= 5){
																		$resultperIgg = 20*$rsultFIgg->result;
																	}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 7){
																		$resultperIgg = (20*$rsultFIgg->result)-20;
																	}elseif($rsultFIgg->result == 8){
																		$resultperIgg = (20*$rsultFIgg->result)-30;
																	}elseif($rsultFIgg->result == 9){
																		$resultperIgg = (20*$rsultFIgg->result)-40;
																	}elseif($rsultFIgg->result == 10){
																		$resultperIgg = (20*$rsultFIgg->result)-50;
																	}elseif($rsultFIgg->result > 10){
																		$resultperIgg = (20*$rsultFIgg->result)-60;
																	}
																	if($resultperIgg > 330){
																		$resultperIgg = 330;
																	}else{
																		$resultperIgg = $resultperIgg;
																	}
																	if($rsultFIgg->result <= 5){
																		echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																		echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}elseif($rsultFIgg->result > 10){
																		echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																	}
																}
															}
														}
														?>
													</ul>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) left center repeat-y;"></td>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
													<td></td>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<?php } ?>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
					<tr>
						<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 5</strong> </p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 5 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
						</td>
						<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>5-10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
						</td>
						<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
							<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 10</strong></p>
							<p style="font-size:12px; line-height:18px; margin:0; padding:0;">here there is a suspicion of atopic dermatitis elevated levels of mould-specific IgE may not be clinically relevant unless they are very high. An Additional borderline parameter is used for this group.</p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
					<tr>
						<td>
							<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
						</td>
					</tr>
				</table>
			<?php
			}
		}
		?>
		<table width="100%"><tr><td height="20"></td></tr></table>
		<?php if(preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)){ ?>
			<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url(); ?>assets/images/next-header2.jpg) left center no-repeat; background-size:cover;">
				<tr>
					<td valign="middle" width="430" style="padding:60px 30px 60px 50px;">
						<img src="<?php echo base_url(); ?>assets/images/nextlab-logo.jpg" alt="" style="max-height:100px; max-width:280px; border-radius:4px;" />
						<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;">Serum Test <br>treatment advice</h5>
					</td>
					<td valign="middle"></td>
				</tr>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
				<tr>
					<td>
						<h4 style="margin:0; color:#2a5b74; font-size:24px;">Step 3 - Starting the treatment</h4>
						<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;">Frequently asked questions</p>
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
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What is the dosage schedule?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Subcutaneous injections are administered with gradually increasing dosages. The schedule below is applicable for dogs, cats and horses. Please keep an eye on the patient for at least 30 minutes after every injection for any side effects.</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td colspan="2">
									<table align="center" width="360">
										<tr bgcolor="#326883">
											<th align="left" height="25" style="color:#ffffff; font-size:13px; padding:0 0 0 20px;">Adviced schedule</th>
											<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;">Dosage</th>
										</tr>
										<tr bgcolor="#326883">
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Week 1</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">0.2 ml</td>
										</tr>
										<tr bgcolor="#326883">
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">2 weeks later (week 3)</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">0.4 ml</td>
										</tr>
										<tr bgcolor="#326883">
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">2 weeks later (week 5)</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">0.6 ml</td>
										</tr>
										<tr bgcolor="#326883">
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">2 weeks later (week 7)</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">0.8 ml</td>
										</tr>
										<tr bgcolor="#326883">
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">3 weeks later (week 10)</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">1.0 ml</td>
										</tr>
										<tr bgcolor="#326883">
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">3 weeks later (week 13)</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">1.0 m</td>
										</tr>
										<tr bgcolor="#326883">
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">4 weeks later (week 17)</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">1.0 m</td>
										</tr>
										<tr bgcolor="#326883">
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">4 weeks later (week 21)</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">1.0 m</td>
										</tr>
										<tr bgcolor="#326883">
											<td colspan="2" align="center" bgcolor="#b8c6d6" style="padding:15px; font-size:12px; color:#1f4964;">Continue with 1.0 ml every 4 weeks for at least 12 months. If noticeable results, Artuvetrin® is a lifelong treatment.</td>
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
												<p style="margin:0 0 4px 0; padding:0; color:#1b3856; font-size:14px;"><strong>Artuvetrin® is a life-long treatment and compliance is key</strong></p>
												<p style="margin:0; padding:0; color:#1b3856; font-size:14px;">Allergy is a chronic disease and every 10 months a follow-up vial is required.</p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">Is it possible to deviate from the standard dosing schedule?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Yes, however this depends on the situation. Please contact our medical department at +31 320 783 100 for advice and support. </p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What is the success rate of Artuvetrin®? </h6></td>
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">The success rate of Artuvetrin® Therapy is 75% for dogs, 70% for cats and 84% for horses. Patients who respond, may expect a recovery from 50% up to 100%. Symptomatic medication might be stopped completely or decreased significantly.</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What if the patient did not respond at all?</h6></td>
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">If the patient did not show any improvement at all after 12 months, please contact our medical department on +31 320 783 100. There can be several reasons for a 0% response: concomitant food allergy, reaction to new allergens or not effective. We are happy to evaluate each case and help you with the relevant follow up.</p></td>
							</tr>
						</table>
						<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="47%">
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">If the symptoms are seasonal, can I administer only during that time?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">No, it should be administered continuously and life-long. If discontinued
								for a long period the immunological tolerance may be decreased and
								the treatment will have to be restarted from the initial dosage.</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What is the best time to start the treatment?</h6></td>
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">The treatment can be started at any time. It is recommended to
								have the skin under control before starting Artuvetrin®</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">Do I need to stop symptomatic medication
								before starting Artuvetrin®? </h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Symptomatic medication does not need to be stopped and it can
								help to keep the skin calm and under control in the initial phase
								(increasing dosage) of the desensitisation. Symptomatic medication
								does not affect the efficacy of the treatment and can be continued.</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">When can I expect to see improvements?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">In general, improvement can be noticed after 4 to 6 months.
								In some cases after 1 month. If there is no improvement at
								all after 6 months, please contact us at +31 320 783 100. We
								are happy to help you with your case.
								</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What to do with cases where the symptoms
								come back?</h6></td>	
							</tr>
							<tr>
								<td colspan="2">
									<p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">In case of a flare-up, the symptoms suddenly appear or worsen.
									This means that the animal has risen above the pruritic threshold.
									It can happen for different reasons:</p>
									<ul style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">
										<li>increased contact with allergens</li>
										<li>secondary infection (yeast, bacterial, etc.)</li>
										<li>other skin irritation due to fleas, swimming, more or less washing, etc.</li>
									</ul>
									<p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">
									With a flare-up, it is important to find the cause and prevent it.
									If the cause is unknown, it can sometimes be sufficient to correct
									the symptoms with temporary symptomatic medication</p>
								</td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">Can I stop the treatment if the symptoms are
								not present anymore?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">No, it is life long and should not be stopped. When stopping there
								is a significant chance the symptoms will come back and restarting
								the treatment will not be as affective as before.
								</p></td>
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
						<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;">Serum Test <br>treatment advice</h5>
					</td>					
					<td valign="middle"></td>
				</tr>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
				<tr>
					<td>
						<h4 style="margin:0; color:#2a5b74; font-size:24px;">About Next+</h4>
						<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;">Frequently asked questions</p>
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
								<td><h6 style="color:#333333; font-size:16px; margin:0;">If there are a lot of positives, will immuno-
								therapy be useful?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Where there are a high number of positive allergens, this does not
								affect the efficacy of the immunotherapy.</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What can the owner do to prevent exposure to the positive allergens?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Many things can be done to prevent or reduce contact with allergens. Please request our Allergen Guide, this brochure contains
								tips for all allergens we test for. </p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What if Malassezia is positive? </h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Malassezia is mostly a secondary problem of atopic dermatitis.
								If Malassezia is suspected to be causing the allergy, consider including
								it in the immunotherapy.</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What if moulds are positive?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Moulds may be only clinically relevant if the animal lives in a moist environment indoors. Should this be the case, we recommend to lower the humidity indoors or remove the moulds with mould cleaner first. Please refer to our Allergen Guide for more tips for moulds. If these adjustments give no or partial improvement, consider including it in the immunotherapy. </p></td>						
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">What if flea has been tested positive?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">A flea bite hypersensitivity reflects type I and type IV hypersensitivity reaction. As immunotherapy only works for type 1, the best
								treatment is a good flea treatment in all seasons of the year.</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">Do the units correlate with the clinical signs?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">No, the magnitude of the units does not necessarily correlate with the severity of the disease but does reflect the animal’s immune response to allergens.</p></td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
								<td><h6 style="color:#333333; font-size:16px; margin:0;">Can symptomatic medication affect the result?</h6></td>	
							</tr>
							<tr>
								<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Corticosteroids may affect the serum test results if administered
								longer than 2-3 months (oral medication).</p></td>
							</tr>
						</table>
						<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="47%">
							<tr>
								<td>
									<table style="background:#edf2f4; padding:20px; border-radius:10px;">
										<tr>
											<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
											<td><h6 style="color:#333333; font-size:16px; margin:0;">What are CCDs?</h6></td>	
										</tr>
										<tr>
											<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">CCDs are cross-reactive carbohydrate determinants – the carbohydrate chains found in glycoproteins. CCDs are part of a structure of many allergy-causing proteins, especially plant-based allergens like pollen.</p></td>
										</tr>
										<tr><td height="30"></td></tr>
										<tr>
											<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
											<td><h6 style="color:#333333; font-size:16px; margin:0;">How are CCDs involved in the allergic
											reaction??</h6></td>
										</tr>
										<tr>
											<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">During an allergic reaction, IgE is produced against the carbohydrate chains as well as the allergen proteins. Studies have confirmed that this occurs in 30% of humans, dogs and cats1-3. The IgE against CCD chains do not seem to be clinically relevant.</p></td>
										</tr>
										<tr><td height="30"></td></tr>
										<tr>
											<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
											<td><h6 style="color:#333333; font-size:16px; margin:0;">Why is it important to block CCDs?</h6></td>	
										</tr>
										<tr>
											<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Blocking CCDs means that the specificity of the in vitro test is enhanced. Evidence shows that the correlation with intradermal testing is also improved<sup>3</sup>.</p></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="30"></td></tr>
							<tr>
								<td height="30">
									<table align="center" width="460">
										<tr bgcolor="#326883">
											<th align="left" height="45" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;">Allergens</th>
											<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;">Without<br>CCD blocker</th>
											<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;">Without<br>CCD blocker</th>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Phleum pratense</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Poa pratensis</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Dactylis glomerata</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Lolium perenne</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Rumex acetosella</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Urtica spp.</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Chenopodium album</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Artemisa vulgaris</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Ambrosia eliator</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Betula pendula</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Corylus avellana</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Salix viminalis</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">Ulmus americana</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">486</td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">375</td>
										</tr>
										<tr>
											<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><strong>Positive allergens</strong></td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><strong>486</strong></td>
											<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><strong>375</strong></td>
										</tr>
										<tr>
											<td colspan="3" bgcolor="#ffffff" style="padding:15px 0 15px 0; font-size:13px; color:#1f4964;">Figure 1. CCD blocking reduces non-relevant positive allergens.</td>
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
						<h5 style="margin:0 0 3px 0; padding:0; color:#326883; font-size:15px;">Do you have any additional questions?</h5>
						<p style="margin:0 0 0 0; padding:0; color:#326883; font-size:13px;">Please call our medical department on +31 320 783 100 or send an email to info.eu@nextmune.com.</p>
					</td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td style="padding:0 0 0 20px;">
						<ol style="color:#19455c; margin:0; padding:0; font-size:12px; line-height:20px;">
							<li>Petersen A et al Ubiquitous structures responsible for IgE cross-reactivity between tomato fruit and grass pollen allergens J Allergy Clin Immunol 1996 Oct; 98(4):805-15</li>
							<li>Mari A et al Specific IgE to cross-reactive carbohydrate determinants strongly affect the in vitro diagnosis of allergic diseases J Allergy Clin Immunol 1999; 103(6):1006-1011</li>
							<li>Gedon NKY et al Agreement of serum allergen test results with unblocked and blocked IgE against cross-reactive carbohydrate determinants (CCD) and intradermal test results in atopic dogs Vet Dermatol 2019; 30(3):195</li>
						</ol>
					</td>
				</tr>
			</table>
		<?php } ?>
	</body>
</html>