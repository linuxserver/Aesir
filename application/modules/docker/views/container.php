<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view( 'menu', array('docker_images' => $docker_images) ); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); 
            $up = (substr( $docker['status'], 0, 2 ) == 'Up') ? true : false;
            $status = ( $up ) ? '<span class="running">Running</span>' : '<span class="stopped">Stopped</span>';
        ?>
        <section id="docker" class="body section1">
            <?php
            //print_r($docker);
            ?>
            <section class="content">
                <h2><?php echo $docker['name'];?><?php echo $status;?></h2>    

                <ul>
                    <?php if( $up ) { ?>
                    <li><a href="<?php echo site_url( 'docker/docker_control/stop/'.$active_menu );?>">Stop</a></li>
                    <?php } else { ?>
                    <li><a href="<?php echo site_url( 'docker/docker_control/start/'.$active_menu );?>">Start</a></li>
                    <?php } ?>
                </ul>       
				     
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
