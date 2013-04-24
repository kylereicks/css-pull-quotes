<?php
/*
Plugin Name: Semantic Pullquotes
Plugin URI: http://github.com/kylereicks/semantic-pullquotes
Description: A wordpress plugin to use sematic pullquotes, which display pullquotes via CSS.
Author: Kyle Reicks
Version: 0.1
Author URI: http://github.com/kylereicks
*/

if(!class_exists('Semantic_Pullquotes')){
  class Semantic_Pullquotes{

    function __construct(){
      add_shortcode('pullquote', array($this, 'pullquote_shortcode'));
      add_filter('the_content', array($this, 'pullquote_setup'), 11);
      add_action('wp_enqueue_scripts', array($this, 'pullquote_styles'));
    }

    function pullquote_shortcode($atts, $content = null){
      extract(shortcode_atts(array(
        'position' => 'right'
      ), $atts));
      $content = do_shortcode($content);
      $content = '<span data-pullquote-text="true" data-pullquote-position="' . $position . '">' . $content . '</span>';
      return $content;
    }

    function pullquote_setup($html){
      $html = $this->standardize_self_closing_tags($html);
      $content = new DOMDocument();
      $content->loadHTML($html);
      $paragraphs = $content->getElementsByTagName('p');
      if($paragraphs->length > 0){
        foreach($paragraphs as $p){
          $spans = $p->getElementsByTagName('span');
          if($spans->length > 0){
            foreach($spans as $span){
              if($span->hasAttribute('data-pullquote-text')){
                $original_p = $this->allow_empty_spans($this->standardize_self_closing_tags($content->saveXML($p)));
                $original_p = $this->trouble_characters($original_p);
                $original_span = $this->standardize_self_closing_tags($content->saveXML($span));
                $trimmed_span = preg_replace('/^<span[^<]+data-pullquote-text[^<]+>/', '', $original_span);
                $trimmed_span = substr($trimmed_span, 0, strlen($trimmed_span) - 7);

                $pullquote_text = html_entity_decode(strip_tags($original_span));
                $pullquote_position = $span->getAttribute('data-pullquote-position');
                $p_existing_class = $p->getAttribute('class');
                $p_existing_class = !empty($p_existing_class) ? $p_existing_class . ' ' : '';
                $p->setAttribute('data-pullquote', $pullquote_text);
                $p->setAttribute('class', $p_existing_class . 'semantic-pullquote pullquote-' . $pullquote_position);
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

    function pullquote_styles(){
        wp_register_style('semantic_pullquote_basic', plugins_url('css/semantic-pullquote-basic.css', __FILE__), array(), false, 'all');
        wp_enqueue_style('semantic_pullquote_basic');
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
  }
  $semantic_pullquotes = new Semantic_Pullquotes();
}
