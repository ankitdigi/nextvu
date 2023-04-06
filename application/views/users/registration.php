<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="icon" href="<?php echo base_url(FAVICON_ICON); ?>" type="image" sizes="16x16">
		<title><?php echo FAVICON_NAME; ?>Registration</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?php echo base_url("assets/bower_components/bootstrap/dist/css/bootstrap.min.css"); ?>">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?php echo base_url("assets/bower_components/font-awesome/css/font-awesome.min.css"); ?>">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?php echo base_url("assets/bower_components/Ionicons/css/ionicons.min.css"); ?>">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo base_url("assets/dist/css/AdminLTE.min.css"); ?>">
		<!-- iCheck -->
		<link rel="stylesheet" href="<?php echo base_url("assets/plugins/iCheck/square/blue.css"); ?>">
		<link rel="stylesheet" href='<?php echo base_url("assets/dist/css/custom.css"); ?>' />
		<!-- Google Font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<a href="<?php echo base_url(); ?>"><img class="logo-img" src='<?php echo base_url("/assets/images/Nextmune_H-Logo_CMYK.png"); ?>' alt="logo" style="height: 48px;width: auto;"></a>
			</div>
			<div class="login-box-body">
				<p class="login-box-msg"></p>
				<?php if(!empty($this->session->flashdata('success'))){ ?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> Success!</h4>
					<?php echo $this->session->flashdata('success'); ?>
				</div>
				<?php } ?>

				<?php if(!empty($this->session->flashdata('error'))){ ?>
				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-warning"></i> Error!</h4>
					<?php echo $this->session->flashdata('error'); ?>
				</div>
				<?php } ?>
				<?php echo form_open(site_url('users/registration-form'), array('name'=>'registrationForm', 'id'=>'registrationForm')); ?>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="First Name" name="first_name" required="">
						<span class="form-control-feedback"><i class="fa fa-user" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="Last Name" name="last_name" required="">
						<span class="form-control-feedback"><i class="fa fa-user" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="Practice Name" name="practice_name" required="">
						<span class="form-control-feedback"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="Practice Email Address" name="practice_email" required="">
						<span class="form-control-feedback"><i class="fa fa-envelope" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="Practice Post Code" name="practice_postcode" required="">
						<span class="form-control-feedback"><i class="fa fa-lastfm-square" aria-hidden="true"></i></span>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
						</div>
					</div>
				<?php echo form_close(); ?>
				<!-- <a href="<?php echo site_url('registration-form');?>" class="text-center">Registration</a> -->
			</div>
		</div>
		<!-- jQuery 3 -->
		<script src="<?php echo base_url("assets/bower_components/jquery/dist/jquery.min.js"); ?>"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url("assets/bower_components/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>
		<!-- iCheck -->
		<script src="<?php echo base_url("assets/plugins/iCheck/icheck.min.js"); ?>"></script>
		<script src="<?php echo base_url('assets/dist/js/parsley/parsley.js'); ?>"></script>
		<script>
		$('#registrationForm').parsley();
		</script>
	</body>
</html>