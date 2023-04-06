<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<style type="text/css">
			.fileUpload{position:relative;overflow:hidden;border-radius:0;margin-left:-4px;margin-top:-2px}
			.fileUpload input.upload{position:absolute;top:0;right:0;margin:0;padding:0;font-size:20px;cursor:pointer;opacity:0;filter:alpha(opacity=0)}
			#demolink{display:none}
			</style>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						Import Pets
						<small>Imports</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Imports</a></li>
						<li class="active">Pets</li>
					</ol>
				</section>

				<section class="content">
					<?php if(!empty($this->session->flashdata('success'))){ ?>
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Alert!</h4>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>

					<?php if(!empty($this->session->flashdata('error'))){ ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-warning"></i> Alert!</h4>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php } ?>
					<div class="row">
						<div class="col-xs-12">
							<div class="box">
								<form action="<?=base_url()?>ImportExportExcel/insertPets" method="post" enctype="multipart/form-data">
									<div class="box-body" style="text-align:center;padding:10% 0px;">
										<div class="row">
											<div class="col-md-4"></div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Excel File <span style="color: #F00">*</span></label>
													<br />
													<input id="uploadFile" readonly style="height: 40px; width: 250px; border: 1px solid #ccc" />
													<div class="fileUpload btn btn-primary" style="padding: 9px 12px;">
														<span>Browse</span>
														<input id="uploadBtn" name="result_file" required="" type="file" class="upload" />
													</div>
												</div>
											</div>
											<div class="col-md-4"></div>
										</div>
									</div>
									<div class="box-footer" style="text-align:center;">
										<button type="submit" class="btn btn-primary">Import</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>  
		</div>
		<?php $this->load->view("script"); ?>
		<script type="text/javascript">
		$(document).ready(function () {
			document.getElementById("uploadBtn").onchange = function () {
				document.getElementById("uploadFile").value = this.value;
			};
		});
		</script>
	</body>
</html>