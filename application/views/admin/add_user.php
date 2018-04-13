<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/BeatPicker.min.css"/>
<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/demos.css"/>
<link rel="stylesheet" href="<?php echo site_url();?>assets/beatpicker/css/prism.css"/>
<script src="<?php echo site_url();?>assets/beatpicker/js/BeatPicker.min.js"></script>
<script src="<?php echo site_url();?>assets/beatpicker/js/prism.js"></script>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add User</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Fill User Information
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    
                                    <form role="form" action="<?php echo site_url();?>admin/user/insert_user" method="post" enctype="multipart/form-data">
                                        <div class="form-group class-color">
                                            <label>First Name</label>
                                            <input type='text' name="firstName" class="form-control" />
                                            <?php echo form_error('firstName'); ?>
                                        </div>
                                       
                                        <div class="form-group class-color">
                                            <label>Last Name</label>
                                            <div class="form-group">
                                                <input type='text' name="lastName" class="form-control" />
                                               <?php echo form_error('lastName'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>Email</label>
                                            <div class="form-group">
                                                <input type='email' name="email" class="form-control" />
                                               <?php echo form_error('email'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>Password</label>
                                            <div class="form-group">
                                                <input type='password' name="password" class="form-control" />
                                               <?php echo form_error('password'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>Age</label>
                                            <div class="form-group">
                                                <input type='text' name="age" class="form-control" />
                                               <?php echo form_error('age'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>Gender</label>
                                            <div class="form-group">
                                               <select class="form-control" name="gender">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                              </select>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>Work Education</label>
                                            <div class="form-group">
                                                <input type='text' name="workEducation" class="form-control" />
                                               <?php echo form_error('workEducation'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group class-color">
                                            <label>City</label>
                                            <div class="form-group">
                                                <input type='text' name="city" class="form-control" />
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

