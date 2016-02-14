<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <ul class="docker_menu">
                <li><h3>Containers</h3></li>
                <?php
                    echo '<li class="active"><a href=""><i class="icon-cloud-download dockstatus running"></i><span class="name">'. __( $name ).'<span>'.$repo.'</span></span></a></li>';
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
                <h2><?php echo $name;?> <span class="running"><?php echo $status;?></span></h2> 
                
                <div id="downloading_image" class="downloading_image">
                    <div class="ball"></div>
                    <div class="ball1"></div>


                    <div class="download_contain">
                    <div id="download_image" class="indicator" data-percent="0" data-img="<?php echo $shaimage;?>">
                	<div class="decor-container"><span class="decor"></span></div>
                    <div class="section_title"><div id="download_status">Downloading</div></div>
                    <div class="show_status"><span id="download_percent">0</span>%</div>
                    </div>


                
                </div>
                </div>
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
