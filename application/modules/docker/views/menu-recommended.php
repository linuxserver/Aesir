			<?php $sub_menu = ( isset( $sub_menu ) ) ? $sub_menu : false; ?>
            <ul class="docker_menu">
            	<li><h3>Containers</h3></li>
            	<li class="new_docker<?php if( $active_menu == 'docker' ) echo ' active';?>"><a href="<?php echo site_url( 'docker' );?>"><i class="icon-library2"></i> <?php _e( 'New Container' );?></a></li>
                <li><h3>Categories</h3></li>
                        <li<?php if( empty( $sub_menu ) ) echo ' class="active"';?>><a href="<?php echo site_url( 'docker/recommended' );?>">All</a></li>
                        <?php
                            $catlist = $this->docker_model->cat_list();
                            foreach( $catlist as $cat ) {
                                //$slug = url_title($cat->cat_name, '-', true);
                                $active = ( $sub_menu == $cat->cat_id ) ? ' class="active"' : '';
                                echo '<li'.$active.'><a href="'.site_url( 'docker/recommended/'.$cat->cat_id ).'">'.$cat->cat_name.'</a>';
                                    if( $sub_menu == $cat->cat_id ) { // only show sublist when active, might change this to js later
                                    if( ( $subcatlist = $this->docker_model->cat_list( $cat->cat_id ) ) !== false ) {
                                        echo '<ul>';
                                        foreach( $subcatlist as $subcat ) {
                                           // $subslug = url_title($subcat->cat_name, '-', true);
                                            $subactive = ( $subsub_menu == $subcat->cat_id ) ? ' class="highlight"' : '';
                                            echo '<li'.$subactive.'><a href="'.site_url( 'docker/recommended/'.$cat->cat_id .'/'.$subcat->cat_id).'">'.$subcat->cat_name.'</a></li>';
                                        }
                                         echo '</ul>';
                                    }
                                    }
                                   
                                   echo ' </li>';
                            }
                        ?>
                        
                </li>                
                <li<?php if( $active_menu == 'docker_settings' ) echo ' class="active"';?>><a href="<?php echo site_url( 'docker/settings' );?>"><?php _e( 'Settings' );?></a></li>
            </ul>