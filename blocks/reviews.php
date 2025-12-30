<?php
/**
 * Reviews/Testimonials Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 * @param array $context The context provided to the block by the parent block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'reviews-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'reviews-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get fields
$title = get_field('reviews_title') ?: get_field('title') ?: '';
$subtitle = get_field('reviews_subtitle') ?: get_field('subtitle') ?: '';
$testimonials = get_field('reviews_testimonials') ?: get_field('testimonials') ?: array(); // Repeater field

// Get testimonials - try both get_field array and have_rows methods
$testimonials_array = array();

if ($testimonials && is_array($testimonials) && count($testimonials) > 0) {
    $testimonials_array = $testimonials;
} else {
    // Try have_rows method
    $testimonial_fields = array('reviews_testimonials', 'testimonials', 'reviews');
    foreach ($testimonial_fields as $field_name) {
        if (have_rows($field_name)) {
            while (have_rows($field_name)) {
                the_row();
                $testimonial_text = get_sub_field('review_text') ?: get_sub_field('testimonial_text') ?: get_sub_field('text') ?: '';
                $customer_name = get_sub_field('review_customer_name') ?: get_sub_field('customer_name') ?: get_sub_field('name') ?: '';
                $customer_location = get_sub_field('review_customer_location') ?: get_sub_field('customer_location') ?: get_sub_field('service_location') ?: get_sub_field('location') ?: '';
                $avatar_icon = get_sub_field('review_avatar') ?: get_sub_field('avatar') ?: get_sub_field('avatar_icon') ?: '';
                
                if (!empty($testimonial_text)) {
                    $testimonials_array[] = array(
                        'text' => $testimonial_text,
                        'name' => $customer_name,
                        'customer_location' => $customer_location,
                        'avatar_icon' => $avatar_icon
                    );
                }
            }
            break;
        }
    }
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="reviews-wrapper" id="reviews">
        <?php if ($title || $subtitle): ?>
            <div class="reviews-header">
                <?php if ($title): ?>
                    <h2 class="reviews-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                
                <?php if ($subtitle): ?>
                    <p class="reviews-subtitle"><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (count($testimonials_array) > 0): ?>
            <div class="reviews-carousel" data-carousel-id="<?php echo esc_attr($id); ?>">
                <button class="reviews-arrow reviews-arrow-prev" aria-label="Previous testimonial">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                
                <div class="reviews-slides-container">
                    <div class="reviews-slides">
                        <?php foreach ($testimonials_array as $index => $testimonial): 
                            $testimonial_text = $testimonial['text'] ?? $testimonial['review_text'] ?? $testimonial['testimonial_text'] ?? '';
                            $customer_name = $testimonial['name'] ?? $testimonial['customer_name'] ?? $testimonial['review_customer_name'] ?? '';
                            $customer_location = $testimonial['customer_location'] ?? $testimonial['review_customer_location'] ?? $testimonial['service_location'] ?? $testimonial['location'] ?? '';
                            $avatar_icon = $testimonial['avatar_icon'] ?? $testimonial['review_avatar'] ?? $testimonial['avatar'] ?? '';
                            
                            if (empty($testimonial_text)) continue;
                        ?>
                            <div class="reviews-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo esc_attr($index); ?>">
                                <div class="reviews-card">
                                    <div class="reviews-quote-mark">"</div>
                                    
                                    <div class="reviews-stars">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <svg class="star-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#FFD700" stroke="#FFD700" stroke-width="1"/>
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                    
                                    <p class="reviews-text"><?php echo esc_html($testimonial_text); ?></p>
                                    
                                    <div class="reviews-divider"></div>
                                    
                                    <div class="reviews-customer">
                                        <div class="reviews-avatar reviews-avatar-icon">
                                            <?php if ($avatar_icon): ?>
                                                <i class="<?php echo esc_attr($avatar_icon); ?>"></i>
                                            <?php else: ?>
                                                <i class="fas fa-user"></i>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="reviews-customer-info">
                                            <?php if ($customer_name): ?>
                                                <div class="reviews-customer-name"><?php echo esc_html($customer_name); ?></div>
                                            <?php endif; ?>
                                            <?php if ($customer_location): ?>
                                                <div class="reviews-customer-location"><?php echo esc_html($customer_location); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <button class="reviews-arrow reviews-arrow-next" aria-label="Next testimonial">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            
            <?php if (count($testimonials_array) > 1): ?>
                <div class="reviews-dots">
                    <?php foreach ($testimonials_array as $index => $testimonial): ?>
                        <button class="reviews-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo esc_attr($index); ?>" aria-label="Go to testimonial <?php echo esc_attr($index + 1); ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

