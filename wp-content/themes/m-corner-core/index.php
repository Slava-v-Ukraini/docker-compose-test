<?php get_header(); ?>

<main id="primary" class="site-main" style="padding: 20px; max-width: 1200px; margin: 0 auto;">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            // Якщо сторінка або пост редагувалися в Elementor, він підставить дизайн сам
            the_content();
        endwhile;
    else :
        echo '<p>Контент не знайдено.</p>';
    endif;
    ?>
</main>

<?php get_footer(); ?>