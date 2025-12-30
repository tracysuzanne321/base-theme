<?php
/**
 * About Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 * @param array $context The context provided to the block by the parent block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'about-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'about-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get fields - try multiple field name variations
$title = get_field('about_title') ?: get_field('title') ?: '';
$subtitle = get_field('about_subtitle') ?: get_field('subtitle') ?: '';
$text_content = get_field('about_text') ?: get_field('text') ?: get_field('content') ?: '';
$features = get_field('about_features') ?: get_field('features') ?: array(); // Repeater field
$image = get_field('about_image') ?: get_field('image') ?: '';
$image_position = get_field('about_image_position') ?: 'right'; // 'left' or 'right'
$background_color = get_field('about_background_color') ?: '#1a3a5f'; // Default dark blue
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" style="background-color: <?php echo esc_attr($background_color); ?>;">
    <div class="about-wrapper" id="about">
        <div class="about-content <?php echo esc_attr($image_position === 'left' ? 'about-content-right' : 'about-content-left'); ?>">
            <?php if ($title): ?>
                <h2 class="about-title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            <?php if ($subtitle): ?>
                <h3 class="about-subtitle"><?php echo esc_html($subtitle); ?></h3>
            <?php endif; ?>
            <?php if ($text_content): ?>
                <div class="about-text">
                    <?php echo wp_kses_post(wpautop($text_content)); ?>
                </div>
            <?php endif; ?>
            
            <?php 
            // Get features - try both get_field array and have_rows methods
            $features_array = array();
            
            if ($features && is_array($features) && count($features) > 0) {
                $features_array = $features;
            } else {
                // Try have_rows method
                $feature_fields = array('about_features', 'features', 'about_items');
                foreach ($feature_fields as $field_name) {
                    if (have_rows($field_name)) {
                        while (have_rows($field_name)) {
                            the_row();
                            $feature_icon = get_sub_field('about_feature_icon') ?: get_sub_field('feature_icon') ?: get_sub_field('icon') ?: '';
                            $feature_text = get_sub_field('about_feature_text') ?: get_sub_field('feature_text') ?: get_sub_field('text') ?: get_sub_field('item') ?: '';
                            if (!empty($feature_text)) {
                                $features_array[] = array(
                                    'icon' => $feature_icon,
                                    'text' => $feature_text
                                );
                            }
                        }
                        break;
                    }
                }
            }
            
            if (count($features_array) > 0): ?>
                <ul class="about-features">
                    <?php foreach ($features_array as $feature): 
                        $feature_icon = $feature['about_feature_icon'] ?? $feature['feature_icon'] ?? $feature['icon'] ?? '';
                        $feature_text = $feature['about_feature_text'] ?? $feature['feature_text'] ?? $feature['text'] ?? $feature['item'] ?? '';
                        if (empty($feature_text)) continue;
                    ?>
                        <li class="about-feature-item">
                            <?php if ($feature_icon): ?>
                                <i class="<?php echo esc_attr($feature_icon); ?> about-feature-icon"></i>
                            <?php else: ?>
                                <i class="fas fa-check-circle about-feature-icon"></i>
                            <?php endif; ?>
                            <span><?php echo esc_html($feature_text); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <?php if ($image): ?>
            <div class="about-image <?php echo esc_attr($image_position === 'left' ? 'about-image-left' : 'about-image-right'); ?>">
                <?php 
                $image_url = is_array($image) ? $image['url'] : $image;
                $image_alt = is_array($image) ? ($image['alt'] ?? '') : '';
                ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
            </div>
        <?php endif; ?>
    </div>
</section>



