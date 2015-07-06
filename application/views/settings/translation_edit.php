        <section id="translation" class="body section1">
            <?php $this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
			<form action="" method="post" />
            <input type="hidden" name="__action" value="save_translation" />
            <section class="bevelbox">
                <?php $this->load->view( 'settings/translation_menu', array( 'trans_active' => $trans_active ) ); ?>
                <section class="box-content">
                    <ul>
                    	<?php
						$folder_name = ( $this->input->post('__folder') ) ? '<input type="hidden" name="__folder" value="'.$this->input->post('__folder').'" />'.$this->input->post('__folder') : '<input type="text" style="display: inline-block;" name="__folder" placeholder="Examples: en / de / fr" value="" />';
						?>
                        <li><span>ISO code</span>: <?php echo $folder_name;?><button class="savebutton" type="submit">save</button></li>
                        <li class="header"><span class="fourcol">Current</span><span class="fourcol">Language</span><span class="fourcol">Complete</span><span class="fourcol">Actions</span></li>
						<?php
							foreach( $english as $key => $eng ) {
								$box = ( strlen( $eng ) > 64 ) ? '<textarea name="'.$key.'">'.$this->input->post($key).'</textarea>' : '<input type="text" name="'.$key.'" value="'.$this->input->post($key).'" />';
								echo '<li><span class="twocol notransform tleft">'.htmlspecialchars($eng).'</span><span class="twocol">'.$box.'</span></li>';
							}
						?>
                    </ul>
                    <button class="savebutton" type="submit">save</button>
                </section>
            </section>
            </form>

        </section>

