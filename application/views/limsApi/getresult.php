<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						LIMS API Run
						<small>Send/Receive Authorised Results to/from LIMS</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> LIMS API Management</a></li>
						<li class="active">LIMS API Run</li>
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
								<div class="box-body" style="text-align:center;padding:10% 0px;">
									<div class="row">
										<div class="col-sm-6 col-md-6 col-lg-6">
											<a onclick="return confirm('Are you sure you want to run LIMS API’s?')" class="btn btn-primary" href="<?php echo base_url('LimsAPI/sendOrderstoLIMS'); ?>" title="Get LIMS Result"> Click here to send latest confirmed Nextlab orders to LIMS</a>
										</div>
										<div class="col-sm-6 col-md-6 col-lg-6">
											<a onclick="return confirm('Are you sure you want to run LIMS API’s?')" class="btn btn-primary" href="<?php echo base_url('LimsAPI/getLIMSResult'); ?>" title="Get LIMS Result"> Click here to collect latest results from LIMS</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>  
		</div>
		<?php $this->load->view("script"); ?>
	</body>
</html>