<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view( 'menu', array('docker_images' => $docker_images) ); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">
            
            <section class="content">
                <div class="docker_list">
                    <div class="inner">Select a Docker image to create a new container</div>
                </div><div class="docker_list">
                    <div class="inner"><form method="get" action="<?php echo site_url( 'docker/search' );?>"><input type="text" name="s" placeholder="Search docker hub for an image" value="" /></form></div>
                </div>
                <div class="docker_list">
                    <div class="inner"><h4>Recommended</h4></div>
                </div><div class="docker_list">
                    <div class="inner more_recommended"><a href="<?php echo site_url( 'docker/recommended' );?>"><?php _e('More recommended');?>...</a></div>
                </div>

                
                <?php
                    foreach( $dockers as $docker ) {
                        $author = explode('/', $docker->temp_repository );
                        $author = $author[0];
                        echo '<div class="docker_list">
                        <div class="dockers">

                            <div class="image_column dark">
                                <img src="'.$docker->temp_icon.'" />
                            </div><div class="docker_details">
                                <div class="author">'.$author.'</div>
                                <h4>'.$docker->temp_name.'</h4>
                                <div class="text_desc">'.$docker->temp_overview.'</div>
                                <a href="'.site_url( 'docker/install/'.$docker->temp_id ).'" class="button dockerinstall">Install</a>
                                <a href="" class="detailslink">Details</a>
                                <a href="'.$docker->temp_support.'" class="supportlink">Support</a>
                            </div>
                        </div>
                        </div>';
                    }
                ?>            
				     
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
