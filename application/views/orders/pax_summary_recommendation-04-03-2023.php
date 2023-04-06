<table width="100%"><tr><td height="20"></td></tr></table>
<table class="main_container optiontbl" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
	<tr>
		<td style="padding: 5px;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $order_details['pet_name']; ?></b><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $order_details['order_number']; ?></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b> <?php echo $raptorData->sample_code; ?></th>
				</tr>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>

			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<td>
						<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('Summary_recommendation'); ?></h4>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="3"></td></tr></table>

			<table width="100%">
				<tr>
					<td>
						<textarea class="form-control treatment_comment" name="treatment_comment" rows="15" style="background:#f2f5f8;border-radius:10px;" readonly><?php echo $dummytext; ?></textarea>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>
			<table id="CreateImage1" cellspacing="0" cellpadding="0" border="0" width="320" align="left" style="min-width:320px;">
				<tr>
					<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?><input type="radio" name="treatment" id="treatment1" value="1" /><?php } ?><span id="treatments1" style="padding: 1px 10px;border: 1px solid #4d5d67;color: #000;font-size: 18px;font-weight: bold;text-align: center;background-color: #fff;display:none;"></span> &nbsp; <?php echo $this->lang->line('Treatment_option'); ?> 1</th>
				</tr>
				<tr>
					<td bgcolor="#e2f2f4" style="padding:20px;">
						<ol style="color:#184359; font-size:14px; margin-left:20px; padding:0;">
							<?php 
							if(!empty($block1)){
								$a=0;
								foreach($block1 as $key=>$value){ ?>
									<li style="margin-bottom: 5px;">
										<input type="hidden" name="allergens1[]" value="<?php echo $key; ?>"><?php echo $value; ?>
									</li>
								<?php 
									$a++;
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
					<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:0 0 10px 10px;">
						<table cellpadding="0" cellspacing="0" border="0" width="100%;">
							<tr>
								<td height="20"></td>
							</tr>
							<tr>
								<th colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('option_results'); ?>:</th>
							</tr>
							<tr>
								<td width="30%">
									<div style="padding: 10px;border: 1px solid #4d5d67;color: #000;font-size: 18px;font-weight: bold;text-align: center;background-color: #fff;height: 40px;width:60px;"><?php echo $totalVials; ?></div>
								</td>
								<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('Subcutaneous_immunotherapy'); ?> </td>
								<td width="30%"><img src="<?php echo base_url(); ?>assets/images/needle.png" alt="" width="60px" /></td>
							</tr>
							<tr>
								<td height="40"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<?php if(!empty($block2) && ($block1 != $block2) && ($order_details['remove_treatment_2'] == 0)){ ?>
			<table id="CreateImage2" cellspacing="0" cellpadding="0" border="0" width="320" align="left" style="margin-left:30px; min-width:320px;">
				<tr>
					<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?><input type="radio" name="treatment" id="treatment2" value="2" <?php if(empty($block2)){ echo 'disabled="disabled"'; } ?> /><?php } ?><span id="treatments2" style="padding: 1px 10px;border: 1px solid #4d5d67;color: #000;font-size: 18px;font-weight: bold;text-align: center;background-color: #fff;display:none;"></span> &nbsp; <?php echo $this->lang->line('Treatment_option'); ?> 2</th>
				</tr>
				<tr>
					<td bgcolor="#e2f2f4" style="padding:20px;">
						<ol style="color:#184359; font-size:14px; margin-left:20px; padding:0;">
							<?php 
							if(!empty($block2)){
								$b=0;
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
							}else{
								$totalViald = 1;
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
								<th colspan="3" align="left" style="color:#303846;"><?php echo $this->lang->line('option_results'); ?>:</th>
							</tr>
							<tr>
								<td width="30%">
									<div style="padding: 10px;border: 1px solid #4d5d67;color: #000;font-size: 18px;font-weight: bold;text-align: center;background-color: #fff;height: 40px;width:60px;"><?php echo $totalViald; ?></div>
								</td>
								<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('Subcutaneous_immunotherapy'); ?></td>
								<td width="30%"><img src="<?php echo base_url(); ?>assets/images/needle.png" alt="" width="60px" /></td>
							</tr>
							<tr>
								<td height="40"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?php } ?>

			<?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?>
			<table id="CreateImage3" class="table3" cellspacing="0" cellpadding="0" border="0" width="320" align="left" style="margin-left:30px; min-width:320px;">
				<tr>
					<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?><input type="radio" name="treatment" id="treatment3" value="3" /><?php } ?><span id="treatments3" style="padding: 1px 10px;border: 1px solid #4d5d67;color: #000;font-size: 18px;font-weight: bold;text-align: center;background-color: #fff;display:none;"></span> &nbsp; <?php echo $this->lang->line('compose_your_own'); ?></th>
				</tr>
				<tr>
					<td bgcolor="#e2f2f4" style="padding:20px;">
						<ol style="color:#184359; font-size:13px; margin-left:20px; padding:0;">
							<li style="margin-bottom: 5px;"></li>
							<li style="padding: 5px 0px;"></li>
							<li style="padding: 5px 0px;"></li>
							<li style="padding: 5px 0px;"></li>
							<li style="padding: 5px 0px;"></li>
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
							<tr>
								<td width="30%">
									<div style="padding: 10px;border: 1px solid #4d5d67;color: #000;font-size: 18px;font-weight: bold;text-align: center;background-color: #fff;height: 40px;width:60px;"></div>
								</td>
								<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('subcutaneous'); ?> <br><?php echo $this->lang->line('Immunotherapy'); ?> </td>
								<td width="30%"><img src="<?php echo base_url(); ?>assets/images/needle.png" alt="" width="60px" /></td>
							</tr>
							<tr>
								<td height="40"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?php }else{ ?>
			<table id="CreateImage3" class="table3" cellspacing="0" cellpadding="0" border="0" width="320" align="left" style="margin-left:30px; min-width:320px;display:none;">
				<tr>
					<th bgcolor="#326883" align="left" height="44" style="padding:0 20px; color:#ffffff; border-radius:10px 10px 0 0;"><?php if($this->user_role == '5' && ($this->session->userdata('user_type') == '1' || $this->session->userdata('user_type') == '2')){ ?><input type="radio" name="treatment" id="treatment3" value="3" /><?php } ?><span id="treatments3" style="padding: 1px 10px;border: 1px solid #4d5d67;color: #000;font-size: 18px;font-weight: bold;text-align: center;background-color: #fff;display:none;"></span> &nbsp; <?php echo $this->lang->line('compose_your_own'); ?></th>
				</tr>
				<tr>
					<td bgcolor="#e2f2f4" style="padding:20px;">
						<ol style="color:#184359; font-size:13px; margin-left:20px; padding:0;">
							<li style="margin-bottom: 5px;"></li>
							<li style="padding: 5px 0px;"></li>
							<li style="padding: 5px 0px;"></li>
							<li style="padding: 5px 0px;"></li>
							<li style="padding: 5px 0px;"></li>
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
							<tr>
								<td width="30%">
									<div style="padding: 10px;border: 1px solid #4d5d67;color: #000;font-size: 18px;font-weight: bold;text-align: center;background-color: #fff;height: 40px;width:60px;"></div>
								</td>
								<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;"><?php echo $this->lang->line('subcutaneous'); ?> <br><?php echo $this->lang->line('Immunotherapy'); ?> </td>
								<td width="30%"><img src="<?php echo base_url(); ?>assets/images/needle.png" alt="" width="60px" /></td>
							</tr>
							<tr>
								<td height="40"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?php } ?>
			<table width="100%"><tr><td height="20"></td></tr></table>
			<table width="70%" align="right" style="">
				<tr>
					<td height="150">&nbsp;</td>
					<td style="background:#def4f6; border-radius:120px 0 0 120px; padding:15px 15px 15px 150px;">
						<h6 style="color:#366784; margin:0 0 10px 0; padding:0; font-size:22px;"><?php echo $this->lang->line('positive_q12'); ?></h6>
						<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;"><?php echo $this->lang->line('Summary_bottom_1'); ?> <?php echo $this->lang->line('Summary_bottom_2'); ?>. <?php echo $this->lang->line('Summary_bottom_3'); ?></p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>