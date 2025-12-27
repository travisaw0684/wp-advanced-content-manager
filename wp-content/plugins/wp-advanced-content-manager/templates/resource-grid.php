<?php
$categories = get_terms([
    'taxonomy' => 'acm_resource_category',
    'hide_empty' => true,
]);
?>

<div class="acm-filters">
    <select id="acm-category">
        <option value="">All Categories</option>
        <?php foreach ( $categories as $cat ) : ?>
            <option value="<?php echo esc_attr( $cat->slug ); ?>">
                <?php echo esc_html( $cat->name ); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select id="acm-type">
        <option value="">All Types</option>
        <option value="article">Article</option>
        <option value="video">Video</option>
        <option value="pdf">PDF</option>
    </select>
</div>

<div id="acm-results"></div>
