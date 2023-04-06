<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
	<tr>
		<td style="padding: 5px;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $order_details['pet_name']; ?></b><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $account_ref; ?></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b> <?php echo $order_details['order_number']; ?></th>
				</tr>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>

			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<td class="blob2 index-capsule" style="padding-left:10px;width:25%;border-radius: 10px 0 0 10px;text-align: left;">
						<span ><?php echo $this->lang->line('Common_Name'); ?></span>
					</td>
					<td class="blob2 index-capsule" style="padding:0px;width:25%;border-radius:0px;text-align:center;">
						<span class="blob2 index-capsule"><?php echo $this->lang->line('Scientific_name'); ?></span>
					</td>
					<td class="blob2 index-capsule" style="padding:0px;width:15%;border-radius:0px;text-align:center;">
						<span class="blob2 index-capsule"><?php echo $this->lang->line('EM_Allergen'); ?></span>
					</td>
					<td class="blob2 index-capsule" style="padding:0px;width:15%;border-radius:0px;text-align:center;">
						<span class="blob2 index-capsule"><?php echo $this->lang->line('function'); ?></span>
					</td>
					<td class="blob2 index-capsule" style="padding:0px;width:15%;border-radius:0px;text-align:center;">
						<span class="blob2 index-capsule"></span>
					</td>
					<td class="blob2 index-capsule" style="padding:0px;width:5%;text-align:center;border-radius:0 10px 10px 0;">
						<span class="blob2 index-capsule"><?php echo $this->lang->line('ng_mL'); ?></span>
					</td>
				</tr>
			</table>

			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<td>
						<h4 style="margin:0; color:#2a5b74; font-size:24px;text-transform:uppercase;"><?php echo $this->lang->line('PAX_Environmental'); ?> <?php echo $this->lang->line('PANEL'); ?></h4>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>
			<?php
			foreach ($getEAllergenParent as $apkey => $apvalue){
				echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
					<tr>
						<td>
							<h4 style="margin:0; color:#2a5b74; font-size:24px;text-transform:uppercase;">'.$apvalue['pax_name'].'</h4>
						</td>
					</tr>
				</table>
				<hr style="border-top: 2px solid #3a6a86;margin: 0px;">';
				$subAllergndArr = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
				if(!empty($subAllergndArr)){
					foreach ($subAllergndArr as $rpvalue){
						if($rpvalue['id'] == '81'){
							$subpVluArr = $this->OrdersModel->getsubAllergensforPanel('459674',$raptorData->result_id);
							if(!empty($subpVluArr)){
								foreach ($subpVluArr as $srow){
									echo '<table width="100%">
										<tr>
											<td style="padding:0px 0px 0px 5px;width:25%">
												<table cellpadding="0" cellspacing="0" border="0" align="center">
													<tr><td align="center" style="padding:0 0 5px 0;">'.$rpvalue['name'].'</td></tr>
												</table>
											</td>
											<td style="padding:0px 5px;width:25%">
												<table cellpadding="0" cellspacing="0" border="0" align="center">
													<tr><td align="center" style="padding:0 0 5px 0;">'.$rpvalue['name'].' pachydermatis</td></tr>
												</table>
											</td>
											<td style="padding:0px 5px;width:15%">
												<table cellpadding="0" cellspacing="0" border="0" align="center">';
													if($srow->em_allergen == 2){
														echo '<tr><td align="center" style="padding:0 0 5px 0;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
													}else{
														echo '<tr><td align="center" style="padding:0 0 5px 0;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
													}
												echo '</table>
											</td>
											<td style="padding:0px 5px;width:15%">
												<table cellpadding="0" cellspacing="0" border="0" align="center">
													<tr><td align="center" style="padding:0 0 5px 0;">'.$srow->raptor_function.'</td></tr>
												</table>
											</td>';
											if(round($srow->result_value) < $cutoffs){
												echo '<td style="padding:0px 5px;width:15%">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>';
											}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
												echo '<td style="padding:0px 5px;width:15%">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
												echo '<td style="padding:0px 5px;width:15%">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
												echo '<td style="padding:0px 5px;width:15%">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
												echo '<td style="padding:0px 5px;width:15%">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
											echo '<td style="padding:0px 5px 0px 0px;width:5%">
												<table cellpadding="0" cellspacing="0" border="0" align="center">
													<tr><td align="center" style="padding:0 0 5px 0;">'.round($srow->result_value).'</td></tr>
												</table>
											</td>
										</tr>
									</table>';
								}
								echo '<hr style="border-top: 1px solid #9acfdb;margin: 0px;">';
							}

							$subpVluArr = $this->OrdersModel->getsubAllergensforPanel($rpvalue['id'],$raptorData->result_id);
							if(!empty($subpVluArr)){
								$r=0;
								foreach ($subpVluArr as $srow){
									echo '<table width="100%">
										<tr>
											<td style="padding:0px 0px 0px 5px;width:25%">
												<table cellpadding="0" cellspacing="0" border="0" align="center">
													<tr><td align="center" style="padding:0 0 5px 0;">&nbsp;</td></tr>
												</table>
											</td>';
											if($r==0){
												echo '<td style="padding:0px 5px;width:25%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">'.$rpvalue['name'].' '.strtolower($rpvalue['pax_latin_name']).'</td></tr>
													</table>
												</td>';
											}else{
												echo '<td style="padding:0px 5px;width:25%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">&nbsp;</td></tr>
													</table>
												</td>';
											}
											echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
												<table cellpadding="0" cellspacing="0" border="0" align="center">';
													if($srow->em_allergen == 2){
														echo '<tr><td align="center" style="padding:0 0 5px 0;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
													}else{
														echo '<tr><td align="center" style="padding:0 0 5px 0;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
													}
												echo '</table>
											</td>
											<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
												<table cellpadding="0" cellspacing="0" border="0" align="center">
													<tr><td align="center" style="padding:0 0 5px 0;">'.$srow->raptor_function.'</td></tr>
												</table>
											</td>';
											if(round($srow->result_value) < $cutoffs){
												echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
														<tr>
															<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
														</tr>
													</table>
												</td>';
											}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
												echo '<td style="0px 5px;width:15%;border-top: 1px solid #3a6a86">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
												echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
												echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
												echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
													<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
											echo '<td style="padding:0px 5px 0px 0px;width:5%;border-top: 1px solid #3a6a86">
												<table cellpadding="0" cellspacing="0" border="0" align="center">
													<tr><td align="center" style="padding:0 0 5px 0;">'.round($srow->result_value).'</td></tr>
												</table>
											</td>
										</tr>
									</table>';
									$r++;
								}
								echo '<hr style="border-top: 1px solid #9acfdb;margin: 0px;">';
							}
						}else{
							$subpVluArr = $this->OrdersModel->getsubAllergensforPanel($rpvalue['id'],$raptorData->result_id);
							if(!empty($subpVluArr)){
								$a=0;
								foreach ($subpVluArr as $srow){
									if($a==0){
										echo '<table width="100%">
											<tr>
												<td style="padding:0px 0px 0px 5px;width:25%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">'.$rpvalue['pax_name'].'</td></tr>
													</table>
												</td>
												<td style="padding:0px 5px;width:25%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">'.$rpvalue['pax_latin_name'].'</td></tr>
													</table>
												</td>
												<td style="padding:0px 5px;width:15%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">';
														if($srow->em_allergen == 2){
															echo '<tr><td align="center" style="padding:0 0 5px 0;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
														}else{
															echo '<tr><td align="center" style="padding:0 0 5px 0;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
														}
													echo '</table>
												</td>
												<td style="padding:0px 5px;width:15%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">'.$srow->raptor_function.'</td></tr>
													</table>
												</td>';
												if(round($srow->result_value) < $cutoffs){
													echo '<td style="padding:0px 5px;width:15%">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
															<tr>
																<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															</tr>
														</table>
													</td>';
												}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
													echo '<td style="padding:0px 5px;width:15%">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
													echo '<td style="padding:0px 5px;width:15%">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
													echo '<td style="padding:0px 5px;width:15%">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
													echo '<td style="padding:0px 5px;width:15%">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
												echo '<td style="padding:0px 5px 0px 0px;width:5%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">'.round($srow->result_value).'</td></tr>
													</table>
												</td>
											</tr>
										</table>';
									}else{
										echo '<table width="100%">
											<tr>
												<td style="padding:0px 0px 0px 5px;width:25%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">&nbsp;</td></tr>
													</table>
												</td>
												<td style="padding:0px 5px;width:25%">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">&nbsp;</td></tr>
													</table>
												</td>
												<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
													<table cellpadding="0" cellspacing="0" border="0" align="center">';
														if($srow->em_allergen == 2){
															echo '<tr><td align="center" style="padding:0 0 5px 0;"><img src="'. base_url() .'assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
														}else{
															echo '<tr><td align="center" style="padding:0 0 5px 0;"><img src="'. base_url() .'assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; '.$srow->raptor_code.'</td></tr>';
														}
													echo '</table>
												</td>
												<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">'.$srow->raptor_function.'</td></tr>
													</table>
												</td>';
												if(round($srow->result_value) < $cutoffs){
													echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
															<tr>
																<td style="width:25px;height:12px;line-height:12px;" class="blob1"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
																<td style="width:25px;height:12px;line-height:12px;" class="light-grey-bg"></td>
															</tr>
														</table>
													</td>';
												}elseif(round($srow->result_value) >= $cutoffs && $srow->result_value < 100){
													echo '<td style="0px 5px;width:15%;border-top: 1px solid #3a6a86">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
													echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
													echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
													echo '<td style="padding:0px 5px;width:15%;border-top: 1px solid #3a6a86">
														<table cellpadding="0" cellspacing="0" border="0" align="center" class="panelmeter">
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
												echo '<td style="padding:0px 5px 0px 0px;width:5%;border-top: 1px solid #3a6a86">
													<table cellpadding="0" cellspacing="0" border="0" align="center">
														<tr><td align="center" style="padding:0 0 5px 0;">'.round($srow->result_value).'</td></tr>
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
				}
				echo '<table width="100%"><tr><td height="20"></td></tr></table>';
			}
			?>
		</td>
	</tr>
</table>