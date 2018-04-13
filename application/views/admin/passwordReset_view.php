<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Urban Collective</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Bootstrap Core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
    <div class="container">
        <!-- Use a container to wrap the slider, the purpose is to enable slider to always fit width of the wrapper while window resize -->
        <div class="container " style="margin-top:30px;">
            <div class="dynamic-width" style="width:30%;margin: 0 auto;">
             <div style="margin: 0 auto;">
                <center>
                    <img class="img-responsive" style="height: 100px !important;" src="../uploads/Logo@3x.png">
                </center>
            </div>

            <div class="login-panel panel panel-default" style="margin-top: 25% !important;">
                <div class="panel-heading" style="height: 53px !important;    padding: 8px 15px !important;">
                    <h1 class="panel-title" style="font-size: 28px !important;text-align: center;font-weight: 100;    margin-top: 2px !important;font-family: 'phenomenabold';">Password Reset</h1>
                </div>
                <div class="panel-body" style="background-color:#FFF;padding: 40px;">
                    <div id="actual_form">
                        <form role="form" id="password-reset">
                            <fieldset>
                               <input type="hidden" name="token" placeholder="User token" class="form-control" id="userToken" value="<?php echo $_GET['token'];?>" >

                               <div class="form-group" style="margin-bottom: 25px !important;">
                                <input class="form-control" style="height:41px !important;font-family: 'phenomenabold';font-size: 17px !important;" placeholder="New password" name="password" id="password" type="password" autofocus value="">
                                <!-- <div style="color:red;font-family: 'phenomenabold';font-size: 19px !important;" class="error_msg" id="password_error">aaa</div> -->
                            </div>
                            <div class="form-group" style="margin-bottom: 25px !important;">
                                <input class="form-control" style="height:41px !important;font-family: 'phenomenabold';font-size: 17px !important;" placeholder="Confirm Password" name="cpassword" id="cpassword" type="password" value="">
                                <!-- <div style="color:red;font-family: 'phenomenabold';font-size: 19px !important;" class="error_msg" id="cpassword_error">bbbb</div> -->
                            </div>
                            <button value="Submit" id="btnSubmit" class="btn btn-lg  btn-block" style="background-color: #45c4e4;
                            border-color: #45c4e4;color: #fff;font-size: 22px;font-weight: 100;border-radius: 23px !important;font-family: 'phenomenabold';">Submit</button>
                        </fieldset>
                    </form>
                </div>
                <div id="message_form" style="font-size: 26px !important;text-align: center;font-weight: 100;    margin-top: 2px !important;font-family: 'phenomenabold';">                                
                </div>
                <div id="wait_form" style="font-size: 26px !important;text-align: center;font-weight: 100;    margin-top: 2px !important;font-family: 'phenomenabold';">
                    <img src="../uploads/loader.gif" width="80px" height="80px">
                </div> 
            </div>
        </div>
    </div>
</div>

</div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $("#actual_form").hide();
            $("#message_form").hide();
            $("#wait_form").show();            

            var url_string = window.location.href;            
            var url = new URL(url_string);
            var token = url.searchParams.get("token");

            var BASE_URL = "http://ec2-52-56-126-65.eu-west-2.compute.amazonaws.com/";            
            $.ajax({
                type:'POST',
                url:BASE_URL+"admin/PasswordReset/check_user_token",
                data:{
                    token:token
                },
                success:function(result){
                 var obj = JSON.parse(result);
                 var code = obj.Code;
                   /* code = 0 -> This link has been expired
                      code = 1 -> This link is valid
                      code = 2 -> This link is invalid
                      */
                      if(code == 0){
                        var Comments = obj.Comments;
                        $("#actual_form").hide();                        
                        $("#message_form").show();
                        $("#message_form").html("<p style='color:red'>"+Comments+"</p>");
                        $("#wait_form").hide();

                    }
                    if(code == 1){
                        var Comments = obj.Comments;
                        $("#actual_form").show();
                        $("#message_form").hide();
                        $("#wait_form").hide();
                    }
                    if(code == 2){
                        var Comments = obj.Comments;
                        $("#actual_form").hide();
                        $("#message_form").show();
                        $("#message_form").html("<p style='color:red'>"+Comments+"</p>");
                        $("#wait_form").hide();
                    }

                   //alert(obj.Comments);
               }
           });


            $("#password-reset").validate({    
             // Specify the validation rules
             rules: {  
                password: {
                    required: true,
                    minlength: 6
                },
                cpassword: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                }
            },

                // Specify the validation error messages
                messages: {                    
                    password: {
                        required: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Please provide a password </div>",
                        minlength: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Your password must be at least 6 characters long </div>"
                    },
                    cpassword: {
                        required: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Please provide a confirm password</div>",
                        minlength: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Your confirm password must be at least 6 characters long</div>",
                        equalTo: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Password and Confirm password must be same</div>"                        
                    }
                },
                
                submitHandler: function(form) {
                    form.submit();
                }
            });


            /* Submit the password reset form*/
            $('#btnSubmit').click(function(){
                //event.preventDefault();

                $("#password-reset").validate({    
                 // Specify the validation rules
                 rules: {  
                    password: {
                        required: true,
                        minlength: 6
                    },
                    cpassword: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    }
                },

                    // Specify the validation error messages
                    messages: {                    
                        password: {
                            required: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Please provide a password </div>",
                            minlength: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Your password must be at least 6 characters long </div>"
                        },
                        cpassword: {
                            required: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Please provide a confirm password</div>",
                            minlength: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Your confirm password must be at least 6 characters long</div>",
                            equalTo: "<div style='color:red;font-family: phenomenabold;font-size: 17px !important;' class='error_msg'>Password and Confirm password must be same</div>"                        
                        }
                    },
                    
                    submitHandler: function(form) {
                        form.submit();
                    }
                });

               
                if($('#password-reset').valid()){
                    $("#actual_form").hide();
                    $("#message_form").hide();
                    $("#wait_form").show();
                }else{
                    $("#actual_form").show();
                    $("#message_form").hide();
                    $("#wait_form").hide();
                }
                 

                var token = $("#userToken").val();
                var password = $("#password").val();
                var cpassword = $("#cpassword").val();
                if(password == cpassword && password !="" && cpassword != ""){
                    $.ajax({
                        type:'POST',
                        url:BASE_URL+"admin/PasswordReset/updatePassword",
                        data:{
                            token:token,
                            password:password,
                            cpassword:cpassword
                        },
                        success:function(result){

                         var obj = JSON.parse(result);
                         var code = obj.Code;
                           /* code = 0 -> Password can not be updated
                              code = 1 -> Password updated successfully
                            */
                              
                            if(code == 1){
                                var Comments = obj.Comments;
                                $("#actual_form").hide();
                                $("#message_form").show();
                                $("#message_form").html("<p style='color:green'>"+Comments+"</p>");
                                $("#wait_form").hide();
                            }
                            if(code == 0){
                                var Comments = obj.Comments;
                                $("#actual_form").hide();
                                $("#message_form").show();
                                $("#message_form").html("<p style='color:red'>"+Comments+"</p>");
                                $("#wait_form").hide();
                            }


                           //alert(obj.Comments);
                       }
                   });
                    return false;
                }
                
                
            });

        });
    </script>

</body>
</html>