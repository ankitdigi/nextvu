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
		$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
		foreach($getAllergenFParent as $rowf){
		?>
			<tr>
				<td colspan="2" style="padding:0 0 5px 0;"><h5 style="margin:0; padding:0; font-size:14px; text-transform:uppercase; color:#2a5b74;"><?php echo $rowf['name']; ?></h5></td>
			</tr>
			<?php
			$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
			foreach($subfAllergens as $sfvalue){
				$this->db->select('*');
				$this->db->from('ci_serum_result_allergens');
				$this->db->where('result_id IN('.$sresultID.')');
				$this->db->where('type_id IN('.$stypeID.')');
				$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'")');
				$this->db->order_by('id', 'ASC');
				$serumfResults = $this->db->get()->row();
				if($serumfResults->result > $cutboff){
					echo '<tr>
						<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumfResults->name.' IgE</td>
						<td style="width:49%;vertical-align: top;font-size:13px;">POSITIVE</td>
					</tr>';
				}elseif($serumfResults->result <= $cutboff && $serumfResults->result >= $cutaoff){
					echo '<tr>
						<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumfResults->name.' IgE</td>
						<td style="width:49%;vertical-align: top;font-size:13px;">BORDER LINE</td>
					</tr>';
				}else{
					echo '<tr>
						<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumfResults->name.' IgE</td>
						<td style="width:49%;vertical-align: top;font-size:13px;">NEGATIVE</td>
					</tr>';
				}

				$this->db->select('*');
				$this->db->from('ci_serum_result_allergens');
				$this->db->where('result_id IN('.$sresultID.')');
				$this->db->where('type_id IN('.$stypeID.')');
				$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
				$this->db->order_by('id', 'ASC');
				$serumfgResults = $this->db->get()->row();
				if($serumfgResults->result > $cutboff){
					echo '<tr>
						<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumfgResults->name.' IgG</td>
						<td style="width:49%;vertical-align: top;font-size:13px;">POSITIVE</td>
					</tr>';
				}elseif($serumfgResults->result <= $cutboff && $serumfgResults->result >= $cutaoff){
					echo '<tr>
						<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumfgResults->name.' IgG</td>
						<td style="width:49%;vertical-align: top;font-size:13px;">BORDER LINE</td>
					</tr>';
				}else{
					echo '<tr>
						<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumfgResults->name.' IgG</td>
						<td style="width:49%;vertical-align: top;font-size:13px;">NEGATIVE</td>
					</tr>';
				}
				echo '<tr><td colspan="2" height="10"></td></tr>';
			}
			echo '<tr><td colspan="2" height="20"></td></tr>';
		}
		?>
	</tbody>
</table>