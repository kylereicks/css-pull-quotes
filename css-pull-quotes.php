<?php
/*
Plugin Name: CSS Pull Quotes
Plugin URI: http://github.com/kylereicks/semantic-pullquotes
Description: A wordpress plugin to display pullquotes via CSS.
Author: Kyle Reicks
Version: 0.1
Author URI: http://github.com/kylereicks
*/

define('CSS_PULL_QUOTES_PATH', plugin_dir_path(__FILE__));
define('CSS_PULL_QUOTES_URL', plugins_url('/', __FILE__));
define('CSS_PULL_QUOTES_VERSION', '0.1.0');

require_once(CSS_PULL_QUOTES_PATH . 'inc/class-css-pull-quotes.php');

register_deactivation_hook(__FILE__, array('CSS_Pull_Quotes', 'deactivate'));

CSS_Pull_Quotes::get_instance();
