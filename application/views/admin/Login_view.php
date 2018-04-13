<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Urban Collective</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo site_url();?>assets/examples-bootstrap/bootstrap.min.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo site_url();?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo site_url();?>assets/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo site_url();?>assets/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo site_url();?>assets/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
            #background{                     
                width: 100%;
                height: 100%;
                background-color: #45c4e4;
            } 

            @font-face {
                font-family: 'phenomenaextralight';
                src: url('../assets/fonts/phenomena-extralight.woff2') format('woff2'),
                url('../assets/fonts/phenomena-extralight.woff') format('woff');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'phenomenabold';
                src: url('../assets/fonts/phenomena-bold.woff2') format('woff2'),
                url('../assets/fonts/phenomena-bold.woff') format('woff');
                font-weight: normal;
                font-style: normal;
            }

            @media only screen and (max-width: 600px)  {
                .dynamic-width {
                    width: 100% !important;
                }
            }

        </style>


</head>
<body id="background" >
    <!-- NAVBAR
    ================================================== -->
   <!--  <div class="container">

    </div> -->
    <div class="container " style="margin-top:30px;"> 
       <div class="dynamic-width" style="width:30%;margin: 0 auto;">
        <div style="margin: 0 auto;">
            <center>
                <img class="img-responsive" style="height: 100px !important;" src="../uploads/Logo@3x.png">
            </center>
        </div>
        
                <?php if($this->session->flashdata('login_failure')!=''){?>
                    <div class="alert alert-warning" style="margin-top: 68px;
    margin-bottom: -65px !important;">
                                <a class="close" data-dismiss="alert" href="components-popups.html#" aria-hidden="true">×</a>
                                <strong>Warning!</strong>   Your Username Or Password is Incorrect.
                    </div>
                <?php  }?>
                <div class="login-panel panel panel-default" style="margin-top: 25% !important;">
                    <div class="panel-heading" style="height: 53px !important;    padding: 8px 15px !important;">
                        <h1 class="panel-title" style="font-size: 28px !important;text-align: center;font-weight: 100;    margin-top: 2px !important;font-family: 'phenomenabold';">Please Sign In</h1>
                    </div>
                    <div class="panel-body" style="background-color:#FFF;padding: 40px;">
                        <form role="form"  method="post" action="<?php echo site_url();?>Login/validate_user">
                            <fieldset>
                                <div class="form-group" style="margin-bottom: 25px !important;">
                                    <input class="form-control" style="height:41px !important;font-family: 'phenomenabold';font-size: 19px !important;" placeholder="Email" name="email" type="text" autofocus>
                                </div>
                                <div class="form-group" style="margin-bottom: 25px !important;">
                                    <input class="form-control" style="height:41px !important;font-family: 'phenomenabold';font-size: 19px !important;" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!--<div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>-->
                                <!-- Change this to a button or input when using this as a form -->
                                
<!--                                    <a style="float: right;margin-bottom:10px" href="<?php //echo site_url();?>ForgotPassword/forgot_password">
                                        <small>Forgot password?</small>
                                    </a>-->
                               
                                <button type="submit" class="btn btn-lg btn-block" value="Login" style="background-color: #45c4e4;
                            border-color: #45c4e4;color: #fff;font-size: 22px;font-weight: 100;border-radius: 23px !important;font-family: 'phenomenabold';">Submit</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
    </div>

        <!-- FOOTER -->
        <footer>
            <!--<p class="pull-right"><a href="#">Back to top</a></p>
            <p>&copy; Jssor Slider 2009 - 2014. &middot; <a href="#">Privacy</a> &middot; </p>
        -->
        </footer>

    </div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo site_url();?>assets/js1/jquery-1.9.1.min.js"></script>
    <script src="<?php echo site_url();?>assets/examples-bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo site_url();?>assets/examples-bootstrap/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo site_url();?>assets/examples-bootstrap/ie10-viewport-bug-workaround.js"></script>

    
    <!-- jQuery -->
    <script src="<?php echo site_url();?>assets/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo site_url();?>assets/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo site_url();?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo site_url();?>assets/js/sb-admin-2.js"></script>






</body>
</html>