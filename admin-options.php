<?php
function branded_sharebox_register_options_page()
{
  register_post_type('labs', [
    // ect
    'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1591 1448q56 89 21.5 152.5t-140.5 63.5h-1152q-106 0-140.5-63.5t21.5-152.5l503-793v-399h-64q-26 0-45-19t-19-45 19-45 45-19h512q26 0 45 19t19 45-19 45-45 19h-64v399zm-779-725l-272 429h712l-272-429-20-31v-436h-128v436z"/></svg>')
  ]);
  add_menu_page('Branded Sharebox', 'Branded Sharebox', 'manage_options', 'branded_sharebox', 'branded_sharebox_option_page');
  add_submenu_page('branded_sharebox', 'Manage URLs', 'Manage URLs', 'manage_options', 'branded_sharebox_manage_posts', 'branded_sharebox_option_page_manage_urls');
  add_submenu_page('branded_sharebox', 'Social Networks', 'Social Networks', 'manage_options', 'branded_sharebox_social_networks', 'branded_sharebox_option_page_social_networks');
  $default = array(
    'type'              => 'string',
    'show_in_rest'      => true,
    'sanitize_callback' => 'sanitize_text_field',
  );
  $default_show_at = array(
    'type'              => 'string',
    'default'           => 'top',
  );
  $default_color = array(
    'type'              => 'string',
    'default'           => 'brand',
  );
  $default_icon_size = array(
    'type'              => 'string',
    'default'           => 'medium',
  );
  $checkbox =  array(
    'type'              =>  'array',
  );
  $url_box_config = array(
    'type'        => 'array',
    'default'     => array(
      'enabled' => false,
      'border'  => array(
        'color' => '#000000',
        'width' => 1
      ),
      'label' => ""
    )
  );


  register_setting('shorten_settings', 'shorten_api_key', $default);
  register_setting('shorten_settings', 'shorten_domain', $default);
  register_setting('shorten_settings', 'shorten_show_on', $checkbox);
  register_setting('shorten_settings', 'shorten_show_at', $default_show_at);
  register_setting('shorten_settings', 'shorten_show_counter', array('type'  => 'boolean'));
  register_setting('shorten_settings', 'shorten_align_where', array('type' => 'string', 'default' => 'center',));
  register_setting('shorten_settings', 'shorten_button_style', array('type' => 'string', 'default' => 'round',));
  register_setting('shorten_settings', 'shorten_should_float', array('type'  => 'boolean'));
  register_setting('shorten_settings', 'shorten_show_non_singular', array('type'  => 'boolean', 'default'  => true));
  register_setting('shorten_settings', 'shorten_icon_color', $default_color);
  register_setting('shorten_settings', 'shorten_icon_color_custom', $default);
  register_setting('shorten_settings', 'shorten_icon_size', $default_icon_size);
  register_setting('shorten_settings', 'shorten_icon_size_custom', $default);
  register_setting('shorten_settings', 'shorten_url_box', $url_box_config);
  register_setting('shorten_settings_social_links', 'shorten_url_box', array(
    'type'  =>'array',
    'default'   => array(
        'facebook' =>1,
        'twitter' =>1,
        'pinterest' =>1,
        'linkedin'  =>1,
    )
));

}

add_action('admin_menu', 'branded_sharebox_register_options_page');



function shorten_enqueue_scripts_admin_options($hook)
{
  // Only add to the edit.php admin page.
  // See WP docs.
  wp_enqueue_script('font-awesome', "https://kit.fontawesome.com/14875fd6e4.js", array('jquery'), 1.0, false);
  wp_enqueue_script('branded_sharebox_admin_js', SHORTEN_PLUGIN_URL . '/assets/js/main.js', array('jquery', 'wp-color-picker'), 1.0, false);
  wp_enqueue_style('branded_sharebox_admin_style', SHORTEN_PLUGIN_URL . '/assets/css/admin_style.css');
  wp_enqueue_style('wp-color-picker');
}

add_action('admin_enqueue_scripts', 'shorten_enqueue_scripts_admin_options');
function branded_sharebox_option_page_manage_urls()
{
  include_once SHORTEN_PLUGIN_DIR . 'option-pages/manage-urls-page.php';
}


function branded_sharebox_option_page()
{
  include_once SHORTEN_PLUGIN_DIR . '/option-pages/main-page.php';
}

function branded_sharebox_option_page_social_networks()
{
  include_once SHORTEN_PLUGIN_DIR . '/option-pages/social-networks-page.php';
}
