<div>
    <h1>Branded Sharebox</h1>
    <form method="post" action="options.php">
        <?php settings_fields('branded_sharebox_settings');
        do_settings_sections('branded_sharebox_settings') ?>
        <table class="form-table" role="presentation">
            <tr valign="top">
                <th scope="row"><label for="branded_sharebox_api_key">API key</label></th>
                <td><input type="text" id="branded_sharebox_api_key" name="branded_sharebox_api_key" value="<?php echo get_option('branded_sharebox_api_key'); ?>" class="regular-text" />
                    <p class="description" id="domain-description">API key from <a href="https://app.shorten.rest/settings">shorten.REST</a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="branded_sharebox_domain">Domain</label></th>
                <td><input type="text" id="branded_sharebox_domain" name="branded_sharebox_domain" value="<?php echo get_option('branded_sharebox_domain'); ?>" class="regular-text" aria-describedby="domain-description" />
                    <p class="description" id="domain-description">Short domain that is already assigned to your account on <a href="https://app.shorten.rest/settings">shorten.REST</a></p>
                </td>
            </tr>
            <?php $branded_sharebox_show_on = get_option('branded_sharebox_show_on'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Show on:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Show on: </span></legend>
                        <p class="description">Select which post types to include</p>
                        <?php
                        $args = array(
                            'public'   => true,
                        );
                        $post_types = get_post_types($args);
                        foreach ($post_types as $post_type) : ?>
                            <label for="branded_sharebox_show_on_<?php echo $post_type ?>"><input name="branded_sharebox_show_on[<?php echo $post_type ?>]" type="checkbox" id="branded_sharebox_show_on_<?php echo $post_type ?>" value="1" <?php checked(isset($branded_sharebox_show_on[$post_type])); ?>>
                                <?php echo get_post_type_object($post_type)->label ?></label><br>
                        <?php endforeach; ?>
                    </fieldset>
                </td>
            </tr>
            <?php $branded_sharebox_show_at = get_option('branded_sharebox_show_at'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Sharebox location:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Sharebox location</span></legend>
                        <label for="branded_sharebox_show_at_top"><input name="branded_sharebox_show_at" type="radio" id="branded_sharebox_show_at_top" value="top" <?php checked('top' == $branded_sharebox_show_at); ?>>
                            Before the content</label><br>
                        <label for="branded_sharebox_show_at_bottom"><input name="branded_sharebox_show_at" type="radio" id="branded_sharebox_show_at_bottom" value="bottom" <?php checked('bottom' == $branded_sharebox_show_at); ?>>
                            After the content</label><br>
                        <label for="branded_sharebox_show_at_both"><input name="branded_sharebox_show_at" type="radio" id="branded_sharebox_show_at_both" value="both" <?php checked('both' == $branded_sharebox_show_at); ?>>
                            Both</label><br>
                    </fieldset>
                </td>
            </tr>
            <?php $branded_sharebox_align_where = get_option('branded_sharebox_align_where'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Align buttons:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Align buttons</span></legend>
                        <label for="branded_sharebox_align_where_start"><input name="branded_sharebox_align_where" type="radio" id="branded_sharebox_align_where_start" value="start" <?php checked('start' == $branded_sharebox_align_where); ?>>
                            Left</label><br>
                        <label for="branded_sharebox_align_where_center"><input name="branded_sharebox_align_where" type="radio" id="branded_sharebox_align_where_center" value="center" <?php checked('center' == $branded_sharebox_align_where); ?>>
                            Center</label><br>
                        <label for="branded_sharebox_align_where_end"><input name="branded_sharebox_align_where" type="radio" id="branded_sharebox_align_where_end" value="end" <?php checked('end' == $branded_sharebox_align_where); ?>>
                            Right</label><br>
                    </fieldset>
                </td>
            </tr>

            <?php $branded_sharebox_button_style = get_option('branded_sharebox_button_style'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Button Style:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Button Style</span></legend>
                        <label for="branded_sharebox_button_style_round"><input name="branded_sharebox_button_style" type="radio" id="branded_sharebox_button_style_round" value="round" <?php checked('round' == $branded_sharebox_button_style); ?>>
                            Round</label><br>
                        <label for="branded_sharebox_button_style_square"><input name="branded_sharebox_button_style" type="radio" id="branded_sharebox_button_style_square" value="square" <?php checked('square' == $branded_sharebox_button_style); ?>>
                            Square</label><br>
                    </fieldset>
                </td>
            </tr>

            <?php $branded_sharebox_show_counter = get_option('branded_sharebox_show_counter'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Display the number of shares:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Display the number of shares</span></legend>
                        <label for="branded_sharebox_show_counter"><input name="branded_sharebox_show_counter" type="checkbox" id="branded_sharebox_show_counter" value="1" <?php checked($branded_sharebox_show_counter); ?>>
                            Yes</label>
                        <p class="description">Shot the number of shares next to the share buttons</p><br>
                    </fieldset>
                </td>
            </tr>
            <?php $branded_sharebox_should_float = get_option('branded_sharebox_should_float'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Show as sticky widget:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Show as sticky widget</span></legend>
                        <label for="branded_sharebox_should_float"><input name="branded_sharebox_should_float" type="checkbox" id="branded_sharebox_should_float" value="1" <?php checked($branded_sharebox_should_float); ?>>
                            Yes</label>
                        <p class="description">This will also disable "show non singular posts"</p><br>
                    </fieldset>
                </td>
            </tr>
            <?php $branded_sharebox_show_non_singular = get_option('branded_sharebox_show_non_singular'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Show non singular posts:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Show non singular posts</span></legend>
                        <label for="branded_sharebox_show_non_singular"><input name="branded_sharebox_show_non_singular" type="checkbox" id="branded_sharebox_show_non_singular" value="1" <?php disabled($branded_sharebox_should_float);
                            checked($branded_sharebox_show_non_singular  && !$branded_sharebox_should_float); ?>>
                            Yes</label>
                        <p class="description">e.g. show posts listed inside archive pages</p><br>
                    </fieldset>
                </td>
            </tr>
            <?php $branded_sharebox_icon_color = get_option('branded_sharebox_icon_color'); ?>
            <tr class="option-site-visibility button-color">
                <th scope="row">Social buttons icon color:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Social buttons icon color:</span></legend>
                        <p class="description">Select what color should the icons be</p>
                        <label for="branded_sharebox_icon_color_brand"><input name="branded_sharebox_icon_color" type="radio" id="branded_sharebox_icon_color_brand" value="brand" <?php checked('brand' == $branded_sharebox_icon_color); ?>>
                            Brand Color</label><br>
                        <label for="branded_sharebox_icon_color_grayscale"><input name="branded_sharebox_icon_color" type="radio" id="branded_sharebox_icon_color_grayscale" value="grayscale" <?php checked('grayscale' == $branded_sharebox_icon_color); ?>>
                            Greysale</label><br>
                        <label for="branded_sharebox_icon_color_custom"><input name="branded_sharebox_icon_color" type="radio" id="branded_sharebox_icon_color_custom" value="custom" <?php checked('custom' == $branded_sharebox_icon_color); ?>>
                            Custom</label><br>
                        <label for="branded_sharebox_icon_color_custom_text" class="branded_sharebox_icon_color_custom_label" <?php if ('custom' != $branded_sharebox_icon_color) echo 'style="display:none"' ?>><input type="text" style="padding:0;cursor:pointer" name="branded_sharebox_icon_color_custom" class="branded_sharebox_icon_color_custom" id="" value="<?php echo get_option('branded_sharebox_icon_color_custom') ?>">
                            <p class='description'>Select Color</p><input type="text" style="padding:0;cursor:pointer" id="branded_sharebox_icon_color_custom_text" value="<?php echo get_option('branded_sharebox_icon_color_custom') ?>">
                            <p class='description'></p>
                        </label><br>


                    </fieldset>
                </td>
            </tr>
            <?php $branded_sharebox_icon_size = get_option('branded_sharebox_icon_size'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Social buttons icon size:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Social buttons icon size:</span></legend>
                        <p class="description">Select what size should the icons be</p>
                        <label for="branded_sharebox_icon_size_small"><input name="branded_sharebox_icon_size" type="radio" id="branded_sharebox_icon_size_small" value="small" <?php checked('small' == $branded_sharebox_icon_size); ?>>
                            Small</label><br>
                        <label for="branded_sharebox_icon_size_medium"><input name="branded_sharebox_icon_size" type="radio" id="branded_sharebox_icon_size_medium" value="medium" <?php checked('medium' == $branded_sharebox_icon_size); ?>>
                            Medium</label><br>
                        <label for="branded_sharebox_icon_size_large"><input name="branded_sharebox_icon_size" type="radio" id="branded_sharebox_icon_size_large" value="large" <?php checked('large' == $branded_sharebox_icon_size); ?>>
                            Large</label><br>
                        <label for="branded_sharebox_icon_size_custom"><input name="branded_sharebox_icon_size" type="radio" id="branded_sharebox_icon_size_custom" value="custom" <?php checked('custom' == $branded_sharebox_icon_size); ?>>
                            Custom</label><br>
                        <label for="branded_sharebox_icon_size_custom" class="branded_sharebox_icon_size_custom_label" <?php if ('custom' != $branded_sharebox_icon_size) echo 'style="display:none"' ?>><input type="number" style="text-align:right; width:3rem;" name="branded_sharebox_icon_size_custom" class="branded_sharebox_icon_size_custom" id="" value="<?php echo get_option('branded_sharebox_icon_size_custom') ?>">
                            <label>px</label>
                            <p class='description'>Select size in px</p>
                        </label><br>
                    </fieldset>
                </td>
            </tr>

            <?php $branded_sharebox_url_box = get_option('branded_sharebox_url_box'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Share URL Box:</th>
                <td>
                    <fieldset class="url-box-border">
                        <legend class="screen-reader-text"><span>Share URL Box:</span></legend>
                        <label for="branded_sharebox_url_box_enabled"><input name="branded_sharebox_url_box[enabled]" type="checkbox" id="branded_sharebox_url_box_enabled" value="1" <?php checked($branded_sharebox_url_box['enabled'])??false; ?>>
                            Enable</label><br>
                        <label for="branded_sharebox_url_box_position">
                            <p class="description">Position Text</p>
                            <select name="branded_sharebox_url_box[position]" id="branded_sharebox_url_box_position">
                                <option <?php selected( 'top' == $branded_sharebox_url_box['position']) ?> value="top">Top</option>
                                <option <?php selected( 'bottom' == $branded_sharebox_url_box['position']) ?> value="bottom">Bottom</option>
                                <option <?php selected( 'left' == $branded_sharebox_url_box['position']) ?> value="left">Left</option>
                                <option <?php selected( 'right' == $branded_sharebox_url_box['position']) ?> value="right">Right</option>
                            </select>
                            <!-- <input name="branded_sharebox_url_box[position]" type="text" id="branded_sharebox_url_box_position" value="<?php echo ($branded_sharebox_url_box['position'])??""; ?>"> -->
                        </label><br>
                        <div class="url-box-border-options" <?php if (!$branded_sharebox_url_box['enabled']) echo "style='display:none'"; ?>>
                        
                            <label for="branded_sharebox_url_box_label">
                                <p class="description">Label Text</p><input name="branded_sharebox_url_box[label]" type="text" id="branded_sharebox_url_box_label" value="<?php echo ($branded_sharebox_url_box['label'])??""; ?>">
                            </label><br>
                            <label for="branded_sharebox_url_box_border_width">
                                <p class="description">Border Width</p> <input name="branded_sharebox_url_box[border][width]" style="text-align: right;width: 3rem;" type="number" id="branded_sharebox_url_box_border_width" value="<?php echo  $branded_sharebox_url_box['border']['width'] ?>">px
                            </label><br>
                            <p class="description"> Select Border Color </p>
                            <label for="branded_sharebox_url_box_border_color_custom_text" class="branded_sharebox_url_box_border_color_custom_label"><input type="text" name="branded_sharebox_url_box[border][color]" class="branded_sharebox_url_box_border_color_custom" id="" value="<?php echo $branded_sharebox_url_box['border']['color'] ?>">
                                <input type="hidden" style="padding:0;cursor:pointer" id="branded_sharebox_url_box_border_color_custom_text" data-default-color="<?php echo $branded_sharebox_url_box['border']['color'] ?>" value="<?php echo $branded_sharebox_url_box['border']['color'] ?>">
                            </label><br>
                        </div>
                    </fieldset>
                </td>
            </tr>

        </table>
        <?php submit_button(); ?>
    </form>
</div>