            <ul>
                <li><h3>Menu</h3></li>
                <li<?php if( $active_menu == 'docker' ) echo ' class="active"';?>><a href="<?php echo site_url( 'docker' );?>"><?php _e( 'My Dockers' );?></a></li>
                <li<?php if( $active_menu == 'docker_list' ) echo ' class="active"';?>><a href="<?php echo site_url( 'docker/docker_list' );?>"><?php _e( 'Docker List' );?></a>
                    <ul>
                        <li<?php if( $sub_menu == 'all' ) echo ' class="highlight"';?>><a href=""><i class="icon-radio-unchecked"></i>All</a></li>
                        <li<?php if( $sub_menu == 'media' ) echo ' class="highlight"';?>><a href=""><i class="icon-radio-unchecked"></i>Media</a></li>
                        <li<?php if( $sub_menu == 'productivity' ) echo ' class="highlight"';?>><a href=""><i class="icon-radio-unchecked"></i>Productivity</a></li>
                    </ul>
                </li>                
                <li<?php if( $active_menu == 'docker_settings' ) echo ' class="active"';?>><a href="<?php echo site_url( 'docker/settings' );?>"><?php _e( 'Settings' );?></a></li>
            </ul>
