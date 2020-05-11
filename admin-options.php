<?php 
function branded_sharebox_register_options_page() {
  register_post_type('labs', [
    // ect
    'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1591 1448q56 89 21.5 152.5t-140.5 63.5h-1152q-106 0-140.5-63.5t21.5-152.5l503-793v-399h-64q-26 0-45-19t-19-45 19-45 45-19h512q26 0 45 19t19 45-19 45-45 19h-64v399zm-779-725l-272 429h712l-272-429-20-31v-436h-128v436z"/></svg>')
 ]);
    add_menu_page('Branded Sharebox', 'Branded Sharebox', 'manage_options', 'branded_sharebox', 'branded_sharebox_option_page');
    add_submenu_page('branded_sharebox', 'Manage URLs', 'Manage URLs','manage_options', 'branded_sharebox_manage_posts', 'branded_sharebox_option_page_test');
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


    register_setting('shorten_settings', 'shorten_api_key',$default);
    register_setting('shorten_settings', 'shorten_domain',$default);
    register_setting('shorten_settings', 'shorten_show_on',$checkbox);
    register_setting('shorten_settings', 'shorten_show_at',$default_show_at);
    register_setting('shorten_settings', 'shorten_show_counter',array('type'  => 'boolean'));
    register_setting('shorten_settings', 'shorten_align_where',array('type'=> 'string','default'=> 'center',));
    register_setting('shorten_settings', 'shorten_button_style',array('type'=> 'string','default'=> 'round',));
    register_setting('shorten_settings', 'shorten_should_float',array('type'  => 'boolean'));
    register_setting('shorten_settings', 'shorten_show_non_singular',array('type'  => 'boolean', 'default'  => true));
    register_setting('shorten_settings', 'shorten_icon_color',$default_color);
    register_setting('shorten_settings', 'shorten_icon_color_custom',$default);
    register_setting('shorten_settings', 'shorten_icon_size',$default_icon_size);
    register_setting('shorten_settings', 'shorten_icon_size_custom',$default);
    register_setting('shorten_settings', 'shorten_url_box',$url_box_config);

  }
  add_action('admin_menu', 'branded_sharebox_register_options_page');



function shorten_enqueue_scripts_admin_options($hook) {
  // Only add to the edit.php admin page.
  // See WP docs.
  wp_enqueue_script( 'branded_sharebox_admin_js', SHORTEN_PLUGIN_URL . '/assets/js/main.js', array('jquery','wp-color-picker'),1.0,false );
  wp_enqueue_style( 'branded_sharebox_admin_style', SHORTEN_PLUGIN_URL . '/assets/css/admin_style.css');
  wp_enqueue_style( 'wp-color-picker' ); 
}

