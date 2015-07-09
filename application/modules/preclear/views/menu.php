            <ul>
                <li><h3>Menu</h3></li>
                <li<?php if( $active_menu == 'preclear' ) echo ' class="active"';?>><a href="<?php echo site_url( 'preclear' );?>"><?php _e( 'Preclear' );?></a></li>
                <li<?php if( $active_menu == 'preclear_settings' ) echo ' class="active"';?>><a href="<?php echo site_url( 'preclear/settings' );?>"><?php _e( 'Settings' );?></a></li>
            </ul>
