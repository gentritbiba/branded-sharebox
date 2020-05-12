<?php 

?>

<div>
    <h1>Branded Sharebox</h1>
    <form method="post" action="options.php">
        <?php settings_fields('shorten_settings_social_links');
        do_settings_sections('shorten_settings_social_links') ?>
        <table class="form-table" role="presentation">
            <tr valign="top">
                <th scope="row"><label for="shorten_url_social">Social Networks:</label></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span></span></legend>
                        <p class="description">Select which socials networks to include</p>
                        <?php  $slected_links_list = get_option( 'shorten_url_social_link' );?>
                        <tr class="option-site-visibility">
                            <th scope="row"></th>
                            <td>
                                <div class="bs_social_buttons_wrapper">
                                    <legend class="screen-reader-text"><span></span></legend>
                                    <?php
                                    $args = array(
                                        'public'   => true,
                                    );
                                    $lin = get_post_types($args);
                                    foreach (array_keys($GLOBALS['bs_share_links']) as $link) : ?>
                                    <input name="shorten_url_social_link[<?php echo $link ?>]" type="checkbox" id="shorten_url_social_link_<?php echo $link ?>" class="shorten_url_social_link_input" value="1" <?php checked(isset($slected_links_list[$link])); ?>>
                                        <label for="shorten_url_social_link_<?php echo $link ?>" class="<?php $temp_link = $link;if($temp_link=='messenger')$temp_link = "facebook-messenger";echo "fa-$temp_link"?>">
                                        <?php $label_text = ucfirst($link); if($link=='messenger')$link = "facebook-messenger";echo " <i class='fab social fa-$link'></i> $label_text"?></label><br>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    </fieldset>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>