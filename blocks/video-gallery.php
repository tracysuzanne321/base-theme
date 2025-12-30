<?php
/**
 * Video Gallery Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 * @param array $context The context provided to the block by the parent block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'video-gallery-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'video-gallery-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get fields
$title = get_field('gallery_title') ?: get_field('title') ?: '';
$subtitle = get_field('gallery_subtitle') ?: get_field('subtitle') ?: '';
$videos = get_field('gallery_videos') ?: get_field('videos') ?: array(); // Repeater field
$columns = get_field('gallery_columns') ?: get_field('columns') ?: 3; // Number of columns (default 3)

// Try to get videos using have_rows if get_field didn't work
$videos_array = array();

if ($videos && is_array($videos) && count($videos) > 0) {
    $videos_array = $videos;
} else {
    // Try have_rows method
    $video_fields = array('gallery_videos', 'videos', 'video_items');
    foreach ($video_fields as $field_name) {
        if (have_rows($field_name)) {
            while (have_rows($field_name)) {
                the_row();
                $video_file = get_sub_field('video_file') ?: get_sub_field('video') ?: get_sub_field('gallery_video_file') ?: '';
                $overlay_title = get_sub_field('overlay_title') ?: get_sub_field('title') ?: get_sub_field('gallery_overlay_title') ?: '';
                $description = get_sub_field('video_description') ?: get_sub_field('description') ?: get_sub_field('gallery_video_description') ?: '';

                if (!empty($video_file)) {
                    $videos_array[] = array(
                        'video_file' => $video_file,
                        'overlay_title' => $overlay_title,
                        'description' => $description
                    );
                }
            }
            break;
        }
    }
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="video-gallery-wrapper" id="video-gallery">
        <?php if ($title): ?>
            <div class="video-gallery-header">
                <h2 class="video-gallery-title"><?php echo esc_html($title); ?></h2>
                <?php if ($subtitle): ?>
                    <h3 class="video-gallery-subtitle"><?php echo esc_html($subtitle); ?></h3>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (count($videos_array) > 0): ?>
            <div class="video-gallery-grid" style="--columns: <?php echo esc_attr($columns); ?>;">
                <?php foreach ($videos_array as $index => $video): 
                    $video_file = $video['video_file'] ?? $video['gallery_video_file'] ?? '';
                    $overlay_title = $video['overlay_title'] ?? $video['title'] ?? '';
                    $description = $video['description'] ?? $video['video_description'] ?? '';
                    
                    // Handle video file - could be array (ACF file field) or URL string
                    $video_url = '';
                    if (is_array($video_file)) {
                        $video_url = $video_file['url'] ?? '';
                    } else {
                        $video_url = $video_file;
                    }
                    
                    $video_id = 'video-' . $block['id'] . '-' . $index;
                ?>
                    <div class="video-gallery-item">
                        <div class="video-gallery-card">
                            <div class="video-gallery-player" data-video-id="<?php echo esc_attr($video_id); ?>">
                                <video 
                                    id="<?php echo esc_attr($video_id); ?>" 
                                    class="video-gallery-video" 
                                    preload="metadata"
                                    playsinline
                                >
                                    <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                
                                <div class="video-gallery-overlay">
                                    <?php if ($overlay_title): ?>
                                        <div class="video-gallery-overlay-title"><?php echo esc_html($overlay_title); ?></div>
                                    <?php endif; ?>
                                    
                                    <button class="video-gallery-play-btn" aria-label="Play video">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    
                                    <button class="video-gallery-fullscreen-btn" aria-label="Fullscreen">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <?php if ($description): ?>
                                <div class="video-gallery-description">
                                    <?php echo esc_html($description); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

