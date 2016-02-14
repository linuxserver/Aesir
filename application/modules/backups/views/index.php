<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view('side-menu'); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">

        	<h2>Stats</h2>


            <section class="content">

                
                <?php
                    foreach( $this->servers as $server ) {
                        $server_backups = $this->backups_model->get_backups( $server->server_id );
                        echo '<div class="docker_list">
                        <div class="dockers">

                            <div class="image_column dark">
                                <i class="icon-server"></i>
                            </div><div class="docker_details">
                                <div class="author">'.$server->server_address.'</div>
                                <h4>'.$server->server_name.'</h4>
                                <div class="text_desc">
                                '.__('Backed up folders').': '.$server_backups['folders'].'<br />
                                '.__('Space used').': 2TB
                                </div>
                                <a href="'.site_url('backups/server_backups/'.$server->server_id).'" class="button dockerinstall">Edit</a>
                                <a href="" class="detailslink">Logs</a>
                                <a href="" class="supportlink">Remove</a>
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