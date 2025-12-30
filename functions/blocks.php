<?php
/**
 * Register ACF Blocks
 */

function basetheme_register_blocks() {
    // Check if function exists (Secure Custom Fields plugin is active)
    if (function_exists('acf_register_block_type')) {
        
        // Register Hero Block
        acf_register_block_type(array(
            'name'              => 'hero',
            'title'             => __('Hero Block'),
            'description'       => __('A customizable hero section block.'),
            'render_template'   => 'blocks/hero.php',
            'category'          => 'common',
            'icon'              => 'cover-image',
            'keywords'          => array('hero', 'banner', 'header'),
            'mode'                => 'preview',
            'supports'          => array(
                'align' => true,
                'mode'  => true,
            ),
        ));
        
        // Register Content Grid Block
        acf_register_block_type(array(
            'name'              => 'content-grid',
            'title'             => __('Content Grid'),
            'description'       => __('A flexible grid layout for displaying content items with icons, titles, and descriptions.'),
            'render_template'   => 'blocks/content-grid.php',
            'category'          => 'common',
            'icon'              => 'grid-view',
            'keywords'          => array('grid', 'services', 'features', 'items', 'cards'),
            'mode'              => 'edit', // 'edit' shows fields in main editor, 'preview' shows in sidebar, 'auto' allows toggle
            'supports'          => array(
                'align' => true,
                'mode'  => true, // This allows users to toggle between edit/preview if they want
            ),
        ));
        
        // Register About Block
        acf_register_block_type(array(
            'name'              => 'about',
            'title'             => __('About Section'),
            'description'       => __('A side-by-side layout for about sections with text content, features list, and image.'),
            'render_template'   => 'blocks/about.php',
            'category'          => 'common',
            'icon'              => 'info',
            'keywords'          => array('about', 'section', 'content', 'text', 'image'),
            'mode'              => 'edit',
            'supports'          => array(
                'align' => true,
                'mode'  => true,
            ),
        ));
        
        // Register Reviews/Testimonials Block
        acf_register_block_type(array(
            'name'              => 'reviews',
            'title'             => __('Reviews/Testimonials'),
            'description'       => __('A carousel of customer reviews and testimonials with star ratings.'),
            'render_template'   => 'blocks/reviews.php',
            'category'          => 'common',
            'icon'              => 'star-filled',
            'keywords'          => array('reviews', 'testimonials', 'ratings', 'customers', 'feedback'),
            'mode'              => 'edit',
            'supports'          => array(
                'align' => true,
                'mode'  => true,
            ),
        ));
        
        // Register Contact Block
        acf_register_block_type(array(
            'name'              => 'contact',
            'title'             => __('Contact Section'),
            'description'       => __('A contact section with contact information cards and a form.'),
            'render_template'   => 'blocks/contact.php',
            'category'          => 'common',
            'icon'              => 'email',
            'keywords'          => array('contact', 'form', 'get in touch', 'email', 'phone'),
            'mode'              => 'edit',
            'supports'          => array(
                'align' => true,
                'mode'  => true,
            ),
        ));

        // Register Video Gallery Block
        acf_register_block_type(array(
            'name'              => 'video-gallery',
            'title'             => __('Video Gallery'),
            'description'       => __('A grid layout for displaying video projects with play buttons and descriptions.'),
            'render_template'   => 'blocks/video-gallery.php',
            'category'          => 'common',
            'icon'              => 'video-alt3',
            'keywords'          => array('video', 'gallery', 'projects', 'work', 'portfolio'),
            'mode'              => 'edit',
            'supports'          => array(
                'align' => true,
                'mode'  => true,
            ),
        ));

        // Register Legal Page Block
        acf_register_block_type(array(
            'name'              => 'legal-page',
            'title'             => __('Legal Page'),
            'description'       => __('A reusable block for legal pages like Privacy Policy, Terms of Service, and Cookie Policy.'),
            'render_template'   => 'blocks/legal-page.php',
            'category'          => 'common',
            'icon'              => 'media-document',
            'keywords'          => array('legal', 'policy', 'privacy', 'terms', 'cookie', 'document'),
            'mode'              => 'edit',
            'supports'          => array(
                'align' => true,
                'mode'  => true,
            ),
        ));
    }
}
add_action('acf/init', 'basetheme_register_blocks');