add_action('admin_enqueue_scripts', 'shorten_enqueue_scripts_admin_options');
function branded_sharebox_option_page_test(){
global $wpdb;
$GLOBALS['shortened_post_count'] = $wpdb->get_results(
  "SELECT p.post_type as post_type, count(*) as total_posts 
  FROM {$wpdb->prefix}posts as p  INNER  JOIN {$wpdb->prefix}postmeta as pd
  on p.ID = pd.post_id
  WHERE pd.meta_key = 'shorten_url' and p.post_status = 'publish'
  GROUP BY p.post_type",ARRAY_A
);
$GLOBALS['post_count'] = $wpdb->get_results(
  "SELECT post_type, count(*) as total_posts FROM {$wpdb->prefix}posts
  WHERE post_status = 'publish'
  GROUP BY post_type",ARRAY_A
);
function shorten_post_count($post_type){
  foreach($GLOBALS['shortened_post_count'] as $post){
    if($post['post_type'] == $post_type){
      $returnArr = $post['total_posts'];
    }
  }
  return $returnArr;
}

?>
  <div>
  <?php screen_icon(); ?>
  <h1>Manage URLs</h1>
  <?php settings_fields( 'shorten_settings' );do_settings_sections( 'shorten_settings' ) ?>
  <table class="form-table bs-table" role="presentation">
    <tr>
      <th>Post Type</th>
      <th>Shortened URL posts / All posts</th>
      <th>Posts left</th>
      <th></th>
    </tr>
    <?php 
    $args = array('public' => true); // expected to get custom post types

    foreach ( $GLOBALS['post_count'] as $post ) {
      $shortened_post_count = shorten_post_count($post['post_type'])??'0';
      $difference = $post['total_posts'] - $shortened_post_count;
      $proportion = number_format($shortened_post_count / $post['total_posts'] * 100,2,',',' ');
      echo "<tr>
            <td class='post-type'>{$post['post_type']}</td>
            <td class='bs-proportion'><span class='bs-proportion-inner'><span class='bs-numerator'>{$shortened_post_count}</span> / <span class='bs-denominator'>{$post['total_posts']}</span></span> <br> <span class='bs-proportion-percentage'>{$proportion}</span>%</td>
            <td class='bs-difference'>{$difference}</td>"
            . ($difference?"<td><button class='button gen-url-btn' data-post-type='{$post['post_type']}'> Generate $difference URLs</button></td>":"<td>Done</td>").
            "</tr>";
    // var_dump($post_type);
    }
    ?>
  
  </table>
  </div>



  <script>
    jQuery(function($){
      
      $( '.gen-url-btn' ).on( 'click', function() {
          console.log("CLICKED!!");
          event.preventDefault();

          var self = $( this );
          var parent_td = self.parent();
          var parent_tr = parent_td.parent();
          self.parent().html('<div class="loader"></div>');
          let gen_urls_ajax_options = {
              action: 'bs_gen_mass_url',
              nonce: '<?php echo wp_create_nonce('gen_mass_url'); ?>',
              ajaxurl: '<?php echo SHORTEN_PLUGIN_URL . 'ajax.php' ?>',
              post_type: self.data('post-type')
          };
          $.post( gen_urls_ajax_options.ajaxurl, gen_urls_ajax_options, function(data) {
            data = JSON.parse(data);  
            if(data.success){
              let numerator = parent_tr.find('.bs-proportion-inner .bs-numerator');
              let denominator = parent_tr.find('.bs-proportion-inner .bs-denominator');
              numerator.text(parseInt(numerator.text())+data.updated);
              parent_tr.find('.bs-proportion-percentage').text( (parseInt(numerator.text()) / parseInt(denominator.text()) * 100).toFixed(2) );
              parent_tr.find('.bs-difference').text( parseInt(numerator.text()) - parseInt(denominator.text()) );
              parent_td.html('Done');
            }
            else if(data.failed){
              window.alert("Somethign wrong with ajax request");
              console.log(data);
              window.alert(data.fail.error);
            }
          });
          return false;
        });
    })
  </script>
  <?php
  
unset($GLOBALS['shortened_post_count']); 
unset($GLOBALS['post_count']); 
}


