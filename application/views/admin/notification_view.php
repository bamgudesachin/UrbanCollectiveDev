<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/BeatPicker.min.css"/>
<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/demos.css"/>
<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/prism.css"/>
<script src="<?php echo site_url();?>assets/beatpicker/js/BeatPicker.min.js"></script>
<script src="<?php echo site_url();?>assets/beatpicker/js/prism.js"></script>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Send Sherpa Notification</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Fill Notification Information
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    
                                    <form role="form" action="<?php echo site_url();?>admin/user/send_sherpa_notification" method="post" enctype="multipart/form-data">
                                        <div class="form-group class-color">
                                            <label>Notification Text</label>
                                            <input type='text' name="notificationText" class="form-control" />
                                            <?php echo form_error('notificationText'); ?>
                                        </div>
                                       
                                        
                                        <button type="submit" class="btn btn-default">Send</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->     

