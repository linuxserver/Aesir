        <section id="addons" class="body">
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
            
            </section>
            <div class="hr"></div>

        </section>

