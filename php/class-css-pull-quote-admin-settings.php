<?php
if(!class_exists('CSS_Pull_Quote_Admin_Settings')){
  class CSS_Pull_Quote_Admin_Settings{

    function __construct(){
      if(is_admin()){
        add_action('admin_menu', array($this, 'add_css_pull_quote_settings_page'));
        add_action('admin_init', array($this, 'init_css_pull_quote_settings'));
      }
    }

    function add_css_pull_quote_settings_page(){
      add_plugins_page(
        'CSS Pull Quote Settings',
        'CSS Pull Quote Settings',
        'manage_options',
        'css_pull_quote_settings_page',
        array($this, 'css_pull_quote_settings_page_view')
      );
    }

    function css_pull_quote_settings_page_view(){
      ?>
      <div class="wrap">
        <?php screen_icon(); ?>
        <h2>CSS Pull Quote Settings</h2>
        <form method="post" action="options.php">
        <?php
          settings_fields('css_pull_quote_settings_group');
          do_settings_sections('css_pull_quote_settings_page');
        ?>
        <?php submit_button(); ?>
        </form>
      </div>
      <?php
    }

    function init_css_pull_quote_settings(){
      // exclude css setting
      register_setting(
        'css_pull_quote_settings_group',
        '_exclude_css'
      );

      add_settings_section(
        'exclude_css_settings_section',
        'Exclude CSS',
        array($this, 'exclude_css_settings_section_info'),
        'css_pull_quote_settings_page'
      );

      add_settings_field(
        'exclude_css',
        'Exclude Plugin CSS',
        array($this, 'exclude_css_field_view'),
        'css_pull_quote_settings_page',
        'exclude_css_settings_section'
      );
    }

    // section info
    function exclude_css_settings_section_info(){
      echo 'You can improve your proformance by excluding the plugin CSS and adding the CSS directly to your theme files.';
    }

    // fields
    function exclude_css_field_view($datasets){
      $exclude_css = get_option('_exclude_css');
      $exclude_css = isset($exclude_css) && $exclude_css == 1 ? true : false;
      ?>
        <label for="checkbox_exclude_css">Exclude the default plugin css:</label> <input type="checkbox" name="_exclude_css" id="checkbox_exclude_css" value="1"<?php echo $exclude_css ? ' checked' : ''; ?> /><br />
      <?php
    }
  }
}
