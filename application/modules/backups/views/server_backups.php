<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view('side-menu'); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">

        	<p>Choose what to backup on the remote server</p>

        	<form method="post" action="">
                <div id="backups">
                    <?php echo $backup_list;?>
                </div>

                <button id="add_dir" class="button add_dir dockerinstall">Add directory</button>
                
        		<button type="submit" class="button dockerinstall"><?php _e('Save')?></button>
        	</form>

        </section>
    </section>
</section>