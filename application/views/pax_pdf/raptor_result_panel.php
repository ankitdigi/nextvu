<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:10px 40px;padding:0px;border:none;">
	<tr>
		<td>
			<h4 style="margin:0; color:#2a5b74; font-size:20px;text-transform:uppercase;"><?php echo $this->lang->line('PAX_Environmental'); ?> <?php echo $this->lang->line('PANEL'); ?></h4>
		</td>
	</tr>
</table>
<?php
$page1Arr = $pages1Arr = $page2Arr = $pages2Arr = $page3Arr = $pages3Arr = $page4Arr = $pages4Arr = $page5Arr = $pages5Arr = [];
foreach ($getAllergenParent as $apkey => $apvalue){
	if($apvalue['pax_parent_id'] == '1' || $apvalue['pax_name'] == 'Grass Pollens' || $apvalue['pax_parent_id'] == '45965' || $apvalue['pax_name'] == 'Weed Pollens'){
		$page1Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page1Arr['pax_name'] = $apvalue['pax_name'];
		$pages1Arr[] = $page1Arr;
	}elseif($apvalue['pax_parent_id'] == '45964' || $apvalue['pax_name'] == 'Tree Pollens'){
		$page2Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page2Arr['pax_name'] = $apvalue['pax_name'];
		$pages2Arr[] = $page2Arr;
	}elseif($apvalue['pax_parent_id'] == '45958' || $apvalue['pax_name'] == 'Mites & Cockroaches'){
		$page3Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page3Arr['pax_name'] = $apvalue['pax_name'];
		$pages3Arr[] = $page3Arr;
	}elseif($apvalue['pax_parent_id'] == '45966' || $apvalue['pax_name'] == 'Moulds & Yeasts' || $apvalue['pax_parent_id'] == '45959' || $apvalue['pax_name'] == 'Insect Venoms'){
		$page4Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page4Arr['pax_name'] = $apvalue['pax_name'];
		$pages4Arr[] = $page4Arr;
	}else{
		$page5Arr['pax_parent_id'] = $apvalue['pax_parent_id'];
		$page5Arr['pax_name'] = $apvalue['pax_name'];
		$pages5Arr[] = $page5Arr;
	}
}

if(!empty($pages1Arr)){
	foreach ($pages1Arr as $p1value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:18px; padding:0 5px 5px 0px;">'.$p1value['pax_name'].'</th>
			</tr>
		</table>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p1value['pax_parent_id'], $order_details['allergens']);
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
		echo '<table width="100%"><tr><td height="14"></td></tr></table>';
	}
	echo "<div style='page-break-after:always'></div>";
}

if(!empty($pages2Arr)){
	foreach ($pages2Arr as $p2value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:18px; padding:0 5px 5px 0px;">'.$p2value['pax_name'].'</th>
			</tr>
		</table>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p2value['pax_parent_id'], $order_details['allergens']);
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
		echo '<table width="100%"><tr><td height="14"></td></tr></table>';
	}
	echo "<div style='page-break-after:always'></div>";
}

if(!empty($pages3Arr)){
	foreach ($pages3Arr as $p3value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:18px; padding:0 5px 5px 0px;">'.$p3value['pax_name'].'</th>
			</tr>
		</table>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p3value['pax_parent_id'], $order_details['allergens']);
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
		echo '<table width="100%"><tr><td height="14"></td></tr></table>';
	}
	echo "<div style='page-break-after:always'></div>";
}

if(!empty($pages4Arr)){
	foreach ($pages4Arr as $p4value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:18px; padding:0 5px 5px 0px;">'.$p4value['pax_name'].'</th>
			</tr>
		</table>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p4value['pax_parent_id'], $order_details['allergens']);
		if(!empty($subAllergndArr)){
			foreach ($subAllergndArr as $rpvalue){
				if($rpvalue['id'] == '81'){
					$subpVluArr = $this->OrdersModel->getsubAllergensforPanel('459674',$raptorData->result_id);
					if(!empty($subpVluArr)){
						foreach ($subpVluArr as $srow){
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">'.(!empty($rpvalue['name'])?$rpvalue['name']:'&nbsp;').'</td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;"><i>'.(!empty($rpvalue['name'])?$rpvalue['name']:'&nbsp;').' pachydermatis</i></td></tr>
										</table>
									</td>
									<td align="left" style="text-align:left;width:130px">
										<table cellpadding="0" style="width:130px">';
											if($srow->em_allergen == 2){
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
											}else{
												echo '<tr><td align="left" style="font-size:11px;text-align:left;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen" style="width: 18px;vertical-align: middle;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
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
						}
					}

					$subpVluArr = $this->OrdersModel->getsubAllergensforPanel($rpvalue['id'],$raptorData->result_id);
					if(!empty($subpVluArr)){
						$r=0;
						foreach ($subpVluArr as $srow){
							echo '<table style="width:750px;margin: 0px 40px;padding:0px;border:none;clear:both;" cellpadding="0">
								<tr>
									<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>';
									if($r==0){
									echo '<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">'.$rpvalue['name'].' '.strtolower($rpvalue['pax_latin_name']).'</td></tr>
										</table>
									</td>';
									}else{
									echo '<td align="left" style="text-align:left;width:175px;">
										<table style="width:175px;">
											<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
										</table>
									</td>';
									}
									echo '<td align="left" style="text-align:left;width:130px;border-top: 1px solid #3a6a86">
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
							$r++;
						}
						echo '<div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div>';
					}
				}else{
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
		}
		echo '<table width="100%"><tr><td height="14"></td></tr></table>';
	}
	echo "<div style='page-break-after:always'></div>";
}

if(!empty($pages5Arr)){
	foreach ($pages5Arr as $p5value){
		echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:18px; padding:0 5px 5px 0px;">'.$p5value['pax_name'].'</th>
			</tr>
		</table>';
		$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($p5value['pax_parent_id'], $order_details['allergens']);
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
		echo '<table width="100%"><tr><td height="14"></td></tr></table>';
	}
}
?>