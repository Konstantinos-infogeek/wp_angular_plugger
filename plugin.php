<?php
/*
  Plugin Name: Angular Plugger
  Plugin URI: http://www.infogeek.gr
  Description: Example of angularjs usage on wordpress
  Author: konstantinos tsatsarounos
  Version: '1.0'
  Author URI: www.infogeek.gr
  License: GPL-2.0+
  License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ANGP')) {
    define('ANGP', '1.0');
}


if (!function_exists('ap_init')) {
    function ap_init()
    {
        //do code
    }

    add_action('plugins_loaded', 'ap_init', 1);
}


if (!function_exists('ap_get_data')) {
    function ap_get_data()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_MAGIC_QUOTES);


        $content = get_post($id);
        $title = null;
        if($content){
            $title = $content->post_title;
        }

        $data = array(
            'name' => get_bloginfo('name'),
            'url' => get_bloginfo('url'),
            'post' => $title
        );

        die(json_encode($data));
    }

    add_action('wp_ajax_ap_get_data', 'ap_get_data', 1);
    add_action('wp_ajax_nopriv_ap_get_data', 'ap_get_data', 1);
}


if (!function_exists('ap_enqueue_scripts')) {
    function ap_enqueue_scripts()
    {
        //$handle, $src, $deps = array(), $ver = false, $in_footer = false
        wp_register_script('ap_angular_js', '//ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular.min.js', array(), '1.4.3', false);
        wp_register_script('ap_app_js', plugins_url('scripts/app.js', __FILE__), array('ap_angular_js'), '1.0', false);

        $local = array(
            'admin' => admin_url('admin-ajax.php')
        );

        wp_localize_script('ap_app_js', 'angvars', $local);

        wp_enqueue_script('ap_app_js');

    }

    add_action('wp_enqueue_scripts', 'ap_enqueue_scripts', 1);
}


if (!function_exists('ap_short_app')) {
    function ap_short_app()
    {
        $output = '<div role="application" ng-app="AngApp" >';
        $output .= '<div ng-controller="MyController">';
        $output .= '<label>Insert PostID</label><input type="number" ng-model="postId" ng-change="setNewTitle()" />';
        $output .= '<p>{{title}}</p>';
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    add_shortcode('post_title_widget', 'ap_short_app');
}