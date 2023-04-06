<style>
.diets tr th{font-weight:400;font-size:13px;padding:20px 5px 10px;width:20px;border-left:1px solid #9acfdb;border-bottom:3px solid #9acfdb;position:relative;height:auto;text-align:left}
.diets tr th.main-head{height: 120px;}
.diets tr th:first-child{white-space:nowrap;font-weight:700;font-size:18px}
.diets tr th:first-child,.diets tr td:first-child{border-left:0}
.diets tr td:first-child{text-align:left}
.diets tr th .rotate{transform:rotate(270deg);display:inline-block;text-align:left;position:absolute;left:0px;top: 85px;width: 100%;white-space: nowrap;}
.diets tr th .rotate.head-option{top: 60px;}
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
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>
			<table class="diets" width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<th style="color:#366784; width: 200px" valign="bottom"><?php echo $this->lang->line("nextmune");?><br><small><?php echo $this->lang->line("nextmune_sub1");?></small></th>
					<th class="main-head"><span class="rotate head-option"><?php echo $this->lang->line("wet_dry");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("beef");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("pork");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("lamb");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("duck");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("chicken");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("turkey");?></span></th>
					<th class="main-head"><span class="rotate"><?php echo $this->lang->line("venison");?></span></th>
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
				</tr>
				<tr>
					<td><?php echo $this->lang->line("solo_vegetal_800g");?></td>
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
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("solo_vegetal_1_5kg");?></td>
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("solo_vegetal_5kg");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
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
					<td><?php echo $this->lang->line("solo_vegetal_400kg");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
					<td><?php echo $this->lang->line("solo_vegetal_150kg");?></td>
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
					<td></td>
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
					<td><?php echo $this->lang->line("solo_cervo");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
					<td><?php echo $this->lang->line("solo_salmone");?></td>
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
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
					<td><?php echo $this->lang->line("solo_tacchino");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
					<td><?php echo $this->lang->line("solo_anatra");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
					<th colspan="22" valign="bottom" style="color:#366784;"><?php echo $this->lang->line("nextmune");?><br><small><?php echo $this->lang->line("nextmune_sub2");?></small></th>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("solo_vegetal_800g");?></td>
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
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("solo_vegetal_1_5kg");?></td>
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("solo_linea");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td colspan="20"><?php echo $this->lang->line("solo_linea_text");?></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_mini_tonno");?></td>
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
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_mini_agnello");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_mini_coniglio");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("adult_mini_maiale");?></td>
					<td><?php echo $this->lang->line("dry");?></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("quaglia_farro");?></td>
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
					<td></td>
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
					<td><?php echo $this->lang->line("direne_wd");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("epato_wd");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
				</tr>
				<tr>
					<td><?php echo $this->lang->line("enterofilus_wd");?></td>
					<td><?php echo $this->lang->line("wet");?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><img src="<?php echo base_url(); ?>assets/images/blank.png" alt=""/></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>