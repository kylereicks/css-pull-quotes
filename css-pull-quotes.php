<?php
/*
Plugin Name: CSS Pull Quotes
Plugin URI: http://github.com/kylereicks/semantic-pullquotes
Description: A wordpress plugin to display pullquotes via CSS.
Author: Kyle Reicks
Version: 0.1
Author URI: http://github.com/kylereicks
*/

if(!class_exists('CSS_Pull_Quotes')){
  class CSS_Pull_Quotes{

    function __construct(){
      require_once('php/class-css-pull-quote-admin-settings.php');
      $css_pull_quote_admin_settings = new CSS_Pull_Quote_Admin_Settings();
      add_shortcode('pullquote', array($this, 'pullquote_shortcode'));
      add_filter('the_content', array($this, 'css_pull_quote_html'), 11);
      add_action('wp_enqueue_scripts', array($this, 'pull_quote_styles'));
      add_action('init', array($this, 'add_pullquote_shortcode_button_tinymce'));
    }

    function pullquote_shortcode($atts, $content = null){
      extract(shortcode_atts(array(
        'position' => 'right'
      ), $atts));
      $content = do_shortcode($content);
      $content = '<span data-pull-quote-text="true" data-pull-quote-position="' . $position . '">' . $content . '</span>';
      return $content;
    }

    function css_pull_quote_html($html){
      $html = $this->standardize_self_closing_tags($html);
      $content = new DOMDocument();
      $content->loadHTML($html);
      $paragraphs = $content->getElementsByTagName('p');
      if($paragraphs->length > 0){
        foreach($paragraphs as $p){
          $spans = $p->getElementsByTagName('span');
          if($spans->length > 0){
            foreach($spans as $span){
              if($span->hasAttribute('data-pull-quote-text')){
                $original_p = $this->allow_empty_spans($this->standardize_self_closing_tags($content->saveXML($p)));
                $original_p = $this->trouble_characters($original_p);
                $original_span = $this->standardize_self_closing_tags($content->saveXML($span));
                $trimmed_span = preg_replace('/^<span[^<]+data-pull-quote-text[^<]+>/', '', $original_span);
                $trimmed_span = substr($trimmed_span, 0, strlen($trimmed_span) - 7);

                $pull_quote_text = html_entity_decode(strip_tags($original_span));
                $pull_quote_position = $span->getAttribute('data-pull-quote-position');
                $p_existing_class = $p->getAttribute('class');
                $p_existing_class = !empty($p_existing_class) ? $p_existing_class . ' ' : '';
                $p->setAttribute('data-pull-quote', $pull_quote_text);
                $p->setAttribute('class', $p_existing_class . 'css-pull-quote pull-quote-' . $pull_quote_position);
                $new_p = $this->standardize_self_closing_tags($content->saveHTML($p));
                $new_p = $this->trouble_characters($new_p);

                $html = $this->str_replace_once($original_p, $new_p, $html);
                $html = $this->str_replace_once($original_span, $trimmed_span, $html);
              }
            }
          }
        }
      }
      return $html;
    }

    function pull_quote_styles(){
      $exclude_css = get_option('_exclude_css');
      if($exclude_css != 1){
        wp_register_style('css_pull_quote_basic', plugins_url('css/css-pull-quote-basic.css', __FILE__), array(), false, 'all');
        wp_enqueue_style('css_pull_quote_basic');
      }
    }

    private function standardize_self_closing_tags($html){
      return preg_replace('/(<(?:area|base|br|col|embed|hr|img|input|keygen|link|menuitem|meta|param|source|track|wbr)[^<]*?)(?:>|\/>|\s\/>)/', '$1 />', $html);
    }

    private function allow_empty_spans($html){
      return preg_replace('/(<(span|div)[^<]*?)(?:\/>|\s\/>)/', '$1></$2>', $html);
    }

    private function trouble_characters($html){
      return str_replace(array('“', '”', '‘', '’', '=""', 'Â'), array('&#8220;', '&#8221;', '&#8216;', '&#8217;', '', ''), $html);
    }

    private function str_replace_once($search, $replace, $subject){
      $string_position = strpos($subject, $search);
      if($string_position !== false){
        return substr_replace($subject, $replace, $string_position, strlen($search));
      }else{
        return $subject;
      }
    }

    function add_pullquote_shortcode_button_tinymce(){
      if(get_user_option('rich_editing') && current_user_can('edit_posts') || current_user_can('edit_pages')){
        add_filter('mce_external_plugins', array($this, 'pullquote_shortcode_mce_plugin'));
        add_filter('mce_buttons', array($this, 'pullquote_shortcode_mce_button'));
      }
    }

    function pullquote_shortcode_mce_plugin($plugin_array){
      $plugin_array['css_pull_quote'] = plugins_url('js/pullquote-shortcode-mce-button.js', __FILE__);
      return $plugin_array;
    }

    function pullquote_shortcode_mce_button($buttons){
      array_push($buttons, 'css_pull_quote');
      return $buttons;
    }
  }
  $css_pull_quotes = new CSS_Pull_Quotes();
}
