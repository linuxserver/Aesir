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
                                        <div class="formrow">
                                            <div class="label"><label><?php _e('Name')?> <span><?php _e('Unique name for the container')?></span></label></div><div class="input"><input type="text" name="name" value="<?php if( isset( $docker->temp_name ) && !empty( $docker->temp_name ) ) echo $docker->temp_name;?>" placeholder="<?php _e('Names must be unique');?>" /></div>
                                        </div>


                                        <div class="formrow">

                                            <div class="label"><label><?php _e('Volumes')?> <span><?php _e('Folder/Directory mapping')?></span><span><?php _e('Local file folder: Where the data really exists')?></span><span><?php _e('Container Mount Path: Where the container thinks it exists')?></span></label></div><div class="input" style="min-height: 178px;">

                                                <table style="width: 100%">
                                                    <tr><th class="col45"><label><?php _e('Local File/Folder');?></label></th><th class="col45"><label><?php _e('Container path');?></label></th><th class="col10"></th></tr>
                                                    <?php
                                                    if( isset( $data['Volume'][0]['Mode'] ) && !empty( $data['Volume'][0]['Mode'] ) ) {
                                                        foreach ( $data['Volume'] as $key => $volume ) {
                                                            $checked = ( $volume['Mode'] == 'rw' ) ? '' : ' checked="checked"';
                                                            echo '<tr><td class="col45"><input type="text" name="data['.$key.'][HostDir]" value="'.$volume['HostDir'].'" /></td><td class="col45"><input type="text" name="data['.$key.'][ContainerDir]" value="'.$volume['ContainerDir'].'" /></td><td class="col10"><input class="lockbox" type="checkbox" id="lockbox'.$key.'" name="data['.$key.'][read_only]"'.$checked.' value="1" /><label for="lockbox'.$key.'"></label></a></td><td><a href=""><i class="icon-cancel-circle"></i></a></td></tr>';
                                                        }
                                                    }
                                                    ?>
                                            
                                                </table>
                                                <a href="">Add</a>

                                            </div>
                                        </div>
                                   
                                        
                                    
                                        <div class="formrow">

                                            <div class="label"><label><?php _e('Ports')?> <span><?php _e('Local Port: Which port is used externally')?></span><span><?php _e("Container Port: WWhich port does the container think it's using")?></span></label></div><div style="min-height: 152px;" class="input">

                                                <table style="width: 100%">
                                                    <tr><th class="col30"><label><?php _e('Local Port');?></label></th><th class="col30"><label><?php _e('Container Port');?></label></th><th class="col30"><label><?php _e('Type');?></label></th><th></th></tr>
                                                    <?php
                                                        if( isset( $networking['Publish'][0]['Port'][0]['HostPort'] ) && !empty( $networking['Publish'][0]['Port'][0]['HostPort'] ) ) {
                                                            foreach ( $networking['Publish'][0]['Port'] as $key => $port ) {
                                                                echo '<tr><td class="col30"><input type="text" name="networking['.$key.'][HostPort]" value="'.$port['HostPort'].'" /></td><td class="col30"><input type="text" name="networking['.$key.'][ContainerPort]" value="'.$port['ContainerPort'].'" /></td><td class="col30"><input type="text" name="networking['.$key.'][Protocol]" value="'.$port['Protocol'].'" /></td><td><a href=""><i class="icon-cancel-circle"></i></a></td></tr>';
                                                            }
                                                        }
                                                    ?>
                                            
                                                </table>
                                                <a href="">Add</a>

                                            </div>
                                        </div>
  
                                    </section>
                                </div>
                                <div class="addontab infotab" id="tabs-2">
                                    <section>
                                        <div class="formrow">
                                            <div class="label"><label><?php _e('Webpage')?> <span><?php _e('Link to webui if available')?></span></label></div><div class="input"><input type="text" name="webpage" value="<?php if( isset( $docker->temp_webui ) && !empty( $docker->temp_webui ) ) echo $docker->temp_webui;?>" placeholder="<?php _e('e.g. http://[IP]:[PORT:5050]/');?>" /></div>
                                        </div>

                                         <div class="formrow">
                                            <div class="label"><label><?php _e('Privileges')?> <span><?php _e('Link to webui if available')?></span></label></div><div class="input"><input type="checkbox" name="privileged" id="privileged" value="1"<?php if( $docker->temp_privileged === 1 ) echo 'checked="checked"';?> /><label for="privileged"> <?php _e('Use high privilege container')?></label></div>
                                        </div>

                                   
                                        

                                        <div class="formrow">
                                            <div class="label"><label><?php _e('Net type')?> <span><?php _e('Net type')?></span></label></div><div class="input">
                                                <input type="radio" value="host" name="nettype" id="nettype1"<?php if( isset( $networking['Mode'] ) && $networking['Mode'] == 'host' ) echo ' checked="checked"';?> /><label for="nettype1"> <?php _e('Host')?></label>
                                                <input type="radio" value="bridge" id="nettype2" name="nettype"<?php if( isset( $networking['Mode'] ) && $networking['Mode'] == 'bridge' ) echo ' checked="checked"';?> /><label for="nettype2"> <?php _e('Bridged (Default)')?></label>
                                                <input type="radio" value="none" id="nettype3" name="nettype"<?php if( isset( $networking['Mode'] ) && $networking['Mode'] == 'none' ) echo ' checked="checked"';?> /><label for="nettype3"> <?php _e('None')?></label>
                                            </div>
                                        </div>


                                        <div class="formrow">
                                            <div class="label"><label><?php _e('Execution Command')?> <span><?php _e('Extra command to be run')?></span></label></div><div class="input"><input type="text" value="" name="execute_command" /></div>
                                        </div>
                                       
                                   


                                        <div class="formrow">

                                            <div class="label"><label><?php _e('Environment Variables')?> <span><?php _e('Variables that do things')?></span></label></div><div class="input">

                                                <table style="width: 100%">
                                                    <tr><th class="col45"><label><?php _e('Variable');?></label></th><th class="col45"><label><?php _e('Value');?></label></th><th class="col10"></th></tr>
                                                    <?php
                                                    if( isset( $environment['Variable'] ) && !empty( $environment['Variable'] ) ) {
                                                        foreach ( $environment['Variable'] as $key => $variable) {
                                                           if( !empty($variable['Name']) )  echo '<tr><td class="col45"><input type="text" name="environment['.$key.'][Name]" value="'.$variable['Name'].'" /></td><td class="col45"><input type="text" name="environment['.$key.'][Value]" value="'.$variable['Value'].'" /></td><td class="col10"><a href=""><i class="icon-cancel-circle"></i></a></td></tr>';
                                                        }
                                                    }
                                                    ?>
                                            
                                                </table>
                                                <a href="">Add</a>

                                            </div>
                                        </div>

                                     
                                    </section>
                                </div>
                                <div class="addontab infotab" id="tabs-3">
                                    <section>


                                         <div class="formrow">
                                            <div class="label"><label><?php _e('CPU Priority')?> <span><?php _e('How much priority this container gets')?></span></label></div><div class="input">
                                                <input type="radio" value="512" name="cpu" id="cpu1" /><label for="cpu1"> Low (512)</label>
                                                <input type="radio" value="1024" name="cpu" id="cpu2" checked="checked" /><label for="cpu2"> Default (1024)</label>
                                                <input type="radio" value="2048" name="cpu" id="cpu3" /><label for="cpu3"> High (2048)</label>
                                                <input type="radio" value="custom" name="cpu" id="cpu4" /><label for="cpu4"> Custom </label>
                                                <input type="text" value="" name="cpu_custom" id="cpu_custom" placeholder="<?php _e('Value if Custom is selected');?>" />
                                            </div>
                                        </div>
                                         <div class="formrow">
                                            <div class="label"><label><?php _e('Memory Limit')?> <span><?php _e('Max memory container can use')?></span></label></div><div class="input">
                                                <input type="text" value="" name="memory" placeholder="eg. 700M or 1G or 8G" />
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right">
                            <button class="big_green" type="submit">Install</button>
                        </div>
                    </div>
                            
        				
                </section>
                <div class="hr"></div>
            </form>
        </section>
    </section>
</section>
