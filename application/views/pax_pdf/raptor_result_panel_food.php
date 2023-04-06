<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:10px 40px;padding:0px;border:none;">
	<tr>
		<td>
			<h4 style="margin:0; color:#2a5b74; font-size:20px;text-transform:uppercase;"><?php echo $this->lang->line('pax_food'); ?> <?php echo $this->lang->line('PANEL'); ?></h4>
		</td>
	</tr>
</table>
<?php
$page6Arr = $pages6Arr = $page7Arr = $pages7Arr = $page8Arr = $pages8Arr = $page9Arr = $pages9Arr = [];
foreach ($getAllergenParent as $apkey => $apvalue){
	if($apvalue['pax_parent_id'] == '45897' || $apvalue['pax_name'] == 'Cereals & Seeds' || $apvalue['pax_parent_id'] == '45900' || $apvalue['pax_name'] == 'Egg & Milk'){
		$page6Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page6Arr['pax_name'] = $apvalue['pax_name'];
		$pages6Arr[] = $page6Arr;
	}elseif($apvalue['pax_parent_id'] == '45899' || $apvalue['pax_name'] == 'Legumes & Nuts' || $apvalue['pax_parent_id'] == '45903' || $apvalue['pax_name'] == 'Vegetables & Tubers'){
		$page7Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page7Arr['pax_name'] = $apvalue['pax_name'];
		$pages7Arr[] = $page7Arr;
	}elseif($apvalue['pax_parent_id'] == '45901' || $apvalue['pax_name'] == 'Meats' || $apvalue['pax_parent_id'] == '45898' || $apvalue['pax_name'] == 'Fruits'){
		$page8Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page8Arr['pax_name'] = $apvalue['pax_name'];
		$pages8Arr[] = $page8Arr;
	}else{
		$page9Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page9Arr['pax_name'] = $apvalue['pax_name'];
		$pages9Arr[] = $page9Arr;
	}
}

if(!empty($pages6Arr)){
	foreach ($pages6Arr as $p6value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:16px; padding:0 5px 5px 0px;">'.$p6value['pax_name'].'</th>
			</tr>
		</table>
		<div style="margin: 0px 40px;"><hr style="border-top: 2px solid #3a6a86;"></div>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p6value['pax_parent_id'], $order_details['allergens']);
		if(!empty($subAllergndArr)){
			foreach ($subAllergndArr as $rpvalue){
				$subpVluArr = $this->OrdersModel->getsubAllergensforPanel($rpvalue['id'],$raptorData->result_id);
				if(!empty($subpVluArr)){
					$a=0;
					foreach ($subpVluArr as $srow){
						if($a==0){
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">'.(!empty($rpvalue['pax_name'])?$rpvalue['pax_name']:'&nbsp;').'</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;"><i>'.(!empty($rpvalue['pax_latin_name'])?$rpvalue['pax_latin_name']:'&nbsp;').'</i></td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px">
										<table cellpadding="0" style="width:130px">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}
										echo '</table>
									</td>
									<td align="left" style="text-align:left;width:120px">
										<table style="width:120px">
											<tr><td align="left" style="font-size:11px;">'.$srow->raptor_function.'</td></tr>
										</table>
									</td>';
									if(round($srow->result_value) < $cutoffs){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 100 && $srow->result_value < 400){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 400 && $srow->result_value < 800){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 800){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob51"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}
									echo '<td align="left" style="text-align:left;width:50px">
										<table style="width:50px">
											<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">'.round($srow->result_value).'</td></tr>
										</table>
									</td>
								</tr>
							</table>';
						}else{
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px;border-top: 1px solid #3a6a86">
										<table style="width:130px;">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}
										echo '</table>
									</td>
									<td align="left" style="text-align:left;width:120px;border-top: 1px solid #3a6a86">
										<table style="width:120px;">
											<tr><td align="left" style="font-size:11px;">'.$srow->raptor_function.'</td></tr>
										</table>
									</td>';
									if(round($srow->result_value) < $cutoffs){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 100 && $srow->result_value < 400){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 400 && $srow->result_value < 800){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 800){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob51"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}
									echo '<td align="left" style="text-align:left;width:50px;border-top: 1px solid #3a6a86">
										<table style="width:50px;">
											<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">'.round($srow->result_value).'</td></tr>
										</table>
									</td>
								</tr>
							</table>';
						}
						$a++;
					}
					echo '<div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div>';
				}
			}
		}
		echo '<table width="100%"><tr><td height="12"></td></tr></table>';
	}
	echo "<div style='page-break-after:always'></div>";
}

