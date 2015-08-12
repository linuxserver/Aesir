<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <ul class="docker_menu">
                <li><h3>Containers</h3></li>
                <?php
                    echo '<li class="active"><a href=""><i class="icon-cloud-download dockstatus running"></i><span class="name">'. __( $docker->temp_name ).'<span>'.$docker->temp_repository.'</span></span></a></li>';
                ?>

            </ul>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); 
            $status = __('Building');
            //var_dump($docker);
        ?>
        <section id="docker" class="body section1">
            <form name="frmInstall" method="post" action="<?php echo current_url();?>">
                <input type="hidden" name="repository" value="<?php echo $docker->temp_repository;?>" />
                <input type="hidden" name="bindtime" value="<?php echo $docker->temp_bindtime;?>" />
                <?php
                //print_r($docker);
                ?>
                <section class="content">
                    <h2><?php echo $docker->temp_name;?> <span class="running"><?php echo $status;?></span></h2>    

                	<div class="options">
                       
                        
                        <div class="tabs addons">
                            <ul>
                                <li class="active"><a href="#tabs-1">Basic Options</a></li>
                                <li><a href="#tabs-2">Environment</a></li>
                                <li><a href="#tabs-3">Resources</a></li>
                            </ul>
                            <div class="tab_container">
                                <div class="addontab infotab active" id="tabs-1">
                                    <section>
                                        <h3>Name</h3>
                                        <input type="text" value="<?php if( isset( $docker->temp_name ) && !empty( $docker->temp_name ) ) echo $docker->temp_name;?>" name="name" placeholder="Names must be unique" />
                                    </section>
                                    <section> 
                                        <h3>Volumes</h3>
                                        <table style="width: 100%">
                                            <tr><th>File/Folder</th><th>Mount path</th><th colspan="2">Read-Only</th></tr>
                                            <?php
                                            if( isset( $data['Volume'][0]['Mode'] ) && !empty( $data['Volume'][0]['Mode'] ) ) {
                                                foreach ( $data['Volume'] as $key => $volume ) {
                                                    $checked = ( $volume['Mode'] == 'rw' ) ? '' : ' checked="checked"';
                                                    echo '<tr><td><input type="text" name="data['.$key.'][HostDir]" value="'.$volume['HostDir'].'" /></td><td><input type="text" name="data['.$key.'][ContainerDir]" value="'.$volume['ContainerDir'].'" /></td><td><input type="checkbox" name="data['.$key.'][read_only]"'.$checked.' value="1" /></td><td><a href="">x</a></td></tr>';
                                                }
                                            }
                                            ?>
                                    
                                        </table>
                                        <a href="">Add</a>
                                    </section>
                                    <section>
                                        
                                    

                                        <h3>Ports</h3>
                                        <table style="width: 100%">
                                     	    <tr><th>Local Port</th><th>Container Port</th><th colspan="2">Type</th></tr>
                                     	
                                            <?php
                                                if( isset( $networking['Publish'][0]['Port'][0]['HostPort'] ) && !empty( $networking['Publish'][0]['Port'][0]['HostPort'] ) ) {
                                                    foreach ( $networking['Publish'][0]['Port'] as $key => $port ) {
                                                        echo '<tr><td><input type="text" name="networking['.$key.'][HostPort]" value="'.$port['HostPort'].'" /></td><td><input type="text" name="networking['.$key.'][ContainerPort]" value="'.$port['ContainerPort'].'" /></td><td><input type="text" name="networking['.$key.'][Protocol]" value="'.$port['Protocol'].'" /></td><td><a href="">x</a></td></tr>';
                                                    }
                                                }
                                            ?>
                                       
                                        </table>
                                        <a href="">Add</a>
                                    </section>
                                </div>
                                <div class="addontab infotab" id="tabs-2">
                                    <section>
                                        <h3>Webpage</h3>
                                        <input type="text" value="<?php if( isset( $docker->temp_webui ) && !empty( $docker->temp_webui ) ) echo $docker->temp_webui;?>" name="webpage" />
                                    </section>
                                    <section>
                                        <h3>Privileges</h3>
                                        <label><input type="checkbox" name="privileged" value="1"<?php if( $docker->temp_privileged === 1 ) echo 'checked="checked"';?> />Use high privilege container</label>
                                    </section>
                                    <section>
                                        <h3>Net type</h3>
                                        <label><input type="radio" value="host" name="nettype"<?php if( isset( $networking['Mode'] ) && $networking['Mode'] == 'host' ) echo ' checked="checked"';?> /> Host</label>
                                        <label><input type="radio" value="bridge" name="nettype"<?php if( isset( $networking['Mode'] ) && $networking['Mode'] == 'bridge' ) echo ' checked="checked"';?> /> Bridged (Default)</label>
                                        <label><input type="radio" value="none" name="nettype"<?php if( isset( $networking['Mode'] ) && $networking['Mode'] == 'none' ) echo ' checked="checked"';?> /> None</label>
                                    </section>
                                    <section>
                                        <h3>Environment Variables</h3>
                                     
                                        <table style="width: 100%">
                                            <tr><th>Variable</th><th>Value</th><th></th></tr>
                                            <?php
                                            if( isset( $environment['Variable'] ) && !empty( $environment['Variable'] ) ) {
                                                foreach ( $environment['Variable'] as $key => $variable) {
                                                   if( !empty($variable['Name']) )  echo '<tr><td><input type="text" name="environment['.$key.'][Name]" value="'.$variable['Name'].'" /></td><td><input type="text" name="environment['.$key.'][Value]" value="'.$variable['Value'].'" /></td><td><a href="">x</a></td></tr>';
                                                }
                                            }
                                            ?>
                                        </table>
                                    </section>
                                    <section>
                                        <h3>Execution Command</h3>
                                        <input type="text" value="" name="execute_command" />
                                  	</section>
                                </div>
                                <div class="addontab infotab" id="tabs-3">
                                    <section>
                                        <h3>resources</h3>
                                        <h3>CPU Priority</h3>
                                        <label><input type="radio" value="512" name="cpu" /> Low (512)</label>
                                        <label><input type="radio" value="1024" name="cpu" checked="checked" /> Default (1024)</label>
                                        <label><input type="radio" value="2048" name="cpu" /> High (2048)</label>
                                        <label><input type="radio" value="custom" name="cpu" /> Custom <input type="text" value="" style="width: 120px; display: inline-block;" name="cpu_custom" /></label>
                                    </section>
                                    <section>
                                        <h3>Memory Limit</h3>
                                        <input type="text" value="" name="memory" placeholder="eg. 700M or 1G or 8G" />
                                    </section>
                                </div>
                            </div>
                        </div>
                             
                        <button type="submit">Install</button>
                    </div>
                            
        				
                </section>
                <div class="hr"></div>
            </form>
        </section>
    </section>
</section>
