<?php
if(!empty($serumType)){
	$stypeIDArr = array(); $sresultIDArr = array(); 
	foreach($serumType as $stype){
		$stypeIDArr[] = $stype->type_id;
		$sresultIDArr[] = $stype->result_id;
	}
}else{
	$this->session->set_flashdata('error', 'LIMS Result data are empty.');
	redirect('orders');	
}

$stypeID = implode(",",$stypeIDArr);
$sresultID = implode(",",$sresultIDArr);

$serumResultsenv = $this->OrdersModel->getSerumTestResultEnv($sresultID,$stypeID);
if(!empty($serumResultsenv)){
	$optn2Arr = $optnenvArr = $moduleArr = $optionenv = []; $block1 = 0;
	foreach($serumResultsenv as $row1){
		$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id,$order_details['allergens']);
		if(!empty($algName)){
			$optionenv['algid'] = $algName->id;
			$optionenv['name'] = $algName->name;
			$optionenv['result'] = $row1->result;
			if($algName->parent_id == '6'){
				if($row1->result >= 1200){
					$optn2Arr[] = $algName->id;
					$block1++;
				}
				$moduleArr[] = $optionenv;
			}else{
				if($row1->result >= 100){
					$optn2Arr[] = $algName->id;
					$block1++;
				}
				$optnenvArr[] = $optionenv;
			}
		}
	}
}else{
	$block1 = 0;
	$optnenvArr = [];
	$moduleArr = [];
	$optn2Arr = [];
}
if(!empty($moduleArr)){
	$this->db->select('name,result');
	$this->db->from('ci_serum_result_allergens');
	$this->db->where('result_id IN('.$sresultID.')');
	$this->db->where('type_id IN('.$stypeID.')');
	$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
	$this->db->order_by('result', 'DESC');
	$malasseziaResults = $this->db->get()->row();
	if(!empty($malasseziaResults)){
		if($malasseziaResults->result >= 1200){
			$block1++;
			$optn2Arr[] = 81;
		}
		$optionmenv['algid'] = 81;
		$optionmenv['name'] = $malasseziaResults->name;
		$optionmenv['result'] = $malasseziaResults->result;
		$moduleArr[] = $optionmenv;
	}
}

$block2 = [];
if(!empty($optn2Arr)){
	$getAllergenParent = $this->AllergensModel->getAllergenParent(json_encode($optn2Arr));
	foreach($getAllergenParent as $apvalue){
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
						$emptyArr = array_intersect($optn2Arr,json_decode($getGroupMixtures[$makey]['mixture_allergens']));
						if(count($emptyArr) >= 2){
							if($getGroupMixtures[$makey]['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($getGroupMixtures[$makey]['id']) > 0){
								$block2[$getGroupMixtures[$makey]['id']] = $getGroupMixtures[$makey]['name'];
							}
						}else{
							$sub0Allergens = $this->AllergensModel->get_subAllergens_dropdown($getGroupMixtures[$makey]['parent_id'], $getGroupMixtures[$makey]['mixture_allergens']);
							foreach($sub0Allergens as $s0value){
								if(in_array($s0value['id'],$emptyArr) && $s0value['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($s0value['id'])){
									$block2[$s0value['id']] = $s0value['name'];
								}
							}
						}
					}
				}else{
					$matchValue = array_intersect($optn2Arr,json_decode($getGroupMixtures[0]['mixture_allergens']));
					if(count($matchValue) >= 2){
						if($getGroupMixtures[0]['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($getGroupMixtures[0]['id'])){
							$block2[$getGroupMixtures[0]['id']] = $getGroupMixtures[0]['name'];
						}
					}else{
						$sub1Allergens = $this->AllergensModel->get_subAllergens_dropdown2($getGroupMixtures[0]['parent_id'],json_encode($optn2Arr), $getGroupMixtures[0]['mixture_allergens']);
						foreach($sub1Allergens as $s1value){
							if($s1value['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($s1value['id'])){
								$block2[$s1value['id']] = $s1value['name'];
							}
						}
					}
				}
			}else{
				$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], json_encode($optn2Arr));
				foreach($sub2Allergens as $s2value){
					if($s2value['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($s2value['id'])){
						$block2[$s2value['id']] = $s2value['name'];
					}
				}
			}
		}elseif($block1 > 1){
			$sub3Allergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], json_encode($optn2Arr));
			foreach($sub3Allergens as $s3value){
				if($s3value['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($s3value['id'])){
					$block2[$s3value['id']] = $s3value['name'];
				}
			}
		}
	}
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

$this->db->select('name');
$this->db->from('ci_price');
$this->db->where('id', $order_details['product_code_selection']);
$respnedn = $this->db->get()->row();
$ordeType = $respnedn->name;
if($order_details['pet_id']>0){
	$this->db->select('type,breed_id,other_breed,gender,age,age_year');
	$this->db->from('ci_pets');
	$this->db->where('id', $order_details['pet_id']);
	$petinfo = $this->db->get()->row_array();

	if($petinfo['breed_id']>0){
		$this->db->select('name');
		$this->db->from('ci_breeds');
		$this->db->where('id', $petinfo['breed_id']);
		$breedinfo = $this->db->get()->row_array();
	}else{
		$breedinfo = array();
	}
}else{
	$petinfo = array();
	$breedinfo = array();
}