if(!empty($pages7Arr)){
	foreach ($pages7Arr as $p7value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:16px; padding:0 5px 5px 0px;">'.$p7value['pax_name'].'</th>
			</tr>
		</table>
		<div style="margin: 0px 40px;"><hr style="border-top: 2px solid #3a6a86;"></div>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p7value['pax_parent_id'], $order_details['allergens']);
		if(!empty($subAllergndArr)){
			foreach ($subAllergndArr as $rpvalue){
				$subpVluArr = $this->OrdersModel->getsubAllergensforPanel($rpvalue['id'],$raptorData->result_id);
				if(!empty($subpVluArr)){
					$a=0;
					foreach ($subpVluArr as $srow){
						if($a==0){
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">'.(!empty($rpvalue['pax_name'])?$rpvalue['pax_name']:'&nbsp;').'</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;"><i>'.(!empty($rpvalue['pax_latin_name'])?$rpvalue['pax_latin_name']:'&nbsp;').'</i></td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px">
										<table cellpadding="0" style="width:130px">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}
										echo '</table>
									</td>
									<td align="left" style="text-align:left;width:120px">
										<table style="width:120px">
											<tr><td align="left" style="font-size:11px;">'.$srow->raptor_function.'</td></tr>
										</table>
									</td>';
									if(round($srow->result_value) < $cutoffs){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 100 && $srow->result_value < 400){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 400 && $srow->result_value < 800){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 800){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob51"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}
									echo '<td align="left" style="text-align:left;width:50px">
										<table style="width:50px">
											<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">'.round($srow->result_value).'</td></tr>
										</table>
									</td>
								</tr>
							</table>';
						}else{
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px;border-top: 1px solid #3a6a86">
										<table style="width:130px;">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}
										echo '</table>
									</td>
									<td align="left" style="text-align:left;width:120px;border-top: 1px solid #3a6a86">
										<table style="width:120px;">
											<tr><td align="left" style="font-size:11px;">'.$srow->raptor_function.'</td></tr>
										</table>
									</td>';
									if(round($srow->result_value) < $cutoffs){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 100 && $srow->result_value < 400){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 400 && $srow->result_value < 800){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 800){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob51"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}
									echo '<td align="left" style="text-align:left;width:50px;border-top: 1px solid #3a6a86">
										<table style="width:50px;">
											<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">'.round($srow->result_value).'</td></tr>
										</table>
									</td>
								</tr>
							</table>';
						}
						$a++;
					}
					echo '<div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div>';
				}
			}
		}
		echo '<table width="100%"><tr><td height="12"></td></tr></table>';
	}
	echo "<div style='page-break-after:always'></div>";
}

if(!empty($pages8Arr)){
	foreach ($pages8Arr as $p8value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:16px; padding:0 5px 5px 0px;">'.$p8value['pax_name'].'</th>
			</tr>
		</table>
		<div style="margin: 0px 40px;"><hr style="border-top: 2px solid #3a6a86;"></div>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p8value['pax_parent_id'], $order_details['allergens']);
		if(!empty($subAllergndArr)){
			foreach ($subAllergndArr as $rpvalue){
				$subpVluArr = $this->OrdersModel->getsubAllergensforPanel($rpvalue['id'],$raptorData->result_id);
				if(!empty($subpVluArr)){
					$a=0;
					foreach ($subpVluArr as $srow){
						if($a==0){
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">'.(!empty($rpvalue['pax_name'])?$rpvalue['pax_name']:'&nbsp;').'</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;"><i>'.(!empty($rpvalue['pax_latin_name'])?$rpvalue['pax_latin_name']:'&nbsp;').'</i></td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px">
										<table cellpadding="0" style="width:130px">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}
										echo '</table>
									</td>
									<td align="left" style="text-align:left;width:120px">
										<table style="width:120px">
											<tr><td align="left" style="font-size:11px;">'.$srow->raptor_function.'</td></tr>
										</table>
									</td>';
									if(round($srow->result_value) < $cutoffs){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 100 && $srow->result_value < 400){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 400 && $srow->result_value < 800){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 800){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob51"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}
									echo '<td align="left" style="text-align:left;width:50px">
										<table style="width:50px">
											<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">'.round($srow->result_value).'</td></tr>
										</table>
									</td>
								</tr>
							</table>';
						}else{
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px;border-top: 1px solid #3a6a86">
										<table style="width:130px;">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}
										echo '</table>
									</td>
									<td align="left" style="text-align:left;width:120px;border-top: 1px solid #3a6a86">
										<table style="width:120px;">
											<tr><td align="left" style="font-size:11px;">'.$srow->raptor_function.'</td></tr>
										</table>
									</td>';
									if(round($srow->result_value) < $cutoffs){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 100 && $srow->result_value < 400){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 400 && $srow->result_value < 800){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 800){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob51"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}
									echo '<td align="left" style="text-align:left;width:50px;border-top: 1px solid #3a6a86">
										<table style="width:50px;">
											<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">'.round($srow->result_value).'</td></tr>
										</table>
									</td>
								</tr>
							</table>';
						}
						$a++;
					}
					echo '<div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div>';
				}
			}
		}
		echo '<table width="100%"><tr><td height="12"></td></tr></table>';
	}
	echo "<div style='page-break-after:always'></div>";
}

