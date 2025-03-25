<?php
if (!defined('ABSPATH')) {
    exit;
}

class Sesame_Service_Traiteur_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'sesame_service_traiteur';
    }

    public function get_title()
    {
        return 'Service Traiteur';
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories()
    {
        return ['sesame-category'];
    }

    /**
     * Enregistrer les contrôles du widget
     */
    protected function register_controls()
    {
        // Section Contenu
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Contenu',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => 'Titre',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Service Traiteur',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => 'Sous-titre',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Découvrez nos services de traiteur pour vos événements',
            ]
        );

        $this->add_control(
            'taxonomy_filter',
            [
                'label' => 'Filtrer par catégorie',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_catering_categories(),
                'default' => [],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => 'Nombre de services à afficher',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => -1,
                'min' => -1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'cta_text',
            [
                'label' => 'Texte du bouton',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Contactez-nous',
            ]
        );

        $this->add_control(
            'cta_link',
            [
                'label' => 'Lien du bouton',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://votre-lien.com',
                'show_external' => true,
                'default' => [
                    'url' => '/contact',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $this->end_controls_section();

        // Section Style
        $this->start_controls_section(
            'section_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Couleur du titre',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#5F3C3C',
                'selectors' => [
                    '{{WRAPPER}} .sesame-traiteur-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => 'Couleur du sous-titre',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#8A5D5D',
                'selectors' => [
                    '{{WRAPPER}} .sesame-traiteur-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'service_bg_color',
            [
                'label' => 'Couleur de fond des services',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .sesame-service-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'service_title_color',
            [
                'label' => 'Couleur du titre des services',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#5F3C3C',
                'selectors' => [
                    '{{WRAPPER}} .sesame-service-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Injecter le CSS du widget
     */
    public function content_template()
    {
        $settings = $this->get_settings_for_display();

        // Récupérer les services de traiteur
        $args = array(
            'post_type' => 'catering',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'date',
            'order' => 'DESC',
        );

        // Ajouter le filtre taxonomique si des catégories sont sélectionnées et si la taxonomie existe
        if (!empty($settings['taxonomy_filter']) && taxonomy_exists('catering_category')) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'catering_category',
                    'field' => 'term_id',
                    'terms' => $settings['taxonomy_filter'],
                ),
            );
        }

        $services_query = new WP_Query($args);
        ?>
        <style>
            .sesame-traiteur-container {
                padding: 40px 0;
            }

            .sesame-traiteur-header {
                text-align: center;
                margin-bottom: 50px;
            }

            .sesame-traiteur-caption {
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
                color: #8A5D5D;
                margin-bottom: 5px;
                font-weight: 500;
            }

            .sesame-traiteur-title {
                font-family: 'Playfair Display', serif;
                font-size: 42px;
                font-weight: 600;
                color: #5F3C3C;
                margin-bottom: 15px;
            }

            .sesame-traiteur-subtitle {
                font-family: 'Montserrat', sans-serif;
                font-size: 16px;
                background-color: rgba(173, 216, 230, 0.3);
                padding: 8px 16px;
                border-radius: 4px;
                color: #5F3C3C;
                margin: 0 auto;
                max-width: 800px;
                display: inline-block;
            }

            .sesame-services-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
                margin-bottom: 40px;
            }

            @media (max-width: 768px) {
                .sesame-services-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 480px) {
                .sesame-services-grid {
                    grid-template-columns: 1fr;
                }
            }

            .sesame-service-item {
                background-color: #FFFFFF;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                border: 1px solid #F0F0F0;
                transition: all 0.3s ease;
            }

            .sesame-service-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            }

            .sesame-service-image {
                position: relative;
                padding-bottom: 66.67%;
                overflow: hidden;
            }

            .sesame-service-image img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .sesame-service-item:hover .sesame-service-image img {
                transform: scale(1.1);
            }

            .sesame-service-content {
                padding: 25px;
            }

            .sesame-service-categories {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                margin-bottom: 15px;
            }

            .sesame-service-category {
                font-family: 'Montserrat', sans-serif;
                font-size: 12px;
                color: #8A5D5D;
                background-color: rgba(138, 93, 93, 0.1);
                padding: 4px 10px;
                border-radius: 20px;
            }

            .sesame-service-title {
                font-family: 'Playfair Display', serif;
                font-size: 24px;
                font-weight: 600;
                color: #5F3C3C;
                margin-bottom: 15px;
            }

            .sesame-service-description {
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
                color: #666666;
                line-height: 1.6;
                margin-bottom: 20px;
            }

            .sesame-service-link {
                display: inline-block;
                color: #8A5D5D;
                text-decoration: none;
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .sesame-service-link:hover {
                color: #5F3C3C;
            }

            .sesame-traiteur-cta {
                text-align: center;
                margin-top: 40px;
            }

            .sesame-traiteur-cta a {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background-color: #5F3C3C;
                color: white;
                padding: 12px 30px;
                border-radius: 6px;
                text-decoration: none;
                font-family: 'Montserrat', sans-serif;
                font-size: 16px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .sesame-traiteur-cta a:hover {
                background-color: #8A5D5D;
            }

            .sesame-traiteur-cta svg {
                margin-left: 8px;
            }
        </style>

        <div class="sesame-traiteur-container">
            <div class="sesame-traiteur-header">
                <div class="sesame-traiteur-caption">dddddddd</div>
                <h2 class="sesame-traiteur-title"><?php echo esc_html($settings['title']); ?></h2>
                <?php if (!empty($settings['subtitle'])): ?>
                    <div class="sesame-traiteur-subtitle"><?php echo esc_html($settings['subtitle']); ?></div>
                <?php endif; ?>
            </div>

            <?php if ($services_query->have_posts()): ?>
                <div class="sesame-services-grid">
                    <?php while ($services_query->have_posts()):
                        $services_query->the_post();
                        $categories = taxonomy_exists('catering_category') ? get_the_terms(get_the_ID(), 'catering_category') : array();
                        $featured_image = get_field('featured_image');
                        $gallery_images = get_field('gallery');
                        if (!$featured_image) {
                            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        }
                        ?>
                        <div class="sesame-service-item">
                            <?php if ($featured_image): ?>
                                <div class="sesame-service-image">
                                    <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                </div>
                            <?php endif; ?>

                            <div class="sesame-service-content">
                                <?php if ($categories && !is_wp_error($categories)): ?>
                                    <div class="sesame-service-categories">
                                        <?php foreach ($categories as $category): ?>
                                            <span class="sesame-service-category"><?php echo esc_html($category->name); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <h3 class="sesame-service-title"><?php the_title(); ?></h3>
                                <div class="sesame-service-description"><?php echo wp_trim_words(get_field('short_description'), 20); ?>
                                </div>

                                <?php if ($gallery_images && is_array($gallery_images)): ?>
                                    <div class="sesame-service-gallery">
                                        <?php foreach (array_slice($gallery_images, 0, 3) as $image): ?>
                                            <div class="sesame-gallery-image">
                                                <img src="<?php echo esc_url($image); ?>"
                                                    alt="<?php echo esc_attr(get_the_title()); ?> gallery">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="sesame-service-link">En savoir plus</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif;
            wp_reset_postdata(); ?>

            <?php if (!empty($settings['cta_text']) && !empty($settings['cta_link']['url'])): ?>
                <div class="sesame-traiteur-cta">
                    <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" <?php echo $settings['cta_link']['is_external'] ? 'target="_blank"' : ''; ?>             <?php echo $settings['cta_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                        <?php echo esc_html($settings['cta_text']); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function get_catering_categories()
    {
        $options = [];

        // Vérifier si la taxonomie existe avant de l'utiliser
        if (taxonomy_exists('catering_category')) {
            $terms = get_terms([
                'taxonomy' => 'catering_category',
                'hide_empty' => false,
            ]);

            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $options[$term->term_id] = $term->name;
                }
            }
        }

        return $options;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Récupérer les services de traiteur
        $args = array(
            'post_type' => 'catering',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'date',
            'order' => 'DESC',
        );

        // Ajouter le filtre taxonomique si des catégories sont sélectionnées et si la taxonomie existe
        if (!empty($settings['taxonomy_filter']) && taxonomy_exists('catering_category')) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'catering_category',
                    'field' => 'term_id',
                    'terms' => $settings['taxonomy_filter'],
                ),
            );
        }

        $services_query = new WP_Query($args);
        ?>
        <style>
            .sesame-traiteur-container {
                padding: 40px 0;
            }

            .sesame-traiteur-header {
                text-align: center;
                margin-bottom: 50px;
            }

            .sesame-traiteur-caption {
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
                color: #8A5D5D;
                margin-bottom: 5px;
                font-weight: 500;
            }

            .sesame-traiteur-title {
                font-family: 'Playfair Display', serif;
                font-size: 42px;
                font-weight: 600;
                color: #5F3C3C;
                margin-bottom: 15px;
            }

            .sesame-traiteur-subtitle {
                font-family: 'Montserrat', sans-serif;
                font-size: 16px;
                background-color: rgba(173, 216, 230, 0.3);
                padding: 8px 16px;
                border-radius: 4px;
                color: #5F3C3C;
                margin: 0 auto;
                max-width: 800px;
                display: inline-block;
            }

            .sesame-services-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
                margin-bottom: 40px;
            }

            @media (max-width: 768px) {
                .sesame-services-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 480px) {
                .sesame-services-grid {
                    grid-template-columns: 1fr;
                }
            }

            .sesame-service-item {
                background-color: #FFFFFF;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                border: 1px solid #F0F0F0;
                transition: all 0.3s ease;
            }

            .sesame-service-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            }

            .sesame-service-image {
                position: relative;
                padding-bottom: 66.67%;
                overflow: hidden;
            }

            .sesame-service-image img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .sesame-service-item:hover .sesame-service-image img {
                transform: scale(1.1);
            }

            .sesame-service-content {
                padding: 25px;
            }

            .sesame-service-categories {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                margin-bottom: 15px;
            }

            .sesame-service-category {
                font-family: 'Montserrat', sans-serif;
                font-size: 12px;
                color: #8A5D5D;
                background-color: rgba(138, 93, 93, 0.1);
                padding: 4px 10px;
                border-radius: 20px;
            }

            .sesame-service-title {
                font-family: 'Playfair Display', serif;
                font-size: 24px;
                font-weight: 600;
                color: #5F3C3C;
                margin-bottom: 15px;
            }

            .sesame-service-description {
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
                color: #666666;
                line-height: 1.6;
                margin-bottom: 20px;
            }

            .sesame-service-link {
                display: inline-block;
                color: #8A5D5D;
                text-decoration: none;
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .sesame-service-link:hover {
                color: #5F3C3C;
            }

            .sesame-traiteur-cta {
                text-align: center;
                margin-top: 40px;
            }

            .sesame-traiteur-cta a {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background-color: #5F3C3C;
                color: white;
                padding: 12px 30px;
                border-radius: 6px;
                text-decoration: none;
                font-family: 'Montserrat', sans-serif;
                font-size: 16px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .sesame-traiteur-cta a:hover {
                background-color: #8A5D5D;
            }

            .sesame-traiteur-cta svg {
                margin-left: 8px;
            }
        </style>

        <div class="sesame-traiteur-container">
            <div class="sesame-traiteur-header">
                <div class="sesame-traiteur-caption">Service Traiteur</div>
                <h2 class="sesame-traiteur-title"><?php echo esc_html($settings['title']); ?></h2>
                <?php if (!empty($settings['subtitle'])): ?>
                    <div class="sesame-traiteur-subtitle"><?php echo esc_html($settings['subtitle']); ?></div>
                <?php endif; ?>
            </div>

            <?php if ($services_query->have_posts()): ?>
                <div class="sesame-services-grid">
                    <?php while ($services_query->have_posts()):
                        $services_query->the_post();
                        $categories = taxonomy_exists('catering_category') ? get_the_terms(get_the_ID(), 'catering_category') : array();
                        $featured_image = get_field('featured_image');
                        $gallery_images = get_field('gallery');
                        if (!$featured_image) {
                            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        }
                        ?>
                        <div class="sesame-service-item">
                            <?php if ($featured_image): ?>
                                <div class="sesame-service-image">
                                    <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                </div>
                            <?php endif; ?>

                            <div class="sesame-service-content">
                                <?php if ($categories && !is_wp_error($categories)): ?>
                                    <div class="sesame-service-categories">
                                        <?php foreach ($categories as $category): ?>
                                            <span class="sesame-service-category"><?php echo esc_html($category->name); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <h3 class="sesame-service-title"><?php the_title(); ?></h3>
                                <div class="sesame-service-description"><?php echo wp_trim_words(get_field('short_description'), 20); ?>
                                </div>

                                <?php if ($gallery_images && is_array($gallery_images)): ?>
                                    <div class="sesame-service-gallery">
                                        <?php foreach (array_slice($gallery_images, 0, 3) as $image): ?>
                                            <div class="sesame-gallery-image">
                                                <img src="<?php echo esc_url($image); ?>"
                                                    alt="<?php echo esc_attr(get_the_title()); ?> gallery">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="sesame-service-link">En savoir plus</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif;
            wp_reset_postdata(); ?>

            <?php if (!empty($settings['cta_text']) && !empty($settings['cta_link']['url'])): ?>
                <div class="sesame-traiteur-cta">
                    <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" <?php echo $settings['cta_link']['is_external'] ? 'target="_blank"' : ''; ?>             <?php echo $settings['cta_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                        <?php echo esc_html($settings['cta_text']); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}