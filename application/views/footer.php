<?php
$newversion = ( $this->github_updater->has_update() ) ? ' <a c;ass="update_available" href="'.site_url( 'main/update' ).'">Update Available</a>' : '' ;
?>
        </section><!-- END OF #pagecontainer-->
        </section><!-- END OF #content-->
        <footer class="main">
            <div class="wrap">
                <i class="icon-aesir"></i> <span class="appname">Aesir</span> <span class="appversion">v<?php echo substr( $this->config->item('current_commit') , 0, 7 );?><?php echo $newversion;?></span>
            </div>
        </footer>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="/library/js/plugins.js"></script>
        <script src="/library/js/vendor/Chart.min.js"></script>
        <script src="/library/js/vendor/jquery.easypiechart.min.js"></script>
        <script src="/library/js/main.js"></script>
        <?php 
        if( isset( $extra_scripts ) && !empty( $extra_scripts ) ) {
            foreach ( $extra_scripts as $extra ) {
                if( isset( $this->_module ) && !empty( $this->_module ) && file_exists( $this->_ci_model_paths[0].'js/'.$extra.'.js' ) ) {
                    echo '<script src="/application/modules/'.$this->_module.'/js/'.$extra.'.js"></script>';
                } elseif( file_exists( FCPATH.'library/js'.$extra.'.js' ) ) {
                    echo '<script src="/library/js/'.$extra.'.js"></script>';
                }
            }
        }
        ?>
        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-46639161-3');ga('send','pageview');
        </script>
    </body>
</html>
