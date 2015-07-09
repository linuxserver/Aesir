<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <ul>
                <li><h3>Menu</h3></li>
                <li><a href="<?php echo site_url( 'docker' );?>"><?php _e( 'My Dockers' );?></a></li>
                <li class="active"><a href="<?php echo site_url( 'docker/docker_list' );?>"><?php _e( 'Docker List' );?></a>
                	<ul>
                    	<li><a href="">All</a></li>
                    	<li><a href="">Media</a></li>
                    	<li><a href="">Productivity</a></li>
                    </ul>
                </li>
                <li><h3>Docker Options</h3></li>
                
                <li><a href="<?php echo site_url( 'docker/settings' );?>"><?php _e( 'Settings' );?></a></li>
            </ul>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">
            
            <section class="content">
				some content            
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
