<?php
$var = $this->config->item("unraid_vars");
$page_title = ( isset( $page_title ) && !empty( $page_title ) ) ? $page_title : '';
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>unRAID</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/png" href="/<?=$var['mdColor']?>.png">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link href='http://fonts.googleapis.com/css?family=Kaushan+Script|Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="/library/css/style.css">
        <script src="/library/js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
		<header id="header" class="fullwidth">
            <section id="logo" class="left">
                <div class="h1"><span>un</span>RAID <span class="sub">v<?php echo $var["version"];?></span></div>
            </section><section id="title">
                <h1><?php echo $page_title;?></h1>
            </section>
            
        </header><section id="content"><div class="left"><nav id="main-nav" class="body"><ul>
                <li class="<?php echo menu_active( 'home' );?>"><a href="/"><i class="icon-meter"></i><?php _e( 'Dashboard' );?></a></li>
                <li class="<?php echo menu_active( 'main' );?>"><a href="/"><i class="icon-home"></i><?php _e( 'Main' );?></a></li>
                <!--<li class="<?php echo menu_active( 'shares' );?>"><a href="/index.php/shares/"><i class="icon-tree6"></i><?php _e( 'Shares' );?></a></li>-->
                <li class="<?php echo menu_active( 'users' );?>"><a href="/index.php/users/"><i class="icon-users"></i><?php _e( 'Users' );?></a></li>
               <!-- <li class="<?php echo menu_active( 'settings' );?>"><a href="/index.php/settings/"><i class="icon-equalizer"></i><?php _e( 'Settings' );?></a></li>-->
                <li class="<?php echo menu_active( 'utilities' );?>"><a href="/index.php/utilities/"><i class="icon-knife"></i><?php _e( 'Utilities' );?></a></li>
                <li class="<?php echo menu_active( 'docker' );?>"><a href="/index.php/docker/"><i class="icon-puzzle3"></i><?php _e( 'Addons' );?></a></li>
                <li class="<?php echo menu_active( 'about' );?>"><a href="/index.php/home/about/"><i class="icon-brain"></i><?php _e( 'About' );?></a></li>
            </ul></nav>
        </div><section id="pagecontainer">