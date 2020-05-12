<?php 
$GLOBALS['bs_share_links'] = array(
    'facebook'       =>     "https://www.facebook.com/sharer.php?t=Onboarding%20CMP%20-%20ShareThis&u=https%3A%2F%2Fwww.sharethis.com%2F",
    'twitter'        =>     "https://twitter.com/intent/tweet?text={%share_title}&url=https%3A%2F%2F{$share_url}%2F",
    'pinterest'      =>     "https://www.pinterest.com/pin/create/button/?url=https%3A%2F%2F{$share_url}%2F&text={$share_title}",
    'linkedin'       =>     "https://www.linkedin.com/shareArticle?title=Onboarding%20CMP%20-%20ShareThis&url=https%3A%2F%2F{$share_url}%2F&text={$share_title}",
    'reddit'         =>     "https://www.reddit.com/submit?url=https%3A%2F%2F{$share_url}%2F&text={$share_title}",
    'tumblr'         =>     "https://www.tumblr.com/share?&u=https%3A%2F%2F{$share_url}%2F&v=3&text={$share_title}",
    'whatsapp'       =>     "https://web.whatsapp.com/send?text=https%3A%2F%2F{$share_url}%2F&text={$share_title}",
    'messenger'      =>     "https://www.facebook.com/dialog/send?link=https%3A%2F%2F{$share_url}%2F&app_id=521270401588372&redirect_uri=https%3A%2F%2F{$share_url}&text={$share_title}",
    'telegram'       =>     "https://t.me/share/url?url=https%3A%2F%2F{$share_url}%2F&text={$share_title}",
);

?>

<div>
    <h1>Branded Sharebox</h1>
    <form method="post" action="options.php">
        <?php settings_fields('shorten_settings');
        do_settings_sections('shorten_settings') ?>
        <table class="form-table" role="presentation">
            <tr valign="top">
                <th scope="row"><label for="shorten_api_key">API key</label></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span>Show on:</span></legend>
                        <p class="description">Select which socials networks to include</p>
                        <?php
                        $slected_links_list = get_option( 'shorten_url_box' );var_dump($slected_link_list);
                        foreach (array_keys($GLOBALS['bs_share_links']) as $link) : ?>
                            <label for="shorten_selected_link_<?php echo $link ?>"><input name="shorten_url_box[<?php echo $link ?>]" type="checkbox" id="shorten_selected_link_<?php echo $link ?>" value="1" <?php checked(isset($slected_link_list[$link])); ?>>
                                <?php echo ucfirst($link); if($link=='messenger')$link = "facebook-messenger";echo " <i class='fab fa-$link'></i>"?></label><br>
                        <?php endforeach; ?>
                    </fieldset>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>