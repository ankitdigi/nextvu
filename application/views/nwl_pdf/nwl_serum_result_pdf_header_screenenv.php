<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;margin:0mm;font-size:13px;line-height:20px;">
	<tr>
		<th align="left" style="color:#346a7e;">VETERINARY SURGEON:</th>
		<td align="left" style="color:#000000;"><?php echo $order_details['name']; ?></td>
	</tr>
	<tr>
		<th align="left" style="color:#346a7e;">PRACTICE:</th>
		<td align="left" style="color:#000000;"><?php echo $order_details['practice_name']; ?></td>
	</tr>
	<tr>
		<th align="left" style="color:#346a7e;">CLIENT:</th>
		<td align="left" style="color:#000000;"><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></td>
	</tr>
	<tr>
		<th align="left" style="color:#346a7e;">ANIMAL:</th>
		<td align="left" style="color:#000000;"><?php echo $order_details['pet_name']; ?></td>
	</tr>
	<tr>
		<th align="left" style="color:#346a7e;">BREED:</th>
		<td align="left" style="color:#000000;"><?php echo $breedinfo['name']; ?></td>
	</tr>
	<tr>
		<th align="left" style="color:#346a7e;">DATE:</th>
		<td align="left" style="color:#000000;"><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></td>
	</tr>
	<tr>
		<th style="color:#346a7e;">&nbsp;</th>
		<td style="color:#000000;">&nbsp;</td>
	</tr>
	<tr>
		<th align="left" style="color:#346a7e;">Allervet Test Results</th>
		<td style="color:#000000;">&nbsp;</td>
	</tr>
</table>
<table width="100%"><tr><td height="30"></td></tr></table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
	<tr>
		<td align="center">
			<p style="margin:5px 0 0 0;color:#2a5b74;font-size:18px;text-align:center;font-weight:bold">SCREEN ENVIRONMENTAL PANEL</p>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="30"></td></tr></table>
