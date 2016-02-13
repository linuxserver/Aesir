<?php
$servers = $this->backups_model->get_servers();
?>
<ul>
    <li><a class="add_new" href="<?php echo site_url( 'backups/add_server' );?>"><?php _e( 'Add New' );?> <i class="icon-plus3"></i></a></li>
    <?php
    foreach( $servers as $server ) {
    	//print_r($server);
    	echo '<li><a class="" href="'.site_url( 'backups/edit_server/'.$server->server_id ).'"><i class="icon-server"></i> '.$server->server_name.'</a></li>';
    }
    ?>
</ul>