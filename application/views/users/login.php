<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="icon" href="<?php echo base_url(FAVICON_ICON); ?>" type="image" sizes="16x16">
		<title><?php echo FAVICON_NAME; ?>Log in</title>
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
				<?php if(!empty($this->session->flashdata('msg'))){ ?>
				<div class="p-3 mb-2 bg-danger text-white"><?php echo $this->session->flashdata('msg'); ?></div>
				<?php } ?>
				<?php echo form_open(site_url('users/auth'), array('name'=>'loginForm', 'id'=>'loginForm')); ?>
					<div class="form-group has-feedback">
						<input type="email" class="form-control" placeholder="Email" name="email" required="" value="<?php echo get_cookie('vetordmgmt_email'); ?>">
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" class="form-control" placeholder="Password" name="password" required="" value="<?php echo get_cookie('vetordmgmt_password'); ?>">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<div class="checkbox icheck">
								<label>
									<input type="checkbox" name="remember" <?php if( get_cookie("vetordmgmt_email") ) { ?> checked <?php } ?> > Remember Me
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat" name="loginSubmit">Sign In</button>
						</div>
					</div>
				<?php echo form_close(); ?>
				<hr>
				<div class="row">
					<div class="col-xs-12" style=" text-align:center;font-size:16px;color:#666;">
						<p>Don't have an account? Click below to Register</p>
						<div class="col-xs-4"></div>
						<div class="col-xs-4"></div>
						<div class="col-xs-4" style="padding-right:0px;padding-left: 20px;">
							<button type="button" class="btn btn-primary btn-block btn-flat" name="loginSubmit"><a href="<?php echo site_url('users/registration-form');?>" title="Register" alt="Register" style="color: #FFF;">Register</a></button>
							
						</div>
					</div>
				</div>
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
		$('#loginForm').parsley();
		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%'
			});
		});
		</script>
	</body>
</html>