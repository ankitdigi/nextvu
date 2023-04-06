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
		.index-capsule{width:140px;height:24px;border-radius:40px}
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
		</style>
	</head>
	<body bgcolor="#fff">
		<?php
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
		if(!empty($order_details['product_code_selection'])){
			$this->db->select('id, name');
			$this->db->from('ci_price');
			$this->db->where('id', $order_details['product_code_selection']);
			$respned = $this->db->get()->row();
			$ordeType = $respned->name;
			$ordeTypeID = $respned->id;
		}else{
			$ordeType = 'Serum Testing';
			$ordeTypeID = 0;
		}

		$getAllergenParent = $this->AllergensModel->getAllergenParentbyName($order_details['allergens']);
		$totalGroup0 = count($getAllergenParent);
		$totalGroup2 = $totalGroup0/2;
		$partA = ((round)($totalGroup2));
		$partB = $partA;

		$block1 = [];
		$subAllergnArr = $this->AllergensModel->getAllergensByID($order_details['allergens']);
		if(!empty($subAllergnArr)){
			foreach ($subAllergnArr as $svalue){
				$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
				if(!empty($subVlu->raptor_code)){
					$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
					if(!empty($raptrVlu)){
						if($raptrVlu->result_value >= 30){
							if($svalue['name'] != "N/A"){
								$block1[$svalue['id']] = $svalue['name'];
							}
						}
					}
				}
			}
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
		?>
		<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;margin:5mm;">
			<tr>
				<td valign="middle" width="430" style="padding:60px 30px 20px 0;">
					<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="Logo" style="max-height:130px; max-width:360px; border-radius:4px;" />
					<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $ordeType;?></h5>
				</td>
				<td valign="middle">
					<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
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
							<td style="color:#000000;"><?php echo $order_details['species_name'];?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Veterinarian'); ?>:</th>
							<td style="color:#000000;"><?php echo $order_details['name']; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
							<td style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Address'); ?>:</th>
							<td style="color:#000000;"><?php echo $fulladdress; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Phone_Fax'); ?>:</th>
							<td style="color:#000000;"><?php echo $order_details['phone_number']; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Email'); ?>:</th>
							<td style="color:#000000;"><?php echo $order_details['email']; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Test_type'); ?>:</th>
							<td style="color:#000000;"><?php echo $ordeType; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
							<td style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('QR_Code'); ?>:</th>
							<td style="color:#000000;"><?php echo $raptorData->barcode; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Test_number'); ?>:</th>
							<td style="color:#000000;"><?php echo $raptorData->sample_code; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Customer_number'); ?>:</th>
							<td style="color:#000000;"><?php echo $order_details['order_number']; ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('Lab'); ?></th>
							<td style="color:#000000;"><?php echo $order_details['lab_order_number']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="20"></td></tr></table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;">
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('Summary_sensitisations'); ?></h4>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="20"></td></tr></table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;">
			<tr>
				<td width="48%" valign="top" style="width:350px;vertical-align:top;">
					<?php
					$totalGroup = 0;
					foreach ($getAllergenParent as $apkey => $apvalue) {
						if($totalGroup < $partB){
							echo '<table cellpadding="0" cellspacing="0" border="0" width="100%;">
							<tr>
								<td colspan="2" style="padding:0 0 5px 0;"><h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;">'.$apvalue['pax_name'].'</h5></td>
							</tr>';
							$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
							foreach ($subAllergens as $skey => $svalue) {
								$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
								if(!empty($subVlu->raptor_code)){
									$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
									if(!empty($raptrVlu)){
										if($raptrVlu->result_value < 30){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif($raptrVlu->result_value >= 30 && $raptrVlu->result_value < 100){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif($raptrVlu->result_value >= 100 && $raptrVlu->result_value < 400){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif($raptrVlu->result_value >= 400 && $raptrVlu->result_value < 800){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob4"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif($raptrVlu->result_value >= 800){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob4"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob5"></td>
														</tr>
													</table>
												</td>
											</tr>';
										}
									}else{
										echo '<tr>
											<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
											<td style="padding:0 0 0 15px;">
												<table align="right" class="meter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>
										</tr>';
									}
								}else{
									echo '<tr>
										<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
										<td style="padding:0 0 0 15px;">
											<table align="right" class="meter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;"></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
												</tr>
											</table>
										</td>
									</tr>';
								}
							}
							echo '</table>
							<table><tr><td height="30"></td></tr></table>';
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
							echo '<table cellpadding="0" cellspacing="0" border="0" width="100%;">
								<tr>
									<td colspan="2" style="padding:0 0 5px 0;"><h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;">'.$apvalue['pax_name'].'</h5></td>
								</tr>';
								$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
								foreach ($subAllergens as $skey => $svalue) {
									$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
									if(!empty($subVlu->raptor_code)){
										$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
										if(!empty($raptrVlu)){
											if($raptrVlu->result_value < 30){
												echo '<tr>
													<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table align="right" class="meter">
															<tr>
																<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															</tr>
														</table>
													</td>
												</tr>';
											}elseif($raptrVlu->result_value >= 30 && $raptrVlu->result_value < 100){
												echo '<tr>
													<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table align="right" class="meter">
															<tr>
																<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															</tr>
														</table>
													</td>
												</tr>';
											}elseif($raptrVlu->result_value >= 100 && $raptrVlu->result_value < 400){
												echo '<tr>
													<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table align="right" class="meter">
															<tr>
																<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															</tr>
														</table>
													</td>
												</tr>';
											}elseif($raptrVlu->result_value >= 400 && $raptrVlu->result_value < 800){
												echo '<tr>
													<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table align="right" class="meter">
															<tr>
																<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob4"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															</tr>
														</table>
													</td>
												</tr>';
											}elseif($raptrVlu->result_value >= 800){
												echo '<tr>
													<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
													<td style="padding:0 0 0 15px;">
														<table align="right" class="meter">
															<tr>
																<td style="width:25px;height:12px;line-height:12px;">'.round($raptrVlu->result_value).'</td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob4"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="blob5"></td>
															</tr>
														</table>
													</td>
												</tr>';
											}
										}else{
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>
											</tr>';
										}
									}else{
										echo '<tr>
											<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
											<td style="padding:0 0 0 15px;">
												<table align="right" class="meter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>
										</tr>';
									}
								}
							echo '</table>
							<table><tr><td height="30"></td></tr></table>';
						}
						$totalGroups++;
					}
					?>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="40"></td></tr></table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;">
			<tr>
				<td>
					<p style="margin:0 0 10px 0; color:#2a5b74; font-size:14px;"><?php echo $this->lang->line('result_note1'); ?></p>
					<h4 style="margin:0; color:#2a5b74; font-size:16px;"><?php echo $this->lang->line('result_note2'); ?></h4>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="10"></td></tr></table>
		
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;">
			<tr>
				<td style="padding:0 15px 0 0;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:14px;">&lt; 30.00 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
						<tr><td class="blob1 index-capsule"></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 0</td></tr>
					</table>
				</td>
				<td style="padding:0 15px 0 15px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:14px;"> 30.00-99.99 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
						<tr><td class="blob2 index-capsule"></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 1</td></tr>
					</table>
				</td>
				<td style="padding:0 15px 0 15px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:14px;">100.00-399.99 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
						<tr><td class="blob3 index-capsule"></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 2</td></tr>
					</table>
				</td>
				<td style="padding:0 15px 0 15px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:14px;">400.00-799.99 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
						<tr><td class="blob4 index-capsule"></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 3</td></tr>
					</table>
				</td>
				<td style="padding:0 0 0 15px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:14px;">&#8805; 800.00 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
						<tr><td class="blob5 index-capsule"></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 4</td></tr>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="10"></td></tr></table>

		<?php if(empty($block1)){ ?>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;">
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('negative_result_title'); ?></h4>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="3"></td></tr></table>

		<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="75%" style="width:75%; border:1px solid #89ccd6; border-radius:0 12px 12px 12px; padding:15px;">
			<tr>
				<td>
					<h6 style="color:#1e3743; font-size:18px; margin:0;"><?php echo $this->lang->line('negative_result_title1'); ?></h6>
					<p style="color:#1e3743; font-size:13px; margin:4px 0 0 0;"><?php echo $this->lang->line('result_note3'); ?></p>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="60"></td></tr></table>

		<table width="100%"><tr><td><h6 style="color:#2a5b74; font-weight:400; font-size:18px; margin:0;"><?php echo $this->lang->line('faq_title'); ?></h6></td></tr></table>
		<table width="100%"><tr><td height="10"></td></tr></table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;">
			<tr>
				<td width="47%" valign="top">
					<table cellpadding="0" cellspacing="0" border="0" align="left" width="100%;">
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('negative_q1'); ?></h6></td>
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('negative_a1'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('negative_q2'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('negative_a2'); ?></p></td>
						</tr>
					</table>
				</td>
				<td width="6%" valign="top"></td>
				<td width="47%" valign="top">
					<table cellpadding="0" cellspacing="0" border="0" align="left" width="100%;">
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('negative_q3'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('negative_a3'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('negative_q4'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('negative_a4'); ?></p></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php } ?>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
			<tr>
				<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $order_details['pet_name']; ?> </b> <?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $order_details['order_number']; ?></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b> <?php echo $raptorData->sample_code; ?></th>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
			<tr>
				<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px 10px 10px 30px; border-radius:30px 0 0 30px;"><?php echo $this->lang->line('Common_Name'); ?></th>
				<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;"><i style="font-weight:400;"><?php echo $this->lang->line('Scientific_name'); ?></i></th>
				<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;"><?php echo $this->lang->line('EM_Allergen'); ?></th>
				<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;"><?php echo $this->lang->line('function'); ?></th>
				<th align="right" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px 30px 10px 10px; border-radius:0 30px 30px 0"><?php echo $this->lang->line('ng_mL'); ?></th>
			</tr>
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:20px;text-transform:uppercase;"><?php echo $ordeType;?> <?php echo $this->lang->line('PANEL'); ?></h4>
				</td>
			</tr>
		</table>

		<?php
		foreach ($getAllergenParent as $apkey => $apvalue){
			echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
				<tr>
					<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:18px; padding:0 10px 10px 30px;">'.$apvalue['pax_name'].'</th>					
				</tr>
			</table>
			<hr style="border-top: 2px solid #3a6a86;margin: 0px;">';
			$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
			if(!empty($subAllergndArr)){
				foreach ($subAllergndArr as $rpvalue){
					$subpVluArr = $this->OrdersModel->getsubAllergensforPanel($rpvalue['id'],$raptorData->result_id);
					if(!empty($subpVluArr)){
						$a=0;
						foreach ($subpVluArr as $srow){
							if($a==0){
								echo '<table style="width:750px;margin:0px;padding:0px;border:none">
									<tr>
										<td style="width:175px;">
											<table style="width:175px;">
												<tr><td align="center" style="font-size:12px;">'.$rpvalue['pax_name'].'</td></tr>
											</table>
										</td>
										<td style="width:175px;">
											<table style="width:175px;">
												<tr><td align="center" style="font-size:12px;">'.$rpvalue['pax_latin_name'].'</td></tr>
											</table>
										</td>
										<td style="width:130px">
											<table style="width:130px">';
												if($srow->em_allergen == 2){
													echo '<tr><td align="center" style="font-size:12px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
												}else{
													echo '<tr><td align="center" style="font-size:12px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
												}
											echo '</table>
										</td>
										<td style="width:120px">
											<table style="width:120px">
												<tr><td align="center" style="font-size:12px;">'.$srow->raptor_function.'</td></tr>
											</table>
										</td>';
										if($srow->result_value < 30){
											echo '<td style="width:110px">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>';
										}elseif($srow->result_value >= 30 && $srow->result_value < 100){
											echo '<td style="width:110px">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>';
										}elseif($srow->result_value >= 100 && $srow->result_value < 400){
											echo '<td style="width:110px">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>';
										}elseif($srow->result_value >= 400 && $srow->result_value < 800){
											echo '<td style="width:110px">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob4"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>';
										}elseif($srow->result_value >= 800){
											echo '<td style="width:110px">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob4"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob5"></td>
													</tr>
												</table>
											</td>';
										}
										echo '<td style="width:50px">
											<table style="width:50px">
												<tr><td align="center">'.round($srow->result_value).'</td></tr>
											</table>
										</td>
									</tr>
								</table>';
							}else{
								echo '<table style="width:750px;margin:0px;padding:0px;border:none">
									<tr>
										<td style="width:175px;">
											<table style="width:175px;">
												<tr><td align="center" style="font-size:12px;">&nbsp;</td></tr>
											</table>
										</td>
										<td style="width:175px;">
											<table style="width:175px;">
												<tr><td align="center" style="font-size:12px;">&nbsp;</td></tr>
											</table>
										</td>
										<td style="width:130px;border-top: 1px solid #3a6a86">
											<table style="width:130px;">';
												if($srow->em_allergen == 2){
													echo '<tr><td align="center" style="font-size:12px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
												}else{
													echo '<tr><td align="center" style="font-size:12px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
												}
											echo '</table>
										</td>
										<td style="width:120px;border-top: 1px solid #3a6a86">
											<table style="width:120px;">
												<tr><td align="center" style="font-size:12px;">'.$srow->raptor_function.'</td></tr>
											</table>
										</td>';
										if($srow->result_value < 30){
											echo '<td style="width:110px;border-top: 1px solid #3a6a86;">
												<table style="width:110px;" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>';
										}elseif($srow->result_value >= 30 && $srow->result_value < 100){
											echo '<td style="width:110px;border-top: 1px solid #3a6a86;">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>';
										}elseif($srow->result_value >= 100 && $srow->result_value < 400){
											echo '<td style="width:110px;border-top: 1px solid #3a6a86;">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>';
										}elseif($srow->result_value >= 400 && $srow->result_value < 800){
											echo '<td style="width:110px;border-top: 1px solid #3a6a86;">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob4"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>';
										}elseif($srow->result_value >= 800){
											echo '<td style="width:110px;border-top: 1px solid #3a6a86;">
												<table style="width:110px" class="panelmeter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob2"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob3"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob4"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob5"></td>
													</tr>
												</table>
											</td>';
										}
										echo '<td style="width:50px;border-top: 1px solid #3a6a86">
											<table style="width:50px;">
												<tr><td align="center" style="padding:0 0 5px 0;font-size:12px;">'.round($srow->result_value).'</td></tr>
											</table>
										</td>
									</tr>
								</table>';
							}
							$a++;
						}
						echo '<hr style="border-top: 1px solid #9acfdb;margin: 0px;">';
					}
				}
			}
			echo '<table width="100%"><tr><td height="20"></td></tr></table>';
		}
		?>
		<table width="100%"><tr><td height="20"></td></tr></table>
		<?php if(!empty($block1)){ ?>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
			<tr>
				<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $order_details['pet_name']; ?></b><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $order_details['order_number']; ?></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b> <?php echo $raptorData->sample_code; ?></th>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('Interpretation_Support'); ?></h4>
				</td>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
			<?php
			$allengesIDsArr = array();
			$subAllergnArr = $this->AllergensModel->getAllergensByID($order_details['allergens']);
			if(!empty($subAllergnArr)){
				$allengesArr = [];
				foreach ($subAllergnArr as $svalue){
					$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
					if(!empty($subVlu->raptor_code)){
						$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
						if(!empty($raptrVlu)){
							if($raptrVlu->result_value >= 30){
								$allengesArr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
								$allengesIDsArr[] = $svalue['id'];
							}
						}
					}
				}
			}
			$subAllergnsArr = $this->AllergensModel->getAllergensByID(json_encode($allengesIDsArr));
			if(!empty($subAllergnsArr)){
				foreach ($subAllergnsArr as $rsvalue){
					echo '<tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74;">'.$rsvalue['pax_name'].'</h4>
							<ol style="color:#184359;font-size:13px;margin:0px 0 0 20px;padding:0;">';
								$subpVluArr = $this->OrdersModel->getRaptorInterpretation($rsvalue['id'],$raptorData->result_id);
								if(!empty($subpVluArr)){
									foreach ($subpVluArr as $srow){
										if($srow->result_value >= 30){
											if($srow->raptor_header != "" && $srow->raptor_header != '[""]'){
												$detaildArr = json_decode($srow->raptor_header);
												if(!empty($detaildArr)){
													foreach($detaildArr as $row1d){
														echo '<li style="list-style-type: disc;">'.$row1d.'</li>';
													}
												}
												echo '<br>';
											}
										}
									}
								}

								$subpEDVluArr = $this->OrdersModel->getRaptorInterpretationED($rsvalue['id'],$raptorData->result_id);
								if(!empty($subpEDVluArr)){
									foreach ($subpEDVluArr as $sedrow){
										if($sedrow->result_value >= 30){
											if($sedrow->raptor_header != "" && $sedrow->raptor_header != '[""]'){
												$detaildedArr = json_decode($sedrow->raptor_header);
												if(!empty($detaildedArr)){
													foreach($detaildedArr as $row2d){
														echo '<li style="list-style-type: disc;">'.$row2d.'</li>';
													}
												}
												echo '<br>';
											}
										}
									}
								}
							echo '</ol>
						</td>
					</tr>
					<tr><td height="10"></td></tr>';
				}
			}
			?>
		</table>
		<table width="100%"><tr><td height="3"></td></tr></table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
			<tr>
				<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $order_details['pet_name']; ?> </b> <?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $order_details['order_number']; ?></th>
				<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b> <?php echo $raptorData->sample_code; ?></th>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('Immunotherapy'); ?></h4>
					<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;"><?php echo $this->lang->line('faq_title'); ?></p>
				</td>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;">
			<tr>
				<td width="48%" valign="top">
					<table cellpadding="0" cellspacing="0" border="0" align="left" width="100%;">
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q1'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a1'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td colspan="2"><h6 style="color:#333333; font-size:16px; margin:0 0 10px 0;"><?php echo $this->lang->line('positive_q2'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2">
								<img width="350" src="<?php echo base_url(); ?>assets/images/week-chart.png" alt="" style="max-width:100%;" />
								<p style="color:#333333; font-size:12px; line-height:18px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a2'); ?></p>
								<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="80%" style="width:100%; border:1px solid #89ccd6; border-radius:0 12px 12px 12px; padding:15px; margin-top:25px;">
									<tr>
										<td>
											<p style="color:#1e3743; font-size:13px; margin:4px 0 0 0;"><?php echo $this->lang->line('positive_a2a'); ?></p>
										</td>
									</tr>
									<tr><td height="15"></td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q3'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a3'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q4'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a4'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q5'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a5'); ?></p></td>
						</tr>
					</table>
				</td>
				<td width="4%" valign="top"></td>
				<td width="48%" valign="top">
					<table cellpadding="0" cellspacing="0" border="0" align="left" width="100%;">
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q6'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a6'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q7'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a7'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q8'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a8'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q9'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a9'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q10'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a10'); ?></p></td>
						</tr>
						<tr><td height="30"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:16px; margin:0;"><?php echo $this->lang->line('positive_q11'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a11'); ?></p></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="40"></td></tr></table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;">
			<tr>
				<td><h6 style="color:#376983; font-size:14px; margin:0;"><?php echo $this->lang->line('positive_q12'); ?></h6></td>	
			</tr>
			<tr>
				<td><p style="color:#1e3743; font-size:13px; line-height:15px; margin:0 0 0 0;"><?php echo $this->lang->line('positive_a12'); ?></p></td>
			</tr>
		</table>
		<?php } ?>
	</body>
</html>