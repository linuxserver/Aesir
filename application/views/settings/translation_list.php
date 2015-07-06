        <section id="translation" class="body section1">
            <?php $this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>

            <section class="bevelbox">
            	<?php $this->load->view( 'settings/translation_menu', array( 'trans_active' => $trans_active ) ); ?>
                <section class="box-content">
                    <ul>
                        <li class="header"><span class="fourcol">Current</span><span class="fourcol">Language</span><span class="fourcol">Complete</span><span class="fourcol">Actions</span></li>

                        <?php
                            foreach( $all_languages as $lname => $lang ) {
                                if( $lname != 'en' ) {
                                    $percent = floor( ($lang['items']/$all_languages['en']['items']) *100 );
                                } else $percent = 100;
                                $current = ( $current_language == $lname ) ? '<a class="radio" href="'.site_url( 'settings/set_language/'.$lname ).'"><i class="icon-radio-checked"></i></a>' : '<a class="radio" href="'.site_url( 'settings/set_language/'.$lname ).'"><i class="icon-radio-unchecked"></i></a>';
                                $options = '<a href="'.site_url( 'settings/edit_translation/'.$lname ).'">Edit</a> | <a href="'.site_url( 'settings/duplicate_translation/'.$lname ).'">Duplicate</a> | <a href="'.site_url( 'settings/delete_translation/'.$lname ).'">Delete</a>';
								if( $lname == 'en' ) $options = '<a href="'.site_url( 'settings/duplicate_translation/'.$lname ).'">Duplicate</a>';

								echo '<li><span class="fourcol">'.$current.'</span><span class="fourcol">'.$lname.'<br /><span class="modified">'.date("jS F Y", $lang['modified']).'</span></span><span class="fourcol">'.$percent.'%</span><span class="fourcol">'.$options.'</span></li>';
                            }
                            //print_r($all_languages);
                        ?>
                    </ul>
                </section>
            </section>

        </section>

