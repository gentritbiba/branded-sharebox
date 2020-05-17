<?php
 add_action( 'wp_ajax_branded_sharebox_gen_mass_url', 'branded_sharebox_gen_mass_url' );
 add_action( 'wp_ajax_nopriv_branded_sharebox_gen_mass_url', 'branded_sharebox_gen_mass_url' );
 add_action( 'wp_ajax_branded_sharebox_link_click_counter', 'branded_sharebox_link_click_counter' );
 add_action( 'wp_ajax_nopriv_branded_sharebox_link_click_counter', 'branded_sharebox_link_click_counter' );

 function branded_sharebox_link_click_counter(){
    if (isset($_POST['action']) && $_POST['action'] == 'branded_sharebox_link_click_counter' 
    &&  isset($_POST['post_id']) && wp_verify_nonce($_POST['nonce'], 'branded_sharebox_link_click_counter_' . $_POST['post_id'])) {
        $real_id = (int)filter_var($_POST['real_id'],FILTER_VALIDATE_INT);
        $count = get_post_meta($real_id, 'branded_sharebox_link_click_counter', true);
        update_post_meta($real_id, 'branded_sharebox_link_click_counter', ($count === '' ? 1 : $count + 1));
    }
    wp_die();
    
}
function branded_sharebox_gen_mass_url(){
    if (isset($_POST['action']) && $_POST['action'] == 'branded_sharebox_gen_mass_url' && isset($_POST['nonce']) &&  isset($_POST['post_type']) && wp_verify_nonce($_POST['nonce'], 'gen_mass_url')) {

        $args = array(
            'post_type' => sanitize_text_field($_POST['post_type']),
            'meta_query' => array(
                array(
                    'key' => 'branded_sharebox_url',
                    'compare' => 'NOT EXISTS' // this should work...
                ),
            ),
            'posts_per_page' => -1
        );
        $posts = get_posts($args);
        $counter = 0;
        foreach ($posts as $post) {
            $api_key = get_option('branded_sharebox_api_key');
            $domain = get_option('branded_sharebox_domain');
            $generated_url = generate_branded_sharebox_url($api_key, $domain, get_permalink($post->ID), get_the_title( $post->ID ));
            $up = false;
            // $err =
            if ($generated_url->fullUrl) {
                $up = $generated_url->fullUrl ? update_post_meta($post->ID, 'branded_sharebox_url', $generated_url->fullUrl) : false;
            } else {
                $err = $generated_url['error'] ?? "";
                break;
            }
            // $up = delete_post_meta($post->ID, 'branded_sharebox_url');
            if ($up) {
                $counter++;
            }
        }
        $total_posts = count($posts);
        if ($counter == $total_posts) {
            echo json_encode(array(
                'success'   => true,
                'updated'   => $counter
            ));
        } else {
            echo json_encode(array(
                'failed'    => true,
                'total'     => $total_posts,
                'updated'   => $counter,
                'error'   => $err,
            ));
        }
    }
    wp_die();
}

add_action('wp_head', 'branded_sharebox_link_click_head');
function branded_sharebox_link_click_head()
{
    global $post;

    if (isset($post->ID)) {
        ?>
        <script type="text/javascript">
            jQuery(function($) {
                $('.share-social-buttons-wrapper a').on('click', function() {
                    event.preventDefault();
                    var self = $(this);
                    var cc_ajax_options = {
                        action: 'branded_sharebox_link_click_counter',
                        nonce: '<?php echo wp_create_nonce('branded_sharebox_link_click_counter_' . $post->ID); ?>',
                        ajaxurl: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                        post_id: '<?php echo $post->ID; ?>',
                        real_id: self.data('post-id')
                    };
                    if (!localStorage.getItem('branded_sharebox_prevent_clicked_share')) {
                        localStorage.setItem('branded_sharebox_prevent_clicked_share', true);
                        setTimeout(function() {
                            localStorage.removeItem('branded_sharebox_prevent_clicked_share');
                        }, 2000);
                        $.post(cc_ajax_options.ajaxurl, cc_ajax_options, function() {
                            window.open(self.attr('href'), 'mywindow', 'location=no,status=1,scrollbars=1,width=500,height=650');
                        });
                    }
                    return false;
                });

            });
        </script>
<?php
    }
}
?>