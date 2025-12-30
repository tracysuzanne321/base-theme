<footer class="site-footer">
    <div class="container">
        <?php
       
        wp_nav_menu([
            'theme_location' => 'footer',
            'container' => 'nav',
            'container_class' => 'footer-nav',
            'menu_class' => 'footer-menu',
        ]);
        ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

