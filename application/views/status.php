        <section id="status" class="<?php echo $statustype;?>">
            <section class="status">
                <div id="status_capacity" class="indicator" data-percent="<?php echo $array_percent_used;?>">
                	<div class="decor-container"><span class="decor"></span></div>
                    <div id="over-free" class="show_status"><?php echo format_bytes($array_free*1024, true, '<span>', '');?><br />free</span></div>
                    <div id="capacity-details"><?php _e( 'Total' );?> : <?php echo format_bytes($array_total*1024, true, '', '');?><br /><?php _e( 'Used' );?> : <?php echo format_bytes(($array_total-$array_free)*1024, true, '', '');?></div>
                    
                </div><div id="status_array" class="indicator <?php echo $array_status['indicator'];?>" data-percent="<?php echo $array_status['percent'];?>">
                	<div class="decor-container"><span class="decor"></span></div>
                    <div class="section_title"><?php _e( 'Array Status' );?></div>
                	<div class="show_status">
                    <?php
                    if( $array_status['status'] == 'ok' ) _e('OK');
                    elseif( $array_status['status'] == 'parity' ) echo $array_status['percent'].'%';
					else echo '<i class="icon-warning"></i>';
					?>
                    </div>
                	<?php echo $array_status['msg'];?>
                    <?php echo $this->unraid->status_button( $array_status['status'], 'array' ); ?>
                </div><div id="status_disks" class="indicator<?php echo ($warn_count > 0) ? ' error' : '';?>" data-percent="100">
                	<div class="decor-container"><span class="decor"></span></div>
                    <div class="section_title"><?php _e( 'Disk Status' );?></div>
                 	<div class="show_status">
                    <?php
					$warn_status = ( $warn_count > 0 ) ? 'error' : 'ok';
					if( $warn_count == 0 ) _e('OK');
					else echo '<i class="icon-warning"></i>';
					?>
                    </div>
                    <div class="indicator_details">
						<div class="title"><?php _e( 'Details' );?></div>
						<?php echo $disk_status_msg;?>
                    </div>
                    <?php echo $this->unraid->status_button( $warn_status, 'disks' ); ?>
                </div>
               
                      
            </section>
        </section>
