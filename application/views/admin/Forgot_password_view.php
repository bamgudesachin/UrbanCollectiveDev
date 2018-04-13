<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BigToe</title>

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

      #background{ // background:url(assets/img/home/m20.PNG) no-repeat ;
        //background:url(assets/img/home/m19.JPEG);
                    background-repeat: no-repeat;
                    //padding:35px;
                    //background-origin: content;
                    width: 100%;
                    height: 100%;
                 }   

    </style>


</head>
<body id="background" >
    <!-- NAVBAR
    ================================================== -->
   <!--  <div class="container">

    </div> -->
    <div class="container " style="margin-top:30px;"> 
       <div class="col-md-4 col-md-offset-4" style="">
        <h1 class="text-center" style="font-family:Times New Roman, Times, serif;color:#3399FF;text-shadow: 1px 1px #FF0000;">BigToe</h1>
        
                <?php if($this->session->flashdata('login_failure')!=''){?>
                    <div class="alert alert-warning">
                                <a class="close" data-dismiss="alert" href="components-popups.html#" aria-hidden="true">×</a>
                                <strong>Warning!</strong> <?php echo $this->session->flashdata('login_failure')?>
                    </div>
                <?php  }?>
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Forgot password</h3>
                    </div>
                    <div class="panel-body" style="background-color:#A9D0F5">
                        <form role="form"  method="post" action="<?php echo site_url();?>ForgotPassword/create">
                            <fieldset>
                                <div class="form-group">
                                    <!-- <p>
                                        Enter your email address and your password will be reset and emailed to you.
                                    </p> -->
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Email" name="email" type="text" autofocus required>
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Send new password">
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