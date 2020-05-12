<?php
$path = preg_replace('/wp-content.*$/', '', __DIR__);
include_once($path . 'wp-load.php');

if (isset($_POST['action']) && $_POST['action'] == 'link_click_counter' && isset($_POST['nonce']) &&  isset($_POST['post_id']) && wp_verify_nonce($_POST['nonce'], 'link_click_counter_' . $_POST['post_id'])) {
    $count = get_post_meta($_POST['real_id'], 'link_click_counter', true);
    update_post_meta($_POST['real_id'], 'link_click_counter', ($count === '' ? 1 : $count + 1));
}
if (isset($_POST['action']) && $_POST['action'] == 'bs_gen_mass_url' && isset($_POST['nonce']) &&  isset($_POST['post_type']) && wp_verify_nonce($_POST['nonce'], 'gen_mass_url')) {

    $args = array(
        'post_type' => $_POST['post_type'],
        'meta_query' => array(
            array(
                'key' => 'shorten_url',
                'compare' => 'NOT EXISTS' // this should work...
            ),
        )
    );
    $posts = get_posts($args);
    $counter = 0;
    foreach ($posts as $post) {
        $api_key = get_option('shorten_api_key');
        $domain = get_option('shorten_domain');
        $generated_url = generate_shorten_url($api_key, $domain, get_permalink($post_id));
        $up = false;
        // $err =
        if ($generated_url->fullUrl) {
            $up = $generated_url->fullUrl ? update_post_meta($post->ID, 'shorten_url', $generated_url->fullUrl) : false;
        } else {
            $err = $generated_url['error'] ?? "";
            break;
        }
        // $up = delete_post_meta($post->ID, 'shorten_url');
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


add_action('wp_head', 'link_click_head');
function link_click_head()
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
                        action: 'link_click_counter',
                        nonce: '<?php echo wp_create_nonce('link_click_counter_' . $post->ID); ?>',
                        ajaxurl: '<?php echo SHORTEN_PLUGIN_URL . 'ajax.php' ?>',
                        post_id: '<?php echo $post->ID; ?>',
                        real_id: self.data('post-id')
                    };
                    if (!localStorage.getItem('bs_prevent_clicked_share')) {
                        localStorage.setItem('bs_prevent_clicked_share', true);
                        setTimeout(function() {
                            localStorage.removeItem('bs_prevent_clicked_share');
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