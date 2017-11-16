<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

    <header id="masthead" class="site-header" role="banner">
        <!-- Navigation -->
        <nav class="navbar navbar-default">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false">
                <span class="sr-only">Toggle Navigation</span>
                <span class=icon-bar></span>
                <span class=icon-bar></span>
                <span class=icon-bar></span>
            </button>
            <div class="navbar-header">
                <a href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/header_logo.png" alt="logo image"></a>
            </div>
            <div class="navbar-collapse collapse" id="navbarResponsive">
                                <?php wp_nav_menu(array(
                                    'container' => false,
                                    'theme_location' => 'header',
                                    'menu_class' => 'nav navbar-nav dropdown navbar-right',
                                    'walker' => new \vista\theme\MW_Walker_Nav_Menu()
                                )
                        );
                    ?>
            </div>
        </nav>
        <!-- Navigation Ends -->
    </header><!-- #masthead -->

    <div class="site-content-contain">
        <div id="content" class="site-content container-fluid">
