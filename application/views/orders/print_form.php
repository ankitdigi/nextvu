<!DOCTYPE html>
<html class="js">
	<head>
		<title><?php echo $this->lang->line('serum_test_request_form'); ?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		*{font-family: 'Open Sans', sans-serif; box-sizing:border-box;}
		.header th{text-align:left;}
		</style>
	</head>
	<body bgcolor="#cccccc">
		<?php 
		if($order_details['pet_id']>0){
			$this->db->select('type,breed_id,other_breed,gender,age,age_year');
			$this->db->from('ci_pets');
			$this->db->where('id', $order_details['pet_id']);
			$petinfo = $this->db->get()->row_array();

			if($petinfo['breed_id']>0){
				$this->db->select('name');
				$this->db->from('ci_breeds');
				$this->db->where('id', $petinfo['breed_id']);
				$breedinfo = $this->db->get()->row_array();
			}else{
				$breedinfo = array();
			}
		}else{
			$petinfo = array();
			$breedinfo = array();
		}

		if($order_details['vet_user_id']>0){
			$refDatas = $this->UsersDetailsModel->getColumnAllArray($order_details['vet_user_id']);
			$refDatas = array_column($refDatas, 'column_field', 'column_name');
			$add_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'].', ' : '';
			$add_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'].', ' : '';
			$add_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
			$city = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
			$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
			$fulladdress = $add_1.$add_2.$add_3;
		}else{
			$fulladdress = '';
			$city = '';
			$postcode = '';
		}

		if(!empty($order_details['product_code_selection'])){
			$this->db->select('name');
			$this->db->from('ci_price');
			$this->db->where('id', $order_details['product_code_selection']);
			$ordeType = $this->db->get()->row()->name;
		}else{
			$ordeType = 'Serum Testing';
		}
		$serumdata = $this->OrdersModel->getSerumTestRecord($order_details['id']);
		$years = !empty($petinfo['age_year'])?$petinfo['age_year'].'Year, ':'';
		$months = !empty($petinfo['age'])?$petinfo['age'].'Month':'';
		?>
		<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="935px" style="width:935px; max-width:935px; padding:0; background:#ffffff;border: none;">
			<tbody>
				<tr>
					<td>
						<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;padding-right:20px;">
							<tbody>
								<tr>
									<td valign="middle" width="350" style="background:#426e89; padding:40px 10px 40px 30px;">
										<h4 style="font-size: 40px;text-transform: uppercase;color: #ffffff;font-weight: 400;margin: 0;letter-spacing: 2px;line-height: 52px; white-space:nowrap;"><?php echo $this->lang->line('serum_test_print_form'); ?><br> <?php echo $this->lang->line('request_form_2'); ?></h4>
									</td>
									<td valign="top" style="line-height:0;">
										<img src="<?php echo base_url("/assets/images/aqua-corner.png"); ?>" alt="NextVu" height="200" />
									</td>
							
									<td style="line-height:0; padding:0 10px 0 30px;" align="right">
										<img src="<?php echo base_url("/assets/images/nextmune-uk.png"); ?>" height="65" alt="NextVu" />
									</td>
								</tr>
							</tbody>
						</table>
						<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 15px;">
							<tbody>
								<tr><td height="40"><p style="color: #346a7e;font-size:16px;"><b><?php echo $this->lang->line('Order_Type'); ?>:</b> <?php echo $ordeType; ?></p></td></tr>
								<tr>
									<td colspan="3">
										<h5 style="color:#426e89; letter-spacing:1px; margin:0 0 20px 0; padding:0; font-size:24px; font-weight:400;"><?php echo $this->lang->line('practice_details'); ?></h5>
									</td>
								</tr>
								<tr>
									<td>
										<table width="48%" align="left" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="28%;">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('date'); ?>:</label>
														<input type="text" value="<?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
													<td width="2%"></td>
													<td width="50%;">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('veterinary_surgeon'); ?>:</label>
														<input type="text" value="<?php echo $order_details['name']; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</label>
														<input type="text" value="<?php echo $order_details['practice_name']; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('practice_details'); ?>:</label>
														<input type="text" value="<?php echo $fulladdress; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="48%;">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('city'); ?>:</label>
														<input type="text" value="<?php echo $city; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
													<td width="2%"></td>
													<td width="32%;">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('postcode'); ?>:</label>
														<input type="text" value="<?php echo $postcode; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
												</tr>
											</tbody>
										</table>
										<table width="48%" align="right" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%;" colspan="3">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('phone'); ?>:</label>
														<input type="text" value="<?php echo $order_details['phone_number']; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('email'); ?>:</label>
														<input type="text" value="<?php echo $order_details['email']; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('results_will_be_delivered_by_email'); ?></label>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if($order_details['shipping_materials'] == '1'){ echo 'checked="checked"'; } ?> /> <?php echo $this->lang->line('serum_test_shipping_materials'); ?></label>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
						<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 15px;">
							<tbody>
								<tr><td height="40"></td></tr>
								<tr>
									<td colspan="3">
										<h5 style="color:#426e89; letter-spacing:1px; margin:0 0 20px 0; padding:0; font-size:24px; font-weight:400;"><?php echo $this->lang->line('pet_and_pet_owner_details'); ?></h5>
									</td>
								</tr>
								<tr>
									<td>
										<table width="48%" align="left" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="48%;">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('pet_owners_first_name'); ?>:</label>
														<input type="text" value="<?php echo $order_details['pet_owner_name']; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
													<td width="2%"></td>
													<td width="50%;">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('pet_owners_last_name'); ?>:</label>
														<input type="text" value="<?php echo $order_details['po_last']; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" />
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td><label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('Species'); ?>:</label></td>
												</tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if($order_details['species_name'] == 'Dog'){ echo 'checked="checked"'; } ?> /> <?php echo $this->lang->line('dog'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if($order_details['species_name'] == 'Cat'){ echo 'checked="checked"'; } ?> /><?php echo $this->lang->line('cat'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if($order_details['species_name'] == 'Horse'){ echo 'checked="checked"'; } ?> /><?php echo $this->lang->line('horse'); ?> </label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="48%;">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('breed'); ?>:</label>
														<input type="text" value="<?php echo $breedinfo['name']; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:30px; padding:0 10px;" />
													</td>
													<td width="2%"></td>
													<td width="50%;">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('age_month_and_year'); ?>:</label>
														<input type="text" value="<?php echo $years.$months; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:30px; padding:0 10px;" />
													</td>
												</tr>
											</tbody>
										</table>
										<table width="48%" align="right" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%;" colspan="3">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('animal_name'); ?>:</label>
														<input type="text" value="<?php echo $order_details['pet_name']; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:30px; padding:0 10px;" />
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td><label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('gender'); ?>:</label></td>
												</tr>
												<tr>
													<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if($petinfo['gender'] == '1'){ echo 'checked="checked"'; } ?> /><?php echo $this->lang->line('male'); ?> </label></td>
													<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if($petinfo['gender'] == '2'){ echo 'checked="checked"'; } ?> /><?php echo $this->lang->line('female'); ?> </label></td>
													<td></td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('date_serum_drawn'); ?>:</label>
														<input type="text" value="<?php echo !empty($serumdata['serum_drawn_date'])?date('d/m/Y',strtotime($serumdata['serum_drawn_date'])):''; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:30px; padding:0 10px;" />
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
						<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 15px;">
							<tbody>
								<tr><td height="40"></td></tr>
								<tr>
									<td colspan="3">
										<h5 style="color:#426e89; letter-spacing:1px; margin:0 0 20px 0; padding:0; font-size:24px; font-weight:400;"><?php echo $this->lang->line('medical_history'); ?></h5>
									</td>
								</tr>
							</tbody>
						</table>
						<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 15px">
							<tbody>
								<tr>
									<td>
										<table width="100%" align="left" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('patient_affected'); ?></label></td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td>
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('pruritus_itch'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '5' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('skin_lesions'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('otitis'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('respiratory_signs'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '6' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('ocular_signs'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '7' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('anaphylaxis'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '4' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('gastro_intestinal_signs'); ?></label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td>
														<table width="48%" align="left" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr><td height="20"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '0' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('other'); ?> </label></td>
																	<td><input type="text" value="<?php echo isset($serumdata['other_symptom']) ? $serumdata['other_symptom'] : ''; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" /></td>
																</tr>
															</tbody>
														</table>
														<table width="48%" align="right" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td>
																		<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('at_what_age_did_these_symptoms_first_appear'); ?></label>
																		<input type="text" value="<?php echo $serumdata['symptom_appear_age'].' years '.$serumdata['symptom_appear_age_month'].' months'; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:30px; padding:0 10px;" />
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>
										<table width="48%" align="left" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('symptoms_most_obvious'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('spring'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('summer'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('fall'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '4' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('winter'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '5' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('year_round'); ?> </label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('clinical_diagnosis'); ?></label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('food'); ?></label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['diagnosis_food']) && (strpos( $serumdata['diagnosis_food'], '1' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('yes'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['diagnosis_food']) && (strpos( $serumdata['diagnosis_food'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('no'); ?></label></td>
																	<td colspan="3">
																		<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
																		<input type="text" value="<?php echo isset($serumdata['other_diagnosis_food']) ? $serumdata['other_diagnosis_food'] : '';?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;">
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('hymenoptera_stings'); ?>:</label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['diagnosis_hymenoptera']) && (strpos( $serumdata['diagnosis_hymenoptera'], '1' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('yes'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['diagnosis_hymenoptera']) && (strpos( $serumdata['diagnosis_hymenoptera'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('no'); ?></label></td>
																	<td colspan="3">
																		<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
																		<input type="text" value="<?php echo isset($serumdata['other_diagnosis_hymenoptera']) ? $serumdata['other_diagnosis_hymenoptera'] : '';?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;">
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="48%" align="right" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('where_symptoms_most_obvious'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '1' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('indoors'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '2' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('outdoors'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '3' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('no_difference'); ?> </label></td>
																	<td></td>
																	<td></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="50"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('food_challenge'); ?>:</label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="15"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '1' ) !== false) ){ echo 'checked'; } ?> />  &lt;<?php echo $this->lang->line('3_h'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('3_12_h'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '3' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('12_24_h'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '4' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('24_48_h'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '5' ) !== false) ){ echo 'checked'; } ?> /> &gt; <?php echo $this->lang->line('48_h'); ?></label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="15"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('other_s'); ?>:</label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['diagnosis_other']) && (strpos( $serumdata['diagnosis_other'], '1' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('yes'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['diagnosis_other']) && (strpos( $serumdata['diagnosis_other'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('no'); ?></label></td>
																	<td colspan="3">
																		<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
																		<input type="text" value="<?php echo isset($serumdata['other_diagnosis']) ? $serumdata['other_diagnosis'] : '';?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;">
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>
										<table width="100%" align="left" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('exposed_following_animals'); ?>:</label></td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td>
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '1' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('cats'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('dogs'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '3' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('horses'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '4' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('cattle'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '5' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('mice'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '6' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('guinea_pigs'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '7' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('rabbits'); ?></label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td>
														<table width="48%" align="left" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr><td height="20"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '0' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('other_s'); ?></label></td>
																	<td><input type="text" value="<?php echo isset($serumdata['other_exposed']) ? $serumdata['other_exposed'] : ''; ?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;" /></td>
																</tr>
															</tbody>
														</table>
														<table width="48%" align="right" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%;" colspan="3">
																		<table cellspacing="0" cellpadding="0" border="0" width="100%">
																			<tbody>
																				<tr>
																					<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('malassezia_infections'); ?></label></td>
																				</tr>
																				<tr><td height="10"></td></tr>
																				<tr>
																					<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('malassezia_otitis'); ?> </label></td>
																					<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('malassezia_dermatitis'); ?></label></td>
																					<td></td>
																					<td></td>
																					<td></td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>
										<table width="100%" align="left" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('receiving_drugs'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('glucocorticoids_oral_topical_injectable'); ?> </label></td>
																	<td></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('no_response'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '2' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('fair_response'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '3' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('good_to_excellent_response'); ?> </label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '2' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('ciclosporin'); ?> </label></td>
																	<td></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('no_response'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('fair_response'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '3' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('good_to_excellent_response'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '3' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('oclacitinib'); ?></label></td>
																	<td></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '1' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('no_response'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('fair_response'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '3' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('good_to_excellent_response'); ?> </label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '4' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('lokivetmab'); ?> </label></td>
																	<td></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('no_response'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '2' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('fair_response'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '3' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('good_to_excellent_response'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '5' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('antibiotics'); ?></label></td>
																	<td></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('no_response'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('fair_response'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '3' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('good_to_excellent_response'); ?> </label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="checkbox" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '6' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('antifungals'); ?></label></td>
																	<td></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '1' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('no_response'); ?> </label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '2' ) !== false) ){ echo 'checked'; } ?> /> <?php echo $this->lang->line('fair_response'); ?></label></td>
																	<td><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '3' ) !== false) ){ echo 'checked'; } ?> /><?php echo $this->lang->line('good_to_excellent_response'); ?> </label></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>
										<table width="48%" align="left" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('treatment_ectoparasites'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td width="20%"><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php echo ( isset($serumdata['treatment_ectoparasites']) && $serumdata['treatment_ectoparasites']==1) ? 'checked' : ''; ?> /> <?php echo $this->lang->line('yes'); ?></label></td>
																	<td width="20%"><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php echo ( isset($serumdata['treatment_ectoparasites']) && $serumdata['treatment_ectoparasites']==2) ? 'checked' : ''; ?> /> <?php echo $this->lang->line('no'); ?></label></td>
																	<td width="60%" colspan="3">
																		<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
																		<input type="text" value="<?php echo isset($serumdata['other_ectoparasites']) ? $serumdata['other_ectoparasites'] : '';?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;">
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('zoonotic_disease'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td width="20%"><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php echo ( isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease']==1) ? 'checked' : ''; ?> /> <?php echo $this->lang->line('yes'); ?></label></td>
																	<td width="20%"><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php echo ( isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease']==0) ? 'checked' : ''; ?> /> <?php echo $this->lang->line('no'); ?></label></td>
																	<td width="60%" colspan="3">
																		<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
																		<input type="text" value="<?php echo isset($serumdata['zoonotic_disease_dec']) ? $serumdata['zoonotic_disease_dec'] : '';?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;">
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;">
														<label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('additional_information'); ?></label>
														<textarea style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:100px; padding:0 10px;"><?php echo isset($serumdata['additional_information']) ? $serumdata['additional_information'] : '';?></textarea>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="48%" align="right" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('elimination_diet'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td width="20%"><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php echo ( isset($serumdata['elimination_diet']) && $serumdata['elimination_diet']==1) ? 'checked' : ''; ?> /> <?php echo $this->lang->line('yes'); ?></label></td>
																	<td width="20%"><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php echo ( isset($serumdata['elimination_diet']) && $serumdata['elimination_diet']==2) ? 'checked' : ''; ?> /> <?php echo $this->lang->line('no'); ?></label></td>
																	<td width="60%" colspan="3">
																		<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
																		<input type="text" value="<?php echo isset($serumdata['other_elimination']) ? $serumdata['other_elimination'] : '';?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;">
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<td colspan="5"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('medication'); ?></label></td>
																</tr>
																<tr><td height="10"></td></tr>
																<tr>
																	<td width="20%"><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php echo ( isset($serumdata['medication']) && $serumdata['medication']==1) ? 'checked' : ''; ?> /> <?php echo $this->lang->line('yes'); ?></label></td>
																	<td width="20%"><label style="display:block; color:#346a7e; font-size:14px;"><input type="radio" style="margin:0 5px 0 0;" <?php echo ( isset($serumdata['medication']) && $serumdata['medication']==0) ? 'checked' : ''; ?> /> <?php echo $this->lang->line('no'); ?></label></td>
																	<td width="60%" colspan="3">
																		<label style="display:block; color:#346a7e; margin:0 0 5px 0; font-size:14px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</label>
																		<input type="text" value="<?php echo isset($serumdata['medication_desc']) ? $serumdata['medication_desc'] : '';?>" style="background:#ebeff1; border:1px solid #5b8398; outline:none; width:100%; height:32px; padding:0 10px;">
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr><td height="10"></td></tr>
												<tr>
													<td width="100%;" colspan="3">
														<table cellspacing="0" cellpadding="0" border="0" width="100%">
															<tbody>
																<tr>
																	<fieldset style="width:100%; border:1px solid #426e89; height:140px;">
																		<legend style="color:#426e89;"><?php echo $this->lang->line('internal_use_only'); ?></legend>
																	</fieldset>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%"><tbody><tr><td height="10"></td></tr></tbody></table>
									</td>
								</tr>
							</tbody>
						</table>
						<table width="100%" style="padding:10px 30px; background:#e5f2f5; max-height:50px;">
							<tbody>
								<tr>
									<td valign="middle"><p style="margin:0; color:#426e89; font-size:18px;"><?php echo $this->lang->line('allergy_resources_visit'); ?></p></td>
									<td valign="bottom"><img src="<?php echo base_url("/assets/images/lock.png"); ?>" alt="Login" /></td>
								</tr>
							</tbody>
						</table>
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tbody>
								<tr>
									<td style="background:#426e89; padding:20px 15px;" align="center">
										<h5 style="color:#ffffff; font-size:30px; font-weight:600; line-height:22px; text-transform:uppercase; margin:0 0 10px 0;"><?php echo $this->lang->line('sample_submission_form_2'); ?></h5>
										<p style="color:#d7ecf0; font-size:20px; line-height:22px; text-transform:uppercase; margin:0;"><?php echo $this->lang->line('individual_test_box_2'); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr><td colspan="2" height="20"></td></tr>
							<tr>
								<td width="30%" valign="top">
									<h5 style="color:#426e89;font-size: 25px; font-weight:600; line-height:22px; text-transform:uppercase;margin: 0 15px 10px 0;text-align: right;"><?php echo $this->lang->line('storage_only'); ?></h5>
								</td>
								<td width="70%" valign="top">
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#1e3743; font-size:14px;"><?php echo $this->lang->line('charge_for_3_months'); ?></label></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<?php if($order_details['species_name'] == 'Dog'){ ?>
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr><td colspan="2" height="20"></td></tr>
							<tr>
								<td width="20%" valign="top"><img src="<?php echo base_url("/assets/images/dog.png"); ?>" width="270" alt="CANINE TEST" /></td>
								<td width="80%" valign="top">
									<table cellpadding="0" cellspacing="0" border="0" style="padding:23px 0 0 0;">
										<tr>
											<td style="background:#cee8ee; color:#426e89; font-size:32px;" valign="middle"><?php echo $this->lang->line('canine_tests_2'); ?></td>
											<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail1.png"); ?>" height="68" alt="CANINE" /></td>
											<td valign="top"></td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td height="30"></td></tr>
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('nextlab_2'); ?></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('comp_env_food_serum_result'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('env_penal'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('food_penal_serum_result'); ?></label></td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td height="30"></td></tr>
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('nextlab_screens_2'); ?> <small style="color:#333333; font-size:50%;"><?php echo $this->lang->line('posi_neg_serum_result'); ?></small></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('env_screen_serum_result'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('food_screen_serum_result'); ?></label></td>
										</tr>
										<tr><td height="30"></td></tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('phase_apps'); ?></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('c_crp_sample'); ?></label></td>
										</tr>
										<tr><td height="30"></td></tr>
									</table>
								</td>
							</tr>
						</table>
						<?php } ?>
						<?php if($order_details['species_name'] == 'Cat'){ ?>
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="20%" valign="top"><img src="<?php echo base_url("/assets/images/cat.png"); ?>" width="270" alt="FELINE TESTS" /></td>
								<td width="80%" valign="top">
									<table cellpadding="0" cellspacing="0" border="0" style="padding:38px 0 0 0;">
										<tr>
											<td style="background:#7dc1c9; color:#ffffff; font-size:32px;" valign="middle"><?php echo $this->lang->line('feline_tests'); ?></td>
											<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail2.png"); ?>" height="67" alt="FELINE" /></td>
											<td valign="top"></td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td height="30"></td></tr>
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('nextlab_2'); ?></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('comp_env_food_serum_result'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('env_penal'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('food_penal_serum_result'); ?></label></td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td height="30"></td></tr>
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('nextlab_screens_2'); ?> <small style="color:#333333; font-size:50%;"><?php echo $this->lang->line('posi_neg_serum_result'); ?></small></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('env_screen_serum_result'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('food_screen_serum_result'); ?></label></td>
										</tr>
										<tr><td height="30"></td></tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('phase_apps'); ?></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('glycoprotein_agp_serum_result'); ?></label></td>
										</tr>
										<tr><td height="30"></td></tr>
									</table>
								</td>
							</tr>
						</table>
						<?php } ?>
						<?php if($order_details['species_name'] == 'Horse'){ ?>
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="20%" valign="top"><img src="<?php echo base_url("/assets/images/horse.png"); ?>" width="270" alt="EQUINE TESTS" /></td>
								<td width="80%" valign="top">
									<table cellpadding="0" cellspacing="0" border="0" style="padding:24px 0 0 0;">
										<tr>
											<td style="background:#b8c6d6; color:#426e89; font-size:32px;" valign="middle"><?php echo $this->lang->line('equine_tests_2'); ?></td>
											<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail3.png"); ?>" height="68" alt="EQUINE" /></td>
											<td valign="top"></td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td height="30"></td></tr>
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('nextlab_2'); ?></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('comp_env_food_serum_result'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('env_penal'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('food_penal_serum_result'); ?></label></td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td height="30"></td></tr>
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('nextlab_screens_2'); ?> <small style="color:#333333; font-size:50%;"><?php echo $this->lang->line('posi_neg_serum_result'); ?></small></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('env_screen_serum_result'); ?></label></td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('food_screen_serum_result'); ?></label></td>
										</tr>
										<tr><td height="30"></td></tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="">
										<tr><td colspan="2" style="font-weight:700; color:#426e89; font-size:24px;"><?php echo $this->lang->line('phase_apps'); ?></td></tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td valign="top"><input type="checkbox" style="margin:0 10px 0 0;" /></td>
											<td valign="top"><label style="display:block; color:#346a7e; font-size:14px;"><?php echo $this->lang->line('env_ins_screen_serum_result'); ?></label></td>
										</tr>
										<tr><td height="30"></td></tr>
									</table>
								</td>
							</tr>
						</table>
						<?php } ?>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 15px;">
							<tr>
								<td align="left">
									<p style="color:#333333; font-size:13px; line-height:20px;"><?php echo $this->lang->line('nextmune_laboratories_serum_result'); ?></p>
									<p style="color:#333333; font-size:13px; line-height:20px;"><?php echo $this->lang->line('devlopment_purposes_serum_result'); ?></br>
									<?php echo $this->lang->line('nextm_labo_utilise'); ?></p>
								</td>
							</tr>
						</table>
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td style="background:#426e89; padding:0 15px;" align="center">
									<p style="color:#ffffff; font-size:15px; line-height:22px;"><?php echo $this->lang->line('nextm_labo_limited_serum_result'); ?><br><?php echo $this->lang->line('t_0800_e'); ?> – <a style="color:#ffffff;" href="mailto:vetorders.uk@nextmune.com"><?php echo $this->lang->line('contact_email'); ?></a></p>
								</td>
							</tr>
						</table>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:10px 30px;">
							<tr>
								<td style="font-size:13px; font-weight:600; color:#333333;">&copy; <?php echo $this->lang->line('netxmune_2022'); ?></td>
								<td style="font-size:12px; color:#333333;" align="right"><?php echo $this->lang->line('nm035_06_22'); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>