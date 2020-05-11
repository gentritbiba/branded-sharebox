<?php
/**
 * Plugin Name: Branded Sharebox 
 * Description: Branded Sharebox adds a custom brand URL social sharing box with share counter
 * Plugin URI: https://shorten.rest
 * Author: Shorten.REST
 * Version: 0.1
 */
define("SHORTEN_PLUGIN_DIR",plugin_dir_path( __FILE__ ) );
define("SHORTEN_PLUGIN_URL",plugin_dir_url( __FILE__ ) );
require_once('ajax.php');
require_once('admin-options.php');

function bs_content_has_blocks() {
    return false !== strpos( $content, '<!-- wp:' );
}


// Displays social share buttons on the front end
add_filter( "the_content", "shorten_adjacent_post_content" );
function shorten_adjacent_post_content($content){

    // if(!(in_the_loop() && is_main_query())  )return $content;
    $id = get_the_ID();
    $should_flaot = get_option( 'shorten_should_float' );
    if($should_flaot && !is_singular( ))return $content;

    

    if(!get_option( 'shorten_show_non_singular' ) && !is_singular( )) return $content;

    $shorten_url = get_post_meta($id, 'shorten_url',true);
    $shorten_show_on = get_option( 'shorten_show_on' );

    if (in_array(get_post_type( ), safe_array_keys($shorten_show_on) )&& $shorten_url) {  
        
        $clickCount = get_post_meta( $id,'link_click_counter',true );
        $style = "";
        $buttonClassList = "";
        $wrapperClassList = "";
        switch(get_option( 'shorten_icon_color')){
            case 'brand': break;
            case 'greyscale': $style.= "background-color:#666 !important";break;
            case 'custom': $style.= "background-color:". get_option( 'shorten_icon_color_custom') . "!important;";break;
        }
        
        switch(get_option( 'shorten_icon_size')){
            case 'small': break;
            case 'medium': $buttonClassList.= " md";break;
            case 'large': $buttonClassList.=" lg";break;
            case 'custom': 
                $custom_size = get_option('shorten_icon_size_custom');
                $style.=" font-size:{$custom_size}px; width:{$custom_size}px; padding:" . $custom_size*2/3 ."px;";break;
            default: $buttonClassList.= " md";
        }
        switch(get_option( 'shorten_align_where')){
            case 'start': $wrapperClassList.=" bs-align-start"; break;
            case 'center': $wrapperClassList.= " bs-align-center";break;
            case 'end': $wrapperClassList.=" bs-align-end";break;
            default: $wrapperClassList.= " bs-align-center";
        }
        if($should_flaot) $wrapperClassList.=" bs-pos-fixed";
        
        $shareButtons = "";
        $shareButtons .= "<div class='share-social-buttons-wrapper $wrapperClassList'>";
        $shareButtons .= $clickCount?"
            <div class='bs-total'>
                <span class='bs-label'>$clickCount</span>
                <span class='bs-shares'>
                Shares
            </span>
        </div>":"";
        $shareButtons .= "
        <a class='social-popup' href='https://www.facebook.com/sharer/sharer.php?u=https://$shorten_url' data-post-id='$id' target='_blank' rel='noopener'>
            <i class='fa social fa-facebook $buttonClassList' style='$style'></i>
        </a>";
        $shareButtons .= "
        <a href='http://twitter.com/share?url=https://$shorten_url' class='social-popup' target='_blank'>
            <i class='fa social fa-twitter $buttonClassList' style='$style'></i>
        </a>";
    
        $shareButtons .= "</div>";

        switch(get_option( 'shorten_show_at' )){
            case "both": $content = ($content?("<div class='bs-f-top'>" . $shareButtons . "</div>"):"") . $content . ("<div class='bs-f-bottom'>" . $shareButtons . "</div>");break;
            case "top" : $content = ("<div class='bs-f-top'>" . $shareButtons . "</div>") . $content;break;
            case "bottom" : $content .=("<div class='bs-f-bottom'>" . $shareButtons . "</div>");break;
            default : $content .=$shareButtons;
        }

    }
        
        return $content;
    }



    


// Enqueue scripts
function shorten_enqueue_scripts($hook) {
    wp_enqueue_script( 'font-awesome',"https://kit.fontawesome.com/14875fd6e4.js" , array('jquery'),1.0,false );
    wp_enqueue_script( 'bs-main-front-end',SHORTEN_PLUGIN_URL . 'assets/js/main-front-end.js' , array('jquery'),1.0,false );
    wp_enqueue_style( 'bs-fontawesome', SHORTEN_PLUGIN_URL . 'assets/css/style.css' );
    wp_enqueue_style( 'wp-color-picker' );

  }
  
  add_action('wp_enqueue_scripts', 'shorten_enqueue_scripts');

// Updates post meta when a new post is created
add_action('save_post', 'shorten_generate_url_on_post_update');
function shorten_generate_url_on_post_update($post_id) {
    // This function is executed everytime a post is updated
    // so it checks if the post has already a shorten url so it does not generate a new one
    $shorten_url = get_post_meta($post_id, 'shorten_url', true);
    $shorten_show_on = get_option( 'shorten_show_on' );
    if( !( $shorten_url ) && in_array(get_post_type( $post_id),safe_array_keys($shorten_show_on))) {
        //Add the new url to the post data 
        $api_key = get_option( 'shorten_api_key' );
        $domain = get_option( 'shorten_domain' );
        $generated_url = generate_shorten_url($api_key, $domain, get_permalink($post_id));
        if(!$generated_url->error){
            update_post_meta($post_id, 'shorten_url',$generated_url->fullUrl );
        }
    }
}

// Add shorten URL 
add_action( 'add_meta_boxes', 'add_shorten_url_to_editor_sidebar' );
function add_shorten_url_to_editor_sidebar(){

        add_meta_box(
            'shorten_url',
            'Shorten Url',
            'showShortenUrl',
            safe_array_keys(get_option( 'shorten_show_on' )),
            'side' ); 
}

function showShortenUrl(){
    $shorten_url = get_post_meta(get_the_ID(  ),'shorten_url',true);
    if($shorten_url)
    echo "<a href='https://$shorten_url'>$shorten_url</a>";
    else 
        echo "<p>There is no shortened URL for this ". get_post_type_object(get_post_type())->labels->singular_name . "</p>";
}

function generate_shorten_url($api_key, $domain, $destination){

    // echo SHORTEN_PLUGIN_DIR .'HTTP/Request2.php';
    require_once(SHORTEN_PLUGIN_DIR . 'Net/URL2.php');
    require_once(SHORTEN_PLUGIN_DIR .'HTTP/Request2.php');


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
    $request->setBody('{"destinations": [{"url": "'.$destination.'", "country": null, "os": null}]}');
    try {
    $response = $request->send();
    if ($response->getStatus() == 200) {
        $returnObj = json_decode($response->getBody());
        $returnObj->domain = $domain;
        $returnObj->fullUrl = $domain . '/' . $returnObj->aliasName;
        return $returnObj;
    }
    else {
        return array('error'=> 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
        $response->getReasonPhrase());
    }
    }
    catch(HTTP_Request2_Exception $e) {
        return array('error' => $e->getMessage());
    }

}


function safe_array_keys($arr){
    if(!$arr)return array();
    return array_keys($arr);
}