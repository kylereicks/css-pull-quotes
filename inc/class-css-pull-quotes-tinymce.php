<?php
if(!class_exists('CSS_Pull_Quotes_TinyMCE')){
  class CSS_Pull_Quotes_TinyMCE{

    // Setup singleton pattern
    public static function get_instance(){
      static $instance;

      if(null === $instance){
        $instance = new self();
      }

      return $instance;
    }

    private function __clone(){
      return null;
    }

    private function __wakeup(){
      return null;
    }

    function __construct(){
      add_action('init', array($this, 'add_pullquote_shortcode_button_tinymce'));
    }

    function add_pullquote_shortcode_button_tinymce(){
      if(get_user_option('rich_editing') && current_user_can('edit_posts') || current_user_can('edit_pages')){
        add_filter('mce_external_plugins', array($this, 'pullquote_shortcode_mce_plugin'));
        add_filter('mce_buttons', array($this, 'pullquote_shortcode_mce_button'));
      }
    }

    function pullquote_shortcode_mce_plugin($plugin_array){
      $plugin_array['css_pull_quote'] = CSS_PULL_QUOTES_URL . 'js/pullquote-shortcode-mce-button.js';
      return $plugin_array;
    }

    function pullquote_shortcode_mce_button($buttons){
      array_push($buttons, 'css_pull_quote');
      return $buttons;
    }
  }
}
