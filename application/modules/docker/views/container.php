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
			$name = ltrim($container_details[0]['Name'], '/');
			$volume_mappings = $container_details[0]['Volumes'];
			$port_bindings = $container_details[0]['HostConfig']['PortBindings'];
			$sorted_port_bindings = array();
			$network_mode = $container_details[0]['HostConfig']['NetworkMode'];
			$enviroment_configs = $container_details[0]['Config']['Env'];
			
        ?>
        <section id="docker" class="body section1">
            <?php
            //print_r($docker);
            ?>
            <section class="content">
                <h2><?php echo $name.$status;?></h2>    

                <ul class="action_bottoms">
                    <?php if( $up ) { ?>
                    <li><a href="<?php echo site_url( 'docker/docker_control/stop/'.$active_menu );?>">Stop</a></li>
                    <li><a href="<?php echo site_url( 'docker/docker_control/restart/'.$active_menu );?>">Restart</a></li>
                    <?php } else { ?>
                    <li><a href="<?php echo site_url( 'docker/docker_control/start/'.$active_menu );?>">Start</a></li>
                    <?php } ?>
					<li><a href="<?php echo site_url( 'docker/docker_control/edit/'.$active_menu );?>">Edit</a></li>
                </ul>       
				     
					<h2 class="underline">Network Details</h2>
					<div class="display_row">
						<div class="label">Network Mode:</div>
						<div class="value"><?php echo ucwords($network_mode); ?></div>
					</div>
					
					<h2 class="underline">Port Binding</h3>
					<?php
						
					foreach($port_bindings as $initial => $port_binding){
						$initial_port = explode("/",$initial)[0];
						$sorted_port_bindings[$initial_port] = $port_binding;
					}
					ksort($sorted_port_bindings);
					
					foreach($sorted_port_bindings as $initial_port => $port_binding){
						$target_port = $port_binding[0]['HostPort'];
						echo("<div class=\"display_row port_binding\">");
							echo("<div class=\"label\">Port ".$initial_port."</div>");
							echo("<div class=\"value\">Port ".$target_port."</div>");
						echo("</div>");
					}
					?>
					
					<h2 class="underline">Volume Mapping</h2>
					
					<?php
					foreach($volume_mappings as $docker_path => $host_path){
						echo("<div class=\"display_row path_mapping\">");
							echo("<div class=\"label\">".$docker_path."</div>");
							echo("<div class=\"value\">".$host_path."</div>");
						echo("</div>");
					}
					?>
					
					
					<?php
					$display_store = false;
					$echo_store = "<h2 class=\"underline\">Environment Variables</h2>";
					foreach($enviroment_configs as $enviroment_config){
						list($variable,$value) = explode("=",$enviroment_config);
						switch(strtolower($variable)){
							case "puid":
							case "pgid":
								$display_store = true;
								$echo_store .= "<div class=\"display_row\">";
									$echo_store .= "<div class=\"label\">".$variable.":</div>";
									$echo_store .= "<div class=\"value\">".$value."</div>";
								$echo_store .= "</div>";
								break;
							default:
								//Nothing
						}
					}	
					echo ($display_store === true) ? $echo_store : "";
					?>
					
					<h2 class="underline">System Usage</h2>
					 
					 
					<?php
					
					function bytesToSize1024($bytes, $precision = 2){
						$unit = array('B','KB','MB','GB','TB','PB','EB');
						return @round(
							$bytes / pow(1000, ($i = floor(log($bytes, 1000)))), $precision
						).' '.$unit[$i];
					}
					
					echo("<div class=\"display_row\">");
						echo("<div class=\"label\">CPU Usage: </div>");
						echo("<div class=\"value\">".$container_stats[0]['cpu_stats']['cpu_usage']['total_usage']."</div>");
					echo("</div>");
					echo("<div class=\"display_row\">");
						echo("<div class=\"label\">Current Memory Usage: </div>");
						echo("<div class=\"value\">".bytesToSize1024($container_stats[0]['memory_stats']['usage'])." / ".bytesToSize1024($container_stats[0]['memory_stats']['limit'])."</div>");
					echo("</div>");
					echo("<div class=\"display_row\">");
						echo("<div class=\"label\">Maximum Memory Usage: </div>");
						echo("<div class=\"value\">".bytesToSize1024($container_stats[0]['memory_stats']['max_usage'])." / ".bytesToSize1024($container_stats[0]['memory_stats']['limit'])."</div>");
					echo("</div>");
					?>
					
                     <?php
						/*echo("<pre>");
                        print_r( $container_details );
						echo("</pre>");
						echo("<pre>");
                        print_r( $container_stats );
						echo("</pre>");*/
                     ?>
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
