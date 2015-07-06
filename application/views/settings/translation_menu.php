                <ul class="nav">
                    <li<?php if( $trans_active == 'overview' ) echo' class="active"';?>><a href="<?php echo site_url( 'settings/translations/' );?>">Translations (<?php echo $lang_count;?>)</a></li>
                    <li<?php if( $trans_active == 'new' ) echo' class="active"';?>><a href="<?php echo site_url( 'settings/new_translation/' );?>">Create New</a></li>
                    <li<?php if( $trans_active == 'more' ) echo' class="active"';?>><a href="<?php echo site_url( 'settings/more_translation/' );?>">Get more</a></li>
                </ul>
