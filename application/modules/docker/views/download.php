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
            $status = __('Downloading');
        ?>
        <section id="docker" class="body section1">
            <?php
            //print_r($docker);
            ?>
            <section class="content">
                <h2><?php echo $docker->temp_name;?> <span class="running"><?php echo $status;?></span></h2>    

                <div class="downloading_image">
                    here

                </div>
				
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
