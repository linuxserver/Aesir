            <ul class="docker_menu">
                <li><h3>Containers</h3></li>
                <li><a class="add_new" href=""><?php _e( 'Add New' );?> <i class="icon-plus3"></i></a></li>
                <?php
					if( isset( $docker_images['running'] ) && !empty( $docker_images['running'] ) ) {
						foreach( $docker_images['running'] as $container => $running ) {
							$active = ( $active_menu == $container ) ? ' class="active"' : '';
							echo '<li'.$active.'><a href="'.site_url( 'docker/container/'.$container ).'"><i class="icon-database2 dockstatus running"></i><span class="name">'. __( $running['name'] ).'<span>'.$running['repository'].':'.$running['tag'].'</span></span></a></li>';
						}
					}
					if( isset( $docker_images['stopped'] ) && !empty( $docker_images['stopped'] ) ) {
						foreach( $docker_images['stopped'] as $container => $stopped ) {
							$active = ( $active_menu == $container ) ? ' class="active"' : '';
							echo '<li'.$active.'><a href="'.site_url( 'docker/container/'.$container ).'"><i class="icon-database2 dockstatus stopped"></i><span class="name">'. __( $stopped['name'] ).'<span>'.$stopped['repository'].':'.$stopped['tag'].'</span></span></a></li>';
						}
					}
                ?>

            </ul>