if($order_details['vet_user_id']>0){
	$refDatas = $this->UsersDetailsModel->getColumnAllArray($order_details['vet_user_id']);
	$refDatas = array_column($refDatas, 'column_field', 'column_name');
	$add_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'].', ' : '';
	$add_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'].', ' : '';
	$add_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
	$city = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
	$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
	$fulladdress = $add_1.$add_2.$add_3;
}else{
	$fulladdress = '';
	$city = '';
	$postcode = '';
}
$serumdata = $this->OrdersModel->getSerumTestRecord($order_details['id']);
$years = !empty($petinfo['age_year'])?$petinfo['age_year'].'Year, ':'';
$months = !empty($petinfo['age'])?$petinfo['age'].'Month':'';
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
		<?php if(preg_match('/\bAcute Phase Proteins\b/', $respnedn->name)){ ?>
			<style>
			.green_strip{background:#366784; padding:5px 10px; color:#ffffff; font-size:18px;}
			.green_bordered{border:1px solid #366784; padding:10px; color:#333333; font-size:18px;}
			.food_table{font-size:16px; line-height:28px;}
			.food_table tr th{border-bottom:1px solid #333333;}
			</style>
			<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="100" style="width:100%; max-width:1030px;background:#ffffff;">
				<tr>
					<td style="padding: 0px 10px;">
						<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
							<tr>
								<td valign="middle" width="270">
									<img src="<?php echo base_url("/assets/images/nextmune-uk.png"); ?>" height="60" alt="" />
								</td>
								<td valign="top">
									<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;padding-top:10px">
										<tr>
											<th style="color:#346a7e;">Owner name:</th>
											<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Animal Name:</th>
											<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
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
											<th style="color:#346a7e;">Date tested:</th>
											<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Laboratory number:</th>
											<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Order number:</th>
											<td style="color:#000000;"><?php echo !empty($order_details['reference_number'])?$order_details['reference_number']:$order_details['order_number']; ?></td>
										</tr>
									</table>
								</td>
								<td style="line-height:0;" align="right">
									<img src="<?php echo base_url("/assets/images/header-cat.png?v=0.1"); ?>" height="140" alt="" />
								</td>
							</tr>
						</table>
						<table class="green_strip" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
							<tr>
								<td>α1-ACID GLYCOPROTEIN (AGP) RESULTS</td>
							</tr>
						</table>
						<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
							<tr>
								<td colspan="3" height="20"></td>
							</tr>
							<tr>
								<th align="left">Acute Phase Protein</th>
								<th style="text-align:center">Concentration</th>
								<th style="text-align:center">Normal Values*</th>
							</tr>
							<tr>
								<td>Alpha-1-Acid Glycoprotein (AGP)</td>
								<?php
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1386")');
								$this->db->order_by('result', 'DESC');
								$agpResults = $this->db->get()->row();
								if(!empty($agpResults)){
									echo '<td style="text-align:center">'. $agpResults->result .' g/L</td>';
								}else{
									echo '<td style="text-align:center">0 g/L</td>';
								}
								?>
								<td style="text-align:center">0.1 to 0.5 g/L</td>
							</tr>
							<tr>
								<td colspan="3" height="20"></td>
							</tr>
						</table>
						<table class="green_bordered" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
							<tr>
								<td>
									<p style="margin:0 0 10px 0; font-size:13px;">When interpreting APP results, in addition to the current medical status, the full clinical history including medications at the time of sampling alongside other diagnostic test results should always be factored into the evaluation. Please contact us for further guidance.</p>
									<p style="margin:0 0 10px 0; font-size:13px;">&nbsp;</p>
									<p style="margin:0; font-size:13px;">* This value represents an approximation taken from veterinary literature and can vary between individuals. Acute phase protein concentrations are generally low to undetectable in healthy animals. By definition circulating APP levels vary by at least 25% in response to inflammation or infection and concentrations usually increase by factors of 10 to 100.</p>
									<p style="margin:0 0 10px 0; font-size:13px;">&nbsp;</p>
									<p style="margin:0; font-size:13px;">Please note: Normal Values are applicable to serum samples only</p>
								</td>
							</tr>
						</table>
						<table width="100%"><tr><td height="20"></td></tr></table>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>
		<?php }else{ ?>
			<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;background-image:url(<?php echo base_url("/assets/images/next-header-new.jpg"); ?>); background-color: #ffffff; background-repeat: no-repeat; background-position: right;">
				<tr>
					<td valign="middle" width="100%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td width="400" style="padding: 0 0 0 40px;">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td><img src="<?php echo base_url("/assets/images/next-logo.png"); ?>" alt="Logo" style="max-width: 180px;" /></td>
										</tr>
										<tr>
											<td style="height: 20px;"></td>
										</tr>
										<tr>
											<td style="font-size:18px; color:#fff;"><b>Serum Test results</b></td>
										</tr>
									</table>
								</td>
								<td valign="middle" style="padding: 0 40px 0 0;">
									<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:12px; line-height:18px;">
										<tr>
											<td style="height: 40px;"></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Owner name:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Animal Name:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $order_details['pet_name']; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Species:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $order_details['species_name'];?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Veterinarian:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $order_details['name']; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Veterinary practice:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $order_details['practice_name']; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Address:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $fulladdress; ?></td>
										</tr>
										<!-- <tr>
											<th style="color:#346a7e;">Phone / Fax:</th>
											<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Email:</th>
											<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
										</tr> -->
										<tr>
											<th style="color:#346a7e;">Test type:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $ordeType; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Date tested:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
										</tr>
										<!-- <tr>
											<th style="color:#346a7e;">Customer number:</th>
											<td style="color:#000000;"><?php echo !empty($order_details['reference_number'])?$order_details['reference_number']:$order_details['order_number']; ?></td>
										</tr> -->
										<tr>
											<th style="color:#346a7e;">Lab:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $order_details['lab_order_number']; ?></td>
										</tr>
										<tr>
											<td style="height: 40px;"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>
			<?php
			if(!empty($respnedn)){
				if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bComplete Food\b/', $respnedn->name))){
				?>
					<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 2mm 8mm 2mm;">
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
									if($serumResults->result >= 201){
										$countergP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumResults->result >= 201){
										$counterwP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumResults->result >= 201){
										$countertP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumcResults->result >= 201){
										$countercP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($ivalue['parent_id'] == '6'){
										if($serumResults->result >= 1501){
											$counteriP++;
										}elseif($serumResults->result <= 1500 && $serumResults->result >= 1200){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}else{
										if($serumResults->result >= 201){
											$counteriP++;
										}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
											$counteriB++;
										}else{
											$counteriN++;
										}
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

							if($order_details['species_name'] == 'Horse'){
								/* Start Insects */
								$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $order_details['allergens']);
								$counteriN = $counteriB = $counteriP = 0;
								foreach($insectAllergens as $ivalue){
									$this->db->select('*');
									$this->db->from('ci_serum_result_allergens');
									$this->db->where('result_id IN('.$sresultID.')');
									$this->db->where('type_id IN('.$stypeID.')');
									$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
									$this->db->order_by('id', 'ASC');
									$serumiResults = $this->db->get()->row();
									if(!empty($serumiResults)){
										if($serumiResults->result >= 201){
											$counteriP++;
										}elseif($serumiResults->result <= 200 && $serumiResults->result >= 100){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}
								}
								echo '<tr>
									<td style="width:300px">Insects</td>';
									if($counteriP > 0){
										echo '<td>POSITIVE</td>';
									}elseif($counteriB > 0){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								echo '</tr>';
								echo '<tr><td colspan="2">&nbsp;</td></tr>';
								/* End Insects */
							}

							if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994' || $order_details['lab_id']=='13788')){
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
										if($fleaResults->result >= 201){
											echo '<td>POSITIVE</td>';
										}elseif($fleaResults->result <= 200 && $fleaResults->result >= 100){
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
										if($malasseziaResults->result >= 201){
											echo '<td>POSITIVE</td>';
										}elseif($malasseziaResults->result <= 200 && $malasseziaResults->result >= 100){
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
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_food_'.$id.'.png'; ?>" alt="Food Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php /* <table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
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
					<table width="100%"><tr><td height="10"></td></tr></table> */ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="font-size:12px; line-height:18px; margin:0; padding:0;">**Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
							</td>
						</tr>
					</table>
				<?php }elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){ ?>
					<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 3mm 8mm 2mm;">
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
									if($serumResults->result >= 201){
										$countergP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumResults->result >= 201){
										$counterwP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumResults->result >= 201){
										$countertP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumcResults->result >= 201){
										$countercP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($ivalue['parent_id'] == '6'){
										if($serumResults->result >= 1501){
											$counteriP++;
										}elseif($serumResults->result <= 1500 && $serumResults->result >= 1200){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}else{
										if($serumResults->result >= 201){
											$counteriP++;
										}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
											$counteriB++;
										}else{
											$counteriN++;
										}
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

							if($order_details['species_name'] == 'Horse'){
								/* Start Insects */
								$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $order_details['allergens']);
								$counteriN = $counteriB = $counteriP = 0;
								foreach($insectAllergens as $ivalue){
									$this->db->select('*');
									$this->db->from('ci_serum_result_allergens');
									$this->db->where('result_id IN('.$sresultID.')');
									$this->db->where('type_id IN('.$stypeID.')');
									$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
									$this->db->order_by('id', 'ASC');
									$serumiResults = $this->db->get()->row();
									if(!empty($serumiResults)){
										if($serumiResults->result >= 201){
											$counteriP++;
										}elseif($serumiResults->result <= 200 && $serumiResults->result >= 100){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}
								}
								echo '<tr>
									<td style="width:300px">Insects</td>';
									if($counteriP > 0){
										echo '<td>POSITIVE</td>';
									}elseif($counteriB > 0){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								echo '</tr>';
								echo '<tr><td colspan="2">&nbsp;</td></tr>';
								/* End Insects */
							}

							if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994' || $order_details['lab_id']=='13788')){
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
										if($fleaResults->result >= 201){
											echo '<td>POSITIVE</td>';
										}elseif($fleaResults->result <= 200 && $fleaResults->result >= 100){
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
										if($malasseziaResults->result >= 201){
											echo '<td>POSITIVE</td>';
										}elseif($malasseziaResults->result <= 200 && $malasseziaResults->result >= 100){
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
				<?php }elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){ ?>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 3mm 8mm 2mm;">
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
									if($fpResults->result >= 201){
										$counterFPP++;
									}elseif($fpResults->result <= 200 && $fpResults->result >= 100){
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
									if($fcResults->result >= 201){
										$counterFCP++;
									}elseif($fcResults->result <= 200 && $fcResults->result >= 100){
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
				<?php }elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (!preg_match('/\bFood Panel\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name))){ ?>
					<?php if(!empty($optnenvArr)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_env_'.$id.'.png'; ?>" alt="Environmental Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php } ?>
					<?php if(!empty($moduleArr)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_module_'.$id.'.png'; ?>" alt="Moulds Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php } ?>
					<?php /* <table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding: 3mm 8mm 2mm;">
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
					<table width="100%"><tr><td height="10"></td></tr></table> */ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
							</td>
						</tr>
					</table>
				<?php }elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_food_'.$id.'.png'; ?>" alt="Food Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php /* <table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
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
					<table width="100%"><tr><td height="10"></td></tr></table> */ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
							</td>
						</tr>
					</table>
				<?php }elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood Panel\b/', $respnedn->name))){ ?>
					<?php if(!empty($optnenvArr)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_env_'.$id.'.png'; ?>" alt="Environmental Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php } ?>
					<?php if(!empty($moduleArr)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_module_'.$id.'.png'; ?>" alt="Moulds Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php } ?>
					<?php
					$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
					if(!empty($serumResultsfod)){
						$rsultcount = 0;
						foreach($serumResultsfod as $rowf){
							$algName = $this->OrdersModel->getAllergensName($rowf->lims_allergens_id,$order_details['allergens']);
							if(!empty($algName) && $algName->name !='N/A'){
								$rsultcount++;
							}
						}
					}
					if($rsultcount > 0){
					?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_food_'.$id.'.png'; ?>" alt="Food Panel" style="width: 100%; height: 150px; max-width: 99%;padding: 1mm 8mm 2mm;" /></td></tr></table>
					<?php } ?>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php /* <table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
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
					<table width="100%"><tr><td height="10"></td></tr></table> */ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
							</td>
						</tr>
					</table>
				<?php }elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood SCREEN\b/', $respnedn->name))){ ?>
					<?php if(!empty($optnenvArr)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_env_'.$id.'.png'; ?>" alt="Environmental Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php } ?>
					<?php if(!empty($moduleArr)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_module_'.$id.'.png'; ?>" alt="Moulds Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php } ?>
					<?php /* <table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
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
					<table width="100%"><tr><td height="10"></td></tr></table> */ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;padding: 3mm 8mm 2mm;">
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
									if($fpResults->result >= 201){
										$counterFPP++;
									}elseif($fpResults->result <= 200 && $fpResults->result >= 100){
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
									if($fcResults->result >= 201){
										$counterFCP++;
									}elseif($fcResults->result <= 200 && $fcResults->result >= 100){
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
				<?php }elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){ ?>
					<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;padding: 3mm 8mm 2mm;">
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
									if($serumResults->result >= 201){
										$countergP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumResults->result >= 201){
										$counterwP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumResults->result >= 201){
										$countertP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumcResults->result >= 201){
										$countercP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($ivalue['parent_id'] == '6'){
										if($serumResults->result >= 1501){
											$counteriP++;
										}elseif($serumResults->result <= 1500 && $serumResults->result >= 1200){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}else{
										if($serumResults->result >= 201){
											$counteriP++;
										}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
											$counteriB++;
										}else{
											$counteriN++;
										}
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

							if($order_details['species_name'] == 'Horse'){
								/* Start Insects */
								$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $order_details['allergens']);
								$counteriN = $counteriB = $counteriP = 0;
								foreach($insectAllergens as $ivalue){
									$this->db->select('*');
									$this->db->from('ci_serum_result_allergens');
									$this->db->where('result_id IN('.$sresultID.')');
									$this->db->where('type_id IN('.$stypeID.')');
									$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
									$this->db->order_by('id', 'ASC');
									$serumiResults = $this->db->get()->row();
									if(!empty($serumiResults)){
										if($serumiResults->result >= 201){
											$counteriP++;
										}elseif($serumiResults->result <= 200 && $serumiResults->result >= 100){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}
								}
								echo '<tr>
									<td style="width:300px">Insects</td>';
									if($counteriP > 0){
										echo '<td>POSITIVE</td>';
									}elseif($counteriB > 0){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								echo '</tr>';
								echo '<tr><td colspan="2">&nbsp;</td></tr>';
								/* End Insects */
							}

							if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994' || $order_details['lab_id']=='13788')){
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
										if($fleaResults->result >= 201){
											echo '<td>POSITIVE</td>';
										}elseif($fleaResults->result <= 200 && $fleaResults->result >= 100){
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
										if($malasseziaResults->result >= 201){
											echo '<td>POSITIVE</td>';
										}elseif($malasseziaResults->result <= 200 && $malasseziaResults->result >= 100){
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
					<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 99%;padding: 3mm 8mm 2mm;">
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
									if($fpResults->result >= 201){
										$counterFPP++;
									}elseif($fpResults->result <= 200 && $fpResults->result >= 100){
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
									if($fcResults->result >= 201){
										$counterFCP++;
									}elseif($fcResults->result <= 200 && $fcResults->result >= 100){
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
				<?php }elseif(preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name)){ ?>
					<?php if(!empty($optnenvArr)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_env_'.$id.'.png'; ?>" alt="Environmental Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php } ?>
					<?php if(!empty($moduleArr)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_module_'.$id.'.png'; ?>" alt="Moulds Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php } ?>
					<?php if(preg_match('/\bFood\b/', $respnedn->name)){
						$serumResultsfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
						if(!empty($serumResultsfod)){
							$rsultcount = 0;
							foreach($serumResultsfod as $rowf){
								$algName = $this->OrdersModel->getAllergensName($rowf->lims_allergens_id,$order_details['allergens']);
								if(!empty($algName) && $algName->name !='N/A'){
									$rsultcount++;
								}
							}
						}
						if($rsultcount > 0){
						?>
							<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_food_'.$id.'.png'; ?>" alt="Food Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
						<?php } ?>
					<?php } ?>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<?php /* <table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
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
					<table width="100%"><tr><td height="10"></td></tr></table> */ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
						<tr>
							<td>
								<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
							</td>
						</tr>
					</table>
				<?php }elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){ ?>
					<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 3mm 8mm 2mm;">
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
									if($serumResults->result >= 201){
										$countergP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumResults->result >= 201){
										$counterwP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumResults->result >= 201){
										$countertP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($serumcResults->result >= 201){
										$countercP++;
									}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
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
									if($ivalue['parent_id'] == '6'){
										if($serumResults->result >= 1501){
											$counteriP++;
										}elseif($serumResults->result <= 1500 && $serumResults->result >= 1200){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}else{
										if($serumResults->result >= 201){
											$counteriP++;
										}elseif($serumResults->result <= 200 && $serumResults->result >= 100){
											$counteriB++;
										}else{
											$counteriN++;
										}
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

							if($order_details['species_name'] == 'Horse'){
								/* Start Insects */
								$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $order_details['allergens']);
								$counteriN = $counteriB = $counteriP = 0;
								foreach($insectAllergens as $ivalue){
									$this->db->select('*');
									$this->db->from('ci_serum_result_allergens');
									$this->db->where('result_id IN('.$sresultID.')');
									$this->db->where('type_id IN('.$stypeID.')');
									$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
									$this->db->order_by('id', 'ASC');
									$serumiResults = $this->db->get()->row();
									if(!empty($serumiResults)){
										if($serumiResults->result >= 201){
											$counteriP++;
										}elseif($serumiResults->result <= 200 && $serumiResults->result >= 100){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}
								}
								echo '<tr>
									<td style="width:300px">Insects</td>';
									if($counteriP > 0){
										echo '<td>POSITIVE</td>';
									}elseif($counteriB > 0){
										echo '<td>BORDER LINE</td>';
									}else{
										echo '<td>NEGATIVE</td>';
									}
								echo '</tr>';
								echo '<tr><td colspan="2">&nbsp;</td></tr>';
								/* End Insects */
							}

							if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994' || $order_details['lab_id']=='13788')){
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
										if($fleaResults->result >= 201){
											echo '<td>POSITIVE</td>';
										}elseif($fleaResults->result <= 200 && $fleaResults->result >= 100){
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
										if($malasseziaResults->result >= 201){
											echo '<td>POSITIVE</td>';
										}elseif($malasseziaResults->result <= 200 && $malasseziaResults->result >= 100){
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
					<?php if(preg_match('/\bFood\b/', $respnedn->name)){ ?>
						<table width="100%"><tr><td height="20"></td></tr></table>
						<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_food_'.$id.'.png'; ?>" alt="Food Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
						<table width="100%"><tr><td height="20"></td></tr></table>
						<?php /* <table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
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
						<table width="100%"><tr><td height="10"></td></tr></table> */ ?>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
							<tr>
								<td>
									<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
								</td>
							</tr>
						</table>
					<?php } ?>
				<?php
				}
			}
			?>
			<?php
			$option1 = [];
			foreach($optnenvArr as $row3){
				if($row3['result'] >= 100 && $this->AllergensModel->checkforArtuveterinallergen($row3['algid']) > 0){
					$option1[$row3['algid']] = $row3['name'];
				}
			}
			foreach($moduleArr as $row4){
				if($row4['result'] >= 1200 && $this->AllergensModel->checkforArtuveterinallergen($row4['algid']) > 0){
					$option1[$row4['algid']] = $row4['name'];
				}
			}

			if(!empty($option1)){
				$this->db->select('name,result');
				$this->db->from('ci_serum_result_allergens');
				$this->db->where('result_id IN('.$sresultID.')');
				$this->db->where('type_id IN('.$stypeID.')');
				$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
				$this->db->order_by('result', 'DESC');
				$malasseziaResults = $this->db->get()->row();
				if(!empty($malasseziaResults)){
					if($malasseziaResults->result >= 1200 && $this->AllergensModel->checkforArtuveterinallergen(81) > 0){
						$option1[81] = 'Malassezia';
					}
				}
			}

			if($order_details['treatment_1'] != "" && $order_details['treatment_1'] != "[]"){
				$subAllergnArr = $this->AllergensModel->getNextlabAllergensByID($order_details['treatment_1'],$order_details['allergens']);
				if(!empty($subAllergnArr)){
					foreach ($subAllergnArr as $svalue){
						$option1[$svalue['id']] = $svalue['name'];
						$block1++;
					}
				}
			}
			asort($option1);
			?>
			<?php if(((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))) && $block1 > 0){ ?>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding: 3mm 8mm 2mm;">
					<tr>
						<td>
							<h4 style="margin:0; color:#2a5b74; font-size:24px;"></h4>
						</td>
						<td align="right">
							<p style="margin:0; color:#333333; font-size:14px;">Laboratory Number <?php echo $order_details['lab_order_number'];?> - Nextvu Order Number <?php echo $order_details['order_number'];?></p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table width="100%" style="padding: 1mm 8mm 2mm;">
					<tr>
						<td valign="top" height="600" style="height:600px;">
							<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="left" style="margin-left:40px;margin-left: 20px;min-height:220px">
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="340">
									</td>
								</tr>
								<tr>
									<td style="background:#326883; padding: 0 20px 20px; color:#ffffff; font-size:20px;">
										<input type="checkbox" style="background:#e4eaed; padding:50px;font-size:20pt; height:60px !important; border:1px solid #4d5d67; width:60px !important;" />
										<b>&nbsp; <?php echo $this->lang->line('Treatment_option'); ?> 1</b>
									</td>
								</tr>
								<tr>
									<td valign="top" height="220" bgcolor="#e2f2f4" style="padding:20px;height: 450px;">
										<ol style="color:#184359; margin:15px 0 0 20px; padding:0;">
											<?php 
											$a=0;
											foreach($option1 as $key=>$value){
												?>
												<li style="font-size:17px; line-height: 26px;"><?php echo $value; ?></li>
												<?php 
												$a++;
											}
											$quotient = ($a/8);
											$totalViald = ((round)($quotient));
											$demimal = $quotient-$totalViald;
											if($demimal > 0){
												$totalViald = $totalViald+1;
											}
											?>
										</ol>
									</td>
								</tr>
								<tr>
									<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff;">
										<table cellpadding="0" cellspacing="0" border="0" width="100%;">
											<tr>
												<td height="20"></td>
											</tr>
											<tr>
												<th colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('option_results'); ?>:</th>
											</tr>
											<tr><td height="8"></td></tr>
											<tr>
												<td width="30%">
													<input type="text" value="<?php echo $totalViald; ?>" style="background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;font-size:20pt;" />
												</td>
												<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('Subcutaneous_immunotherapy'); ?> </td>
											</tr>
											<tr>
												<td height="40"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="340">
									</td>
								</tr>
							</table>
						</td>
						<?php if(!empty($block2) && $option1 != $block2){ ?>
						<td valign="top" height="600" style="height:600px;">
							<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="left" style="margin-left:40px;margin-left: 20px;min-height:220px">
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="340">
									</td>
								</tr>
								<tr>
									<td style="background:#326883; padding: 0 20px 20px; color:#ffffff; font-size:20px;">
										<input type="checkbox" style="background:#e4eaed; padding:50px;font-size:20pt; height:60px !important; border:1px solid #4d5d67; width:60px !important;" /> <b>&nbsp; <?php echo $this->lang->line('Treatment_option'); ?> 2</b>
									</td>
								</tr>
								<tr>
									<td valign="top" height="220" bgcolor="#e2f2f4" style="padding:20px;height: 450px;">
										<ol style="color:#184359; margin:15px 0 0 20px; padding:0;">
											<?php 
											$b=0; $totalViald = 0;
											foreach($block2 as $key=>$value){ ?>
												<li style="font-size:17px; line-height: 26px;"><?php echo $value; ?></li>
											<?php 
												$b++;
											}
											$quotient = ($b/8);
											$totalViald = ((round)($quotient));
											$demimal = $quotient-$totalViald;
											if($demimal > 0){
												$totalViald = $totalViald+1;
											}
											?>
										</ol>
									</td>
								</tr>
								<tr>
									<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff;">
										<table cellpadding="0" cellspacing="0" border="0" width="100%;">
											<tr>
												<td height="20"></td>
											</tr>
											<tr>
												<th colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('option_results'); ?>:</th>
											</tr>
											<tr><td height="8"></td></tr>
											<tr>
												<td width="30%"><input type="text" value="<?php echo $totalViald; ?>" style="font-size:20pt;background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;" /></td>
												<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('Subcutaneous_immunotherapy'); ?> </td>
											</tr>
											<tr>
												<td height="40"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="340">
									</td>
								</tr>
							</table>
						</td>
						<?php } ?>
						<td valign="top" height="600" style="height:600px;">	
							<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style=" margin-left:20px;">
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="340">
									</td>
								</tr>
								<tr>
									<td style="background:#326883; padding: 0 20px 20px; color:#ffffff; font-size:18px;">
										<input type="checkbox" style="background:#e4eaed; padding:50px;font-size:20pt; height:60px !important; border:1px solid #4d5d67; width:60px !important;" /> <b>&nbsp; Compose your own</b>
									</td>
								</tr>
								<tr>
									<td height="220" bgcolor="#e2f2f4" style="padding:20px;height: 450px;">
										<ol style="color:#184359; font-size:20px; margin:15px 0 0 20px; padding:0;">
											<li>&nbsp;</li>
											<li>&nbsp;</li>
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
												<td style="font-size: 15px; line-height: 22px">Artuvetrin® subcutaneous immunotherapy: up to 8 allergens and/or allergen mixtures can be included into 1 vial. For cases with more than 8 allergens, additional vial(s) will be produced.</td>
											</tr>
											<tr>
												<td height="40"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="340">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div style='page-break-after:always'></div>
			<?php } ?>
			<table width="100%"><tr><td height="20"></td></tr></table>
			<?php if((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))){ ?>
				<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-collapse: collapse; background:url(<?php echo base_url(); ?>assets/images/next-header2.jpg) top center no-repeat; background-size:cover;">
					<tr>
						<td valign="middle">
							<tr>
								<td>
									<table style="width:100%; padding:100px 0px 100px 15px;">
										<tr>
											<td>
												<img src="<?php echo base_url(); ?>assets/images/nextlab-logo.jpg" alt="" style="max-height:100px; max-width:280px; border-radius:4px;margin-bottom: 25px;" />                    
											</td>
										</tr>
										<tr>
											<td>
												<h5 style="margin-top:20px; font-weight:700; font-size:20px; color:#ffffff;">Serum Test <br>treatment advice</h5>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</td>
						<td valign="middle"></td>
					</tr>
				</table>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding: 0mm 8mm 2mm;">
					<tr>
						<td>
							<h4 style="margin:0; color:#2a5b74; font-size:22px;">Step 3 - Starting the treatment</h4>
							<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;">Frequently asked questions</p>
						</td>
					</tr>
				</table>
				<table width="100%" style="padding: 1.5mm 8mm 2mm;">
					<tr>
						<td valign="top">
							<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="100%">
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">What is the dosage schedule?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">Subcutaneous injections are administered with gradually increasing dosages. The schedule below is applicable for dogs, cats and horses. Please keep an eye on the patient for at least 30 minutes after every injection for any side effects.</p></td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td colspan="2">
										<table align="center" width="360">
											<tr bgcolor="#326883">
												<th align="left" height="25" style="color:#ffffff; font-size:13px; padding:0 0 0 20px;">Advised schedule</th>
												<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;">Dosage</th>
											</tr>
											<tr bgcolor="#326883">
												<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:14px; color:#1f4964;">Week 1</td>
												<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:14px; color:#1f4964;">0.2 ml</td>
											</tr>
											<tr bgcolor="#326883">
												<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:14px; color:#1f4964;">2 weeks later (week 3)</td>
												<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:14px; color:#1f4964;">0.4 ml</td>
											</tr>
											<tr bgcolor="#326883">
												<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:14px; color:#1f4964;">2 weeks later (week 5)</td>
												<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:14px; color:#1f4964;">0.6 ml</td>
											</tr>
											<tr bgcolor="#326883">
												<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:14px; color:#1f4964;">2 weeks later (week 7)</td>
												<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:14px; color:#1f4964;">0.8 ml</td>
											</tr>
											<tr bgcolor="#326883">
												<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:14px; color:#1f4964;">3 weeks later (week 10)</td>
												<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:14px; color:#1f4964;">1.0 ml</td>
											</tr>
											<tr bgcolor="#326883">
												<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:14px; color:#1f4964;">3 weeks later (week 13)</td>
												<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:14px; color:#1f4964;">1.0 m</td>
											</tr>
											<tr bgcolor="#326883">
												<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:14px; color:#1f4964;">4 weeks later (week 17)</td>
												<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:14px; color:#1f4964;">1.0 m</td>
											</tr>
											<tr bgcolor="#326883">
												<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:14px; color:#1f4964;">4 weeks later (week 21)</td>
												<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:14px; color:#1f4964;">1.0 m</td>
											</tr>
											<tr bgcolor="#326883">
												<td colspan="2" align="center" bgcolor="#b8c6d6" style="padding:12px; font-size:13px; line-height: 18px; color:#1f4964;">Continue with 1.0 ml every 4 weeks for at least 12 months. If noticeable results, Artuvetrin® is a lifelong treatment.</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td colspan="2">
										<table style="border:1px solid #9bd4dc; border-radius:0 10px 10px 10px; padding:10px;" width="100%">
											<tr>
												<td>
													<p style="margin:0 0 4px 0; padding:0; color:#1b3856; font-size:14px;"><strong>Artuvetrin® is a lifelong treatment and compliance is key</strong></p>
													<p style="margin:0; padding:0; color:#1b3856; font-size:14px;">Allergy is a chronic disease and every 10 months a follow-up vial is required every 10 months.</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">Is it possible to deviate from the standard dosing schedule?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">Yes, however this depends on the situation. Please contact our medical department at +01 494 629 979 for advice and support. </p></td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">What is the success rate of Artuvetrin®? </h6></td>
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">The success rate of Artuvetrin® Therapy is 75% for dogs, 70% for cats and 84% for horses. Patients who respond, may expect a recovery from 50% up to 100%. Symptomatic medication may be stopped completely or decreased significantly.</p></td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">What if the patient does not respond at all?</h6></td>
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">If the patient did not show any improvement at all after 12 months, please contact our medical department on +01 494 629 979. There can be several reasons for a 0% response: concomitant food allergy, reaction to new allergens or ineffective treatment. We are happy to evaluate each case and help you with the relevant follow up.</p></td>
								</tr>
							</table>
						</td>
						<td valign="top">
							 <table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="100%">
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">If the symptoms are seasonal, should I administer only during that time?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">No, it should be administered continuously and lifelong. If discontinued
									for a long period the immunological tolerance may be decreased and
									the treatment will have to be restarted from the initial dosage.</p></td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">What is the best time to start the treatment?</h6></td>
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">The treatment can be started at any time. It is recommended to
									have the skin under control before starting Artuvetrin®</p></td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">Do I need to stop symptomatic medication
									before starting Artuvetrin®? </h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">Symptomatic medication does not need to be stopped and it can
									help to keep the skin calm and under control in the initial phase
									(increasing dosage) of the desensitisation. Symptomatic medication
									does not affect the efficacy of the treatment and can be continued.</p></td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">Can symptomatic medication affect the result?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">Corticosteroids may affect the serum test results if administered
									longer than 2-3 months (oral medication).</p></td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">When can I expect to see improvements?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">In general, improvement can be noticed after 4 to 6 months.
									In some cases after 1 month. If there is no improvement at
									all after 6 months, please contact us at +01 494 629 979. We
									are happy to help you with your case.
									</p></td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">What to do with cases where the symptoms
									come back?</h6></td>	
								</tr>
								<tr>
									<td colspan="2">
										<p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">In case of a flare-up, the symptoms suddenly appear or worsen, this means that the animal has risen above the pruritic threshold.
										It can happen for different reasons:</p>
										<ul style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">
											<li>increased contact with allergens</li>
											<li>secondary infection (yeast, bacterial, etc.)</li>
											<li>other skin irritation due to fleas, swimming, more or less washing, etc.</li>
										</ul>
										<p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">
										With a flare-up, it is important to find the cause and prevent it.
										If the cause is unknown, it can sometimes be sufficient to correct
										the symptoms with temporary symptomatic medication</p>
									</td>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:17px; margin:0;">Can I stop the treatment if the symptoms are
									not present anymore?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:15px; line-height:22px; margin:10px 0 0 0;">No, it is lifelong and should not be stopped. When stopping there is a significant chance the symptoms will come back and restarting the treatment will not be as effective as before.
									</p></td>
								</tr>
							</table>
						</td>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="30"></td></tr></table>
				<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:100px 0px 100px 15px; background:url(<?php echo base_url(); ?>assets/images/next-header2.jpg) top center no-repeat; background-size:cover;">
					<tr>
						<td valign="middle">
							<img src="<?php echo base_url(); ?>assets/images/nextlab-logo.jpg" alt="" style="max-height:100px; max-width:280px; border-radius:4px; margin-bottom: 20px;" />
							<h5 style="font-weight:700; font-size:20px; color:#ffffff;">Serum Test <br>treatment advice</h5>
						</td>					
						<td valign="middle"></td>
					</tr>
				</table>
				<table width="100%"><tr><td height="20"></td></tr></table>
				<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding: 0mm 8mm 2mm;">
					<tr>
						<td>
							<h4 style="margin:0; color:#2a5b74; font-size:22px;">About Next+</h4>
							<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;">Frequently asked questions</p>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="10"></td></tr></table>
				<table width="100%" style="padding: 1.5mm 8mm 2mm;">
					<tr>
						<td valign="top">
							<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="100%">
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:15px; margin:0;">If there are high levels of positives, will immuno-
									therapy be useful?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">Where there are a high number of positive allergens, this does not
									affect the efficacy of the immunotherapy.</p></td>
								</tr>
								<tr><td height="20"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:15px; margin:0;">What can the owner do to prevent exposure to the positive allergens?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">Many things can be done to prevent or reduce contact with allergens. Please request our Allergen Guide, this brochure contains
									tips for all allergens we test for. </p></td>
								</tr>
								<tr><td height="20"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:15px; margin:0;">What if Malassezia is positive? </h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">Malassezia is mostly a secondary problem of atopic dermatitis.
									If Malassezia is suspected to be causing the allergy, consider including
									it in the immunotherapy.</p></td>
								</tr>
								<tr><td height="20"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:15px; margin:0;">What if moulds are positive?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">Moulds may be only clinically relevant if the animal lives in a moist environment indoors. Should this be the case, we recommend to lower the humidity indoors or remove the moulds with mould cleaner first. Please refer to our Allergen Guide for more tips for moulds. If these adjustments give no or partial improvement, consider including it in the immunotherapy. </p></td>						
								</tr>
								<tr><td height="20"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:15px; margin:0;">What if flea has been tested positive?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">A flea bite hypersensitivity has been identified. A good flea treatment, used permanently, is recommended for flea hypersensitivity.</p></td>
								</tr>
								<tr><td height="20"></td></tr>
								<tr>
									<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
									<td><h6 style="color:#333333; font-size:15px; margin:0;">Do the units correlate with the clinical signs?</h6></td>	
								</tr>
								<tr>
									<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">No, the magnitude of the units does not necessarily correlate with the severity of the disease but does reflect the animal’s immune response to allergens.</p></td>
								</tr>
							</table>
						</td>
						<td valign="top">
							<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="100%">
								<tr>
									<td>
										<table style="background:#edf2f4; padding:20px; border-radius:10px;">
											<tr>
												<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
												<td><h6 style="color:#333333; font-size:15px; margin:0;">What are CCDs?</h6></td>	
											</tr>
											<tr>
												<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">CCDs are cross-reactive carbohydrate determinants – the carbohydrate chains found in glycoproteins. CCDs are part of a structure of many allergy-causing proteins, especially plant-based allergens like pollen.</p></td>
											</tr>
											<tr><td height="20"></td></tr>
											<tr>
												<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
												<td><h6 style="color:#333333; font-size:15px; margin:0;">How are CCDs involved in the allergic
												reaction??</h6></td>
											</tr>
											<tr>
												<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">During an allergic reaction, IgE is produced against the carbohydrate chains as well as the allergen proteins. Studies have confirmed that this occurs in 30% of humans, dogs and cats1-3. The IgE against CCD chains do not seem to be clinically relevant.</p></td>
											</tr>
											<tr><td height="20"></td></tr>
											<tr>
												<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
												<td><h6 style="color:#333333; font-size:15px; margin:0;">Why is it important to block CCDs?</h6></td>	
											</tr>
											<tr>
												<td colspan="2"><p style="color:#333333; font-size:13px; line-height:20px; margin:10px 0 0 0;">Blocking CCDs means that the specificity of the in vitro test is enhanced. Evidence shows that the correlation with intradermal testing is also improved<sup>3</sup>.</p></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td height="30"></td></tr>
								<!-- <tr>
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
								</tr> -->
							</table>
						</td>
					</tr>
				</table>
				<table width="100%"><tr><td height="30"></td></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;" >
					<tr>
						<td style="">
							<h5 style="margin:0 0 3px 0; padding:0; color:#326883; font-size:15px;">Do you have any additional questions?</h5>
							<p style="margin:0 0 0 0; padding:0; color:#326883; font-size:13px;">Please call our medical department on +01 494 629 979 or send an email to info.eu@nextmune.com.</p>
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td style="padding:0 0 0 20px;">
							<ol style="color:#19455c; margin:0; padding:0; font-size:13px; line-height:24px;">
								<li>Petersen A et al Ubiquitous structures responsible for IgE cross-reactivity between tomato fruit and grass pollen allergens. J Allergy Clin Immunol 1996 Oct; 98(4):805-815</li>
								<li>Mari A et al Specific IgE to cross-reactive carbohydrate determinants strongly affect the in vitro diagnosis of allergic diseases. J Allergy Clin Immunol 1999; 103(6):1005-1011</li>
								<li>Gedon NKY et al Agreement of serum allergen test results with unblocked and blocked IgE against cross-reactive carbohydrate determinants (CCD) and intradermal test results in atopic dogs Vet Dermatol 2019; 30(3):195-e61</li>
							</ol>
						</td>
					</tr>
				</table>
			<?php } ?>
			<?php if(preg_match('/\bSCREEN Food only\b/', $respnedn->name) || preg_match('/\bComplete Food Panel\b/', $respnedn->name)){ ?>
			<div style='page-break-after:always'></div>
			<table class="main_container" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tbody>
					<tr>
						<td width="100%">
							<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
								<tbody>
									<tr>
										<td width="100%">
											<table width="100%" cellpadding="0" cellspacing="0" border="0">
												<tbody>
													<tr>
														<td align="left" valign="middle" style="background:#426e89; padding: 5mm 0mm 5mm 8mm;">
															<p style="font-size: 32px;text-transform: uppercase;color: #ffffff;font-weight: 400; margin: 0; letter-spacing: 2px; line-height: 38px; white-space:nowrap;">Serum test<br> Request form</p>
														</td>
														<td valign="top"><img src="<?php echo base_url("/assets/images/aqua-corner-shape.png"); ?>" alt="NextVu" height="180" /></td>
														<td width="7%">&nbsp;</td>
														<td align="right" style="padding: 0 8mm 0 0;">
															<img src="<?php echo base_url("/assets/images/nextmune-uk.png"); ?>" height="55" alt="NextVu" />
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
			<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 8mm 8mm 2mm;">
				<tbody>
					<tr>
						<td width="100%">
							<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
								<tbody>
									<tr>
										<td width="100%">
											<table cellpadding="0" cellspacing="0" border="0">
												<tbody>
													<tr>
														<td style="color:#426e89; letter-spacing:1px; font-size:20px;">Practice details</td>
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
			<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0mm 8mm;">
				<tr>
					<td width="49%" valign="top">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="33%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#1c3642; font-size:13px; line-height: 22px;">Date:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="2%">&nbsp;</td>
													<td width="65%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#1c3642; font-size:13px; line-height: 22px;">Veterinary surgeon:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $order_details['name']; ?></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">Veterinary practice:</p>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $order_details['practice_name']; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:5px; line-height:5px;"></td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">Practice details:</p>
													</td>
												</tr>
												<tr>
													<td style="height: 5px; line-height: 5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $fulladdress; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:5px; line-height:5px;"></td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="63%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#1c3642; font-size:13px; line-height: 22px;">City:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $city; ?></td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="2%">&nbsp;</td>
													<td width="35%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#1c3642; font-size:13px; line-height: 22px;">Postcode:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $postcode; ?></td>
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
					<td width="2%">&nbsp;</td>
					<td width="49%" valign="top">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">Phone:</p>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $order_details['phone_number']; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:5px; line-height:5px;"></td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">Email:</p>
													</td>
												</tr>
												<tr>
													<td style="height: 5px; line-height: 5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $order_details['email']; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:5px; line-height:5px;"></td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">Results will be delivered by email.</p>
													</td>
												</tr>
												<tr>
													<td style="height: 5px; line-height: 5px;"></td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="4%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td>
																						<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																	<td width="2%">&nbsp;</td>
																	<td width="94%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td style="color:#1c3642; font-size:12px;">
																						I would like to order more serum test shipping materials
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
			<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
				<tbody>
					<tr>
						<td style="height: 25px;"></td>
					</tr>
				</tbody>
			</table>
			<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0 8mm">
				<tbody>
					<tr>
						<td width="100%">
							<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
								<tbody>
									<tr>
										<td width="100%">
											<table cellpadding="0" cellspacing="0" border="0">
												<tbody>
													<tr>
														<td style="color:#426e89; letter-spacing:1px; font-size:20px;">Animal and owner details</td>
													</tr>
													<tr>
														<td style="height: 10px; line-height: 10px;"></td>
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
			<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0mm 8mm;">
				<tr>
					<td width="49%" valign="top">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="33%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="middle" width="18%">
																					<?php if($order_details['species_name'] == 'Dog'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;">Dog</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="33%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="middle" width="18%">
																					<?php if($order_details['species_name'] == 'Cat'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;">Cat</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="33%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="middle" width="18%">
																					<?php if($order_details['species_name'] == 'Horse'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;">Horse</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="33%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="middle" width="18%">
																					<?php if($petinfo['gender'] == '1'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;">Male</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="33%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="middle" width="18%">
																					<?php if($petinfo['gender'] == '2'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;">Female</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="33%" valign="middle"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:10px"></td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">Owner name:</p>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $order_details['pet_owner_name']; ?> <?php echo $order_details['po_last']; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td style="height:10px;"></td>
												</tr>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">Animal name:</p>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $order_details['pet_name']; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td style="height:10px;"></td>
												</tr>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">Breed:</p>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $breedinfo['name']; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td style="height:10px;"></td>
												</tr>
												<tr>
													<td width="57%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#1c3642; font-size:13px; line-height: 22px;">Date of birth:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $years.$months; ?></td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="2%">&nbsp;</td>
													<td width="40%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#1c3642; font-size:13px; line-height: 22px;">Date serum drawn:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo !empty($serumdata['serum_drawn_date'])?date('d/m/Y',strtotime($serumdata['serum_drawn_date'])):''; ?></td>
																</tr>
															</tbody>
														</table>
													</td>
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
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
																	Is this animal suffering from a zoonotic disease?
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td width="33%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="18%">
																											<?php if(isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease']==1){ ?>
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="82%" style="color:#1c3642; font-size:13px; line-height: 22px;">Yes</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="33%">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="18%">
																											<?php if(isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease']==0){ ?>
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="82%" style="color:#1c3642; font-size:13px; line-height: 22px;">No</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="33%" valign="middle"></td>
																		</tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td width="100%" style="height: 5px;">
																	
																</td>
															</tr>
															<tr>
																<td width="100%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tbody>
																			<tr>
																				<td width="100%" valign="top">
																					<p style="color:#1c3642; font-size:13px; line-height: 22px;">If yes, please specify:</p>
																				</td>
																			</tr>
																			<tr>
																				<td style="height:5px; line-height:5px;"></td>
																			</tr>
																			<tr>
																				<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 75px; padding:0 10px; font-size:13px;"><?php echo isset($serumdata['zoonotic_disease_dec']) ? $serumdata['zoonotic_disease_dec'] : ''; ?></td>
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
							</tbody>
						</table>
					</td>
					<td width="2%">&nbsp;</td>
					<td width="49%" valign="top">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
																	What are the major presenting symptoms?
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td width="20%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if(isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '1' ) !== false) ){ ?>
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">Pruritus</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="17%">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" valign="middle" width="30%">
																											<?php if(isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '2' ) !== false) ){ ?>
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" valign="middle" width="70%" style="color:#1c3642; font-size:12px; line-height: 20px;">Otitis</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="27%">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="18%">
																											<?php if(isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '3' ) !== false) ){ ?>
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="77%" style="color:#1c3642; font-size:12px; line-height: 20px;">Respiratory</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="36%">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="15%">
																											<?php if(isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '4' ) !== false) ){ ?>
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;">Gastrointestinal</td>
																									</tr>
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
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tbody>
																			<tr>
																				<td width="25%" valign="middle">
																					<table width="100%" cellspacing="0" cellpadding="0" border="0">
																						<tbody>
																							<tr>
																								<td width="100%" valign="middle">
																									<table width="100%" cellspacing="0" cellpadding="0" border="0">
																										<tr>
																											<td valign="middle" width="20%">
																												<?php if(isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '0' ) !== false) ){ ?>
																												<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																												<?php }else{ ?>
																												<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																												<?php } ?>
																											</td>
																											<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">Other</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</td>
																				<td width="75%">
																					<table width="100%" cellspacing="0" cellpadding="0" border="0">
																						<tbody>
																							<tr>
																								<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo isset($serumdata['other_symptom']) ? $serumdata['other_symptom'] : ''; ?></td>
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
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:10px"></td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td style="height: 10px;"></td>
												</tr>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#1c3642; font-size:13px; line-height: 22px;">At what age did these symptoms first appear?:</p>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="60%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"><?php echo $serumdata['symptom_appear_age'].' years '.$serumdata['symptom_appear_age_month'].' months'; ?></td>
																<td width="40%">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:10px"></td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td style="height: 10px;"></td>
												</tr>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
																	When are the symptoms most obvious?
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td width="20%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '1' ) !== false) ){ ?> 
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">Spring</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="20%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '2' ) !== false) ){ ?> 
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">Summer</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="20%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '3' ) !== false) ){ ?> 
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">Autumn</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="20%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '4' ) !== false) ){ ?> 
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">Winter</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="20%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '5' ) !== false) ){ ?> 
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">All year</td>
																									</tr>
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
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:10px"></td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td style="height: 10px;"></td>
												</tr>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
																	Where are the symptoms most obvious?
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td width="33%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '1' ) !== false) ){ ?> 
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">Indoors</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="33%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '2' ) !== false) ){ ?> 
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">Outdoors</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="33%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '3' ) !== false) ){ ?> 
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;">No difference</td>
																									</tr>
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
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height: 10px;"></td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
																	Is the animal receiving any medication at the moment?
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td width="33%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td width="18%">
																											<?php if(isset($serumdata['medication']) && $serumdata['medication']==1){ ?>
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td width="82%" style="color:#1c3642; font-size:13px; line-height: 22px;">Yes</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="33%">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td width="18%">
																											<?php if(isset($serumdata['medication']) && $serumdata['medication']==0){ ?>
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																											<?php }else{ ?>
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<?php } ?>
																										</td>
																										<td width="82%" style="color:#1c3642; font-size:13px; line-height: 22px;">No</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="33%" valign="middle"></td>
																		</tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tbody>
																			<tr>
																				<td width="100%" valign="top">
																					<p style="color:#1c3642; font-size:13px; line-height: 22px;">If yes, please specify:</p>
																				</td>
																			</tr>
																			<tr>
																				<td style="height:5px; line-height:5px;"></td>
																			</tr>
																			<tr>
																				<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 75px; padding:0 10px; font-size:13px;"><?php echo isset($serumdata['medication_desc']) ? $serumdata['medication_desc'] : ''; ?></td>
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
							</tbody>
						</table>
					</td>
				</tr>
			</table>
			<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
				<tbody>
					<tr>
						<td style="height: 25px;"></td>
					</tr>
				</tbody>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="80%" valign="top">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0mm 8mm;">
										<tbody>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#1c3642; font-size:13px; line-height: 22px;">Internal Use Only</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#fff; border:1px solid #5b8398; outline:none; height: 75px; padding:0 10px; font-size:13px;"></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height: 20px;"></td>
							</tr>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #e5f4f7;">
										<tbody>
											<tr>
												<td width="100%" style="padding: 2.5mm 0 2.5mm 8mm;">
													<p style="color:#426e89; font-size:15px; line-height: 22px;">For allergy resources visit nextmunelaboratories.co.uk/login</p>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="20%" valign="bottom">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td width="100%">
									<img src="<?php echo base_url(); ?>assets/images/practice-portal-lock-img.png" width="215">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<div style='page-break-after:always'></div>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color: #426e89;">
				<tbody>
					<tr>
						<td width="100%">
							<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
								<tbody>
									<tr>
										<td width="100%" style="height: 25px;"></td>
									</tr>
									<tr>
										<td width="100%" align="center" valign="middle">
											<table width="100%" cellpadding="0" cellspacing="0" border="0">
												<tbody>
													<tr>
														<td>
															<p style="font-size: 26px;color: #fff;font-weight: 400; margin: 0; text-transform: uppercase; text-align: center;">Sample Submission Form</p>
														</td>
												</tr></tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td width="100%" style="height: 5px; line-height: 5px;"></td>
									</tr>
									<tr>
										<td width="100%" align="center" valign="middle">
											<table cellpadding="0" cellspacing="0" border="0" width="100%">
												<tbody>
													<tr>
														<td>
															<p style="font-size: 16px;color: #fff;font-weight: 400; margin: 0; text-transform: uppercase; text-align:center;">Please select individual test(s) by ticking the appropriate box(es)</p>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td width="100%" style="height: 10px;"></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding: 0 12mm;">
				<tbody>
					<tr>
						<td width="100%" style="height: 20px; line-height: 20px;"></td>
					</tr>
					<tr>
						<td width="100%">
							<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
								<tbody>
									<tr>
										<td width="25%" align="left" valign="top">
											<table width="100%" cellpadding="0" cellspacing="0" border="0">
												<tbody>
													<tr>
														<td>
															<p style="font-size: 20px;color: #426e89;font-weight: 600; margin: 0; text-transform: uppercase;">STORAGE ONLY</p>
														</td>
												</tr></tbody>
											</table>
										</td>
										<td width="75%" align="left" valign="top">
											<table cellpadding="0" cellspacing="0" border="0">
												<tbody>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tbody>
																	<tr>
																		<td width="100%">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="5%" valign="top">
																							<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																						</td>
																						<td valign="top" width="95%" style="color:#000; font-size:13px; text-align: left;">
																							Samples will be held free of charge for 3 months. If you select storage only, please do not tick a test type. Please contact us when you wish to run this sample.
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
					<tr>
						<td width="100%" style="height: 10px;"></td>
					</tr>
				</tbody>
			</table>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0 8mm 0 0;">
				<tbody>
					<tr>
						<td width="100%">
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr>
										<td width="25%" align="left" valign="top">
											<img src="<?php echo base_url("/assets/images/dog.png"); ?>" width="200" alt="CANINE TEST" />
										</td>
										<td width="75%" align="left" valign="top">
											<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:17px 0 0 0;">
												<tbody>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="50%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td style="background:#cee8ee; color:#426e89; font-size:24px; text-transform: uppercase;">Canine Tests</td>
																				<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail1.png"); ?>"  height="51" alt="CANINE" /></td>
																			</tr>
																		</table>
																	</td>
																	<td width="50%">&nbsp;</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="text-transform: uppercase; font-size: 16px; color:#426e89;"><b>Nextlab</b></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Environmental Panel (IgE) & Food Panel (IgE & IgG)' && $order_details['species_name'] == 'Dog'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">COMPLETE ENVIRONMENTAL & FOOD : Food panel, environmental panel (indoor & outdoor) & Malassezia IgE. Sample required: 2ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Environmental Panel (IgE)' && $order_details['species_name'] == 'Dog'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">ENVIRONMENTAL: Environmental panel (indoor & outdoor) & Malassezia IgE. Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Food Panel (IgE & IgG)' && $order_details['species_name'] == 'Dog'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">FOOD : Food panel. Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="font-size: 11px; color:#000;"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;">Nextlab Screens</b><b>(positive/negative result only; can be expanded on request)</b></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB SCREEN Environmental only, 4 Panels: Grasses, Weeds, Trees and Indoor' && $order_details['species_name'] == 'Dog'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">ENVIRONMENTAL SCREEN: Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB SCREEN Food only, single result Positive/Negative' && $order_details['species_name'] == 'Dog'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">FOOD SCREEN: Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;">ACUTE PHASE PROTEINS (APPs)</b></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="top" width="5%">
																					<?php if(preg_match('/\bAcute Phase Proteins\b/', $respnedn->name) && ($order_details['species_name'] == 'Dog')){ ?>
																						<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																						<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">C-REACTIVE PROTEIN (CRP) Sample required: 0.5ml serum</td>
																			</tr>
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
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0 8mm 0 0;">
				<tbody>
					<tr>
						<td width="100%" style="height: 5px;"></td>
					</tr>
					<tr>
						<td width="100%">
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr>
										<td width="25%" align="left" valign="top">
											<img src="<?php echo base_url("/assets/images/cat.png"); ?>" width="200" alt="Feline TEST" />
										</td>
										<td width="75%" align="left" valign="top">
											<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:27px 0 0 0;">
												<tbody>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="50%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td style="background:#7dc1c9; color:#fff; font-size:24px; text-transform: uppercase;">Feline Tests</td>
																				<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail2.png"); ?>"  height="51" alt="Feline" /></td>
																			</tr>
																		</table>
																	</td>
																	<td width="50%">&nbsp;</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="text-transform: uppercase; font-size: 16px; color:#426e89;"><b>Nextlab</b></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Environmental Panel (IgE) & Food Panel (IgE & IgG)' && $order_details['species_name'] == 'Cat'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">COMPLETE ENVIRONMENTAL & FOOD: Food panel, environmental panel (indoor & outdoor). Sample required: 2ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Environmental Panel (IgE)' && $order_details['species_name'] == 'Cat'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">ENVIRONMENTAL: Environmental panel (indoor & outdoor). Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Food Panel (IgE & IgG)' && $order_details['species_name'] == 'Cat'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">FOOD : Food panel. Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="font-size: 11px; color:#000;"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;">Nextlab Screens</b><b>(positive/negative result only; can be expanded on request)</b></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB SCREEN Environmental only, 4 Panels: Grasses, Weeds, Trees and Indoor' && $order_details['species_name'] == 'Cat'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">ENVIRONMENTAL SCREEN: Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB SCREEN Food only, single result Positive/Negative' && $order_details['species_name'] == 'Cat'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">FOOD SCREEN: Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;">ACUTE PHASE PROTEINS (APPs)</b></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="top" width="5%">
																					<?php if(preg_match('/\bAcute Phase Proteins\b/', $respnedn->name) && ($order_details['species_name'] == 'Cat')){ ?>
																						<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																						<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;">α1-ACID GLYCOPROTEIN (AGP): Sample required: 0.5ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
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
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0 8mm 0 0;">
				<tbody>
					<tr>
						<td width="100%">
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr>
										<td width="25%" align="left" valign="top">
											<img src="<?php echo base_url("/assets/images/horse.png"); ?>" width="200" alt="Equine TEST" />
										</td>
										<td width="75%" align="left" valign="top">
											<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:17px 0 0 0;">
												<tbody>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="50%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td style="background:#b8c6d6; color:#426e89; font-size:24px; text-transform: uppercase;">Equine Tests</td>
																				<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail3.png"); ?>"  height="51" alt="Equine" /></td>
																			</tr>
																		</table>
																	</td>
																	<td width="50%">&nbsp;</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="text-transform: uppercase; font-size: 16px; color:#426e89;"><b>Nextlab</b></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Environmental and Insect Panel (IgE) & Food (IgE & IgG)' && $order_details['species_name'] == 'Horse'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;">COMPLETE ENVIRONMENTAL, INSECT & FOOD: Food panel, environmental panel (indoor & outdoor) and insect panel. Sample required: 3ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Environmental and Insect Panel (IgE)' && $order_details['species_name'] == 'Horse'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;">ENVIRONMENTAL & INSECT: Environmental panel (indoor & outdoor) & insect panel. Sample required: 2ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB Complete Food (IgE & IgG)' && $order_details['species_name'] == 'Horse'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;">FOOD: Food panel. Sample required: 1ml serum</td>
																			</tr>
																			<tr>
																				<td style="height: 5px;"></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="font-size: 11px; color:#000;"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;">Nextlab Screens</b><b>(positive/negative result only; can be expanded on request)</b></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td width="100%">
															<table width="100%" cellspacing="0" cellpadding="0" border="0">
																<tr>
																	<td width="100%" style="height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tr>
																				<td valign="top" width="5%">
																					<?php if($ordeType == 'NEXTLAB SCREEN Environmental & Insect Screen (IgE)' && $order_details['species_name'] == 'Horse'){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<?php } ?>
																				</td>
																				<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;">ENVIRONMENTAL & INSECT SCREEN: Sample required: 2ml serum</td>
																			</tr>
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
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0 8mm;">
				<tr>
					<td width="100%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td width="100%" style="height: 10px;"></td>
							</tr>
							<tr>
								<td width="100%" style="font-size: 10px; line-height: 15px; color: #000;">
									Samples submitted are subject to Nextmune Laboratories’ terms & conditions of business (www.nextmunelaboratories.co.uk/terms-of-business)
								</td>
							</tr>
							<tr>
								<td width="100%" style="height: 5px;"></td>
							</tr>
							<tr>
								<td width="100%" style="font-size: 10px; line-height: 15px; color: #000;">
									We may store and use any surplus serum for quality control, research and development purposes.
								</td>
							</tr>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td width="60%">
												<table width="100%" cellspacing="0" cellpadding="0" border="0">
													<tr>
														<td width="85%" style="font-size: 10px; line-height: 15px; color: #000;">
															If you do not wish Nextmune Laboratories to utilise this sample, please tick here
														</td>
														<td width="15%">
															<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" width="8"/>
														</td>
													</tr>
												</table>
											</td>
											<td width="40%"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="100%" style="height: 10px;"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #426e89; padding: 1.5mm 0;">
				<tr>
					<td width="100%" align="center">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0 8mm;">
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td width="100%" style="font-size: 12px; line-height: 20px; color: #fff;">
												Nextmune Laboratories Limited, Unit 651, Street 5, Thorp Arch Trading Estate, Wetherby, UK, LS23 7FZ
											</td>
										</tr>
										<tr>
											<td width="100%">
												<table width="100%" cellspacing="0" cellpadding="0" border="0">
													<tr>
														<td width="49%" style="font-size: 12px; line-height: 20px; color: #fff; text-align: right;">
															T – 0800 3 047 047
														</td>
														<td width="2%">&nbsp;</td>
														<td width="49%" style="font-size: 12px; line-height: 20px; color: #fff; text-align: left;">
															E – <a href="mailto:vetorders.uk@nextmune.com" style="color: #fff; text-decoration: none;">vetorders.uk@nextmune.com</a>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="100%" style="height: 5px;"></td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="100%" align="center">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0 8mm;">
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td width="49%" style="font-size: 8px; line-height: 20px; color: #000; text-align: left;">
												<b>© 2022 Nextmune Laboratories Limited</b>
											</td>
											<td width="2%">&nbsp;</td>
											<td width="49%" style="font-size: 8px; line-height: 20px; color: #000; text-align: right;">
												NM035_06_22
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
		<?php } ?>
	</body>
</html>