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
			
			foreach($port_bindings as $initial => $port_binding){
				$initial_port = explode('/',$initial)[0];
				$sorted_port_bindings[$initial_port] = $port_binding[0]['HostPort'];
			}
			ksort($sorted_port_bindings);
					
        ?>
        <section id="docker" class="body section1">
		
            <?php
			//print_r_fancy($id);
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
					
					<?php
					if( is_object($image_detail) ){
						$gui_ip = explode(":",$_SERVER['HTTP_HOST'])[0];
						$webui = $image_detail->temp_webui;
						if( trim($webui) ){
							$webui = str_replace(array("[IP]","[PORT:","]"),array($gui_ip,"",""),$webui);
							$port = parse_url($webui)['port'];
							$webui = str_replace(":".$port,":".$sorted_port_bindings[$port],$webui);
							echo '<li><a href="'.$webui.'" target="_blank">WebUI</a></li>';
						}
					}
					?>
                </ul>   

                    <section>
                        <div class="formrow">
                            <div class="label"><label><?php _e('Network Mode')?> <span><?php _e('Network settings')?></span></label></div><div class="input">
                            	<?php echo ucwords($network_mode); ?>
                            </div>
                        </div>
                        <div class="formrow">
                            <div class="label"><label><?php _e('Port Binding')?> <span><?php _e('What ports to bind')?></span></label></div><div class="input">
                            	<?php 
								foreach($sorted_port_bindings as $initial_port => $target_port){
									echo '<div class="display_row port_binding">';
										echo '<div class="inputlabel">'.__( 'Port' ).' '.$initial_port.'</div>';
										echo '<div class="inputvalue">'.__( 'Port' ).' '.$target_port.'</div>';
									echo '</div>';
								}

                            	?>
                            </div>
                        </div>
                        <div class="formrow">
                            <div class="label"><label><?php _e('Volume Mapping')?></label></div><div class="input">
                            	<?php 
								foreach($volume_mappings as $docker_path => $host_path){
									echo '<div class="display_row path_mapping">';
										echo '<div class="inputlabel">'.$docker_path.'</div>';
										echo '<div class="inputvalue">'.$host_path.'</div>';
									echo '</div>';
								}

                            	?>
                            </div>
                        </div>
                        <div class="formrow">
                            <div class="label"><label><?php _e('Environment Variables')?></label></div><div class="input">
                            	<?php 
								$display_store = false;
								$echo_store = '';
								foreach($enviroment_configs as $enviroment_config){
									list($variable,$value) = explode('=',$enviroment_config);
									switch(strtolower($variable)){
										case "puid":
										case "pgid":
											$display_store = true;
											$echo_store .= '<div class="display_row">';
												$echo_store .= '<div class="inputlabel">'.$variable.':</div>';
												$echo_store .= '<div class="inputvalue">'.$value.'</div>';
											$echo_store .= '</div>';
											break;
										default:
											//Nothing
									}
								}	
								echo ($display_store === true) ? $echo_store : '';

                            	?>
                            </div>
                        </div>
                        <div class="formrow">
                            <div class="label"><label><?php _e('System Usage')?></label></div><div class="input">
                            	<?php 
					echo '<div class="display_row">';
						echo '<div class="inputlabel">'.__( 'CPU Usage' ).':</div>';
						echo '<div class="inputvalue total_usage_percentage">'.$container_stats['cpu_stats']['total_usage_percent'].'%</div>';
					echo '</div>';
					echo '<div class="display_row">';
						echo '<div class="inputlabel">'.__( 'Current Memory Usage' ).': </div>';
						echo '<div class="inputvalue current_memory_usage">'.$container_stats['memory_stats']['usage_percent'].'% ('.$container_stats['memory_stats']['usage_formatted'].' / '.$container_stats['memory_stats']['total_system_formatted'].')</div>';
					echo '</div>';
					echo '<div class="display_row">';
						echo '<div class="inputlabel">'.__( 'Maximum Memory Usage' ).': </div>';
						echo '<div class="inputvalue max_memory_usage">'.$container_stats['memory_stats']['max_usage_percent'].'% ('.$container_stats['memory_stats']['max_usage_formatted'].' / '.$container_stats['memory_stats']['total_system_formatted'].')</div>';
					echo '</div>';

                            	?>
                            </div>
                        </div>

                        <div class="formrow">
                            <div class="label"><label><?php _e('Storage Usage')?></label></div><div class="input">
								<?php
								echo '<div class="display_row">';
									echo '<div class="inputlabel">'.__( 'Virtual Storage Usage' ).': </div>';
									echo '<div class="inputvalue">'.format_bytes($virtual_storage_usage,false,'','').'</div>';
								echo '</div>';
								?>
                            </div>
                        </div>


                    </section>
				     
										
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
