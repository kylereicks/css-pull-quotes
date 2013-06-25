<?php
if(!class_exists('CSS_Pull_Quotes')){
  class CSS_Pull_Quotes{

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

    public static function deactivate(){
      self::clear_css_pull_quotes_settings();
    }

    private static function clear_css_pull_quotes_settings(){
      global $wpdb;
      $css_pull_quotes_settings = $wpdb->get_col('SELECT option_name FROM ' . $wpdb->options . ' WHERE option_name LIKE \'_css_pull_quotes%\'');
      foreach($css_pull_quotes_settings as $setting){
        delete_option($setting);
      }
    }


    private function __construct(){
      require_once(CSS_PULL_QUOTES_PATH . 'inc/class-css-pull-quotes-tinymce.php');
      require_once(CSS_PULL_QUOTES_PATH . 'inc/class-css-pull-quotes-admin-settings.php');
      CSS_Pull_Quotes_TinyMCE::get_instance();
      $css_pull_quotes_admin_settings = CSS_Pull_Quotes_Admin_Settings::get_instance();
      add_shortcode('pullquote', array($this, 'pullquote_shortcode'));
      add_filter('the_content', array($this, 'css_pull_quote_html'), 11);
      add_action('wp_enqueue_scripts', array($this, 'pull_quote_styles'));
    }

    public function pullquote_shortcode($atts, $content = null){
      extract(shortcode_atts(array(
        'position' => 'right'
      ), $atts));
      $content = do_shortcode($content);
      $content = '<span data-pull-quote-text data-pull-quote-position="' . $position . '">' . $content . '</span>';
      return $content;
    }

    public function css_pull_quote_html($html){
      $html = self::standardize_self_closing_tags($html);
      $content = new DOMDocument();
      $content->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . $html);
      $paragraphs = $content->getElementsByTagName('p');
      if($paragraphs->length > 0){
        foreach($paragraphs as $p){
          $spans = $p->getElementsByTagName('span');
          if($spans->length > 0){
            foreach($spans as $span){
              if($span->hasAttribute('data-pull-quote-text')){
                $original_p = html_entity_decode(self::allow_empty_spans(self::standardize_self_closing_tags($content->saveXML($p))), ENT_COMPAT, 'UTF-8');
                $original_span = self::standardize_self_closing_tags($content->saveXML($span));
                $trimmed_span = preg_replace('/^<span[^<]+data-pull-quote-text[^<]+>/', '', $original_span);
                $trimmed_span = substr($trimmed_span, 0, strlen($trimmed_span) - 7);

                $pull_quote_text = html_entity_decode(strip_tags($original_span), ENT_COMPAT, 'UTF-8');
                $pull_quote_position = $span->getAttribute('data-pull-quote-position');
                $p_existing_class = $p->getAttribute('class');
                $p_existing_class = !empty($p_existing_class) ? $p_existing_class . ' ' : '';
                $p->setAttribute('data-pull-quote', $pull_quote_text);
                $p->setAttribute('class', $p_existing_class . 'css-pull-quote pull-quote-' . $pull_quote_position);
                $new_p = self::standardize_self_closing_tags($content->saveHTML($p));

                $html = $this->str_replace_once(self::fix_empty_attributes($original_p), $new_p, html_entity_decode($html, ENT_COMPAT, 'UTF-8'));
                $html = $this->str_replace_once($original_span, $trimmed_span, $html);
              }
            }
          }
        }
      }
      return $html;
    }

    public function pull_quote_styles(){
      $exclude_css = get_option('_css_pull_quotes_exclude_css');
      if($exclude_css != 1){
        wp_register_style('css_pull_quote_basic', CSS_PULL_QUOTES_URL . 'css/css-pull-quote-basic.css', array(), false, 'all');
        wp_enqueue_style('css_pull_quote_basic');
      }
    }

    static function standardize_self_closing_tags($html){
      return preg_replace('/(<(?:area|base|br|col|embed|hr|img|input|keygen|link|menuitem|meta|param|source|track|wbr)[^<]*?)(?:>|\/>|\s\/>)/', '$1 />', $html);
    }

    static function allow_empty_spans($html){
      return preg_replace('/(<(span|div)[^<]*?)(?:\/>|\s\/>)/', '$1></$2>', $html);
    }

    private function fix_empty_attributes($html){
      return str_replace('=""', '', $html);
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
}
