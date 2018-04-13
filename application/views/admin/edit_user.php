<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/BeatPicker.min.css"/>
<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/demos.css"/>
<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/prism.css"/>
<script src="<?php echo site_url();?>assets/beatpicker/js/BeatPicker.min.js"></script>
<script src="<?php echo site_url();?>assets/beatpicker/js/prism.js"></script>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit User</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit User Information
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                     <?php $success_message = $this->session->flashdata('success_message');
                                    if (!empty($success_message)) {?>
                                        <div class="alert alert-success">
                                              <a class="close" data-dismiss="alert" href="components-popups.html#" aria-hidden="true">×</a>
                                              <strong><?php echo $this->session->flashdata('success_message');  ?></strong>
                                        </div> 
                                    <?php }  ?>
                                    <?php $failure_message = $this->session->flashdata('failure_message');
                                    if (!empty($failure_message)) {?>
                                        <div class="alert alert-warning">
                                              <a class="close" data-dismiss="alert" href="components-popups.html#" aria-hidden="true">×</a>
                                              <strong><?php echo $this->session->flashdata('failure_message');  ?></strong>
                                        </div> 
                                    <?php }  ?>
                                    <form role="form" action="<?php echo site_url();?>admin/user/update_user" method="post" enctype="multipart/form-data">
                                        <input type='hidden' name="userId" value ="<?php echo $user['userId']?>" class="form-control" />
                                        <div class="form-group class-color">
                                            <label>First Name</label>
                                            <input type='text' name="firstName" value ="<?php echo $user['firstName']?>" class="form-control" />
                                            <?php echo form_error('firstName'); ?>
                                        </div>
                                       
                                        <div class="form-group class-color">
                                            <label>Last Name</label>
                                            <div class="form-group">
                                                <input type='text' name="lastName" value ="<?php echo $user['lastName']?>" class="form-control" />
                                               <?php echo form_error('lastName'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>Email</label>
                                            <div class="form-group">
                                                <input type='email' name="email" value ="<?php echo $user['email']?>" class="form-control" />
                                               <?php echo form_error('email'); ?>
                                            </div>
                                        </div>
                                       
                                        <div class="form-group class-color">
                                            <label>Age</label>
                                            <div class="form-group">
                                                <input type='text' name="age" value ="<?php echo $user['age']?>" class="form-control" />
                                               <?php echo form_error('age'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>Gender</label>
                                            <div class="form-group">
                                               <select class="form-control" name="gender">
                                                <?php if($user['gender']=="Male") { ?>
                                                <option value="Male" selected>Male</option>
                                                <option value="Female">Female</option>
                                                <?php } else { ?>
                                                <option value="Male">Male</option>
                                                <option value="Female" selected>Female</option>
                                                <?php } ?>
                                              </select>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>Work Education</label>
                                            <div class="form-group">
                                                <input type='text' name="workEducation" value ="<?php echo $user['workEducation']?>" class="form-control" />
                                               <?php echo form_error('workEducation'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>City</label>
                                            <div class="form-group">
                                                <input type='text' name="city" value ="<?php echo $user['city']?>" class="form-control" />
                                               <?php echo form_error('city'); ?>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-default">Save</button>
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

