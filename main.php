<?php
/**
* Plugin Name: Song Club Setlist
* Description: Simple CPT Shortcode that allows for adding updating songs and links to their respective demos.
 * Version: 0.1
 * Author: Chris Cline
 * Author URI: http://www.christiancline.com
 */

// Exit if this wasn't accessed via WordPress (aka via direct access)
if (!defined('ABSPATH')) exit;

class ScSetlistPlugin {
    public function __construct() {
        // add CSS
        add_action('wp_enqueue_scripts', array($this,'enqueue'));
        //register the post type
        add_action('init', array($this,'registration'));
        //add the shortcode
        add_shortcode('sc_setlist', array($this,'shortcode'));
    }
    //register our post type
    public function registration() {
        register_post_type(
            'sc-setlist',
            array(
                'labels' => array(
                    'name' => _x('Set List', 'post type general name'),
                    'singular_name' => _x('Set List', 'post type singular name'),
                    'add_new' => _x('Add Song', 'nc number'),
                    'add_new_item' => __('Add New Song'),
                    'edit_item' => __('Edit Song'),
                    'new_item' => __('New Song'),
                    'all_items' => __('All Songs'),
                    'view_item' => __('View Song'),
                    'search_items' => __('Search Songs'),
                    'not_found' =>  __('No Songs'),
                    'not_found_in_trash' => __('No Songs found in Trash'),
                    'parent_item_colon' => '',
                    'menu_name' => 'Set List'
                ),
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => false,
                'rewrite' => true,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'capability_type' => 'post',
                'has_archive' => false,
                'hierarchical' => false,
                'menu_position' => 10,
                'supports' => array('title'),
                'public' => true,
            )
        );
    }
    // our shortcode function
    public function shortcode($atts) {

        ob_start();
           $query = new WP_Query( array(
               'post_type' => 'sc-setlist',
               'posts_per_page' => -1,
               'order' => 'DESC',
               'orderby' => 'date',
           ) );
           if ( $query->have_posts() ) { ?>
               <h2 class="song-list col-md-12">Song Club Songs:</h2>
               <ul class="set-list">
                   <?php while ( $query->have_posts() ) : $query->the_post();
                   $url = get_field( 'link_to_demo' );//ACF field ?>
                   <li class="item">
                       <a href="<?php echo $url; ?>" target="_blank"><?php the_title(); ?></a>
                   </li>
                   <?php endwhile;
                   wp_reset_postdata(); ?>
               </ul>
           <?php $output = ob_get_clean();
           return $output;
           }
    }

    // enqueue function for our css
    public function enqueue() {
        wp_enqueue_style('sc-setlist', plugins_url('css/sc-setlist.css', __FILE__), null, '1.0');
    }
}
// Let's do this thing!
$scSetPlug = new  ScSetlistPlugin();
