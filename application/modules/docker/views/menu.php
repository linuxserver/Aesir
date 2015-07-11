<?php $sub_menu = ( isset( $sub_menu ) ) ? $sub_menu : false; ?>
            <ul>
                <li><h3>Menu</h3></li>
                <li<?php if( $active_menu == 'docker' ) echo ' class="active"';?>><a href="<?php echo site_url( 'docker' );?>"><?php _e( 'My Dockers' );?></a></li>
                <li<?php if( $active_menu == 'docker_list' ) echo ' class="active"';?>><a href="<?php echo site_url( 'docker/docker_list' );?>"><?php _e( 'Docker List' );?></a>
                <?php if( $active_menu == 'docker_list' ) { ?>
                    <ul>
                        <li<?php if( $sub_menu == 'all' ) echo ' class="highlight"';?>><a href="<?php echo site_url( 'docker/docker_list' );?>"><i class="icon-radio-unchecked"></i>All</a></li>
                        <?php
                            $catlist = $this->docker_model->cat_list();
                            foreach( $catlist as $cat ) {
                                //$slug = url_title($cat->cat_name, '-', true);
                                $active = ( $sub_menu == $cat->cat_id ) ? ' class="highlight"' : '';
                                echo '<li'.$active.'><a href="'.site_url( 'docker/docker_list/'.$cat->cat_id ).'"><i class="icon-radio-unchecked"></i>'.$cat->cat_name.'</a>';
                                    if( $sub_menu == $cat->cat_id ) { // only show sublist when active, might change this to js later
                                    if( ( $subcatlist = $this->docker_model->cat_list( $cat->cat_id ) ) !== false ) {
                                        echo '<ul>';
                                        foreach( $subcatlist as $subcat ) {
                                           // $subslug = url_title($subcat->cat_name, '-', true);
                                            $subactive = ( $subsub_menu == $subcat->cat_id ) ? ' class="highlight"' : '';

                                            echo '<li'.$subactive.'><a href="'.site_url( 'docker/docker_list/'.$cat->cat_id .'/'.$subcat->cat_id).'"><i class="icon-radio-unchecked"></i>'.$subcat->cat_name.'</a></li>';

                                        }
                                         echo '</ul>';
                                    }
                                    }
                                   
                                   echo ' </li>';
                            }
                        ?>
                        
                    </ul>
                <?php } ?>
                </li>                
                <li<?php if( $active_menu == 'docker_settings' ) echo ' class="active"';?>><a href="<?php echo site_url( 'docker/settings' );?>"><?php _e( 'Settings' );?></a></li>
            </ul>
