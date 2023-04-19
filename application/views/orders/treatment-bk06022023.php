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
			</style>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						Serum Test
						<small>Treatment Advice</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="<?php echo site_url('orders'); ?>"> Orders Management</a></li>
						<li class="active">Treatment Advice</li>
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
												<label>Internal Practice Comment</label>
												<textarea class="form-control internal_practice_comment" name="internal_practice_comment" rows="5" placeholder="Enter Internal Practice Comment"><?php echo !empty($order_details['internal_practice_comment'])?$order_details['internal_practice_comment']:''; ?></textarea>
											</div>
											<div class="form-group">
												<button type="submit" value="next" name="next" class="btn btn-primary mrgnbtm10 next_btn">Save Internal Comments/Message to Pet Parents</button>
												<?php if (isset($order_details['requisition_form']) && $order_details['requisition_form'] != '') { ?>
												<a class="btn btn-primary mrgnbtm10" onclick="window.open('<?php echo base_url() . REQUISITION_FORM_PATH; ?>/<?php echo $order_details['requisition_form']; ?>','Requisition Form','width=1200,height=9000')" title="View Order Requisition"> View Uploaded Order Requisition Form</a>
												<?php } ?>
											</div>
											<div class="form-group">
												<?php if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994' || $order_details['lab_id']=='13788')){ ?>
												<a target="_blank" href="<?php echo site_url('orders/getSerumResultdoc/'.$order_details['id'].''); ?>" class="btn btn-primary" style="padding:5px 10px;margin-bottom: 10px;"> Download Word Document</a><br/>
												<?php }else{ ?>
												<button id="downloadonly" type="button" class="btn btn-primary top">Download / Print test result</button>
												<?php } ?>
											</div>
											<?php if((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))){ ?>
												<div class="form-group">
													<h2 style="margin:0px;font-weight:700;font-size:28px;color:#366784;">Order Recommendations</h2><hr style="margin: 10px 0px;">
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
												<label>Laboratory Comment</label>
												<textarea class="form-control internal_comment" name="internal_comment" rows="5" placeholder="Enter Laboratory Comment"><?php echo $order_details['internal_comment']; ?></textarea>
											</div>
											<div class="form-group">
												<button type="submit" value="next" name="next" class="btn btn-primary mrgnbtm10 next_btn" <?php if($order_details['is_serum_result_sent'] == 1) { echo 'disabled="disabled"'; } ?>>Save Laboratory Comment</button>
												<?php if($order_details['lab_id']>0 && ($order_details['lab_id']=='13401' || $order_details['lab_id']=='13789' || $order_details['lab_id']=='28995' || $order_details['lab_id']=='29164' || $order_details['lab_id']=='28994' || $order_details['lab_id']=='13788')){ ?>
												<a target="_blank" href="<?php echo site_url('orders/getSerumResultdoc/'.$order_details['id'].''); ?>" class="btn btn-primary"> Download Word Document</a>
												<?php }else{ ?>
												<button id="downloadonly" type="button" class="btn btn-primary top">Download Result</button>
												<?php } ?>
												<?php
												$zonesIds = $this->OrdersModel->checkZones($id);
												if(!empty($zonesIds) && in_array("8", $zonesIds)){
												?>
												<a target="_blank" href="<?php echo site_url('orders/getSerumResultExcel/'.$order_details['id'].''); ?>" class="btn btn-primary"> Download Excel Document</a>
												<?php } ?>
												<?php if (isset($order_details['requisition_form']) && $order_details['requisition_form'] != '') { ?>
												<a class="btn btn-primary mrgnbtm10" onclick="window.open('<?php echo base_url() . REQUISITION_FORM_PATH; ?>/<?php echo $order_details['requisition_form']; ?>','Requisition Form','width=1200,height=9000')" title="View Order Requisition"> View Uploaded Order Requisition Form</a>
												<?php } ?>
												<br><br>
												<?php if((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))){ ?>
													<?php if($block1 > 0 && $order_details['is_serum_result_sent'] != 1){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation1Admin" name="recommendation1" value="Edit Immunotherapy Treatment Opt 1">
													<?php } ?>
													<?php if(!empty($block2) && ($option1 != $block2) && $order_details['is_serum_result_sent'] != 1){ ?>
													<input type="button" class="btn btn-primary mrgnbtm10 recommendation2Admin" name="recommendation2" value="Edit Immunotherapy Treatment Opt 2">
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
												<button id="geeks" type="button" class="btn btn-primary top">Save, Send out & Close</button>
											</div>
											<?php if(((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) || (preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name))) && ($this->user_role == '1' || $this->user_role == '11')){ ?>
												<div class="form-group">
													<h2 style="margin:0px;font-weight:700;font-size:28px;color:#366784;">Order Recommendations</h2><hr style="margin: 10px 0px;">
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
															<h4 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;">Medical history</h4>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">Date Serum Drawn:</th>
																		<td style="color:#000000;text-align: left;"><?php echo date("d/m/Y",strtotime($serumdata['serum_drawn_date']));?></td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['major_symptoms']) && ((strpos($serumdata['major_symptoms'], '0' ) !== false) || (strpos($serumdata['major_symptoms'], '1' ) !== false) || (strpos($serumdata['major_symptoms'], '2' ) !== false) || (strpos($serumdata['major_symptoms'], '3' ) !== false) || (strpos($serumdata['major_symptoms'], '4' ) !== false) || (strpos($serumdata['major_symptoms'], '5' ) !== false) || (strpos($serumdata['major_symptoms'], '6' ) !== false) || (strpos($serumdata['major_symptoms'], '7' ) !== false))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;">With which one(s) of the following is the patient affected?:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if(strpos($serumdata['major_symptoms'], '1' ) !== false){ ?>
																			Pruritus (itch), 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '2' ) !== false){ ?>
																			Otitis, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '3' ) !== false){ ?>
																			Respiratory signs, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '4' ) !== false){ ?>
																			Gastro-intestinal signs, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '5' ) !== false){ ?>
																			Skin lesions, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '6' ) !== false){ ?>
																			Ocular signs, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '7' ) !== false){ ?>
																			Anaphylaxis, 
																			<?php } ?>
																			<?php if(strpos($serumdata['major_symptoms'], '0' ) !== false){ ?>
																			<?php echo isset($serumdata['other_symptom']) ? $serumdata['other_symptom'] : ''; ?>
																			<?php } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if($serumdata['symptom_appear_age_month'] != '' || $serumdata['symptom_appear_age'] != ''){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;">At what age did these symptoms first appear:</th>
																		<td style="color:#000000;text-align: left;"><?php echo $serumdata['symptom_appear_age_month'].'/'.$serumdata['symptom_appear_age']; ?></td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['when_obvious_symptoms']) && ((strpos( $serumdata['when_obvious_symptoms'], '1' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '2' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '3' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '4' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '5' ) !== false))){ ?> 
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;">When are the symptoms most obvious?:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '1' ) !== false){ ?>
																			Spring, 
																			<?php } ?>
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '2' ) !== false){ ?>
																			Summer, 
																			<?php } ?>
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '3' ) !== false){ ?>
																			Fall, 
																			<?php } ?>
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '4' ) !== false){ ?>
																			Winter, 
																			<?php } ?>
																			<?php if(strpos($serumdata['when_obvious_symptoms'], '5' ) !== false){ ?>
																			Year-round
																			<?php } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['where_obvious_symptoms']) && ((strpos( $serumdata['where_obvious_symptoms'], '1' ) !== false) || (strpos( $serumdata['where_obvious_symptoms'], '2' ) !== false) || (strpos( $serumdata['where_obvious_symptoms'], '3' ) !== false))){ ?> 
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;">Where are the symptoms most obvious?:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if(strpos($serumdata['where_obvious_symptoms'], '1' ) !== false){ ?>
																			Indoors, 
																			<?php } ?>
																			<?php if(strpos($serumdata['where_obvious_symptoms'], '2' ) !== false){ ?>
																			Outdoors, 
																			<?php } ?>
																			<?php if(strpos($serumdata['where_obvious_symptoms'], '3' ) !== false){ ?>
																			No Difference
																			<?php } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['diagnosis_food']) && ($serumdata['diagnosis_food'] == 1 || $serumdata['diagnosis_food'] == 2)) || (isset($serumdata['diagnosis_hymenoptera']) && ($serumdata['diagnosis_hymenoptera'] == 1 || $serumdata['diagnosis_hymenoptera'] == 2)) || (isset($serumdata['diagnosis_other']) && ($serumdata['diagnosis_other'] == 1 || $serumdata['diagnosis_other'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;">Has there been a clinical diagnosis of allergy to the following?</th>
																		<td style="color:#000000;text-align: left;"></td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['diagnosis_food']) && ($serumdata['diagnosis_food'] == 1 || $serumdata['diagnosis_food'] == 2))){ ?>
																		<tr>
																			<th style="color:#346a7e;text-align: left;width: 60%;">Food(s):</th>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">How fast do signs relapse after a food challenge:</th>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">Hymenoptera stings:</th>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">Other(s):</th>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">Is the patient regularly exposed to the following animals:</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if(strpos($serumdata['regularly_exposed'], '1' ) !== false){ ?>
																			Cats, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '2' ) !== false){ ?>
																			Dogs, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '3' ) !== false){ ?>
																			Horses, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '4' ) !== false){ ?>
																			Cattle, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '5' ) !== false){ ?>
																			Mice, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '6' ) !== false){ ?>
																			Guinea pigs, 
																			<?php } ?>
																			<?php if(strpos($serumdata['regularly_exposed'], '7' ) !== false){ ?>
																			Rabbits, 
																			<?php } ?>
																			<?php if($serumdata['other_exposed'] != ""){ echo $serumdata['other_exposed']; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['malassezia_infections']) && ((strpos( $serumdata['malassezia_infections'], '1' ) !== false) || (strpos( $serumdata['malassezia_infections'], '2' ) !== false))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;">Does the patient suffer from recurrent Malassezia infections?</th>
																		<td style="color:#000000;text-align: left;">
																			<?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '1' ) !== false) ){ echo 'Malassezia otitis, '; } ?>
																			<?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '2' ) !== false) ){ echo 'Malassezia dermatitis'; } ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if(isset($serumdata['receiving_drugs']) && ((strpos($serumdata['receiving_drugs'], '1') !== false) || (strpos($serumdata['receiving_drugs'], '2') !== false) || (strpos($serumdata['receiving_drugs'], '3') !== false) || (strpos($serumdata['receiving_drugs'], '4') !== false) || (strpos($serumdata['receiving_drugs'], '5') !== false) || (strpos($serumdata['receiving_drugs'], '6') !== false))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;">Is the patient receiving the following drugs and what was the response to treatment?</th>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">Did the patient receive or is receiving treatment against ectoparasites?</th>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">Has an elimination food trial been performed with a strict elimination diet?</th>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">Any additional relevant information (e.g., other known triggers of allergy signs)?</th>
																		<td style="color:#000000;text-align: left;">
																			<?php echo $serumdata['additional_information']; ?>
																		</td>
																	</tr>
																	<?php } ?>
																	<?php if((isset($serumdata['zoonotic_disease']) && ($serumdata['zoonotic_disease'] == 1 || $serumdata['zoonotic_disease'] == 2))){ ?>
																	<tr style="height: 30px;">
																		<th style="color:#346a7e;text-align: left;width: 60%;">Is this animal suffering from a zoonotic disease?</th>
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
																		<th style="color:#346a7e;text-align: left;width: 60%;">Is the animal receiving any medication at the moment?</th>
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
														<img src="<?php echo base_url("/assets/images/header-cat.png"); ?>" height="130" alt="" />
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
								<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="1030px" style="width:100%;background:#ffffff;">
									<tr>
										<td>
											<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url("/assets/images/next-header.jpg"); ?>) left center no-repeat; background-size:cover;">
												<tr>
													<td valign="middle" width="50%" style="padding:60px 30px 60px 50px;">
														<img src="<?php echo base_url("/assets/images/nextlab-logo.jpg"); ?>" alt="NEXT+ Logo" style="max-height:100px; max-width:280px; border-radius:4px;" />
														<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;">Serum Test results</h5>
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
																<th style="color:#346a7e;">Laboratory number:</th>
																<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
															</tr>
															<tr>
																<th style="color:#346a7e;">Order number:</th>
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
													<div id="CreateImage3">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																			<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;">IgE / IgG</th>
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
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
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
																												$resultperIge = $rsultFIge->result;
																												if($resultperIge > 330){
																													$resultperIge = 330;
																												}else{
																													$resultperIge = $resultperIge;
																												}
																												if($rsultFIge->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 99){
																													echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result <= 200 && $rsultFIge->result >= 100){
																													echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result >= 201){
																													echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
																											}

																											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIgg)){
																												$resultperIgg = $rsultFIgg->result;
																												if($resultperIgg > 330){
																													$resultperIgg = 330;
																												}else{
																													$resultperIgg = $resultperIgg;
																												}
																												if($rsultFIgg->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 99){
																													echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result <= 200 && $rsultFIgg->result >= 100){
																													echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result >= 201){
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
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;display:none;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 99</strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 99 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>100-200</strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 201</strong></p>
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
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
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
												<?php
												}elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){
												?>
													<table width="100%"><tr><td height="10"></td></tr></table>
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
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
																				<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																				<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;">EA Units*</th>
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
																				<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																			</tr>
																		</table>
																		<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																			<tr>
																				<td>
																					<table cellpadding="0" cellspacing="0" width="100%" border="0">
																						<tr>
																							<th height="35" width="30%"></th>
																							<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																							<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
																							<th></th>
																						</tr>
																						<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																							<td>
																								<ul class="bargraph" style="margin-top:15px;">
																									<?php
																									$resultper = 0;
																									foreach($optnenvArr as $row2){
																										$resultper = $row2['result'];
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] <= 99){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 99 && $row2['result'] <= 200){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 200){
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
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Moulds</th>
																			<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;">EA Units*</th>
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
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: left;">1200</th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: center;">1500</th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="bargraph" style="margin-top:15px;">
																								<?php
																								if(!empty($moduleArr)){
																									$resultper = 0;
																									foreach($moduleArr as $row2){
																										if($row2['result'] <= 1199){
																											$resultper = $row2['result']/12;
																										}elseif($row2['result'] >= 1299 && $row2['result'] <= 1200){
																											$resultper = $row2['result']/10;
																										}elseif($row2['result'] >= 1399 && $row2['result'] <= 1300){
																											$resultper = $row2['result']/9;
																										}elseif($row2['result'] >= 1500 && $row2['result'] <= 1400){
																											$resultper = $row2['result']/7.5;
																										}elseif($row2['result'] > 1500){
																											$resultper = $row2['result']/7;
																										}
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] > 0 && $row2['result'] <= 1199){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] >= 1200 && $row2['result'] <= 1500){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 1500){
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
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;display:none;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 99</strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 99 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>100-200</strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 201</strong></p>
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
												<?php }elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name)){ ?>
													<div id="CreateImage3">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																			<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;">IgE / IgG</th>
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
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
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
																												$resultperIge = $rsultFIge->result;
																												if($resultperIge > 330){
																													$resultperIge = 330;
																												}else{
																													$resultperIge = $resultperIge;
																												}
																												if($rsultFIge->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 99){
																													echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 99 && $rsultFIge->result <= 200){
																													echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 200){
																													echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
																											}

																											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIgg)){
																												$resultperIgg = $rsultFIgg->result;
																												if($resultperIgg > 330){
																													$resultperIgg = 330;
																												}else{
																													$resultperIgg = $resultperIgg;
																												}
																												if($rsultFIgg->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 99){
																													echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 99 && $rsultFIgg->result <= 200){
																													echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 200){
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
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;display:none;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 99</strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 99 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>100-200</strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 201</strong></p>
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
													if(!empty($optnenvArr)){
													?>
														<div id="CreateImage">
															<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
																<tr>
																	<td>
																		<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																			<tr>
																				<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																				<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;">EA Units*</th>
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
																				<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																			</tr>
																		</table>
																		<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																			<tr>
																				<td>
																					<table cellpadding="0" cellspacing="0" width="100%" border="0">
																						<tr>
																							<th height="35" width="30%"></th>
																							<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																							<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
																							<th></th>
																						</tr>
																						<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																							<td>
																								<ul class="bargraph" style="margin-top:15px;">
																									<?php
																									$resultper = 0;
																									foreach($optnenvArr as $row2){
																										$resultper = $row2['result'];
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] <= 99){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 99 && $row2['result'] <= 200){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 200){
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
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Moulds</th>
																			<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;">EA Units*</th>
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
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: left;">1200</th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: center;">1500</th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="bargraph" style="margin-top:15px;">
																								<?php
																								if(!empty($moduleArr)){
																									$resultper = 0;
																									foreach($moduleArr as $row2){
																										if($row2['result'] <= 1199){
																											$resultper = $row2['result']/12;
																										}elseif($row2['result'] >= 1299 && $row2['result'] <= 1200){
																											$resultper = $row2['result']/10;
																										}elseif($row2['result'] >= 1399 && $row2['result'] <= 1300){
																											$resultper = $row2['result']/9;
																										}elseif($row2['result'] >= 1500 && $row2['result'] <= 1400){
																											$resultper = $row2['result']/7.5;
																										}elseif($row2['result'] > 1500){
																											$resultper = $row2['result']/7;
																										}
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] > 0 && $row2['result'] <= 1199){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] >= 1200 && $row2['result'] <= 1500){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 1500){
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
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																			<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;">IgE / IgG</th>
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
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
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
																												$resultperIge = $rsultFIge->result;
																												if($resultperIge > 330){
																													$resultperIge = 330;
																												}else{
																													$resultperIge = $resultperIge;
																												}
																												if($rsultFIge->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 99){
																													echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 99 && $rsultFIge->result <= 200){
																													echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 200){
																													echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
																											}

																											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIgg)){
																												$resultperIgg = $rsultFIgg->result;
																												if($resultperIgg > 330){
																													$resultperIgg = 330;
																												}else{
																													$resultperIgg = $resultperIgg;
																												}
																												if($rsultFIgg->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 99){
																													echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 99 && $rsultFIgg->result <= 200){
																													echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 200){
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
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;display:none;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 99</strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 99 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>100-200</strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 201</strong></p>
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
													if(!empty($optnenvArr)){
													?>
														<div id="CreateImage">
															<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
																<tr>
																	<td>
																		<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																			<tr>
																				<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																				<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;">EA Units*</th>
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
																				<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																			</tr>
																		</table>
																		<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																			<tr>
																				<td>
																					<table cellpadding="0" cellspacing="0" width="100%" border="0">
																						<tr>
																							<th height="35" width="30%"></th>
																							<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																							<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
																							<th></th>
																						</tr>
																						<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																							<td>
																								<ul class="bargraph" style="margin-top:15px;">
																									<?php
																									$resultper = 0;
																									foreach($optnenvArr as $row2){
																										$resultper = $row2['result'];
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] <= 99){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 99 && $row2['result'] <= 200){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 200){
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
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Moulds</th>
																			<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;">EA Units*</th>
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
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: left;">1200</th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: center;">1500</th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="bargraph" style="margin-top:15px;">
																								<?php
																								if(!empty($moduleArr)){
																									$resultper = 0;
																									foreach($moduleArr as $row2){
																										if($row2['result'] <= 1199){
																											$resultper = $row2['result']/12;
																										}elseif($row2['result'] >= 1299 && $row2['result'] <= 1200){
																											$resultper = $row2['result']/10;
																										}elseif($row2['result'] >= 1399 && $row2['result'] <= 1300){
																											$resultper = $row2['result']/9;
																										}elseif($row2['result'] >= 1500 && $row2['result'] <= 1400){
																											$resultper = $row2['result']/7.5;
																										}elseif($row2['result'] > 1500){
																											$resultper = $row2['result']/7;
																										}
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] > 0 && $row2['result'] <= 1199){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] >= 1200 && $row2['result'] <= 1500){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 1500){
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
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;display:none;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 99</strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 99 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>100-200</strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 201</strong></p>
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
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
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
												<?php
												}elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
												?>
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
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
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
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
																				<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																				<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;">EA Units*</th>
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
																				<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																			</tr>
																		</table>
																		<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																			<tr>
																				<td>
																					<table cellpadding="0" cellspacing="0" width="100%" border="0">
																						<tr>
																							<th height="35" width="30%"></th>
																							<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																							<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
																							<th></th>
																						</tr>
																						<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																							<td>
																								<ul class="bargraph" style="margin-top:15px;">
																									<?php
																									$resultper = 0;
																									foreach($optnenvArr as $row2){
																										$resultper = $row2['result'];
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] <= 99){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 99 && $row2['result'] <= 200){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 200){
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
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Moulds</th>
																			<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align:right;">EA Units*</th>
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
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: left;">1200</th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: center;">1500</th>
																						<th></th>
																					</tr>
																					<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
																						<td>
																							<ul class="bargraph" style="margin-top:15px;">
																								<?php
																								if(!empty($moduleArr)){
																									$resultper = 0;
																									foreach($moduleArr as $row2){
																										if($row2['result'] <= 1199){
																											$resultper = $row2['result']/12;
																										}elseif($row2['result'] >= 1299 && $row2['result'] <= 1200){
																											$resultper = $row2['result']/10;
																										}elseif($row2['result'] >= 1399 && $row2['result'] <= 1300){
																											$resultper = $row2['result']/9;
																										}elseif($row2['result'] >= 1500 && $row2['result'] <= 1400){
																											$resultper = $row2['result']/7.5;
																										}elseif($row2['result'] > 1500){
																											$resultper = $row2['result']/7;
																										}
																										if($resultper > 330){
																											$resultper = 330;
																										}else{
																											$resultper = $resultper;
																										}
																										if($row2['result'] > 0 && $row2['result'] <= 1199){
																											echo '<li style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] >= 1200 && $row2['result'] <= 1500){
																											echo '<li class="grey" style="width:'. $resultper .'%;" class=""><span></span></li>';
																										}elseif($row2['result'] > 1500){
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
																					<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																					<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;">IgE / IgG</th>
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
																					<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																				</tr>
																			</table>
																			<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																				<tr>
																					<td>
																						<table cellpadding="0" cellspacing="0" width="100%" border="0">
																							<tr>
																								<th height="35" width="30%"></th>
																								<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																								<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
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
																														$resultperIge = $rsultFIge->result;
																														if($resultperIge > 330){
																															$resultperIge = 330;
																														}else{
																															$resultperIge = $resultperIge;
																														}
																														if($rsultFIge->result == 0){
																															echo '<li style="width:0%;" class=""><span></span></li>';
																														}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 99){
																															echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																														}elseif($rsultFIge->result > 99 && $rsultFIge->result <= 200){
																															echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																														}elseif($rsultFIge->result > 200){
																															echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																														}
																													}else{
																														echo '<li style="width:0%;" class=""><span></span></li>';
																													}

																													$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																													if(!empty($rsultFIgg)){
																														$resultperIgg = $rsultFIgg->result;
																														if($resultperIgg > 330){
																															$resultperIgg = 330;
																														}else{
																															$resultperIgg = $resultperIgg;
																														}
																														if($rsultFIgg->result == 0){
																															echo '<li style="width:0%;" class=""><span></span></li>';
																														}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 99){
																															echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																														}elseif($rsultFIgg->result > 99 && $rsultFIgg->result <= 200){
																															echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																														}elseif($rsultFIgg->result > 200){
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
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;display:none;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 99</strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 99 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>100-200</strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 201</strong></p>
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
												}elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){
												?>
													<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 98%;margin-left: 15px;">
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
													<div id="CreateImage3">
														<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
															<tr>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="45%" align="left">
																		<tr>
																			<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
																			<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;text-align: right;">IgE / IgG</th>
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
																			<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
																		</tr>
																	</table>
																	<table cellpadding="0" cellspacing="0" border="0" width="55%" align="left">
																		<tr>
																			<td>
																				<table cellpadding="0" cellspacing="0" width="100%" border="0">
																					<tr>
																						<th height="35" width="30%"></th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: left;">100</th>
																						<th width="20%" style="color:#326883; font-size:15px;text-align: center;">200</th>
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
																												$resultperIge = $rsultFIge->result;
																												if($resultperIge > 330){
																													$resultperIge = 330;
																												}else{
																													$resultperIge = $resultperIge;
																												}
																												if($rsultFIge->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 0 && $rsultFIge->result <= 99){
																													echo '<li style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 99 && $rsultFIge->result <= 200){
																													echo '<li class="grey" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}elseif($rsultFIge->result > 200){
																													echo '<li class="red" style="width:'. $resultperIge .'%;" class=""><span></span></li>';
																												}
																											}else{
																												echo '<li style="width:0%;" class=""><span></span></li>';
																											}

																											$rsultFIgg = $this->OrdersModel->getSerumTestResultFoodIGG($row1->name,$sresultID,$stypeID);
																											if(!empty($rsultFIgg)){
																												$resultperIgg = $rsultFIgg->result;
																												if($resultperIgg > 330){
																													$resultperIgg = 330;
																												}else{
																													$resultperIgg = $resultperIgg;
																												}
																												if($rsultFIgg->result == 0){
																													echo '<li style="width:0%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 0 && $rsultFIgg->result <= 99){
																													echo '<li style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 99 && $rsultFIgg->result <= 200){
																													echo '<li class="grey" style="width:'. $resultperIgg .'%;" class=""><span></span></li>';
																												}elseif($rsultFIgg->result > 200){
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
													<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;display:none;">
														<tr>
															<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>&lt; 99</strong> </p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 99 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
															</td>
															<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>100-200</strong></p>
																<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
															</td>
															<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
																<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 201</strong></p>
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
																<p style="margin:0; color:#333333; font-size:13px;">Laboratory Number <?php echo $order_details['lab_order_number'];?> - Nextvu Order Number <?php echo $order_details['order_number'];?></p>
															</td>
														</tr>
													</table>
													<table width="100%"><tr><td height="20"></td></tr></table>
													<table cellspacing="0" cellpadding="0" border="0" width="30%" align="left" style="margin-left:30px;min-width:30%;">
														<tr>
															<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?> <input type="radio" name="treatment" id="treatment1" value="1" /><?php } ?> Treatment option 1</th>
														</tr>
														<tr>
															<td bgcolor="#e2f2f4" style="padding:20px;">
																<p style="color:#184359; font-size:13px; margin:0; padding:0;">This treatment option contains indivi- dual allergens only. Most dermatologists recommend to include individual allergens in the treatment.</p>
																<ol style="color:#184359; font-size:13px; margin:15px 0 0 20px; padding:0;">
																	<?php 
																	$a=0;
																	foreach($option1 as $key=>$value){
																		?>
																		<li style="margin-bottom: 5px;">
																			<input type="hidden" name="allergens1[]" value="<?php echo $key; ?>"><?php echo $value; ?>
																		</li>
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
															<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
																<table cellpadding="0" cellspacing="0" border="0" width="100%;">
																	<tr>
																		<td height="20"></td>
																	</tr>
																	<tr>
																		<td colspan="3" align="left" style="color:#303846;">This option results in:</td>
																	</tr>
																	<tr>
																		<td width="30%"><input type="text" value="<?php echo $totalViald; ?>" style="background:#e4eaed; padding:0 10px; height:40px; border:1px solid #4d5d67; width:60px;color: #000;" <?php if(empty($option1)){ echo 'disabled="disabled"'; } ?> /></td>
																		<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;">Subcutaneous <br>immunotherapy </td>
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
															<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?> <input type="radio" name="treatment" id="treatment2" value="2" <?php if(empty($block2)){ echo 'disabled="disabled"'; } ?> /><?php } ?> &nbsp; Treatment option 2</th>
														</tr>
														<tr>
															<td bgcolor="#e2f2f4" style="padding:20px;">
																<p style="color:#184359; font-size:13px; margin:0; padding:0;">When costs or compliance influence the treatment, pollen mixtures can be an alternative to treatment option 1.</p>
																<ol style="color:#184359; font-size:13px; margin:15px 0 0 20px; padding:0;">
																	<?php 
																	$b=0; $totalViald = 0;
																	foreach($block2 as $key=>$value){ ?>
																		<li style="margin-bottom: 5px;">
																			<input type="hidden" name="allergens2[]" value="<?php echo $key; ?>"><?php echo $value; ?>
																		</li>
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
															<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
																<table cellpadding="0" cellspacing="0" border="0" width="100%;">
																	<tr><td height="20"></td></tr>
																	<tr>
																		<th colspan="3" align="left" style="color:#303846;">This option results in:</th>
																	</tr>
																	<tr>
																		<td width="30%"><input type="text" value="<?php echo $totalViald; ?>" style="background:#e4eaed; padding:0 10px; height:40px; border:1px solid #4d5d67; width:60px;color: #000;" <?php if(empty($block2)){ echo 'disabled="disabled"'; } ?> /></td>
																		<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;">Subcutaneous <br>immuno therapy </td>
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
															<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?> <input type="radio" name="treatment" id="treatment3" value="3" /><?php } ?> &nbsp; Compose it yourself</th>
														</tr>
														<tr>
															<td bgcolor="#e2f2f4" style="padding:20px;">
																<p style="color:#184359; font-size:13px; margin:0; padding:0;">Prefer to compose your own therapy based on the clinical history? Please enter the desired allergens below.</p>
																<textarea style="resize:none; background:#e4eaed; padding:10px; height:400px; border:1px solid #4d5d67; width:70px; width:100%; box-sizing:border-box; margin:20px 0 0 0; outline:none;"></textarea>
															</td>
														</tr>
														<tr>
															<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
																<table cellpadding="0" cellspacing="0" border="0" width="100%;">
																	<tr><td height="20"></td></tr>
																	<tr>
																		<td><p style="color:#184359; font-size:13px; margin:0; padding:0;">Artuvetrin® subcutaneous immunotherapy: up to 8 allergens and/or allergen mixtures can be included into 1 vial. For cases with more than 8 allergens, additional vial(s) will be produced.</p></td>
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
									<table width="100%"><tr><td height="20"></td></tr></table>
									<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding:0; background:#ffffff;">
										<tr>
											<td>
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
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">1.0 ml</td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">4 weeks later (week 17)</td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">1.0 ml</td>
																			</tr>
																			<tr bgcolor="#326883">
																				<td bgcolor="#d1dbe5" style="padding:0 0 0 20px; font-size:13px; color:#1f4964;">4 weeks later (week 21)</td>
																				<td bgcolor="#c4d0dd" height="25" style="padding:0 20px 0 20px; font-size:13px; color:#1f4964;">1.0 ml</td>
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
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">Yes, however this depends on the situation. Please contact our medical department at 01494 629979 for advice and support. </p></td>
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
																	<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;">If the patient did not show any improvement at all after 12 months, please contact our medical department on 01494 629979. There can be several reasons for a 0% response: concomitant food allergy, reaction to new allergens or not effective. We are happy to evaluate each case and help you with the relevant follow up.</p></td>
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
																	all after 6 months, please contact us at 01494 629979. We
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
															<p style="margin:0 0 0 0; padding:0; color:#326883; font-size:13px;">Please call our medical department on 01494 629979 or send an email to vetorders.uk@nextmune.com.</p>
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
											</td>
										</tr>
									</table>
								<?php } ?>
								<table width="100%"><tr><td height="20"></td></tr></table>
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
								//window.location= '<?php echo site_url('orders/authorisedConfirmed/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/authorisedConfirmed/'.$id.''); ?>';
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
								//window.location= '<?php echo site_url('orders/authorisedConfirmed/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/authorisedConfirmed/'.$id.''); ?>';
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
								//window.location= '<?php echo site_url('orders/authorisedConfirmed/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/authorisedConfirmed/'.$id.''); ?>';
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
								$('.loader').hide();
								$('.scroll').css("overflow","scroll");
								window.location= '<?php echo site_url('orders/authorisedConfirmed/'.$id.''); ?>';
							},
                        }).done(function(o) {
							//window.location= '<?php echo site_url('orders/authorisedConfirmed/'.$id.''); ?>';
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
								//window.location= '<?php echo site_url('orders/getLIMSSerumResultPDF/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/getLIMSSerumResultPDF/'.$id.''); ?>';
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
								//window.location= '<?php echo site_url('orders/getLIMSSerumResultPDF/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/getLIMSSerumResultPDF/'.$id.''); ?>';
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
								//window.location= '<?php echo site_url('orders/getLIMSSerumResultPDF/'.$id.''); ?>';
							},
						}).done(function(o) {
							//window.location= '<?php echo site_url('orders/getLIMSSerumResultPDF/'.$id.''); ?>';
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
								$('.loader').hide();
								$('.scroll').css("overflow","scroll");
								window.location= '<?php echo site_url('orders/getLIMSSerumResultPDF/'.$id.''); ?>';
							},
                        }).done(function(o) {
							//window.location= '<?php echo site_url('orders/getLIMSSerumResultPDF/'.$id.''); ?>';
						});
					}
				});
            }
		});
		</script>
	</body>
</html>