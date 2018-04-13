<!-- Custom Stylesheets-->

<link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/daterangepicker.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/charts/bootstrap_for_worksheet.min.css">

<!-- BaseUrl -->
<script type="text/javascript">
var BaseUrl = "<?php echo site_url();?>";
</script>
<!--<script src="<?php echo site_url();?>assets/js/jquery-2.0.3.min.js"></script>
-->

<!-- Jquery for Chart
<script src="<?php echo site_url(); ?>assets/js/jquery-migrate-1.2.1.js"></script>
<script src="<?php echo site_url(); ?>assets/js/jquery-ui-1.10.3-custom.min.js"></script>
-->

<script type='text/javascript' src='<?php echo site_url(); ?>assets/js/charts/daterangepicker.js'></script>

<script src="<?php echo site_url();?>assets/js/bootstrap-datetimepicker.min.js"></script> <!-- Date picker -->

<script src="<?php echo site_url();?>assets/js/chosen.jquery.min.js"></script><!-- Chosen -->




<!-- for table-->
<script type="text/javascript">
      jQuery(function($) {
        $('.chosen-select').chosen({allow_single_deselect:true}); 
        //resize the chosen on window resize
        $(window)
        .off('resize.chosen')
        .on('resize.chosen', function() {
          $('.chosen-select').each(function() {
             var $this = $(this);
             $this.next().css({'width': '200px'});
          })
        }).trigger('resize.chosen');
      });
</script>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-3 col-md-6">
<!--                    <div class="panel panel-green">-->
<!--                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user-md fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">h1</div>
                                    <div>Active Users</div>
                                </div>
                            </div>
                        </div>-->
                       <!--<a href=""> -->
<!--                            <div class="panel-footer">
                                <span class="pull-left">Users</span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>-->
                       <!--</a>-->
<!--                    </div>-->
                </div>
                <div class="col-lg-3 col-md-6">
<!--                    <div class="panel panel-red">-->
<!--                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user-md fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">h1</div>
                                    <div>Inactive Users</div>
                                </div>
                            </div>
                        </div>-->
                       <!--<a href=""> -->
<!--                            <div class="panel-footer">
                                <span class="pull-left">Users</span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>-->
                         <!--</a>-->
<!--                    </div>-->
                </div>
                
            </div>
            <!-- /.row -->        


        </div>
        <!-- /#page-wrapper -->