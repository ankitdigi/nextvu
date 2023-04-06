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
		$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
		foreach($getAllergenParent as $row1){ 
		?>
			<tr>
				<td colspan="2" style="padding:0 0 5px 0;"><h5 style="margin:0; padding:0; font-size:14px; text-transform:uppercase; color:#2a5b74;"><?php echo $row1['name']; ?></h5></td>
			</tr>
			<?php
			$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
			foreach($sub2Allergens as $s2value){
				$this->db->select('*');
				$this->db->from('ci_serum_result_allergens');
				$this->db->where('result_id IN('.$sresultID.')');
				$this->db->where('type_id IN('.$stypeID.')');
				$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
				$this->db->order_by('id', 'ASC');
				$serumResults = $this->db->get()->row();
				if(!empty($serumResults)){
					if($serumResults->result > $cutboff){
						echo '<tr>
							<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumResults->name.'</td>
							<td style="width:49%;vertical-align: top;font-size:13px;">POSITIVE</td>
						</tr>';
					}elseif($serumResults->result <= $cutboff && $serumResults->result >= $cutaoff){
						echo '<tr>
							<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumResults->name.'</td>
							<td style="width:49%;vertical-align: top;font-size:13px;">BORDER LINE</td>
						</tr>';
					}else{
						echo '<tr>
							<td style="width:49%;vertical-align: top;font-size:13px;">'.$serumResults->name.'</td>
							<td style="width:49%;vertical-align: top;font-size:13px;">NEGATIVE</td>
						</tr>';
					}
				}
			}
			echo '<tr><td colspan="2" height="20"></td></tr>';
		}
		?>
		<tr>
			<td style="width:49%;vertical-align: top;">
				<h5 style="margin:0; padding:0; font-size:14px; text-transform:uppercase; color:#2a5b74;">Malassezia</h5>
			</td>
			<?php
			$this->db->select('result');
			$this->db->from('ci_serum_result_allergens');
			$this->db->where('result_id IN('.$sresultID.')');
			$this->db->where('type_id IN('.$stypeID.')');
			$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
			$this->db->order_by('result', 'DESC');
			$malasseziaResults = $this->db->get()->row();
			if(!empty($malasseziaResults)){
				if($malasseziaResults->result > $cutdoff){
					echo '<td style="width:49%;vertical-align: top;font-size:13px;">POSITIVE</td>';
				}elseif($malasseziaResults->result <= $cutdoff && $malasseziaResults->result >= $cutcoff){
					echo '<td style="width:49%;vertical-align: top;font-size:13px;">BORDER LINE</td>';
				}else{
					echo '<td style="width:49%;vertical-align: top;">NEGATIVE</td>';
				}
			}else{
				echo '<td style="width:49%;vertical-align: top;font-size:13px;">NEGATIVE</td>';
			}
			?>
		</tr>
	</tbody>
</table>