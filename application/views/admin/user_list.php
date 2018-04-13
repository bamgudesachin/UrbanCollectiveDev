<!-- for date-range picker -->
 <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
 <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 <!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script type="text/javascript">
var BaseUrl = "<?php echo site_url();?>";
</script>

 <!-- for date-range picker -->
<script type="text/javascript">
$(function() {

    //var start = moment().subtract(29, 'days');
    var start = moment().subtract(1, 'days');
    var end = moment();
       

    //alert(end);

    var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
    var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

    var startSetDate = moment(url_start_date);
    var endSetDate = moment(url_end_date);

    if(url_start_date && url_end_date){        
        $('#reportrange span').html(moment(url_start_date).format('DD MMM YYYY') + ' - ' + moment(url_end_date).format('DD MMM YYYY'));
    }

    

    function changeData(start, end) {        

        $('#reportrange span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));

        var startDate = start.format('YYYY-MM-DD');
        var endDate = end.format('YYYY-MM-DD');
        getData(startDate,endDate);

    }

   // $("#reportrange").data('daterangepicker').setStartDate(url_start_date);
   // $("#reportrange").data('daterangepicker').setEndDate(url_end_date);

    $('#reportrange').daterangepicker({
        startDate: startSetDate,
        endDate: endSetDate,
        locale: {
          format: 'DD MMM YYYY'
        },

        ranges: {           
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           'All' :[moment('2017-01-01'),moment()]
        }
    }, changeData);



    //changeData(start, end);

    function getData(startDate, endDate) {

        //alert(startDate+'-'+endDate);
         /*sort the coulmn asc and descending*/
     
        var url_records = $(location).attr('href').split("/").splice(6, 1).join("/");
        var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
        
        //var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        //var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");
        var url_start_date = startDate;
        var url_end_date = endDate;

        var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");
        //alert(url_records+"-"+url_order);
        /*var order;
            if (url_sortby == sortby) {      
                if(url_order == 'asc'){
                  order = 'desc';
                }else{
                  order = 'asc';
                }
            }else{
              order = 'asc';
            }
        */
            window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1"; 
    }
    
});
</script>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Show Users</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Users
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                             <?php $success_message = $this->session->flashdata('success_message');
                            if (!empty($success_message) || ($_GET && $_GET['type']== 'delete') || ($_GET && $_GET['type']== 'testUser') || ($_GET && $_GET['type']== 'realUser')) {
                                $success_message = (!empty($success_message))? $this->session->flashdata('success_message') : $message;  
                            ?>
                                <div class="alert alert-success">
                                      <button class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                      <strong><?php echo $success_message;  ?></strong>
                                </div> 
                            <?php }  ?>
                            <?php $failure_message = $this->session->flashdata('failure_message');
                            if (!empty($failure_message) || ($_GET && $_GET['type']=='error')) {
                                    $failure_message = (!empty($failure_message))? $this->session->flashdata('failure_message') : $message;  
                                ?>
                                <div class="alert alert-warning">
                                      <button class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                      <strong><?php echo $failure_message;  ?></strong>
                                </div> 
                            <?php }  ?>
                            <div class="row">
                                <div class="col-md-3">
                                  <label>Date range:</label>
                                   <div id="reportrange"  style="background: #fff;cursor: pointer;padding: 8px 10px;border: 1px solid #ccc;width: 100%;border-radius: 5px;height: 34px;">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span></span> 
                                    </div> 
                                </div>

                                <div class="col-md-3">
                                    <?php $criteria = $this->uri->segment('8');?>
                                  <label>Select criteria:</label>
                                   <select class="form-control " id="criteria" name="criteria" onchange="changeCriteria(this);">
                                    <option value="createdAt" <?php if($criteria == 'createdAt'){ echo 'selected';} ?>>createdAt</option>       
                                    <option value="lastLogin" <?php if($criteria == 'lastLogin'){ echo 'selected';} ?>>Last Login</option>

                                    <option value="sherpaSignedup" <?php if($criteria == 'sherpaSignedup'){ echo 'selected';} ?>>Sherpa signed up</option> 
                                  </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom:7px;margin-top:7px;">
                                <div class="col-md-12">
                                <a href ="<?php echo site_url();?>admin/User/add_user"><button class="btn btn-primary" style="margin-right: 10px;margin-top: 10px;margin-bottom: 10px;">Add User</button></a>&nbsp;&nbsp;&nbsp;
                                 <a href ="<?php echo site_url().'admin/User/download_user_data/'.$this->uri->segment('4').'/'.$this->uri->segment('5').'/'.$this->uri->segment('6')
                                 .'/'.$this->uri->segment('7')
                                 .'/'.$this->uri->segment('8')
                                 .'/'.$this->uri->segment('9')
                                 .'/'.$this->uri->segment('10')
                                 ;?>"><button class="btn btn-primary" style="margin-right: 10px;margin-top: 10px;margin-bottom: 10px;">Download Users</button></a>&nbsp;&nbsp;&nbsp; 

                                <!-- <a href ="<?php //echo site_url();?>admin/User/download_user_data"> 
                                    <button class="btn btn-primary pull-right" onclick='downloadData(<?php //echo  json_encode($users) ?>)' style="margin-right: 10px;">Download Users
                                    </button>
                                <!-- </a> &nbsp;&nbsp;&nbsp;-->
                                <a href ="<?php echo site_url().'admin/User/download_user_sherpa_interest/'.$this->uri->segment('4').'/'.$this->uri->segment('5').'/'.$this->uri->segment('6')
                                 .'/'.$this->uri->segment('7')
                                 .'/'.$this->uri->segment('8')
                                 .'/'.$this->uri->segment('9')
                                 .'/'.$this->uri->segment('10')
                                 ;?>"><button class="btn btn-primary" style="margin-right: 10px;margin-top: 10px;margin-bottom: 10px;">Download Sherpa Interested Users</button></a>&nbsp;&nbsp;&nbsp;
                            </div>
                            </div>
                            <div class="row container-fluid" style="margin-bottom:7px;">
                                <div class="pull-left ">
                                <?php $records_per_page = $this->uri->segment('4');?>
                                  <label for="sel1">record per page:</label>
                                  <select class="form-control " id="records_per_page" name="records_per_page" onchange="recordPerPage(this);">
                                    <option value="5" <?php if($records_per_page == 5){ echo 'selected';} ?>>5</option>       
                                    <option value="10" <?php if($records_per_page == 10){ echo 'selected';} ?>>10</option>
                                    <option value="25" <?php if($records_per_page == 25){ echo 'selected';} ?>>25</option>
                                    <option value="50" <?php if($records_per_page == 50){ echo 'selected';} ?>>50</option>
                                    <option value="100" <?php if($records_per_page == 100){ echo 'selected';} ?>>100</option>
                                  </select>
                                </div>
                                <div class="pull-left ">
                                    <button disabled class="btn btn-primary action" style="margin-top: 24px;
    margin-left: 24px;" data-toggle="modal" data-target="#myModal2">Action on selected users</button> 
    <!-- Modal -->
  <div class="modal fade" id="myModal2"  role="dialog">
    <div class="modal-dialog modal-sm">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Perform action </h4>
        </div>
        <div class="modal-body">
          <!-- <input type="hidden" id="checkData" value="" /> -->
          <button type="button" class="btn btn-success" style="margin-top: 5px;margin-bottom: 5px;" data-toggle="modal" data-target="#myTestUser">Make Test user</button>
          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myDeleteUser" style="margin-top: 5px;margin-bottom: 5px;">Delete user</button>
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myRealUser" style="margin-top: 5px;margin-bottom: 5px;">Make Real user</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  <!-- Test user Modal -->
    <div class="modal fade" id="myTestUser" role="dialog">
    <div class="modal-dialog modal-sm">    
      <!-- Modal content-->
      <div class="modal-content">       
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
               <center><h2 style="color:red !important;">Confirm !</h2></center>
            </div>
            <div class="modal-body">                
              <p>Are you sure to make this user as Test user</p>
            </div>
            <div class="modal-footer">
              <button  type="button" class="btn btn-success" onclick="makeTestUser()">Ok</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
      </div>      
    </div>
  </div>
   <!-- End Test user Modal -->

<!-- Real user Modal -->
    <div class="modal fade" id="myRealUser" role="dialog">
    <div class="modal-dialog modal-sm">    
      <!-- Modal content-->
      <div class="modal-content">       
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
               <center><h2 style="color:red !important;">Confirm !</h2></center>
            </div>
            <div class="modal-body">                
              <p>Are you sure to make this user as Real user</p>
            </div>
            <div class="modal-footer">
              <button  type="button" class="btn btn-success" onclick="makeRealUser()">Ok</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
      </div>      
    </div>
  </div>
   <!-- End Real user Modal -->

   <!-- Delete user Modal -->
    <div class="modal fade" id="myDeleteUser" role="dialog">
    <div class="modal-dialog modal-sm">    
      <!-- Modal content-->
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
               <center><h2 style="color:red !important;">Confirm !</h2></center>
            </div>
            <div class="modal-body">                
              <p>Are you sure to delete users</p>          
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" onclick="deleteUsers()">Ok</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
      </div>      
    </div>
  </div>
   <!-- End Delete user Modal -->

                                </div>
                                <div class="pull-right">
                                <?php $key = $this->uri->segment('5');?>
                                  <input class="form-control" placeholder="Search" style="display:inline-block;margin-top: 24px"  name="searchtext" id="searchtext"   value="<?php echo ($key == "nosearch")?"":$key;?>" />
                                </div>
                              </div>

                            <div class="table-responsive">
                                 <table class="table table-striped table-bordered table-hover" id="example12">
                                    <thead>
                                        <tr>
                                            <th>Check</th>
                                            <th>Sr.No.</th>
                                            <th class="column-width">First Name &nbsp;&nbsp;<a onclick="sort_by('firstName');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th class="column-width">Last Name &nbsp;&nbsp;<a onclick="sort_by('lastName');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th class="column-width">Email &nbsp;&nbsp;<a onclick="sort_by('email');"><i class="glyphicon glyphicon-sort"></i></th>          
                                            <th class="column-width">city  &nbsp;&nbsp;<a onclick="sort_by('city');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 150px !important;">Last Login &nbsp;&nbsp;<a onclick="sort_by('lastLogin');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 150px !important;">Created At &nbsp;&nbsp;<a onclick="sort_by('createdAt');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th>connect to Sherpa</th>
                                        <!-- new changes on 14/9/2017 -->    
                                            <th style="min-width: 150px !important;">Total Search &nbsp;&nbsp;<a onclick="sort_by('no_search');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 150px !important;">Total Tribe &nbsp;&nbsp;<a onclick="sort_by('no_tribe');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 150px !important;">Moving Date &nbsp;&nbsp;<a onclick="sort_by('idealMovingDate');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 150px !important;">Min Price &nbsp;&nbsp;<a onclick="sort_by('minPrice');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 150px !important;">Max Price &nbsp;&nbsp;<a onclick="sort_by('maxPrice');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 100px !important;">Test user</th>
                                            <th style="min-width: 170px !important;">Sherpa Signed Up &nbsp;&nbsp;<a onclick="sort_by('sherpaSignedup');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <!-- end new changes -->
                                            <th style="min-width: 150px !important;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!empty($users)) {
                                            $i=0;
                                            $records_per_page = $this->uri->segment('4');
                                            $currentPage = $this->uri->segment('11');
                                            if($currentPage  == 1 || $currentPage==''){
                                                $srno=1;
                                            }else{
                                                $srno = ($records_per_page * ($currentPage-1))+1;
                                            }
                                            
                                           // echo  $this->uri->segment('4');
                                            foreach($users as $user){ 
                                                
                                                if($user['connectSherpaFlag']==1){
                                                    $sherpaFlag = "Yes";
                                                } else {
                                                    $sherpaFlag = "No";
                                                }

                                                if($user['sherpaSignedup']){
                                                    $sherpaSignedup = date('d M Y', strtotime($user['sherpaSignedup']));
                                                } else {
                                                    $sherpaSignedup = "";
                                                }
                                                
                                                if($user['idealMovingDate']){
                                                    $idealMovingDate = date('d M Y', strtotime($user['idealMovingDate']));
                                                } else {
                                                    $idealMovingDate = "";
                                                }

                                                echo   '<tr class="gradeX"> 
                                                        <td><input style="height: 20px !important;width: 20px !important;margin: 4px 0 0 10px !important;" class="my-checkbox" type="checkbox" name="checkfield" id='.$user['userId'].' onchange="allUsers(this)"/>
                                                        </td>             
                                                        <td>' .$srno++ . '</td> 
                                                        <td>' .$user['firstName'] . '</td>
                                                        <td>' .$user['lastName'] . '</td> 
                                                        <td>'.$user['email'].'</td>                
                                                        <td>'.$user['city'].'</td>     
                                                        <td>'.date('d M Y', strtotime($user['lastLogin'])).'</td>    
                                                        <td>'.date('d M Y', strtotime($user['createdAt'])).'</td>   
                                                        <td>'.$sherpaFlag.'</td>  


                                                        <td>'.$user['no_search'].'</td> 
                                                        <td>'.$user['no_tribe'].'</td> 
                                                        <td>'.$idealMovingDate.'</td> 
                                                        <td>'.$user['minPrice'].'</td> 
                                                        <td>'.$user['maxPrice'].'</td> 
                                                        
                                                        <td>'.$user['testFlag'].'</td> 
                                                               
                                                        <td>'.$sherpaSignedup .'</td>                                
                                                        <td class="actions">';
                                                        echo anchor('admin/user/edit_user/'.$user['userId'],'<i class="fa fa-pencil"></i>','title="Edit" class="btn btn-default btn-circle" ');

                                                        // echo anchor('admin/user/update_test_flag/'.$user['userId'],'<i class="fa fa-user"></i>','title="Test Flag" class="btn btn-default btn-circle" ');
                                                        if($user['testFlag'] == 'TestUser'){
                                                            echo '<a style="margin-left:5px;margin-right:5px;" title="Normal user" class="btn btn-danger btn-circle" id='.$user['userId'].' data-toggle="modal" data-target="#testUser_'.$user['userId'].'"><i class="fa fa-user"></i></a>';
                                                        }else{
                                                            echo '<a style="margin-left:5px;margin-right:5px;"  title="Test user" class="btn btn-success btn-circle"
                                                            id='.$user['userId'].' data-toggle="modal" data-target="#testUser_'.$user['userId'].'"><i class="fa fa-user"></i></a>';
                                                        }

                                                        echo ' <a 
                                                            title="delete"
                                                        type="button" class="btn btn-danger btn-circle" id='.$user['userId'].' data-toggle="modal" data-target="#myModal_'.$user['userId'].'"><i class="fa fa-trash-o"></i></a>';

                                                        
 echo '<div class="modal fade" id="myModal_'.$user['userId'].'"  role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" id='.$user['userId'].'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <center><h2 style="color:red !important;">Confirm !</h2></center>
        </div>
        <div class="modal-body">
            <p>Are you sure to delete this user</p>
        </div>
        <div class="modal-footer">          
          <a class="btn btn-success" title="delete" id='.$user['userId'].' onclick="delete_user(this);">ok</a>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>  ' ;

  echo '<div class="modal fade"  id="testUser_'.$user['userId'].'" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <center><h2 style="color:red !important;">Confirm !</h2></center>
        </div>
        <div class="modal-body">';
        if($user['testFlag'] == 'TestUser'){
            echo '<p>Are you sure to make this user as Normal user</p>';
        }else{
            echo '<p>Are you sure to make this user as Test user</p>';
        }
       echo ' </div>
        <div class="modal-footer">          
          <a class="btn btn-success" title="test user" id='.$user['userId'].' onclick="change_status1(this);">ok</a>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>  ' ;

                                                        
                                                        }
//                                                            echo '<a  title="Cancelled" class="btn btn-default btn-circle" id='.$user['userId'].' onclick="change_status1(this);"><i class="fa fa-power-off"></i></a>';
                                                        echo '</td>
                                                        </tr>';
                                            }else{
                                                echo '<tr>
                                                        <td colspan="16"> No records found </td> 
                                                     </tr>';
                                                }?>


                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->

                           <div class="col-md-12">
                                <div class="pull-right">
                                    <?php echo $links;?>
                                </div>
                            </div>

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

