<?php 

namespace vista\theme\actions
{
    add_action( 'wp_enqueue_scripts', 'vista\theme\actions\wp_enqueue_scripts' );
    add_action( 'after_setup_theme', 'vista\theme\actions\after_setup_theme' );

    function wp_enqueue_scripts()
    {
        wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css?family=Nunito+Sans:300,400|Scope+One');
        wp_enqueue_style( 'main-style', get_template_directory_uri() . '/assets/dist/css/main.css');
        wp_enqueue_script(
            'main',
            get_template_directory_uri() . '/assets/dist/js/scripts.js',
            array('jquery'),
            false,
            true
        );

    }

    function after_setup_theme()
    {
        add_theme_support( 'title-tag' );
        
        add_theme_support( 'post-thumbnails' );

        /**
        * Image sizes
        */
        add_image_size( 'thumb-sm', 640, 427, true );
        add_image_size( 'hero', 1910, 650, true );

        //This enables the breadcrumbs automatically 
        //Users won/t be able to disable from wp admin
        add_theme_support( 'yoast-seo-breadcrumbs' );

        register_nav_menus(
            array(
            'header' => ( 'Header Menu' ),
            'footer' => ( 'Footer Menu' ),
            'social' => ( 'Social menu' ),
            )
        );
    }

}


namespace vista\theme\filters
{
    add_filter( 'excerpt_length', 'vista\theme\filters\wpdocs_custom_excerpt_length', 999 );
    add_filter( 'excerpt_more', 'vista\theme\filters\wpdocs_excerpt_more' );




    function wpdocs_custom_excerpt_length( $length ) 
    {
        return 20;
    }

    function wpdocs_excerpt_more( $more ) 
    {
        return '...';
    }
}

namespace vista\theme\shortcodes
{
    add_shortcode( 'sitemap', 'vista\theme\shortcodes\generate_sitemap' );

    function generate_sitemap()
    {
        $prm_menu = array(
            'menu'              => 'header-menu',
            'theme_location'    => 'header',
            'depth'             => 3,
            'container_class' => 'sitemap-items',
        );
        return wp_nav_menu( $prm_menu );
    }
}

namespace vista\theme
{
    // Relative date & time
    function wp_relative_date($post_date)
    {
        return human_time_diff( $post_date , current_time( 'timestamp' ) ) . ' ago';
    }
    

    class MW_Walker_Nav_Menu extends \Walker_Nav_Menu
    {
            function start_lvl(&$output, $depth = 0, $args = array())
            {
                $indent = str_repeat("\t", $depth);
                global $form, $mega, $dep, $login;

                $output .= "\n$indent";
                if($depth == 0 and $form == 1)
                {
                $output .= "<div class=\"dropdown-menu\">\n"; //[ startul: $depth, mega: $mega ]
                } elseif($depth == 0 and $mega == 1)
                {
                $output .= "<div class=\"dropdown-menu accordion\">\n"; //[ startul: $depth, mega: $mega ]
                } elseif($depth == 1 and $mega == 1) //startul: 1, mega: yes
                {
                $output .= "<ul>\n";    //[ startul: $depth, mega: $mega ]
                } else
                {
                $output .= "<div class=\"dropdown-menu\"><ul>\n"; //[ startul: $depth, mega: $mega ]
                }
            }

            function end_lvl(&$output, $depth = 0, $args = array())
            {
                $indent = str_repeat("\t", $depth);
                global $form, $mega, $login;
                $output .= "\n$indent" ;
                if($depth == 0 and $form == 1)
                {
                $output .= "</div>\n"; //[ startul: $depth, mega: $mega ]

                }
                elseif($depth == 0 and $mega == 1)
                {
                $output .= "</div>\n"; //[ startul: $depth, mega: $mega ]

                }
                elseif($depth == 1 and $mega == 1)
                {
                $output .= "</ul>\n"; //[ startul: $depth, mega: $mega ]
                }
                else
                {
                $output .= "</ul></div>\n";
                }
            }

            function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
            {
                global $wp_query;
                $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

                $class_names = $value = '';

                $classes = empty( $item->classes ) ? array() : (array) $item->classes;
                $classes[] = 'menu-item-' . $item->ID;
                global $form, $mega, $dep, $login;

                if(in_array('form', $classes) and $depth == 0){
                $form = 1;
                }
                elseif(in_array('mega-dropdown', $classes) and $depth == 0){
                $mega = 1;
                }
                elseif($depth == 0)
                {

                $mega = 0;
                }
                if($mega == 1 and $depth == 1) {
                $classes[] = 'col-lg-3 col-md-3 col-sm-3 col';}

                $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
                $class_names = ' class="' . esc_attr( $class_names ) . '"';

                $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
                $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';


                $output .= $indent; //"inner[ startul: $depth, mega: $mega ]" ;
                if($mega == 1 and $depth == 1)
                {
                $output .= '<div' . $id . $value . $class_names .'>';
                }
                elseif(in_array('login', $classes)){
                global $login;
                $login =1;
                $output .= '';
                }
                else
                {
                $output .= '<li' . $id . $value . $class_names .'>';
                }
                $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
                $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
                $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
                $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
                if($item->attr_title == 'dropdown-toggle'){
                $attributes .= ' data-toggle="dropdown" ';
                $attributes .= ' class="dropdown-toggle disabled" ';
                }
                $item_output = $args->before;
                if(in_array('head', $classes)) $item_output .= '<strong class="title">';
                $item_output .= '<a'. $attributes .'>';
                $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
                $item_output .= '</a>';

                if(in_array('head', $classes)) $item_output .= '</strong>';

                $item_output .= $args->after;


                if(in_array('login', $classes)){
                global $login;
                $login=1;
                $output .= get_login_form();
                }else{
                $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

                }
            }

