<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();

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
	$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
	$fulladdress = $add_1.$add_2.$add_3.$city.$postcode;
}else{
	$fulladdress = '';
	$account_ref = '';
}

$serumdata = $this->OrdersModel->getSerumTestRecord($id);
$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
$ordeType = $respnedn->name;
$ordeTypeID = $respnedn->id;

/* get removed treatment 1 */
$removed_treatment_1 = array();
$removed_treatment_1 = $order_details['removed_treatment_1'];
if(!empty($removed_treatment_1)){
	$removed_treatment_1 = json_decode($removed_treatment_1);
}

/* get removed treatment 2 */
$removed_treatment_2 = array();
$removed_treatment_2 = $order_details['removed_treatment_2'];
if(!empty($removed_treatment_2)){
	$removed_treatment_2 = json_decode($removed_treatment_2);
}
$boxremoved = 0; $box2removed = 0;

/* Environmental */
if($ordeType == 'PAX Environmental' || $ordeType == 'PAX Environmental Screening' || $ordeType == 'PAX Environmental + Food' || $ordeType == 'PAX Environmental + Food Screening'){
	$getEAllergenParent = $this->AllergensModel->getEnvAllergenParentbyName($order_details['allergens']);
	$totalGroup0 = count($getEAllergenParent);
	$totalGroup2 = $totalGroup0/2;
	$partA = ((round)($totalGroup2));
	$partB = $partA;

	$allengesArr = []; $allenges3Arr = []; $allenges4Arr = []; $allengesIDArr = []; $allengesID3Arr = []; $allengesID4Arr = []; $block1 = []; $blocks1 = []; $allengesIDsArr = array(); $dummytext = "";
	foreach ($getEAllergenParent as $apkey => $apvalue){
		$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
		foreach ($subAllergens as $skey => $svalue) {
			$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
			if(!empty($subVlu->raptor_code)){
				$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
				if(!empty($raptrVlu)){
					if(floor($raptrVlu->result_value) >= $cutoffs){
						if($svalue['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($svalue['id']) > 0){
							if((!in_array($svalue['id'],$removed_treatment_1)) && (!in_array($svalue['id'],$removed_treatment_2))){
								$allenges3Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
								$allengesID3Arr[] = $svalue['id'];
							}
							$block1[$svalue['id']] = $svalue['name'];
						}else{
							if((!in_array($svalue['id'],$removed_treatment_1)) && (!in_array($svalue['id'],$removed_treatment_2))){
								$allenges4Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
								$allengesID4Arr[] = $svalue['id'];
							}
							$blocks1[$svalue['id']] = $svalue['name'];
						}
						if((!in_array($svalue['id'],$removed_treatment_1)) && (!in_array($svalue['id'],$removed_treatment_2))){
							$allengesIDArr[] = $svalue['id'];
						}
						$allengesIDsArr[] = $svalue['id'];
						$allengesArr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
					}
				}
			}
		}
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
				if($svalue['name'] != "N/A"){
					if((!in_array($svalue['id'],$removed_treatment_1)) && (!in_array($svalue['id'],$removed_treatment_2)) && (!in_array($svalue['id'],$allengesID3Arr))){
						$allenges3Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
					}
				}else{
					if((!in_array($svalue['id'],$removed_treatment_1)) && (!in_array($svalue['id'],$removed_treatment_2)) && (!in_array($svalue['id'],$allengesID4Arr))){
						$allenges4Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
					}
				}
			}
		}
	}

	$block2 = []; $chk_alg_cunt = 0;
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
					foreach($parentIdArr as $makey=>$mavalue){
						$allergenArr = json_decode($getGroupMixtures[$makey]['mixture_allergens']);
						$testingArr = [];
						foreach($allergenArr as $amid){
							$rmcodes = $this->OrdersModel->getsubAllergensCode($amid);
							if(!empty($rmcodes->raptor_code)){
								$raptrmVlu = $this->OrdersModel->getRaptorValue($rmcodes->raptor_code,$raptorData->result_id);
								if(!empty($raptrmVlu)){
									if(floor($raptrmVlu->result_value) >= $cutoffs){
										$testingArr[$mavalue] += 1;
									}
								}
							}
						}

						if(count($allergenArr) >= 3){
							$chk_alg_cunt = (count($allergenArr)-1);
							if($testingArr[$mavalue] >= $chk_alg_cunt){
								if($getGroupMixtures[$makey]['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($getGroupMixtures[$makey]['id']) > 0){
									$block2[$getGroupMixtures[$makey]['id']] = $getGroupMixtures[$makey]['name'];
								}
							}
						}else{
							if($testingArr[$mavalue] >= 2){
								if($getGroupMixtures[$makey]['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($getGroupMixtures[$makey]['id']) > 0){
									$block2[$getGroupMixtures[$makey]['id']] = $getGroupMixtures[$makey]['name'];
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
								if(floor($raptrVlu->result_value) >= $cutoffs){
									$tested++;
								}
							}
						}
					}
					
					if($apvalue['parent_id'] == 1){
						if($tested >= 3){
							if($getGroupMixtures[0]['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($getGroupMixtures[0]['id']) > 0){
								$block2[$getGroupMixtures[0]['id']] = $getGroupMixtures[0]['name'];
							}
						}
					}else{
						if(count($allergensArr) >= 3){
							$chk_alg_cunt = (count($allergensArr)-1);
							if($tested >= $chk_alg_cunt){
								if($getGroupMixtures[0]['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($getGroupMixtures[0]['id']) > 0){
									$block2[$getGroupMixtures[0]['id']] = $getGroupMixtures[0]['name'];
								}
							}
						}else{
							if($tested >= 2){
								if($getGroupMixtures[0]['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($getGroupMixtures[0]['id']) > 0){
									$block2[$getGroupMixtures[0]['id']] = $getGroupMixtures[0]['name'];
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
							if($raptr2Vlu->result_value >= 30){
								if($s2value['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($s2value['id']) > 0){
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
						if($raptr3Vlu->result_value >= 30){
							if($s3value['name'] != "N/A" && $this->AllergensModel->checkforArtuveterinallergen($s3value['id']) > 0){
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
					if((!in_array($svalue['id'],$removed_treatment_1)) && (!in_array($svalue['id'],$removed_treatment_2)) && (!in_array($svalue['id'],$allengesID3Arr))){
						$allenges3Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
					}
				}else{
					if((!in_array($svalue['id'],$removed_treatment_1)) && (!in_array($svalue['id'],$removed_treatment_2)) && (!in_array($svalue['id'],$allengesID4Arr))){
						$allenges4Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
					}
				}
			}
		}
	}
	
	if(count($allengesArr) > 1){
		asort($allengesArr);
		$lastchnk = end($allengesArr);
		$lastchnkName = ', '.$lastchnk;
		$lastchnkCng = ' '.$this->lang->line('recommendation_text_and').' '.$lastchnk;
		$allengesStr = implode(", ",$allengesArr);
		$allengeStr = str_replace($lastchnkName,$lastchnkCng,$allengesStr);
	}else{
		$allengeStr = implode(", ",$allengesArr);
	}

	if(count($allenges3Arr) > 1){
		asort($allenges3Arr);
		$last3chnk = end($allenges3Arr);
		$lastchnk3Name = ', '.$last3chnk;
		$lastchnk3Cng = ' '.$this->lang->line('recommendation_text_and').' '.$last3chnk;
		$allenges3Str = implode(", ",$allenges3Arr);
		$allenge3Str = str_replace($lastchnk3Name,$lastchnk3Cng,$allenges3Str);
	}else{
		$allenge3Str = implode(", ",$allenges3Arr);
	}

	if(count($allenges4Arr) > 1){
		asort($allenges4Arr);
		$last4chnk = end($allenges4Arr);
		$lastchnk4Name = ', '.$last4chnk;
		$lastchnk4Cng = ' '.$this->lang->line('recommendation_text_and').' '.$last4chnk;
		$allenges4Str = implode(", ",$allenges4Arr);
		$allenge4Str = str_replace($lastchnk4Name,$lastchnk4Cng,$allenges4Str);
	}else{
		$allenge4Str = implode(", ",$allenges4Arr);
	}

	$dummytext .= $this->lang->line('summery_text_1') . $allengeStr .".";
	$dummytext .= "\n\r";
	if(!empty($allenges3Arr)){
	$dummytext .= $this->lang->line('summery_text_2') . $allenge3Str .".";
	$dummytext .= "\n\r";
	}
	if(!empty($allenges4Arr)){
	$dummytext .= $this->lang->line('summery_text_3') . $allenge4Str . $this->lang->line('summery_text_3a');
	$dummytext .= "\n\r";
	}
	$dummytext .= $this->lang->line('summery_text_4');
	$dummytext .= "\n\r";
	$dummytext .= $this->lang->line('summery_text_5');
	$dummytext .= "\n\r";

	$sql = "SELECT result_value, name FROM `ci_raptor_result_allergens` WHERE result_id = '". $raptorData->result_id ."' AND (name LIKE 'CCD-HSA' OR name LIKE 'Hom s LF')";
	$responce = $this->db->query($sql);
	$ccd1Results = $responce->result();
	$ccdresult1 = 0; $ccdresult2 = 0;
	if(!empty($ccd1Results)){
		foreach($ccd1Results as $cvalue){
			if($cvalue->name == 'CCD-HSA'){
				$ccdresult1 = $cvalue->result_value;
			}
			if($cvalue->name == 'Hom s LF'){
				$ccdresult2 = $cvalue->result_value;
			}
		}
	}

	if(floor($ccdresult1) >= $cutoffs || floor($ccdresult2) >= $cutoffs){
		$dummytext .= $this->lang->line('pax_ccd_text_'.$cutoffs.'');
		$dummytext .= "\n\r";
	}
	
	if(!empty($block1)){
		foreach($block1 as $key=>$value){
			if(!in_array($key,$removed_treatment_1)){
				$boxremoved++;
			}
		}
	}

	if(!empty($block2)){
		foreach($block2 as $key=>$value){
			if(!in_array($key,$removed_treatment_2)){
				$box2removed++;
			}
		}
	}
}

if($ordeType == 'PAX Food' || $ordeType == 'PAX Food Screening' || $ordeType == 'PAX Environmental + Food' || $ordeType == 'PAX Environmental + Food Screening'){
	/* Food */
	$getFAllergenParent = $this->AllergensModel->getFoodAllergenParentbyName($order_details['allergens']);
	$totalfGroup = count($getFAllergenParent);
	$totalf1Group = $totalfGroup/2;
	$partPF = ((round)($totalf1Group));
	$partF = $partPF;

	$allengesFArr = []; $allengesF3Arr = []; $allengesF4Arr = []; $dummyFtext = ""; $allengesIDFArr = array(); $foodtotal = 0;
	foreach ($getFAllergenParent as $apkey => $apvalue){
		$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
		foreach ($subAllergens as $skey => $svalue) {
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
	}

	if(count($allengesFArr) > 1){
		$lastchnk = end($allengesFArr);
		$lastchnkName = ', '.$lastchnk;
		$lastchnkCng = ' '.$this->lang->line('recommendation_text_and').' '.$lastchnk;
		$allengesStr = implode(", ",$allengesFArr);
		$allengeStr = str_replace($lastchnkName,$lastchnkCng,$allengesStr);
	}else{
		$allengeStr = implode(", ",$allengesFArr);
	}

	if(count($allengesF3Arr) > 1){
		$last3chnk = end($allengesF3Arr);
		$lastchnk3Name = ', '.$last3chnk;
		$lastchnk3Cng = ' '.$this->lang->line('recommendation_text_and').' '.$last3chnk;
		$allenges3Str = implode(", ",$allengesF3Arr);
		$allenge3Str = str_replace($lastchnk3Name,$lastchnk3Cng,$allenges3Str);
	}else{
		$allenge3Str = implode(", ",$allengesF3Arr);
	}

	if(count($allengesF4Arr) > 1){
		$last4chnk = end($allengesF4Arr);
		$lastchnk4Name = ', '.$last4chnk;
		$lastchnk4Cng = ' '.$this->lang->line('recommendation_text_and').' '.$last4chnk;
		$allenges4Str = implode(", ",$allengesF4Arr);
		$allenge4Str = str_replace($lastchnk4Name,$lastchnk4Cng,$allenges4Str);
	}else{
		$allenge4Str = implode(", ",$allengesF4Arr);
	}

	$dummyFtext .= $this->lang->line('summery_text_1') . $allengeStr .".";
	$dummyFtext .= "\n\r";
	$dummyFtext .= $this->lang->line('summery_food_text_2') . $allengeStr .  $this->lang->line('summery_food_text_2a');
	$dummyFtext .= "\n\r";
	$dummyFtext .= $this->lang->line('summery_food_text_3');
	$dummyFtext .= "\n\r";

	$sql = "SELECT result_value, name FROM `ci_raptor_result_allergens` WHERE result_id = '". $raptorData->result_id ."' AND (name LIKE 'CCD-HSA' OR name LIKE 'Hom s LF')";
	$responce = $this->db->query($sql);
	$ccd1Results = $responce->result();
	$ccdresult1 = 0; $ccdresult2 = 0;
	if(!empty($ccd1Results)){
		foreach($ccd1Results as $cvalue){
			if($cvalue->name == 'CCD-HSA'){
				$ccdresult1 = $cvalue->result_value;
			}
			if($cvalue->name == 'Hom s LF'){
				$ccdresult2 = $cvalue->result_value;
			}
		}
	}

	if(floor($ccdresult1) >= $cutoffs || floor($ccdresult2) >= $cutoffs){
		$dummyFtext .= $this->lang->line('pax_ccd_text_'.$cutoffs.'');
		$dummyFtext .= "\n\r";
	}
}

if($order_details['species_name'] == 'Horse'){
	$speciesName = $this->lang->line('horse');
}elseif($order_details['species_name'] == 'Cat'){
	$speciesName = $this->lang->line('cat');
}else{
	$speciesName = $this->lang->line('dog');
}
?>
			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
			<link rel="stylesheet" href='<?php echo base_url("assets/dist/css/vet_style.css"); ?>' />
			<style>
			.sticky {position:sticky;top:0;width:100%;z-index:99;opacity:99;}
			*{margin:0;padding:0;box-sizing:border-box;font-family:'Mark Pro'}
			img{max-width:100%}
			html{scroll-behavior:smooth}
			@font-face{font-family:'Mark Pro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro.woff"); ?>') format("woff");font-weight:400;font-style:normal;font-display:swap}
			@font-face{font-family:'MarkPro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro-Medium.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro-Medium.woff"); ?>') format("woff");font-weight:500;font-style:normal;font-display:swap}
			@font-face{font-family:'Mark Pro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro-Bold.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro-Bold.woff"); ?>') format("woff");font-weight:700;font-style:normal;font-display:swap}
			body{font-family:'Mark Pro'}
			.tab{overflow:hidden;background-color:#ffffff}
			.tab button{background-color:inherit;float:left;border:1px solid #3c8dbc;outline:none;cursor:pointer;padding:14px 16px;transition:.3s;font-size:17px;color:#366784}
			.tab button:hover{background-color:#2f5c79;color:#FFF}
			.tab button.active{background-color:#244459;color:#FFF}
			.tabcontent{display:none;border:1px solid #3c8dbc;border-top:none}
			</style>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						<?php echo $this->lang->line('page_title'); ?>
						<small><?php echo $this->lang->line('page_subtitle'); ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="<?php echo site_url('orders'); ?>"> <?php echo $this->lang->line('Orders_Management'); ?></a></li>
						<li class="active"><?php echo $this->lang->line('page_subtitle'); ?> </li>
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
						<h4><i class="icon fa fa-check"></i> Alert!</h4>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>
					<?php if(!empty($this->session->flashdata('error'))){ ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-warning"></i> Alert!</h4>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php } ?>
					<!--alert msg-->
					<div class="row">
						<div class="col-xs-5" style="min-height:1325px;">
							<?php echo form_open('', array('name'=>'interpretationForm', 'id'=>'interpretationForm')); ?>
								<div class="box box-primary">
									<div class="box-header with-border">
										<p class="pull-left">
											<a href="<?php echo site_url('orders'); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i>Back</a>
										</p>
									</div>
									<div class="box-body">
										<h2 style="margin:0px; font-weight:700; font-size:28px; color:#366784;">Communicate Results to Veterinarian Surgeon</h2><hr style="margin: 10px 0px;">
										<div class="form-group">
											<label>Laboratory Comment</label>
											<textarea class="form-control internal_comment" name="internal_comment" rows="5" placeholder="Enter Laboratory Comment"><?php echo $order_details['internal_comment']; ?></textarea>
										</div>
										<?php if($ordeType == 'PAX Environmental' || $ordeType == 'PAX Environmental Screening' || $ordeType == 'PAX Environmental + Food' || $ordeType == 'PAX Environmental + Food Screening'){ ?>
											<div class="form-group">
												<label>Result Environmental Interpretation</label>
												<textarea class="form-control interpretation" name="interpretation" rows="15"><?php echo !empty($order_details['interpretation'])?$order_details['interpretation']:$dummytext; ?></textarea>
											</div>
										<?php } ?>
										<?php if($ordeType == 'PAX Food' || $ordeType == 'PAX Food Screening' || $ordeType == 'PAX Environmental + Food' || $ordeType == 'PAX Environmental + Food Screening'){ ?>
											<div class="form-group">
												<label>Result Food Interpretation</label>
												<textarea class="form-control interpretation_food" name="interpretation_food" rows="15"><?php echo !empty($order_details['interpretation_food'])?$order_details['interpretation_food']:$dummyFtext; ?></textarea>
											</div>
										<?php } ?>
										<div class="form-group">
											<button type="submit" value="next" name="next" class="btn btn-primary mrgnbtm10 next_btn" <?php if($order_details['is_serum_result_sent'] == 1) { echo 'disabled="disabled"'; } ?>>Save Laboratory Comment and Result Interpretation</button>
											<?php if (isset($order_details['requisition_form']) && $order_details['requisition_form'] != '') { ?>
												<a class="btn btn-primary mrgnbtm10" onclick="window.open('<?php echo base_url() . REQUISITION_FORM_PATH; ?>/<?php echo $order_details['requisition_form']; ?>','Requisition Form','width=1200,height=9000')" title="View Order Requisition"> View Uploaded Order Requisition Form</a>
											<?php } ?>
											<?php if($ordeType == "PAX Environmental + Food" || $ordeType == "PAX Environmental & Food Screening Expanded"){ ?>
											<a target="_blank" href="<?php echo site_url('orders/downloadPaxResultENV/'.$order_details['id'].''); ?>" class="btn btn-primary mrgnbtm10"> Download / Print Environmental test result</a>
											<a target="_blank" href="<?php echo site_url('orders/downloadPaxResultFood/'.$order_details['id'].''); ?>" class="btn btn-primary mrgnbtm10"> Download / Print Food test result</a>
											<?php }elseif($ordeType == "PAX Environmental + Food Screening"){ ?>
											<a target="_blank" href="<?php echo site_url('orders/downloadPaxResultENV/'.$order_details['id'].''); ?>" class="btn btn-primary mrgnbtm10"> Download / Print Environmental Screening test result</a>
											<a target="_blank" href="<?php echo site_url('orders/downloadPaxResultFood/'.$order_details['id'].''); ?>" class="btn btn-primary mrgnbtm10"> Download / Print Food Screening test result</a>
											<?php }elseif($ordeType == "PAX Environmental" || $ordeType == "PAX Environmental Screening" || $ordeType == "PAX Environmental Screening Expanded"){ ?>
											<a target="_blank" href="<?php echo site_url('orders/downloadPaxResultENV/'.$order_details['id'].''); ?>" class="btn btn-primary mrgnbtm10"> Download / Print Result</a>
											<?php }elseif($ordeType == "PAX Food" || $ordeType == "PAX Food Screening" || $ordeType == "PAX Food Screening Expanded"){ ?>
											<a target="_blank" href="<?php echo site_url('orders/downloadPaxResultFood/'.$order_details['id'].''); ?>" class="btn btn-primary mrgnbtm10"> Download / Print Result</a>
											<?php } ?>
											<?php
											$zonesIds = $this->OrdersModel->checkZones($order_details['id']);
											if(!empty($zonesIds) && in_array("8", $zonesIds)){
											?>
												<a target="_blank" href="<?php echo site_url('orders/getSerumResultExcel/'.$order_details['id'].''); ?>" class="btn btn-primary mrgnbtm10"> Download Excel Document</a>
												<?php
												if($ordeType == 'PAX Environmental' || $ordeType == 'PAX Environmental + Food'){
												?>
<a target="_blank" href="<?php echo site_url('orders/getSerumResultExcel/'.$order_details['id'].'?modify=1'); ?>" class="btn btn-primary mrgnbtm10"> Modify Excel Document</a>
												<?php
												}
											} ?>
											<br>
											<?php if($ordeType == 'PAX Environmental' || $ordeType == 'PAX Environmental + Food'){ ?>
												<?php if(!empty($block1) && $boxremoved > 0 && $order_details['is_serum_result_sent'] != 1){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation1" name="recommendation1" value="Edit Immunotherapy Treatment Opt 1">
												<?php }elseif(!empty($block1) && $order_details['is_serum_result_sent'] != 1){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation1" name="recommendation1" value="Add Immunotherapy Treatment Opt 1">
												<?php } ?>
												<?php if(!empty($block2) && $box2removed > 0 && $order_details['is_serum_result_sent'] != 1 && $order_details['remove_treatment_2'] == 0){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation2" name="recommendation2" value="Edit Immunotherapy Treatment Opt 2">
												<?php }elseif(empty($block2) && !empty($block1) && $order_details['is_serum_result_sent'] != 1){ ?>
													<?php if($order_details['remove_treatment_2'] == 1){ ?>
													<a onclick="return confirm('Are you sure you want to Add Treatment Option 2?')" href="<?php echo site_url('orders/add_pax_treatment2/'.$id.''); ?>" class="btn btn-primary mrgnbtm10">Add Immunotherapy Treatment Opt 2</a>
													<?php }else{ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation2" name="recommendation2" value="Add Immunotherapy Treatment Opt 2">
													<?php } ?>
												<?php } ?>
											<?php } ?>
										</div>
										<div class="form-group">   
											<label>Annotated by</label>
											<input type="text" class="form-control" name="annotated_by" placeholder="Enter Your Name" value="<?php echo !empty($userData['name'])?$userData['name']:''; ?>">
										</div>
										<div class="form-group">   
											<label>Price</label>
											<input type="text" class="form-control" name="price" value="<?php echo $order_details['unit_price'];?>" required="">
										</div>
										<div class="form-group">
											<button id="sendResult" type="button" class="btn btn-primary top">Send Results</button>
										</div>
										<?php if(!empty($block1) && ($ordeType == 'PAX Environmental' || $ordeType == 'PAX Environmental + Food') && ($this->user_role == '1' || $this->user_role == '11')){ ?>
											<div class="form-group">
												<h2 style="margin:0px;font-weight:700;font-size:28px;color:#366784;">Order Recommendations</h2><hr style="margin: 10px 0px;">
												<?php if(!empty($block1) && $boxremoved > 0 && $order_details['is_serum_result_sent'] != 1){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendations1" name="recommendations1" value="Order IM recommendation 1">
												<?php } ?>
												<?php if(!empty($block2) && $box2removed > 0 && $order_details['is_serum_result_sent'] != 1 && $order_details['remove_treatment_2'] == 0){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendations2" name="recommendations2" value="Order IM recommendation 2">
												<?php } ?>
												<input type="button" class="btn btn-primary mrgnbtm10 vetgoid" name="vetgoid" value="Vet-Goid">
												<input type="button" class="btn btn-primary mrgnbtm10 petslit" name="petslit" value="PetSlit">
											</div>
										<?php } ?>
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>
						<div class="col-xs-7 scroll" style="height:1325px;overflow:scroll;padding:0px;">
							<div class="box box-primary">
								<div class="box-header with-border">
									<div class="col-xs-4 pull-right">
										<select onchange="javascript:window.location.href='<?php echo base_url(); ?>LanguageSwitcher/switchLang/'+this.value;" class="form-control">
											<option value="english" <?php if($this->session->userdata('site_lang') == 'english' && $this->session->userdata('export_site_lang') == 'english') echo 'selected="selected"'; ?>><?php echo $this->lang->line('english'); ?></option>
											<option value="danish" <?php if($this->session->userdata('site_lang') == 'danish') echo 'selected="selected"'; ?>>Danish</option>
											<option value="french" <?php if($this->session->userdata('site_lang') == 'french') echo 'selected="selected"'; ?>>French</option>
											<option value="german" <?php if($this->session->userdata('site_lang') == 'german') echo 'selected="selected"'; ?>>German</option>
											<option value="italian" <?php if($this->session->userdata('site_lang') == 'italian') echo 'selected="selected"'; ?>>Italian</option>
											<option value="dutch" <?php if($this->session->userdata('site_lang') == 'dutch') echo 'selected="selected"'; ?>>Dutch</option>
											<option value="norwegian" <?php if($this->session->userdata('site_lang') == 'norwegian') echo 'selected="selected"'; ?>>Norwegian</option>
											<option value="spanish" <?php if($this->session->userdata('site_lang') == 'spanish') echo 'selected="selected"'; ?>>Spanish</option>
											<option value="swedish" <?php if($this->session->userdata('site_lang') == 'swedish') echo 'selected="selected"'; ?>>Swedish</option>
											<option value="export_dutch" <?php if($this->session->userdata('export_site_lang') == 'export_dutch') echo 'selected="selected"'; ?>>Export NL</option>
											<option value="export_spanish" <?php if($this->session->userdata('export_site_lang') == 'export_spanish') echo 'selected="selected"'; ?>>Export ES</option>
										</select>
									</div>
								</div>
							</div>
							<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
								<tr>
									<td style="padding: 5px;">
										<?php require_once(APPPATH."views/orders/medical_history.php"); ?>
									</td>
								</tr>
							</table>

							<?php if($ordeType == 'PAX Environmental'){ ?>
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
									<tr>
										<td style="padding: 5px;">
											<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;">
												<tr>
													<td valign="middle" width="530" style="padding:60px 30px 20px 0;">
														<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:130px;max-width:360px; border-radius:4px;" />
														<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('PAX_Environmental'); ?></h5>
													</td>
													<td valign="middle">
														<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?>:</th>
																<td style="color:#000000;"><?php echo $speciesName;?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
																<td style="color:#000000;"><?php echo $fulladdress; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Email'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																<td style="color:#000000;"><?php echo $ordeType; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('lab_number'); ?></th>
																<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $account_ref; ?></td>
															</tr>
															<?php if($order_details['lab_id'] > 0){ ?>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Lab'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
															</tr>
															<?php } ?>
														</table>
													</td>
												</tr>
											</table>
											<table width="100%"><tr><td height="20"></td></tr></table>
											<?php require_once(APPPATH."views/orders/pax_result_summary.php"); ?>
										</td>
									</tr>
								</table>
								<?php if(empty($block1) && empty($blocks1)){ ?>
								<table width="100%"><tr><td height="20"></td></tr></table>
								<?php require_once(APPPATH."views/orders/pax_negative_faq.php"); ?>
								<?php } ?>
								<?php if(!empty($block1) || !empty($blocks1)){ ?>
								<?php echo form_open('orders/recommendation/'.$order_details['id'].'', array('name'=>'recommendationForm', 'id'=>'recommendation1Form')); ?>
									<input type="hidden" id="treatment" name="treatment" value="" />
									<?php require_once(APPPATH."views/orders/pax_summary_recommendation.php"); ?>
								<?php echo form_close(); ?>
								<?php } ?>
								<?php require_once(APPPATH."views/orders/pax_result_panel.php"); ?>
								<?php if(!empty($block1) || !empty($blocks1)){ ?>
								<?php require_once(APPPATH."views/orders/pax_interpretation_support.php"); ?>
								<?php require_once(APPPATH."views/orders/pax_faq.php"); ?>
								<?php } ?>
							<?php }elseif($ordeType == 'PAX Food'){ ?>
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
									<tr>
										<td style="padding: 5px;">
											<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;">
												<tr>
													<td valign="middle" width="530" style="padding:60px 30px 20px 0;">
														<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:130px;max-width:360px; border-radius:4px;" />
														<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('pax_food'); ?></h5>
													</td>
													<td valign="middle">
														<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?>:</th>
																<td style="color:#000000;"><?php echo $speciesName;?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
																<td style="color:#000000;"><?php echo $fulladdress; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Email'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																<td style="color:#000000;"><?php echo $ordeType; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('lab_number'); ?></th>
																<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $account_ref; ?></td>
															</tr>
															<?php if($order_details['lab_id'] > 0){ ?>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Lab'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
															</tr>
															<?php } ?>
														</table>
													</td>
												</tr>
											</table>
											<table width="100%"><tr><td height="20"></td></tr></table>
											<?php require_once(APPPATH."views/orders/pax_result_summary_food.php"); ?>
										</td>
									</tr>
								</table>
								<?php if($foodtotal == 0){ ?>
								<table width="100%"><tr><td height="20"></td></tr></table>
								<?php require_once(APPPATH."views/orders/pax_negative_faq_food.php"); ?>
								<?php } ?>
								<?php if($foodtotal > 0){ ?>
								<?php require_once(APPPATH."views/orders/pax_summary_recommendation_food.php"); ?>
								<?php } ?>
								<?php require_once(APPPATH."views/orders/pax_result_panel_food.php"); ?>
								<?php if($foodtotal > 0){ ?>
								<?php require_once(APPPATH."views/orders/pax_interpretation_support_food.php"); ?>
								<?php if($this->session->userdata('export_site_lang') == 'export_spanish'){ require_once(APPPATH."views/orders/pax_diet_chart_spanish.php"); }elseif($this->session->userdata('export_site_lang') == 'export_dutch'){ require_once(APPPATH."views/orders/pax_diet_chart_dutch.php"); }else{ require_once(APPPATH."views/orders/pax_diet_chart_".$this->session->userdata('site_lang').".php"); } ?>
								<?php } ?>
							<?php }elseif($ordeType == 'PAX Environmental + Food'){ ?>
								<div class="tab">
									<button class="tablinks active" id="tabenv" onclick="openEnvironmental()"><?php echo $this->lang->line('environmental_result'); ?></button>
									<button class="tablinks" id="tabfood" onclick="openFood()"><?php echo $this->lang->line('food_result'); ?></button>
								</div>
								<div id="tab-environmental" class="tabcontent" style="display:block;">
									<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
										<tr>
											<td style="padding: 5px;">
												<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;">
													<tr>
														<td valign="middle" width="530" style="padding:60px 30px 20px 0;">
															<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:130px;max-width:360px; border-radius:4px;" />
															<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('PAX_Environmental'); ?></h5>
														</td>
														<td valign="middle">
															<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?>:</th>
																	<td style="color:#000000;"><?php echo $speciesName;?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
																	<td style="color:#000000;"><?php echo $fulladdress; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Email'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																	<td style="color:#000000;"><?php echo $ordeType; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																	<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('lab_number'); ?></th>
																	<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
																	<td style="color:#000000;"><?php echo $account_ref; ?></td>
																</tr>
																<?php if($order_details['lab_id'] > 0){ ?>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Lab'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
																</tr>
																<?php } ?>
															</table>
														</td>
													</tr>
												</table>
												<table width="100%"><tr><td height="20"></td></tr></table>
												<?php require_once(APPPATH."views/orders/pax_result_summary.php"); ?>
											</td>
										</tr>
									</table>
									<?php if(empty($block1) && empty($blocks1)){ ?>
									<table width="100%"><tr><td height="20"></td></tr></table>
									<?php require_once(APPPATH."views/orders/pax_negative_faq.php"); ?>
									<?php } ?>
									<?php if(!empty($block1) || !empty($blocks1)){ ?>
									<?php echo form_open('orders/recommendation/'.$order_details['id'].'', array('name'=>'recommendationForm', 'id'=>'recommendation1Form')); ?>
										<input type="hidden" id="OrderRecommendationsBtns" name="OrderRecommendationsBtns" value="" />
										<input type="hidden" id="treatment" name="treatment" value="" />
										<?php require_once(APPPATH."views/orders/pax_summary_recommendation.php"); ?>
									<?php echo form_close(); ?>
									<?php } ?>
									<?php require_once(APPPATH."views/orders/pax_result_panel.php"); ?>
									<?php if(!empty($block1) || !empty($blocks1)){ ?>
									<?php require_once(APPPATH."views/orders/pax_interpretation_support.php"); ?>
									<?php require_once(APPPATH."views/orders/pax_faq.php"); ?>
									<?php } ?>
								</div>
								<div id="tab-food" class="tabcontent">
									<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
										<tr>
											<td style="padding: 5px;">
												<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;">
													<tr>
														<td valign="middle" width="530" style="padding:60px 30px 20px 0;">
															<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:130px;max-width:360px; border-radius:4px;" />
															<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('pax_food'); ?></h5>
														</td>
														<td valign="middle">
															<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?>:</th>
																	<td style="color:#000000;"><?php echo $speciesName;?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
																	<td style="color:#000000;"><?php echo $fulladdress; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Email'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																	<td style="color:#000000;"><?php echo $ordeType; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																	<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('lab_number'); ?></th>
																	<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
																	<td style="color:#000000;"><?php echo $account_ref; ?></td>
																</tr>
																<?php if($order_details['lab_id'] > 0){ ?>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Lab'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
																</tr>
																<?php } ?>
															</table>
														</td>
													</tr>
												</table>
												<table width="100%"><tr><td height="20"></td></tr></table>
												<?php require_once(APPPATH."views/orders/pax_result_summary_food.php"); ?>
											</td>
										</tr>
									</table>
									<?php if($foodtotal == 0){ ?>
									<table width="100%"><tr><td height="20"></td></tr></table>
									<?php require_once(APPPATH."views/orders/pax_negative_faq_food.php"); ?>
									<?php } ?>
									<?php if($foodtotal > 0){ ?>
									<?php require_once(APPPATH."views/orders/pax_summary_recommendation_food.php"); ?>
									<?php } ?>
									<?php require_once(APPPATH."views/orders/pax_result_panel_food.php"); ?>
									<?php if($foodtotal > 0){ ?>
									<?php require_once(APPPATH."views/orders/pax_interpretation_support_food.php"); ?>
									<?php if($this->session->userdata('export_site_lang') == 'export_spanish'){ require_once(APPPATH."views/orders/pax_diet_chart_spanish.php"); }elseif($this->session->userdata('export_site_lang') == 'export_dutch'){ require_once(APPPATH."views/orders/pax_diet_chart_dutch.php"); }else{ require_once(APPPATH."views/orders/pax_diet_chart_".$this->session->userdata('site_lang').".php"); } ?>
									<?php } ?>
								</div>
							<?php }elseif(preg_match('/\bPAX Environmental Screening\b/', $respnedn->name)){ ?>
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
									<tr>
										<td style="padding: 5px;">
											<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;">
												<tr>
													<td valign="middle" width="530" style="padding:60px 30px 20px 0;">
														<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:130px;max-width:360px; border-radius:4px;" />
														<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;">
														<?php echo $this->lang->line('pax_env_scr'); ?></h5>
													</td>
													<td valign="middle">
														<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?>:</th>
																<td style="color:#000000;"><?php echo $speciesName;?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
																<td style="color:#000000;"><?php echo $fulladdress; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Email'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																<td style="color:#000000;"><?php echo $ordeType; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('lab_number'); ?></th>
																<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $account_ref; ?></td>
															</tr>
															<?php if($order_details['lab_id'] > 0){ ?>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Lab'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
															</tr>
															<?php } ?>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<?php if(empty($block1) && empty($blocks1)){ ?>
								<?php require_once(APPPATH."views/orders/pax_screening_negative.php"); ?>
								<?php }else{ ?>
								<?php require_once(APPPATH."views/orders/pax_screening_positive.php"); ?>
								<?php } ?>
							<?php }elseif(preg_match('/\bPAX Food Screening\b/', $respnedn->name)){ ?>
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
									<tr>
										<td style="padding: 5px;">
											<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;">
												<tr>
													<td valign="middle" width="530" style="padding:60px 30px 20px 0;">
														<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:130px;max-width:360px; border-radius:4px;" />
														<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('pax_food_scr'); ?></h5>
													</td>
													<td valign="middle">
														<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?>:</th>
																<td style="color:#000000;"><?php echo $speciesName;?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
																<td style="color:#000000;"><?php echo $fulladdress; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Email'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																<td style="color:#000000;"><?php echo $ordeType; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('lab_number'); ?></th>
																<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $account_ref; ?></td>
															</tr>
															<?php if($order_details['lab_id'] > 0){ ?>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Lab'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
															</tr>
															<?php } ?>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<?php if($foodtotal == 0){ ?>
								<?php require_once(APPPATH."views/orders/pax_screening_negative_food.php"); ?>
								<?php }else{ ?>
								<?php require_once(APPPATH."views/orders/pax_screening_positive_food.php"); ?>
								<?php } ?>
							<?php }elseif((preg_match('/\bPAX Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Screening\b/', $respnedn->name))){ ?>
								<div class="tab">
									<button class="tablinks active" id="tabenv" onclick="openEnvironmental()"><?php echo $this->lang->line('environmental_screening_result'); ?></button>
									<button class="tablinks" id="tabfood" onclick="openFood()"><?php echo $this->lang->line('food_screening_result'); ?></button>
								</div>
								<div id="tab-environmental" class="tabcontent" style="display:block;">
									<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
										<tr>
											<td style="padding: 5px;">
												<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;">
													<tr>
														<td valign="middle" width="530" style="padding:60px 30px 20px 0;">
															<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:130px;max-width:360px; border-radius:4px;" />
															<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('pax_env_scr'); ?></h5>
														</td>
														<td valign="middle">
															<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?>:</th>
																	<td style="color:#000000;"><?php echo $speciesName;?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
																	<td style="color:#000000;"><?php echo $fulladdress; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Email'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																	<td style="color:#000000;"><?php echo $ordeType; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																	<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('lab_number'); ?></th>
																	<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
																	<td style="color:#000000;"><?php echo $account_ref; ?></td>
																</tr>
																<?php if($order_details['lab_id'] > 0){ ?>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Lab'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
																</tr>
																<?php } ?>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<?php if(empty($block1) && empty($blocks1)){ ?>
									<?php require_once(APPPATH."views/orders/pax_screening_negative.php"); ?>
									<?php }else{ ?>
									<?php require_once(APPPATH."views/orders/pax_screening_positive.php"); ?>
									<?php } ?>
								</div>
								<div id="tab-food" class="tabcontent">
									<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
										<tr>
											<td style="padding: 5px;">
												<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;">
													<tr>
														<td valign="middle" width="530" style="padding:60px 30px 20px 0;">
															<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:130px;max-width:360px; border-radius:4px;" />
															<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('pax_food_scr'); ?></h5>
														</td>
														<td valign="middle">
															<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Species'); ?>:</th>
																	<td style="color:#000000;"><?php echo $speciesName;?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Address'); ?>:</th>
																	<td style="color:#000000;"><?php echo $fulladdress; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Email'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;vertical-align: baseline;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																	<td style="color:#000000;"><?php echo $ordeType; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																	<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('lab_number'); ?></th>
																	<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
																</tr>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
																	<td style="color:#000000;"><?php echo $account_ref; ?></td>
																</tr>
																<?php if($order_details['lab_id'] > 0){ ?>
																<tr>
																	<th style="color:#346a7e;"><?php echo $this->lang->line('Lab'); ?>:</th>
																	<td style="color:#000000;"><?php echo $order_details['lab_name']; ?></td>
																</tr>
																<?php } ?>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<?php if($foodtotal == 0){ ?>
									<?php require_once(APPPATH."views/orders/pax_screening_negative_food.php"); ?>
									<?php }else{ ?>
									<?php require_once(APPPATH."views/orders/pax_screening_positive_food.php"); ?>
									<?php } ?>
								</div>
							<?php } ?>
							<table width="100%"><tr><td height="30"></td></tr></table>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>
		</div>
		<?php $this->load->view("script"); ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
		<script>
		$(document).ready(function(){
			$(document).on('click', '.recommendation1', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/recommendation/'.$order_details['id'].'');?>");
				$("#treatment").val(1);
				$("form#recommendation1Form").submit();
			});

			$(document).on('click', '.recommendation2', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/recommendation/'.$order_details['id'].'');?>");
				$("#treatment").val(2);
				$("form#recommendation1Form").submit();
			});

			/*   START vetgoid AND petslit   */
			$(document).on('click', '.vetgoid', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/serum_treatment/'.$order_details['id'].'');?>");
				$("#treatment").val(1);
				$("#OrderRecommendationsBtns").val(1);
				$("form#recommendation1Form").submit();
			});

			$(document).on('click', '.petslit', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/serum_treatment/'.$order_details['id'].'');?>");
				$("#treatment").val(1);
				$("#OrderRecommendationsBtns").val(2);
				$("form#recommendation1Form").submit();
			});
			/*   END vetgoid AND petslit   */

			$(document).on('click', '.recommendations1', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/serum_treatment/'.$order_details['id'].'');?>");
				$("#treatment").val(1);
				$("form#recommendation1Form").submit();
			});
			
			$(document).on('click', '.recommendations2', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/serum_treatment/'.$order_details['id'].'');?>");
				$("#treatment").val(2);
				$("form#recommendation1Form").submit();
			});

			$(document).on('click', '#sendResult', function(){
				if(($('#CreateImage1').length) || ($('#CreateImage2').length) || ($('#CreateImage3').length)){
					if ($('#treatments1').length) {
						$('#treatments1').show();
					}
					if ($('#treatments2').length) {
						$('#treatments2').show();
					}
					if ($('#treatments3').length) {
						$('#treatments3').show();
					}
					$('.loader').show();
					$('.scroll').css("overflow","unset");
					if ($('.table3').length) {
						$('.table3').show();
					}
					$('.scroll').animate({
						scrollTop: $(".optiontbl").offset().top},
					'slow');
					setTimeout(function () {
						myGreeting();
					}, 2000);
					
				}else{
					window.location= '<?php echo site_url('orders/sendPaxResultNotification/'.$id.''); ?>';
				}
			});

			function myGreeting(){
				if ($('#CreateImage1').length) {
					html2canvas($("#CreateImage1"), {
						onrendered: function(canvas) {
							var imgsrc = canvas.toDataURL("image/png");
							$("#newimg").attr('src', imgsrc);
							var dataURL = canvas.toDataURL();
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('upload_pdf_image'); ?>",
								data: {imgBase64: dataURL, order_id: "<?php echo $id; ?>"},
								success: function(data) {
									$('#treatments1').hide();
								},
							}).done(function(o) {
								
							});
						}
					});
				}

				if ($('#CreateImage2').length) {
					html2canvas($("#CreateImage2"), {
						onrendered: function(canvas) {
							var imgsrc = canvas.toDataURL("image/png");
							$("#newimg").attr('src', imgsrc);
							var dataURL = canvas.toDataURL();
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('upload_pdf_image2'); ?>",
								data: {imgBase64: dataURL, order_id: "<?php echo $id; ?>"},
								success: function(data) {
									$('#treatments2').hide();
								},
							}).done(function(o) {
							});
						}
					});
				}

				if ($('#CreateImage3').length) {
					html2canvas($("#CreateImage3"), {
						onrendered: function(canvas) {
							var imgsrc = canvas.toDataURL("image/png");
							$("#newimg").attr('src', imgsrc);
							var dataURL = canvas.toDataURL();
							$.ajax({
								type: "POST",
								url: "<?php echo site_url('upload_pdf_image3'); ?>",
								data: {imgBase64: dataURL, order_id: "<?php echo $id; ?>"},
								success: function(data) {
									$('.loader').hide();
									$('.table3').hide();
									$('#treatments3').hide();
									$('.scroll').css("overflow","scroll");
									window.location= '<?php echo site_url('orders/sendPaxResultNotification/'.$id.''); ?>';
								},
							}).done(function(o) {
								//window.location= '<?php echo site_url('orders/sendPaxResultNotification/'.$id.''); ?>';
							});
						}
					});
				}
            }
		});

		function openEnvironmental(){
			$("#tab-food").hide();
			$("#tabfood").removeClass(" active");
			$("#tabenv").addClass(" active");
			$("#tab-environmental").show();
		}

		function openFood(){
			$("#tab-environmental").hide();
			$("#tabenv").removeClass(" active");
			$("#tabfood").addClass(" active");
			$("#tab-food").show();
		}
		</script>
		<?php if($ordeType == 'PAX Environmental + Food' || $ordeType == 'PAX Environmental + Food Screening'){ ?>
		<script>
		$(".scroll").on('scroll', function() {
			myFunction();
		});
		function myFunction() {
			if ($(".scroll").scrollTop() > 200) {
				$(".tab").addClass(" sticky");
			}else{
				$(".tab").removeClass(" sticky");
			}
		}
		</script>
		<?php } ?>
	</body>
</html>
