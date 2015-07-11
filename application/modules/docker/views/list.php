<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view( 'menu' ); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">
            
            <section class="content">
				<?php
                    foreach( $dockers as $docker ) {
                        $sall = array('none', 'running', 'none', 'stopped', 'none', 'none', 'none', 'none', 'none');
                        $status = array_rand($sall, 1);
                        $percent = ($sall[$status] == 'none') ? 0 : 100;
                        echo '
                        <div class="indicator dockerbuttonz '.$sall[$status].'" data-percent="'.$percent.'">
                            <div class="status_container"><div class="status_button"><div class="title">'.__('Install').'</div><a href="#problem">'.__('Install this docker').'</a></div></div>
                            <div class="decor-container"><span class="decor"></span></div>
                            <div id="over-free" class="show_status">
                                <img width="100" src="'.$docker->temp_icon.'" />
                            </div>
                            <div id="capacity-details">'.$docker->temp_name.'</div>
                    
                        </div>';
                    }
                ?>            
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
