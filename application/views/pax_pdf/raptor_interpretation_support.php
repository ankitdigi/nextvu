<table width="100%"><tr><td height="5"></td></tr></table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
	<tr>
		<td>
			<h4 style="margin:0; color:#2a5b74; font-size:20px;"><?php echo $this->lang->line('interpretation_support'); ?></h4>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="10"></td></tr></table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
	<?php
	foreach ($getAllergenParent as $apkey => $apvalue){
		$subAllergnsArr = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], json_encode($allengesIDsArr));
		if(!empty($subAllergnsArr)){
			foreach ($subAllergnsArr as $rsvalue){
				echo '<tr>
					<td>
						<table>
							<tr>
								<td colspan="2"><h4 style="margin:10px 0px 0px 0px;color:#2a5b74;">'.$rsvalue['pax_name'].'</h4></td>
							</tr>';
							$extIDArr = array();
							$extVluArr = $this->OrdersModel->getRaptorInterpretationExtract($rsvalue['id'],$raptorData->result_id);
							if(!empty($extVluArr)){
								foreach ($extVluArr as $rowe){
									if(floor($rowe->result_value) >= $cutoffs){
										$extIDArr[] = $rowe->id;
									}
								}
							}

							$compIDArr = array();
							$compVluArr = $this->OrdersModel->getRaptorInterpretationComponents($rsvalue['id'],$raptorData->result_id);
							if(!empty($compVluArr)){
								foreach ($compVluArr as $rowc){
									if(floor($rowc->result_value) >= $cutoffs){
										$compIDArr[] = $rowc->id;
									}
								}
							}

							if(!empty($extIDArr) && empty($compIDArr)){
								$extVluArr = $this->OrdersModel->getRaptorInterpretationExtract($rsvalue['id'],$raptorData->result_id);
								if(!empty($extVluArr)){
									foreach ($extVluArr as $rowe){
										if(floor($rowe->result_value) >= $cutoffs){
											if($rowe->raptor_header != "" && $rowe->raptor_header != '[""]'){
												$detaildArr = json_decode($rowe->raptor_header);
												if(!empty($detaildArr)){
													foreach($detaildArr as $row1d){
														echo '<tr><td style="padding-top:5px" valign="top"><img height="10" width="12" src="assets/images/dot.png"></td><td style="font-size: 12px;">'.$row1d.'</td><tr>';
													}
												}
												echo '<tr><td style="height:10px" valign="top"></td><tr>';
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
													echo '<tr><td style="padding-top:5px" valign="top"><img height="10" width="12" src="assets/images/dot.png"></td><td style="font-size: 12px;">'.$row1d.'</td><tr>';
												}
											}
											echo '<tr><td style="height:10px" valign="top"></td><tr>';
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
														echo '<tr><td style="padding-top:5px" valign="top"><img height="10" width="12" src="assets/images/dot.png"></td><td style="font-size: 12px;">'.$row1d.'</td><tr>';
													}
												}
												echo '<tr><td style="height:10px" valign="top"></td><tr>';
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
														echo '<tr><td style="padding-top:5px" valign="top"><img height="10" width="12" src="assets/images/dot.png"></td><td style="font-size: 12px;">'.$row1d.'</td><tr>';
													}
												}
												echo '<tr><td style="height:10px" valign="top"></td><tr>';
											}
										}
									}
								}

								$compVluArr = $this->OrdersModel->getRaptorInterpretationComponents($rsvalue['id'],$raptorData->result_id);
								if(!empty($compVluArr)){
									foreach ($compVluArr as $rowc){
										if(floor($rowc->result_value) >= $cutoffs){
											if($rowc->raptor_header != "" && $rowc->raptor_header != '[""]'){
												$detaildArr = json_decode($rowc->raptor_header);
												if(!empty($detaildArr)){
													foreach($detaildArr as $row1d){
														echo '<tr><td style="padding-top:5px" valign="top"><img height="10" width="12" src="assets/images/dot.png"></td><td style="font-size: 12px;">'.$row1d.'</td><tr>';
													}
												}
												echo '<tr><td style="height:10px" valign="top"></td><tr>';
											}
										}
									}
								}
							}
						echo '</table>
					</td>
				</tr>
				<tr><td height="10"></td></tr>';
			}
		}
	}
	?>
</table>