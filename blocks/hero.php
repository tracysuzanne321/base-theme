<?php
/**
 * Hero Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 * @param array $context The context provided to the block by the parent block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'hero-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Get fields - using correct ACF field names
$title = get_field('hero_title') ?: get_field('title') ?: '';
$subtitle = get_field('hero_subtitle') ?: get_field('subtitle') ?: '';
$text = get_field('hero_text') ?: get_field('text') ?: get_field('description') ?: '';
$hero_image = get_field('hero_image') ?: get_field('image') ?: get_field('background_image');
$secondary_image = get_field('secondary_image');

// Determine layout - if secondary image exists, use side-by-side layout
$has_secondary_image = !empty($secondary_image);
$layout_class = $has_secondary_image ? 'hero-layout-side-by-side' : 'hero-layout-centered';

// Get button fields - ACF Link fields return an array
$primary_button_text = get_field('primary_button_text') ?: '';
$primary_button_link = get_field('primary_button_link'); // Returns array for Link field
$secondary_button_text = get_field('secondary_button_text') ?: '';
$secondary_button_link = get_field('secondary_button_link'); // Returns array for Link field

// Create class attribute allowing for custom "className" and "align" values.
$className = 'hero-block ' . $layout_class;
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" 
    <?php if ($hero_image): ?>
        <?php 
        // Handle both array (image object) and URL string formats
        $image_url = is_array($hero_image) ? $hero_image['url'] : $hero_image;
        ?>
        style="background-image: url('<?php echo esc_url($image_url); ?>');"
    <?php endif; ?>>
    
    <?php if ($hero_image && !$has_secondary_image): ?>
        <div class="hero-overlay"></div>
    <?php endif; ?>
    
    <div class="hero-wrapper" id="hero">
        <div class="hero-content">
        <?php if ($title): ?>
            <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
        <?php endif; ?>
        
        <?php if ($subtitle): ?>
            <h2 class="hero-subtitle"><?php echo esc_html($subtitle); ?></h2>
        <?php endif; ?>
        
        <?php if ($text): ?>
            <p class="hero-description"><?php echo esc_html($text); ?></p>
        <?php endif; ?>
        
        <?php 
        // Primary Button
        // ACF Link fields return an array, so we need to check if it exists and extract the URL
        $primary_link_url = '';
        $primary_link_target = '';
        if ($primary_button_link && is_array($primary_button_link)) {
            $primary_link_url = $primary_button_link['url'] ?? '';
            $primary_link_target = !empty($primary_button_link['target']) ? ' target="' . esc_attr($primary_button_link['target']) . '"' : '';
        } elseif ($primary_button_link && is_string($primary_button_link)) {
            $primary_link_url = $primary_button_link;
        }
        
        // Use button text from field, or fallback to link title if available
        $primary_button_display_text = $primary_button_text;
        if (empty($primary_button_display_text) && $primary_button_link && is_array($primary_button_link)) {
            $primary_button_display_text = $primary_button_link['title'] ?? '';
        }
        
        // Secondary Button
        $secondary_link_url = '';
        $secondary_link_target = '';
        if ($secondary_button_link && is_array($secondary_button_link)) {
            $secondary_link_url = $secondary_button_link['url'] ?? '';
            $secondary_link_target = !empty($secondary_button_link['target']) ? ' target="' . esc_attr($secondary_button_link['target']) . '"' : '';
        } elseif ($secondary_button_link && is_string($secondary_button_link)) {
            $secondary_link_url = $secondary_button_link;
        }
        
        // Use button text from field, or fallback to link title if available
        $secondary_button_display_text = $secondary_button_text;
        if (empty($secondary_button_display_text) && $secondary_button_link && is_array($secondary_button_link)) {
            $secondary_button_display_text = $secondary_button_link['title'] ?? '';
        }
        
        // Only show button container if at least one button exists
        if (($primary_button_display_text && $primary_link_url) || ($secondary_button_display_text && $secondary_link_url)): ?>
            <div class="hero-buttons">
                <?php if ($primary_button_display_text && $primary_link_url): ?>
                    <a href="<?php echo esc_url($primary_link_url); ?>" class="hero-button hero-button-primary"<?php echo $primary_link_target; ?>>
                        <?php echo esc_html($primary_button_display_text); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($secondary_button_display_text && $secondary_link_url): ?>
                    <a href="<?php echo esc_url($secondary_link_url); ?>" class="hero-button hero-button-secondary"<?php echo $secondary_link_target; ?>>
                        <?php echo esc_html($secondary_button_display_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        </div>
        
        <?php if ($has_secondary_image): ?>
            <div class="hero-secondary-image">
                <?php 
                $secondary_image_url = is_array($secondary_image) ? $secondary_image['url'] : $secondary_image;
                $secondary_image_alt = is_array($secondary_image) ? ($secondary_image['alt'] ?? '') : '';
                ?>
                <img src="<?php echo esc_url($secondary_image_url); ?>" alt="<?php echo esc_attr($secondary_image_alt); ?>" />
            </div>
        <?php endif; ?>
    </div>
</section>

