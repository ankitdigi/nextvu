<!-- jQuery 3 -->
<script src="<?php echo base_url("assets/bower_components/jquery/dist/jquery.min.js"); ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url("assets/bower_components/jquery-ui/jquery-ui.min.js"); ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url("assets/bower_components/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/input-mask/jquery.inputmask.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/input-mask/jquery.inputmask.date.extensions.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/input-mask/jquery.inputmask.extensions.js"); ?>"></script>
<script src="<?php echo base_url("assets/bower_components/moment/min/moment.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"); ?>"></script>
<!-- datepicker -->
<script src="<?php echo base_url("assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url("assets/dist/js/adminlte.min.js"); ?>"></script>
<script src="<?php echo base_url('assets/dist/js/parsley/parsley.js'); ?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url('assets/bower_components/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bower_components/datatables.net-bs/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/bower_components/datatables.net-bs/js/dataTables.checkboxes.min.js'); ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url('assets/dist/js/bootstrap-select.js'); ?>"></script>
<?php /* <script src="<?php echo base_url("assets/dist/js/library.js"); ?>"></script> */ ?>
<script src="<?php echo base_url('assets/dist/js/icon_fonts.js'); ?>"></script>
<script>
$(document).ready(function(){
	$(".menu_trigger").click(function(){
		$("body").toggleClass("half");
	});

	/* Toggle dropdown menu on click of trigger element */
	$(".user-account").click(function(){
		$(this).parent(".account-area").find("ul").slideToggle();
	});

	/* Hide dropdown menu on click outside */
	$(document).on("click", function(event){
		if(!$(event.target).closest(".account-area").length){
			$(".account-area").find("ul").slideUp();
		}
	});

	$(".has-dropdown a").click(function(event){
		//event.preventDefault();
		if ($(this).parent("li").hasClass("active")) {
			$(this).parent("li").find(".sub-menus").slideUp();
			$(this).parent("li").removeClass("active");
		} else if (!$(this).hasClass("active")) {
			$(this).parent("li").find(".sub-menus").slideDown();
			$(this).parent("li").addClass("active");
		}
	});
});
$(window).on('load', function(){
	if($('.dashboard_left_navigation .active').hasClass('has-dropdown')){
		$('.dashboard_left_navigation .active').find('.sub-menus').show();
	}
});

/* window.onscroll = function(){ scrollFunction(); };
function scrollFunction() {
	if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
		$(".dashboard_left_panel").attr("style","top:0px");
	} else {
		$(".dashboard_left_panel").attr("style","top:58px");
	}
} */
</script>