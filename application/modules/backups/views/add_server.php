<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <ul>
            	<li><a href="">something</a></li>
            </ul>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">

        	<h2>Add a new server to backup</h2>
        	<p>Enter the details of the server you want to backup</p>

        	<form method="post" action="">
                <label>Name</label><input type="text" name="server_name" />
                <label>IP Address</label><input type="text" name="server_address" />
        		<label>Password</label><input type="text" name="server_password" />
        		<button type="submit" class="button dockerinstall">Save</button>
        	</form>

        </section>
    </section>
</section>