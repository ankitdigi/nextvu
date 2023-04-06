<?php  if( $id>0 ){ $param = '/'.$id; }else{ $param = ''; }  ?>
<?php if( $fetch_method=='orderType' ){  ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("Order_Type");?></li>
        </ol>
    </nav>
<?php } ?>

<?php if( $fetch_method=='sub_order_type' ){  ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("sub_order_type");?></li>
        </ol>
    </nav>
<?php } ?>

<?php if( $fetch_method=='plc_selection' ){  ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <?php if($this->session->userdata('order_type') == '1'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/sub_order_type'.$param); ?>"><?php echo $this->lang->line("sub_order_type");?></a></li>
        <?php } ?>
        <?php if($this->session->userdata('order_type') == '2'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/species_selection'.$param); ?>"><?php echo $this->lang->line("Species");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/product_code_selection'.$param); ?>"><?php echo $this->lang->line("product_code");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("practice_lab");?></li>
        </ol>
    </nav>
<?php } ?>

<?php if( $fetch_method=='addEdit' ){  ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <?php if($this->session->userdata('order_type') == '1'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/sub_order_type'.$param); ?>"><?php echo $this->lang->line("sub_order_type");?></a></li>
        <?php } ?>
        <?php if($this->session->userdata('order_type') == '2'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/species_selection'.$param); ?>"><?php echo $this->lang->line("Species");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/product_code_selection'.$param); ?>"><?php echo $this->lang->line("product_code");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/plc_selection'.$param); ?>"><?php echo $this->lang->line("practice_lab");?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("Order_Details");?></li>
        </ol>
    </nav>
<?php } ?>

<?php if( $fetch_method=='allergens' ){  ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <?php if($this->session->userdata('order_type') == '1'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/sub_order_type'.$param); ?>"><?php echo $this->lang->line("sub_order_type");?></a></li>
        <?php } ?>
        <?php if($this->session->userdata('order_type') == '2'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/species_selection'.$param); ?>"><?php echo $this->lang->line("Species");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/product_code_selection'.$param); ?>"><?php echo $this->lang->line("product_code");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/plc_selection'.$param); ?>"><?php echo $this->lang->line("practice_lab");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/addEdit'.$param); ?>"><?php echo $this->lang->line("Order_Details");?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("Allergens");?></li>
        </ol>
    </nav>
<?php } ?>

<?php if( $fetch_method == 'summary'){ ?>
	<?php if($this->session->userdata('order_type') == '2'){ ?>
	<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/species_selection'.$param); ?>"><?php echo $this->lang->line("Species");?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/product_code_selection'.$param); ?>"><?php echo $this->lang->line("product_code");?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/plc_selection'.$param); ?>"><?php echo $this->lang->line("practice_lab");?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/addEdit'.$param); ?>"><?php echo $this->lang->line("Order_Details");?></a></li>
			<?php if( $sub_order_type=='3' ){ ?>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/serum_request'.$param); ?>"><?php echo $this->lang->line("Serum_Request");?></a></li>
			<?php } ?>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("summary");?></li>
		 </ol>
    </nav>
	<?php }else{ ?>
	<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <?php if($this->session->userdata('order_type') == '1'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/sub_order_type'.$param); ?>"><?php echo $this->lang->line("sub_order_type");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/plc_selection'.$param); ?>"><?php echo $this->lang->line("practice_lab");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/addEdit'.$param); ?>"><?php echo $this->lang->line("Order_Details");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/allergens'.$param); ?>"><?php echo $this->lang->line("Allergens");?></a></li>
        <?php if( $sub_order_type=='3' ){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/serum_request'.$param); ?>"><?php echo $this->lang->line("Serum_Request");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("summary");?></li>
        </ol>
    </nav>
	<?php } ?>
<?php } ?>

<?php if( $fetch_method=='species_selection' ){ ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <?php if($this->session->userdata('order_type') == '1'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/sub_order_type'.$param); ?>"><?php echo $this->lang->line("sub_order_type");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("Species");?></li>
        </ol>
    </nav>
<?php } ?>

<?php if( $fetch_method=='product_code_selection' ){  ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <?php if($this->session->userdata('order_type') == '1'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/sub_order_type'.$param); ?>"><?php echo $this->lang->line("sub_order_type");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/species_selection'.$param); ?>"><?php echo $this->lang->line("Species");?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("product_code");?></li>
        </ol>
    </nav>
<?php } ?>

<?php if( $fetch_method=='single_double_selection' ){  ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <?php if($this->session->userdata('order_type') == '1'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/sub_order_type'.$param); ?>"><?php echo $this->lang->line("sub_order_type");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("single_double");?></li>
        </ol>
    </nav>
<?php } ?>

<?php if( $fetch_method=='serum_request' ){ ?>
	<?php if($this->session->userdata('order_type') == '2'){ ?>
	<nav aria-label="breadcrumb">
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/species_selection'.$param); ?>"><?php echo $this->lang->line("Species");?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/product_code_selection'.$param); ?>"><?php echo $this->lang->line("product_code");?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/plc_selection'.$param); ?>"><?php echo $this->lang->line("practice_lab");?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/addEdit'.$param); ?>"><?php echo $this->lang->line("Order_Details");?></a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("Serum_Request");?></li>
		 </ol>
    </nav>
	<?php }else{ ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/orderType'.$param); ?>"><?php echo $this->lang->line("Order_Type");?></a></li>
        <?php if($this->session->userdata('order_type') == '1'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/sub_order_type'.$param); ?>"><?php echo $this->lang->line("sub_order_type");?></a></li>
        <?php } ?>
        <?php if($this->session->userdata('order_type') == '2'){ ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/species_selection'.$param); ?>"><?php echo $this->lang->line("Species");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/product_code_selection'.$param); ?>"><?php echo $this->lang->line("product_code");?></a></li>
        <?php } ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/plc_selection'.$param); ?>"><?php echo $this->lang->line("practice_lab");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/addEdit'.$param); ?>"><?php echo $this->lang->line("Order_Details");?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url($fetch_class.'/allergens'.$param); ?>"><?php echo $this->lang->line("Allergens");?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line("Serum_Request");?></li>
        </ol>
    </nav>
	<?php } ?>
<?php } ?>








