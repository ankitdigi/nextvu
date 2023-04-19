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
				<?php if(!empty($success)){ ?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> Success!</h4>
					<?= $this->lang->line("register_success"); ?>
				</div>
				<?php } ?>

				<?php if(!empty($error)){ ?>
				<div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-warning"></i> Error!</h4>
					<?= $this->lang->line("register_error_".$errorNum); ?>
				</div>
				<?php } ?>
				<?php echo form_open(site_url('users/registration-form'), array('name'=>'registrationForm', 'id'=>'registrationForm')); ?>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("first_name"); ?>" name="name" value="<?= !empty($error) ? $data['name'] : ""; ?>" required="">
						<span class="form-control-feedback"><i class="fa fa-user" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("last_name"); ?>" name="last_name" value="<?= !empty($error) ? $data['last_name'] : ""; ?>" required="">
						<span class="form-control-feedback"><i class="fa fa-user" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("clinic"); ?>" name="clinic" value="<?= !empty($error) ? $data['clinic'] : ""; ?>" required="">
						<span class="form-control-feedback"><i class="fa fa-user-md" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<select class="form-control" name="role" required="">
							<option value="">---<?= $this->lang->line("select_role"); ?>---</option>
							<option value="2,0">Veterinarian</option>
							<option value="5,1">Veterinary nurse</option>
							<option value="5,3">Practice manager</option>
						</select>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("street"); ?>" name="street" required="" value="<?= !empty($error) ? $data['street'] : ""; ?>" >
						<span class="form-control-feedback"><i class="fa fa-location-arrow" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("post_code"); ?>" name="post_code" required="" value="<?= !empty($error) ? $data['post_code'] : ""; ?>" >
						<span class="form-control-feedback"><i class="fa fa-lastfm-square" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("city"); ?>" name="city" required="" value="<?= !empty($error) ? $data['city'] : ""; ?>" >
						<span class="form-control-feedback"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<select class="form-control" name="country" required="">
							<option value="">---<?= $this->lang->line("select_country"); ?>---</option>
							<?php
							foreach($staffCountries as $key => $val) {
								?>
								<option value="<?= $val['id']; ?>" <?php if (!empty($error)){ if ($data['country'] == $val['id']){ ?>selected="selected<?php } } ?>" ><?= $val['name']; ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("va_org_number"); ?>" name="vat" required="" value="<?= !empty($error) ? $data['vat'] : ""; ?>" >
						<span class="form-control-feedback"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("phone"); ?>" name="phone_number" required="" value="<?= !empty($error) ? $data['phone_number'] : ""; ?>" >
						<span class="form-control-feedback"><i class="fa fa-mobile" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="<?= $this->lang->line("email"); ?>" name="email" required="" value="<?= !empty($error) ? $data['email'] : ""; ?>" >
						<span class="form-control-feedback"><i class="fa fa-envelope" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" class="form-control" type="password" placeholder="<?= $this->lang->line("password"); ?>" name="password" required="">
						<span class="form-control-feedback"><i class="fa fa-key" aria-hidden="true"></i></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" class="form-control" type="password" placeholder="<?= $this->lang->line("confirm_password"); ?>" name="confirm_password" required="">
						<span class="form-control-feedback"><i class="fa fa-key" aria-hidden="true"></i></span>
					</div>

					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-primary btn-block btn-flat"><?= $this->lang->line("submit"); ?></button>
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