            function end_el(&$output, $object, $depth = 0, $args = array())
            {
                global $mega, $form, $login;
                if($mega == 1 and $depth == 1)
                {
                //$output .= "inner close[ startul: $depth, mega: $mega ]" ;
                $output .= "</div>\n";
                }
                elseif($depth == 1 and $login == 1){
                $output .= '';
                $login=0;
                }
                else
                {

                $output .= "</li>\n";
                }
            }
    }

    function wp_bootstrap_pagination( $args = array() ) 
    {
    
        $defaults = array(
            'range'           => 4,
            'custom_query'    => FALSE,
            'previous_string' => __( 'Previous', 'text-domain' ),
            'next_string'     => __( 'Next', 'text-domain' ),
            'before_output'   => '<div class="post-nav"><ul class="pager">',
            'after_output'    => '</ul></div>'
        );
        
        $args = wp_parse_args( 
            $args, 
            apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
        );
        
        $args['range'] = (int) $args['range'] - 1;
        if ( !$args['custom_query'] )
            $args['custom_query'] = @$GLOBALS['wp_query'];
        $count = (int) $args['custom_query']->max_num_pages;
        $page  = intval( get_query_var( 'paged' ) );
        $ceil  = ceil( $args['range'] / 2 );
        
        if ( $count <= 1 )
            return FALSE;
        
        if ( !$page )
            $page = 1;
        
        if ( $count > $args['range'] ) {
            if ( $page <= $args['range'] ) {
                $min = 1;
                $max = $args['range'] + 1;
            } elseif ( $page >= ($count - $ceil) ) {
                $min = $count - $args['range'];
                $max = $count;
            } elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
                $min = $page - $ceil;
                $max = $page + $ceil;
            }
        } else {
            $min = 1;
            $max = $count;
        }
        
        $echo = '';
        $previous = intval($page) - 1;
        $previous = esc_attr( get_pagenum_link($previous) );
        
        $firstpage = esc_attr( get_pagenum_link(1) );
        if ( $firstpage && (1 != $page) )
            $echo .= '<li class="previous"><a href="' . $firstpage . '">' . __( 'First', 'text-domain' ) . '</a></li>';

        if ( $previous && (1 != $page) )
            $echo .= '<li><a href="' . $previous . '" title="' . __( 'previous', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';
        
        if ( !empty($min) && !empty($max) ) {
            for( $i = $min; $i <= $max; $i++ ) {
                if ($page == $i) {
                    $echo .= '<li class="active"><span class="active">' . str_pad( (int)$i, 2, '0', STR_PAD_LEFT ) . '</span></li>';
                } else {
                    $echo .= sprintf( '<li><a href="%s">%002d</a></li>', esc_attr( get_pagenum_link($i) ), $i );
                }
            }
        }
        
        $next = intval($page) + 1;
        $next = esc_attr( get_pagenum_link($next) );
        if ($next && ($count != $page) )
            $echo .= '<li><a href="' . $next . '" title="' . __( 'next', 'text-domain') . '">' . $args['next_string'] . '</a></li>';
        
        $lastpage = esc_attr( get_pagenum_link($count) );
        if ( $lastpage ) {
            $echo .= '<li class="next"><a href="' . $lastpage . '">' . __( 'Last', 'text-domain' ) . '</a></li>';
        }

        if ( isset($echo) )
            echo $args['before_output'] . $echo . $args['after_output'];
    }

    function edit_link() 
    {
        $link = edit_post_link(
            sprintf(
                /* translators: %s: Name of current post */
                __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'vista' ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );

        return $link;
    }

    class Walker_Secondary_Nav_Menu extends \Walker_Nav_Menu {

        function start_lvl( &$output, $depth = 0, $args = array() ) {

            if ( 0 < $depth ) {

                parent::start_lvl( $output, $depth, $args );
            }
        }

        function end_lvl( &$output, $depth = 0, $args = array() ) {

            if ( 0 < $depth ) {

                parent::end_lvl( $output, $depth, $args );
            }
        }

        function start_el(
            &$output,
            $item,
            $depth = 0,
            $args = array(),
            $id = 0 ) {

            if ( 0 < $depth ) {

                parent::start_el( $output, $item, $depth, $args, $id );
            }
        }

        function end_el(
            &$output,
            $item,
            $depth = 0,
            $args = array() ) {

            if ( 0 < $depth ) {

                parent::end_el( $output, $item, $depth, $args );
            }
        }

        function display_element(
            $element,
            &$children_elements,
            $max_depth,
            $depth = 0,
            $args,
            &$output ) {

            global $wp_query;

            if ( $wp_query->is_main_query()
                && isset( $wp_query->query_vars[ 'post_type' ] )
                && 0 == strcmp( $wp_query->query_vars[ 'post_type' ], 'event' )
                && isset( $wp_query->query_vars[ 'event' ] )
                && 1 === preg_match( '/.*\/whats-on\/$/', $element->url ) ) {

                $element->classes[] = 'current-menu-item';
            }

            $classes = empty( $element->classes ) ? array() : (array) $element->classes;

            $c = array_intersect(
                array(
                    'current-menu-item',
                    'current-menu-parent',
                    'current-menu-ancestor'
                ),
                $classes
            );

            if ( 0 < $depth
                || !empty( $c ) ) {

                parent::display_element(
                    $element,
                    $children_elements,
                    $max_depth,
                    $depth,
                    $args,
                    $output
                );
            }
        }
    }
}