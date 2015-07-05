        <section id="addons" class="body section1">
            <?php $this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        	<nav class="secondary">
            	<ul>
                	<?php
					$initialactive = ($section === false) ? ' class="active"' : '';
					echo '<li'.$initialactive.'><a href="/index.php/addons/">All</a></li>';
					foreach ($tags as $tagname => $tag) {
						$active = ($section == $tagname) ? ' class="active"' : '';
						echo '<li'.$active.'><a href="/index.php/addons/category/'.$tagname.'/">'.$tag.'</a></li>';
					}
					?>
                </ul>
            </nav>
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


                        </dl>
                    </fieldset>
                </form>
            
            </section>
            <div class="hr"></div>

        </section>

