<?php 
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
$boxremoved = 0;
if(!empty($block1)){
	foreach($block1 as $key=>$value){
		if(!in_array($key,$removed_treatment_1)){
			$boxremoved++;
		}
	}
}
$box2removed = 0;
if(!empty($block2)){
	foreach($block2 as $key=>$value){
		if(!in_array($key,$removed_treatment_2)){
			$box2removed++;
		}
	}
}
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0px 40px;">
	<tr>
		<td>
			<h4 style="margin:0; color:#2a5b74; font-size:20px;"><?php echo $this->lang->line('Summary_recommendation'); ?></h4>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="5"></td></tr></table>
<div style="width: 88%;background-color: #f5fafb;border-radius: 20px;padding: 10px;margin: 0px 40px;font-size:12px;">
	<?php echo !empty($order_details['interpretation'])?nl2br($order_details['interpretation']):$dummytext; ?>
</div>
<table width="100%"><tr><td height="10"></td></tr></table>
<table width="100%" style="margin: 0px 40px;">
	<tr>
		<?php if(!empty($block1) && $boxremoved > 0){ ?>
		<td valign="top" height="500" width="380" style="height:500px;">
			<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="left" style="margin-left:40px;margin-left: 20px;min-height:320px">
				<tr>
					<td>
						<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="380">
					</td>
				</tr>
				<tr>
					<td style="background:#326883; padding: 0 20px 20px; color:#ffffff; font-size:18px;">
						<input type="checkbox" style="background:#e4eaed; padding:50px;font-size:20pt; height:60px !important; border:1px solid #4d5d67; width:60px !important;" />
						<b>&nbsp; <?php echo $this->lang->line('Treatment_option'); ?> 1</b>
					</td>
				</tr>
				<tr>
					<td valign="top" height="290" bgcolor="#e2f2f4" style="padding:20px;height:290px;">
						<ol style="color:#184359; font-size:9pt; margin:15px 0 0 20px; padding:0;width:380px">
							<?php 
							if(!empty($block1)){
								asort($block1); $a=0;
								foreach($block1 as $key=>$value){
									if(!in_array($key,$removed_treatment_1)){ ?>
										<li style="margin-bottom: 5px;font-size:16px;">
											<?php echo $value; ?>
										</li>
										<?php 
										$a++;
									}
								}
								$quotient = ($a/8);
								$totalVials = ((round)($quotient));
								$demimal = $quotient-$totalVials;
								if($demimal > 0){
									$totalVials = $totalVials+1;
								}
							}else{
								$totalVials = 1;
							}
							?>
						</ol>
					</td>
				</tr>
				<tr>
					<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff;">
						<table cellpadding="0" cellspacing="0" border="0" width="100%;">
							<tr>
								<td height="20"></td>
							</tr>
							<tr>
								<th colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('option_results'); ?>:</th>
							</tr>
							<tr><td height="8"></td></tr>
							<tr>
								<td width="30%">
									<input type="text" value="<?php echo $totalVials; ?>" style="background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;font-size:20pt;" />
								</td>
								<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('Subcutaneous_immunotherapy'); ?> </td>
							</tr>
							<tr>
								<td height="40"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="380">
					</td>
				</tr>
			</table>
		</td>
		<?php } ?>
		<?php if(!empty($block2) && $box2removed > 0 && $order_details['remove_treatment_2'] == 0){ ?>
		<td valign="top" height="500" width="380" style="height:500px;">
			<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="left" style="margin-left:40px;margin-left: 20px;min-height:320px">
				<tr>
					<td>
						<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="380">
					</td>
				</tr>
				<tr>
					<td style="background:#326883; padding: 0 20px 20px; color:#ffffff; font-size:18px;">
						<input type="checkbox" style="background:#e4eaed; padding:50px;font-size:20pt; height:60px !important; border:1px solid #4d5d67; width:60px !important;" /> <b>&nbsp; <?php echo $this->lang->line('Treatment_option'); ?> 2</b>
					</td>
				</tr>
				<tr>
					<td valign="top" height="290" bgcolor="#e2f2f4" style="padding:20px;height:290px;">
						<ol style="color:#184359; font-size:9pt; margin:15px 0 0 20px; padding:0;width:380px">
							<?php 
							if(!empty($block2)){
								asort($block2); $b=0;
								foreach($block2 as $keys=>$values){
									if(!in_array($keys,$removed_treatment_2)){ ?>
										<li style="margin-bottom: 5px;font-size:16px;">
											<?php echo $values; ?>
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
							}else{
								$totalViald = 1;
							}
							?>
						</ol>
					</td>
				</tr>
				<tr>
					<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff;">
						<table cellpadding="0" cellspacing="0" border="0" width="100%;">
							<tr>
								<td height="20"></td>
							</tr>
							<tr>
								<th colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('option_results'); ?>:</th>
							</tr>
							<tr><td height="8"></td></tr>
							<tr>
								<td width="30%"><input type="text" value="<?php echo $totalViald; ?>" style="font-size:20pt;background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;" /></td>
								<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('Subcutaneous_immunotherapy'); ?> </td>
							</tr>
							<tr>
								<td height="40"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="380">
					</td>
				</tr>
			</table>
		</td>
		<?php } ?>
		<td valign="top" width="380" height="480" style="height:480px;">	
			<table width="100%" width="380" cellspacing="0" cellpadding="0" border="0" align="left" style=" margin-left:20px;min-height:300px">
				<tr>
					<td>
						<img src="<?php echo base_url(); ?>assets/images/top-border-radius.png" width="380">
					</td>
				</tr>
				<tr>
					<td style="background:#326883; padding: 0 20px 20px; color:#ffffff; font-size:16px;">
						<input type="checkbox" style="background:#e4eaed; padding:50px;font-size:16pt; height:60px !important; border:1px solid #4d5d67; width:60px !important;" /> <b>&nbsp; <?php echo $this->lang->line('compose_your_own'); ?></b>
					</td>
				</tr>
				<tr>
					<td valign="top" height="280" bgcolor="#e2f2f4" style="padding:20px;height:260px;">
						<ol style="color:#184359; font-size:9pt; margin:15px 0 0 20px; padding:0;width:380px">
							<li>&nbsp;</li>
							<li>&nbsp;</li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
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
								<th colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('option_results'); ?>:</th>
							</tr>
							<tr><td height="8"></td></tr>
							<tr>
								<td width="30%"><input type="text" placeholder="" style="font-size:20pt;background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;" /></td>
								<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('Subcutaneous_immunotherapy'); ?></td>
							</tr>
							<tr>
								<td height="30"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<img src="<?php echo base_url(); ?>assets/images/bottom_radius_pax.png" width="380">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div style="width: 50%;float: right;background:#def4f6; border-radius:120px 0 0 120px; padding:15px 15px 15px 100px;<?=(!empty($block2))?'margin-top: 25px;':'margin-top: 25px;'?>">
	<h6 style="color:#366784; margin:0 0 10px 0; padding:0; font-size:18px;"><?php echo $this->lang->line('positive_q12'); ?></h6>
	<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:14px; line-height:20px;"><?php echo $this->lang->line('Summary_bottom_1'); ?> <?php echo $this->lang->line('Summary_bottom_2'); ?>. <?php echo $this->lang->line('Summary_bottom_3'); ?></p>
</div>