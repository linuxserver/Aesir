<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view( 'menu', array('docker_images' => $docker_images) ); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); 

            $up = ( $container_details[0]['State']['Running'] == '1') ? true : false;
            $status = ( $up ) ? '<span class="running">'.__('Running').'</span>' : '<span class="stopped">'.__('Stopped').'</span>';
			
			$id = $container_details[0]['Id'];
			$image_id = $container_details[0]['Image'];
			$name = ltrim($container_details[0]['Name'], '/');
			$volume_mappings = $container_details[0]['Volumes'];
			$port_bindings = $container_details[0]['HostConfig']['PortBindings'];
			$sorted_port_bindings = array();
			$network_mode = $container_details[0]['HostConfig']['NetworkMode'];
			$enviroment_configs = $container_details[0]['Config']['Env'];
			$virtual_storage_usage = 0;
			foreach($image_stats as $image_details){
				if($image_details['Id'] == $image_id){
					$virtual_storage_usage = $image_details['VirtualSize'];
				}
			}
        ?>
        <section id="docker" class="body section1">
		
            <?php
			print_r_fancy($id);
            ?>
            <section class="content">
                <h2><?php echo $name.$status;?></h2>    

				
                <ul class="action_bottoms">
                    <?php if( $up ) { ?>
                    <li><a href="<?php echo site_url( 'docker/container/'.$active_menu.'/stop' );?>">Stop</a></li>
                    <li><a href="<?php echo site_url( 'docker/container/'.$active_menu.'/restart' );?>">Restart</a></li>
                    <?php } else { ?>
                    <li><a href="<?php echo site_url( 'docker/container/'.$active_menu.'/start' );?>">Start</a></li>
                    <?php } ?>
					<li><a href="<?php echo site_url( 'docker/docker_control/edit/'.$active_menu );?>">Edit</a></li>
                </ul>       
				     
					<h2 class="underline"><?php _e( 'Network Details' ); ?></h2>
					<div class="display_row">
						<div class="label"><?php _e( 'Network Mode' ); ?>:</div>
						<div class="value"><?php echo ucwords($network_mode); ?></div>
					</div>
					
					<h2 class="underline"><?php _e( 'Port Binding' ); ?></h3>
					<?php
						
					foreach($port_bindings as $initial => $port_binding){
						$initial_port = explode('/',$initial)[0];
						$sorted_port_bindings[$initial_port] = $port_binding;
					}
					ksort($sorted_port_bindings);
					
					foreach($sorted_port_bindings as $initial_port => $port_binding){
						$target_port = $port_binding[0]['HostPort'];
						echo '<div class="display_row port_binding">';
							echo '<div class="label">'.__( 'Port' ).' '.$initial_port.'</div>';
							echo '<div class="value">'.__( 'Port' ).' '.$target_port.'</div>';
						echo '</div>';
					}
					?>
					
					<h2 class="underline"><?php _e( 'Volume Mapping' ); ?></h2>
					
					<?php
					foreach($volume_mappings as $docker_path => $host_path){
						echo '<div class="display_row path_mapping">';
							echo '<div class="label">'.$docker_path.'</div>';
							echo '<div class="value">'.$host_path.'</div>';
						echo '</div>';
					}
					?>
					
					
					<?php
					$display_store = false;
					$echo_store = '<h2 class="underline">'.__( 'Environment Variables' ).'</h2>';
					foreach($enviroment_configs as $enviroment_config){
						list($variable,$value) = explode('=',$enviroment_config);
						switch(strtolower($variable)){
							case "puid":
							case "pgid":
								$display_store = true;
								$echo_store .= '<div class="display_row">';
									$echo_store .= '<div class="label">'.$variable.':</div>';
									$echo_store .= '<div class="value">'.$value.'</div>';
								$echo_store .= '</div>';
								break;
							default:
								//Nothing
						}
					}	
					echo ($display_store === true) ? $echo_store : '';
					?>
					
					<h2 class="underline"><?php _e( 'System Usage' ); ?></h2>
					 
					 
					<?php

					//print_r_fancy($container_stats);
					echo 'Docker stats gathered in '.$container_stats["gather_time_ms"].'ms<br>';

					echo '<div class="display_row">';
						echo '<div class="label">'.__( 'CPU Usage' ).':</div>';
						echo '<div class="value">'.$container_stats['cpu_stats']['total_usage_percent'].'%</div>';
					echo '</div>';
					echo '<div class="display_row">';
						echo '<div class="label">'.__( 'Current Memory Usage' ).': </div>';
						echo '<div class="value">'.$container_stats['memory_stats']['usage_percent'].'% ('.format_bytes($container_stats['memory_stats']['usage'],false,'','').' / '.format_bytes($container_stats['memory_stats']['total_system_bytes'],false,'','').')</div>';
					echo '</div>';
					echo '<div class="display_row">';
						echo '<div class="label">'.__( 'Maximum Memory Usage' ).': </div>';
						echo '<div class="value">'.$container_stats['memory_stats']['max_usage_percent'].'% ('.format_bytes($container_stats['memory_stats']['max_usage'],false,'','').' / '.format_bytes($container_stats['memory_stats']['total_system_bytes'],false,'','').')</div>';
					echo '</div>';

					?>

					<h2 class="underline"><?php _e( 'Storage Usage' ); ?></h2>
					
					<?php
					echo '<div class="display_row">';
						echo '<div class="label">'.__( 'Virtual Storage Usage' ).': </div>';
						echo '<div class="value">'.format_bytes($virtual_storage_usage,false,'','').'</div>';
					echo '</div>';

					/*if( isset($volume_mappings['/config']) ){
						echo '<div class="display_row">';
							echo '<div class="label">'.__( '/config Storage Usage' ).': </div>';
							echo '<div class="value">'.explode("\t",shell_exec('du -cksh '.$volume_mappings['/config']))[0].'B</div>';
						echo '</div>';
					}*/
					
					?>
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
