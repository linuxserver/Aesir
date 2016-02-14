<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <?php $this->load->view('side-menu'); ?>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="docker" class="body section1">

        	<form method="post" action="">
                <div class="formrow">
                    <div class="label"><label><?php _e('Backup location')?> <span><?php _e('path to backup location')?></span></label></div><div class="input"><input type="text" name="settings_backup_path" /></div>
                </div>
                <div class="formrow">
                    <div class="label"><label><?php _e('Alpha')?> <span><?php _e('How many hours to save')?></span></label></div><div class="input"><input type="text" name="settings_alpha" /></div>
                </div>
                <div class="formrow">
                    <div class="label"><label><?php _e('Beta')?> <span><?php _e('How many hours to save')?></span></label></div><div class="input"><input type="text" name="settings_alpha" /></div>
                </div>
                <div class="formrow">
                    <div class="label"><label><?php _e('Gamma')?> <span><?php _e('How many hours to save')?></span></label></div><div class="input"><input type="text" name="settings_alpha" /></div>
                </div>
                <div class="formrow">
                    <div class="label"><label><?php _e('Delta')?> <span><?php _e('How many hours to save')?></span></label></div><div class="input"><input type="text" name="settings_alpha" /></div>
                </div>
        		<button type="submit" class="button dockerinstall"><?php _e('Save')?></button>
        	</form>

        </section>
    </section>
</section>