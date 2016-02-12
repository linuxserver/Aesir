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
                <label>Name <span>(for your reference only)</span></label><input type="text" name="server_name" />
                <label>IP Address <span>(or hostname)</span></label><input type="text" name="server_address" />
        		<label>Password</label><input type="text" name="server_password" />
        		<button type="submit" class="button dockerinstall">Save</button>
        	</form>

        </section>
    </section>
</section>