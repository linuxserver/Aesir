<?php
$servers = $this->backups_model->get_servers();
?>
<ul>
    <li><a class="add_new" href="<?php echo site_url( 'backups/add_server' );?>"><?php _e( 'Add New' );?> <i class="icon-plus3"></i></a></li>
    <?php
    if( empty( $servers ) ) {
    	echo '<li class="addinfo">'.__('You currently have no servers to backup, add one above').'</li>';
    } else {
	    foreach( $servers as $server ) {
	    	//print_r($server);
	    	echo '
		    	<li>
		    		<a class="" href="'.site_url( 'backups/edit_server/'.$server->server_id ).'">
		    			<i class="icon-server"></i>
		    			<span class="name">'.$server->server_name.'<span>'.$server->server_address.'</span></span>
		    		</a>
		    	</li>';
	    }
	}
    ?>
</ul>