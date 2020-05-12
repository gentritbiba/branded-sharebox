<div>
    <h1>Branded Sharebox</h1>
    <form method="post" action="options.php">
        <?php settings_fields('shorten_settings');
        do_settings_sections('shorten_settings') ?>
        <table class="form-table" role="presentation">
            <tr valign="top">
                <th scope="row"><label for="shorten_api_key">API key</label></th>
                <td><input type="text" id="shorten_api_key" name="shorten_api_key" value="<?php echo get_option('shorten_api_key'); ?>" class="regular-text" />
                    <p class="description" id="domain-description">API key from <a href="https://app.shorten.rest/settings">Shorten.REST</a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="shorten_domain">Domain</label></th>
                <td><input type="text" id="shorten_domain" name="shorten_domain" value="<?php echo get_option('shorten_domain'); ?>" class="regular-text" aria-describedby="domain-description" />
                    <p class="description" id="domain-description">Short domain that is already assigned to your account on <a href="https://app.shorten.rest/settings">Shorten.REST</a></p>
                </td>
            </tr>
            <?php $shorten_show_on = get_option('shorten_show_on'); ?>
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
                            <label for="shorten_show_on_<?php echo $post_type ?>"><input name="shorten_show_on[<?php echo $post_type ?>]" type="checkbox" id="shorten_show_on_<?php echo $post_type ?>" value="1" <?php checked(isset($shorten_show_on[$post_type])); ?>>
                                <?php echo get_post_type_object($post_type)->label ?></label><br>
                        <?php endforeach; ?>
                    </fieldset>
                </td>
            </tr>
            <?php $shorten_show_at = get_option('shorten_show_at'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Sharebox location:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Sharebox location</span></legend>
                        <label for="shorten_show_at_top"><input name="shorten_show_at" type="radio" id="shorten_show_at_top" value="top" <?php checked('top' == $shorten_show_at); ?>>
                            Before the content</label><br>
                        <label for="shorten_show_at_bottom"><input name="shorten_show_at" type="radio" id="shorten_show_at_bottom" value="bottom" <?php checked('bottom' == $shorten_show_at); ?>>
                            After the content</label><br>
                        <label for="shorten_show_at_both"><input name="shorten_show_at" type="radio" id="shorten_show_at_both" value="both" <?php checked('both' == $shorten_show_at); ?>>
                            Both</label><br>
                    </fieldset>
                </td>
            </tr>
            <?php $shorten_align_where = get_option('shorten_align_where'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Align buttons:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Align buttons</span></legend>
                        <label for="shorten_align_where_start"><input name="shorten_align_where" type="radio" id="shorten_align_where_start" value="start" <?php checked('start' == $shorten_align_where); ?>>
                            Left</label><br>
                        <label for="shorten_align_where_center"><input name="shorten_align_where" type="radio" id="shorten_align_where_center" value="center" <?php checked('center' == $shorten_align_where); ?>>
                            Center</label><br>
                        <label for="shorten_align_where_end"><input name="shorten_align_where" type="radio" id="shorten_align_where_end" value="end" <?php checked('end' == $shorten_align_where); ?>>
                            Right</label><br>
                    </fieldset>
                </td>
            </tr>

            <?php $shorten_button_style = get_option('shorten_button_style'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Button Style:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Button Style</span></legend>
                        <label for="shorten_button_style_round"><input name="shorten_button_style" type="radio" id="shorten_button_style_round" value="round" <?php checked('round' == $shorten_button_style); ?>>
                            Round</label><br>
                        <label for="shorten_button_style_square"><input name="shorten_button_style" type="radio" id="shorten_button_style_square" value="square" <?php checked('square' == $shorten_button_style); ?>>
                            Square</label><br>
                    </fieldset>
                </td>
            </tr>

            <?php $shorten_show_counter = get_option('shorten_show_counter'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Display the number of shares:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Display the number of shares</span></legend>
                        <label for="shorten_show_counter"><input name="shorten_show_counter" type="checkbox" id="shorten_show_counter" value="1" <?php checked($shorten_show_counter); ?>>
                            Yes</label>
                        <p class="description">Shot the number of shares next to the share buttons</p><br>
                    </fieldset>
                </td>
            </tr>
            <?php $shorten_should_float = get_option('shorten_should_float'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Show as sticky widget:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Show as sticky widget</span></legend>
                        <label for="shorten_should_float"><input name="shorten_should_float" type="checkbox" id="shorten_should_float" value="1" <?php checked($shorten_should_float); ?>>
                            Yes</label>
                        <p class="description">This will also disable "show non singular posts"</p><br>
                    </fieldset>
                </td>
            </tr>
            <?php $shorten_show_non_singular = get_option('shorten_show_non_singular'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Show non singular posts:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Show non singular posts</span></legend>
                        <label for="shorten_show_non_singular"><input name="shorten_show_non_singular" type="checkbox" id="shorten_show_non_singular" value="1" <?php disabled($shorten_should_float);
                            checked($shorten_show_non_singular  && !$shorten_should_float); ?>>
                            Yes</label>
                        <p class="description">e.g. show posts listed inside archive pages</p><br>
                    </fieldset>
                </td>
            </tr>
            <?php $shorten_icon_color = get_option('shorten_icon_color'); ?>
            <tr class="option-site-visibility button-color">
                <th scope="row">Social buttons icon color:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Social buttons icon color:</span></legend>
                        <p class="description">Select what color should the icons be</p>
                        <label for="shorten_icon_color_brand"><input name="shorten_icon_color" type="radio" id="shorten_icon_color_brand" value="brand" <?php checked('brand' == $shorten_icon_color); ?>>
                            Brand Color</label><br>
                        <label for="shorten_icon_color_grayscale"><input name="shorten_icon_color" type="radio" id="shorten_icon_color_grayscale" value="grayscale" <?php checked('grayscale' == $shorten_icon_color); ?>>
                            Greysale</label><br>
                        <label for="shorten_icon_color_custom"><input name="shorten_icon_color" type="radio" id="shorten_icon_color_custom" value="custom" <?php checked('custom' == $shorten_icon_color); ?>>
                            Custom</label><br>
                        <label for="shorten_icon_color_custom_text" class="shorten_icon_color_custom_label" <?php if ('custom' != $shorten_icon_color) echo 'style="display:none"' ?>><input type="text" style="padding:0;cursor:pointer" name="shorten_icon_color_custom" class="shorten_icon_color_custom" id="" value="<?php echo get_option('shorten_icon_color_custom') ?>">
                            <p class='description'>Select Color</p><input type="text" style="padding:0;cursor:pointer" id="shorten_icon_color_custom_text" value="<?php echo get_option('shorten_icon_color_custom') ?>">
                            <p class='description'></p>
                        </label><br>


                    </fieldset>
                </td>
            </tr>
            <?php $shorten_icon_size = get_option('shorten_icon_size'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Social buttons icon size:</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Social buttons icon size:</span></legend>
                        <p class="description">Select what size should the icons be</p>
                        <label for="shorten_icon_size_small"><input name="shorten_icon_size" type="radio" id="shorten_icon_size_small" value="small" <?php checked('small' == $shorten_icon_size); ?>>
                            Small</label><br>
                        <label for="shorten_icon_size_medium"><input name="shorten_icon_size" type="radio" id="shorten_icon_size_medium" value="medium" <?php checked('medium' == $shorten_icon_size); ?>>
                            Medium</label><br>
                        <label for="shorten_icon_size_large"><input name="shorten_icon_size" type="radio" id="shorten_icon_size_large" value="large" <?php checked('large' == $shorten_icon_size); ?>>
                            Large</label><br>
                        <label for="shorten_icon_size_custom"><input name="shorten_icon_size" type="radio" id="shorten_icon_size_custom" value="custom" <?php checked('custom' == $shorten_icon_size); ?>>
                            Custom</label><br>
                        <label for="shorten_icon_size_custom" class="shorten_icon_size_custom_label" <?php if ('custom' != $shorten_icon_size) echo 'style="display:none"' ?>><input type="number" style="text-align:right; width:3rem;" name="shorten_icon_size_custom" class="shorten_icon_size_custom" id="" value="<?php echo get_option('shorten_icon_size_custom') ?>">
                            <label>px</label>
                            <p class='description'>Select size in px</p>
                        </label><br>
                    </fieldset>
                </td>
            </tr>

            <?php $shorten_url_box = get_option('shorten_url_box'); ?>
            <tr class="option-site-visibility">
                <th scope="row">Share URL Box:</th>
                <td>
                    <fieldset class="url-box-border">
                        <legend class="screen-reader-text"><span>Share URL Box:</span></legend>
                        <label for="shorten_url_box_enabled"><input name="shorten_url_box[enabled]" type="checkbox" id="shorten_url_box_enabled" value="1" <?php checked($shorten_url_box['enabled'])??false; ?>>
                            Enable</label><br>
                        <label for="shorten_url_box_position">
                            <p class="description">Position Text</p>
                            <select name="shorten_url_box[position]" id="shorten_url_box_position">
                                <option <?php selected( 'top' == $shorten_url_box['position']) ?> value="top">Top</option>
                                <option <?php selected( 'bottom' == $shorten_url_box['position']) ?> value="bottom">Bottom</option>
                                <option <?php selected( 'left' == $shorten_url_box['position']) ?> value="left">Left</option>
                                <option <?php selected( 'right' == $shorten_url_box['position']) ?> value="right">Right</option>
                            </select>
                            <!-- <input name="shorten_url_box[position]" type="text" id="shorten_url_box_position" value="<?php echo ($shorten_url_box['position'])??""; ?>"> -->
                        </label><br>
                        <div class="url-box-border-options" <?php if (!$shorten_url_box['enabled']) echo "style='display:none'"; ?>>
                        
                            <label for="shorten_url_box_label">
                                <p class="description">Label Text</p><input name="shorten_url_box[label]" type="text" id="shorten_url_box_label" value="<?php echo ($shorten_url_box['label'])??""; ?>">
                            </label><br>
                            <label for="shorten_url_box_border_width">
                                <p class="description">Border Width</p> <input name="shorten_url_box[border][width]" style="text-align: right;width: 3rem;" type="number" id="shorten_url_box_border_width" value="<?php echo  $shorten_url_box['border']['width'] ?>">px
                            </label><br>
                            <p class="description"> Select Border Color </p>
                            <label for="shorten_url_box_border_color_custom_text" class="shorten_url_box_border_color_custom_label"><input type="text" name="shorten_url_box[border][color]" class="shorten_url_box_border_color_custom" id="" value="<?php echo $shorten_url_box['border']['color'] ?>">
                                <input type="hidden" style="padding:0;cursor:pointer" id="shorten_url_box_border_color_custom_text" data-default-color="<?php echo $shorten_url_box['border']['color'] ?>" value="<?php echo $shorten_url_box['border']['color'] ?>">
                            </label><br>
                        </div>
                    </fieldset>
                </td>
            </tr>

        </table>
        <?php submit_button(); ?>
    </form>
</div>