function branded_sharebox_option_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h1>Branded Sharebox</h1>
  <form method="post" action="options.php">
  <?php settings_fields( 'shorten_settings' );do_settings_sections( 'shorten_settings' ) ?>
  <table class="form-table" role="presentation">
  <tr valign="top">
    <th scope="row"><label for="shorten_api_key">API key</label></th>
    <td><input type="text" id="shorten_api_key" name="shorten_api_key" value="<?php echo get_option('shorten_api_key'); ?>" class="regular-text"/>
    <p class="description" id="domain-description">API key from <a href="https://app.shorten.rest/settings">Shorten.REST</a></p></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="shorten_domain">Domain</label></th>
    <td><input type="text" id="shorten_domain" name="shorten_domain" value="<?php echo get_option('shorten_domain'); ?>" class="regular-text" aria-describedby="domain-description"/>
    <p class="description" id="domain-description">Short domain that is already assigned to your account on  <a href="https://app.shorten.rest/settings">Shorten.REST</a></p></td>
  </tr>
  <?php $shorten_show_on = get_option( 'shorten_show_on' ); ?>
  <tr class="option-site-visibility">
    <th scope="row">Show on:</th>
    <td><fieldset><legend class="screen-reader-text"><span>Show on:</span></legend>
      <p class="description">Select which post types to include</p>
      <?php 
        $args = array(
          'public'   => true,
        );
        $post_types = get_post_types($args);
        foreach($post_types as $post_type):?>
      <label for="shorten_show_on_<?php echo $post_type ?>"><input name="shorten_show_on[<?php echo $post_type ?>]" type="checkbox" id="shorten_show_on_<?php echo $post_type ?>" value="1"<?php checked( isset( $shorten_show_on[$post_type] ) ); ?>>
      <?php echo get_post_type_object( $post_type )->label?></label><br>
        <?php endforeach;?>
    </fieldset></td>
  </tr>
  <?php $shorten_show_at = get_option( 'shorten_show_at' ); ?>
  <tr class="option-site-visibility">
    <th scope="row">Sharebox location:</th>
    <td><fieldset><legend class="screen-reader-text"><span>Sharebox location</span></legend>
      <label for="shorten_show_at_top"><input name="shorten_show_at" type="radio" id="shorten_show_at_top" value="top"<?php checked( 'top' == $shorten_show_at  ); ?>>
      Before the content</label><br>
      <label for="shorten_show_at_bottom"><input name="shorten_show_at" type="radio" id="shorten_show_at_bottom" value="bottom"<?php checked( 'bottom' == $shorten_show_at  ); ?>>
      After the content</label><br>
      <label for="shorten_show_at_both"><input name="shorten_show_at" type="radio" id="shorten_show_at_both" value="both"<?php checked(  'both' == $shorten_show_at  ); ?>>
      Both</label><br>
    </fieldset></td>
  </tr>
  <?php $shorten_align_where = get_option( 'shorten_align_where' ); ?>
  <tr class="option-site-visibility">
    <th scope="row">Align buttons:</th>
    <td><fieldset><legend class="screen-reader-text"><span>Align buttons</span></legend>
      <label for="shorten_align_where_start"><input name="shorten_align_where" type="radio" id="shorten_align_where_start" value="start"<?php checked( 'start' == $shorten_align_where  ); ?>>
      Left</label><br>
      <label for="shorten_align_where_center"><input name="shorten_align_where" type="radio" id="shorten_align_where_center" value="center"<?php checked( 'center' == $shorten_align_where  ); ?>>
      Center</label><br>
      <label for="shorten_align_where_end"><input name="shorten_align_where" type="radio" id="shorten_align_where_end" value="end"<?php checked(  'end' == $shorten_align_where  ); ?>>
      Right</label><br>
    </fieldset></td>
  </tr>

  <?php $shorten_button_style = get_option( 'shorten_button_style' ); ?>
  <tr class="option-site-visibility">
    <th scope="row">Button Style:</th>
    <td><fieldset><legend class="screen-reader-text"><span>Button Style</span></legend>
      <label for="shorten_button_style_round"><input name="shorten_button_style" type="radio" id="shorten_button_style_round" value="round"<?php checked( 'round' == $shorten_button_style  ); ?>>
      Round</label><br>
      <label for="shorten_button_style_square"><input name="shorten_button_style" type="radio" id="shorten_button_style_square" value="square"<?php checked( 'square' == $shorten_button_style  ); ?>>
      Square</label><br>
    </fieldset></td>
  </tr>




  <?php $shorten_show_counter = get_option( 'shorten_show_counter' ); ?>
  <tr class="option-site-visibility">
    <th scope="row">Display the number of shares:</th>
    <td><fieldset><legend class="screen-reader-text"><span>Display the number of shares</span></legend>
      <label for="shorten_show_counter"><input name="shorten_show_counter" type="checkbox" id="shorten_show_counter" value="1"<?php checked( $shorten_show_counter  ); ?>>
      Yes</label><p class="description">Shot the number of shares next to the share buttons</p><br>
    </fieldset></td>
  </tr>
  <?php $shorten_should_float = get_option( 'shorten_should_float' ); ?>
  <tr class="option-site-visibility">
    <th scope="row">Show as floating widget:</th>
    <td><fieldset><legend class="screen-reader-text"><span>Show as floating widget</span></legend>
      <label for="shorten_should_float"><input name="shorten_should_float" type="checkbox" id="shorten_should_float" value="1"<?php checked( $shorten_should_float  ); ?>>
      Yes</label><p class="description">This will also disable "show non singular posts"</p><br>
    </fieldset></td>
  </tr>
  <?php $shorten_show_non_singular = get_option( 'shorten_show_non_singular' ); ?>
  <tr class="option-site-visibility">
    <th scope="row">Show non singular posts:</th>
    <td><fieldset><legend class="screen-reader-text"><span>Show non singular posts</span></legend>
      <label for="shorten_show_non_singular"><input name="shorten_show_non_singular" type="checkbox" id="shorten_show_non_singular" value="1"<?php disabled( $shorten_should_float ); checked( $shorten_show_non_singular  && !$shorten_should_float ); ?>>
      Yes</label><p class="description">e.g. show posts listed inside archive pages</p><br>
    </fieldset></td>
  </tr>
  <?php $shorten_icon_color = get_option( 'shorten_icon_color' ); ?>
    <tr class="option-site-visibility button-color">
      <th scope="row">Social buttons icon color:</th>
      <td><fieldset><legend class="screen-reader-text"><span>Social buttons icon color:</span></legend>
        <p class="description">Select what color should the icons be</p>
        <label for="shorten_icon_color_brand"><input name="shorten_icon_color" type="radio" id="shorten_icon_color_brand" value="brand"<?php checked( 'brand' == $shorten_icon_color  ); ?>>
        Brand Color</label><br>
        <label for="shorten_icon_color_greyscale"><input name="shorten_icon_color" type="radio" id="shorten_icon_color_greyscale" value="greyscale"<?php checked( 'greyscale' == $shorten_icon_color  ); ?>>
        Greysale</label><br>
        <label for="shorten_icon_color_custom"><input name="shorten_icon_color" type="radio" id="shorten_icon_color_custom" value="custom"<?php checked(  'custom' == $shorten_icon_color  ); ?>>
        Custom</label><br>
        <label for="shorten_icon_color_custom_text" class="shorten_icon_color_custom_label" <?php if('custom' != $shorten_icon_color) echo 'style="display:none"'?> ><input type="text" style="padding:0;cursor:pointer" name="shorten_icon_color_custom" class="shorten_icon_color_custom" id="" value="<?php echo get_option( 'shorten_icon_color_custom' )?>">
        <p class='description'>Select Color</p><input type="text" style="padding:0;cursor:pointer"  id="shorten_icon_color_custom_text" value="<?php echo get_option( 'shorten_icon_color_custom' )?>">
        <p class='description'></p></label><br>

        
      </fieldset></td>
    </tr>
    <?php $shorten_icon_size = get_option( 'shorten_icon_size' ); ?>
    <tr class="option-site-visibility">
      <th scope="row">Social buttons icon color:</th>
      <td><fieldset><legend class="screen-reader-text"><span>Social buttons icon color:</span></legend>
        <p class="description">Select what size should the icons be</p>
        <label for="shorten_icon_size_small"><input name="shorten_icon_size" type="radio" id="shorten_icon_size_small" value="small"<?php checked( 'small' == $shorten_icon_size  ); ?>>
        Small</label><br>
        <label for="shorten_icon_size_medium"><input name="shorten_icon_size" type="radio" id="shorten_icon_size_medium" value="medium"<?php checked( 'medium' == $shorten_icon_size  ); ?>>
        Medium</label><br>
        <label for="shorten_icon_size_large"><input name="shorten_icon_size" type="radio" id="shorten_icon_size_large" value="large"<?php checked(  'large' == $shorten_icon_size  ); ?>>
        Large</label><br>
        <label for="shorten_icon_size_custom"><input name="shorten_icon_size" type="radio" id="shorten_icon_size_custom" value="custom"<?php checked(  'custom' == $shorten_icon_size  ); ?>>
        Custom</label><br>
        <label for="shorten_icon_size_custom" class="shorten_icon_size_custom_label" <?php if('custom' != $shorten_icon_size) echo 'style="display:none"'?> ><input type="number" style="text-align:right; width:3rem;" name="shorten_icon_size_custom" class="shorten_icon_size_custom" id="" value="<?php echo get_option( 'shorten_icon_size_custom' )?>">
        <label>px</label>
        <p class='description'>Select size in px</p></label><br>
      </fieldset></td>
    </tr>

    <?php $shorten_url_box = get_option( 'shorten_url_box' ); ?>
    <tr class="option-site-visibility">
      <th scope="row">Share URL Box:</th>
      <td><fieldset class="url-box-border"><legend class="screen-reader-text"><span>Share URL Box:</span></legend>
        <label for="shorten_url_box_enabled"><input name="shorten_url_box[enabled]" type="checkbox" id="shorten_url_box_enabled" value="1"<?php checked( $shorten_url_box['enabled']);?>>
        Enable</label><br>

        <div class="url-box-border-options" <?php if(!$shorten_url_box['enabled']) echo "style='display:none'";?>>
        <label for="shorten_url_box_label"><p class="description">Label Text</p><input name="shorten_url_box[label]" type="text" id="shorten_url_box_label" value="<?php echo ( $shorten_url_box['label']);?>">
        </label><br>
        <label for="shorten_url_box_border_width"><p class="description">Border Width</p> <input name="shorten_url_box[border][width]" style="text-align: right;width: 3rem;"type="number" id="shorten_url_box_border_width" value="<?php echo  $shorten_url_box['border']['width'] ?>">px
        </label><br>
        <p class="description"> Select Border Color </p>
      <label for="shorten_url_box_border_color_custom_text" class="shorten_url_box_border_color_custom_label"><input type="hidden" style="padding:0;cursor:pointer" name="shorten_url_box[border][color]" class="shorten_url_box_border_color_custom" id="" value="<?php echo $shorten_url_box['border']['color']?>" >
       <input type="text" style="padding:0;cursor:pointer"  id="shorten_url_box_border_color_custom_text" data-default-color="<?php echo $shorten_url_box['border']['color']?>" value="<?php echo $shorten_url_box['border']['color']?>">
      </label><br></div>
      </fieldset></td>
    </tr>

  </table>
  <?php submit_button(); ?>
  </form>
  </div>
<?php
} ?>