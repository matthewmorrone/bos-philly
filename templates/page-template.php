<?php
$args['name'] = query()["name"];
$args['post_type'] = "page";
$query = new WP_Query($args);
$posts = $query->get_posts();
$page = $posts[0];
?>
<div style="
display: flex;
align-items: center;
justify-content: center;
"><div id="cssCage" style="display: block;"><?php
@get_header();
?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const styleSheet = document.getElementById('global-styles-inline-css');
    if (!styleSheet || !styleSheet.sheet) return;
    let ruleList = styleSheet.sheet.cssRules;
    for (let i = 0; i < ruleList.length; i++) {
        ruleList[i].selectorText = "#cssCage " + ruleList[i].selectorText;
    }
    for (let i = 0; i < ruleList.length; i++) {
        console.log(ruleList[i].selectorText);
    }
});
</script>
<?php
apply_filters('the_content', get_post_field('post_content', $page->id));
print_r($page->post_content);
?></div></div>
