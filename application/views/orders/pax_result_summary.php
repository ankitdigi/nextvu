<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
	<tr>
		<td>
			<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('Summary_sensitisations'); ?></h4>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>

<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
	<tr>
		<td style="width:49%;vertical-align: top;">
			<?php
			$totalGroup = 0;
			foreach ($getEAllergenParent as $apkey => $apvalue) {
				if($totalGroup < $partB){
					echo '<table cellpadding="0" cellspacing="0" border="0" width="100%;">
						<tr>
							<td colspan="2" style="padding:0 0 5px 0;"><h5 style="margin:0; padding:0; font-size:22px; text-transform:uppercase; color:#2a5b74;">'.$apvalue['pax_name'].'</h5></td>
						</tr>';
						if($apvalue['pax_parent_id'] == '45958'){
							$subAllergensArr = array('60','46019','62','61','45904','63','64','45994','73','45895');
							foreach($subAllergensArr as $arow){
								$paxnames = $this->AllergensModel->getMCPaxnameById($arow);
								$subVlu = $this->OrdersModel->getsubAllergensCode($arow);
								if(!empty($subVlu->raptor_code)){
									$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
									if(!empty($raptrVlu)){
										if(floor($raptrVlu->result_value) < $cutoffs){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$paxnames->pax_name.'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif(floor($raptrVlu->result_value) >= $cutoffs && $raptrVlu->result_value < 100){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$paxnames->pax_name.'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
												<td style="padding:0 15px 0 0;" height="28">'.$paxnames->pax_name.'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
												<td style="padding:0 15px 0 0;" height="28">'.$paxnames->pax_name.'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
												<td style="padding:0 15px 0 0;" height="28">'.$paxnames->pax_name.'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
											<td style="padding:0 15px 0 0;" height="28">'.$paxnames->pax_name.'</td>
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
										<td style="padding:0 15px 0 0;" height="28">'.$paxnames->pax_name.'</td>
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
						}else{
							$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
							foreach ($subAllergens as $skey => $svalue) {
								$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
								if(!empty($subVlu->raptor_code)){
									$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
									if(!empty($raptrVlu)){
										if(floor($raptrVlu->result_value) < $cutoffs){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>
											</tr>';
										}elseif(floor($raptrVlu->result_value) >= $cutoffs && $raptrVlu->result_value < 100){
											echo '<tr>
												<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
												<td style="padding:0 0 0 15px;">
													<table align="right" class="meter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
															<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
						}
					echo '</table>
					<table><tr><td height="30"></td></tr></table>';
				}
				$totalGroup++;
			}
			?>
		</td>
		<td align="top" style="width:2%"></td>
		<td style="width:49%;vertical-align: top;">
			<?php
			$totalGroups = 0;
			foreach ($getEAllergenParent as $apkey => $apvalue) {
				if($totalGroups >= $partB){
					echo '<table cellpadding="0" cellspacing="0" border="0" width="100%;">
						<tr>
							<td colspan="2" style="padding:0 0 5px 0;"><h5 style="margin:0; padding:0; font-size:22px; text-transform:uppercase; color:#2a5b74;">'.$apvalue['pax_name'].'</h5></td>
						</tr>';
						$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
						foreach ($subAllergens as $skey => $svalue) {
							$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
							if(!empty($subVlu->raptor_code)){
								$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
								if(!empty($raptrVlu)){
									if(floor($raptrVlu->result_value) < $cutoffs){
										echo '<tr>
											<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
											<td style="padding:0 0 0 15px;">
												<table align="right" class="meter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
														<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
													</tr>
												</table>
											</td>
										</tr>';
									}elseif(floor($raptrVlu->result_value) >= $cutoffs && $raptrVlu->result_value < 100){
										echo '<tr>
											<td style="padding:0 15px 0 0;" height="28">'.$svalue['pax_name'].'</td>
											<td style="padding:0 0 0 15px;">
												<table align="right" class="meter">
													<tr>
														<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
														<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
														<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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
														<td style="width:25px;height:12px;line-height:12px;">'.floor($raptrVlu->result_value).'</td>
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

<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
	<tr>
		<td>
			<p style="margin:0 0 10px 0; color:#2a5b74; font-size:14px;"><?php echo $this->lang->line('result_note1'); ?></p>
			<h4 style="margin:0; color:#2a5b74; font-size:16px;"><?php echo $this->lang->line('result_note2'); ?></h4>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="10"></td></tr></table>

<table width="100%">
	<tr>
		<td style="padding:0 15px 0 0;">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr><td align="center" style="padding:0 0 5px 0;">&lt; <?php echo $cutoffs; ?>.00 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
				<tr><td class="blob1 index-capsule"></td></tr>
				<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 0</td></tr>
			</table>
		</td>
		<td style="padding:0 15px 0 15px;">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr><td align="center" style="padding:0 0 5px 0;"> <?php echo $cutoffs; ?>.00-99.99 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
				<tr><td class="blob2 index-capsule"></td></tr>
				<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 1</td></tr>
			</table>
		</td>
		<td style="padding:0 15px 0 15px;">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr><td align="center" style="padding:0 0 5px 0;">100.00-399.99 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
				<tr><td class="blob3 index-capsule"></td></tr>
				<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 2</td></tr>
			</table>
		</td>
		<td style="padding:0 15px 0 15px;">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr><td align="center" style="padding:0 0 5px 0;">400.00-799.99 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
				<tr><td class="blob4 index-capsule"></td></tr>
				<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 3</td></tr>
			</table>
		</td>
		<td style="padding:0 0 0 15px;">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr><td align="center" style="padding:0 0 5px 0;">&#8805; 800.00 <?php echo $this->lang->line('ng_mL'); ?></td></tr>
				<tr><td class="blob5 index-capsule"></td></tr>
				<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class'); ?> 4</td></tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="10"></td></tr></table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
	<tr>
		<td>
			<p style="margin:0 0 10px 0; color:#2a5b74; font-size:14px;"><?php echo $this->lang->line('class1_note'); ?></p>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>