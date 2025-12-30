<?php
/**
 * Contact Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 * @param array $context The context provided to the block by the parent block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'contact-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'contact-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get fields
$title = get_field('contact_title') ?: get_field('title') ?: '';
$subtitle = get_field('contact_subtitle') ?: get_field('subtitle') ?: '';
$form_shortcode = get_field('contact_form_shortcode') ?: get_field('form_shortcode') ?: '';

// Get contact info items - try both get_field array and have_rows methods
$contact_items = get_field('contact_items') ?: get_field('contact_info') ?: array();
$contact_items_array = array();

if ($contact_items && is_array($contact_items) && count($contact_items) > 0) {
    $contact_items_array = $contact_items;
} else {
    // Try have_rows method
    $contact_fields = array('contact_items', 'contact_info', 'contact_information');
    foreach ($contact_fields as $field_name) {
        if (have_rows($field_name)) {
            while (have_rows($field_name)) {
                the_row();
                $item_icon = get_sub_field('contact_item_icon') ?: get_sub_field('item_icon') ?: get_sub_field('icon') ?: '';
                $item_label = get_sub_field('contact_item_label') ?: get_sub_field('item_label') ?: get_sub_field('label') ?: '';
                $item_value = get_sub_field('contact_item_value') ?: get_sub_field('item_value') ?: get_sub_field('value') ?: '';
                $item_link = get_sub_field('contact_item_link') ?: get_sub_field('item_link') ?: get_sub_field('link') ?: '';
                
                // Show card if it has either a label or value
                if (!empty($item_label) || !empty($item_value)) {
                    $contact_items_array[] = array(
                        'icon' => $item_icon,
                        'label' => $item_label,
                        'value' => $item_value,
                        'link' => $item_link
                    );
                }
            }
            break;
        }
    }
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="contact-wrapper">
        <?php if ($title || $subtitle): ?>
            <div class="contact-header">
                <?php if ($title): ?>
                    <h2 class="contact-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                
                <?php if ($subtitle): ?>
                    <p class="contact-subtitle"><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="contact-content">
            <?php if (count($contact_items_array) > 0): ?>
                <div class="contact-info">
                    <?php foreach ($contact_items_array as $item): 
                        $item_icon = $item['icon'] ?? $item['contact_item_icon'] ?? $item['item_icon'] ?? '';
                        $item_label = $item['label'] ?? $item['contact_item_label'] ?? $item['item_label'] ?? '';
                        $item_value = $item['value'] ?? $item['contact_item_value'] ?? $item['item_value'] ?? '';
                        $item_link = $item['link'] ?? $item['contact_item_link'] ?? $item['item_link'] ?? '';
                    ?>
                        <div class="contact-info-card <?php echo (strpos($item_icon, 'whatsapp') !== false) ? 'has-whatsapp' : ''; ?>">
                            <div class="contact-info-icon">
                                <?php if ($item_icon): ?>
                                    <i class="<?php echo esc_attr($item_icon); ?>"></i>
                                <?php endif; ?>
                            </div>
                            <div class="contact-info-content">
                                <?php if ($item_label): ?>
                                    <div class="contact-info-label"><?php echo esc_html($item_label); ?></div>
                                <?php endif; ?>
                                
                                <?php if ($item_value): ?>
                                    <?php 
                                    // Check if link exists and has a valid URL
                                    $has_link = false;
                                    $link_url = '';
                                    $link_target = '';
                                    
                                    if ($item_link) {
                                        if (is_array($item_link) && !empty($item_link['url'])) {
                                            $has_link = true;
                                            $link_url = $item_link['url'];
                                            $link_target = $item_link['target'] ?? '';
                                        } elseif (is_string($item_link) && !empty($item_link)) {
                                            $has_link = true;
                                            $link_url = $item_link;
                                        }
                                    }
                                    ?>
                                    
                                    <?php if ($has_link): ?>
                                        <a href="<?php echo esc_url($link_url); ?>" 
                                           <?php if ($link_target): ?>target="<?php echo esc_attr($link_target); ?>"<?php endif; ?>
                                           class="contact-info-value">
                                            <?php echo esc_html($item_value); ?>
                                        </a>
                                    <?php else: ?>
                                        <div class="contact-info-value"><?php echo esc_html($item_value); ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($form_shortcode): ?>
                <div class="contact-form">
                    <div class="contact-form-card">
                        <?php echo do_shortcode($form_shortcode); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

