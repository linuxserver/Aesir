<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view('side-menu'); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">

        	<h2>Add a new server to backup</h2>
        	<p>Enter the details of the server you want to backup</p>

        	<form method="post" action="">
                <div class="formrow">
                    <div class="label"><label><?php _e('Name')?> <span><?php _e('For your reference only')?></span></label></div><div class="input"><input type="text" name="server_name" /></div>
                </div>
                <div class="formrow">
                    <div class="label"><label><?php _e('IP Address')?> <span><?php _e('or hostname')?></span></label></div><div class="input"><input type="text" name="server_address" /></div>
                </div>
                <div class="formrow">
        		    <div class="label"><label><?php _e('Password')?> <span><?php _e('Password is *NOT* saved anywhere, only used for initial setup')?></span></label></div><div class="input"><input type="password" name="server_password" /></div>
                </div>
        		<button type="submit" class="button dockerinstall"><?php _e('Save')?></button>
        	</form>

        </section>
    </section>
</section>