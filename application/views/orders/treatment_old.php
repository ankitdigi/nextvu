<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();

$serumdata = $this->OrdersModel->getSerumTestRecord($id);
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
				if($row1->result >= 60){
					$optn2Arr[] = $algName->id;
					$block1++;
				}
				$moduleArr[] = $optionenv;
			}else{
				if($row1->result >= 5){
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
		if($malasseziaResults->result >= 60){
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

$option1 = [];
foreach($optnenvArr as $row3){
	if($row3['result'] >= 5 && $this->AllergensModel->checkforArtuveterinallergen($row3['algid']) > 0){
		$option1[$row3['algid']] = $row3['name'];
	}
}
foreach($moduleArr as $row4){
	if($row4['result'] >= 60 && $this->AllergensModel->checkforArtuveterinallergen($row4['algid']) > 0){
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
		if($malasseziaResults->result >= 60 && $this->AllergensModel->checkforArtuveterinallergen(81) > 0){
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
				if($serumfResults->result >= 5){
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
				if($serumfiggResults->result >= 5){
					$foodpos++;
				}
			}
		}
	}
}
?>
			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
			<style>
			@font-face{font-family:'Mark Pro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro.woff"); ?>') format("woff");font-weight:400;font-style:normal;font-display:swap}
			@font-face{font-family:'MarkPro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro-Medium.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro-Medium.woff"); ?>') format("woff");font-weight:500;font-style:normal;font-display:swap}
			@font-face{font-family:'Mark Pro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro-Bold.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro-Bold.woff"); ?>') format("woff");font-weight:700;font-style:normal;font-display:swap}
			body{font-family:'Mark Pro'}
			*{margin:0;padding:0;box-sizing:border-box;font-family:'Mark Pro'}
			.header th{text-align:left}
			.bargraph{list-style:none;width:100%;position:relative;margin:0;padding:0}
			.bargraph li{position:relative;height:21px;margin-bottom:6px;transition:width 2s;-webkit-transition:width 2s;background:#abd084}
			.bargraph li.grey{background:#ccc}
			.bargraph li.red{background:red}
			.bargraph li span{display:block}
			.foodbargraph{list-style:none;width:100%;position:relative;margin:0;padding:0}
			.foodbargraph li{position:relative;height:22.1px;margin-bottom:5px;transition:width 2s;-webkit-transition:width 2s;background:#abd084}
			.foodbargraph li.grey{background:#ccc}
			.foodbargraph li.red{background:red}
			.foodbargraph li span{display:block}
			table.diets th{text-align: left; font-weight: 400;} th{text-align: left; font-weight: 400;} th{text-align: left; font-weight: 400;}
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
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line('page_title'); ?>
						<small><?php echo $this->lang->line('treatment_advice'); ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="<?php echo site_url('orders'); ?>"> <?php echo $this->lang->line('Orders_Management'); ?></a></li>
						<li class="active"><?php echo $this->lang->line('treatment_advice'); ?></li>
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
						<div class="col-xs-5">
							<?php echo form_open('', array('name'=>'treatmentForm', 'id'=>'treatmentForm')); ?>
								<div class="box box-primary">
									<div class="box-header with-border">
										<p class="pull-left">
											<a href="<?php echo site_url('orders'); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i>Back</a>
										</p>
									</div>
									<?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?>
										<div class="box-body" style="max-height: 680px;min-height: 680px;height: 100%;">
											<div class="form-group">
												<label><?php echo $this->lang->line('internal_practice_comment'); ?></label>
												<textarea class="form-control internal_practice_comment" name="internal_practice_comment" rows="5" placeholder="<?php echo $this->lang->line('enter_internal_practice_comment'); ?>"><?php echo !empty($order_details['internal_practice_comment'])?$order_details['internal_practice_comment']:''; ?></textarea>
											</div>
											<div class="form-group">
												<button type="submit" value="next" name="next" class="btn btn-primary mrgnbtm10 next_btn"><?php echo $this->lang->line('internal_comments_message'); ?></button>
												<?php if (isset($order_details['requisition_form']) && $order_details['requisition_form'] != '') { ?>
												<a class="btn btn-primary mrgnbtm10" onclick="window.open('<?php echo base_url() . REQUISITION_FORM_PATH; ?>/<?php echo $order_details['requisition_form']; ?>','Requisition Form','width=1200,height=9000')" title="View Order Requisition">  <?php echo $this->lang->line('view_uploaded_order_requisition_form'); ?></a>
												<?php } ?>
											</div>
											<div class="form-group">
												<?php if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994' || $order_details['lab_id']=='13788')){ ?>
												<a target="_blank" href="<?php echo site_url('orders/getOLDSerumResultdoc/'.$order_details['id'].''); ?>" class="btn btn-primary" style="padding:5px 10px;margin-bottom: 10px;"> <?php echo $this->lang->line('download_word_document'); ?> </a><br/>
												<?php }else{ ?>
												<button id="downloadonly" type="button" class="btn btn-primary top"><?php echo $this->lang->line('download_5'); ?></button>
												<?php } ?>
											</div>
											<?php if((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))){ ?>
												<div class="form-group">
													<h2 style="margin:0px;font-weight:700;font-size:28px;color:#366784;"><?php echo $this->lang->line('order_recommendations'); ?></h2><hr style="margin: 10px 0px;">
													<?php if(!empty($optn2Arr)){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation1" name="recommendation1" value="Order IM recommendation 1">
													<?php } ?>
													<?php if(!empty($block2) && $option1 != $block2){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation2" name="recommendation2" value="Order IM recommendation 2">
													<?php } ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation3" name="recommendation3" value="Compose and order own IM">
												</div>
											<?php } ?>
										</div>
									<?php }else{ ?>
										<div class="box-body" style="max-height: 680px;min-height: 680px;height: 100%;">
											<div class="form-group">
												<label><?php echo $this->lang->line('laboratory_comment'); ?></label>
												<textarea class="form-control internal_comment" name="internal_comment" rows="5" placeholder="<?php echo $this->lang->line('enter_laboratory_comment'); ?>"><?php echo $order_details['internal_comment']; ?></textarea>
											</div>
											<div class="form-group">
												<button type="submit" value="next" name="next" class="btn btn-primary mrgnbtm10 next_btn" <?php if($order_details['is_serum_result_sent'] == 1) { echo 'disabled="disabled"'; } ?>><?php echo $this->lang->line('save_labo_comment'); ?></button>
												<?php if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994' || $order_details['lab_id']=='13788')){ ?>
												<a target="_blank" href="<?php echo site_url('orders/getOLDSerumResultdoc/'.$order_details['id'].''); ?>" class="btn btn-primary"> <?php echo $this->lang->line('download_word_document'); ?> </a>
												<?php }else{ ?>
												<button id="downloadonly" type="button" class="btn btn-primary top"><?php echo $this->lang->line('download_result'); ?></button>
												<?php } ?>
												<?php
												$zonesIds = $this->OrdersModel->checkZones($id);
												if(!empty($zonesIds) && in_array("8", $zonesIds)){
												?>
												<a target="_blank" href="<?php echo site_url('orders/getOLDSerumResultExcel/'.$order_details['id'].''); ?>" class="btn btn-primary"> <?php echo $this->lang->line('down_ecxel_doc'); ?></a>
												<?php } ?>
												<?php if (isset($order_details['requisition_form']) && $order_details['requisition_form'] != '') { ?>
												<a class="btn btn-primary mrgnbtm10" onclick="window.open('<?php echo base_url() . REQUISITION_FORM_PATH; ?>/<?php echo $order_details['requisition_form']; ?>','Requisition Form','width=1200,height=9000')" title="View Order Requisition">  <?php echo $this->lang->line('view_uploaded_order_requisition_form'); ?></a>
												<?php } ?>
												<br><br>
												<?php if((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))){ ?>
													<?php if($block1 > 0 && $order_details['is_serum_result_sent'] != 1){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation1Admin" name="recommendation1" value="Edit Immunotherapy Treatment Opt 1">
													<?php } ?>
													<?php if(!empty($block2) && ($option1 != $block2) && $order_details['is_serum_result_sent'] != 1 && $order_details['remove_treatment_2'] == 0){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation2Admin" name="recommendation2" value="Edit Immunotherapy Treatment Opt 2">
													<?php } ?>
												<?php } ?>
											</div>
											<div class="form-group">
												<label><?php echo $this->lang->line('annotated_by'); ?></label>
												<input type="text" class="form-control" name="annotated_by" placeholder="Enter Your Name" value="<?php echo !empty($userData['name'])?$userData['name']:''; ?>">
											</div>
											<div class="form-group">
												<label><?php echo $this->lang->line('price'); ?></label>
												<input type="text" class="form-control" name="price" value="<?php echo $order_details['unit_price'];?>" required="">
											</div>
											<div class="form-group">
												<button id="geeks" type="button" class="btn btn-primary top"><?php echo $this->lang->line('save_send_out_close'); ?></button>
											</div>
											<?php if(((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))) && ($this->user_role == '1' || $this->user_role == '11')){ ?>
												<div class="form-group">
													<h2 style="margin:0px;font-weight:700;font-size:28px;color:#366784;"><?php echo $this->lang->line('order_recommendations'); ?></h2><hr style="margin: 10px 0px;">
													<?php if(!empty($optn2Arr)){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendations1Admin" name="recommendations1Admin" value="Order IM recommendation 1">
													<?php } ?>
													<?php if(!empty($block2) && $option1 != $block2){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendations2Admin" name="recommendations2Admin" value="Order IM recommendation 2">
													<?php } ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							<?php echo form_close(); ?>
						</div>
						<div class="col-xs-7 scroll" style="height:950px;overflow:scroll;padding:0px;">
							<div id="CreateImageHead">
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
									<tr>
										<td style="padding: 5px;">
											<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
												<tbody>
													<tr>
														<td>
															<h4 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('medical_history'); ?></h4>
														</td>
													</tr>
												</tbody>
											</table>
											<table width="100%"><tr><td height="20"></td></tr></table>

											<table width="100%">
												<tbody>
													<tr>
														<td style="padding:0 5px;">
															<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="100%">
																<tbody>
																	<?php if(isset($serumdata['serum_drawn_date'])){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('date_serum_drawn'); ?>:</th>
																		<td style="color:#000000;text-align: left;"><?php echo date("d/m/Y",strtotime($serumdata['serum_drawn_date']));?></td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['major_symptoms']) && ((strpos($serumdata['major_symptoms'], '0' ) !== false) || (strpos($serumdata['major_symptoms'], '1' ) !== false) || (strpos($serumdata['major_symptoms'], '2' ) !== false) || (strpos($serumdata['major_symptoms'], '3' ) !== false) || (strpos($serumdata['major_symptoms'], '4' ) !== false) || (strpos($serumdata['major_symptoms'], '5' ) !== false) || (strpos($serumdata['major_symptoms'], '6' ) !== false) || (strpos($serumdata['major_symptoms'], '7' ) !== false))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('patient_affected'); ?>:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if(strpos($serumdata['major_symptoms'], '1' ) !== false){ ?>
																				<?php echo $this->lang->line('pruritus_itch'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '2' ) !== false){ ?>
																				<?php echo $this->lang->line('otitis'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '3' ) !== false){ ?>
																				<?php echo $this->lang->line('respiratory_signs'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '4' ) !== false){ ?>
																				<?php echo $this->lang->line('gastro_intestinal_signs'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '5' ) !== false){ ?>
																				<?php echo $this->lang->line('skin_lesions'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '6' ) !== false){ ?>
																				<?php echo $this->lang->line('ocular_signs'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '7' ) !== false){ ?>
																				<?php echo $this->lang->line('anaphylaxis'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '0' ) !== false){ ?>
																			<?php echo isset($serumdata['other_symptom']) ? $serumdata['other_symptom'] : ''; ?>
																			<?php } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if($serumdata['symptom_appear_age_month'] != '' || $serumdata['symptom_appear_age'] != ''){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('at_what_age_did_these_symptoms_first_appear'); ?>:</th>
																		<td style="color:#000000;text-align: left;"><?php echo $serumdata['symptom_appear_age_month'].'/'.$serumdata['symptom_appear_age']; ?></td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['when_obvious_symptoms']) && ((strpos( $serumdata['when_obvious_symptoms'], '1' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '2' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '3' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '4' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '5' ) !== false))){ ?> 
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('symptoms_most_obvious'); ?>:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '1' ) !== false){ ?>
																				<?php echo $this->lang->line('spring'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '2' ) !== false){ ?>
																				<?php echo $this->lang->line('summer'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '3' ) !== false){ ?>
																				<?php echo $this->lang->line('fall'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '4' ) !== false){ ?>
																				<?php echo $this->lang->line('winter'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '5' ) !== false){ ?>
																				<?php echo $this->lang->line('year_round'); ?>
																			<?php } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['where_obvious_symptoms']) && ((strpos( $serumdata['where_obvious_symptoms'], '1' ) !== false) || (strpos( $serumdata['where_obvious_symptoms'], '2' ) !== false) || (strpos( $serumdata['where_obvious_symptoms'], '3' ) !== false))){ ?> 
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('where_symptoms_most_obvious'); ?>:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if(strpos($serumdata['where_obvious_symptoms'], '1' ) !== false){ ?>
																				<?php echo $this->lang->line('indoors'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['where_obvious_symptoms'], '2' ) !== false){ ?>
																				<?php echo $this->lang->line('outdoors'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['where_obvious_symptoms'], '3' ) !== false){ ?>
																				<?php echo $this->lang->line('no_difference'); ?>
																			<?php } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['diagnosis_food']) && ($serumdata['diagnosis_food'] == 1 || $serumdata['diagnosis_food'] == 2)) || (isset($serumdata['diagnosis_hymenoptera']) && ($serumdata['diagnosis_hymenoptera'] == 1 || $serumdata['diagnosis_hymenoptera'] == 2)) || (isset($serumdata['diagnosis_other']) && ($serumdata['diagnosis_other'] == 1 || $serumdata['diagnosis_other'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('clinical_diagnosis'); ?></th>
																		<td style="color:#000000;text-align: left;"></td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['diagnosis_food']) && ($serumdata['diagnosis_food'] == 1 || $serumdata['diagnosis_food'] == 2))){ ?>
																		<tr>
																			<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('food'); ?>:</th>
																			<td style="color:#000000;text-align: left;">
																				<?php 
																				if(isset($serumdata['diagnosis_food']) && $serumdata['diagnosis_food'] == 1){ 
																					if(isset($serumdata['other_diagnosis_food']) && $serumdata['other_diagnosis_food'] != ""){ 
																						echo 'Yes, '.$serumdata['other_diagnosis_food'];
																					}else{
																						echo 'Yes';
																					}
																				} ?>
																				<?php if(isset($serumdata['diagnosis_food']) && $serumdata['diagnosis_food'] == 2){ echo 'No'; } ?>
																			</td>
																		</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['diagnosis_food']) && $serumdata['diagnosis_food'] == 1) && (isset($serumdata['food_challenge']) && ((strpos( $serumdata['food_challenge'], '1' ) !== false) || (strpos( $serumdata['food_challenge'], '2' ) !== false) || (strpos( $serumdata['food_challenge'], '3' ) !== false) || (strpos( $serumdata['food_challenge'], '4' ) !== false) || (strpos( $serumdata['food_challenge'], '5' ) !== false)))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('food_challenge'); ?>:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '1' ) !== false)){ echo '&lt; 3 hours'; } ?>  
																			<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '2' ) !== false)){ echo '3-12 hours'; } ?>  
																			<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '3' ) !== false)){ echo '12-24 hours'; } ?>  
																			<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '4' ) !== false)){ echo '24-48 h'; } ?>  
																			<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '5' ) !== false)){ echo '&gt; 48 h'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['diagnosis_hymenoptera']) && ($serumdata['diagnosis_hymenoptera'] == 1 || $serumdata['diagnosis_hymenoptera'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('hymenoptera_stings'); ?>:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php 
																			if(isset($serumdata['diagnosis_hymenoptera']) && $serumdata['diagnosis_hymenoptera'] == 1){ 
																				if(isset($serumdata['other_diagnosis_hymenoptera']) && $serumdata['other_diagnosis_hymenoptera'] != ""){ 
																					echo 'Yes, '.$serumdata['other_diagnosis_hymenoptera'];
																				}else{
																					echo 'Yes';
																				}
																			} ?>
																			<?php if(isset($serumdata['diagnosis_hymenoptera']) && $serumdata['diagnosis_hymenoptera'] == 2){ echo 'No'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['diagnosis_other']) && ($serumdata['diagnosis_other'] == 1 || $serumdata['diagnosis_other'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('other_s'); ?>:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php 
																			if(isset($serumdata['diagnosis_other']) && $serumdata['diagnosis_other'] == 1){ 
																				if(isset($serumdata['other_diagnosis']) && $serumdata['other_diagnosis'] != ""){ 
																					echo 'Yes, '.$serumdata['other_diagnosis'];
																				}else{
																					echo 'Yes';
																				}
																			} ?>
																			<?php if(isset($serumdata['diagnosis_other']) && $serumdata['diagnosis_other'] == 2){ echo 'No'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['regularly_exposed']) && ((strpos($serumdata['regularly_exposed'], '1') !== false) || (strpos($serumdata['regularly_exposed'], '2') !== false) || (strpos($serumdata['regularly_exposed'], '3') !== false) || (strpos($serumdata['regularly_exposed'], '4') !== false) || (strpos($serumdata['regularly_exposed'], '5') !== false) || (strpos($serumdata['regularly_exposed'], '6') !== false) || (strpos($serumdata['regularly_exposed'], '7') !== false) || (strpos($serumdata['regularly_exposed'], '0') !== false))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('exposed_following_animals'); ?>:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if(strpos($serumdata['regularly_exposed'], '1' ) !== false){ ?>
																				<?php echo $this->lang->line('cats'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '2' ) !== false){ ?>
																				<?php echo $this->lang->line('dogs'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '3' ) !== false){ ?>
																				<?php echo $this->lang->line('horses'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '4' ) !== false){ ?>
																				<?php echo $this->lang->line('cattle'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '5' ) !== false){ ?>
																				<?php echo $this->lang->line('mice'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '6' ) !== false){ ?>
																				<?php echo $this->lang->line('guinea_pigs'); ?>, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '7' ) !== false){ ?>
																				<?php echo $this->lang->line('rabbits'); ?>, 
																			<?php } ?>
																			<?php if($serumdata['other_exposed'] != ""){ echo $serumdata['other_exposed']; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['malassezia_infections']) && ((strpos( $serumdata['malassezia_infections'], '1' ) !== false) || (strpos( $serumdata['malassezia_infections'], '2' ) !== false))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('malassezia_infections'); ?></th>
																		<td style="color:#000000;text-align: left;">
																			<?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '1' ) !== false) ){ echo 'Malassezia otitis, '; } ?>
																			<?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '2' ) !== false) ){ echo 'Malassezia dermatitis'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['receiving_drugs']) && ((strpos($serumdata['receiving_drugs'], '1') !== false) || (strpos($serumdata['receiving_drugs'], '2') !== false) || (strpos($serumdata['receiving_drugs'], '3') !== false) || (strpos($serumdata['receiving_drugs'], '4') !== false) || (strpos($serumdata['receiving_drugs'], '5') !== false) || (strpos($serumdata['receiving_drugs'], '6') !== false))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('receiving_drugs'); ?></th>
																		<td>
																			<?php 
																			if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '1' ) !== false) ){
																				if(isset($serumdata['receiving_drugs_1']) && $serumdata['receiving_drugs_1'] == 1){ 
																					echo 'Glucocorticoids (oral, topical, injectable) - No response'; 
																				}elseif(isset($serumdata['receiving_drugs_1']) && $serumdata['receiving_drugs_1'] == 2){ 
																					echo 'Glucocorticoids (oral, topical, injectable) - Fair response';
																				}elseif(isset($serumdata['receiving_drugs_1']) && $serumdata['receiving_drugs_1'] == 3){ 
																					echo 'Glucocorticoids (oral, topical, injectable) - Good to excellent response';
																				}else{
																					echo 'Glucocorticoids (oral, topical, injectable)';
																				}
																				echo ', ';
																			}

																			if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '2' ) !== false) ){
																				if(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_2'] == 1){ 
																					echo 'Ciclosporin - No response'; 
																				}elseif(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_2'] == 2){ 
																					echo 'Ciclosporin - Fair response';
																				}elseif(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_2'] == 3){ 
																					echo 'Ciclosporin - Good to excellent response';
																				}else{
																					echo 'Ciclosporin';
																				}
																				echo ', ';
																			}

																			if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '3' ) !== false) ){
																				if(isset($serumdata['receiving_drugs_3']) && $serumdata['receiving_drugs_3'] == 1){ 
																					echo 'Oclacitinib (Apoquel) - No response'; 
																				}elseif(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_3'] == 2){ 
																					echo 'Oclacitinib (Apoquel) - Fair response';
																				}elseif(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_3'] == 3){ 
																					echo 'Oclacitinib (Apoquel) - Good to excellent response';
																				}else{
																					echo 'Oclacitinib (Apoquel)';
																				}
																				echo ', ';
																			}

																			if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '4' ) !== false) ){
																				if(isset($serumdata['receiving_drugs_4']) && $serumdata['receiving_drugs_4'] == 1){ 
																					echo 'Lokivetmab (Cytopoint) - No response'; 
																				}elseif(isset($serumdata['receiving_drugs_4']) && $serumdata['receiving_drugs_4'] == 2){ 
																					echo 'Lokivetmab (Cytopoint) - Fair response';
																				}elseif(isset($serumdata['receiving_drugs_4']) && $serumdata['receiving_drugs_4'] == 3){ 
																					echo 'Lokivetmab (Cytopoint) - Good to excellent response';
																				}else{
																					echo 'Lokivetmab (Cytopoint)';
																				}
																				echo ', ';
																			}

																			if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '5' ) !== false) ){
																				if(isset($serumdata['receiving_drugs_5']) && $serumdata['receiving_drugs_5'] == 1){ 
																					echo 'Antibiotics - No response'; 
																				}elseif(isset($serumdata['receiving_drugs_5']) && $serumdata['receiving_drugs_5'] == 2){ 
																					echo 'Antibiotics - Fair response';
																				}elseif(isset($serumdata['receiving_drugs_5']) && $serumdata['receiving_drugs_5'] == 3){ 
																					echo 'Antibiotics - Good to excellent response';
																				}else{
																					echo 'Antibiotics';
																				}
																				echo ', ';
																			}

																			if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '6' ) !== false) ){
																				if(isset($serumdata['receiving_drugs_6']) && $serumdata['receiving_drugs_6'] == 1){ 
																					echo 'Antifungals - No response'; 
																				}elseif(isset($serumdata['receiving_drugs_6']) && $serumdata['receiving_drugs_6'] == 2){ 
																					echo 'Antifungals - Fair response';
																				}elseif(isset($serumdata['receiving_drugs_6']) && $serumdata['receiving_drugs_6'] == 3){ 
																					echo 'Antifungals - Good to excellent response';
																				}else{
																					echo 'Antifungals';
																				}
																				echo ', ';
																			}
																			?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['treatment_ectoparasites']) && ($serumdata['treatment_ectoparasites'] == 1 || $serumdata['treatment_ectoparasites'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('treatment_ectoparasites'); ?></th>
																		<td style="color:#000000;text-align: left;">
																			<?php 
																			if(isset($serumdata['treatment_ectoparasites']) && $serumdata['treatment_ectoparasites'] == 1){ 
																				if(isset($serumdata['other_ectoparasites']) && $serumdata['other_ectoparasites'] != ""){ 
																					echo 'Yes, '.$serumdata['other_ectoparasites'];
																				}else{
																					echo 'Yes';
																				}
																			} ?>
																			<?php if(isset($serumdata['treatment_ectoparasites']) && $serumdata['treatment_ectoparasites'] == 2){ echo 'No'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['elimination_diet']) && ($serumdata['elimination_diet'] == 1 || $serumdata['elimination_diet'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('elimination_diet'); ?></th>
																		<td style="color:#000000;text-align: left;">
																			<?php 
																			if(isset($serumdata['elimination_diet']) && $serumdata['elimination_diet'] == 1){ 
																				if(isset($serumdata['other_elimination']) && $serumdata['other_elimination'] != ""){ 
																					echo 'Yes, '.$serumdata['other_elimination'];
																				}else{
																					echo 'Yes';
																				}
																			} ?>
																			<?php if(isset($serumdata['elimination_diet']) && $serumdata['elimination_diet'] == 2){ echo 'No'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['additional_information']) && $serumdata['additional_information'] != "")){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('additional_information'); ?></th>
																		<td style="color:#000000;text-align: left;">
																			<?php echo $serumdata['additional_information']; ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['zoonotic_disease']) && ($serumdata['zoonotic_disease'] == 1 || $serumdata['zoonotic_disease'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('zoonotic_disease'); ?></th>
																		<td style="color:#000000;text-align: left;">
																			<?php 
																			if(isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease'] == 1){ 
																				if(isset($serumdata['zoonotic_disease_dec']) && $serumdata['zoonotic_disease_dec'] != ""){ 
																					echo 'Yes, '.$serumdata['zoonotic_disease_dec'];
																				}else{
																					echo 'Yes';
																				}
																			} ?>
																			<?php if(isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease'] == 2){ echo 'No'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['medication']) && ($serumdata['medication'] == 1 || $serumdata['medication'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;"><?php echo $this->lang->line('medication'); ?></th>
																		<td style="color:#000000;text-align: left;">
																			<?php 
																			if(isset($serumdata['medication']) && $serumdata['medication'] == 1){ 
																				if(isset($serumdata['medication_desc']) && $serumdata['medication_desc'] != ""){ 
																					echo 'Yes, '.$serumdata['medication_desc'];
																				}else{
																					echo 'Yes';
																				}
																			} ?>
																			<?php if(isset($serumdata['medication']) && $serumdata['medication'] == 2){ echo 'No'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<table width="100%"><tr><td height="20"></td></tr></table>
										</td>
									</tr>
								</table>
							</div>
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
													<td valign="middle" width="350">
														<img src="<?php echo base_url("/assets/images/nextmune-uk.png"); ?>" height="60" alt="" />
													</td>
													<td valign="top">
														<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;padding-top:10px">
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
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
																<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('laboratory_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																<td style="color:#000000;"><?php echo !empty($order_details['reference_number'])?$order_details['reference_number']:$order_details['order_number']; ?></td>
															</tr>
														</table>
													</td>
													<td style="line-height:0;" align="right">
														<img src="<?php echo base_url("/assets/images/header-cat.png"); ?>" height="130" alt="" />
													</td>
												</tr>
											</table>
											<table class="green_strip" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
												<tr>
													<td><?php echo $this->lang->line('acid_glycoprotein'); ?></td>
												</tr>
											</table>
											<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
												<tr>
													<td colspan="3" height="20"></td>
												</tr>
												<tr>
													<th align="left"><?php echo $this->lang->line('acute_phase_protein'); ?></th>
													<th style="text-align:center"><?php echo $this->lang->line('concentration'); ?></th>
													<th style="text-align:center"><?php echo $this->lang->line('normal_values'); ?>*</th>
												</tr>
												<tr>
													<td><?php echo $this->lang->line('alpha_1'); ?></td>
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
													<td style="text-align:center"><?php echo $this->lang->line('0_1_to_0_5_gL'); ?></td>
												</tr>
												<tr>
													<td colspan="3" height="20"></td>
												</tr>
											</table>
											<table class="green_bordered" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
												<tr>
													<td>
														<p style="margin:0 0 10px 0; font-size:13px;"><?php echo $this->lang->line('interpreting_app_results'); ?></p>
														<p style="margin:0 0 10px 0; font-size:13px;">&nbsp;</p>
														<p style="margin:0; font-size:13px;">* <?php echo $this->lang->line('acute_phase_protein_concentrations'); ?></p>
														<p style="margin:0 0 10px 0; font-size:13px;">&nbsp;</p>
														<p style="margin:0; font-size:13px;"><?php echo $this->lang->line('please_note'); ?></p>
													</td>
												</tr>
											</table>
											<table width="100%"><tr><td height="20"></td></tr></table>
										</td>
									</tr>
								</table>
								<table width="100%"><tr><td height="20"></td></tr></table>
							<?php }else{ ?>
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="1030px" style="width:100%;background:#ffffff;">
									<tr>
										<td>
											<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url("/assets/images/next-header.jpg"); ?>) left center no-repeat; background-size:cover;">
												<tr>
													<td valign="middle" width="50%" style="padding:60px 30px 60px 50px;">
														<img src="<?php echo base_url("/assets/images/nextlab-logo.jpg"); ?>" alt="NEXT+ Logo" style="max-height:100px; max-width:280px; border-radius:4px;" />
														<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;"><?php echo $this->lang->line('serum_test_results_2'); ?></h5>
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
																<td style="color:#000000;"><?php echo $order_details['species_name'];?></td>
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
																<th style="color:#346a7e;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('email'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Test_type'); ?>:</th>
																<td style="color:#000000;"><?php echo $ordeType; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
																<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('laboratory_number'); ?>:</th>
																<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;"><?php echo $this->lang->line('order_number'); ?>:</th>
																<td style="color:#000000;"><?php echo !empty($order_details['reference_number'])?$order_details['reference_number']:$order_details['order_number']; ?></td>
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
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
														<tbody>
															<tr>
																<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;"><?php echo $this->lang->line('screen_penal'); ?></h6></td>
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
																	if($ivalue['parent_id'] == '6'){
																		if($serumResults->result >= 76){
																			$counteriP++;
																		}elseif($serumResults->result <= 75 && $serumResults->result >= 60){
																			$counteriB++;
																		}else{
																			$counteriN++;
																		}
																	}else{
																		if($serumResults->result >= 10){
																			$counteriP++;
																		}elseif($serumResults->result >= 5 && $serumResults->result < 10){
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
																		if($serumiResults->result >= 10){
																			$counteriP++;
																		}elseif($serumiResults->result >= 5 && $serumiResults->result < 10){
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

															if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994') || ($order_details['lab_id']=='13788' && $order_details['species_name'] != 'Horse')){
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
																		if($malasseziaResults->result >= 76){
																			echo '<td>POSITIVE</td>';
																		}elseif($malasseziaResults->result <= 75 && $malasseziaResults->result >= 60){
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
													<div id="CreateImage3">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																			<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;"><?php echo $this->lang->line('ige_igg'); ?></th>
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
																				if(!empty($algName) && $algName->name !='N/A'){
																					$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
																					$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
																					echo '<tr bgcolor="#d0ebef">
																						<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $algName->name .'</td>
																						<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
																						<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIge->result.'</td>
																					</tr>
																					<tr bgcolor="#d0ebef">
																						<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;"></td>
																						<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgG</td>
																						<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIgg->result.'</td>
																					</tr>';
																				}
																			}
																		}
																		?>
																		<tr>
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_0'); ?></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="foodbargraph" style="margin-top:10px;">
																								<?php
																								$serumResultsgfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
																								if(!empty($serumResultsgfod)){
																									$rsultFIge = $rsultFIgg = []; $resultperIge = $resultperIgg = 0;
																									foreach($serumResultsgfod as $row1){
																										$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
																										if(!empty($algName) && $algName->name !='N/A'){
																											$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIge)){
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
																												if($rsultFIge->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 5){
																													echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																													echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 10){
																													echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
																											}

																											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIgg)){
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
																												if($rsultFIgg->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 5){
																													echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																													echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 10){
																													echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
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
													</div>
													<table width="100%"><tr><td height="20"></td></tr></table>
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; <?php echo $this->lang->line('5_0'); ?></strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment'); ?></p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong><?php echo $this->lang->line('5_10'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment_2'); ?></p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> <?php echo $this->lang->line('10_0'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('additional_borderline'); ?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
														<tr>
															<td>
																<p style="font-size:11px; line-height:16px; margin:0; padding:0;"><?php echo $this->lang->line('arbitrary_units'); ?></p>
															</td>
														</tr>
													</table>
												<?php
												}elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){
												?>
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
														<tbody>
															<tr>
																<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;"><?php echo $this->lang->line('screen_penal'); ?></h6></td>
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
																	if($ivalue['parent_id'] == '6'){
																		if($serumResults->result >= 76){
																			$counteriP++;
																		}elseif($serumResults->result <= 75 && $serumResults->result >= 60){
																			$counteriB++;
																		}else{
																			$counteriN++;
																		}
																	}else{
																		if($serumResults->result >= 10){
																			$counteriP++;
																		}elseif($serumResults->result >= 5 && $serumResults->result < 10){
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
																		if($serumiResults->result >= 10){
																			$counteriP++;
																		}elseif($serumiResults->result >= 5 && $serumiResults->result < 10){
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

															if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994') || ($order_details['lab_id']=='13788' && $order_details['species_name'] != 'Horse')){
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
																		if($malasseziaResults->result >= 76){
																			echo '<td>POSITIVE</td>';
																		}elseif($malasseziaResults->result <= 75 && $malasseziaResults->result >= 60){
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
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
														<tbody>
															<tr>
																<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;"><?php echo $this->lang->line('screen_food_panel'); ?></h6></td>
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
												}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (!preg_match('/\bFood Panel\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name))){
													if(!empty($optnenvArr)){
													?>
														<div id="CreateImage">
															<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
																<tr>
																	<td>
																		<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																			<tr>
																				<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																				<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;"><?php echo $this->lang->line('ea_units'); ?>*</th>
																			</tr>
																			<tr bgcolor="#eaf6f7">
																				<td align="left" style="padding:5px 10px 5px 15px;"></td>
																				<td align="right" style="padding:5px 15px 5px 10px;"></td>
																			</tr>
																			<?php
																			foreach($optnenvArr as $row1){
																				echo '<tr bgcolor="#d0ebef">
																					<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $row1['name'] .'</td>
																					<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
																				</tr>';
																			}
																			?>
																			<tr>
																				<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																			</tr>
																		</table>
																		<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																			<tr>
																				<td>
																					<table cellpadding="0" cellspacing="0" width="100%" border="0">
																						<tr>
																							<th height="35" width="30%"></th>
																							<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_10'); ?></th>
																							<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																							<th></th>
																						</tr>
																						<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																							<td>
																								<ul class="bargraph" style="margin-top:15px;">
																									<?php
																									$resultper = 0;
																									foreach($optnenvArr as $row2){
																										if($row2['result'] > 0 && $row2['result'] <= 5){
																											$resultper = 20*$row2['result'];
																										}elseif($row2['result'] == 6){
																											$resultper = 110;
																										}elseif($row2['result'] == 7){
																											$resultper = 120;
																										}elseif($row2['result'] == 8){
																											$resultper = 130;
																										}elseif($row2['result'] == 9){
																											$resultper = 140;
																										}elseif($row2['result'] == 10){
																											$resultper = 150;
																										}elseif($row2['result'] > 10){
																											$resultper = (14*$row2['result']);
																										}else{
																											$resultper = 0;
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
														</div>
														<table width="100%"><tr><td height="20"></td></tr></table>
													<?php } ?>
													<?php if(!empty($moduleArr)){ ?>
													<div id="CreateImage2">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('moulds'); ?></th>
																			<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;"><?php echo $this->lang->line('ea_units'); ?>*</th>
																		</tr>
																		<tr bgcolor="#eaf6f7">
																			<td align="left" style="padding:5px 10px 5px 15px;"></td>
																			<td align="right" style="padding:5px 15px 5px 10px;"></td>
																		</tr>
																		<?php
																		if(!empty($moduleArr)){
																			foreach($moduleArr as $row1){
																				echo '<tr bgcolor="#d0ebef">
																					<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $row1['name'] .'</td>
																					<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
																				</tr>';
																			}
																		}
																		?>
																		<tr>
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('60_0'); ?></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('75_0'); ?></th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="bargraph" style="margin-top:15px;">
																								<?php
																								if(!empty($moduleArr)){
																									$resultper = 0;
																									foreach($moduleArr as $row2){
																										if($row2['result'] <= 59){
																											$resultper = $row2['result']+40;
																										}elseif($row2['result'] >= 60 && $row2['result'] <= 75){
																											$resultper = 2*$row2['result'];
																										}elseif($row2['result'] > 75){
																											$resultper = (2*$row2['result'])+10;
																										}
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] > 0 && $row2['result'] <= 59){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] >= 60 && $row2['result'] <= 75){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 75){
																											echo '<li class="red" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}else{
																											echo '<li class="" style="width:0%;" class=""><span></span></li>';
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
													</div>
													<?php } ?>
													<table width="100%"><tr><td height="20"></td></tr></table>
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; <?php echo $this->lang->line('5_0'); ?></strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment'); ?></p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong><?php echo $this->lang->line('5_10'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment_2'); ?></p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> <?php echo $this->lang->line('10_0'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('additional_borderline'); ?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
														<tr>
															<td>
																<p style="font-size:11px; line-height:16px; margin:0; padding:0;"><?php echo $this->lang->line('arbitrary_units'); ?></p>
															</td>
														</tr>
													</table>
												<?php }elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name) || preg_match('/\bNEXTLAB Complete Food\b/', $respnedn->name)){ ?>
													<div id="CreateImage3">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																			<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;"><?php echo $this->lang->line('ige_igg'); ?></th>
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
																				if(!empty($algName) && $algName->name !='N/A'){
																					$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
																					$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
																					echo '<tr bgcolor="#d0ebef">
																						<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $algName->name .'</td>
																						<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
																						<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIge->result.'</td>
																					</tr>
																					<tr bgcolor="#d0ebef">
																						<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;"></td>
																						<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgG</td>
																						<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIgg->result.'</td>
																					</tr>';
																				}
																			}
																		}
																		?>
																		<tr>
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_0'); ?></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="foodbargraph" style="margin-top:10px;">
																								<?php
																								$serumResultsgfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
																								if(!empty($serumResultsgfod)){
																									$rsultFIge = $rsultFIgg = []; $resultperIge = $resultperIgg = 0;
																									foreach($serumResultsgfod as $row1){
																										$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
																										if(!empty($algName) && $algName->name !='N/A'){
																											$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIge)){
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
																												if($rsultFIge->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 5){
																													echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																													echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 10){
																													echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
																											}

																											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIgg)){
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
																												if($rsultFIgg->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 5){
																													echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																													echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 10){
																													echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
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
													</div>
													<table width="100%"><tr><td height="20"></td></tr></table>
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; <?php echo $this->lang->line('5_0'); ?></strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment'); ?></p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong><?php echo $this->lang->line('5_10'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment_2'); ?></p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> <?php echo $this->lang->line('10_0'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('additional_borderline'); ?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
														<tr>
															<td>
																<p style="font-size:11px; line-height:16px; margin:0; padding:0;"><?php echo $this->lang->line('arbitrary_units'); ?></p>
															</td>
														</tr>
													</table>
												<?php
												}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood Panel\b/', $respnedn->name))){
													if(!empty($optnenvArr)){
													?>
														<div id="CreateImage">
															<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
																<tr>
																	<td>
																		<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																			<tr>
																				<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																				<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;"><?php echo $this->lang->line('ea_units'); ?>*</th>
																			</tr>
																			<tr bgcolor="#eaf6f7">
																				<td align="left" style="padding:5px 10px 5px 15px;"></td>
																				<td align="right" style="padding:5px 15px 5px 10px;"></td>
																			</tr>
																			<?php
																			foreach($optnenvArr as $row1){
																				echo '<tr bgcolor="#d0ebef">
																					<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $row1['name'] .'</td>
																					<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
																				</tr>';
																			}
																			?>
																			<tr>
																				<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																			</tr>
																		</table>
																		<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																			<tr>
																				<td>
																					<table cellpadding="0" cellspacing="0" width="100%" border="0">
																						<tr>
																							<th height="35" width="30%"></th>
																							<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_0'); ?></th>
																							<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																							<th></th>
																						</tr>
																						<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																							<td>
																								<ul class="bargraph" style="margin-top:15px;">
																									<?php
																									$resultper = 0;
																									foreach($optnenvArr as $row2){
																										if($row2['result'] > 0 && $row2['result'] <= 5){
																											$resultper = 20*$row2['result'];
																										}elseif($row2['result'] == 6){
																											$resultper = 110;
																										}elseif($row2['result'] == 7){
																											$resultper = 120;
																										}elseif($row2['result'] == 8){
																											$resultper = 130;
																										}elseif($row2['result'] == 9){
																											$resultper = 140;
																										}elseif($row2['result'] == 10){
																											$resultper = 150;
																										}elseif($row2['result'] > 10){
																											$resultper = (14*$row2['result']);
																										}else{
																											$resultper = 0;
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
														</div>
														<table width="100%"><tr><td height="10"></td></tr></table>
													<?php } ?>
													<?php if(!empty($moduleArr)){ ?>
													<div id="CreateImage2">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('moulds'); ?></th>
																			<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;"><?php echo $this->lang->line('ea_units'); ?>*</th>
																		</tr>
																		<tr bgcolor="#eaf6f7">
																			<td align="left" style="padding:5px 10px 5px 15px;"></td>
																			<td align="right" style="padding:5px 15px 5px 10px;"></td>
																		</tr>
																		<?php
																		if(!empty($moduleArr)){
																			foreach($moduleArr as $row1){
																				echo '<tr bgcolor="#d0ebef">
																					<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $row1['name'] .'</td>
																					<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
																				</tr>';
																			}
																		}
																		?>
																		<tr>
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('60_0'); ?></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('75_0'); ?></th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="bargraph" style="margin-top:15px;">
																								<?php
																								if(!empty($moduleArr)){
																									$resultper = 0;
																									foreach($moduleArr as $row2){
																										if($row2['result'] <= 59){
																											$resultper = $row2['result']+40;
																										}elseif($row2['result'] >= 60 && $row2['result'] <= 75){
																											$resultper = 2*$row2['result'];
																										}elseif($row2['result'] > 75){
																											$resultper = (2*$row2['result'])+10;
																										}
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] > 0 && $row2['result'] <= 59){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] >= 60 && $row2['result'] <= 75){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 75){
																											echo '<li class="red" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}else{
																											echo '<li class="" style="width:0%;" class=""><span></span></li>';
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
													</div>
													<?php } ?>
													<table width="100%"><tr><td height="10"></td></tr></table>
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
													<div id="CreateImage3">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																			<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;"><?php echo $this->lang->line('ige_igg'); ?></th>
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
																				$algName = $this->OrdersModel->getAllergensName($rowf->lims_allergens_id,$order_details['allergens']);
																				if(!empty($algName) && $algName->name !='N/A'){
																					$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
																					$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
																					echo '<tr bgcolor="#d0ebef">
																						<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $algName->name .'</td>
																						<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
																						<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIge->result.'</td>
																					</tr>
																					<tr bgcolor="#d0ebef">
																						<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;"></td>
																						<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgG</td>
																						<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIgg->result.'</td>
																					</tr>';
																				}
																			}
																		}
																		?>
																		<tr>
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_0'); ?></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="foodbargraph" style="margin-top:10px;">
																								<?php
																								$serumResultsgfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
																								if(!empty($serumResultsgfod)){
																									$rsultFIge = $rsultFIgg = []; $resultperIge = $resultperIgg = 0;
																									foreach($serumResultsgfod as $row1){
																										$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id,$order_details['allergens']);
																										if(!empty($algName) && $algName->name !='N/A'){
																											$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIge)){
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
																												if($rsultFIge->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 5){
																													echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																													echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 10){
																													echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
																											}

																											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIgg)){
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
																												if($rsultFIgg->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 5){
																													echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																													echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 10){
																													echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
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
													</div>
													<?php } ?>
													<table width="100%"><tr><td height="20"></td></tr></table>
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; <?php echo $this->lang->line('5_0'); ?></strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment'); ?></p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong><?php echo $this->lang->line('5_10'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment_2'); ?></p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> <?php echo $this->lang->line('10_0'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('additional_borderline'); ?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
														<tr>
															<td>
																<p style="font-size:11px; line-height:16px; margin:0; padding:0;"><?php echo $this->lang->line('arbitrary_units'); ?></p>
															</td>
														</tr>
													</table>
												<?php
												}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood SCREEN\b/', $respnedn->name))){
													if(!empty($optnenvArr)){
													?>
														<div id="CreateImage">
															<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
																<tr>
																	<td>
																		<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																			<tr>
																				<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																				<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;"><?php echo $this->lang->line('ea_units'); ?>*</th>
																			</tr>
																			<tr bgcolor="#eaf6f7">
																				<td align="left" style="padding:5px 10px 5px 15px;"></td>
																				<td align="right" style="padding:5px 15px 5px 10px;"></td>
																			</tr>
																			<?php
																			foreach($optnenvArr as $row1){
																				echo '<tr bgcolor="#d0ebef">
																					<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $row1['name'] .'</td>
																					<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
																				</tr>';
																			}
																			?>
																			<tr>
																				<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibodys'); ?></th>
																			</tr>
																		</table>
																		<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																			<tr>
																				<td>
																					<table cellpadding="0" cellspacing="0" width="100%" border="0">
																						<tr>
																							<th height="35" width="30%"></th>
																							<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_0'); ?></th>
																							<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																							<th></th>
																						</tr>
																						<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																							<td>
																								<ul class="bargraph" style="margin-top:15px;">
																									<?php
																									$resultper = 0;
																									foreach($optnenvArr as $row2){
																										if($row2['result'] > 0 && $row2['result'] <= 5){
																											$resultper = 20*$row2['result'];
																										}elseif($row2['result'] == 6){
																											$resultper = 110;
																										}elseif($row2['result'] == 7){
																											$resultper = 120;
																										}elseif($row2['result'] == 8){
																											$resultper = 130;
																										}elseif($row2['result'] == 9){
																											$resultper = 140;
																										}elseif($row2['result'] == 10){
																											$resultper = 150;
																										}elseif($row2['result'] > 10){
																											$resultper = (14*$row2['result']);
																										}else{
																											$resultper = 0;
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
														</div>
														<table width="100%"><tr><td height="20"></td></tr></table>
													<?php } ?>
													<?php if(!empty($moduleArr)){ ?>
													<div id="CreateImage2">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('moulds'); ?></th>
																			<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;"><?php echo $this->lang->line('ea_units'); ?>*</th>
																		</tr>
																		<tr bgcolor="#eaf6f7">
																			<td align="left" style="padding:5px 10px 5px 15px;"></td>
																			<td align="right" style="padding:5px 15px 5px 10px;"></td>
																		</tr>
																		<?php
																		if(!empty($moduleArr)){
																			foreach($moduleArr as $row1){
																				echo '<tr bgcolor="#d0ebef">
																					<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $row1['name'] .'</td>
																					<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
																				</tr>';
																			}
																		}
																		?>
																		<tr>
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('60_0'); ?></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('75_0'); ?></th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="bargraph" style="margin-top:15px;">
																								<?php
																								if(!empty($moduleArr)){
																									$resultper = 0;
																									foreach($moduleArr as $row2){
																										if($row2['result'] <= 59){
																											$resultper = $row2['result']+40;
																										}elseif($row2['result'] >= 60 && $row2['result'] <= 75){
																											$resultper = 2*$row2['result'];
																										}elseif($row2['result'] > 75){
																											$resultper = (2*$row2['result'])+10;
																										}
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] > 0 && $row2['result'] <= 59){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] >= 60 && $row2['result'] <= 75){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 75){
																											echo '<li class="red" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}else{
																											echo '<li class="" style="width:0%;" class=""><span></span></li>';
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
													</div>
													<?php } ?>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; <?php echo $this->lang->line('5_0'); ?></strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment'); ?></p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong><?php echo $this->lang->line('5_10'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment_2'); ?></p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> <?php echo $this->lang->line('10_0'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('additional_borderline'); ?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
														<tr>
															<td>
																<p style="font-size:11px; line-height:16px; margin:0; padding:0;"><?php echo $this->lang->line('arbitrary_units'); ?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
														<tbody>
															<tr>
																<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;"><?php echo $this->lang->line('screen_food_panel'); ?></h6></td>
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
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
														<tbody>
															<tr>
																<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;"><?php echo $this->lang->line('screen_penal'); ?></h6></td>
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
																	if($ivalue['parent_id'] == '6'){
																		if($serumResults->result >= 76){
																			$counteriP++;
																		}elseif($serumResults->result <= 75 && $serumResults->result >= 60){
																			$counteriB++;
																		}else{
																			$counteriN++;
																		}
																	}else{
																		if($serumResults->result >= 10){
																			$counteriP++;
																		}elseif($serumResults->result >= 5 && $serumResults->result < 10){
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
																		if($serumiResults->result >= 10){
																			$counteriP++;
																		}elseif($serumiResults->result >= 5 && $serumiResults->result < 10){
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

															if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994') || ($order_details['lab_id']=='13788' && $order_details['species_name'] != 'Horse')){
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
																		if($malasseziaResults->result >= 76){
																			echo '<td>POSITIVE</td>';
																		}elseif($malasseziaResults->result <= 75 && $malasseziaResults->result >= 60){
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
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
														<tbody>
															<tr>
																<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;"><?php echo $this->lang->line('screen_food_panel'); ?></h6></td>
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
												}elseif(preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name)){
													if(!empty($optnenvArr)){
													?>
														<div id="CreateImage">
															<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
																<tr>
																	<td>
																		<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																			<tr>
																				<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																				<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;"><?php echo $this->lang->line('ea_units'); ?>*</th>
																			</tr>
																			<tr bgcolor="#eaf6f7">
																				<td align="left" style="padding:5px 10px 5px 15px;"></td>
																				<td align="right" style="padding:5px 15px 5px 10px;"></td>
																			</tr>
																			<?php
																			foreach($optnenvArr as $row1){
																				echo '<tr bgcolor="#d0ebef">
																					<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $row1['name'] .'</td>
																					<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
																				</tr>';
																			}
																			?>
																			<tr>
																				<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																			</tr>
																		</table>
																		<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																			<tr>
																				<td>
																					<table cellpadding="0" cellspacing="0" width="100%" border="0">
																						<tr>
																							<th height="35" width="30%"></th>
																							<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_0'); ?></th>
																							<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																							<th></th>
																						</tr>
																						<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																							<td>
																								<ul class="bargraph" style="margin-top:15px;">
																									<?php
																									$resultper = 0;
																									foreach($optnenvArr as $row2){
																										if($row2['result'] > 0 && $row2['result'] <= 5){
																											$resultper = 20*$row2['result'];
																										}elseif($row2['result'] == 6){
																											$resultper = 110;
																										}elseif($row2['result'] == 7){
																											$resultper = 120;
																										}elseif($row2['result'] == 8){
																											$resultper = 130;
																										}elseif($row2['result'] == 9){
																											$resultper = 140;
																										}elseif($row2['result'] == 10){
																											$resultper = 150;
																										}elseif($row2['result'] > 10){
																											$resultper = (14*$row2['result']);
																										}else{
																											$resultper = 0;
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
														</div>
														<table width="100%"><tr><td height="10"></td></tr></table>
													<?php } ?>
													<?php if(!empty($moduleArr)){ ?>
													<div id="CreateImage2">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('moulds'); ?></th>
																			<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;"><?php echo $this->lang->line('ea_units'); ?>*</th>
																		</tr>
																		<tr bgcolor="#eaf6f7">
																			<td align="left" style="padding:5px 10px 5px 15px;"></td>
																			<td align="right" style="padding:5px 15px 5px 10px;"></td>
																		</tr>
																		<?php
																		if(!empty($moduleArr)){
																			foreach($moduleArr as $row1){
																				echo '<tr bgcolor="#d0ebef">
																					<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $row1['name'] .'</td>
																					<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">'. $row1['result'] .'</td>
																				</tr>';
																			}
																		}
																		?>
																		<tr>
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('60_0'); ?></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('75_0'); ?></th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="bargraph" style="margin-top:15px;">
																								<?php
																								if(!empty($moduleArr)){
																									$resultper = 0;
																									foreach($moduleArr as $row2){
																										if($row2['result'] <= 59){
																											$resultper = $row2['result']+40;
																										}elseif($row2['result'] >= 60 && $row2['result'] <= 75){
																											$resultper = 2*$row2['result'];
																										}elseif($row2['result'] > 75){
																											$resultper = (2*$row2['result'])+10;
																										}
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] > 0 && $row2['result'] <= 59){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] >= 60 && $row2['result'] <= 75){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 75){
																											echo '<li class="red" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}else{
																											echo '<li class="" style="width:0%;" class=""><span></span></li>';
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
													</div>
													<?php } ?>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<?php 
													if(preg_match('/\bFood\b/', $respnedn->name)){
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
															<div id="CreateImage3">
																<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
																	<tr>
																		<td>
																			<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																				<tr>
																					<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																					<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;"><?php echo $this->lang->line('ige_igg'); ?></th>
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
																						if(!empty($algName) && $algName->name !='N/A'){
																							$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
																							$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
																							echo '<tr bgcolor="#d0ebef">
																								<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $algName->name .'</td>
																								<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
																								<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIge->result.'</td>
																							</tr>
																							<tr bgcolor="#d0ebef">
																								<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;"></td>
																								<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgG</td>
																								<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIgg->result.'</td>
																							</tr>';
																						}
																					}
																				}
																				?>
																				<tr>
																					<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																				</tr>
																			</table>
																			<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																				<tr>
																					<td>
																						<table cellpadding="0" cellspacing="0" width="100%" border="0">
																							<tr>
																								<th height="35" width="30%"></th>
																								<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_0'); ?></th>
																								<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																								<th></th>
																							</tr>
																							<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																								<td>
																									<ul class="foodbargraph" style="margin-top:10px;">
																										<?php
																										$serumResultsgfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
																										if(!empty($serumResultsgfod)){
																											$rsultFIge = $rsultFIgg = []; $resultperIge = $resultperIgg = 0;
																											foreach($serumResultsgfod as $row1){
																												$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
																												if(!empty($algName) && $algName->name !='N/A'){
																													$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																													if(!empty($rsultFIge)){
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
																														if($rsultFIge->result == 0){
																															echo '<li style="width:0%;" class=""><span></span></li>';
																														}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 5){
																															echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																														}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																															echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																														}elseif($rsultFIge->result > 10){
																															echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																														}
																													}else{
																														echo '<li style="width:0%;" class=""><span></span></li>';
																													}

																													$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																													if(!empty($rsultFIgg)){
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
																														if($rsultFIgg->result == 0){
																															echo '<li style="width:0%;" class=""><span></span></li>';
																														}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 5){
																															echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																														}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																															echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																														}elseif($rsultFIgg->result > 10){
																															echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																														}
																													}else{
																														echo '<li style="width:0%;" class=""><span></span></li>';
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
															</div>
														<?php } ?>
													<?php } ?>
													<table width="100%"><tr><td height="20"></td></tr></table>
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; <?php echo $this->lang->line('5_0'); ?></strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment'); ?></p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong><?php echo $this->lang->line('5_10'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment_2'); ?></p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> <?php echo $this->lang->line('10_0'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('additional_borderline'); ?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
														<tr>
															<td>
																<p style="font-size:11px; line-height:16px; margin:0; padding:0;"><?php echo $this->lang->line('arbitrary_units'); ?></p>
															</td>
														</tr>
													</table>
												<?php
												}elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){
												?>
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
														<tbody>
															<tr>
																<td colspan="2"><h6 style="color:#366784; text-transform:uppercase; font-size:18px; margin:0;"><?php echo $this->lang->line('screen_penal'); ?></h6></td>
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
																	if($ivalue['parent_id'] == '6'){
																		if($serumResults->result >= 76){
																			$counteriP++;
																		}elseif($serumResults->result <= 75 && $serumResults->result >= 60){
																			$counteriB++;
																		}else{
																			$counteriN++;
																		}
																	}else{
																		if($serumResults->result >= 10){
																			$counteriP++;
																		}elseif($serumResults->result >= 5 && $serumResults->result < 10){
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
																		if($serumiResults->result >= 10){
																			$counteriP++;
																		}elseif($serumiResults->result >= 5 && $serumiResults->result < 10){
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

															if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994') || ($order_details['lab_id']=='13788' && $order_details['species_name'] != 'Horse')){
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
																		if($malasseziaResults->result >= 76){
																			echo '<td>POSITIVE</td>';
																		}elseif($malasseziaResults->result <= 75 && $malasseziaResults->result >= 60){
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
													<div id="CreateImage3">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
																			<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;"><?php echo $this->lang->line('ige_igg'); ?></th>
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
																				if(!empty($algName) && $algName->name !='N/A'){
																					$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($rowf->name,$sresultID,$stypeID);
																					$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($rowf->name,$sresultID,$stypeID);
																					echo '<tr bgcolor="#d0ebef">
																						<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;">'. $algName->name .'</td>
																						<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgE</td>
																						<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIge->result.'</td>
																					</tr>
																					<tr bgcolor="#d0ebef">
																						<td align="left" style="padding:5px 10px 5px 15px; font-size: 12px;font-weight: 600;"></td>
																						<td align="right" style="padding:5px 10px 5px 15px; font-size:12px;">IgG</td>
																						<td align="right" style="padding: 0px 10px;text-align: right;">'.$rsultFIgg->result.'</td>
																					</tr>';
																				}
																			}
																		}
																		?>
																		<tr>
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: left;"><?php echo $this->lang->line('5_0'); ?></th>
																						<th width="10%" style="color:#326883; font-size:15px;text-align: center;"><?php echo $this->lang->line('10_0'); ?></th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="foodbargraph" style="margin-top:10px;">
																								<?php
																								$serumResultsgfod = $this->OrdersModel->getSerumTestResultFood($sresultID,$stypeID);
																								if(!empty($serumResultsgfod)){
																									$rsultFIge = $rsultFIgg = []; $resultperIge = $resultperIgg = 0;
																									foreach($serumResultsgfod as $row1){
																										$algName = $this->OrdersModel->getAllergensName($row1->lims_allergens_id);
																										if(!empty($algName) && $algName->name !='N/A'){
																											$rsultFIge = $this->OrdersModel->getSerumTestResultFoodIGE($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIge)){
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
																												if($rsultFIge->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 5){
																													echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 5 && $rsultFIge->result <= 10){
																													echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 10){
																													echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
																											}

																											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIgg)){
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
																												if($rsultFIgg->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 5){
																													echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 5 && $rsultFIgg->result <= 10){
																													echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 10){
																													echo '<li class="red" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
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
													</div>
													<table width="100%"><tr><td height="20"></td></tr></table>
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; <?php echo $this->lang->line('5_0'); ?></strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment'); ?></p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong><?php echo $this->lang->line('5_10'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment_2'); ?></p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> <?php echo $this->lang->line('10_0'); ?></strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('additional_borderline'); ?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
														<tr>
															<td>
																<p style="font-size:11px; line-height:16px; margin:0; padding:0;"><?php echo $this->lang->line('arbitrary_units'); ?></p>
															</td>
														</tr>
													</table>
													<?php } ?>
												<?php
												}
											} ?>
										</td>
									</tr>
								</table>
								<table width="100%"><tr><td height="20"></td></tr></table>
								<?php 
								if((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))){
									if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){
										echo form_open('orders/serum_treatment/'.$order_details['id'].'', array('name'=>'recommendationForm', 'id'=>'recommendationForm'));
										if($this->user_role == '1' || $this->user_role == '11'){
											echo '<input type="hidden" id="treatment" name="treatment" value="" />';
										}
									}
									if($this->user_role == '1' || $this->user_role == '11'){
										echo form_open('orders/recommendation/'.$order_details['id'].'', array('name'=>'recommendationForm', 'id'=>'recommendation1Form'));
										echo '<input type="hidden" id="treatment" name="treatment" value="" />';
									}
									?>
										<?php if($block1 > 0){ ?>
										<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding:0; background:#ffffff;">
											<tr>
												<td>
													<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
														<tr>
															<td>
																<h4 style="margin:0; color:#2a5b74; font-size:24px;"></h4>
															</td>
															<td align="right">
																<p style="margin:0; color:#333333; font-size:13px;"><?php echo $this->lang->line('laboratory_number'); ?> <?php echo $order_details['lab_order_number'];?> - <?php echo $this->lang->line('nextvu_order_no'); ?> <?php echo $order_details['order_number'];?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="20"></td></tr></table>
													<table cellspacing="0" cellpadding="0" border="0" width="30%" align="left" style="margin-left:30px;min-width:30%;">
														<tr>
															<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?> <input type="radio" name="treatment" id="treatment1" value="1" /><?php } ?> <?php echo $this->lang->line('Treatment_option'); ?> 1</th>
														</tr>
														<tr>
															<td bgcolor="#e2f2f4" style="padding:20px;">
																<p style="color:#184359; font-size:13px; margin:0; padding:0;"><?php echo $this->lang->line('dual_allergens'); ?></p>
																<ol style="color:#184359; font-size:13px; margin:15px 0 0 20px; padding:0;">
																	<?php 
																	$a=0;
																	foreach($option1 as $key=>$value){
																		if(!in_array($key,$removed_treatment_1)){
																			?>
																			<li style="margin-bottom: 5px;">
																				<input type="hidden" name="allergens1[]" value="<?php echo $key; ?>"><?php echo $value; ?>
																			</li>
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
															<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
																<table cellpadding="0" cellspacing="0" border="0" width="100%;">
																	<tr>
																		<td height="20"></td>
																	</tr>
																	<tr>
																		<td colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('dual_allergens'); ?>This option results in:</td>
																	</tr>
																	<tr>
																		<td width="30%"><input type="text" value="<?php echo $totalViald; ?>" style="background:#e4eaed; padding:0 10px; height:40px; border:1px solid #4d5d67; width:60px;color: #000;" <?php if(empty($option1)){ echo 'disabled="disabled"'; } ?> /></td>
																		<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('subcutaneous'); ?> <br><?php echo $this->lang->line('Immunotherapy'); ?> </td>
																		<td width="30%"><img src="<?php echo base_url(); ?>assets/images/needle.png" alt="" width="60px" /></td>
																	</tr>
																	<tr>
																		<td height="40"></td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>

													<?php if(!empty($block2) && $option1 != $block2){ ?>
													<table cellspacing="0" cellpadding="0" border="0" width="30%" align="left" style="margin-left:30px;min-width:30%;">
														<tr>
															<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?> <input type="radio" name="treatment" id="treatment2" value="2" <?php if(empty($block2)){ echo 'disabled="disabled"'; } ?> /><?php } ?> &nbsp; <?php echo $this->lang->line('Treatment_option'); ?> 2</th>
														</tr>
														<tr>
															<td bgcolor="#e2f2f4" style="padding:20px;">
																<p style="color:#184359; font-size:13px; margin:0; padding:0;"><?php echo $this->lang->line('alternative_treatment_option_1'); ?> </p>
																<ol style="color:#184359; font-size:13px; margin:15px 0 0 20px; padding:0;">
																	<?php 
																	$b=0; $totalViald = 0;
																	foreach($block2 as $keys=>$values){
																		if(!in_array($keys,$removed_treatment_2)){ 
																		?>
																			<li style="margin-bottom: 5px;">
																				<input type="hidden" name="allergens2[]" value="<?php echo $keys; ?>"><?php echo $values; ?>
																			</li>
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
															<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
																<table cellpadding="0" cellspacing="0" border="0" width="100%;">
																	<tr><td height="20"></td></tr>
																	<tr>
																		<th colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('option_results'); ?>:</th>
																	</tr>
																	<tr>
																		<td width="30%"><input type="text" value="<?php echo $totalViald; ?>" style="background:#e4eaed; padding:0 10px; height:40px; border:1px solid #4d5d67; width:60px;color: #000;" <?php if(empty($block2)){ echo 'disabled="disabled"'; } ?> /></td>
																		<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('subcutaneous'); ?> <br><?php echo $this->lang->line('immuno_therapy'); ?> </td>
																		<td width="30%"><img src="<?php echo base_url(); ?>assets/images/needle.png" alt="" width="60px" /></td>
																	</tr>
																	<tr><td height="40"></td></tr>
																</table>
															</td>
														</tr>
													</table>
													<?php } ?>
													<?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?>
													<table cellspacing="0" cellpadding="0" border="0" width="30%" align="left" style="margin-left:30px;min-width:30%;">
														<tr>
															<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?> <input type="radio" name="treatment" id="treatment3" value="3" /><?php } ?> &nbsp;<?php echo $this->lang->line('compose_it_yourself'); ?> </th>
														</tr>
														<tr>
															<td bgcolor="#e2f2f4" style="padding:20px;">
																<p style="color:#184359; font-size:13px; margin:0; padding:0;"><?php echo $this->lang->line('own_therapy_based'); ?></p>
																<textarea style="resize:none; background:#e4eaed; padding:10px; height:400px; border:1px solid #4d5d67; width:70px; width:100%; box-sizing:border-box; margin:20px 0 0 0; outline:none;"></textarea>
															</td>
														</tr>
														<tr>
															<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
																<table cellpadding="0" cellspacing="0" border="0" width="100%;">
																	<tr><td height="20"></td></tr>
																	<tr>
																		<td><p style="color:#184359; font-size:13px; margin:0; padding:0;"><?php echo $this->lang->line('artuvetrin_subcutaneous_immunotherapy'); ?></p></td>
																	</tr>
																	<tr><td height="40"></td></tr>
																</table>
															</td>
														</tr>
													</table>
													<?php } ?>
												</td>
											</tr>
										</table>
										<?php } ?>
									<?php 
									if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){
										echo form_close();
									}
									if($this->user_role == '1' || $this->user_role == '11'){
										echo form_close();
									}
									?>
								<?php } ?>
								<?php if(((preg_match('/\bComplete Food\b/', $respnedn->name)) || (preg_match('/\bNEXTLAB Complete Food\b/', $respnedn->name)) || ((preg_match('/\bSCREEN Environmental\b/', $respnedn->name) || preg_match('/\bComplete Environmental\b/', $respnedn->name)) && preg_match('/\bFood\b/', $respnedn->name))) && (!preg_match('/\bSCREEN Food\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name)) && (!preg_match('/\bFood Positive\b/', $respnedn->name)) && ($foodpos > 0)){ ?>
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="100" style="width:100%; max-width:1030px;background:#ffffff;">
									<tr>
										<td style="padding: 0px 10px;">
											<table width="100%"><tr><td height="20"></td></tr></table>
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
												<tr>
													<th colspan="22" valign="bottom" style="color:#366784;font-size:14px;font-weight:bold;padding: 10px 0 0 0;margin: 0px; border-right: 0px">Specific</th>
												</tr>
												<tr>
													<td style="font-size:10px;" class="table-first">Canine DRM</td>
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
												<tr>
													<td style="font-size:10px;" class="table-first">Active Dog</td>
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
										</td>
									</tr>
								</table>
								<?php } ?>
								<?php if((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))){ ?>
									<table width="100%"><tr><td height="20"></td></tr></table>
									<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding:0; background:#ffffff;">
										<tr>
											<td>
												<table width="100%"><tr><td height="30"></td></tr></table>
												<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url(); ?>assets/images/next-header2.jpg) left center no-repeat; background-size:cover;">
													<tr>
														<td valign="middle" width="430" style="padding:60px 30px 60px 50px;">
															<img src="<?php echo base_url(); ?>assets/images/nextlab-logo.jpg" alt="" style="max-height:100px; max-width:280px; border-radius:4px;" />
															<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;"><?php echo $this->lang->line('page_title'); ?> <br><?php echo $this->lang->line('Treatment_advice'); ?></h5>
														</td>
														<td valign="middle"></td>
													</tr>
												</table>
												<table width="100%"><tr><td height="30"></td></tr></table>
												<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
													<tr>
														<td>
															<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('step_3_starting_the_treatment'); ?></h4>
															<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;"><?php echo $this->lang->line('faq_title'); ?></p>
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
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q1'); ?></h6></td>	
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a1'); ?></p></td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td colspan="2">
																		<table align="center" width="360">
																			<tr bgcolor="#326883">
																				<th align="left" height="25" style="color:#ffffff; font-size:13px; padding:0 0 0 20px;"><?php echo $this->lang->line('adviced_schedule'); ?></th>
																				<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;"><?php echo $this->lang->line('dosage'); ?></th>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('week_1'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('0_2_ml'); ?></td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('2_weeks_later_week_3'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('0_4_ml'); ?></td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('2_weeks_later_week_5'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('0_6_ml'); ?></td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('2_weeks_later_week_7'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('0_8_ml'); ?></td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('3_weeks_later_week_10'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('1_0_ml'); ?></td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('3_weeks_later_week_13'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('1_0_ml'); ?></td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('4_weeks_later_week_17'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('1_0_ml'); ?></td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('4_weeks_later_week_21'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('1_0_ml'); ?></td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td colspan="2" align="center" bgcolor="#b8c6d6" style="padding:15px; font-size:12px; color:#1f4964;"><?php echo $this->lang->line('positive_a2'); ?></td>
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
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('medical_department'); ?> </p></td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q4'); ?> </h6></td>
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q4'); ?></p></td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q5'); ?></h6></td>
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('contact_our_medical_department'); ?></p></td>
																</tr>
															</table>
															<table class="" cellspacing="0" cellpadding="0" border="0" align="right" width="47%">
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"> <?php echo $this->lang->line('positive_q6'); ?></h6></td>	
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q6'); ?></p></td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q7'); ?></h6></td>
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q7'); ?></p></td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q8'); ?></h6></td>	
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q8'); ?>.</p></td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q9'); ?></h6></td>	
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('general_improvement_serum_result'); ?>
																	</p></td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q10'); ?></h6></td>	
																</tr>
																<tr>
																	<td colspan="2">
																		<p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q10'); ?>:</p>
																		<ul style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">
																			<li><?php echo $this->lang->line('positive_faq_option1'); ?></li>
																			<li><?php echo $this->lang->line('positive_faq_option2'); ?></li>
																			<li><?php echo $this->lang->line('positive_faq_option3'); ?></li>
																		</ul>
																		<p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">
																		<?php echo $this->lang->line('positive_faq_option4'); ?></p>
																	</td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q11'); ?></h6></td>	
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_option5'); ?>
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
															<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;"><?php echo $this->lang->line('page_title'); ?> <br><?php echo $this->lang->line('Treatment_advice'); ?></h5>
														</td>					
														<td valign="middle"></td>
													</tr>
												</table>
												<table width="100%"><tr><td height="30"></td></tr></table>
												<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
													<tr>
														<td>
															<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('about_next_+'); ?></h4>
															<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;"><?php echo $this->lang->line('faq_title'); ?></p>
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
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('malassezia_secondary_problem'); ?>.</p></td>
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
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('correlate_clinical_signs'); ?></h6></td>	
																</tr>
																<tr>
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('necessarily_correlate'); ?></p></td>
																</tr>
																<tr><td height="30"></td></tr>
																<tr>
																	<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
																	<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('can_symptomatic_medication_affect_the_result'); ?></h6></td>	
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
																				<th align="left" height="45" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;"><?php echo $this->lang->line('Allergens'); ?></th>
																				<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;"><?php echo $this->lang->line('without'); ?><br><?php echo $this->lang->line('CCD_blocker'); ?></th>
																				<th align="left" style="color:#ffffff; font-size:13px; padding:0 20px 0 20px;"><?php echo $this->lang->line('without'); ?><br><?php echo $this->lang->line('CCD_blocker'); ?></th>
																			</tr>
																			<tr>
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('phleum_pratense'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
																			</tr>
																			<tr>
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('poa_pratensis'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
																			</tr>
																			<tr>
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('dactylis_glomerata'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('486_0'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
																			</tr>
																			<tr>
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('lolium_perenne'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
																			</tr>
																			<tr>
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('rumex_acetosella'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
																			</tr>
																			<tr>
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('urtica_spp'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('486_0'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('375_0'); ?></td>
																			</tr>
																			<tr>
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('chenopodium_album'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#ffffff;"><?php echo $this->lang->line('486_0'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
																			</tr>
																			<tr>
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('artemisa_vulgaris'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('486_0'); ?></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><?php echo $this->lang->line('375_0'); ?></td>
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
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><strong><?php echo $this->lang->line('486_0'); ?></strong></td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;"><strong><?php echo $this->lang->line('375_0'); ?></strong></td>
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
															<p style="margin:0 0 0 0; padding:0; color:#326883; font-size:13px;"><?php echo $this->lang->line('medical_dep_email'); ?></p>
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
								<?php } ?>
								<table width="100%"><tr><td height="20"></td></tr></table>
								<?php if(((preg_match('/\bComplete Food\b/', $respnedn->name)) || (preg_match('/\bNEXTLAB Complete Food\b/', $respnedn->name)) || ((preg_match('/\bSCREEN Environmental\b/', $respnedn->name) || preg_match('/\bComplete Environmental\b/', $respnedn->name)) && preg_match('/\bFood\b/', $respnedn->name))) && (!preg_match('/\bSCREEN Food\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name)) && (!preg_match('/\bFood Positive\b/', $respnedn->name)) && ($foodpos > 0)){ ?>
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="100" style="width:100%; max-width:1030px;background:#ffffff;">
									<tr>
										<td style="padding: 0px 10px;">
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
																																		<img src="<?php echo base_url(); ?>assets/images/chart-img.png" width="400">
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
																															In your Nextlab food test results, allergen specific IgE and IgG concentrations are reported as a graded class score between 0 (no reaction) and 5 (very strong reaction).
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
																			<b style="color: #8accd6; text-transform: uppercase;">STEP 1: SELECT ZERO SCORING FOODS - </b>If a food has a class score of 0 for both IgE & IgG, you will see a green tick against it on your results; the high negative predictive value of the test means this food may be suitable as an ingredient for a diet trial. Please note, even if all foods score 0/0 this still does not rule out a food allergy. A class score of ≥ 1 shows that antibodies, above a pre-determined level, have been detected to that food and it should be avoided for the purposes of a diet trial.
																		</td>
																	</tr>
																	<tr>
																		<td style="height: 5px;"></td>
																	</tr>
																	<tr>
																		<td width="100%" style="font-size: 12px; line-height: 18px; color: #57585a">
																			In situations where the results yield no foods with a class score of 0 to both IgE & IgG, allergens with low reactivity (score 1) may also be considered, if ingestion of that food has been recently proven to be tolerated. If all foods tested have scores higher than this, either a hydrolysed diet or home-prepared diet using uncommon novel ingredients is advised (see below).
																		</td>
																	</tr>
																	<tr>
																		<td style="height: 5px;"></td>
																	</tr>
																	<tr>
																		<td width="100%" style="font-size: 12px; line-height: 18px; color: #57585a">
																			<b style="color: #8accd6; text-transform: uppercase;">STEP 2: CONSIDER CROSS-REACTIVITY - </b>Cross-reactivity has been shown to exist between certain proteins. If an animal has a class score of ≥ 1 to a protein source (or that food is a known dietary component), it is therefore advisable to avoid all other similar types of protein in a diet trial (where possible). For the example in the results above, although lamb has an IgE & IgG class score of 0, the other mammalian proteins (beef, cow’s milk etc.) do not; in contrast, all of the avian protein sources (chicken, turkey, duck, egg) score 0/0, so may be a better choice for a dietary trial. Food allergens are listed within their related groups to aid in the selection process.
																		</td>
																	</tr>
																	<tr>
																		<td style="height: 5px;"></td>
																	</tr>
																	<tr>
																		<td width="100%" style="font-size: 12px; line-height: 18px; color: #57585a">
																			<b style="color: #8accd6; text-transform: uppercase;">STEP 3: INCORPORATE DIETARY HISTORY - </b>The full dietary history must be considered whether opting for a homeprepared diet (using a single protein and a single carbohydrate source) or a commercial diet. Ingredients to which the animal has not been previously exposed should be selected; where many different foods have been given, more uncommon alternatives might be required; some examples are listed in the table below. Please note that this is not an exhaustive list and as long as the ingredient is novel to the animal in question, and fits with the results of the food test (factoring in crossreactivity if possible), then it can be a candidate for a food trial.
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
																						<th style="text-transform: uppercase; color:#97d4dc; background-color: #336584; padding: 0.2mm 2.5mm; font-size: 12px; line-height: 18px; border: 1px solid #c4e5ea; border-left: 0; border-top: 0;"><b>Animal proteins</b></th>
																						<th style="text-transform: uppercase; color:#97d4dc; background-color: #336584; padding: 0.2mm 2.5mm; font-size: 12px; line-height: 18px; border: 1px solid #c4e5ea; border-left: 0; border-top: 0;"><b>Fish proteins</b></th>
																						<th style="text-transform: uppercase; color:#97d4dc; background-color: #336584; padding: 0.2mm 2.5mm; font-size: 12px; line-height: 18px; border: 1px solid #c4e5ea; border-left: 0; border-top: 0;"><b>Other proteins</b></th>
																						<th style="text-transform: uppercase; color:#97d4dc; background-color: #336584; padding: 0.2mm 2.5mm; font-size: 12px; line-height: 18px; border: 1px solid #c4e5ea; border-left: 0; border-top: 0;"><b>Carbohydrates</b></th>
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
																			<b style="color: #8accd6; text-transform: uppercase;">Be aware - </b>Even after factoring in all of the above, both home-prepared and commercial diets (including hydrolysed) may still trigger a reaction in a small number of cases. A second dietary trial using a completely different food is always worth considering, if there is no response to the first. It is especially important to ensure the diet is fully balanced if extending beyond 8 weeks (or if the animal has other health conditions).
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
																							Was the animal fully symptomatic at the time of sampling?
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
																						<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">Was the animal on any medication that might affect testing?</td>
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
																											Certain medications have been shown to affect the immune response, and therefore may impact test results. Please see our Withdrawal Guide for guidance at <b>nextmunelaboratories.co.uk/vets/submit-a-sample-uk/</b>
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
																						<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">Does the animal suffer from any kind of immunodeficiency?</td>
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
																											A generalised immunodeficiency can influence results
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
																						<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">Was the animal on its usual diet prior to sampling?</td>
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
																						<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">Was the animal over 6 months of age?</td>
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
																											Animals should ideally be over 6 months of age before testing, to ensure there is no interference from maternal antibodies, the immune system has fully matured, and the animal has been exposed to a variety of foods. If you would like to test an animal under 6 months old, please contact our Customer Support team on <b>01494 629979</b>, or at <b><a href="mailto:vetorders.uk@nextmune.com" style="color: #57585a; text-decoration: none;">vetorders.uk@nextmune.com</a></b> for advice.
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
																						<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">Could the hypersensitivity be to an unusual allergen?</td>
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
																						<td width="92%" valign="middle" style="font-size: 17px; line-height: 22px; background-color: #8accd6; color: #fff; padding-left: 2mm; text-transform: uppercase;">Is the animal suffering from a food intolerance?</td>
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
																											Diet trials are a big undertaking and, like any diagnostic test, must be run properly in order to generate meaningful results. To enable your clients to make a success of their pet’s diet trial, please see our Diet Trial Instructions, available in the Nextmune UK Laboratories Practice Portal at nextmunelaboratories.co.uk/login
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
															Nextmune Laboratories Limited, Unit 651, Street 5, Thorp Arch Trading Estate, Wetherby, UK, LS23 7FZ<br> T – 01494 629979 E – <a style="color:#ffffff;" href="mailto:vetorders.uk@nextmune.com">vetorders.uk@nextmune.com</a>
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
										</td>
									</tr>
								</table>
								<?php } ?>
							<?php } ?>
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
				$("#treatment2").removeAttr("checked");
				$("#treatment3").removeAttr("checked");
				$("#treatment1").attr("checked","checked");
				$("form#recommendationForm").submit();
			});

			$(document).on('click', '.recommendation2', function(){
				$("#treatment1").removeAttr("checked");
				$("#treatment3").removeAttr("checked");
				$("#treatment2").attr("checked","checked");
				$("form#recommendationForm").submit();
			});

			$(document).on('click', '.recommendation3', function(){
				$("#treatment1").removeAttr("checked");
				$("#treatment2").removeAttr("checked");
				$("#treatment3").attr("checked","checked");
				$("form#recommendationForm").submit();
			});

			$(document).on('click', '.recommendation1Admin', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/recommendation/'.$order_details['id'].'');?>");
				$("#treatment").val(1);
				$("form#recommendation1Form").submit();
			});

			$(document).on('click', '.recommendation2Admin', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/recommendation/'.$order_details['id'].'');?>");
				$("#treatment").val(2);
				$("form#recommendation1Form").submit();
			});

			$(document).on('click', '.recommendations1Admin', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/serum_treatment/'.$order_details['id'].'');?>");
				$("#treatment").val(1);
				$("form#recommendation1Form").submit();
			});

			$(document).on('click', '.recommendations2Admin', function(){
				$("form#recommendation1Form").attr("action","<?php echo site_url('orders/serum_treatment/'.$order_details['id'].'');?>");
				$("#treatment").val(2);
				$("form#recommendation1Form").submit();
			});

			$(document).on('click', '#geeks', function(){
				$('.loader').show();
				$('.scroll').css("overflow","unset");
				myGreeting();
			});

			function myGreeting(){
				html2canvas($("#CreateImage"), {
					onrendered: function(canvas) {
						var imgsrc = canvas.toDataURL("image/png");
						$("#newimg").attr('src', imgsrc);
						var dataURL = canvas.toDataURL();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('upload_pdf_image'); ?>",
							data: {imgBase64: dataURL, order_id: "<?php echo $id; ?>"},
							success: function(data) {
								//window.location= '<?php echo site_url('orders/authorisedConfirmedOld/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/authorisedConfirmedOld/'.$id.''); ?>';
						});
					}
				});

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
								//window.location= '<?php echo site_url('orders/authorisedConfirmedOld/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/authorisedConfirmedOld/'.$id.''); ?>';
						});
                    }
                });

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
								//window.location= '<?php echo site_url('orders/authorisedConfirmedOld/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/authorisedConfirmedOld/'.$id.''); ?>';
						});
                    }
                });

				html2canvas($("#CreateImageHead"), {
					onrendered: function(canvas) {
						var imgsrc = canvas.toDataURL("image/png");
						$("#newimg").attr('src', imgsrc);
						var dataURL = canvas.toDataURL();
						$.ajax({
							type: "POST",
                            url: "<?php echo site_url('upload_pdf_image_head'); ?>",
                            data: {imgBase64: dataURL, order_id: "<?php echo $id; ?>"},
                            success: function(data) {
								setTimeout(function () {
									$('.loader').hide();
									$('.scroll').css("overflow","scroll");
									window.location= '<?php echo site_url('orders/authorisedConfirmedOld/'.$id.''); ?>';
								}, 5000);
							},
                        }).done(function(o) {
							//window.location= '<?php echo site_url('orders/authorisedConfirmedOld/'.$id.''); ?>';
						});
					}
				});
            }

			$(document).on('click', '#downloadonly', function(){
				$('.loader').show();
				$('.scroll').css("overflow","unset");
				myDownloadonly();
			});

			function myDownloadonly(){
				html2canvas($("#CreateImage"), {
					onrendered: function(canvas) {
						var imgsrc = canvas.toDataURL("image/png");
						$("#newimg").attr('src', imgsrc);
						var dataURL = canvas.toDataURL();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('upload_pdf_image'); ?>",
							data: {imgBase64: dataURL, order_id: "<?php echo $id; ?>"},
							success: function(data) {
								//window.location= '<?php echo site_url('orders/getOldLIMSSerumResultPDF/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/getOldLIMSSerumResultPDF/'.$id.''); ?>';
						});
					}
				});

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
								//window.location= '<?php echo site_url('orders/getOldLIMSSerumResultPDF/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/getOldLIMSSerumResultPDF/'.$id.''); ?>';
						});
                    }
                });

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
								//window.location= '<?php echo site_url('orders/getOldLIMSSerumResultPDF/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/getOldLIMSSerumResultPDF/'.$id.''); ?>';
						});
                    }
                });

				html2canvas($("#CreateImageHead"), {
					onrendered: function(canvas) {
						var imgsrc = canvas.toDataURL("image/png");
						$("#newimg").attr('src', imgsrc);
						var dataURL = canvas.toDataURL();
						$.ajax({
							type: "POST",
                            url: "<?php echo site_url('upload_pdf_image_head'); ?>",
                            data: {imgBase64: dataURL, order_id: "<?php echo $id; ?>"},
                            success: function(data) {
								setTimeout(function () {
									$('.loader').hide();
									$('.scroll').css("overflow","scroll");
									window.location= '<?php echo site_url('orders/getOldLIMSSerumResultPDF/'.$id.''); ?>';
								}, 5000);
							},
                        }).done(function(o) {
							//window.location= '<?php echo site_url('orders/getOldLIMSSerumResultPDF/'.$id.''); ?>';
						});
					}
				});
            }
		});
		</script>
	</body>
</html>