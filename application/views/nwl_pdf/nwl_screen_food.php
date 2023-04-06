<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
	<tbody>
		<tr>
			<td style="width:49%;vertical-align: top;">
				<h5 style="margin:0; padding:0; font-size:14px;color:#2a5b74;">Allergen</h5>
			</td>
			<td style="width:49%;vertical-align: top;">
				<h5 style="margin:0; padding:0; font-size:14px;color:#2a5b74;">Result</h5>
			</td>
		</tr>
		<tr><td colspan="2" height="10"></td></tr>
		<?php 
		if($data['cutoff_version'] == 1){
			$cutaoff = '5';
			$cutboff = '10';
			$cutcoff = '60';
			$cutdoff = '75';
		}elseif($data['cutoff_version'] == 2){
			$cutaoff = '100';
			$cutboff = '200';
			$cutcoff = '1200';
			$cutdoff = '1500';
		}else{
			$cutaoff = '200';
			$cutboff = '250';
			$cutcoff = '1200';
			$cutdoff = '1500';
		}
		/* Start Food Proteins */
		$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
		$counterFPN = $counterFPB = $counterFPP = 0;
		foreach($proteinsAllergens as $fpvalue){
			$this->db->select('result');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
			$this->db->order_by('result', 'DESC');
			$fpResults = $this->db->get()->row();
			if(!empty($fpResults)){
				if($fpResults->result > $cutboff){
					$counterFPP++;
				}elseif($fpResults->result <= $cutboff && $fpResults->result >= $cutaoff){
					$counterFPB++;
				}else{
					$counterFPN++;
				}
			}
		}
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px; text-transform:uppercase;">Food Proteins</td>';
			if($counterFPP > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
			}elseif($counterFPB > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		echo '<tr><td colspan="2" height="20"></td></tr>';
		/* End Food Proteins */

		/* Start Food Carbohydrates */
		$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
		$counterFCN = $counterFCB = $counterFCP = 0;
		foreach($carbohyAllergens as $fcvalue){
			$this->db->select('result');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
			$this->db->order_by('result', 'DESC');
			$fcResults = $this->db->get()->row();
			if(!empty($fcResults)){
				if($fcResults->result > $cutboff){
					$counterFCP++;
				}elseif($fcResults->result <= $cutboff && $fcResults->result >= $cutaoff){
					$counterFCB++;
				}else{
					$counterFCN++;
				}
			}
		}
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px; text-transform:uppercase;">Food Carbohydrates</td>';
			if($counterFCP > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
			}elseif($counterFCB > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		/* End Food Carbohydrates */
		?>
	</tbody>
</table>