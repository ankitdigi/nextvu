<table class="food_table" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%;">
	<tbody>
		<tr>
			<td style="width:49%;vertical-align: top;font-size:14px;">
				<h5 style="margin:0; padding:0; font-size:14px;color:#2a5b74;">Allergen</h5>
			</td>
			<td style="width:49%;vertical-align: top;font-size:14px;">
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
		/* Start Grasses */
		$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
		$countergN = $countergB = $countergP = 0;
		foreach($grassesAllergens as $gvalue){
			$this->db->select('*');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
			$this->db->order_by('id', 'ASC');
			$serumResults = $this->db->get()->row();
			if(!empty($serumResults)){
				if($serumResults->result > $cutboff){
					$countergP++;
				}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
					$countergB++;
				}else{
					$countergN++;
				}
			}
		}
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px;text-transform:uppercase;">Grasses</td>';
			if($countergP > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
			}elseif($countergB > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		echo '<tr><td colspan="2" height="20"></td></tr>';
		/* End Grasses */

		/* Start Weeds */
		$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
		$counterwN = $counterwB = $counterwP = 0;
		foreach($weedsAllergens as $wvalue){
			$this->db->select('*');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
			$this->db->order_by('id', 'ASC');
			$serumResults = $this->db->get()->row();
			if(!empty($serumResults)){
				if($serumResults->result > $cutboff){
					$counterwP++;
				}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
					$counterwB++;
				}else{
					$counterwN++;
				}
			}
		}
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px;text-transform:uppercase;">Weeds</td>';
			if($counterwP > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
			}elseif($counterwB > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		echo '<tr><td colspan="2" height="20"></td></tr>';
		/* End Weeds */

		/* Start Trees */
		$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
		$countertN = $countertB = $countertP = 0;
		foreach($treesAllergens as $tvalue){
			$this->db->select('*');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
			$this->db->order_by('id', 'ASC');
			$serumResults = $this->db->get()->row();
			if(!empty($serumResults)){
				if($serumResults->result > $cutboff){
					$countertP++;
				}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
					$countertB++;
				}else{
					$countertN++;
				}
			}
		}
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px;text-transform:uppercase;">Trees</td>';
			if($countertP > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
			}elseif($countertB > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		echo '<tr><td colspan="2" height="20"></td></tr>';
		/* End Trees */

		/* Start Crops */
		$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
		$countercN = $countercB = $countercP = 0;
		foreach($cropsAllergens as $cvalue){
			$this->db->select('*');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
			$this->db->order_by('id', 'ASC');
			$serumcResults = $this->db->get()->row();
			if(!empty($serumcResults)){
				if($serumcResults->result > $cutboff){
					$countercP++;
				}elseif($serumcResults->result <= $cutboff && $serumcResults->result >= $cutaoff){
					$countercB++;
				}else{
					$countercN++;
				}
			}
		}
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px;text-transform:uppercase;">Crops</td>';
			if($countercP > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
			}elseif($countercB > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		echo '<tr><td colspan="2" height="20"></td></tr>';
		/* End Crops */

		/* Start Indoor(Mites/Moulds/Epithelia) */
		$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
		$counteriN = $counteriB = $counteriP = 0;
		foreach($indoorAllergens as $ivalue){
			$this->db->select('*');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
			$this->db->order_by('id', 'ASC');
			$serumResults = $this->db->get()->row();
			if(!empty($serumResults)){
				if($serumResults->result > $cutboff){
					$counteriP++;
				}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
					$counteriB++;
				}else{
					$counteriN++;
				}
			}
		}
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px;text-transform:uppercase;">Indoor</td>';
			if($counteriP > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
			}elseif($counteriB > 0){
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		echo '<tr><td colspan="2" height="20"></td></tr>';
		/* End Indoor(Mites/Moulds/Epithelia) */

		if($data['species_name'] == 'Horse'){
			/* Start Insects */
			$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $data['allergens']);
			$counteritN = $counteritB = $counteritP = 0;
			foreach($insectAllergens as $ivalue){
				$this->db->select('*');
				$this->db->from('ci_serum_result_allergens');
				$this->db->where('result_id IN('.$sresultID.')');
				$this->db->where('type_id IN('.$stypeID.')');
				$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
				$this->db->order_by('id', 'ASC');
				$serumiResults = $this->db->get()->row();
				if(!empty($serumiResults)){
					if($serumiResults->result > $cutboff){
						$counteritP++;
					}elseif($serumiResults->result <= $cutboff && $serumiResults->result >= $cutaoff){
						$counteritB++;
					}else{
						$counteritN++;
					}
				}
			}
			echo '<tr>
				<td style="width:49%;vertical-align: top;font-size:14px;text-transform:uppercase;">Insects</td>';
				if($counteritP > 0){
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
				}elseif($counteritB > 0){
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
				}else{
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
				}
			echo '</tr>';
			echo '<tr><td colspan="2" height="20"></td></tr>';
			/* End Insects */
		}

		/* Start Flea */
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px;text-transform:uppercase;">Flea</td>';
			$this->db->select('result');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
			$this->db->order_by('id', 'ASC');
			$fleaResults = $this->db->get()->row();
			if(!empty($fleaResults)){
				if($fleaResults->result > $cutboff){
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
				}elseif($fleaResults->result <= $cutboff && $fleaResults->result >= $cutaoff){
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
				}else{
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
				}
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		echo '<tr><td colspan="2" height="20"></td></tr>';
		/* End Flea */

		/* Start Malassezia */
		echo '<tr>
			<td style="width:49%;vertical-align: top;font-size:14px;text-transform:uppercase;">Malassezia</td>';
			$this->db->select('result');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
			$this->db->order_by('id', 'ASC');
			$malasseziaResults = $this->db->get()->row();
			if(!empty($malasseziaResults)){
				if($malasseziaResults->result > $cutboff){
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">POSITIVE</td>';
				}elseif($malasseziaResults->result <= $cutboff && $malasseziaResults->result >= $cutaoff){
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">BORDER LINE</td>';
				}else{
					echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
				}
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:14px;">NEGATIVE</td>';
			}
		echo '</tr>';
		echo '<tr><td colspan="2" height="20"></td></tr>';
		/* End Malassezia */
		?>
	</tbody>
</table>