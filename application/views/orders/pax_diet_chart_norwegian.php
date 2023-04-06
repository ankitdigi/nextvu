<style>
.diets tr th{font-weight:400;font-size:13px;padding:20px 5px 10px;width:20px;border-left:1px solid #9acfdb;border-bottom:3px solid #9acfdb;position:relative;height:auto;text-align:left}
.diets tr th.main-head{height: 120px;}
.diets tr th:first-child{white-space:nowrap;font-weight:700;font-size:18px}
.diets tr th:first-child,.diets tr td:first-child{border-left:0}
.diets tr td:first-child{text-align:left}
.diets tr th .rotate{transform:rotate(270deg);display:inline-block;text-align:left;position:absolute;white-space:nowrap;left:1px;top: 85px;width: 100%}
.diets tr th .rotate.head-option{top: 70px;}
.diets tr td{border-left:1px solid #9acfdb;border-bottom:1px solid #9acfdb;font-size:13px;text-align:center;padding:5px}
</style>
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
			<table width="100%"><tr><td><h5 style="text-transform:uppercase; font-size:30px; margin:0;"><?php echo $this->lang->line("canine_veterinary_diets");?></h5></td></tr></table>
			<table width="100%">
				<tr>
					<td valign="middle" width="28"><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td valign="middle" style="line-height:0; font-size:14px;"> <span><?php echo $this->lang->line("ingredient_present");?><span></td>
				</tr>
				<tr>
					<td valign="middle" width="28"><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td valign="middle" style="line-height:0; font-size:14px;"> <span><?php echo $this->lang->line("hydrolysed");?><span></td>
				</tr>
				<tr>
					<td valign="middle" width="28"><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td valign="middle" style="line-height:0; font-size:14px;"> <span><?php echo $this->lang->line("starch_only");?><span></td>
				</tr>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>
			<table class="diets" width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<th style="text-transform:uppercase; color:#366784; width: 200px" valign="bottom"><?php echo $this->lang->line("nextmune");?></th>
					<th class="main-head"><span class="rotate head-option"><?php echo $this->lang->line("wet_dry");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("beef");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("pork");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("lamb");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("duck");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("chicken");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("turkey");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("venison");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("viltkjtt");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("rabbit");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("horse");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("salmon");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("white_fish");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("wheat");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("soya");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("barley");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("rice");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("potato");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("corn");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("oats");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("egg");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("cows_milk");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("insektprotein");?></span></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("solo_coniglio");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("solo_quaglia");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="24" valign="top" style="color:#366784;padding: 0px;margin: 0px;margin-top:5px;border:0px"><?php echo $this->lang->line("solo_attention");?></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("acana");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_amall_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_dog");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_large_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sport_agility");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("light_fit");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("senior_dog");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("prairie_poultry");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("wild_coast");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("classic_red");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("wild_prairie");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("pacifica");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("grasslands");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("ranchilands");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("grass_fed_lamb");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("yorkshire_pork");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("pacific_pilchard");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("life_veterinay");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("insect_protein");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("eukanuba");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("premium_performance_21_13");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("premium_performance_30_20");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("premium_performance_26_16");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("premium_performance_30_28");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("weight_control_small_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("weight_control_medium_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("weight_control_large_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_samll_breed_dog");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_small_bites_dry_dog");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_lamb");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_large_breed_lamb");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("senior_lamb");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("senior_medium_breed_dry_dog");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("senior_large_breed_dry_dog");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("happy_dog");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_junior_lamb_rice");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_india");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_greece");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_andalucia");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_lombardia");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_toscana");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_ireland");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_karibik");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_france");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_neuseeland");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_montana");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_canada");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_africa");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensible_piemonte");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sano_n");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_hypersensitivity");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_struvit");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_struvit");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_hepatic");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_intestinal_low_fat");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_intestinal");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_intestinal");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_renal");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_renal");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_skin_protect");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_skin_protect");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_adipositas");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_adipositas");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_urinary_low_purine");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("vet_mobility");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("farmina");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("farmina_diet_1");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("farmina_diet_2");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("farmina_diet_3");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("hills_pet_nutrition");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("derm_defense");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("derm_defense_stew");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("z_d");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("z_d");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("d_d_duck_rice");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("d_d_salmon_rice");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("d_d_duck");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("d_d_salmon");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("i_d");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("i_d_stew");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("i_d");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("i_d_sensitive");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("i_d_low_fat");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("i_d_low_fat_stew");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("i_d_low_fat");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("science_plan_sensitive_stomach_skin");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("science_plan_small_mini_stomach_skin");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("purina");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_drm");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_dm_diabetes");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_en_gastrointestinal");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_en_gastrointestinal");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_om_obesity");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_om_obesity");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_jm_joint_mobility");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_nc_neurocare");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_cn_convalescence");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_hp_hepatic");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_nf_renal");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_nf_renal");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_ur_urinary");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_ha_hypoallergenic");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("canine_ha_hypoallergenic");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("royal_canin");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("anallergenic_contains_feather_hydrolysate_with_very_low_molecular_weight");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("hypoallergenic_hypoallergenic_hypoallergenic_moderate_calorie_and_hypoallergenic_small_dogs");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("hypoallergenic");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("renal_hypoallergenic_multifunction_diet");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("urinary_hypoallergenic_multifunction_diet");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensitivity_control_duck_with_tapioca");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensitivity_control_Chicken_with_rice");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("sensitivity_control_duck_with_rice");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("skin_care_skin_care_skin_care_small_dogs_skin_care_small_dogs_puppy");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("specific");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("active_dog");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("active_dog");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_all_breeds");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_large_giant_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_medium_bree");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_small_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_organic");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_organic_beef");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_organic_fish");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("senior_all_breeds");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("senior_large_giant_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("senior_medium_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("senior_small_breed");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("food_allergen_management");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("food_allergen_management");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("allergen_management_plus");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("allergen_management_plus");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("struvite_management");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("digestive_support");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("digestive_support");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("digestive_support_low_fat");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("endocrine_support");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("heart_kidney_support");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("heart_kidney_support");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("intensive_support");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("joint_support");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("skin_support");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("weight_control");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("weight_reduction");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("weight_reduction");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<th colspan="24" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("virbac_veteinary_hpm");?></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("digestive_support");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("dermatology_support");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("hypoallergy_1");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" /></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("hypoallergy_2");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>
			<table width="100%"><tr><td><?php echo $this->lang->line("diet_disclaimer");?></td></tr></table>
			<table width="100%"><tr><td height="20"></td></tr></table>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>