<?php
$var = $this->config->item("unraid_vars");
?>
    <section id="dashboard">
		<?php $this->load->view( 'status', array( 'statustype' => 'full' ) ); ?>
        <section class="dashmodules">
        	<section class="system_info full">
             	<ul>
                	<li><span class="greentext"><?php _e( 'Server' );?></span> <?php echo $var["NAME"];?> - <?php echo $uptime;?></li>
                	<li><span class="greentext"><?php _e( 'Load' );?></span> <?php echo $load;?></li>
                	<li><span class="greentext"><?php _e( 'Model' );?></span> <?php echo $cpuinfo;?></li>
                    <li><span class="greentext"><?php _e( 'Memory' );?></span> <?php echo $memory;?></li>
                    <li><span class="greentext"><?php _e( 'IP Address' );?></span> <?php echo $var["IPADDR"];?></li>
            	</ul>
           
            </section>
        </section>

        
        <section class="section1 center">

            <section id="datadisks" class="body">
            	<div class="datadisks">
                    <div class="tabs">
                        <ul>
                            <li class="active"><a href="#tabs-1"><?php _e( 'Attention Required' );?> <span class="lightertext">(<?php echo $warn_count;?>)</span></a></li><li>
                            <a href="#tabs-2"><?php _e( 'All Disks' );?> <span class="lightertext">(<?php echo $all_count;?>)</span></a></li><li>
                            <a href="#tabs-3"><?php _e( 'Non-array Disks' );?> (<?php echo $narray_disk_count;?>)</a></li><li>
                            <a href="#tabs-4"><?php _e( 'Options' );?></a></li>
                        </ul>
                        <div class="tab_container">
                            <div class="addontab active" id="tabs-1">
                                <div class="inner">
                                    <!-- items needing attention -->
                                    <?php echo $warning_list; ?>
                                </div>
                            </div>
                            <div class="addontab" id="tabs-2">
                                <div class="inner">
                                    <!-- all disks -->
                                    <?php echo $all_list; ?>
                                    
                                </div>
                            </div>
                            <div class="addontab" id="tabs-3">
                                <div class="inner">
                                    <!-- options -->
                                    <?php echo $narray_list; ?>
                                </div>
                            </div>
                            <div class="addontab" id="tabs-4">
                                <div class="inner">
                                    <!-- options -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </section>
            
        
        </section>
        
        
    </section>

<form class="ajaxupdate" method="post" action="http://<?php echo current(explode(":", $_SERVER['HTTP_HOST']));?>/update.htm">
    <input type="hidden" name="cmdStatus" value="Apply" />
    <button type="submit">Update</button>
</form>