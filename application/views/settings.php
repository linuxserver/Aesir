<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
            <ul>
                <li><h3>System Settings</h3></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">Translations</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">Date and Time</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">Disk Settings</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">Docker</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">Identification</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">Network Settings</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">Global Share Settings</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">UPS Settings</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">VM Manager</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">Network Services</a></li>
                <li><h3>Network Settings</h3></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">AFP</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">NFS</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">SMB</a></li>
                <li><a href="<?php echo site_url( 'settings/translations' );?>">FTP Server</a></li>
                <li><h3>User Preferences</h3></li>
            </ul>
        </nav>

    </section>
    <section class="rightcontent">
        <?php $this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="addons" class="body section1">
            
            <section class="content">
                <form>
                    <fieldset>
                        <legend>Some legend</legend>
                        <ul>
                            <li><label>Some input</label><input type="text" name="name" placeholder="Some input" /></li>
                            <li><label>Email address</label><input class="inputleft" type="text" placeholder="Email address" name="user_email" value="" /><button class="inputright" type="submit">Save</button></li>
                            <li><label>Some input</label><input type="text" name="name" placeholder="Some input" /></li>
                            <li><label>Some input</label><input type="text" name="name" placeholder="Some input" /></li>
                            <li><label>Some input</label><input type="text" name="name" placeholder="Some input" /></li>
                            <li><label>Some input</label><input type="text" name="name" placeholder="Some input" /></li>


                        </ul>
                    </fieldset>
                </form>
            
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>
