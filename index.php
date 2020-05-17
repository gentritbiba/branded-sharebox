<?php

/**
 * Plugin Name: Branded Sharebox 
 * Description: Branded Sharebox adds a custom brand URL social sharing box with share counter
 * Plugin URI: https://shorten.rest
 * Author: shorten.REST
 * Author URI: https://shorten.rest
 * Version: 1.3
 */
define("BRANDED_SHAREBOX_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("BRANDED_SHAREBOX_PLUGIN_URL", plugin_dir_url(__FILE__));
require_once('admin-options.php');

add_shortcode('branded_sharebox','branded_sharebox_shortcode');
add_filter("the_content", "branded_sharebox_adjacent_post_content");





function branded_sharebox_content_has_blocks()
{
    return false !== strpos($content, '<!-- wp:');
}

$GLOBALS['branded_sharebox_share_links'] = array(
    'facebook'       =>     "https://www.facebook.com/sharer.php?t=SHARE_TITLE&u=https://SHARE_URL",
    'twitter'        =>     "https://twitter.com/intent/tweet?text=SHARE_TITLE&url=https://SHARE_URL",
    'pinterest'      =>     "https://www.pinterest.com/pin/create/button/?url=https://SHARE_URL&description=SHARE_TITLE",
    'linkedin'       =>     "https://www.linkedin.com/shareArticle?title=SHARE_TITLE&url=https://SHARE_URL&text=SHARE_TITLE",
    'reddit'         =>     "https://www.reddit.com/submit?url=https://SHARE_URL&title=SHARE_TITLE",
    'tumblr'         =>     "https://www.tumblr.com/share?&u=https://SHARE_URL&v=3&text=SHARE_TITLE",
    'whatsapp'       =>     "https://web.whatsapp.com/send?text=https://SHARE_URL&text=SHARE_TITLE",
    // 'messenger'      =>     "https://www.facebook.com/dialog/send?link=https://SHARE_URL&app_id=521270401588372&redirect_uri=https://SHARE_URL&text=SHARE_TITLE",
    'telegram'       =>     "https://t.me/share/url?url=https://SHARE_URL&text=SHARE_TITLE",
);
// Displays social share buttons on the front end
function branded_sharebox_adjacent_post_content($content)
{

    // if(!(in_the_loop() && is_main_query())  )return $content;
    $id = get_the_ID();
    $should_flaot = get_option('branded_sharebox_should_float');
    if ($should_flaot && !is_singular()) return $content;

    

    if (!get_option('branded_sharebox_show_non_singular') && !is_singular()) return $content;


    $share_title = get_the_title();
    $branded_sharebox_url = get_post_meta($id, 'branded_sharebox_url', true);
    $branded_sharebox_show_on = get_option('branded_sharebox_show_on');

    if (in_array(get_post_type(), branded_sharebox_safe_array_keys($branded_sharebox_show_on)) && $branded_sharebox_url) {

        $clickCount = get_post_meta($id, 'branded_sharebox_link_click_counter', true);
        $style = "font-family:FontAwesome !important";
        $buttonClassList = "";
        $wrapperClassList = "";
        switch (get_option('branded_sharebox_icon_color')) {
            case 'brand':
                break;
            case 'grayscale':
                $style .= " filter:grayscale(100%);";
                break;
            case 'custom':
                $style .= " background-color:" . get_option('branded_sharebox_icon_color_custom') . "!important;";
                break;
        }

        switch (get_option('branded_sharebox_icon_size')) {
            case 'small':
                break;
            case 'medium':
                $buttonClassList .= " md";
                break;
            case 'large':
                $buttonClassList .= " lg";
                break;
            case 'custom':
                $custom_size = get_option('branded_sharebox_icon_size_custom');
                if ($custom_size)
                    $style .= " font-size:{$custom_size}px; width:{$custom_size}px; padding:" . $custom_size * 2 / 3 . "px;";
                break;
            default:
                $buttonClassList .= " md";
        }
        switch (get_option('branded_sharebox_button_style')) {
            case 'round':
                $buttonClassList .= " icon-circle";
            case 'square':
                $buttonClassList .= " icon-square";
        }

        switch (get_option('branded_sharebox_align_where')) {
            case 'start':
                $wrapperClassList .= " bs-align-start";
                break;
            case 'center':
                $wrapperClassList .= " bs-align-center";
                break;
            case 'end':
                $wrapperClassList .= " bs-align-end";
                break;
            default:
                $wrapperClassList .= " bs-align-center";
        }
        if ($should_flaot) $wrapperClassList .= " bs-pos-fixed";

        $shareButtons = "";
        $shareButtons .= "<div class='share-social-buttons-wrapper $wrapperClassList'>";
        $shareBoxBorder = "";
        $shareBoxBorderStyle = "";
        $share_box_border = get_option('branded_sharebox_url_box');
        if ($share_box_border['enabled']) {
            if(in_array($share_box_border['position'], array('left', 'right'))) $shareBoxBorderStyle.= " width:auto; margin:0 5px;";
            else $shareBoxBorderStyle=" width:100%; margin:10px 0";
            $shareBoxBorder .= "<div class='bs-share-url-box-wrapper' style='$shareBoxBorderStyle'><b>{$share_box_border['label']}</b><div class='bs-share-url-box' style='border-width:{$share_box_border['border']['width']}px;border-color:{$share_box_border['border']['color']};'>
                <input readonly type='text' class='bs-share-url-box-link' value='https://$branded_sharebox_url'> <i onclick='bsCopyToClipboard(this)' class='far fa-copy bs-copy-btn'></i>
            </div></div>";
        }
        if ($share_box_border['enabled'] && in_array($share_box_border['position'], array('left', 'top')) ) {
            $shareButtons .= $shareBoxBorder;
        }        
        if (get_option('branded_sharebox_show_counter'))
            $shareButtons .= $clickCount ? "
            <div class='bs-total'>
                <span class='bs-label'>$clickCount</span>
                <span class='bs-shares'>
                Shares
            </span>
        </div>" : "";

        foreach (branded_sharebox_safe_array_keys(get_option('branded_sharebox_url_social_link')) as $social_buttons_link_name) {
            $share_url_link = str_replace("SHARE_URL", $branded_sharebox_url, $GLOBALS['branded_sharebox_share_links'][$social_buttons_link_name]);
            $share_url_link = str_replace("SHARE_TITLE", $share_title, $share_url_link);
            $fa_name = $social_buttons_link_name == "messenger" ? "facebook-messenger" : $social_buttons_link_name;
            $shareButtons .= "
            <a class='social-popup' href='$share_url_link' data-post-id='$id' target='_blank' rel='noopener'>
                <i class='fa social fa-$fa_name $buttonClassList' style='$style'></i>
            </a>";
        }
        if ($share_box_border['enabled'] && in_array($share_box_border['position'], array('bottom', 'right')) ) {
            $shareButtons .= $shareBoxBorder;
        }    
        // $shareButtons .= "
        // <a class='social-popup' href='https://www.facebook.com/sharer/sharer.php?u=https://$branded_sharebox_url' data-post-id='$id' target='_blank' rel='noopener'>
        //     <i class='fa social fa-facebook $buttonClassList' style='$style'></i>
        // </a>";
        // $shareButtons .= "
        // <a href='http://twitter.com/share?url=https://$branded_sharebox_url' class='social-popup' target='_blank'>
        //     <i class='fa social fa-twitter $buttonClassList' style='$style'></i>
        // </a>";
        $shareButtons .= "</div>";

        switch (get_option('branded_sharebox_show_at')) {
            case "both":
                $content = ($content ? ("<div class='bs-f-top'>" . $shareButtons . "</div>") : "") . $content . ("<div class='bs-f-bottom'>" . $shareButtons . "</div>");
                break;
            case "top":
                $content = ("<div class='bs-f-top'>" . $shareButtons . "</div>") . $content;
                break;
            case "bottom":
                $content .= ("<div class='bs-f-bottom'>" . $shareButtons . "</div>");
                break;
            default:
                $content .= $shareButtons;
        }
    }

    return $content;
}






// Enqueue scripts
function branded_sharebox_enqueue_scripts($hook)
{
    wp_enqueue_style('bs-font-awesome-css', "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style('bs-main-css', BRANDED_SHAREBOX_PLUGIN_URL . 'assets/css/style.css');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('bs-main-front-end', BRANDED_SHAREBOX_PLUGIN_URL . 'assets/js/main-front-end.js', array('jquery'), 1.0, false);
}

add_action('wp_enqueue_scripts', 'branded_sharebox_enqueue_scripts');

// Updates post meta when a new post is created
add_action('publish_post', 'branded_sharebox_generate_url_on_post_update');
add_action('add_attachment', 'branded_sharebox_generate_url_on_post_update');
function branded_sharebox_generate_url_on_post_update($post_id)
{
    // This function is executed everytime a post is updated
    // so it checks if the post has already a shorten url so it does not generate a new one
    $branded_sharebox_url = get_post_meta($post_id, 'branded_sharebox_url', true);
    $branded_sharebox_show_on = get_option('branded_sharebox_show_on');
    if (!($branded_sharebox_url) && in_array(get_post_type($post_id), branded_sharebox_safe_array_keys($branded_sharebox_show_on))) {
        //Add the new url to the post data 
        $api_key = get_option('branded_sharebox_api_key');
        $domain = get_option('branded_sharebox_domain');
        $generated_url = generate_branded_sharebox_url($api_key, $domain, get_permalink($post_id), get_the_title($post_id));
        if (!$generated_url->error) {
            update_post_meta($post_id, 'branded_sharebox_url', $generated_url->fullUrl);
        }
    }
}

// Add shorten URL 
add_action('add_meta_boxes', 'add_branded_sharebox_url_to_editor_sidebar');
function add_branded_sharebox_url_to_editor_sidebar()
{
    if (in_array(get_post_type(), branded_sharebox_safe_array_keys(get_option('branded_sharebox_show_on'))))
        add_meta_box(
            'branded_sharebox_url',
            'Shorten Url',
            'show_branded_sharebox_url',
            branded_sharebox_safe_array_keys(get_option('branded_sharebox_show_on')),
            'side'
        );
}

function show_branded_sharebox_url()
{

    $branded_sharebox_url = get_post_meta(get_the_ID(), 'branded_sharebox_url', true);
    if ($branded_sharebox_url)
        echo "<a href='https://$branded_sharebox_url'>$branded_sharebox_url</a>";
    else
        echo "<p>There is no branded_shareboxed URL for this " . get_post_type_object(get_post_type())->labels->singular_name . "</p>";
}

function generate_branded_sharebox_url($api_key, $domain, $destination, $title)
{

    // echo BRANDED_SHAREBOX_PLUGIN_DIR .'HTTP/Request2.php';
    require_once(BRANDED_SHAREBOX_PLUGIN_DIR . 'Net/URL2.php');
    require_once(BRANDED_SHAREBOX_PLUGIN_DIR . 'HTTP/Request2.php');
    if (!$domain) $domain = 'short.fyi';
    $request = new HTTP_Request2();
    $request->setUrl("https://api.shorten.rest/aliases?domainName=$domain&aliasName=@rnd");
    $request->setMethod(HTTP_Request2::METHOD_POST);
    $request->setConfig(array(
        'follow_redirects' => TRUE
    ));
    $request->setHeader(array(
        'x-api-key' => $api_key,
        'Content-Type' => 'application/json'
    ));
    $request->setBody('{"destinations": [{"url": "' . $destination . '", "country": null, "os": null}],"metatags" :[{"name":"og:title", "content" :"' . urlencode($title) . '"}]}');
    try {
        $response = $request->send();
        if ($response->getStatus() == 200) {

            $returnObj = json_decode($response->getBody());
            // var_dump($returnObj);
            $returnObj->domain = $domain;
            $returnObj->fullUrl = $returnObj->domain . '/' . $returnObj->aliasName;
            return $returnObj;
        } else {
            return array('error' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                $response->getReasonPhrase());
        }
    } catch (HTTP_Request2_Exception $e) {
        return array('error' => $e->getMessage());
    }
}


function branded_sharebox_safe_array_keys($arr)
{
    if (!$arr) return array();
    return array_keys($arr);
}


function branded_sharebox_shortcode(){
    $content = "";
    // if(!(in_the_loop() && is_main_query())  )return $content;
    $id = get_the_ID();
    // $should_flaot = get_option('branded_sharebox_should_float');
    // if ($should_flaot && !is_singular()) return $content;



    // if (!get_option('branded_sharebox_show_non_singular') && !is_singular()) return $content;
    // echo $id;

    $share_title = get_the_title();
    $branded_sharebox_url = get_post_meta($id, 'branded_sharebox_url', true);
    if(!$branded_sharebox_url)return;


        $clickCount = get_post_meta($id, 'branded_sharebox_link_click_counter', true);
        $style = "font-family:FontAwesome !important";
        $buttonClassList = "";
        $wrapperClassList = "";
        switch (get_option('branded_sharebox_icon_color')) {
            case 'brand':
                break;
            case 'grayscale':
                $style .= " filter:grayscale(100%);";
                break;
            case 'custom':
                $style .= " background-color:" . get_option('branded_sharebox_icon_color_custom') . "!important;";
                break;
        }

        switch (get_option('branded_sharebox_icon_size')) {
            case 'small':
                break;
            case 'medium':
                $buttonClassList .= " md";
                break;
            case 'large':
                $buttonClassList .= " lg";
                break;
            case 'custom':
                $custom_size = get_option('branded_sharebox_icon_size_custom');
                if ($custom_size)
                    $style .= " font-size:{$custom_size}px; width:{$custom_size}px; padding:" . $custom_size * 2 / 3 . "px;";
                break;
            default:
                $buttonClassList .= " md";
        }
        switch (get_option('branded_sharebox_button_style')) {
            case 'round':
                $buttonClassList .= " icon-circle";
            case 'square':
                $buttonClassList .= " icon-square";
        }

        switch (get_option('branded_sharebox_align_where')) {
            case 'start':
                $wrapperClassList .= " bs-align-start";
                break;
            case 'center':
                $wrapperClassList .= " bs-align-center";
                break;
            case 'end':
                $wrapperClassList .= " bs-align-end";
                break;
            default:
                $wrapperClassList .= " bs-align-center";
        }
        // if ($should_flaot) $wrapperClassList .= " bs-pos-fixed";

        $shareButtons = "";
        $shareButtons .= "<div class='share-social-buttons-wrapper $wrapperClassList'>";
        $shareBoxBorder = "";
        $shareBoxBorderStyle = "";
        $share_box_border = get_option('branded_sharebox_url_box');
        if ($share_box_border['enabled']) {
            if(in_array($share_box_border['position'], array('left', 'right'))) $shareBoxBorderStyle.= " width:auto; margin:0 5px;";
            else $shareBoxBorderStyle=" width:100%; margin:10px 0";
            $shareBoxBorder .= "<div class='bs-share-url-box-wrapper' style='$shareBoxBorderStyle'><b>{$share_box_border['label']}</b><div class='bs-share-url-box' style='border-width:{$share_box_border['border']['width']}px;border-color:{$share_box_border['border']['color']};'>
                <input readonly type='text' class='bs-share-url-box-link' value='https://$branded_sharebox_url'> <i onclick='bsCopyToClipboard(this)' class='far fa-copy bs-copy-btn'></i>
            </div></div>";
        }
        if ($share_box_border['enabled'] && in_array($share_box_border['position'], array('left', 'top')) ) {
            $shareButtons .= $shareBoxBorder;
        }        
        if (get_option('branded_sharebox_show_counter'))
            $shareButtons .= $clickCount ? "
            <div class='bs-total'>
                <span class='bs-label'>$clickCount</span>
                <span class='bs-shares'>
                Shares
            </span>
        </div>" : "";

        foreach (branded_sharebox_safe_array_keys(get_option('branded_sharebox_url_social_link')) as $social_buttons_link_name) {
            $share_url_link = str_replace("SHARE_URL", $branded_sharebox_url, $GLOBALS['branded_sharebox_share_links'][$social_buttons_link_name]);
            $share_url_link = str_replace("SHARE_TITLE", $share_title, $share_url_link);
            $fa_name = $social_buttons_link_name == "messenger" ? "facebook-messenger" : $social_buttons_link_name;
            $shareButtons .= "
            <a class='social-popup' href='$share_url_link' data-post-id='$id' target='_blank' rel='noopener'>
                <i class='fa social fa-$fa_name $buttonClassList' style='$style'></i>
            </a>";
        }
        if ($share_box_border['enabled'] && in_array($share_box_border['position'], array('bottom', 'right')) ) {
            $shareButtons .= $shareBoxBorder;
        }    
        // $shareButtons .= "
        // <a class='social-popup' href='https://www.facebook.com/sharer/sharer.php?u=https://$branded_sharebox_url' data-post-id='$id' target='_blank' rel='noopener'>
        //     <i class='fa social fa-facebook $buttonClassList' style='$style'></i>
        // </a>";
        // $shareButtons .= "
        // <a href='http://twitter.com/share?url=https://$branded_sharebox_url' class='social-popup' target='_blank'>
        //     <i class='fa social fa-twitter $buttonClassList' style='$style'></i>
        // </a>";
        $shareButtons .= "</div>";

        switch (get_option('branded_sharebox_show_at')) {
            case "both":
                $content = ($content ? ("<div class='bs-f-top'>" . $shareButtons . "</div>") : "") . $content . ("<div class='bs-f-bottom'>" . $shareButtons . "</div>");
                break;
            case "top":
                $content = ("<div class='bs-f-top'>" . $shareButtons . "</div>") . $content;
                break;
            case "bottom":
                $content .= ("<div class='bs-f-bottom'>" . $shareButtons . "</div>");
                break;
            default:
                $content .= $shareButtons;
        }

    return $content;
}
include_once('ajax.php');
 