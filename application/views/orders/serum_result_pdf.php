<?php
if($order_details['cutoff_version'] == 1){
	$cutaoff = '5';
	$cutboff = '10';
	$cutcoff = '60';
	$cutdoff = '75';
}elseif($order_details['cutoff_version'] == 2){
	$cutaoff = '100';
	$cutboff = '200';
	$cutcoff = '1200';
	$cutdoff = '1500';
}else{
	$cutaoff = '200';
	$cutboff = '250';
	$cutcoff = '1200';
	$cutdoff = '1500';
}

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
				if($row1->result >= $cutcoff){
					$optn2Arr[] = $algName->id;
					$block1++;
				}
				$moduleArr[] = $optionenv;
			}else{
				if($row1->result >= $cutaoff){
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
		if($malasseziaResults->result >= $cutcoff){
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

$foodpos = 0;
if(((preg_match('/\bComplete Food\b/', $respnedn->name)) || (preg_match('/\bNEXTLAB Complete Food\b/', $respnedn->name)) || ((preg_match('/\bSCREEN Environmental\b/', $respnedn->name) || preg_match('/\bComplete Environmental\b/', $respnedn->name)) && preg_match('/\bFood\b/', $respnedn->name))) && (!preg_match('/\bSCREEN Food\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name)) && (!preg_match('/\bFood Positive\b/', $respnedn->name))){
	$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($order_details['allergens']);
	$foodpos = 0;
	foreach($getAllergenFParent as $rowf){
		$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $order_details['allergens']);
		foreach($subfAllergens as $sfvalue){
			$this->db->select('result');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'")');
			$this->db->where('lims_allergens_id >','0');
			$this->db->order_by('id', 'ASC');
			$serumfResults = $this->db->get()->row();
			if(!empty($serumfResults)){
				if($serumfResults->result > $cutboff){
					$foodpos++;
				}
			}

			$this->db->select('result');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
			$this->db->where('lims_allergens_id >','0');
			$this->db->order_by('id', 'ASC');
			$serumfiggResults = $this->db->get()->row();
			if(!empty($serumfiggResults)){
				if($serumfiggResults->result > $cutboff){
					$foodpos++;
				}
			}
		}
	}
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
		table.diets th{text-align: left; font-weight: 400;}
		.diets tr th{border-bottom: 3px solid #9acfdb; padding-bottom: 5px; position: relative; width: 20px;}
		.diets tr th, .diets tr td{border-right: 1px solid #9acfdb; border-left: 0px;}
		.diets .table-first{text-align: left;}
		.diets .table-head{border-right: 0px;}
		.diets tr td:first-child{text-align: left;}
		.rotate{padding-right: 5px; display: inline-block; transform: rotate(-90deg);}
		.diets tr th:not(:first-child) span {padding: 0; top: -16px; position: absolute; left: 0px; width: 100%;}
		.diets tr td{text-align: center; border-bottom:1px solid #9acfdb; padding: 2px 0;}
		.diets tr th:not(:first-child) {text-align: left !important;}
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
										<?php if($order_details['vet_user_id'] != '24927'){ ?>
										<tr>
											<th style="color:#346a7e;">Veterinary practice:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $order_details['practice_name']; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Address:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $fulladdress; ?></td>
										</tr>
										<?php } ?>
										<tr>
											<th style="color:#346a7e;">Test type:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo $ordeType; ?></td>
										</tr>
										<tr>
											<th style="color:#346a7e;">Date tested:</th>
											<td style="color:#000000; padding-left: 8px;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
										</tr>
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
									if($serumResults->result > $cutboff){
										$countergP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumResults->result > $cutboff){
										$counterwP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumResults->result > $cutboff){
										$countertP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumcResults->result > $cutboff){
										$countercP++;
									}elseif($serumcResults->result <= $cutboff && $serumcResults->result >= $cutaoff){
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
										if($serumResults->result > $cutdoff){
											$counteriP++;
										}elseif($serumResults->result <= $cutdoff && $serumResults->result >= $cutcoff){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}else{
										if($serumResults->result > $cutboff){
											$counteriP++;
										}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
										if($serumiResults->result > $cutboff){
											$counteriP++;
										}elseif($serumiResults->result <= $cutboff && $serumiResults->result >= $cutaoff){
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

							if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994') || ($order_details['lab_id']=='13788' && $order_details['species_name'] == 'Dog')){
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
										if($fleaResults->result > $cutboff){
											echo '<td>POSITIVE</td>';
										}elseif($fleaResults->result <= $cutboff && $fleaResults->result >= $cutaoff){
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
										if($malasseziaResults->result > $cutdoff){
											echo '<td>POSITIVE</td>';
										}elseif($malasseziaResults->result <= $cutdoff && $malasseziaResults->result >= $cutcoff){
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
					<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
						<tr>
							<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_green.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;"><?php echo $cutaoff; ?>-<?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_gray.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">> <?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_red.png" alt="class 0" style="height: 18px;" /></p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="color:#000;font-size:11px; line-height:16px; margin:0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units – This is considered irrelevant for immunotherapy at the time of testing, please note IgE levels may increase during periods of increased exposure to allergens.</p>
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
									if($serumResults->result > $cutboff){
										$countergP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumResults->result > $cutboff){
										$counterwP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumResults->result > $cutboff){
										$countertP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumcResults->result > $cutboff){
										$countercP++;
									}elseif($serumcResults->result <= $cutboff && $serumcResults->result >= $cutaoff){
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
										if($serumResults->result > $cutdoff){
											$counteriP++;
										}elseif($serumResults->result <= $cutdoff && $serumResults->result >= $cutcoff){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}else{
										if($serumResults->result > $cutboff){
											$counteriP++;
										}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
										if($serumiResults->result > $cutboff){
											$counteriP++;
										}elseif($serumiResults->result <= $cutboff && $serumiResults->result >= $cutaoff){
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

							if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994') || ($order_details['lab_id']=='13788' && $order_details['species_name'] == 'Dog')){
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
										if($fleaResults->result > $cutboff){
											echo '<td>POSITIVE</td>';
										}elseif($fleaResults->result <= $cutboff && $fleaResults->result >= $cutaoff){
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
										if($malasseziaResults->result > $cutdoff){
											echo '<td>POSITIVE</td>';
										}elseif($malasseziaResults->result <= $cutdoff && $malasseziaResults->result >= $cutcoff){
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
									if($fpResults->result > $cutboff){
										$counterFPP++;
									}elseif($fpResults->result <= $cutboff && $fpResults->result >= $cutaoff){
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
									if($fcResults->result > $cutaoff){
										$counterFCP++;
									}elseif($fcResults->result <= $cutaoff && $fcResults->result >= $cutboff){
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
					<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
						<tr>
							<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_green.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;"><?php echo $cutaoff; ?>-<?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_gray.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">> <?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_red.png" alt="class 0" style="height: 18px;" /></p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="color:#000;font-size:11px; line-height:16px; margin:0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units – This is considered irrelevant for immunotherapy at the time of testing, please note IgE levels may increase during periods of increased exposure to allergens.</p>
							</td>
						</tr>
					</table>
				<?php }elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name) || preg_match('/\bNEXTLAB Complete Food\b/', $respnedn->name)){ ?>
					<table width="100%"><tr><td width="100%"><img height="200" src="<?php echo base_url().PDF_IMAGE_PATH.'pdf_image_food_'.$id.'.png'; ?>" alt="Food Panel" style="width: 100%; height: 150px;padding: 3mm 8mm 2mm;" /></td></tr></table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
						<tr>
							<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_green.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;"><?php echo $cutaoff; ?>-<?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_gray.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">> <?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_red.png" alt="class 0" style="height: 18px;" /></p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="color:#000;font-size:11px; line-height:16px; margin:0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units – This is considered irrelevant for immunotherapy at the time of testing, please note IgE levels may increase during periods of increased exposure to allergens.</p>
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
					<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
						<tr>
							<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_green.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;"><?php echo $cutaoff; ?>-<?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_gray.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">> <?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_red.png" alt="class 0" style="height: 18px;" /></p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="color:#000;font-size:11px; line-height:16px; margin:0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units – This is considered irrelevant for immunotherapy at the time of testing, please note IgE levels may increase during periods of increased exposure to allergens.</p>
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
					<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
						<tr>
							<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_green.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;"><?php echo $cutaoff; ?>-<?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_gray.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">> <?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_red.png" alt="class 0" style="height: 18px;" /></p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="color:#000;font-size:11px; line-height:16px; margin:0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units – This is considered irrelevant for immunotherapy at the time of testing, please note IgE levels may increase during periods of increased exposure to allergens.</p>
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
									if($fpResults->result > $cutboff){
										$counterFPP++;
									}elseif($fpResults->result <= $cutboff && $fpResults->result >= $cutaoff){
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
									if($fcResults->result > $cutaoff){
										$counterFCP++;
									}elseif($fcResults->result <= $cutaoff && $fcResults->result >= $cutboff){
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
									if($serumResults->result > $cutboff){
										$countergP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumResults->result > $cutboff){
										$counterwP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumResults->result > $cutboff){
										$countertP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumcResults->result > $cutboff){
										$countercP++;
									}elseif($serumcResults->result <= $cutboff && $serumcResults->result >= $cutaoff){
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
										if($serumResults->result > $cutdoff){
											$counteriP++;
										}elseif($serumResults->result <= $cutdoff && $serumResults->result >= $cutcoff){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}else{
										if($serumResults->result > $cutboff){
											$counteriP++;
										}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
										if($serumiResults->result > $cutboff){
											$counteriP++;
										}elseif($serumiResults->result <= $cutboff && $serumiResults->result >= $cutaoff){
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

							if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994') || ($order_details['lab_id']=='13788' && $order_details['species_name'] == 'Dog')){
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
										if($fleaResults->result > $cutboff){
											echo '<td>POSITIVE</td>';
										}elseif($fleaResults->result <= $cutboff && $fleaResults->result >= $cutaoff){
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
										if($malasseziaResults->result > $cutdoff){
											echo '<td>POSITIVE</td>';
										}elseif($malasseziaResults->result <= $cutdoff && $malasseziaResults->result >= $cutcoff){
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
									if($fpResults->result > $cutboff){
										$counterFPP++;
									}elseif($fpResults->result <= $cutboff && $fpResults->result >= $cutaoff){
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
									if($fcResults->result > $cutaoff){
										$counterFCP++;
									}elseif($fcResults->result <= $cutaoff && $fcResults->result >= $cutboff){
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
					<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
						<tr>
							<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_green.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;"><?php echo $cutaoff; ?>-<?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_gray.png" alt="class 0" style="height: 18px;" /></p>
							</td>
							<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;text-align:center;">
								<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">> <?php echo $cutboff; ?> EA Units</p>
								<p><img src="<?php echo base_url(); ?>assets/images/nextlab_red.png" alt="class 0" style="height: 18px;" /></p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
						<tr>
							<td>
								<p style="color:#000;font-size:11px; line-height:16px; margin:0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units – This is considered irrelevant for immunotherapy at the time of testing, please note IgE levels may increase during periods of increased exposure to allergens.</p>
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
									if($serumResults->result > $cutboff){
										$countergP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumResults->result > $cutboff){
										$counterwP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumResults->result > $cutboff){
										$countertP++;
									}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
									if($serumcResults->result > $cutboff){
										$countercP++;
									}elseif($serumcResults->result <= $cutboff && $serumcResults->result >= $cutaoff){
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
										if($serumResults->result > $cutdoff){
											$counteriP++;
										}elseif($serumResults->result <= $cutdoff && $serumResults->result >= $cutcoff){
											$counteriB++;
										}else{
											$counteriN++;
										}
									}else{
										if($serumResults->result > $cutboff){
											$counteriP++;
										}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
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
										if($serumiResults->result > $cutboff){
											$counteriP++;
										}elseif($serumiResults->result <= $cutboff && $serumiResults->result >= $cutaoff){
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

							if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994') || ($order_details['lab_id']=='13788' && $order_details['species_name'] == 'Dog')){
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
										if($fleaResults->result > $cutboff){
											echo '<td>POSITIVE</td>';
										}elseif($fleaResults->result <= $cutboff && $fleaResults->result >= $cutaoff){
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
										if($malasseziaResults->result > $cutdoff){
											echo '<td>POSITIVE</td>';
										}elseif($malasseziaResults->result <= $cutdoff && $malasseziaResults->result >= $cutcoff){
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
						<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding: 3mm 8mm 2mm;">
							<tr>
								<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;text-align:center;">
									<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units</p>
									<p><img src="<?php echo base_url(); ?>assets/images/nextlab_green.png" alt="class 0" style="height: 18px;" /></p>
								</td>
								<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;text-align:center;">
									<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;"><?php echo $cutaoff; ?>-<?php echo $cutboff; ?> EA Units</p>
									<p><img src="<?php echo base_url(); ?>assets/images/nextlab_gray.png" alt="class 0" style="height: 18px;" /></p>
								</td>
								<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;text-align:center;">
									<p style="color:#000; font-size:12px; line-height:15px; margin:0 0 5px 0; padding:0;">> <?php echo $cutboff; ?> EA Units</p>
									<p><img src="<?php echo base_url(); ?>assets/images/nextlab_red.png" alt="class 0" style="height: 18px;" /></p>
								</td>
							</tr>
						</table>
						<table width="100%"><tr><td height="10"></td></tr></table>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 8mm 2mm;">
							<tr>
								<td>
									<p style="color:#000;font-size:11px; line-height:16px; margin:0; padding:0;">&lt; <?php echo $cutaoff; ?> EA Units – This is considered irrelevant for immunotherapy at the time of testing, please note IgE levels may increase during periods of increased exposure to allergens.</p>
								</td>
							</tr>
						</table>
					<?php } ?>
				<?php
				}
			}

			$option1 = [];
			foreach($optnenvArr as $row3){
				if($row3['result'] >= $cutaoff && $this->AllergensModel->checkforArtuveterinallergen($row3['algid']) > 0){
					$option1[$row3['algid']] = $row3['name'];
				}
			}
			foreach($moduleArr as $row4){
				if($row4['result'] >= $cutcoff && $this->AllergensModel->checkforArtuveterinallergen($row4['algid']) > 0){
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
					if($malasseziaResults->result >= $cutcoff && $this->AllergensModel->checkforArtuveterinallergen(81) > 0){
						$option1[81] = 'Malassezia';
					}
				}
			}

			if($order_details['treatment_1'] != "" && $order_details['treatment_1'] != "[]"){
				$subAllergnArr = $this->AllergensModel->getNextlabAllergensByID($order_details['treatment_1'],'');
				if(!empty($subAllergnArr)){
					foreach ($subAllergnArr as $svalue){
						$option1[$svalue['id']] = $svalue['name'];
						$block1++;
					}
				}
			}
			asort($option1);

			//get removed treatment 1
			$removed_treatment_1 = array();
			$removed_treatment_1 = $order_details['removed_treatment_1'];
			if(!empty($removed_treatment_1)){
				$removed_treatment_1 = json_decode($removed_treatment_1);
			}

			//get removed treatment 2
			$removed_treatment_2 = array();
			$removed_treatment_2 = $order_details['removed_treatment_2'];
			if(!empty($removed_treatment_2)){
				$removed_treatment_2 = json_decode($removed_treatment_2);
			}
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
						<td valign="top" height="600" width="380" style="height:600px;">
							<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="margin-left:40px;margin-left: 20px;min-height:220px">
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="380">
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
												if(!in_array($key,$removed_treatment_1)){
												?>
													<li style="font-size:17px; line-height: 26px;"><?php echo $value; ?></li>
													<?php 
													$a++;
												}
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
										<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="380">
									</td>
								</tr>
							</table>
						</td>
						<?php if(!empty($block2) && $option1 != $block2){ ?>
						<td valign="top" height="600" width="380" style="height:600px;">
							<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="margin-left:40px;margin-left: 20px;min-height:220px">
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="380">
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
											foreach($block2 as $keys=>$values){ 
												if(!in_array($keys,$removed_treatment_2)){ 
												?>
													<li style="font-size:17px; line-height: 26px;"><?php echo $values; ?></li>
													<?php 
													$b++;
												}
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
										<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="380">
									</td>
								</tr>
							</table>
						</td>
						<?php } ?>
						<td valign="top" height="600" width="380" style="height:600px;">	
							<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style=" margin-left:20px;">
								<tr>
									<td>
										<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="380">
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
										<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="380">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			<?php } ?>
			<?php if(((preg_match('/\bComplete Food\b/', $respnedn->name)) || (preg_match('/\bNEXTLAB Complete Food\b/', $respnedn->name)) || ((preg_match('/\bSCREEN Environmental\b/', $respnedn->name) || preg_match('/\bComplete Environmental\b/', $respnedn->name)) && preg_match('/\bFood\b/', $respnedn->name))) && (!preg_match('/\bSCREEN Food\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name)) && (!preg_match('/\bFood Positive\b/', $respnedn->name)) && ($foodpos > 0) && ($order_details['species_name'] == 'Dog')){ ?>
				<div style='page-break-after:always'></div>
				<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
					<tbody>
						<tr>
							<td valign="middle" style="padding-left: 15mm;">
								<img src="<?php echo base_url(); ?>assets/images/nextmune-uk.png" alt="Logo" width="320" />
							</td>
							<td valign="middle" align="right" style="padding-right: 8mm;">
								<img src="<?php echo base_url(); ?>assets/images/header-right-img.png" alt="nextlab food test img" width="220" />
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td width="100%" style="background-color: #336584; height: 30px;"></td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 4mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%" style="font-size: 15px; line-height: 22px; color: #336584;">
												DISCLAIMER: This dietary chart is produced as a guide only. Many commercial diets contain ingredients not listed on the packaging, so we recommend checking all information with the manufacturer before selecting the food as an elimination diet in your patient. Nextmune is not responsible for any reliance made on this information regarding third party manufacturers.
											</td>
										</tr>
										<tr>
											<td style="height: 12px;"></td>
										</tr>
										<tr>
											<td width="100%" style="font-size: 15px; line-height: 22px; color: #336584;">
												Nextmune recommends Solo Vegetal for use in elimination diet trials where appropriate. For more information on Solo Vegetal email vetenquiries.uk@nextmune.com or visit www.nextmune.com
											</td>
										</tr>
									</tbody>
								</table>	
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 2mm 4mm;">
					<tr>
						<td width="100%">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td width="100%">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="50%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td style="text-transform:uppercase; font-size:16px; margin:0;"><b>CANINE VETERINARY DIETS</b></td>
														</tr>
														<tr>
															<td width="100%">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tr>
																		<td style="height: 5px;"></td>
																	</tr>
																	<tr>
																		<td valign="top" width="10%" style="font-size: 12px; color: #000;">Key:</td>
																		<td valign="top" width="90%">
																			<table width="100%" cellpadding="0" cellspacing="0" border="0">
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Ingredient present</span></td>
																				</tr>
																				<tr>
																					<td style="height: 4px;"></td>
																				</tr>
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Hydrolysed</span></td>
																				</tr>
																				<tr>
																					<td style="height: 4px;"></td>
																				</tr>
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Starch only</span></td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
												<td width="50%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td width="100%" style="text-align: right;">
																<img src="<?php echo base_url(); ?>/assets/images/food-pack-img.png" width="150" alt="Food pack">
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
				<table class="diets" width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding: 0 4mm;">
					<tr>
						<th style="text-transform:uppercase; color:#366784;font-size:14px;font-weight:bold; border-left: 0px; width: 200px;" valign="bottom">NEXTMUNE</th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">WET/DRY</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Beef</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Pork</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Lamb</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Duck</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Chicken</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Turkey</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Venison</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Rabbit</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Horse</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Salmon</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">White Fish</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Wheat</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Soya</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Barley</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Rice</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Potato</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Corn</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Oats</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Egg</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Cow’s Milk</span></th>
					</tr>
					<tr>
						<td style="font-size:10px;" class="table-first">Solo Vegetal 800g</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td style="font-size:10px;" class="table-first">Solo Vegetal 1.5kg</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td style="font-size:10px;" class="table-first">Solo Vegetal 5kg</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td style="font-size:10px;" class="table-first">Solo Vegetal 400g</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td style="font-size:10px;" class="table-first">Solo Vegetal 150g</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>

					<tr>
						<th colspan="22" valign="bottom" style="color:#366784;font-size:14px;font-weight:bold;padding: 5px 0 0 0;margin: 0px; border-right: 0px">ROYAL CANIN</th>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Anallergenic (contains feather hydrolysate with very low molecular weight)</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Hypoallergenic (Hypoallergenic, Hypoallergenic Moderate Calorie and Hypoallergenic Small Dogs)</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Hypoallergenic</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Renal + Hypoallergenic (Multifunction diet)</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Urinary + Hypoallergenic (Multifunction diet)</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Sensitivity Control (Duck with tapioca)</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Sensitivity Control Chicken with rice</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Sensitivity Control Duck with rice</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Skin Care (Skin Care, Skin Care Small Dogs, Skin Care Small Dogs Puppy)</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>

					<tr>
						<th colspan="22" valign="bottom" style="color:#366784;font-size:14px;font-weight:bold;padding: 5px 0 0 0;margin: 0px;border-right: 0px;">JAMES WELLBELOVED</th>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Jame Wellbeloved Turkey Puppy</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Duck Adult</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Grain Free Fish Adult</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Lamb Senior</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Grain Free Turkey Senior</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Grain Free Fish Small Breed</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Grain Free Turkey Small Breed Senior</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Grain Free Turkey Puppy</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Grain Free Adult Lamb</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">James Wellbeloved Grain Free Turkey Senior</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
				<div style='page-break-after:always'></div>
				<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
					<tbody>
						<tr>
							<td valign="middle" style="padding-left: 15mm;">
								<img src="<?php echo base_url(); ?>assets/images/nextmune-uk.png" alt="Logo" width="320" />
							</td>
							<td valign="middle" align="right" style="padding-right: 8mm;">
								<img src="<?php echo base_url(); ?>assets/images/header-right-img.png" alt="nextlab food test img" width="220" />
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td width="100%" style="background-color: #336584; height: 30px;"></td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 2mm 4mm;">
					<tr>
						<td width="100%">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td width="100%">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="50%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td style="text-transform:uppercase; font-size:16px; margin:0;"><b>CANINE VETERINARY DIETS</b></td>
														</tr>
														<tr>
															<td width="100%">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tr>
																		<td style="height: 5px;"></td>
																	</tr>
																	<tr>
																		<td valign="top" width="10%" style="font-size: 12px; color: #000;">Key:</td>
																		<td valign="top" width="90%">
																			<table width="100%" cellpadding="0" cellspacing="0" border="0">
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Ingredient present</span></td>
																				</tr>
																				<tr>
																					<td style="height: 4px;"></td>
																				</tr>
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Hydrolysed</span></td>
																				</tr>
																				<tr>
																					<td style="height: 4px;"></td>
																				</tr>
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Starch only</span></td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
												<td width="50%"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table class="diets" width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding: 0 4mm;">
					<tr>
						<th style="text-transform:uppercase; color:#366784;font-size:14px;font-weight:bold; border-left: 0px; width: 200px;" valign="bottom">Specific</th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">WET/DRY</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Beef</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Pork</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Lamb</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Duck</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Chicken</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Turkey</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Venison</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Rabbit</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Horse</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Salmon</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">White Fish</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Wheat</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Soya</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Barley</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Rice</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Potato</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Corn</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Oats</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Egg</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Cow’s Milk</span></th>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine DRM</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine DM Diabetes</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine EN Gastrointestinal</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine EN Gastrointestinal</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine OM Obesity</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine OM Obesity</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine JM Joint Mobility</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine NC Neurocare</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine CN Convalescence</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine HP Hepatic</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine NF Renal</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine NF Renal</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine UR Urinary</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine HA Hypoallergenic</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Canine HA Hypoallergenic</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th colspan="22" valign="bottom" style="color:#366784;font-size:14px;font-weight:bold;padding: 10px 0 0 0;margin: 0px; border-right: 0px">HILL’S PET NUTRITION</th>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Derm Defense</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Derm Defense Stew</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">z/d</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">z/d</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">d/d Duck & Rice</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">d/d Salmon & Rice</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">d/d Duck</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">d/d Salmon</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">i/d</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">i/d Stew</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">i/d</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">i/d Sensitive</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">i/d Low Fat</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">i/d Low Fat Stew</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">i/d Low Fat</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Science Plan Sensitive Stomach & Skin</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Science Plan Small & Mini Stomach & Skin</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
				<div style='page-break-after:always'></div>
				<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
					<tbody>
						<tr>
							<td valign="middle" style="padding-left: 15mm;">
								<img src="<?php echo base_url(); ?>assets/images/nextmune-uk.png" alt="Logo" width="320" />
							</td>
							<td valign="middle" align="right" style="padding-right: 8mm;">
								<img src="<?php echo base_url(); ?>assets/images/header-right-img.png" alt="nextlab food test img" width="220" />
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td width="100%" style="background-color: #336584; height: 30px;"></td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 2mm 4mm;">
					<tr>
						<td width="100%">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td width="100%">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="50%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td style="text-transform:uppercase; font-size:16px; margin:0;"><b>CANINE VETERINARY DIETS</b></td>
														</tr>
														<tr>
															<td width="100%">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tr>
																		<td style="height: 5px;"></td>
																	</tr>
																	<tr>
																		<td valign="top" width="10%" style="font-size: 12px; color: #000;">Key:</td>
																		<td valign="top" width="90%">
																			<table width="100%" cellpadding="0" cellspacing="0" border="0">
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Ingredient present</span></td>
																				</tr>
																				<tr>
																					<td style="height: 4px;"></td>
																				</tr>
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Hydrolysed</span></td>
																				</tr>
																				<tr>
																					<td style="height: 4px;"></td>
																				</tr>
																				<tr>
																					<td valign="middle" width="20" style="padding-right: 2mm;"><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 18px;" /></td>
																					<td valign="middle" style="line-height:0; font-size:11px;"><span>Starch only</span></td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
												<td width="50%"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table class="diets" width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 4mm;">
					<tr>
						<th style="text-transform:uppercase; color:#366784;font-size:14px;font-weight:bold; width: 200px;" valign="bottom">HILL’S PET NUTRITION</th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">WET/DRY</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Beef</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Pork</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Lamb</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Duck</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Chicken</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Turkey</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Venison</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Rabbit</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Horse</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Salmon</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">White Fish</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Wheat</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Soya</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Barley</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Rice</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Potato</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Corn</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Oats</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Egg</span></th>
						<th text-rotate="90" style="text-align: center;" valign="bottom"><span class="rotate" style="font-size: 10px;">Cow’s Milk</span></th>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Active Dog</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Active Dog</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Adult all breeds</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Adult large & giant breed</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Adult medium breed</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Adult small breed</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Adult organic</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Adult organic Beef</td>
						<td style="font-size:10px;">Wet</td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Adult organic Fish</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Senior all breeds</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Senior large & giant breed</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Senior medium breed</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Senior small breed</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Food allergen management</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Food allergen management</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Allergen management Plus</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Allergen management Plus</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Struvite management</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Digestive support</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Digestive support</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Digestive support Low Fat</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Endocrine support</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Heart & kidney support</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Heart & kidney support</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Intensive support</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Joint support</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Skin support</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Weight control</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Weight reduction</td>
						<td style="font-size:10px;">Wet</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Weight reduction</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th colspan="22" valign="bottom" style="color:#366784;font-size:14px;font-weight:bold;padding: 10px 0 0 0;margin: 0px;border-right: 0px">VIRBAC – VETERINARY HPM</th>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Digestive Support</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Dermatology Support</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr><td style="font-size:10px;" class="table-first">Hypoallergy 2</td>
						<td style="font-size:10px;">Dry</td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 14px;" width="14"/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
				<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 4mm 0;">
					<tbody>
						<tr>
							<td valign="middle" style="padding-left: 10mm; color:#366784; font-size: 12px;">
								Nextmune UK Laboratories, Unit 651, Street 5, Thorp Arch Trading Estate, Wetherby LS237FZ 
							</td>
							<td valign="middle" align="right" style="padding-right: 10mm; color:#366784; font-size: 12px;">
								Tel: 01494 629979
							</td>
						</tr>
					</tbody>
				</table>
			<?php } ?>
			<?php if((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))){ ?>
				<div style='page-break-after:always'></div>
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
				<div style='page-break-after:always'></div>
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
			<?php if(((preg_match('/\bComplete Food\b/', $respnedn->name)) || (preg_match('/\bNEXTLAB Complete Food\b/', $respnedn->name)) || ((preg_match('/\bSCREEN Environmental\b/', $respnedn->name) || preg_match('/\bComplete Environmental\b/', $respnedn->name)) && preg_match('/\bFood\b/', $respnedn->name))) && (!preg_match('/\bSCREEN Food\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name)) && (!preg_match('/\bFood Positive\b/', $respnedn->name)) && ($foodpos > 0)){ ?>
				<div style='page-break-after:always'></div>
				<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
					<tbody>
						<tr>
							<td valign="middle" style="padding-left: 10mm;">
								<img src="<?php echo base_url(); ?>assets/images/nextmune-uk.png" alt="Logo" width="270" />
							</td>
							<td valign="middle" align="right" style="padding-right: 3mm;">
								<img src="<?php echo base_url(); ?>assets/images/header-right-img.png" alt="nextlab food test img" width="220" />
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;padding:0; background:#ffffff;">
					<tbody>
						<tr>
							<td width="100%" style="background-color: #336584; padding: 0mm 10mm;">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%" style="font-size: 22px; line-height: 28px; color: #fff; text-transform: uppercase;">Interpreting nextlab food test results</td>
										</tr>
									</tbody>
								</table>	
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 2mm 10mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%" style="font-size: 13px; line-height: 18px; color: #57585a; text-align: left;">The only way to diagnose a food allergy is by conducting a diet trial; this in itself should be seen as a diagnostic test. The results from the NEXTLAB food test can help you select which ingredients to use for this.</td>
										</tr>
									</tbody>
								</table>	
							</td>
						</tr>
						<tr>
							<td width="100%" style="height: 12px;"></td>
						</tr>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e9eff4; padding: 3mm 3mm 2mm">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td valign="middle" width="57%">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%">
																				<table width="100%" cellpadding="0" cellspacing="0">
																					<tbody>
																						<tr>
																							<td width="100%" style="font-size: 13px; line-height:20px; text-transform: uppercase; color: #336584; padding: 0 0 1mm 0;">Nextlab food test results</td>
																						</tr>
																						<tr>
																							<td width="100%" style="background-color: #fff;">
																								<table width="100%" style="margin: 0 auto; padding: 1mm 0;" cellpadding="0" cellspacing="0" border="0">
																									<tbody> 
																										<tr>
																											<td width="100%">
																												<img src="<?php echo base_url(); ?>assets/images/chart-<?php echo $cutaoff;?>.png" width="400">
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
																			<td width="100%" style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="font-size: 9px; line-height: 14px; color: #5e697f;">
																				Abbreviated example results for demonstration purposes only.
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
															<td valign="middle" width="3%">&nbsp;</td>
															<td valign="middle" width="40%">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%">
																				<table width="100%" cellpadding="0" cellspacing="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" style="font-size: 11px; line-height:16px; color: #57585a;">
																								In your Nextlab food test results, allergen specific IgE and IgG concentrations are reported as EA units &lt; <?php echo $cutaoff; ?>, <?php echo $cutaoff; ?>-<?php echo $cutboff; ?> & ><?php echo $cutboff; ?>.
																							</td>
																						</tr>
																						<tr>
																							<td style="height: 10px;"></td>
																						</tr>
																						<tr>
																							<td width="100%" style="font-size: 11px; line-height:16px; color: #57585a;">
																								IgE reactivity is classically associated with Type 1 hypersensitivity reactions; however, food-specific IgG levels can be useful in cases of suspected adverse food reaction that are not IgE-mediated.
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td width="100%" style="background-color: #8accd6; padding: 1.5mm 10mm;">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%" style="font-size: 16px; line-height: 24px; color: #fff;">How your <span style="color: #336584">NEXTLAB</span> food test results can be used to help you take the next step towards the diagnosis of a food allergy:</td>
										</tr>
									</tbody>
								</table>	
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 2mm 10mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%" style="font-size: 12px; line-height: 18px; color: #57585a">
												<b style="color: #8accd6; text-transform: uppercase;">STEP 1: SELECT FOODS WITH SCORES &lt;<?php echo $cutboff; ?> EA UNITS – </b>If a food has an EA units as score of &lt;<?php echo $cutaoff; ?> for both IgE & IgG, the high negative predictive value of the test means this food may be suitable as an ingredient for a diet trial. Please note, even if all foods score &lt;<?php echo $cutaoff; ?>, this still does not rule out a food allergy. An EA unit ><?php echo $cutaoff; ?> shows that antibodies have been detected to that food and it should be avoided for the purposes of a diet trial.
											</td>
										</tr>
										<tr>
											<td style="height: 5px;"></td>
										</tr>
										<tr>
											<td width="100%" style="font-size: 12px; line-height: 18px; color: #57585a">
												In situations where the results yield no foods with EA units &lt;<?php echo $cutaoff; ?> to both IgE & IgG, allergens with EA units between <?php echo $cutaoff; ?>-<?php echo $cutboff; ?> may also be considered, if ingestion of that food has been recently proven to be tolerated. If all foods tested have scores higher than this, either a hydrolysed diet, vegetable diet or home-prepared diet using uncommon novel ingredients is advised (see below).
											</td>
										</tr>
										<tr>
											<td style="height: 5px;"></td>
										</tr>
										<tr>
											<td width="100%" style="font-size: 12px; line-height: 18px; color: #57585a">
												<b style="color: #8accd6; text-transform: uppercase;">STEP 2: CONSIDER CROSS-REACTIVITY – </b>Cross-reactivity has been shown to exist between certain proteins. If an animal has scored ><?php echo $cutboff; ?> EA units to a protein source (or that food is a known dietary component), it is advisable to avoid all other similar types of protein in a diet trial (where possible). For example lamb cross reacts with beef, so if lamb scores ><?php echo $cutboff; ?> EA units but beef does not, avoid beef and beef related products eg. cows milk.
											</td>
										</tr>
										<tr>
											<td style="height: 5px;"></td>
										</tr>
										<tr>
											<td width="100%" style="font-size: 12px; line-height: 18px; color: #57585a">
												<b style="color: #8accd6; text-transform: uppercase;">STEP 3: INCORPORATE DIETARY HISTORY – </b>The full dietary history must be considered whether opting for a home-prepared diet (using a single protein and a single carbohydrate source) or a commercial diet. Ingredients to which the animal has not been previously exposed should be selected; where many different foods have been given, more uncommon alternatives might be required; some examples are listed in the table below. Please note that this is not an exhaustive list and as long as the ingredient is novel to the animal in question, and fits with the results of the food test (factoring in cross-reactivity if possible), then it can be a candidate for a food trial.
											</td>
										</tr>
									</tbody>
								</table>	
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td valign="top" width="10%">
								<img src="<?php echo base_url(); ?>assets/images/animals-img.png" width="140">
							</td>
							<td valign="top" width="90%" style="padding-right: 10mm; padding-top: 6px;">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" bordercolor="#8accd6">
													<thead>
														<tr>
															<th style="text-transform: uppercase; color:#97d4dc; background-color: #336584; padding: 0.2mm 2.5mm; font-size: 12px; line-height: 18px; border: 1px solid #c4e5ea; border-left: 0; border-top: 0;"><b>ANIMAL PROTEINS</b></th>
															<th style="text-transform: uppercase; color:#97d4dc; background-color: #336584; padding: 0.2mm 2.5mm; font-size: 12px; line-height: 18px; border: 1px solid #c4e5ea; border-left: 0; border-top: 0;"><b>FISH PROTEINS</b></th>
															<th style="text-transform: uppercase; color:#97d4dc; background-color: #336584; padding: 0.2mm 2.5mm; font-size: 12px; line-height: 18px; border: 1px solid #c4e5ea; border-left: 0; border-top: 0;"><b>OTHER PROTEINS</b></th>
															<th style="text-transform: uppercase; color:#97d4dc; background-color: #336584; padding: 0.2mm 2.5mm; font-size: 12px; line-height: 18px; border: 1px solid #c4e5ea; border-left: 0; border-top: 0;"><b>CARBOHYDRATES</b></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td valign="top" style="color:#000; background-color: #fff; padding: 1mm 3mm 1.5mm; font-size: 11px; line-height: 16px; border: 1px solid #c4e5ea; border-top: 0px; border-left: 0; height: 64.5px;">Goat, goose, insect, kangaroo, ostrich, pheasant, quail.</td>
															<td valign="top" style="color:#000; background-color: #fff; padding: 1mm 3mm 1.5mm; font-size: 11px; line-height: 16px; border: 1px solid #c4e5ea; border-top: 0px; border-left: 0; height: 64.5px;">Blue whiting, capelin, catfish.</td>
															<td valign="top" style="color:#000; background-color: #fff; padding: 1mm 3mm 1.5mm; font-size: 11px; line-height: 16px; border: 1px solid #c4e5ea; border-top: 0px; border-left: 0; height: 64.5px;">Beans, lentils, tofu, vegan Quorn.</td>
															<td valign="top" style="color:#000; background-color: #fff; padding: 1mm 3mm 1.5mm; font-size: 11px; line-height: 16px; border: 1px solid #c4e5ea; border-top: 0px; border-left: 0; height: 64.5px;">Pasta, pumpkin, quinoa, squash, sweet potato, tapioca.</td>
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 4mm 10mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%" style="font-size: 13px; line-height: 18px; color: #57585a">
												<b style="color: #8accd6; text-transform: uppercase;">BE AWARE – </b>Even after factoring in all of the above, both home-prepared and commercial diets (including hydrolysed) may still trigger a reaction in a small number of cases. A second dietary trial using a completely different food is always worth considering, if there is no response to the first. It is especially important to ensure the diet is fully balanced if extending beyond 8 weeks (or if the animal has other health conditions).
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0mm 10mm 3mm;">
					<tbody>
						<tr>
							<td width="100%" style="font-size: 10px; line-height: 16px; color: #57585a;">
								© 2022 Nextmune Laboratories Limited
							</td>
						</tr>
					</tbody>
				</table>
				<div style='page-break-after:always'></div>
				<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
					<tbody>
						<tr>
							<td valign="middle" style="padding-left: 10mm;">
								<img src="<?php echo base_url(); ?>assets/images/nextmune-uk.png" alt="Logo" width="270" />
							</td>
							<td valign="middle" align="right" style="padding-right: 3mm;">
								<img src="<?php echo base_url(); ?>assets/images/header-right-img.png" alt="nextlab food test img" width="220" />
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td width="100%" style="background-color: #336584; padding: 0mm 10mm;">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%" style="font-size: 22px; line-height: 28px; color: #fff; text-transform: uppercase;">Interpreting nextlab food test results</td>
										</tr>
									</tbody>
								</table>	
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 10mm 0mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%" style="font-size: 14px; line-height: 20px; color: #57585a;">There are also a number of other factors to consider, which could have an effect on the serology results:</td>
										</tr>
									</tbody>
								</table>	
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 3mm 10mm 0mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td width="6%" valign="middle" style="font-size: 20px; line-height: 28px; background-color: #336584; color: #fff; padding-left: 4mm">1.</td>
															<td width="2%" valign="middle"><img src="<?php echo base_url(); ?>assets/images/gradient-color-img.png" width="15"></td>
															<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">
																WAS THE ANIMAL FULLY SYMPTOMATIC AT THE TIME OF SAMPLING?
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
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e9eff4; padding: 2mm 0mm 2mm 3mm;">
													<tbody>
														<tr>
															<td width="75%" valign="middle">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				Unless clinical signs are controlled ONLY using medications that are not thought to affect testing (see point 2), sampling should be undertaken when the animal is fully symptomatic so the immune response is likely to be at its highest.
																			</td>
																		</tr>
																		<tr>
																			<td width="100%" style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				We offer free sample storage in case you would like to sample at the optimum time but test at a later date.
																			</td>
																		</tr>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				For more information visit <b>nextmunelaboratories.co.uk/vets/submit-a-sample-uk/</b>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
															<td width="25%" valign="middle" style="text-align: right;">
																<img src="<?php echo base_url(); ?>assets/images/serum-sample-store.png" width="160">
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0mm 10mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td width="6%" valign="middle" style="font-size: 20px; line-height: 28px; background-color: #336584; color: #fff; padding-left: 4mm">2.</td>
															<td width="2%" valign="middle"><img src="<?php echo base_url(); ?>assets/images/gradient-color-img.png" width="15"></td>
															<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">WAS THE ANIMAL ON ANY MEDICATION THAT MIGHT AFFECT TESTING?</td>
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
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e9eff4; padding: 2mm 3mm;">
													<tbody>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				Certain medications have been shown to affect the immune response, and therefore may impact test results. Please see our <b>Withdrawal Guide</b> for guidance at <b>nextmunelaboratories.co.uk/vets/submit-a-sample-uk/</b>
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0mm 10mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td width="6%" valign="middle" style="font-size: 20px; line-height: 28px; background-color: #336584; color: #fff; padding-left: 4mm">3.</td>
															<td width="2%" valign="middle"><img src="<?php echo base_url(); ?>assets/images/gradient-color-img.png" width="15"></td>
															<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">DOES THE ANIMAL SUFFER FROM ANY KIND OF IMMUNODEFICIENCY?</td>
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
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e9eff4; padding: 2mm 3mm;">
													<tbody>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				A generalised immunodeficiency can influence results.
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0mm 10mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td width="6%" valign="middle" style="font-size: 20px; line-height: 28px; background-color: #336584; color: #fff; padding-left: 4mm">4.</td>
															<td width="2%" valign="middle"><img src="<?php echo base_url(); ?>assets/images/gradient-color-img.png" width="15"></td>
															<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">WAS THE ANIMAL ON ITS USUAL DIET PRIOR TO SAMPLING?</td>
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
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e9eff4; padding: 2mm 3mm;">
													<tbody>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				The patient needs to have been eating their normal / unrestricted diet for at least 2 months before blood sampling, otherwise antibody levels may fall too low to be measured.
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0mm 10mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td width="6%" valign="middle" style="font-size: 20px; line-height: 28px; background-color: #336584; color: #fff; padding-left: 4mm">5.</td>
															<td width="2%" valign="middle"><img src="<?php echo base_url(); ?>assets/images/gradient-color-img.png" width="15"></td>
															<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">WAS THE ANIMAL OVER 6 MONTHS OF AGE?</td>
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
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e9eff4; padding: 2mm 3mm;">
													<tbody>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				Animals should ideally be over 6 months of age before testing, to ensure there is no interference from maternal antibodies, the immune system has fully matured, and the animal has been exposed to a variety of foods. If you would like to test an animal under 6 months old, please contact our Customer Support team on <b>01494 629979,</b> or at <b>vetorders.uk@nextmune.com</b> for advice.
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0mm 10mm;">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td width="6%" valign="middle" style="font-size: 20px; line-height: 28px; background-color: #336584; color: #fff; padding-left: 4mm">6.</td>
															<td width="2%" valign="middle"><img src="<?php echo base_url(); ?>assets/images/gradient-color-img.png" width="15"></td>
															<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">COULD THE HYPERSENSITIVITY BE TO AN UNUSUAL ALLERGEN?</td>
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
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e9eff4; padding: 2mm 3mm;">
													<tbody>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				Our species-specific test panels are developed to identify hypersensitivity to the most common allergens implicated in food allergies. This is based upon guidelines within current literature, advice from dermatologists and selection of common ingredients used. It is, however, possible that the animal is hypersensitive to an unusual allergen not included in the panel.
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0mm 10mm 5mm;">
					<tbody>	
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td width="6%" valign="middle" style="font-size: 20px; line-height: 28px; background-color: #336584; color: #fff; padding-left: 4mm">7.</td>
															<td width="2%" valign="middle"><img src="<?php echo base_url(); ?>assets/images/gradient-color-img.png" width="15"></td>
															<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">IS THE ANIMAL SUFFERING FROM A FOOD INTOLERANCE?</td>
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
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e9eff4; padding: 2mm 3mm 4mm;">
													<tbody>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #57585a;">
																				A food intolerance can result in symptoms similar to those of food allergy, but it does not involve the immune system. A food trial will still be useful to identify the causal foods in this situation.
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tbody>
										<tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr>
															<td width="74%" valign="middle" style="background-color: #afd9e2; padding: 2mm 0 2mm 10mm;">
																<table width="100%" cellpadding="0" cellspacing="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" style="font-size: 13px; line-height: 16px; color: #426e89;">
																				Diet trials are a big undertaking and, like any diagnostic test, must be run properly in order to generate meaningful results. To enable your clients to make a success of their pet’s diet trial, please see our <b>Diet Trial Instructions,</b> available in the Nextmune UK Laboratories Practice Portal at <b>nextmunelaboratories.co.uk/login</b>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
															<td width="10%">
																<img src="<?php echo base_url(); ?>assets/images/footer-gradient-img.png" width="79">
															</td>
															<td width="16%" valign="middle" style="text-align: right; background-color: #f0f3f8; padding: 2mm 10mm 2mm 0;">
																<img src="<?php echo base_url(); ?>assets/images/lock-new.png" width="60">
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
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td style="height: 10px;"></td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td style="background:#426e89; padding:3mm 10mm; color:#ffffff; font-size: 13px; line-height: 16px;" align="center">
								Nextmune Laboratories Limited, Unit 651, Street 5, Thorp Arch Trading Estate, Wetherby, UK, LS23 7FZ<br> T – <b>01494 629979</b> E – <b>vetorders.uk@nextmune.com</b>
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:3mm 10mm 5mm;">
					<tbody>
						<tr>
							<td style="font-size:10px; color:#333333;">© 2022 Nextmune Laboratories Limited</td>
							<td style="font-size:10px; color:#333333;" align="right">NML_058_09_22 (V)</td>
						</tr>
					</tbody>
				</table>
			<?php } ?>
		<?php } ?>
	</body>
</html>