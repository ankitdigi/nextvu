<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
	<tbody>
		<tr>
			<td>
				<h4 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#366784;"><?php echo $this->lang->line("medical_history");?></h4>
			</td>
		</tr>
	</tbody>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>

<table width="100%">
	<tbody>
		<tr>
			<td style="padding:0 5px;">
				<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="100%">
					<tbody>
						<?php if(isset($serumdata['serum_drawn_date'])){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("date_serum_drawn");?></th>
							<td style="color:#000000;text-align: left;"><?php echo date("d/m/Y",strtotime($serumdata['serum_drawn_date']));?></td>
						</tr>
						<?php } ?>
						<?php if(isset($serumdata['major_symptoms']) && ((strpos($serumdata['major_symptoms'], '0' ) !== false) || (strpos($serumdata['major_symptoms'], '1' ) !== false) || (strpos($serumdata['major_symptoms'], '2' ) !== false) || (strpos($serumdata['major_symptoms'], '3' ) !== false) || (strpos($serumdata['major_symptoms'], '4' ) !== false) || (strpos($serumdata['major_symptoms'], '5' ) !== false) || (strpos($serumdata['major_symptoms'], '6' ) !== false) || (strpos($serumdata['major_symptoms'], '7' ) !== false))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("patient_affected");?></th>
							<td style="color:#000000;text-align: left;">
								<?php if(strpos($serumdata['major_symptoms'], '1' ) !== false){ ?>
									<?php echo $this->lang->line("pruritus_itch");?> 
								<?php } ?>
								<?php if(strpos($serumdata['major_symptoms'], '2' ) !== false){ ?>
									<?php echo $this->lang->line("otitis");?>	
								<?php } ?>
								<?php if(strpos($serumdata['major_symptoms'], '3' ) !== false){ ?>
									<?php echo $this->lang->line("respiratory_signs");?>
								<?php } ?>
								<?php if(strpos($serumdata['major_symptoms'], '4' ) !== false){ ?>
									<?php echo $this->lang->line("gastro_intestinal_signs");?>
								<?php } ?>
								<?php if(strpos($serumdata['major_symptoms'], '5' ) !== false){ ?>
									<?php echo $this->lang->line("skin_lesions");?> 
								<?php } ?>
								<?php if(strpos($serumdata['major_symptoms'], '6' ) !== false){ ?>
									<?php echo $this->lang->line("ocular_signs");?>
								<?php } ?>
								<?php if(strpos($serumdata['major_symptoms'], '7' ) !== false){ ?>
									<?php echo $this->lang->line("anaphylaxis");?> 
								<?php } ?>
								<?php if(strpos($serumdata['major_symptoms'], '0' ) !== false){ ?>
								<?php echo isset($serumdata['other_symptom']) ? $serumdata['other_symptom'] : ''; ?>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if($serumdata['symptom_appear_age_month'] != '' || $serumdata['symptom_appear_age'] != ''){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("at_what_age_did_these_symptoms_first_appear");?></th>
							<td style="color:#000000;text-align: left;"><?php echo $serumdata['symptom_appear_age_month'].'/'.$serumdata['symptom_appear_age']; ?></td>
						</tr>
						<?php } ?>
						<?php if(isset($serumdata['when_obvious_symptoms']) && ((strpos( $serumdata['when_obvious_symptoms'], '1' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '2' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '3' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '4' ) !== false) || (strpos( $serumdata['when_obvious_symptoms'], '5' ) !== false))){ ?> 
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("symptoms_most_obvious");?></th>
							<td style="color:#000000;text-align: left;">
								<?php if(strpos($serumdata['when_obvious_symptoms'], '1' ) !== false){ ?>
									<?php echo $this->lang->line("spring");?> 
								<?php } ?>
								<?php if(strpos($serumdata['when_obvious_symptoms'], '2' ) !== false){ ?>
									<?php echo $this->lang->line("summer");?> 
								<?php } ?>
								<?php if(strpos($serumdata['when_obvious_symptoms'], '3' ) !== false){ ?>
									<?php echo $this->lang->line("fall");?> 
								<?php } ?>
								<?php if(strpos($serumdata['when_obvious_symptoms'], '4' ) !== false){ ?>
									<?php echo $this->lang->line("winter");?> 
								<?php } ?>
								<?php if(strpos($serumdata['when_obvious_symptoms'], '5' ) !== false){ ?>
									<?php echo $this->lang->line("year_round");?>
																	<?php } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if(isset($serumdata['where_obvious_symptoms']) && ((strpos( $serumdata['where_obvious_symptoms'], '1' ) !== false) || (strpos( $serumdata['where_obvious_symptoms'], '2' ) !== false) || (strpos( $serumdata['where_obvious_symptoms'], '3' ) !== false))){ ?> 
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;">	<?php echo $this->lang->line("symptoms_most_obvious");?></th>
							<td style="color:#000000;text-align: left;">
								<?php if(strpos($serumdata['where_obvious_symptoms'], '1' ) !== false){ ?>
									<?php echo $this->lang->line("indoors");?> 
								<?php } ?>
								<?php if(strpos($serumdata['where_obvious_symptoms'], '2' ) !== false){ ?>
									<?php echo $this->lang->line("outdoors");?> 
								<?php } ?>
								<?php if(strpos($serumdata['where_obvious_symptoms'], '3' ) !== false){ ?>
									<?php echo $this->lang->line("no_difference");?>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['diagnosis_food']) && ($serumdata['diagnosis_food'] == 1 || $serumdata['diagnosis_food'] == 2)) || (isset($serumdata['diagnosis_hymenoptera']) && ($serumdata['diagnosis_hymenoptera'] == 1 || $serumdata['diagnosis_hymenoptera'] == 2)) || (isset($serumdata['diagnosis_other']) && ($serumdata['diagnosis_other'] == 1 || $serumdata['diagnosis_other'] == 2))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;">	<?php echo $this->lang->line("has_there_been_a_clinical_diagnosis_of_allergy_to_the_following");?></th>
							<td style="color:#000000;text-align: left;"></td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['diagnosis_food']) && ($serumdata['diagnosis_food'] == 1 || $serumdata['diagnosis_food'] == 2))){ ?>
							<tr>
								<th style="color:#346a7e;text-align: left;width:530px;">	<?php echo $this->lang->line("food");?></th>
								<td style="color:#000000;text-align: left;">
									<?php 
									if(isset($serumdata['diagnosis_food']) && $serumdata['diagnosis_food'] == 1){ 
										if(isset($serumdata['other_diagnosis_food']) && $serumdata['other_diagnosis_food'] != ""){ 
											echo 'Yes, '.$serumdata['other_diagnosis_food'];
										}else{
											echo 'Yes';
										}
									} ?>
									<?php if(isset($serumdata['diagnosis_food']) && $serumdata['diagnosis_food'] == 2){ echo 'No'; } ?>
								</td>
							</tr>
						<?php } ?>
						<?php if((isset($serumdata['diagnosis_food']) && $serumdata['diagnosis_food'] == 1) && (isset($serumdata['food_challenge']) && ((strpos( $serumdata['food_challenge'], '1' ) !== false) || (strpos( $serumdata['food_challenge'], '2' ) !== false) || (strpos( $serumdata['food_challenge'], '3' ) !== false) || (strpos( $serumdata['food_challenge'], '4' ) !== false) || (strpos( $serumdata['food_challenge'], '5' ) !== false)))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;">	<?php echo $this->lang->line("food_challenge");?></th>
							<td style="color:#000000;text-align: left;">
								<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '1' ) !== false)){ echo '&lt; 3 hours'; } ?>  
								<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '2' ) !== false)){ echo '3-12 hours'; } ?>  
								<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '3' ) !== false)){ echo '12-24 hours'; } ?>  
								<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '4' ) !== false)){ echo '24-48 h'; } ?>  
								<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '5' ) !== false)){ echo '&gt; 48 h'; } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['diagnosis_hymenoptera']) && ($serumdata['diagnosis_hymenoptera'] == 1 || $serumdata['diagnosis_hymenoptera'] == 2))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;">	<?php echo $this->lang->line("hymenoptera_stings");?></th>
							<td style="color:#000000;text-align: left;">
								<?php 
								if(isset($serumdata['diagnosis_hymenoptera']) && $serumdata['diagnosis_hymenoptera'] == 1){ 
									if(isset($serumdata['other_diagnosis_hymenoptera']) && $serumdata['other_diagnosis_hymenoptera'] != ""){ 
										echo 'Yes, '.$serumdata['other_diagnosis_hymenoptera'];
									}else{
										echo 'Yes';
									}
								} ?>
								<?php if(isset($serumdata['diagnosis_hymenoptera']) && $serumdata['diagnosis_hymenoptera'] == 2){ echo 'No'; } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['diagnosis_other']) && ($serumdata['diagnosis_other'] == 1 || $serumdata['diagnosis_other'] == 2))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("other_s");?></th>
							<td style="color:#000000;text-align: left;">
								<?php 
								if(isset($serumdata['diagnosis_other']) && $serumdata['diagnosis_other'] == 1){ 
									if(isset($serumdata['other_diagnosis']) && $serumdata['other_diagnosis'] != ""){ 
										echo 'Yes, '.$serumdata['other_diagnosis'];
									}else{
										echo 'Yes';
									}
								} ?>
								<?php if(isset($serumdata['diagnosis_other']) && $serumdata['diagnosis_other'] == 2){ echo 'No'; } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if(isset($serumdata['regularly_exposed']) && ((strpos($serumdata['regularly_exposed'], '1') !== false) || (strpos($serumdata['regularly_exposed'], '2') !== false) || (strpos($serumdata['regularly_exposed'], '3') !== false) || (strpos($serumdata['regularly_exposed'], '4') !== false) || (strpos($serumdata['regularly_exposed'], '5') !== false) || (strpos($serumdata['regularly_exposed'], '6') !== false) || (strpos($serumdata['regularly_exposed'], '7') !== false) || (strpos($serumdata['regularly_exposed'], '0') !== false))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("exposed_following_animals");?></th>
							<td style="color:#000000;text-align: left;">
								<?php if(strpos($serumdata['regularly_exposed'], '1' ) !== false){ ?>
									<?php echo $this->lang->line("cats");?>
								<?php } ?>
								<?php if(strpos($serumdata['regularly_exposed'], '2' ) !== false){ ?>
									<?php echo $this->lang->line("dogs");?>
								<?php } ?>
								<?php if(strpos($serumdata['regularly_exposed'], '3' ) !== false){ ?>
									<?php echo $this->lang->line("horses");?>
								<?php } ?>
								<?php if(strpos($serumdata['regularly_exposed'], '4' ) !== false){ ?>
									<?php echo $this->lang->line("cattle");?> 
								<?php } ?>
								<?php if(strpos($serumdata['regularly_exposed'], '5' ) !== false){ ?>
									<?php echo $this->lang->line("mice");?> 
								<?php } ?>
								<?php if(strpos($serumdata['regularly_exposed'], '6' ) !== false){ ?>
									<?php echo $this->lang->line("guinea_pigs");?> 
								<?php } ?>
								<?php if(strpos($serumdata['regularly_exposed'], '7' ) !== false){ ?>
									<?php echo $this->lang->line("rabbits");?> 
								<?php } ?>
								<?php if($serumdata['other_exposed'] != ""){ echo $serumdata['other_exposed']; } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if(isset($serumdata['malassezia_infections']) && ((strpos( $serumdata['malassezia_infections'], '1' ) !== false) || (strpos( $serumdata['malassezia_infections'], '2' ) !== false))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;">	<?php echo $this->lang->line("malassezia_infections");?></th>
							<td style="color:#000000;text-align: left;">
								<?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '1' ) !== false) ){ echo 'Malassezia otitis, '; } ?>
								<?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '2' ) !== false) ){ echo 'Malassezia dermatitis'; } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if(isset($serumdata['receiving_drugs']) && ((strpos($serumdata['receiving_drugs'], '1') !== false) || (strpos($serumdata['receiving_drugs'], '2') !== false) || (strpos($serumdata['receiving_drugs'], '3') !== false) || (strpos($serumdata['receiving_drugs'], '4') !== false) || (strpos($serumdata['receiving_drugs'], '5') !== false) || (strpos($serumdata['receiving_drugs'], '6') !== false))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;">	<?php echo $this->lang->line("receiving_drugs");?></th>
							<td>
								<?php 
								if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '1' ) !== false) ){
									if(isset($serumdata['receiving_drugs_1']) && $serumdata['receiving_drugs_1'] == 1){ 
										echo 'Glucocorticoids (oral, topical, injectable) - No response'; 
									}elseif(isset($serumdata['receiving_drugs_1']) && $serumdata['receiving_drugs_1'] == 2){ 
										echo 'Glucocorticoids (oral, topical, injectable) - Fair response';
									}elseif(isset($serumdata['receiving_drugs_1']) && $serumdata['receiving_drugs_1'] == 3){ 
										echo 'Glucocorticoids (oral, topical, injectable) - Good to excellent response';
									}else{
										echo 'Glucocorticoids (oral, topical, injectable)';
									}
									echo ', ';
								}

								if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '2' ) !== false) ){
									if(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_2'] == 1){ 
										echo 'Ciclosporin - No response'; 
									}elseif(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_2'] == 2){ 
										echo 'Ciclosporin - Fair response';
									}elseif(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_2'] == 3){ 
										echo 'Ciclosporin - Good to excellent response';
									}else{
										echo 'Ciclosporin';
									}
									echo ', ';
								}

								if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '3' ) !== false) ){
									if(isset($serumdata['receiving_drugs_3']) && $serumdata['receiving_drugs_3'] == 1){ 
										echo 'Oclacitinib (Apoquel) - No response'; 
									}elseif(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_3'] == 2){ 
										echo 'Oclacitinib (Apoquel) - Fair response';
									}elseif(isset($serumdata['receiving_drugs_2']) && $serumdata['receiving_drugs_3'] == 3){ 
										echo 'Oclacitinib (Apoquel) - Good to excellent response';
									}else{
										echo 'Oclacitinib (Apoquel)';
									}
									echo ', ';
								}

								if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '4' ) !== false) ){
									if(isset($serumdata['receiving_drugs_4']) && $serumdata['receiving_drugs_4'] == 1){ 
										echo 'Lokivetmab (Cytopoint) - No response'; 
									}elseif(isset($serumdata['receiving_drugs_4']) && $serumdata['receiving_drugs_4'] == 2){ 
										echo 'Lokivetmab (Cytopoint) - Fair response';
									}elseif(isset($serumdata['receiving_drugs_4']) && $serumdata['receiving_drugs_4'] == 3){ 
										echo 'Lokivetmab (Cytopoint) - Good to excellent response';
									}else{
										echo 'Lokivetmab (Cytopoint)';
									}
									echo ', ';
								}

								if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '5' ) !== false) ){
									if(isset($serumdata['receiving_drugs_5']) && $serumdata['receiving_drugs_5'] == 1){ 
										echo 'Antibiotics - No response'; 
									}elseif(isset($serumdata['receiving_drugs_5']) && $serumdata['receiving_drugs_5'] == 2){ 
										echo 'Antibiotics - Fair response';
									}elseif(isset($serumdata['receiving_drugs_5']) && $serumdata['receiving_drugs_5'] == 3){ 
										echo 'Antibiotics - Good to excellent response';
									}else{
										echo 'Antibiotics';
									}
									echo ', ';
								}

								if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '6' ) !== false) ){
									if(isset($serumdata['receiving_drugs_6']) && $serumdata['receiving_drugs_6'] == 1){ 
										echo 'Antifungals - No response'; 
									}elseif(isset($serumdata['receiving_drugs_6']) && $serumdata['receiving_drugs_6'] == 2){ 
										echo 'Antifungals - Fair response';
									}elseif(isset($serumdata['receiving_drugs_6']) && $serumdata['receiving_drugs_6'] == 3){ 
										echo 'Antifungals - Good to excellent response';
									}else{
										echo 'Antifungals';
									}
									echo ', ';
								}
								?>
							</td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['treatment_ectoparasites']) && ($serumdata['treatment_ectoparasites'] == 1 || $serumdata['treatment_ectoparasites'] == 2))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("treatment_ectoparasites");?> </th>
							<td style="color:#000000;text-align: left;">
								<?php 
								if(isset($serumdata['treatment_ectoparasites']) && $serumdata['treatment_ectoparasites'] == 1){ 
									if(isset($serumdata['other_ectoparasites']) && $serumdata['other_ectoparasites'] != ""){ 
										echo 'Yes, '.$serumdata['other_ectoparasites'];
									}else{
										echo 'Yes';
									}
								} ?>
								<?php if(isset($serumdata['treatment_ectoparasites']) && $serumdata['treatment_ectoparasites'] == 2){ echo 'No'; } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['elimination_diet']) && ($serumdata['elimination_diet'] == 1 || $serumdata['elimination_diet'] == 2))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line(" elimination_diet");?></th>
							<td style="color:#000000;text-align: left;">
								<?php 
								if(isset($serumdata['elimination_diet']) && $serumdata['elimination_diet'] == 1){ 
									if(isset($serumdata['other_elimination']) && $serumdata['other_elimination'] != ""){ 
										echo 'Yes, '.$serumdata['other_elimination'];
									}else{
										echo 'Yes';
									}
								} ?>
								<?php if(isset($serumdata['elimination_diet']) && $serumdata['elimination_diet'] == 2){ echo 'No'; } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['additional_information']) && $serumdata['additional_information'] != "")){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line(" additional_information");?></th>
							<td style="color:#000000;text-align: left;">
								<?php echo $serumdata['additional_information']; ?>
							</td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['zoonotic_disease']) && ($serumdata['zoonotic_disease'] == 1 || $serumdata['zoonotic_disease'] == 2))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("zoonotic_disease");?> </th>
							<td style="color:#000000;text-align: left;">
								<?php 
								if(isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease'] == 1){ 
									if(isset($serumdata['zoonotic_disease_dec']) && $serumdata['zoonotic_disease_dec'] != ""){ 
										echo 'Yes, '.$serumdata['zoonotic_disease_dec'];
									}else{
										echo 'Yes';
									}
								} ?>
								<?php if(isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease'] == 2){ echo 'No'; } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if((isset($serumdata['medication']) && ($serumdata['medication'] == 1 || $serumdata['medication'] == 2))){ ?>
						<tr style="height: 30px;">
							<th style="color:#346a7e;text-align: left;width:530px;"><?php echo $this->lang->line("medication");?></th>
							<td style="color:#000000;text-align: left;">
								<?php 
								if(isset($serumdata['medication']) && $serumdata['medication'] == 1){ 
									if(isset($serumdata['medication_desc']) && $serumdata['medication_desc'] != ""){ 
										echo 'Yes, '.$serumdata['medication_desc'];
									}else{
										echo 'Yes';
									}
								} ?>
								<?php if(isset($serumdata['medication']) && $serumdata['medication'] == 2){ echo 'No'; } ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>