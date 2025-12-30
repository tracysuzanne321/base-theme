<?php
/**
 * Legal Page Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 * @param array $context The context provided to the block by the parent block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'legal-page-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'legal-page-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get fields - try multiple field name variations
$title = get_field('legal_title') ?: get_field('title') ?: '';
$last_updated = get_field('legal_last_updated') ?: get_field('last_updated') ?: '';
$content = get_field('legal_content') ?: get_field('content') ?: '';
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="legal-page-header">
        <?php if ($title): ?>
            <h1 class="legal-page-title"><?php echo esc_html($title); ?></h1>
        <?php endif; ?>
        
        <?php if ($last_updated): ?>
            <p class="legal-page-date">Last updated: <?php echo esc_html($last_updated); ?></p>
        <?php endif; ?>
    </div>
    
    <?php if ($content): ?>
        <div class="legal-page-content">
            <?php echo wp_kses_post($content); ?>
        </div>
    <?php endif; ?>
</section>

