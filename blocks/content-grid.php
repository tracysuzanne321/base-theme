<?php
/**
 * Content Grid Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 * @param array $context The context provided to the block by the parent block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'content-grid-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'content-grid-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get fields - try multiple field name variations
$title = get_field('grid_title') ?: get_field('title') ?: '';
$subtitle = get_field('grid_subtitle') ?: get_field('subtitle') ?: '';
$description = get_field('grid_description') ?: get_field('description') ?: '';
$items = get_field('grid_items') ?: get_field('items') ?: get_field('content_items') ?: array(); // Repeater field
$columns = get_field('grid_columns') ?: get_field('columns') ?: 3; // Number of columns (default 3)
$background_image = get_field('grid_background_image') ?: get_field('background_image');
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" 
    <?php if ($background_image): ?>
        <?php 
        $bg_image_url = is_array($background_image) ? $background_image['url'] : $background_image;
        ?>
        style="background-image: url('<?php echo esc_url($bg_image_url); ?>');"
    <?php endif; ?>>
    
    <?php if ($background_image): ?>
        <div class="content-grid-overlay"></div>
    <?php endif; ?>
    
    <div class="content-grid-wrapper" id="content-grid">
        <?php if ($title || $subtitle || $description): ?>
            <div class="content-grid-header">
                <?php if ($title): ?>
                    <h2 class="content-grid-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                
                <?php if ($subtitle): ?>
                    <h3 class="content-grid-subtitle"><?php echo esc_html($subtitle); ?></h3>
                <?php endif; ?>
                
                <?php if ($description): ?>
                    <p class="content-grid-description"><?php echo esc_html($description); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php 
        // Try to get items using have_rows if get_field didn't work
        $has_items = false;
        $items_array = array();
        
        // Reset any previous have_rows() loops
        if (function_exists('reset_rows')) {
            reset_rows();
        }
        
        // First try get_field (returns array) - this is the preferred method for blocks
        if ($items && is_array($items) && count($items) > 0) {
            $has_items = true;
            $items_array = $items;
        } 
        // If that didn't work, try have_rows (ACF repeater method)
        else {
            // Try different field names
            $repeater_fields = array('grid_items', 'items', 'content_items');
            foreach ($repeater_fields as $field_name) {
                if (have_rows($field_name)) {
                    while (have_rows($field_name)) {
                    the_row();
                    $items_array[] = array(
                        'item_icon' => get_sub_field('item_icon') ?: get_sub_field('icon') ?: get_sub_field('grid_item_icon') ?: '',
                        'item_title' => get_sub_field('item_title') ?: get_sub_field('title') ?: get_sub_field('grid_item_title') ?: '',
                        'item_description' => get_sub_field('item_description') ?: get_sub_field('description') ?: get_sub_field('grid_item_description') ?: '',
                        'item_link' => get_sub_field('item_link') ?: get_sub_field('link') ?: get_sub_field('grid_item_link') ?: '',
                    );
                    }
                    $has_items = count($items_array) > 0;
                    break; // Found the field, stop looking
                }
            }
        }
        
       
        
        if ($has_items && count($items_array) > 0): ?>
            <div class="content-grid-items" style="--grid-columns: <?php echo esc_attr($columns); ?>;">
                <?php foreach ($items_array as $item): 
                    // Get sub-field values - use correct field names from ACF
                    $item_icon = $item['item_icon'] ?? $item['icon'] ?? '';
                    $item_title = $item['item_title'] ?? $item['title'] ?? '';
                    $item_description = $item['item_description'] ?? $item['description'] ?? '';
                    $item_link = $item['item_link'] ?? $item['link'] ?? '';
                    
                    if (empty($item_title)) continue;
                ?>
                    <div class="content-grid-item">
                        <?php if ($item_icon): ?>
                            <div class="content-grid-item-icon">
                                <?php 
                                // Handle both image array and URL string
                                if (is_array($item_icon)) {
                                    $icon_url = $item_icon['url'] ?? '';
                                    $icon_alt = $item_icon['alt'] ?? '';
                                } else {
                                    $icon_url = $item_icon;
                                    $icon_alt = '';
                                }
                                ?>
                                <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($icon_alt); ?>" />
                            </div>
                        <?php endif; ?>
                        
                        <div class="content-grid-item-content">
                            <?php if ($item_link && is_array($item_link) && !empty($item_link['url'])): ?>
                                <h3 class="content-grid-item-title">
                                    <a href="<?php echo esc_url($item_link['url']); ?>" 
                                       <?php if (!empty($item_link['target'])): ?>target="<?php echo esc_attr($item_link['target']); ?>"<?php endif; ?>>
                                        <?php echo esc_html($item_title); ?>
                                    </a>
                                </h3>
                            <?php else: ?>
                                <h3 class="content-grid-item-title"><?php echo esc_html($item_title); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ($item_description): ?>
                                <p class="content-grid-item-description"><?php echo esc_html($item_description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php if (current_user_can('manage_options')): ?>
                <div style="background: rgba(255,255,0,0.2); border: 2px dashed orange; padding: 20px; margin: 20px 0; text-align: center; color: #000;">
                    <strong>No items to display.</strong><br>
                    Please add items to the repeater field in the block settings.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