<!-- DataTables JavaScript -->
   <script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "../js/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
        }
    } );
} );

</script>

<script type="text/javascript">


    function changeCriteria(criteria)
    {          
        var criteria = criteria.value;

        var url_records = $(location).attr('href').split("/").splice(6, 1).join("/");
        var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
        
        //var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        //var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");
        var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

        var url_criteria = criteria;

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");

        window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1"; 
    }
    

    function recordPerPage(records_per_page)
    {   var records_per_page = records_per_page.value;

        var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
        
        var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

        var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");
        //alert(records_per_page);
         
         window.location = "<?php echo site_url();?>admin/User/user_list/"+records_per_page+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1"; 
    }

   $("#searchtext").keypress(function(event) {
        var searchtext = document.getElementById("searchtext").value;
       // console.log(searchtext);
        
        var records_per_page = $(location).attr('href').split("/").splice(6, 1).join("/");
        var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

        var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");
        //alert(records_per_page);

        if ( event.which == 13 ) {
            if(searchtext){
            window.location = "<?php echo site_url();?>admin/User/user_list/"+records_per_page+"/"+searchtext +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1";            
            } else {               
               window.location = "<?php echo site_url();?>admin/User/user_list/"+records_per_page+"/nosearch/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1"; 
            }
        }
    });

   /*sort the coulmn asc and descending*/
  function sort_by(sortby){   
    //splice 6 url_records

    var url_records = $(location).attr('href').split("/").splice(6, 1).join("/");
    var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
    var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
    var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

    var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

    var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
    var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");

    var order;
        if (url_sortby == sortby) {      
            if(url_order == 'asc'){
              order = 'desc';
            }else{
              order = 'asc';
            }
        }else{
          order = 'asc';
        }        

        window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ sortby +"/"+order+"/1"; 
      }

   // function downloadData(a){
     
   //   $.ajax({
   //              type:'POST',
   //              url:'<?php echo site_url();?>admin/User/download_user_data',
   //              data: {'data':a},
   //              dataType : 'json', 
   //              success:function(data){
   //                  //$('#resultdiv').html(data);
   //              },
   //              error: function (error) {
   //                  alert('Something goes wrong,Please try again later..');
   //              }
   //          });

   // }

   /* Make user as test or normal user */
    function change_status1(obj){
        var id = $(obj).attr('id');
        var title = $(obj).attr('title');
        
        var url_records = $(location).attr('href').split("/").splice(6, 1).join("/");        
        var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
        var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

        var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");
      
      
        if (id !== null && id !== ''){
          window.location = "<?php echo site_url();?>admin/User/update_test_flag/"+id+"/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1"; 
        } else{
          return false; 
        }
             
    } 

    /* Delete user */

    function delete_user(obj){
        var id = $(obj).attr('id');
        var title = $(obj).attr('title');

        var url_records = $(location).attr('href').split("/").splice(6, 1).join("/");        
        var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
        var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

        var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");
        
      // var r=confirm("Do you want to "+ title +" this user?")
        if (id !== null && id !== ''){
          //window.location = "<?php echo site_url();?>admin/User/delete_user/"+id;
          window.location = "<?php echo site_url();?>admin/User/delete_user/"+id+"/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1";
        } else{
          return false; 
        }
             
    } 

    var some = [];  
    function allUsers(checkboxElem) {              
       var id = checkboxElem.id;
       if (checkboxElem.checked) {
         some.push(id);
       } else {
            var index = some.indexOf(id);
            if (index > -1) {
                some.splice(index, 1);
            }
       }

       if(some.length > 0){
            /*enabled the action button*/
            $('.action').attr('disabled',false);
       }else{
            $('.action').attr('disabled',true);
       }
        //console.log(some);        
        
        $('#testUserData').val(some);
        $('#deleteUserData').val(some);
        
    }

    //console.log(some);

    /* Make the test users */
    function makeTestUser(){        
         var url_records = $(location).attr('href').split("/").splice(6, 1).join("/");
        var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
        var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

        var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");
        
        $.ajax({
                type:'POST',
                url:'<?php echo site_url();?>admin/User/testMultipleUsers',
                data: {'data':some},
                dataType : 'json', 
                success:function(data){
                    //console.log(data);
                    
                    if(data.Status == 200){
                        window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=testUser"; 

                    }else if(data.Status == 400){
                        window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=error";
                    }
                    
                },
                error: function (error) {
                     window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=error";
                }
            });
    }

    /* Make the Real users */
    function makeRealUser(){        
         var url_records = $(location).attr('href').split("/").splice(6, 1).join("/");
        var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
        var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

        var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");
        
        $.ajax({
                type:'POST',
                url:'<?php echo site_url();?>admin/User/realMultipleUsers',
                data: {'data':some},
                dataType : 'json', 
                success:function(data){
                    //console.log(data);
                    
                    if(data.Status == 200){
                        window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=realUser"; 

                    }else if(data.Status == 400){
                        window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=error";
                    }
                    
                },
                error: function (error) {
                     window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=error";
                }
            });
    }

    /* Delete users */
    function deleteUsers(){
        var userId = some;
        //console.log(some);
        var url_records = $(location).attr('href').split("/").splice(6, 1).join("/");
        var url_search = $(location).attr('href').split("/").splice(7, 1).join("/");
        var url_start_date = $(location).attr('href').split("/").splice(8, 1).join("/");
        var url_end_date = $(location).attr('href').split("/").splice(9, 1).join("/");

        var url_criteria = $(location).attr('href').split("/").splice(10, 1).join("/");

        var url_sortby = $(location).attr('href').split("/").splice(11, 1).join("/");
        var url_order = $(location).attr('href').split("/").splice(12, 1).join("/");
        
        $.ajax({
                type:'POST',
                url:'<?php echo site_url();?>admin/User/deleteMultipleUsers',
                data: {'data':some},
                dataType : 'json', 
                success:function(data){
                   // console.log(data);
                    
                    if(data.Status == 200){
                        window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=delete"; 

                    }else if(data.Status == 400){
                        window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=error";
                    }
                    
                },
                error: function (error) {
                     window.location = "<?php echo site_url();?>admin/User/user_list/"+url_records+"/"+url_search +"/"+url_start_date+"/"+url_end_date+"/"+url_criteria+"/"+ url_sortby +"/"+url_order+"/1?type=error";
                }
            });
    }

</script>
