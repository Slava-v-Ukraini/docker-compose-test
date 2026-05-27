<?php
if (!defined('ABSPATH')) exit; // Безпека: закриваємо прямий доступ до файлу

function m_corner_core_setup() {
    add_theme_support('title-tag');
    
    add_theme_support('post-thumbnails');
    
    register_nav_menus(array(
        'main-menu' => esc_html__('Main Menu', 'm-corner-core'),
    ));
}
add_action('after_setup_theme', 'm_corner_core_setup');

function m_corner_core_scripts() {
    wp_enqueue_style('m-corner-core-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'm_corner_core_scripts');

// Кастомний шорткод для виведення сучасної сітки AI-новин
add_shortcode('m_corner_all_posts', 'm_corner_all_posts_shortcode');
function m_corner_all_posts_shortcode() {
    $query = new WP_Query(array(
        'posts_per_page' => 6,
        'post_status'    => 'publish'
    ));
    
    if (!$query->have_posts()) {
        return '<p style="color: #6c7284; font-style: italic; text-align: center; padding: 60px; background: #16171d; border-radius: 16px; border: 1px dashed #242631;">Стрічка новин порожня. Очікуємо на перші автоматичні публікації від ШІ...</p>';
    }
    
    $output = '<div class="ai-news-grid">';
    
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        
        // 1. Спочатку перевіряємо стандартне зображення
        $thumb_url = get_the_post_thumbnail_url($post_id, 'medium_large');
        
        // 2. Якщо стандартного немає, перевіряємо лінк від FIFU
        if (!$thumb_url) {
            $thumb_url = get_post_meta($post_id, '_fifu_image_url', true);
        }
        
        // Збираємо блок картинки із зум-ефектом
        $output .= '<div class="news-card">';
        $output .= '<div class="news-card-image-wrapper">';
        
        if ($thumb_url) {
            $output .= '<div class="news-card-image" style="background-image: url('.esc_url($thumb_url).');"></div>';
        } else {
            $output .= '<div class="news-card-image" style="background: linear-gradient(135deg, #1f212a 0%, #2c2f3f 100%); display: flex; align-items: center; justify-content: center; color: #3d4256; font-weight: 800; font-size: 1.6rem; letter-spacing: 2px;">M-CORNER AI</div>';
        }
        
        $output .= '</div>'; // news-card-image-wrapper
        
        // Збираємо контентну частину
        $output .= '<div class="news-card-content">';
        $output .= '<span class="news-card-tag">Cyber Intelligence</span>';
        $output .= '<h3 class="news-card-title"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
        $output .= '<div class="news-card-meta">📅 '.get_the_date().'</div>';
        $output .= '<div class="news-card-excerpt">'.get_the_excerpt().'</div>';
        $output .= '<a href="'.get_permalink().'" class="news-card-button">Читати далі <span>→</span></a>';
        $output .= '</div>'; // news-card-content
        $output .= '</div>'; // news-card
    }
    
    $output .= '</div>';
    wp_reset_postdata();
    return $output;
}