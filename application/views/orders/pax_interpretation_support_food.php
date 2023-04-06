<table width="100%"><tr><td height="20"></td></tr></table>
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
					<td>
						<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('interpretation_support'); ?></h4>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="3"></td></tr></table>

			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<?php
				foreach ($getFAllergenParent as $apkey => $apvalue){
					$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], json_encode($allengesIDFArr));
					if(!empty($subAllergens)){
						foreach ($subAllergens as $rsvalue){
							echo '<tr>
								<td>
									<h4 style="margin:10px 0px 0px 0px;color:#2a5b74;">'.$rsvalue['pax_name'].'</h4>
									<ol style="color:#184359;font-size:13px;margin:0px 0 0 20px;padding:0;">';
										$extIDArr = array();
										$extVluArr = $this->OrdersModel->getRaptorInterpretationExtract($rsvalue['id'],$raptorData->result_id);
										if(!empty($extVluArr)){
											foreach ($extVluArr as $rowe){
												if(round($rowe->result_value) >= $cutoffs){
													$extIDArr[] = $rowe->id;
												}
											}
										}

										$compIDArr = array();
										$compVluArr = $this->OrdersModel->getRaptorInterpretationComponents($rsvalue['id'],$raptorData->result_id);
										if(!empty($compVluArr)){
											foreach ($compVluArr as $rowc){
												if(round($rowc->result_value) >= $cutoffs){
													$compIDArr[] = $rowc->id;
												}
											}
										}

										if(!empty($extIDArr) && empty($compIDArr)){
											$extVluArr = $this->OrdersModel->getRaptorInterpretationExtract($rsvalue['id'],$raptorData->result_id);
											if(!empty($extVluArr)){
												foreach ($extVluArr as $rowe){
													if(round($rowe->result_value) >= $cutoffs){
														if($rowe->raptor_header != "" && $rowe->raptor_header != '[""]'){
															$detaildArr = json_decode($rowe->raptor_header);
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
										}else{
											$descVluArr = $this->OrdersModel->getRaptorInterpretationDescription($rsvalue['id'],$raptorData->result_id);
											if(!empty($descVluArr)){
												foreach ($descVluArr as $rowd){
													if($rowd->raptor_header != "" && $rowd->raptor_header != '[""]'){
														$detaildArr = json_decode($rowd->raptor_header);
														if(!empty($detaildArr)){
															foreach($detaildArr as $row1d){
																echo '<li style="list-style-type: disc;">'.$row1d.'</li>';
															}
														}
														echo '<br>';
													}
												}
											}elseif(empty($descVluArr) && empty($extIDArr)){
												$extVluArr = $this->OrdersModel->getRaptorInterpretationExtractNew($rsvalue['id'],$raptorData->result_id);
												if(!empty($extVluArr)){
													foreach ($extVluArr as $rowe){
														if($rowe->raptor_header != "" && $rowe->raptor_header != '[""]'){
															$detaildArr = json_decode($rowe->raptor_header);
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

											if(!empty($extIDArr)){
												$extVluArr = $this->OrdersModel->getRaptorInterpretationExtract($rsvalue['id'],$raptorData->result_id);
												if(!empty($extVluArr)){
													foreach ($extVluArr as $rowe){
														if($rowe->raptor_header != "" && $rowe->raptor_header != '[""]'){
															$detaildArr = json_decode($rowe->raptor_header);
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

											$compVluArr = $this->OrdersModel->getRaptorInterpretationComponents($rsvalue['id'],$raptorData->result_id);
											if(!empty($compVluArr)){
												foreach ($compVluArr as $rowc){
													if(round($rowc->result_value) >= $cutoffs){
														if($rowc->raptor_header != "" && $rowc->raptor_header != '[""]'){
															$detaildArr = json_decode($rowc->raptor_header);
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
										}
									echo '</ol>
								</td>
							</tr>';
						}
					}
				}
				?>
			</table>
			<table width="100%"><tr><td height="3"></td></tr></table>
			<table width="100%">
				<tr>
					<td>
						<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;"><?php echo $this->lang->line('interpretation_DISCLAIMER'); ?> </p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>