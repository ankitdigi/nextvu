<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mismatch Report
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"> Orders Management</a></li>
        <li class="active">Mismatch Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php if(!empty($this->session->flashdata('success'))){ ?>
      <!--alert msg-->
      <!-- <div class="alert alert-info fade in alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><span><?php //echo $this->session->flashdata('success'); ?></span></div> -->
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Alert!</h4>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
      <?php } ?>
      <?php if(!empty($this->session->flashdata('error'))){ ?>
      <!-- <div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><span><?php //echo $this->session->flashdata('error'); ?></span></div> -->

        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-warning"></i> Alert!</h4>
          <?php echo $this->session->flashdata('error'); ?>
        </div>
      
      <!--alert msg-->
      <?php } ?>

      <div class="row">
        <!-- left column -->
        <div class="col-xs-12">
          <!-- general form elements -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="mismatch_report" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Order Number</th>
                  <!-- <th>File Name</th> -->
                  <th>Mismatch Columns</th>
                </tr>
                </thead>
                
                <tfoot>
                <tr>
                  <th>Order Number</th>
                  <!-- <th>File Name</th> -->
                  <th>Mismatch Columns</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>

        </div>
        <!--/.col (left) -->
        
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  

<?php $this->load->view("footer"); ?>  
  
</div>
<!-- ./wrapper -->

<?php $this->load->view("script"); ?>

<script>
$(document).ready(function(){
          
  var id = <?php echo $id; ?>;
  var dataTable = $('#mismatch_report').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [[ 1, 'desc' ]],
      "ajax": {
          "url": "<?php echo base_url('invoices/getReportTableData'); ?>",
          "type": "POST",
          "data" : {"id": id}
      },
      "columns": [
          { "data": "order_number" },
          { "data": "columns" },
      ]
  });

});
</script>

</body>
</html>
