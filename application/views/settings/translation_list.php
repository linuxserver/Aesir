        <section id="translation" class="body section1">
            <?php $this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>

            <section class="bevelbox">
                <ul class="nav">
                    <li class="active"><a href="">Translations (<?php echo $lang_count;?>)</a></li>
                    <li><a href="">Create New</a></li>
                    <li><a href="">Get more</a></li>
                </ul>
                <section class="box-content">
                    <ul>
                        <li class="header"><span class="fourcol">Current</span><span class="fourcol">Language</span><span class="fourcol">Complete</span><span class="fourcol">Actions</span></li>

                        <?php
                            foreach( $all_languages as $lname => $lang ) {
                                if( $lname != 'en' ) {
                                    $percent = floor( ($lang['items']/$all_languages['en']['items']) *100 );
                                } else $percent = 100;
                                $current = ( $current_language == $lname ) ? '<a class="radio" href="'.site_url( 'settings/set_language/'.$lname ).'"><i class="icon-radio-checked"></i></a>' : '<a class="radio" href="'.site_url( 'settings/set_language/'.$lname ).'"><i class="icon-radio-unchecked"></i></a>';
                                echo '<li><span class="fourcol">'.$current.'</span><span class="fourcol">'.$lname.'<br />'.date("jS F Y", $lang['modified']).'</span><span class="fourcol">'.$percent.'%</span><span class="fourcol">Edit | Duplicate | Delete</span></li>';
                            }
                            //print_r($all_languages);
                        ?>
                    </ul>
                </section>
            </section>

        </section>

