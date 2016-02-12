<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view('side-menu'); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">

        	<a href="<?php echo site_url( 'backups/add_server' );?>">Add new</a>

        </section>
    </section>
</section>