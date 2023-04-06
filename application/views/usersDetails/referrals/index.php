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
            Referral Practices
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Referral Specialist</a></li>
            <li class="active">Referral Practices</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!--alert msg-->
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
        <!--alert msg-->

    <div class="row">
        <!-- left column -->
        <div class="col-xs-12">
            <!-- general form elements -->
            <div class="box">
            <div class="box-header">
                <h3 class="box-title">List</h3>
                <?php if($userData['role']==1){ ?>
                <p class="pull-right">
                    <a href="<?php echo base_url('referrals/add'); ?>" class="btn btn-primary ad-click-event">Add</a>
                </p>
                <?php } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="referrals" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
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
  
</div><!-- ./wrapper -->


<?php $this->load->view("script"); ?>

<script>
$(document).ready(function(){
          
    var dataTable = $('#referrals').DataTable({
        "processing": true,
        "serverSide": true,
        "columnDefs": [
            { orderable: false, targets: 1 }
        ],
        "fixedColumns": true,
        "ajax": {
            "url": "<?php echo base_url('UsersDetails/referrals_getTableData'); ?>",
            "type": "POST",
        },
        "columns": [
            { "data": "name" },
            
            <?php if($userData['role']==1){ ?>
            { "data": "id", render : function ( data, type, row, meta ) {
                return  '<div class="btn-group"><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('referrals/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a>  <a class="btn btn-sm btn-outline-light delReferral" href="javascript:void(0);" data-href="<?php echo base_url('UsersDetails/delete/'); ?>'+data+'" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i></a>  </div>';
            } }
            <?php } ?>
        ]
    });


    $(document).on('click','.delReferral', function(){
        if(confirm('Are you sure you want to delete this Referral?')){
            var href = $(this).data('href');
            var role = 2;
            $('#cover-spin').show();
            $.ajax({
                url: href,
                type: 'GET',
                data : {"role": role},
                success: function (data) {
                    $('#cover-spin').hide();
                    if(data == 'failed'){
                        alert('Something went wrong!');
                    }else{
                        dataTable.ajax.reload();
                    }
                }
            });
        }
    });

});
</script>

</body>
</html>
