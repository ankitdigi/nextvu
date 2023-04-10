<!DOCTYPE html>
<html class="js">
	<head>
		<title>PAX Serum Test Result</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page{margin:0}
		*{margin:0;padding:0;box-sizing:border-box;font-family:'Mark Pro'}
		img{max-width:100%}
		html{scroll-behavior:smooth}
		@font-face{font-family:'Mark Pro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro.woff"); ?>') format("woff");font-weight:400;font-style:normal;font-display:swap}
		@font-face{font-family:'MarkPro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro-Medium.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro-Medium.woff"); ?>') format("woff");font-weight:500;font-style:normal;font-display:swap}
		@font-face{font-family:'Mark Pro';src:url('<?php echo base_url("assets/dist/fonts/MarkPro-Bold.woff2"); ?>') format("woff2"),url('<?php echo base_url("assets/dist/fonts/MarkPro-Bold.woff"); ?>') format("woff");font-weight:700;font-style:normal;font-display:swap}
		body{font-family:'Mark Pro'}
		table{font-family:'calibri'}
		/*table {page-break-inside: avoid;}
		table tr {page-break-inside: avoid;}*/
		div{font-family:'calibri'}
		.header th{text-align:left}
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
		.diets tr th{font-weight:400;font-size:13px;padding:20px 5px 10px;width:20px;border-left:1px solid #9acfdb;border-bottom:3px solid #9acfdb;position:relative;height:85px;text-align:left}
		.diets tr th:first-child{white-space:nowrap;font-weight:700;font-size:18px}
		.diets tr th:first-child,.diets tr td:first-child{border-left:0}
		.diets tr td:first-child{text-align:left}
		.diets tr th .rotate{transform:rotate(270deg);display:block;transform-origin:center bottom;text-align:left;position:absolute;white-space:nowrap;left:2px;bottom:40px;text-align:left}
		.diets tr td{border-left:1px solid #9acfdb;border-bottom:1px solid #9acfdb;font-size:13px;text-align:center;padding:5px}
		</style>
	</head>
	<body bgcolor="#fff">
		<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;/*margin:40px;*/">
			<tr>
				<td valign="middle" width="400" style="padding-left: 40px;padding-right: 20px;padding-top: 40px;">
					
					<img src="assets/images/pax-logo.png" alt="Logo" style="max-height:100px; max-width:300px; border-radius:4px;margin-bottom:60px !important; " />
				</td>
				<td  rowspan="2" valign="middle" style="padding-right: 40px;padding-top: 40px;">
					<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:10px; line-height:15px;font-family:'calibri';">
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
							<?php
							if($order_details['species_name'] == 'Horse'){
								echo '<td style="color:#000000;">'. $this->lang->line('horse') .'</td>';
							}elseif($order_details['species_name'] == 'Cat'){
								echo '<td style="color:#000000;">'. $this->lang->line('cat') .'</td>';
							}else{
								echo '<td style="color:#000000;">'. $this->lang->line('dog') .'</td>';
							}
							?>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
							<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
						</tr>
						<?php if($order_details['vet_user_id'] != '24927'){ ?>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
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
							<th style="color:#1e3743;"><?php echo $this->lang->line('order_number'); ?></th>
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
			<tr>
				<td valign="bottom" width="400" style="padding-left: 40px;padding-right: 20px;padding-top: 60px;">
					<h5 style="font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line('pax_food'); ?></h5>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="10"></td></tr></table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0 40px;">
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:20px;"><?php echo $this->lang->line('Summary_sensitisations'); ?></h4>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="10"></td></tr></table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-left:40px;margin-right:40px;">
			<tr>
				<td width="48%" valign="top" style="width:350px;vertical-align:top;">
					<?php
					$totalGroup = 0;
					foreach ($getAllergenParent as $apkey => $apvalue) {
						if($totalGroup < $partB){
							echo '<table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 14px;">
							<tr>
								<td colspan="2" style="padding:0 0 5px 0;"><h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;">'.$apvalue['pax_name'].'</h5></td>
							</tr>';
							$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
							foreach ($subAllergens as $skey => $svalue) {
								$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
								if(!empty($subVlu->raptor_code)){
									$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
									if(!empty($raptrVlu)){
										if(floor($raptrVlu->result_value) < $cutoffs){
											echo '<tr>
												<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table cellpadding="0" cellspacing="0" align="right" class="meter">
							                            <tr>
							                                <td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif(floor($raptrVlu->result_value) >= $cutoffs && $raptrVlu->result_value < 100){
											echo '<tr>
												<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table cellpadding="0" cellspacing="0" align="right" class="meter">
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif($raptrVlu->result_value >= 100 && $raptrVlu->result_value < 400){
											echo '<tr>
												<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													
													<table cellpadding="0" cellspacing="0" align="right" class="meter">														
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif($raptrVlu->result_value >= 400 && $raptrVlu->result_value < 800){
											echo '<tr>
												<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													

													<table cellpadding="0" cellspacing="0" align="right" class="meter">														
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif($raptrVlu->result_value >= 800){
											echo '<tr>
												<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													

													<table cellpadding="0" cellspacing="0" align="right" class="meter">														
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt="" style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_1.png" alt="" style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_2.png" alt="" style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_3.png" alt="" style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_4.png" alt="" style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
												</td>
											</tr>';
										}
									}else{
										echo '<tr>
											<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
											<td style="padding:0 0 0 15px;">
												<table cellpadding="0" cellspacing="0" align="right" class="meter">
													<tr>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;">0</td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
													</tr>
												</table>
											</td>
										</tr>';
									}
								}else{
									echo '<tr>
										<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
										<td style="padding:0 0 0 15px;">
											<table cellpadding="0" cellspacing="0" align="right" class="meter">
												<tr>
													<td style="width:25px;height:6px;line-height:8px;font-size:12px;">0</td>
													<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
													<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
													<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
													<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
													<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
												</tr>
											</table>
										</td>
									</tr>';
								}
							}
							echo '</table>
							<table><tr><td height="5"></td></tr></table>';
						}
						$totalGroup++;
					}
					?>
				</td>
				<td width="4%" valign="top"></td>
				<td width="48%" valign="top" style="width:350px;vertical-align:top;">
					<?php
					$totalGroups = 0;
					foreach ($getAllergenParent as $apkey => $apvalue) {
						if($totalGroups >= $partB){
							echo '<table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 14px;">
								<tr>
									<td colspan="2" style="padding:0 0 5px 0;"><h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;">'.$apvalue['pax_name'].'</h5></td>
								</tr>';
								$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
								foreach ($subAllergens as $skey => $svalue) {
									$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
									if(!empty($subVlu->raptor_code)){
										$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
										if(!empty($raptrVlu)){
											if(floor($raptrVlu->result_value) < $cutoffs){
												echo '<tr>
													<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table cellpadding="0" cellspacing="0" align="right" class="meter">
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
													</td>
												</tr>';
											}elseif(floor($raptrVlu->result_value) >= $cutoffs && $raptrVlu->result_value < 100){
												echo '<tr>
													<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table cellpadding="0" cellspacing="0" align="right" class="meter">
														
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
													</td>
												</tr>';
											}elseif($raptrVlu->result_value >= 100 && $raptrVlu->result_value < 400){
												echo '<tr>
													<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table cellpadding="0" cellspacing="0" align="right" class="meter">														
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
													</td>
												</tr>';
											}elseif($raptrVlu->result_value >= 400 && $raptrVlu->result_value < 800){
												echo '<tr>
													<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table cellpadding="0" cellspacing="0" align="right" class="meter">														
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
													</td>
												</tr>';
											}elseif($raptrVlu->result_value >= 800){
												echo '<tr>
													<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table cellpadding="0" cellspacing="0" align="right" class="meter">														
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
													</td>
												</tr>';
											}
										}else{
											echo '<tr>
												<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table cellpadding="0" cellspacing="0" align="right" class="meter">
														<tr>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;">0</td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
															<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														</tr>
													</table>
												</td>
											</tr>';
										}
									}else{
										echo '<tr>
											<td style="padding:0 15px 0 0;font-size:12px;">'.$svalue['pax_name'].'</td>
											<td style="padding:0 0 0 15px;">
												<table cellpadding="0" cellspacing="0" align="right" class="meter">
													<tr>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;">0</td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;" class=""><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
														<td style="width:25px;height:6px;line-height:8px;font-size:12px;"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 10px;" height="15" width="23" /></td>
													</tr>
												</table>
											</td>
										</tr>';
									}
								}
							echo '</table>
							<table><tr><td height="5"></td></tr></table>';
						}
						$totalGroups++;
					}
					?>
				</td>
			</tr>
		</table>