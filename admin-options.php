<?php
function branded_sharebox_register_options_page()
{
  register_post_type('labs', [
    // ect
    'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1591 1448q56 89 21.5 152.5t-140.5 63.5h-1152q-106 0-140.5-63.5t21.5-152.5l503-793v-399h-64q-26 0-45-19t-19-45 19-45 45-19h512q26 0 45 19t19 45-19 45-45 19h-64v399zm-779-725l-272 429h712l-272-429-20-31v-436h-128v436z"/></svg>')
  ]);
  add_menu_page('Branded Sharebox', 'Branded Sharebox', 'manage_options', 'branded_sharebox', 'branded_sharebox_option_page', 'dashicons-admin-links');
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
    'sanitize_callback' => 'sanitize_text_field',
  );
  $default_color = array(
    'type'              => 'string',
    'default'           => 'brand',
    'sanitize_callback' => 'sanitize_text_field',
  );
  $default_icon_size = array(
    'type'              => 'string',
    'default'           => 'medium',
    'sanitize_callback' => 'sanitize_text_field',
  );
  $checkbox =  array(
    'type'              =>  'array',
    'sanitize_callback' => 'branded_sharebox_sanatize_array'
  );
  $url_box_config = array(
    'type'        => 'array',
    'default'     => array(
      'enabled' => false,
      'border'  => array(
        'color' => '#000000',
        'width' => 1
      ),
      'label' => "",
      'position'  => 'top',
    )
  );
function branded_sharebox_sanatize_array($arr){
  if (is_array($arr)) {
    foreach ($arr as &$tag) {
        $el = esc_attr($el);
    }
    unset( $el );
  } else {
      $arr = esc_attr($arr);
  }
  return $arr;
}

  register_setting('branded_sharebox_settings', 'branded_sharebox_api_key', $default);
  register_setting('branded_sharebox_settings', 'branded_sharebox_domain', $default);
  register_setting('branded_sharebox_settings', 'branded_sharebox_show_on', $checkbox);
  register_setting('branded_sharebox_settings', 'branded_sharebox_show_at', $default_show_at);
  register_setting('branded_sharebox_settings', 'branded_sharebox_show_counter', array('type'  => 'boolean'));
  register_setting('branded_sharebox_settings', 'branded_sharebox_align_where', array('type' => 'string', 'default' => 'center','sanitize_callback' => 'sanitize_text_field'));
  register_setting('branded_sharebox_settings', 'branded_sharebox_button_style', array('type' => 'string', 'default' => 'round','sanitize_callback' => 'sanitize_text_field'));
  register_setting('branded_sharebox_settings', 'branded_sharebox_should_float', array('type'  => 'boolean'));
  register_setting('branded_sharebox_settings', 'branded_sharebox_show_non_singular', array('type'  => 'boolean', 'default'  => true));
  register_setting('branded_sharebox_settings', 'branded_sharebox_icon_color', $default_color);
  register_setting('branded_sharebox_settings', 'branded_sharebox_icon_color_custom', $default);
  register_setting('branded_sharebox_settings', 'branded_sharebox_icon_size', $default_icon_size);
  register_setting('branded_sharebox_settings', 'branded_sharebox_icon_size_custom', $default);
  register_setting('branded_sharebox_settings', 'branded_sharebox_url_box', $url_box_config);
  register_setting('branded_sharebox_settings_social_links', 'branded_sharebox_url_social_link', array(
    'type'  =>'array',
    'default'   => array(
        'facebook' =>1,
        'twitter' =>1,
        'pinterest' =>1,
        'linkedin'  =>1,
        'sanitize_callback' => 'branded_sharebox_sanatize_array'
    )
));

}

add_action('admin_menu', 'branded_sharebox_register_options_page');



function branded_sharebox_enqueue_scripts_admin_options($hook)
{
  // Only add to the edit.php admin page.
  // See WP docs.
  wp_enqueue_style('font-awesome', "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
  wp_enqueue_script('branded_sharebox_admin_js', BRANDED_SHAREBOX_PLUGIN_URL . '/assets/js/main.js', array('jquery', 'wp-color-picker'), 1.0, false);
  wp_enqueue_style('branded_sharebox_admin_style', BRANDED_SHAREBOX_PLUGIN_URL . '/assets/css/admin_style.css');
  wp_enqueue_style('branded_sharebox_button_style', BRANDED_SHAREBOX_PLUGIN_URL . '/assets/css/style.css');
  wp_enqueue_style('wp-color-picker');
}

add_action('admin_enqueue_scripts', 'branded_sharebox_enqueue_scripts_admin_options');
function branded_sharebox_option_page_manage_urls()
{
  include_once BRANDED_SHAREBOX_PLUGIN_DIR . 'option-pages/manage-urls-page.php';
}


function branded_sharebox_option_page()
{
  include_once BRANDED_SHAREBOX_PLUGIN_DIR . '/option-pages/main-page.php';
}

function branded_sharebox_option_page_social_networks()
{
  include_once BRANDED_SHAREBOX_PLUGIN_DIR . '/option-pages/social-networks-page.php';
}