if(!empty($pages9Arr)){
	foreach ($pages9Arr as $p9value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:18px; padding:0 5px 5px 0px;">'.$p9value['pax_name'].'</th>
			</tr>
		</table>
		<div style="margin: 0px 40px;"><hr style="border-top: 2px solid #3a6a86;"></div>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p9value['pax_parent_id'], $order_details['allergens']);
		if(!empty($subAllergndArr)){
			foreach ($subAllergndArr as $rpvalue){
				$subpVluArr = $this->OrdersModel->getsubAllergensforPanel($rpvalue['id'],$raptorData->result_id);
				if(!empty($subpVluArr)){
					$a=0;
					foreach ($subpVluArr as $srow){
						if($a==0){
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">'.(!empty($rpvalue['pax_name'])?$rpvalue['pax_name']:'&nbsp;').'</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;"><i>'.(!empty($rpvalue['pax_latin_name'])?$rpvalue['pax_latin_name']:'&nbsp;').'</i></td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px">
										<table cellpadding="0" style="width:130px">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}
										echo '</table>
									</td>
									<td align="left" style="text-align:left;width:120px">
										<table style="width:120px">
											<tr><td align="left" style="font-size:11px;">'.$srow->raptor_function.'</td></tr>
										</table>
									</td>';
									if(round($srow->result_value) < $cutoffs){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 100 && $srow->result_value < 400){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 400 && $srow->result_value < 800){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 800){
										echo '<td align="left" style="text-align:left;width:110px">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob51"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}
									echo '<td align="left" style="text-align:left;width:50px">
										<table style="width:50px">
											<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">'.round($srow->result_value).'</td></tr>
										</table>
									</td>
								</tr>
							</table>';
						}else{
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px;border-top: 1px solid #3a6a86">
										<table style="width:130px;">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}
										echo '</table>
									</td>
									<td align="left" style="text-align:left;width:120px;border-top: 1px solid #3a6a86">
										<table style="width:120px;">
											<tr><td align="left" style="font-size:11px;">'.$srow->raptor_function.'</td></tr>
										</table>
									</td>';
									if(round($srow->result_value) < $cutoffs){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 100 && $srow->result_value < 400){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_comman.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 400 && $srow->result_value < 800){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg1"><img src="'.base_url().'assets/images/ss_last.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}elseif($srow->result_value >= 800){
										echo '<td align="left" style="text-align:left;width:110px;border-top: 1px solid #3a6a86;">
											<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
												<tr>
													<td style="width:25px;height:12px;line-height:12px;" class="blob11"><img src="'.base_url().'assets/images/ss1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob21"><img src="'.base_url().'assets/images/ss_1.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob31"><img src="'.base_url().'assets/images/ss_2.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob41"><img src="'.base_url().'assets/images/ss_3.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
													<td style="width:25px;height:12px;line-height:12px;" class="blob51"><img src="'.base_url().'assets/images/ss_4.png" alt=""  style="height: 9px;" height="14" width="23" /></td>
												</tr>
											</table>
										</td>';
									}
									echo '<td align="left" style="text-align:left;width:50px;border-top: 1px solid #3a6a86">
										<table style="width:50px;">
											<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">'.round($srow->result_value).'</td></tr>
										</table>
									</td>
								</tr>
							</table>';
						}
						$a++;
					}
					echo '<div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div>';
				}
			}
		}
		echo '<table width="100%"><tr><td height="18"></td></tr></table>';
	}
}
?>