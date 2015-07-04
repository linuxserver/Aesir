<section id="users" class="body">
	<?php $this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
<section class="section1">	
<ul>
<?php

//print_r( $users );
foreach( $users as $user ) {
	$details = $this->user_model->get_user_by_id( $user['idx'] );
	echo '<li>';
	if( isset( $details->user_email ) && !empty( $details->user_email ) ) {
		echo '<img src="'.$this->gravatar->get_gravatar($details->user_email).'" />';
	} else {
		echo '<img src="'.$this->gravatar->get_gravatar(NULL).'" />';		
	}
	$email = ( isset( $details->user_email ) && !empty( $details->user_email ) ) ? $details->user_email : '';
	echo '	<div>
				<h2>'.$user['name'].'</h2>
				<form method="post" action="">
					<input type="hidden" name="action" value="save_email" />
					<input type="hidden" name="user_id" value="'.$user['idx'].'" />
					<input class="inputleft" type="text" placeholder="Email address" name="user_email" value="'.$email.'" /><button class="button greenbutton biggerbutton inputright" type="submit">Save</button>
				</form>
			</div>';
	echo '</li>';
}
?>
</ul>
</section>
</section>