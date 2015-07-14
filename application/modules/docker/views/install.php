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
			print_r($docker);
        ?>
        <section id="docker" class="body section1">
            <?php
            //print_r($docker);
            ?>
            <section class="content">
                <h2><?php echo $docker->temp_name;?> <span class="running"><?php echo $status;?></span></h2>    

            	<div class="options">
                   
                    
                <div class="tabs addons">
                  <ul>
                    <li class="active"><a href="#tabs-1">Basic Options</a></li>
                    <li><a href="#tabs-2">Volume</a></li>
                    <li><a href="#tabs-3">Environment</a></li>
                  </ul>
                    <div class="tab_container">
                      <div class="addontab infotab active" id="tabs-1">
                       <h2>resources</h2>
                      	<label><input type="checkbox" name="enable_limitation" class="toggle_hidden" data-toggle="#install_resources" />Enable resource limitation</label>
                        <div class="resources" id="install_resources">
                            <h3>CPU Priority</h3>
                            <label><input type="radio" value="512" name="cpu" /> Low (512)</label>
                            <label><input type="radio" value="1024" name="cpu" checked="checked" /> Default (1024)</label>
                            <label><input type="radio" value="2048" name="cpu" /> High (2048)</label>
                            <label><input type="radio" value="custom" name="cpu" /> Custom <input type="text" value="" style="width: 120px; display: inline-block;" name="cpu_custom" /></label>
                           
                            
                           <h3>Memory Limit</h3>
                             <input type="text" value="" name="memory" placeholder="eg. 700M or 1G or 8G" />
                         </div>
                         
                         <h2>Webpage</h2>
                         <input type="text" value="" name="webpage" />
                         
                         <h2>Ports</h2>
                         <table style="width: 100%">
                         	<tr><td>Local Port</td><td>Container Port</td><td>Type</td></tr>
                         	<tr><td>44444</td><td>32400</td><td>TCP <a href="">edit</a> <a href="">delete</a></td></tr>
                            
                         </table>
                         <a href="">Add</a>
                      </div>
                      <div class="addontab infotab" id="tabs-2">
                      	
                         <table style="width: 100%">
                         	<tr><td>File/Folder</td><td>Mount path</td><td>Read-Only</td></tr>
                         	<tr><td>/media/video/Movies</td><td>/data/movies</td><td><input type="checkbox" name="read_only[]" value="" /> <a href="">edit</a> <a href="">delete</a></td></tr>
                         	<tr><td>/media/video/TV Shows</td><td>/data/tvshows</td><td><input type="checkbox" name="read_only[]" value="" /> <a href="">edit</a> <a href="">delete</a></td></tr>
                         	<tr><td>/docker/plex/config</td><td>/config</td><td><input type="checkbox" name="read_only[]" value="" /> <a href="">edit</a> <a href="">delete</a></td></tr>
                            
                         </table>
                        	<a href="">Add</a>
                      </div>
                      <div class="addontab infotab" id="tabs-3">
						<h2>Privileges</h2>
                        <label><input type="checkbox" name="privilege" />Use high privilege container</label>
						<h2>Net type</h2>
                            <label><input type="radio" value="0" name="nettype" /> None</label>
                            <label><input type="radio" value="1" name="nettype" checked="checked" /> Bridged (Default)</label>
                            <label><input type="radio" value="2" name="nettype" /> Host)</label>
                     	 <h2>Environment Variables</h2>
                         
                         <table style="width: 100%">
                         	<tr><td>Variable</td><td>Value</td></tr>
                         	<tr><td>name</td><td>plex <a href="">edit</a> <a href="">delete</a></td></tr>
                         	<tr><td>net</td><td>host <a href="">edit</a> <a href="">delete</a></td></tr>
                         	<tr><td>PUID</td><td>1024 <a href="">edit</a> <a href="">delete</a></td></tr>
                         </table>
  						<h2>Execution Command</h2>
                       <input type="text" value="" name="webpage" />
                      </div>
                    </div>
                  </div>
                 
                 <button type="submit">Install</button>
                 </div>
                    
				
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
