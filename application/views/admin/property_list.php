
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Show Properties</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Properties
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
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
                            <div class="row container-fluid" style="margin-bottom:7px">
                                <a href ="<?php echo site_url();?>admin/Property/add_property"><button class="btn btn-primary pull-right">Add Property</button></a>
                            </div>
                            <div class="row container-fluid" style="margin-bottom:7px">
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
                                <div class="pull-right">
                                <?php $key = $this->uri->segment('5');?>
                                  <input class="form-control" placeholder="Search" style="display:inline-block;margin-top: 24px"  name="searchtext" id="searchtext"   value="<?php echo ($key == "nosearch")?"":$key;?>" />
                                </div>
                              </div>

                            <div class="table-responsive">
                                 <table class="table table-striped table-bordered table-hover" id="example12">
                                    <thead>
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th class="column-width">Property Name &nbsp;&nbsp;<a onclick="sort_by('propertyName');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th class="column-width">Property Url &nbsp;&nbsp;<a onclick="sort_by('propertyUrl');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th class="column-width">Commute Time &nbsp;&nbsp;<a onclick="sort_by('commuteTime');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th class="column-width">description &nbsp;&nbsp;<a onclick="sort_by('description');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 200px !important;">Price &nbsp;&nbsp;<a onclick="sort_by('price');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th class="column-width">Available Date &nbsp;&nbsp;<a onclick="sort_by('availableDate');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th class="column-width">Average Property Rating  &nbsp;&nbsp;<a onclick="sort_by('avgPropertyRating');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th style="min-width: 150px !important;">:Created At &nbsp;&nbsp;<a onclick="sort_by('createdAt');"><i class="glyphicon glyphicon-sort"></i></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!empty($properties)) {
                                            $i=0;
                                            $records_per_page = $this->uri->segment('4');
                                            $currentPage = $this->uri->segment('8');
                                            if($currentPage  == 1 || $currentPage==''){
                                                $srno=1;
                                            }else{
                                                $srno = ($records_per_page * ($currentPage-1))+1;
                                            }
                                           // echo  $this->uri->segment('4');
                                            foreach($properties as $property){ 
                                                echo   '<tr class="gradeX"> 
                                                        <td>' .$srno++ . '</td> 
                                                        <td>' .$property['propertyName'] . '</td>
                                                        <td>' .$property['propertyUrl'] . '</td> 
                                                        <td>'.$property['commuteTime'].'</td>
                                                        <td>' .$property['description'] . '</td> 
                                                        <td>' .$property['price'] . '</td> 
                                                        <td>'.$property['availableDate'].'</td>
                                                        <td>'.$property['avgPropertyRating'].'</td>     
                                                        <td>'.$property['createdAt'].'</td>       
                                                        
                                                                                              
                                                        <td class="actions">';
                                                        echo anchor('admin/user/edit_user/'.$property['userId'],'<i class="fa fa-pencil"></i>','title="Edit" class="btn btn-default btn-circle" ');
//                                                            echo '<a  title="Cancelled" class="btn btn-default btn-circle" id='.$property['userId'].' onclick="change_status1(this);"><i class="fa fa-power-off"></i></a>';
                                                        echo '</td>
                                                        </tr>';
                                            }}else{
                                                echo '<tr>
                                                        <td colspan="13"> No records found </td> 
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
    function recordPerPage(records_per_page)
    {   var records_per_page = records_per_page.value;
        //alert(records_per_page);
         window.location = "<?php echo site_url();?>admin/Property/properties_list/" + records_per_page +"/nosearch/userId/asc/1";
    }

   $("#searchtext").keypress(function(event) {
        var searchtext = document.getElementById("searchtext").value;
        console.log(searchtext);
        if ( event.which == 13 ) {
            if(searchtext){
            window.location = "<?php echo site_url();?>admin/Property/properties_list/5/" + searchtext +"/userId/asc/1";            
            } else {
               window.location = "<?php echo site_url();?>admin/Property/properties_list/5/nosearch/userId/asc/1"; 
            }
        }
    });
   /*sort the coulmn asc and descending*/
  function sort_by(sortby){   
    var url_records = $(location).attr('href').split("/").splice(7, 1).join("/");
    var url_search = $(location).attr('href').split("/").splice(8, 1).join("/");
    var url_sortby = $(location).attr('href').split("/").splice(9, 1).join("/");
    var url_order = $(location).attr('href').split("/").splice(10, 1).join("/");
    //alert(url_records+"-"+url_order);
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
        window.location = "<?php echo site_url();?>admin/Property/properties_list/"+url_records+"/"+url_search +"/" + sortby +"/"+order+"/1"; 
      }



    function change_status1(obj){
        var id = $(obj).attr('id');
        var title = $(obj).attr('title');
        
       var r=confirm("Do you want to "+ title +" this Class?")
        if (r==true){
          window.location = "<?php echo site_url();?>admin/user/change_status/"+id;
        } else{
          return false; 
        }
             
        } 
</script>
