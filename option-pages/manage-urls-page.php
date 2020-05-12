<?php

global $wpdb;
$GLOBALS['shortened_post_count'] = $wpdb->get_results(
    "SELECT p.post_type as post_type, count(*) as total_posts 
  FROM {$wpdb->prefix}posts as p  INNER  JOIN {$wpdb->prefix}postmeta as pd
  on p.ID = pd.post_id
  WHERE pd.meta_key = 'shorten_url' and p.post_status = 'publish'
  GROUP BY p.post_type",
    ARRAY_A
);
$GLOBALS['post_count'] = $wpdb->get_results(
    "SELECT post_type, count(*) as total_posts FROM {$wpdb->prefix}posts
  WHERE post_status = 'publish'
  GROUP BY post_type",
    ARRAY_A
);
function shorten_post_count($post_type)
{
    foreach ($GLOBALS['shortened_post_count'] as $post) {
        if ($post['post_type'] == $post_type) {
            $returnArr = $post['total_posts'];
        }
    }
    return $returnArr;
}

?>
<div>
    <?php screen_icon(); ?>
    <h1>Manage URLs</h1>
    <?php settings_fields('shorten_settings_social_links');
    do_settings_sections('shorten_settings_social_links') ?>
    <table class="form-table bs-table" role="presentation">
        <tr>
            <th>Post Type</th>
            <th>Shortened URL posts / All posts</th>
            <th>Posts left</th>
            <th></th>
        </tr>
        <?php
        $args = array('public' => true); // expected to get custom post types

        foreach ($GLOBALS['post_count'] as $post) {
            $shortened_post_count = shorten_post_count($post['post_type']) ?? '0';
            $difference = $post['total_posts'] - $shortened_post_count;
            $proportion = number_format($shortened_post_count / $post['total_posts'] * 100, 2, ',', ' ');
            echo "<tr>
            <td class='post-type'>{$post['post_type']}</td>
            <td class='bs-proportion'><span class='bs-proportion-inner'><span class='bs-numerator'>{$shortened_post_count}</span> / <span class='bs-denominator'>{$post['total_posts']}</span></span> <br> <span class='bs-proportion-percentage'>{$proportion}</span>%</td>
            <td class='bs-difference'>{$difference}</td>"
                . ($difference ? "<td><button class='button gen-url-btn' data-post-type='{$post['post_type']}'> Generate $difference URLs</button></td>" : "<td>Done</td>") .
                "</tr>";
            // var_dump($post_type);
        }
        ?>

    </table>
</div>



<script>
    jQuery(function($) {

        $('.gen-url-btn').on('click', function() {
            console.log("CLICKED!!");
            event.preventDefault();

            var self = $(this);
            var parent_td = self.parent();
            var old_parent_td_html = parent_td.html();
            var parent_tr = parent_td.parent();
            self.parent().html('<div class="loader"></div>');
            let gen_urls_ajax_options = {
                action: 'bs_gen_mass_url',
                nonce: '<?php echo wp_create_nonce('gen_mass_url'); ?>',
                ajaxurl: '<?php echo SHORTEN_PLUGIN_URL . 'ajax.php' ?>',
                post_type: self.data('post-type')
            };
            $.post(gen_urls_ajax_options.ajaxurl, gen_urls_ajax_options, function(data) {
                data = JSON.parse(data);
                if (data.success) {
                    let numerator = parent_tr.find('.bs-proportion-inner .bs-numerator');
                    let denominator = parent_tr.find('.bs-proportion-inner .bs-denominator');
                    numerator.text(parseInt(numerator.text()) + data.updated);
                    parent_tr.find('.bs-proportion-percentage').text((parseInt(numerator.text()) / parseInt(denominator.text()) * 100).toFixed(2));
                    parent_tr.find('.bs-difference').text(parseInt(numerator.text()) - parseInt(denominator.text()));
                    parent_td.html('Done');
                } else if (data.failed) {
                    //   window.alert("Somethign wrong with ajax request");
                    parent_td.html(old_parent_td_html);
                    window.alert(data.error);
                    console.log(data);
                }
            });
            return false;
        });
    })
</script>
<?php

unset($GLOBALS['shortened_post_count']);
unset($GLOBALS['post_count']);
