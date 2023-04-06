
<!DOCTYPE html>
<html class="js">
	<head>
		<title><?php echo $this->lang->line('serum_test_result'); ?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page {margin:0mm;}
		/*html {margin:5px 15px;padding:0px}*/
		*{font-family:'calibri',sans-serif}
		table{font-family:'calibri';}
		.header th{text-align:left}
		.green_strip{background:#bed600;padding:5px 10px;color:#fff;font-size:18px}
		.green_bordered{border:1px solid #bed600;padding:10px;color:#333;font-size:18px}
		.blob1{background:#becedb}
		.blob2{background:#9acfdb}
		.blob3{background:#59c0d3}
		.blob4{background:#366784}
		.blob5{background:#273945}
		.light-grey-bg{background:#f2f5f8}
		.grey-bg{background:#becedb}
		.aqua-bg{background:#9acfdb}
		.cgreen-bg{background:#366784}
		.cgreen-dark-bg{background:#273945}
		.meter{min-width:120px;max-width:120px}
		.meter tr td{font-size:13px;line-height:11px}
		.meter tr td:nth-child(2){border-radius:10px 0 0 10px}
		.meter tr td:last-child{border-radius:0 10px 10px 0}
		.index-capsule{width:140px;height:24px;border-radius: 40px 40px 40px 40px;}
		.light-lemon-bg{background:#eaf4e3}
		.lemon-bg{background:#9fd08a}
		.military-bg{background:#666c3e}
		.red-bg{background:#d1232a}
		.mehroon-bg{background:#b02a2f}
		.green-bg{background:#40ae49}
		.orange-bg{background:#f58220}
		.diets tr th{font-weight:400;font-size:13px;padding:20px 5px 10px;width:20px;border-left:1px solid #9acfdb;border-bottom:3px solid #9acfdb;position:relative;height:100px;text-align:left}
		.diets tr th:first-child{white-space:nowrap;font-weight:700;font-size:18px}
		.diets tr th:first-child,.diets tr td:first-child{border-left:0}
		.diets tr td:first-child{text-align:left}
		.diets tr th .rotate{transform:rotate(270deg);display:block;transform-origin:center bottom;text-align:left;position:absolute;white-space:nowrap;left:2px;bottom:40px;text-align:left}
		.diets tr td{border-left:1px solid #9acfdb;border-bottom:1px solid #9acfdb;font-size:13px;text-align:center;padding:5px}
		/*#test {
		    background-color:blue;width:450px;height:800px;z-index:50;
		    border-radius: 0px 0px 100px 100px;
		}*/
		</style>
	</head>
	<body bgcolor="#fff">
				
		<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; border-bottom:2px solid #366784;background:url(assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;/*margin:40px;*/">
			<tr>
				<td valign="middle" width="400" style="padding-left: 40px;padding-right: 20px;padding-top: 40px;">
					<img src="assets/images/pax-logo.png" alt="Logo" style="max-height:100px; max-width:300px; border-radius:4px;margin-bottom:60px !important; " />
					<!-- <img src="assets/images/blank_1.png" alt="Logo" style="max-height:130px; max-width:360px; border-radius:4px;" />
					<img src="assets/images/blank_img.png" alt="Logo" style="max-height:130px; max-width:360px; border-radius:4px;" />
					 -->
					<!-- <h5 style="font-weight:700; font-size:28px; color:#366784;">PAX Environmental</h5> -->
				</td>
				<td rowspan="2" valign="middle" style="padding-right: 40px;padding-top: 40px;">
					<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:10px; line-height:14px;font-family:'calibri';">
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('owner_name'); ?>:</th>
							<td style="color:#000000;"> <?php echo $this->lang->line('faz'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('animal_name'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('dave'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('species'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('dog'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('veterinarian'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('uk_practice_user'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('veterinary_practice'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('test_practice_nextmune_vets'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('address'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('1_vet_practice_address_2_vet_practice_address_3_vet_practice_addressIn_a_townAB12_3cd'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('phone_fax'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('01234_5678'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('email'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('stewart_practiceuk@yopmail_com'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('test_type'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('pax_environmental'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('date_tested'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('06_12_2022'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('laboratory_code'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('90AAC2E9'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('test_number'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('15159813'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('customer_number'); ?>:</th>
							<td style="color:#000000;"><?php echo $this->lang->line('43663'); ?></td>
						</tr>
						<tr>
							<th style="color:#1e3743;"><?php echo $this->lang->line('laboratory'); ?></th>
							<td style="color:#000000;"><?php echo $this->lang->line('nextmune_spain_netherlands_uk'); ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td valign="middle" width="400" style="padding-left: 40px;padding-right: 20px;padding-top: 40px;">
					<!-- <img src="assets/images/pax-logo.png" alt="Logo" style="max-height:120px; max-width:340px; border-radius:4px;margin-bottom:60px !important; " /> -->
					<!-- <img src="assets/images/blank_1.png" alt="Logo" style="max-height:130px; max-width:360px; border-radius:4px;" />
					<img src="assets/images/blank_img.png" alt="Logo" style="max-height:130px; max-width:360px; border-radius:4px;" />
					 -->
					<h5 style="font-weight:700; font-size:28px; color:#366784;">
					<?php echo $this->lang->line('pax_environmental'); ?></h5>
				</td>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:40px 40px 5px;">
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:20px;"><?php echo $this->lang->line('serum_test_result'); ?><?php echo $this->lang->line('summary_on_detectable_sensitisations'); ?></h4>
				</td>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-left:35px;margin-right:35px;">
		    <tr>
		        <td width="48%" valign="top" style="width:350px;vertical-align:top;">
		        	<table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 11px;">
		                <tr>
		                    <td colspan="2" style="padding:0 0 5px 0;">
		                        <h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;"><?php echo $this->lang->line('grass_pollens'); ?></h5>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('bermuda_grass'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">	<?php echo $this->lang->line('kentucky_blue_grass'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">	<?php echo $this->lang->line('28'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">	<?php echo $this->lang->line('meadow_fescue'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">	<?php echo $this->lang->line('36'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">	<?php echo $this->lang->line('orchard_grass'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">	<?php echo $this->lang->line('39'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">	<?php echo $this->lang->line('perennial_ryegrass'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">	<?php echo $this->lang->line('20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
								<?php echo $this->lang->line('ryegrass_cultivated'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">	<?php echo $this->lang->line('40'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('timothy_grass'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('22'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		            </table>
		            <table>
		                <tr>
		                    <td height="5"></td>
		                </tr>
		            </table>
		            <table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 11px;">
		                <tr>
		                    <td colspan="2" style="padding:0 0 5px 0;">
		                        <h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;"><?php echo $this->lang->line('tree_pollen'); ?></h5>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('alder'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('130'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('birch'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('birch_22'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('hazel'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('hazel_22'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('cypress'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('cypress_28'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('beech'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('beech_27');?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('ash'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('ash_22'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('privet'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('privet_23'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('olive_tree'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('olive_tree_24'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('plane_tree'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('plane_tree_25'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('cottonwood'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('cottonwood_25'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('elm'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('elm_21'); ?>21</td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		            </table>
		            <table>
		                <tr>
		                    <td height="5"></td>
		                </tr>
		            </table>
		            <table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 11px;">
		                <tr>
		                    <td colspan="2" style="padding:0 0 5px 0;">
		                        <h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;"><?php echo $this->lang->line('mites_cockroaches'); ?></h5>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('acarus_siro'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('acarus_siro_26'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('american_cockroach'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('american_cockroach_45'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('blomia_tropicalis'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('blomia_tropicalis_37'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('dermatophagoides_farinae'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('dermatophagoides_farinae_93'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('dermatophagoides_pteronyssinus'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('dermatophagoides_pteronyssinus_86'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('flea'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('flea_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('german_cockroach'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('german_cockroach_45'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('glycyphagus_domesticus'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('glycyphagus_domesticus_23'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('lepidoglyphus_destructor'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('lepidoglyphus_destructor_21'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('tyrophagus_putrescentiae'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('tyrophagus_putrescentiae_28'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		            </table>
		        </td>
		        <td width="4%" valign="top"></td>
		        <td width="48%" valign="top" style="width:350px;vertical-align:top;">
		        	<table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 14px;">
		                <tr>
		                    <td colspan="2" style="padding:0 0 5px 0;">
		                        <h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;"><?php echo $this->lang->line('well_pollen'); ?></h5>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('ragweed'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('ragweed_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('mugwort'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('mugwort_22'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('lambs_quarter'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('lambs_quarter_21'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('wall_pellitory'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('wall_pellitory_17'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('english_plantain'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('english_plantain_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('dock_sorrel'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('dock_sorrel_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('russian_thistle'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('russian_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('nettle'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('nettle_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		            </table>
		        	<table>
		                <tr>
		                    <td height="5"></td>
		                </tr>
		            </table>
		            <table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 14px;">
		                <tr>
		                    <td colspan="2" style="padding:0 0 5px 0;">
		                        <h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;"><?php echo $this->lang->line('danders_epithelia'); ?></h5>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('cat'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('cat_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('cattle'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('cattle_22'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('dog'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('dog_21'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('guinea_pig'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('guinea_pig_17'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('horse'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('horse_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('mouse'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('mouse_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('rabbit'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('rabbit_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		            </table>
		            <table>
		                <tr>
		                    <td height="5"></td>
		                </tr>
		            </table>
		            <table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 14px;">
		                <tr>
		                    <td colspan="2" style="padding:0 0 5px 0;">
		                        <h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;"><?php echo $this->lang->line('moulds_yeast'); ?></h5>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('alternaria_alternata'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('alternaria_alternata_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('aspergillus_fumigatus'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('aspergillus_fumigatus_22'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('cladosporium_herbarum'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('cladosporium_herbarum_21'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('malassezia_pachydermatis'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('malassezia_pachydermatis_17'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('malassezia_sympodialis'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('malassezia_sympodialis_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		            </table>
		            <table>
		                <tr>
		                    <td height="5"></td>
		                </tr>
		            </table>
		            <table cellpadding="0" cellspacing="0" border="0" width="100%;" style="font-size: 14px;">
		                <tr>
		                    <td colspan="2" style="padding:0 0 5px 0;">
		                        <h5 style="margin:0; padding:0; font-size:18px; text-transform:uppercase; color:#2a5b74;"><?php echo $this->lang->line('insects_venons'); ?></h5>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('honey_bee_venom'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('honey_bee_venom_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;">
							<?php echo $this->lang->line('long_headed_wasp_venom'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('long_headed_wasp_venom_22'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('paper_wasp_venom'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('paper_wasp_venom_21'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('fire_ant_venom'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('fire_ant_venom_17'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		                <tr>
		                    <td style="padding:0 15px 0 0;font-size:13px;"><?php echo $this->lang->line('common_wasp_venom'); ?></td>
		                    <td style="padding:0 0 0 15px;">
		                        <table cellpadding="0" cellspacing="0" align="right" class="meter">
		                            <tr>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;"><?php echo $this->lang->line('common_wasp_venom_20'); ?></td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
		                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
		                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
		                                </td>
		                            </tr>
		                        </table>
		                    </td>
		                </tr>
		            </table>
		        </td>
		    </tr>
		</table>
		<table>
            <tr>
                <td height="40"></td>
            </tr>
        </table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-left:40px;margin-right:40px;">
			<tr>
				<td>
					<p style="margin:0 0 10px 0; color:#2a5b74; font-size:11px;"><?php echo $this->lang->line('the_results_above_represent_a_summary_of_the_sensitisations_detected_with_extracts_and_components_for_each_allergen_source_detailed_results_can_be_found_in_the_following_pages'); ?></p>
					<h4 style="margin:0; color:#2a5b74; font-size:13px;"><?php echo $this->lang->line('highest_measured_IgE_concentration_per_allergen_group'); ?></h4>
				</td>
			</tr>
		</table>
		<!-- <table width="100%" border="0" cellpadding="20px">
		<tr>
		    <td><div style="display: inline-block;background-color:blue;width:15px;height:15px;border-radius: 50px 50px 50px 50px;">Test</div></td>
		    <td><span style="width:15px;height:15px;background-color:#f5b041;border : 1px solid #000;border-radius:50px;margin-right:05px;padding:2em;">Demo2</span></td>
		    <td><span style="width:15px;height:15px;background-color:#e74c3c;border-radius:50px;margin-right:05px;padding:2em;">Demo3</span></td>
		</tr>
		</table> -->
		<!-- <div style="background-color:blue;width:15px;height:15px;border-radius: 50px 50px 50px 50px;">Test</div>
		</div> -->
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:20px;">
			<tr>
				<td style="padding:0 10px 0 0;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:12px;">&lt;
						<?php echo $this->lang->line('30_00_ng_mL'); ?> </td></tr>
						<tr><td class=" "><img src="assets/images/class_0.png" style="height: 18px;" alt="class 0" /></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class_0'); ?></td></tr>
					</table>
				</td>
				<td style="padding:0 15px 0 15px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:12px;">
						 <?php echo $this->lang->line('30_00_99_99_ng_mL'); ?></td></tr>
						<tr><td class=" "><img src="assets/images/class_1.png" style="height: 18px;" alt="class 1" /></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class_1'); ?></td></tr>
					</table>
				</td>
				<td style="padding:0 15px 0 15px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:12px;">
						<?php echo $this->lang->line('100_00_399_99_ng_mL'); ?></td></tr>
						<tr><td class=" "><img src="assets/images/class_2.png" style="height: 18px;" alt="class 2" /></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class_2'); ?></td></tr>
					</table>
				</td>
				<td style="padding:0 15px 0 15px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:12px;">
						<?php echo $this->lang->line('400_00_799_99_ng_mL'); ?></td></tr>
						<tr><td class=" "><img src="assets/images/class_3.png" style="height: 18px;" alt="class 3" /></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center"><?php echo $this->lang->line('class_3'); ?></td></tr>
					</table>
				</td>
				<td style="padding:0 0 0 15px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
						<tr><td align="center" style="padding:0 0 5px 0;font-size:12px;">&#8805;
						<?php echo $this->lang->line('800_00_ng_mL'); ?> </td></tr>
						<tr><td class=" "><img src="assets/images/class_4.png" style="height: 18px;" alt="class 4" /></td></tr>
						<tr><td style="font-size:13px; padding:5px 0 0 0;" align="center">
						<?php echo $this->lang->line('class_4'); ?></td></tr>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%"><tr><td height="10"></td></tr></table>
		<table style="width:100%;">
	<tbody>
		<tr><td width="30%" align="left" style="font-size:11px;text-align:left;padding-left: 40px;">
			<!-- <img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; Allergen Extract --></td>
			<td width="60%" align="left" style="font-size:11px;text-align:left;">
			<!-- <img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; Molecular Allergen --></td>
			<td width="10%" style="background:url(assets/images/footer_page.png) center top no-repeat #ffffff;text-align:right;border-radius: 40px 0px 0px 40px;height: 100%;background-size: cover;background-repeat: repeat-x;background-position-y: center;color: #ffffff;font-weight: bold;text-align: center;/* padding: 10px; *//* vertical-align: middle; */"><?php echo $this->lang->line('pageno'); ?></td>
		</tr>
		</tbody>
	</table> 
		
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:10px 40px;padding:0px;border:none;">
	<tr>
		<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('dave'); ?> </b> <?php echo $this->lang->line('faz'); ?> </th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $this->lang->line('43663'); ?></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b><?php echo $this->lang->line('15159813'); ?> </th>
	</tr>
</table>

<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page {margin:0mm;}
		/*html {margin:5px 15px;padding:0px}*/
		*{font-family:'Open Sans',sans-serif}
		.header th{text-align:left}
		.green_strip{background:#bed600;padding:5px 10px;color:#fff;font-size:18px}
		.green_bordered{border:1px solid #bed600;padding:10px;color:#333;font-size:18px}
		.blob1{background:#becedb}
		.blob2{background:#9acfdb}
		.blob3{background:#59c0d3}
		.blob4{background:#366784}
		.blob5{background:#273945}
		.light-grey-bg{background:#f2f5f8}
		.grey-bg{background:#becedb}
		.aqua-bg{background:#9acfdb}
		.cgreen-bg{background:#366784}
		.cgreen-dark-bg{background:#273945}
		.meter{min-width:120px;max-width:120px}
		.meter tr td{font-size:13px;line-height:11px}
		.meter tr td:nth-child(2){border-radius:10px 0 0 10px}
		.meter tr td:last-child{border-radius:0 10px 10px 0}
		.index-capsule{width:140px;height:24px;border-radius: 40px 40px 40px 40px;}
		.light-lemon-bg{background:#eaf4e3}
		.lemon-bg{background:#9fd08a}
		.military-bg{background:#666c3e}
		.red-bg{background:#d1232a}
		.mehroon-bg{background:#b02a2f}
		.green-bg{background:#40ae49}
		.orange-bg{background:#f58220}
		.diets tr th{font-weight:400;font-size:13px;padding:20px 5px 10px;width:20px;border-left:1px solid #9acfdb;border-bottom:3px solid #9acfdb;position:relative;height:100px;text-align:left}
		.diets tr th:first-child{white-space:nowrap;font-weight:700;font-size:18px}
		.diets tr th:first-child,.diets tr td:first-child{border-left:0}
		.diets tr td:first-child{text-align:left}
		.diets tr th .rotate{transform:rotate(270deg);display:block;transform-origin:center bottom;text-align:left;position:absolute;white-space:nowrap;left:2px;bottom:40px;text-align:left}
		.diets tr td{border-left:1px solid #9acfdb;border-bottom:1px solid #9acfdb;font-size:13px;text-align:center;padding:5px}
		/*#test {
		    background-color:blue;width:450px;height:800px;z-index:50;
		    border-radius: 0px 0px 100px 100px;
		}*/
		</style>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0px 40px;">
					<tr>
						<td>
							<h4 style="margin:0; color:#2a5b74; font-size:20px;">
							<?php echo $this->lang->line('summary_and_immunotherapy_recommendation'); ?></h4>
						</td>					
					</tr>
				</table>
				
				<table width="100%" cellspacing="0" cellpadding="0" border="0" style="/*background:#f5fafb; border-radius:10px;*/margin: 0px 40px; font-size: 14px;margin:0px 40px;"><tr><td style="padding:30px;">
					<!-- <p style="margin-top:0;">This patient is sensitised to Alder ,Cockroach, English Plantain, Alternaria alternata, Malassezia sympodialis.</p>
					<p>&nbsp;</p>
					<p>If the corresponding clinical signs occur, allergen-specific immunotherapy is recommended for: Alder, Cockroach, English Plantain, Alternaria alternata and Malassezia sympodialis</p>
					<p>&nbsp;</p>
					<p>Please find full interpretation and detailed results per allergen extract and component in the
						following pages</p>
						<p>&nbsp;</p>
					<p>Based on these results we recommend the following immunotherapy composition(s):</p>
					
					<p>&nbsp;</p> -->
					<img style="width:100%"  src="assets/images/pax_pdf/imminotherapy.png" alt="" >
				</td></tr></table>
				
				
				<table width="100%"><tr><td height="10"></td></tr></table>
				
				<table width="100%" cellspacing="0" cellpadding="0" border="0"  style="margin: 0px 40px;">
					<tr>
						<td >
							<img style="width:100%" src="assets/images/pax_pdf/option123.png" alt="" >
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0" border="0" width="100%" align="right" >
					<tr style="text-align: right;">
						<td style="text-align: right;">
							<img style="width:100%;height:150px;" src="assets/images/pax_pdf/did_bgtext.png" alt="" >
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0" border="0" style="width:100%;page-break-before:avoid;">
					<tbody>
						<tr><td width="30%" align="left" style="font-size:11px;text-align:left;padding-left: 40px;">
							<!-- <img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; Allergen Extract --></td>
							<td width="60%" align="left" style="font-size:11px;text-align:left;">
							<!-- <img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; Molecular Allergen --></td>
							<td width="10%" style="background:url(assets/images/footer_page.png) center top no-repeat #ffffff;text-align:right;border-radius: 40px 0px 0px 40px;height: 100%;background-size: cover;background-repeat: repeat-x;background-position-y: center;color: #ffffff;font-weight: bold;text-align: center;/* padding: 10px; *//* vertical-align: middle; */"><?php echo $this->lang->line('pageno'); ?></td>
						</tr>
					</tbody>
				</table> 
			<!-- <table width="100%"  style="margin: 0px 40px;">
				<tr>
					<td>
			
						<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="left" style="margin-left:40px;margin-left: 20px;">
								<tr>
									<th bgcolor="#326883" align="left" height="44" style=""><img src="assets/images/op_1.png" alt="Logo" style=" " /></th>
								</tr>
								<tr>
									<td height="220" bgcolor="#e2f2f4" style="padding:20px;">
										
										<ol style="color:#184359; font-size:20px; margin:15px 0 0 20px; padding:0;">
											<li>Alder</li>
											<li>Cockroach</li>
										<li>English Plantain</li>
										<li>Alternaria alternata</li>
										<li>Malassezia</li>
										<li></li>
										<li></li>
										<li></li>
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
								<td bgcolor="#b4dee5" align="left" height="44" style="padding:0 20px; color:#ffffff;">
								
									<table cellpadding="0" cellspacing="0" border="0" width="100%;">
									
										<tr>
											<td height="20"></td>
										</tr>
										<tr>
											<th colspan="3" align="left" style="color:#303846;">This option results in:</th>
										</tr>
										<tr><td height="8"></td></tr>
										<tr>
											<td width="30%"><input type="text" placeholder="" style="background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;" /></td>
											<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;">Subcutaneous <br>immuno therapy </td>
											
										</tr>
										<tr>
											<td height="40"></td>
										</tr>
									</table>
								
								</td>
							</tr>
						</table>
						
					</td>
					<td>
						<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="left" style=" margin-left:20px;">
							<tr>
								<th bgcolor="#326883" align="left" height="44" style=""><img src="assets/images/op_2.png" alt="Logo" style=" " /></th>
							</tr>
							<tr>
								<td height="220" bgcolor="#e2f2f4" style="padding:20px;">
									
									<ol style="color:#184359; font-size:20px; margin:15px 0 0 20px; padding:0;">
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
											<th colspan="3" align="left" style="color:#303846;">This option results in:</th>
										</tr>
										<tr><td height="8"></td></tr>
										<tr>
											<td width="30%"><input type="text" placeholder="" style="background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;" /></td>
											<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;">Subcutaneous <br>immuno therapy </td>
											
										</tr>
										<tr>
											<td height="40"></td>
										</tr>
									</table>
								
								</td>
							</tr>
						</table>
						
					</td>	
					<td>	
						<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style=" margin-left:20px;">
							<tr>
								<th bgcolor="#326883" align="left" height="44" style=""><img src="assets/images/op_3.png" alt="Logo" style=" " /></th>
							</tr>
							<tr>
								<td height="220" bgcolor="#e2f2f4" style="padding:20px;">
									
									<ol style="color:#184359; font-size:20px; margin:15px 0 0 20px; padding:0;">
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
											<th colspan="3" align="left" style="color:#303846;">This option results in:</th>
										</tr>
										<tr><td height="8"></td></tr>
										<tr>
											<td width="30%"><input type="text" placeholder="" style="background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;" /></td>
											<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;">Subcutaneous <br>immuno therapy </td>
											
										</tr>
										<tr>
											<td height="40"></td>
										</tr>
									</table>
								
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table> -->	
						
			
			
			
			
			<table width="100%"><tr><td height="50"></td></tr></table>
			
			
			<table width="100%"><tr><td height="40"></td></tr></table>
			<table width="40%" align="right" >
				<tr>
					<td align="right"><img src="assets/images/did_bgtext.png" alt="" style=" " width="450" /></td>
				</tr>
			</table>
			<!-- <table width="50%" align="right" style="">
				<tr>
					<td height="250"><img src="assets/images/radius1.png" alt="" style=" " /></td>
					<td style="background:#def4f6; border-radius:120px 0 0 120px; padding:15px 15px 15px 150px;">
						<h6 style="color:#366784; margin:0 0 10px 0; padding:0; font-size:22px;">Do you need support?</h6>
						<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;">If you have any questions or need support, please contact our technical department by phone
<a href="tel:+31 320 783 100" style="color:#366784; text-decoration:none; font-weight:700;">+31 320 783 100</a> or by email <a style="color:#366784; text-decoration:none; font-weight:700;" href="mailto:info.eu@nextmune.com">info.eu@nextmune.com</a> You can find more information about immunotherapy and how to start the treatment in the next page</p>
				
					</td>
				</tr>
			</table> -->

			<!-- <table width="100%"><tr><td height="20"></td></tr></table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0px 40px;">
				<tr>
					<td>
						<h4 style="margin:0; color:#2a5b74; font-size:20px;">Summary and immunotherapy recommendation</h4>
					</td>					
				</tr>
			</table>
			<table width="100%"><tr><td height="3"></td></tr></table>
			
			<table width="100%" style="background:#f5fafb; border-radius:10px;margin: 0px 40px;"><tr><td style="padding:30px;">
				<p>This patient is sensitized to German cockroach, American cockroach, Dermatophagoides pteronyssinus, Dermatophagoides farinae, Meadow fescue, Blomia tropicalis, Orchard grass and Ryegrass, cultivated.</p>
				<p>&nbsp;</p>
				<p>If the corresponding clinical signs occur, allergen-specific immunotherapy is recommended for: German cockroach, American cockroach, Dermatophagoides pteronyssinus, Dermatophagoides farinae, Meadow fescue, Orchard grass and Ryegrass, cultivated.</p>
				<p>&nbsp;</p>
				<p>Allergen-specific immunotherapy is currently not available for Blomia tropicalis; the treatment is symptomatic.</p>
				<p>&nbsp;</p>
				<p>Please find full interpretation and detailed results per allergen extract and component in the following pages.</p>
				<p>&nbsp;</p>
				<p>Based on these results we recommend the following immunotherapy composition(s):</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</td></tr></table>
			
			
			<table width="100%"><tr><td height="50"></td></tr></table>
			
			
			<table width="100%"  style="margin: 0px 40px;">
				<tr>
					<td>
			
						<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="left" style="margin-left:40px;margin-left: 20px;">
								<tr>
									<th bgcolor="#326883" align="left" height="44" style=""><img src="assets/images/treatment_option1.png" alt="Logo" style=" " /></th>
								</tr>
								<tr>
									<td height="220" bgcolor="#e2f2f4" style="padding:20px;">
										
										<ol style="color:#184359; font-size:16px; margin:15px 0 0 20px; padding:0;">
											<li>Cockroach (Periplaneta americana)</li>
											<li>Cockroach (Periplaneta americana)</li>
											<li>Dermatophagoides pteronyssinus (European house dus</li>
																			<li>
											Farinae Mite (Dermatophagoides farinae)										</li>
																			<li>
											Festuca pratensis )										</li>
																			<li>
											Dactylis glomerata)										</li>
																			<li>
											cultivated (Secale cereale)										</li>							
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
											<th colspan="3" align="left" style="color:#303846;">This option results in:</th>
										</tr>
										<tr><td height="8"></td></tr>
										<tr>
											<td width="30%"><input type="text" placeholder="" style="background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;" /></td>
											<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;">Subcutaneous <br>immuno therapy </td>
											
										</tr>
										<tr>
											<td height="40"></td>
										</tr>
									</table>
								
								</td>
							</tr>
						</table>
						
					</td>
					<td>
						<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="left" style=" margin-left:20px;">
							<tr>
								<th bgcolor="#326883" align="left" height="44" style=""><img src="assets/images/treatment_option2.png" alt="Logo" style=" " /></th>
							</tr>
							<tr>
								<td height="220" bgcolor="#e2f2f4" style="padding:20px;">
									
									<ol style="color:#184359; font-size:16px; margin:15px 0 0 20px; padding:0;">
										<li>Cockroach (Periplaneta americana)</li>
										<li>Cockroach (Periplaneta americana)</li>
										<li>Dermatophagoides pteronyssinus (European house dus</li>
										<li>House Dust/Farinae Mite (Dermatophagoides farinae)</li>
										<li>Meadow fescue (Festuca pratensis )</li>
										<li>Orchard grass (Dactylis glomerata)</li>	
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
											<th colspan="3" align="left" style="color:#303846;">This option results in:</th>
										</tr>
										<tr><td height="8"></td></tr>
										<tr>
											<td width="30%"><input type="text" placeholder="" style="background:#e4eaed; padding:0 10px; height:36px; border:1px solid #4d5d67; width:60px;" /></td>
											<td width="40%" style="padding:0 10px; white-space:nowrap; color:#303846; font-size:13px;">Subcutaneous <br>immuno therapy </td>
											
										</tr>
										<tr>
											<td height="40"></td>
										</tr>
									</table>
								
								</td>
							</tr>
						</table>
						
					</td>	
					
				</tr>
			</table> -->	
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:40px;padding:0px;border:none;">
	<tr>
		<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('dave'); ?> </b>  <?php echo $this->lang->line('faz'); ?></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b><?php echo $this->lang->line('43663'); ?> </th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b><?php echo $this->lang->line('15159813'); ?> </th>
	</tr>
</table>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:5mm;padding:0px;border:none;">
			<tr>
				<!-- <th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px 10px 10px 30px; border-radius:30px 0 0 30px;">Common Name</th>
				<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;"><i style="font-weight:400;">Scientific name</i></th>
				<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;">E/M Allergen</th>
				<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;">Function</th>
				<th align="right" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px 30px 10px 10px; border-radius:0 30px 30px 0">ng/mL</th> -->
				<th colspan="4"><img style="height:80px" src="assets/images/graph_header.png" alt="class 3" /></th>
			</tr>
		</table>
<style>
@page {margin:0mm;}
html {margin:5px 15px;padding:0px}
*{font-family:'Open Sans',sans-serif}
.header th{text-align:left}
.green_strip{background:#bed600;padding:5px 10px;color:#fff;font-size:18px}
.green_bordered{border:1px solid #bed600;padding:10px;color:#333;font-size:18px}
.blob1{background:#becedb}
.blob2{background:#9acfdb}
.blob3{background:#59c0d3}
.blob4{background:#366784}
.blob5{background:#273945}
.light-grey-bg{background:#f2f5f8}
.grey-bg{background:#becedb}
.aqua-bg{background:#9acfdb}
.cgreen-bg{background:#366784}
.cgreen-dark-bg{background:#273945}
.meter{min-width:120px;max-width:120px}
.meter tr td{font-size:13px;line-height:11px}
.meter tr td:nth-child(2){border-radius:10px 0 0 10px}
.meter tr td:last-child{border-radius:0 10px 10px 0}
.index-capsule{width:140px;height:24px;border-radius: 40px 40px 40px 40px;}
.light-lemon-bg{background:#eaf4e3}
.lemon-bg{background:#9fd08a}
.military-bg{background:#666c3e}
.red-bg{background:#d1232a}
.mehroon-bg{background:#b02a2f}
.green-bg{background:#40ae49}
.orange-bg{background:#f58220}
.diets tr th{font-weight:400;font-size:13px;padding:20px 5px 10px;width:20px;border-left:1px solid #9acfdb;border-bottom:3px solid #9acfdb;position:relative;height:100px;text-align:left}
.diets tr th:first-child{white-space:nowrap;font-weight:700;font-size:18px}
.diets tr th:first-child,.diets tr td:first-child{border-left:0}
.diets tr td:first-child{text-align:left}
.diets tr th .rotate{transform:rotate(270deg);display:block;transform-origin:center bottom;text-align:left;position:absolute;white-space:nowrap;left:2px;bottom:40px;text-align:left}
.diets tr td{border-left:1px solid #9acfdb;border-bottom:1px solid #9acfdb;font-size:13px;text-align:center;padding:5px}
/*#test {
    background-color:blue;width:450px;height:800px;z-index:50;
    border-radius: 0px 0px 100px 100px;
}*/
</style>

<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:40px 40px 20px;padding:0px;border:none;">
	<tr>
		<!-- <th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px 10px 10px 30px; border-radius:30px 0 0 30px;">Common Name</th>
		<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;"><i style="font-weight:400;">Scientific name</i></th>
		<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;">E/M Allergen</th>
		<th align="left" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px;">Function</th>
		<th align="right" bgcolor="#9acfdb" style="color:#ffffff; font-size:16px; padding:10px 30px 10px 10px; border-radius:0 30px 30px 0">ng/mL</th> -->
		<!-- <th colspan="4"><img style="height:80px" src="assets/images/graph_header.png" alt="class 3" /></th> -->
	</tr>
	<tr>
		<td>
			<h4 style="margin:0; color:#2a5b74; font-size:20px;text-transform:uppercase;"><?php echo $this->lang->line('pax_environmental_penal'); ?></h4>
		</td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
		<tr>
			<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:15px; padding:0 10px 10px 0px;margin-left:10px"><?php echo $this->lang->line('danders_epithelia'); ?></th>					
		</tr>
	</table>
	<div style="margin: 0px 40px;"><hr style="border-top: 2px solid #3a6a86;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('cat'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('felis_domesticus'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('fel_d_1'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('uteroglobin'); ?></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line(' fel_d_2'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('serum_albumin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('fel_d_4'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"> <?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"> <?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;  <?php echo $this->lang->line('fel_d_7'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('19'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('cattle'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('bos_domesticus'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('bos_d_2'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('22'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('dog'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('canis_familiaris'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('can_f_1'); ?></td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('17'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('can_f_2'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('21'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('can_f_3'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('serum_albumin'); ?> </td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('can_f_4'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('21'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('can_f_6'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('18'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('can_f_male_urine'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('18'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('can_f_Fd1'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('fel_d_1_like'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('guinea_pig'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('cavia_porcellus'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('cav_p_1'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('17'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('horse'); ?> </td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('equus_caballus'); ?> </td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('equ_c_1'); ?>  </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('19'); ?>  </td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('equ_c_3'); ?>   </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('serum_albumin'); ?>  </td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('17'); ?>   </td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('equ_c_4'); ?>   </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('latherin'); ?>   </td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px;" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?>   </td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('mouse'); ?>   </td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('mus_musculus'); ?>   </td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('mus_m_1'); ?>   </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?>   </td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('rabbit'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('oryctolagus_cuniculus'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('ory_c_1'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('ory_c_2'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('lipocalin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('19'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('ory_c_3'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('secretoglobin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" alt="" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" alt="" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('18'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table width="100%"><tr><td height="20"></td></tr></table><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
		<tr>
			<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:15px; padding:0 10px 10px 0px;margin-left:10px"><?php echo $this->lang->line('grass_pollens'); ?></th>					
		</tr>
	</table>
	<div style="margin: 0px 40px;"><hr style="border-top: 2px solid #3a6a86;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('bermuda_grass'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('cynodon_dactylon'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('cyn_d'); ?></td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('18'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('cyn_d_1'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('beta_expansin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('kentucky_blue_grass'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('poa_pratensis'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('poa_p'); ?></td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('28'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('meadow_fescue'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('festuca_pratensis'); ?> </td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('fes_p'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('36'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('orchard_grass'); ?> </td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('dactylis_glomerata'); ?> </td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('dac_g'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('39'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('perennial_ryegrass'); ?> </td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('lolium_perenne'); ?> </td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('lol_p_1'); ?>  </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('beta_expansin'); ?> </td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('timothy_grass'); ?> </td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('phleum_pratense'); ?> </td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; 	<?php echo $this->lang->line('phl_p_1'); ?></td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('beta_expansin'); ?> </td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('21'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('phl_p_2'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('expansin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('21'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('phl_p_5_0101'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('grass_group_5_6'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('22'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('phl_p_6'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('grass_group_5_6'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('21'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('phl_p_7'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('polcalcin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('16'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('phl_p_12'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('profilin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('18'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table width="100%"><tr><td height="20"></td></tr></table><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
		<tr>
			<th colspan="5" align="left" style="color:#366784; border-bottom:2px solid #366784; font-size:15px; padding:0 10px 10px 0px;margin-left:10px"><?php echo $this->lang->line('mites_cockroaches'); ?></th>					
		</tr>
	</table>
	<div style="margin: 0px 40px;"><hr style="border-top: 2px solid #3a6a86;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('acarus_siro'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('aca_s'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('26'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('blomia_tropicalis'); ?> </td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('blo_t_10'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('tropomyosin'); ?> </td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('37'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('dermatophagoides_farinae'); ?> </td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('der_f'); ?>  </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('93'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('der_f_1'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('cysteine_protease'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('23'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('der_f_2'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('npc2_family'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('der_f_15'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('chitinase'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('18'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('der_f_18'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('chitin_binding_protein'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('dermatophagoides_pteronyssinus'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('der_p'); ?></td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('24'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('der_p_1'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('cysteine_protease'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('16'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('der_p_2'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('npc2_family'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('der_p_5'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('19'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('der_p_7'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('mites_group_7'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('21'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('der_p_10'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('tropomyosin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('21'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('der_p_11'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('myosin_heavy_chain'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('17'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('der_p_20'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('arginine_kinase'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('86'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('der_p_21'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"> <?php echo $this->lang->line('22'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;  <?php echo $this->lang->line('der_p_23'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										 <?php echo $this->lang->line('peritrophin_like_protein_domain'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"> <?php echo $this->lang->line('18'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"> <?php echo $this->lang->line('flea'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"> <?php echo $this->lang->line('ctenocephalides_felis'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line(' cte_f_1'); ?> </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line(' 20'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('german_cockroach'); ?> </td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('blatella_germanica'); ?> </td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line(' ble_g_1'); ?>  </td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('cockroach_group_1'); ?> </td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('17'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('bla_g_2'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('aspartyl_protease'); ?> </td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('17'); ?> </td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line(' bla_g_4'); ?>  </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('calycin'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line(' 18'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('bla_g_5'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('glutathione_S_transferase'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; 	<?php echo $this->lang->line('bla_g_9'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;">	
										<?php echo $this->lang->line('arginine_kinase'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('45'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('glycyphagus_domesticus'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('glycyphagus_domesticus'); ?></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('gly_d_2'); ?></td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('npc2_family'); ?></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;">23</td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('lepidoglyphus_destructor'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('lep_d'); ?></td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('21'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('lep_d_2'); ?> </td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('npc2_family'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('20'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">
										<?php echo $this->lang->line('tyrophagus_putrescentiae'); ?></td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td>
								<td style="width:130px">
									<table style="width:130px"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('tyr_p'); ?></td></tr></table>
								</td>
								<td style="width:120px">
									<table style="width:120px">
										<tr><td align="left" style="font-size:11px;"></td></tr>
									</table>
								</td><td style="width:110px">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px">
									<table style="width:50px">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('22'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><table style="width:750px;margin: 0px 40px;padding:0px;border:none">
							<tr>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:175px;">
									<table style="width:175px;">
										<tr><td align="left" style="font-size:11px;">&nbsp;</td></tr>
									</table>
								</td>
								<td style="width:130px;border-top: 1px solid #3a6a86">
									<table style="width:130px;"><tr><td align="left" style="font-size:11px;text-align:left;"><img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('tyr_p_2'); ?></td></tr></table>
								</td>
								<td style="width:120px;border-top: 1px solid #3a6a86">
									<table style="width:120px;">
										<tr><td align="left" style="font-size:11px;"><?php echo $this->lang->line('npc2_family'); ?></td></tr>
									</table>
								</td><td style="width:110px;border-top: 1px solid #3a6a86;">
										<table cellspacing="0" cellpadding="0" style="width:110px" class="panelmeter">
											<tr>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;" class="">
				                                    <img src="assets/images/ss1.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23"/>
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_comman.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                                <td style="width:25px;height:6px;line-height:8px;font-size:9px;">
				                                    <img src="assets/images/ss_last.png" style="height: 9px;" alt="" height="14" width="23" />
				                                </td>
				                            </tr>
										</table>
									</td><td style="width:50px;border-top: 1px solid #3a6a86">
									<table style="width:50px;">
										<tr><td align="left" style="padding:0 0 5px 0;font-size:11px;"><?php echo $this->lang->line('28'); ?></td></tr>
									</table>
								</td>
							</tr>
						</table><div style="margin: 0px 40px;"><hr style="border-top: 1px solid #9acfdb;margin: 0px;"></div><table width="100%"><tr><td height="20"></td></tr></table> <table style="width:100%;">
	<tbody>
		<tr><td width="30%" align="left" style="font-size:11px;text-align:left;padding-left: 40px;">
			<img src="assets/images/Extract.png" alt="Allergen Extract" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('allergen_extract'); ?> </td>
			<td width="60%" align="left" style="font-size:11px;text-align:left;">
			<img src="assets/images/Molecular.png" alt="Molecular Allergen	" style="width: 18px;">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('molecular_allergen'); ?> </td>
			<td width="10%" style="background:url(assets/images/footer_page.png) center top no-repeat #ffffff;text-align:right;border-radius: 40px 0px 0px 40px;height: 100%;background-size: cover;background-repeat: repeat-x;background-position-y: center;color: #ffffff;font-weight: bold;text-align: center;/* padding: 10px; *//* vertical-align: middle; */"><?php echo $this->lang->line('pageno'); ?></td>
		</tr>
		</tbody>
	</table> 


	<!DOCTYPE html>
<html class="js">
	<head>
		<title><?php echo $this->lang->line('serum_test_result'); ?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page {margin:0mm;}
		html {margin:5px 15px;padding:0px}
		*{font-family:'Open Sans',sans-serif}
		.header th{text-align:left}
		.green_strip{background:#bed600;padding:5px 10px;color:#fff;font-size:18px}
		.green_bordered{border:1px solid #bed600;padding:10px;color:#333;font-size:18px}
		.blob1{background:#becedb}
		.blob2{background:#9acfdb}
		.blob3{background:#59c0d3}
		.blob4{background:#366784}
		.blob5{background:#273945}
		.light-grey-bg{background:#f2f5f8}
		.grey-bg{background:#becedb}
		.aqua-bg{background:#9acfdb}
		.cgreen-bg{background:#366784}
		.cgreen-dark-bg{background:#273945}
		.meter{min-width:120px;max-width:120px}
		.meter tr td{font-size:13px;line-height:11px}
		.meter tr td:nth-child(2){border-radius:10px 0 0 10px}
		.meter tr td:last-child{border-radius:0 10px 10px 0}
		.index-capsule{width:140px;height:24px;border-radius: 40px 40px 40px 40px;}
		.light-lemon-bg{background:#eaf4e3}
		.lemon-bg{background:#9fd08a}
		.military-bg{background:#666c3e}
		.red-bg{background:#d1232a}
		.mehroon-bg{background:#b02a2f}
		.green-bg{background:#40ae49}
		.orange-bg{background:#f58220}
		.diets tr th{font-weight:400;font-size:13px;padding:20px 5px 10px;width:20px;border-left:1px solid #9acfdb;border-bottom:3px solid #9acfdb;position:relative;height:100px;text-align:left}
		.diets tr th:first-child{white-space:nowrap;font-weight:700;font-size:18px}
		.diets tr th:first-child,.diets tr td:first-child{border-left:0}
		.diets tr td:first-child{text-align:left}
		.diets tr th .rotate{transform:rotate(270deg);display:block;transform-origin:center bottom;text-align:left;position:absolute;white-space:nowrap;left:2px;bottom:40px;text-align:left}
		.diets tr td{border-left:1px solid #9acfdb;border-bottom:1px solid #9acfdb;font-size:13px;text-align:center;padding:5px}
		/*#test {
		    background-color:blue;width:450px;height:800px;z-index:50;
		    border-radius: 0px 0px 100px 100px;
		}*/
		</style>
	</head>
	<body bgcolor="#fff">
		
		<table width="100%"><tr><td height="20"></td></tr></table>
				

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:20px;">
					<?php echo $this->lang->line('interpretation_support'); ?></h4>
				</td>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:40px 40px;padding:0px;border:none;">
			<tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74;">
							<?php echo $this->lang->line('american_cockroach'); ?></h4>
							<ol style="color:#184359;font-size:13px;margin:0px 0 0 20px;padding:0;"></ol>
						</td>
					</tr>
					<tr><td height="10"></td></tr><tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74; font-size: 14px;"><?php echo $this->lang->line('german_cockroach'); ?></h4>
							<ul style="color:#184359;font-size:12px;margin:0px 0 0 20px;padding:0;"><li style="list-style-type: disc;"><?php echo $this->lang->line('bla_g__is_an_allergen_from_the_germancockroach_blatella_germanica__it_is_a_member_of_the_arginine_kinase_AK_allergen_family'); ?></li><li style="list-style-type: disc;"><?php echo $this->lang->line('the_potential_for_cross_reactions_of_bla_g_9_with_other_arginine_kinase_family_allergens_group_20_ from_mites_or_group_2_from_invertebrates_is_very_high'); ?></li><br></ul>
						</td>
					</tr>
					<tr><td height="10"></td></tr><tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74; font-size: 14px;"><?php echo $this->lang->line('dermatophagoides_pteronyssinus'); ?>
							</h4>
							<ul style="color:#184359;font-size:12px;margin:0px 0 0 20px;padding:0;"><li style="list-style-type: disc;"><?php echo $this->lang->line('der_p_20_is_an_allergen_from_the_Dermatophagoides_pteronyssinus-house_dust_mite_it_is-a_member_of_the_mite_ group_20_allergen_family_arginine_kinases'); ?>
							</li><li style="list-style-type: disc;"><?php echo $this->lang->line('der_p_20_is_a_minor_allergen_of_humans_sensitized_to_this_house_dust_mite_and_those_affected_with_scabies_at_ this_time_it_is_not_known_if_this_is_also_the_case_in_animals'); ?>
							</li><li style="list-style-type: disc;"><?php echo $this->lang->line('the_potential_for_cross_reactions_of_der_p_20_with_invertebrate_arginine_kinases_bla_g_9_is_high
							'); ?>
							</li><li style="list-style-type: disc;"><?php echo $this->lang->line('allergen_specific_immunotherapy_is_recommended_for_house_dust_mite_Der_p_20_sensitization_if_the_ corresponding_clinical_signs_occur'); ?>
							</li><br></ul>
						</td>
					</tr>
					<tr><td height="10"></td></tr><tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74; font-size: 14px;"><?php echo $this->lang->line('dermatophagoides_farinae'); ?>
							</h4>
							<ul style="color:#184359;font-size:12px;margin:0px 0 0 20px;padding:0;"><li style="list-style-type: disc;"><?php echo $this->lang->line('this_patient_has_a_sensitization_to_house_dust_mites
							'); ?>
							</li><li style="list-style-type: disc;"><?php echo $this->lang->line('associated_allergic_signs_are_generally_year_round_but_house_dust_mites_are_known_to_proliferate_during_times_ of_high_humidity_and_temperature
							'); ?>
							</li><li style="list-style-type: disc;"><?php echo $this->lang->line('there_is_a_known_cross_reactivity_between_allergens_of_house_dust_and_storage_mite_species_as_well_as_between_ those_of_dermatophagoides_farinae_and_toxocara_canis
							'); ?>
							</li><li style="list-style-type: disc;"><?php echo $this->lang->line('allergen_specific_immunotherapy_is_recommended_for_house_dust_mite_sensitization_if_the_corresponding_ clinical_ signs_occur'); ?>
							</li><br></ul>
						</td>
					</tr>
					<tr><td height="10"></td></tr><tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74; font-size: 14px;"><?php echo $this->lang->line('meadow_fescue'); ?>
							</h4>
							<ul style="color:#184359;font-size:12px;margin:0px 0 0 20px;padding:0;"><li style="list-style-type: disc;"><?php echo $this->lang->line('meadow_fescue_festuca_pratensis'); ?>
					</li><li style="list-style-type: disc;"><?php echo $this->lang->line('associated_allergic_signs_are_generally_worse_during_the_grass_pollination_-season_in_the_spring_and_summer
					'); ?>
					</li><li style="list-style-type: disc;"><?php echo $this->lang->line('the_potential_for_cross_reactions_with_other_grass_pollens_is_very_high
					'); ?>
					</li><li style="list-style-type: disc;"><?php echo $this->lang->line('allergen_specific_immunotherapy_is_recommended_for_grass_pollen_sensitization_if_the_corresponding_clinical_ signs_occur_due_to_the_profound_cross_reactivity_existing_among_grass_pollens_immunotherapy_might_be_limited_ to_a_single_grass_species_for_example_timothy_grass'); ?>
					</li><br></ul>
						</td>
					</tr>
					<tr><td height="10"></td></tr><tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74; font-size: 14px;"><?php echo $this->lang->line('blomia_tropicalis'); ?>
							</h4>
							<ul style="color:#184359;font-size:12px;margin:0px 0 0 20px;padding:0;"><li style="list-style-type: disc;"><?php echo $this->lang->line('blo_t_10_is_an_allergen_from_the_sub_tropical_house_dust_mite_Blomia_tropicalis_it_is_a_member_of_the_mite_ Group_10_allergen_family_tropomyosins
							'); ?>
							</li><li style="list-style-type: disc;"><?php echo $this->lang->line('the_potential_for_cross_reactions_of_Blo_t_10_with_other-tropomyosins_present_in_mites_insects_nematodes_ and_ ingested_seafood_is_very_high.
							'); ?>
							</li><li style="list-style-type: disc;"><?php echo $this->lang->line('due_to_the_high_risk_of_cross_reactions_among_tropomyosins_immunotherapy_is_not_recommended_in_case_of_dogs_ sensitized_to_Blo_t_10_and_to_Der_f_extract_only'); ?>
							</li><br></ul>
						</td>
					</tr>
					<tr><td height="10"></td></tr><tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74; font-size: 14px;"><?php echo $this->lang->line('orchard_grass'); ?>
</h4>
							<ul style="color:#184359;font-size:12px;margin:0px 0 0 20px;padding:0;"><li style="list-style-type: disc;"><?php echo $this->lang->line('this_patient_is_sensitized_to_cocksfoot_orchard_grass_pollen
						'); ?>
						</li><li style="list-style-type: disc;"><?php echo $this->lang->line('associated_allergic_signs_are_generally_worse_during_the_grass_pollination_-season_in_the_spring_and_summer'); ?></li><li style="list-style-type: disc;"><?php echo $this->lang->line('the_potential_for_cross_reactions_with_other_grass_pollens_is_very_high
						'); ?></li><li style="list-style-type: disc;"><?php echo $this->lang->line('allergen_specific_immunotherapy_is_recommended_for_grass_pollen_sensitization_if_the_corresponding_clinical_signs_occur_due_to_the_profound_cross_reactivity_existing_among_grass_pollens_immunotherapy_might_be_limited_ to_a_single_grass_species_for_example_timothy_grass'); ?></li><br></ul>
							</td>
					</tr>
					<tr><td height="10"></td></tr><tr>
						<td>
							<h4 style="margin:10px 0px 0px 0px;color:#2a5b74; font-size: 14px;"><?php echo $this->lang->line('ryegrass_cultivated'); ?>
</h4>
							<ol style="color:#184359;font-size:13px;margin:0px 0 0 20px;padding:0;"></ol>
						</td>
					</tr>
					<tr><td height="10"></td></tr>		</table>
		<table width="100%"><tr><td height="3"></td></tr></table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:40px;padding:0px;border:none;">
			<tr>
				<td width="100%" style="font-size: 11px; color: #000; text-transform: uppercase;">
				<?php echo $this->lang->line('disclamier:the_presence_of_IGE_antibodies_implies_a_risk_of_allergic_reactions_and_has_to_be_analyzed_in_interpretation_of_pax_result_pax_recommendation_do_not_replace_the_diagnosis_by_a_veterinarin_no_liability_is_accepted_for+_recommendation_and_resulting_thereapeutic_interventions_the_stated_commens_are_designed_exclusively_for_pax_results.'); ?>
 
				</td>
			</tr>
		</table>
	
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;padding:0px;border:none;">
			<tr>
				<td>
					<h4 style="margin:0; color:#2a5b74; font-size:20px;"><?php echo $this->lang->line('immunotherapy'); ?>
</h4>
					<p style="margin:5px 0 0 0; color:#2a5b74; font-size:15px;">
					<?php echo $this->lang->line('frequently_asked_questions'); ?>
</p>
				</td>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;">
			<tr>
				<td width="48%" valign="top">
					<table cellpadding="0" cellspacing="0" border="0" align="left" width="100%;">
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;">
							<?php echo $this->lang->line('what_is_the_dosage_schedule'); ?>
	</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;">	<?php echo $this->lang->line('subcutaneous_injections_are_administered_with_gradually_incre_asing_dosages_the_schedule_below_is_applicable_ for_dogs_cats_and_horses_please_keep_an_eye_on_the_patient_for_at-least_30_minutes_after_every_injection_for any_side_effects'); ?></p></td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td colspan="2"><h6 style="color:#333333; font-size:14px; margin:0 0 10px 0;">	<?php echo $this->lang->line('advised_schedule_dosage'); ?></h6></td>	
						</tr>
						<tr>
							<td colspan="2">
								<img width="350" src="assets/images/dosage1.png" alt="" style="max-width:100%;" />
								<!-- <p style="color:#333333; font-size:12px; line-height:18px; margin:10px 0 0 0;">Continue with 1.0 ml every 4 weeks for at least 12 months. If noticeable results, Artuvetrin® is a lifelong treatment.</p> -->
								<!-- <table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="80%" style="width:100%; border:1px solid #89ccd6; border-radius:0 12px 12px 12px; padding:15px; margin-top:25px;">
									<tr>
										<td>
											<p style="color:#1e3743; font-size:13px; margin:4px 0 0 0;"><strong>Artuvetrin® is a life-long treatment and compliance is key</strong> Allergy is a chronic disease and every 10 months a follow-up vial is required.</p>
										</td>
									</tr>
									<tr><td height="15"></td></tr>
								</table> -->
							</td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;"><?php echo $this->lang->line('is_it_possible_to_deviate_from_the_standard_dosing_schedule
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('yes_however_this_depends_on_the_situation_please_contact_our_veterinary_support_team_for_advice_and_support
'); ?>
</p></td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;"><?php echo $this->lang->line('what_is_the_success_rate_of_Artuvetrin
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('the_success_rate_of_subcutaneous_allergen_immunotherapy_varies_depending_upon_the_studies_but_generally_ between_60_and_75%_of_animals_can_be_expected_to_have_a_50%_to_100%_reduction_in_clinical_signs_and_ _a_reduction_or_discontinuation_of_anti_allergic_symptomatic_therapy_needs
'); ?>
</p></td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;"><?php echo $this->lang->line('what_if_the_patient_did_not_respond_at_all
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('if_there_is_no_improvement_in_clinical_signs_after_one_year_of_Artuvetrin_please_contact_our_veterinary_ support_team_there_can_be_several_explanations_for_such_a_response_which_include_the_development_of_new_ allergen_sensitizations_the_existence_of_a_concurrent_food_allergy_and_the_presence_of_skin_infections_or_ fleas_Our_support_team_will_be_happy_to_help_you_development_a_new_treatment_plan'); ?> </p></td>
						</tr>
					</table>
				</td>
				<td width="4%" valign="top"></td>
				<td width="48%" valign="top">
					<table cellpadding="0" cellspacing="0" border="0" align="left" width="100%;">
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;"><?php echo $this->lang->line('if_the_symptoms_are_seasonal_can_i_administer_only_during_that_time
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('it_is_best_to_continue_the_treatment_year_round_as_the_tolerance_to_allergens_takes_several_months_to develop_in_case_of_interruption_of_treatment_it_is_best_to_restart_the_treatment_from_the_beginning_to_ decrease_the_risk_of_side_effects
'); ?>
</p></td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;"><?php echo $this->lang->line('what_is_the_best_time_to_start_the_treatment
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('the_treatment_can_be_started_at_any_time_for_seasonal_allergies_it_is_preferable_to_start_at_least_9_months_ before_the_expected_beginning_of_seasonal_flares_to_ensure_that_tolerance_is_achieved
'); ?>
</p></td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;"><?php echo $this->lang->line('do_ineed_to_stop_symptomatic_Pmedication_Pbefore_starting_Artuvetrin®
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('there_is_no_evidence_suggesting_that_antiallergic_drugs_like_antihistamines_glucocorticoids_ciclosporin_JAK_inhibitors_and_biologics_need_to_be_discontinued_during_Artuvetrin_therapy_in-fact_they_may_help_ controlsigns_during_the_time_it_takes_for_the_immunotherapy_to_be_effective
'); ?>
</p></td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;"><?php echo $this->lang->line('when_can_i_expect_to_see_improvements
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('by_6_months_of_treatment_clinical_signs_are_expected_to_begin_to_decrease_if_there_is_no_improvement_after_6_months_please_contact_our_veterinary_support_team_for_advice
'); ?>
</p></td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:14px; margin:0;"><?php echo $this->lang->line('what_to_do_with_cases_where_the_symptoms_come_back
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('there_are_several_possibilities_to_explain_a_flare_of_clinical_signs_which_include_the_appearance_of_new_allergen_sensitizations_the_presence_of_concurrent_food_allergies_skin_infections_or_parasites_like_fleas_With_a_flare_up_it_is_important_to_identify_the_cause_and_treat_prevent_it_symptomatic_treatment_might_help reintroduce_control
'); ?>
</p></td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td width="35" style="line-height:0;"><img src="assets/images/question.png" alt="" /></td>
							<td><h6 style="color:#333333; font-size:15px; margin:0;"><?php echo $this->lang->line('can_I_stop_the_treatment-if_the_symptoms_are_not_present_anymore
'); ?>
</h6></td>	
						</tr>
						<tr>
							<td colspan="2"><p style="color:#333333; font-size:13px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('despite_the_lack_of_long_term_trial_results_in_allergic_animals_if_there_is_continuous_and_stable_control_of_ signs_after_years_of_immunotherapy_it_is_logical_to_decrease_and_then_possibly_stop_the_frequency_of_injections_while_monitoring_for_a_recurrence_of_signs
'); ?>
</p></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0px 40px;">
			<tr>
				<td><h6 style="color:#376983; font-size:13px; margin:0;"><br/>
				<?php echo $this->lang->line('do_you_need_support'); ?>
</h6></td>	
			</tr>
			<tr>
				<td><p style="color:#1e3743; font-size:13px; line-height:15px; margin:0 0 0 0;"><?php echo $this->lang->line('please_contact_our_veterinary_support_team_by_phone_+31_320_783_100_or_by_email_info_eu@nextmune_com'); ?>
</p></td>
			</tr>
		</table>
			</body>
</html>