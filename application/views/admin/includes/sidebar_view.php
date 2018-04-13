<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">                        
                        <li class="<?php if(!empty($active_dashboard)){echo $active_dashboard;}?>">
                            <a href="<?php echo site_url();?>admin/Dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        
                        <li class="<?php if(!empty($active_package)){echo $active_package;}?>">
                        <?php $startDate = date("Y-m-d"); $endDate = date('Y-m-d',strtotime("-1 days"));?>

                            <a href="<?php echo site_url();?>admin/User/user_list/100/nosearch/<?php echo $endDate.'/'.$startDate;?>/lastLogin/createdAt/desc/1"><i class="fa fa-suitcase"></i> Users<span class="fa arrow"></span></a>

                        </li>
<!--                        <li class="<?php// if(!empty($active_location)){echo $active_location;}?>">
                            <a href="<?php// echo site_url();?>admin/Property/properties_list/1/nosearch/shortlistedId/desc/1"><i class="fa fa-location-arrow fa-lg"></i> Properties<span class="fa arrow"></span></a>
                        </li>-->
                        <li class="<?php if(!empty($active_style)){echo $active_style;}?>">
                            <a href="<?php echo site_url();?>admin/User/sherpa_notification"><i class="fa fa-line-chart"></i>Instant Notifications<span class="fa arrow"></span></a>
                        </li>
<!--                        <li class="<?php if(!empty($active_style)){echo $active_style;}?>">
                            <a href="<?php echo site_url();?>admin/dashboard"><i class="fa fa-line-chart"></i> Blog<span class="fa arrow"></span></a>
                        </li>-->
                     
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>