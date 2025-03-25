<?php
/**
 * Widget Galerie d'Images Sésame
 */

// Si cette page est accédée directement, on quitte
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe du widget Galerie d'Images Sésame
 */
class Sesame_Image_Gallery_Widget extends \Elementor\Widget_Base
{
    /**
     * Obtenir le nom du widget
     */
    public function get_name()
    {
        return 'sesame_image_gallery';
    }

    /**
     * Obtenir le titre du widget
     */
    public function get_title()
    {
        return esc_html__('Galerie d\'Images Sésame', 'sesame-gallery');
    }

    /**
     * Obtenir l'icône du widget
     */
    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    /**
     * Obtenir les catégories du widget
     */
    public function get_categories()
    {
        return ['sesame-category'];
    }

    /**
     * Obtenir les mots-clés du widget
     */
    public function get_keywords()
    {
        return ['galerie', 'image', 'sésame', 'photo'];
    }

    /**
     * Enregistrer les contrôles du widget
     */
    protected function _register_controls()
    {
        // Section des Images
        $this->start_controls_section(
            'section_images',
            [
                'label' => esc_html__('Images', 'sesame-gallery'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Contrôle pour l'image principale
        $this->add_control(
            'main_image',
            [
                'label' => esc_html__('Image Principale', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Contrôle pour l'image en bas à gauche
        $this->add_control(
            'bottom_left_image',
            [
                'label' => esc_html__('Image Bas Gauche', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Contrôle pour l'image en haut à droite
        $this->add_control(
            'top_right_image',
            [
                'label' => esc_html__('Image Haut Droite', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        // Section des Effets
        $this->start_controls_section(
            'section_effects',
            [
                'label' => esc_html__('Effets', 'sesame-gallery'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Contrôle pour la couleur du gradient
        $this->add_control(
            'glow_color_start',
            [
                'label' => esc_html__('Couleur Gradient Début', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F0D7C2',
            ]
        );

        $this->add_control(
            'glow_color_middle',
            [
                'label' => esc_html__('Couleur Gradient Milieu', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(138, 93, 93, 0.4)',
            ]
        );

        $this->add_control(
            'glow_color_end',
            [
                'label' => esc_html__('Couleur Gradient Fin', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(255, 248, 240, 0.3)',
            ]
        );

        // Contrôle pour l'opacité du glow
        $this->add_control(
            'glow_opacity',
            [
                'label' => esc_html__('Opacité de l\'effet lueur', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.4,
                ],
            ]
        );

        // Contrôle pour l'animation
        $this->add_control(
            'enable_animation',
            [
                'label' => esc_html__('Activer l\'animation', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Oui', 'sesame-gallery'),
                'label_off' => esc_html__('Non', 'sesame-gallery'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Section Style des Images
        $this->start_controls_section(
            'section_style_images',
            [
                'label' => esc_html__('Style des Images', 'sesame-gallery'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Contrôle pour la taille de l'image principale
        $this->add_control(
            'main_image_size',
            [
                'label' => esc_html__('Taille de l\'image principale', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sesame-image-gallery' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Contrôle pour la taille des images secondaires
        $this->add_control(
            'secondary_image_size',
            [
                'label' => esc_html__('Taille des images secondaires', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 400,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 20,
                        'max' => 50,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 192,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bottom-left-image, {{WRAPPER}} .top-right-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Contrôle pour le rayon des coins
        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Rayon des coins', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .main-image, {{WRAPPER}} .glow-effect' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bottom-left-image, {{WRAPPER}} .top-right-image' => 'border-radius: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Afficher le contenu du widget
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Vérifier si l'animation est activée
        $animation_class = $settings['enable_animation'] === 'yes' ? 'animated-glow' : '';

        // Obtenir les URLs des images
        $main_image_url = $settings['main_image']['url'];
        $bottom_left_image_url = $settings['bottom_left_image']['url'];
        $top_right_image_url = $settings['top_right_image']['url'];

        // Obtenir les couleurs du gradient
        $gradient_start = $settings['glow_color_start'];
        $gradient_middle = $settings['glow_color_middle'];
        $gradient_end = $settings['glow_color_end'];
        $glow_opacity = $settings['glow_opacity']['size'];

        // Style CSS personnalisé
        $custom_css = "
            <style>
                .sesame-image-gallery .glow-effect {
                    background: linear-gradient(to bottom right, {$gradient_start}, {$gradient_middle}, {$gradient_end});
                    opacity: {$glow_opacity};
                }
                
                .animated-glow .glow-effect {
                    animation: pulse 2s infinite alternate;
                }
                
                @keyframes pulse {
                    0% {
                        opacity: " . ($glow_opacity - 0.1) . ";
                    }
                    100% {
                        opacity: " . ($glow_opacity + 0.1) . ";
                    }
                }
            </style>
        ";

        // HTML du widget
        echo $custom_css;
        ?>
        <div class="sesame-image-gallery <?php echo esc_attr($animation_class); ?>">
            <div class="main-image-container">
                <div class="glow-effect"></div>
                <div class="main-image">
                    <img src="<?php echo esc_url($main_image_url); ?>" alt="Image principale" />
                </div>
            </div>
            <div class="bottom-left-image">
                <img src="<?php echo esc_url($bottom_left_image_url); ?>" alt="Image secondaire gauche" />
            </div>
            <div class="top-right-image">
                <img src="<?php echo esc_url($top_right_image_url); ?>" alt="Image secondaire droite" />
            </div>
        </div>
        <?php
    }

    /**
     * Injecter le CSS du widget
     */
    public function _content_template()
    {
        ?>
        <style>
            .sesame-image-gallery {
                position: relative;
                width: 100%;
                max-width: 500px;
                margin: 0 auto;
                padding-bottom: 100px;
                padding-right: 40px;
            }

            .main-image-container {
                position: relative;
            }

            .glow-effect {
                position: absolute;
                inset: -16px;
                border-radius: 16px;

                background: linear-gradient(to bottom right, {
                        {
                            {
                            settings.glow_color_start
                        }
                    }
                }

                , {
                    {
                        {
                        settings.glow_color_middle
                    }
                }
            }

            , {
                {
                    {
                    settings.glow_color_end
                }
            }
            });

            opacity: {
                    {
                        {
                        settings.glow_opacity.size
                    }
                }
            }

            ;
            filter: blur(20px);
            }

            <# if (settings.enable_animation==='yes') {
                #>.glow-effect {
                    animation: pulse 2s infinite alternate;
                }

                <#
            }

            #>.main-image {
                position: relative;
                aspect-ratio: 1;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(240, 215, 194, 0.3);
                background-color: white;
            }

            .main-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .bottom-left-image {
                position: absolute;
                bottom: -32px;
                left: -32px;
                width: 192px;
                height: 192px;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                border: 4px solid white;
                transform: rotate(6deg);
                background-color: white;
                z-index: 2;
            }

            .top-right-image {
                position: absolute;
                right: -24px;
                top: 33%;
                width: 192px;
                height: 192px;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                border: 4px solid white;
                transform: rotate(-6deg);
                background-color: white;
                z-index: 2;
            }

            .bottom-left-image img,
            .top-right-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            @keyframes pulse {
                0% {
                    opacity: calc({
                            {
                                {
                                settings.glow_opacity.size
                            }
                        }
                    }

                    - 0.1);
            }

            100% {
                opacity: calc({
                        {
                            {
                            settings.glow_opacity.size
                        }
                    }
                }

                + 0.1);
            }
            }

            /* Responsive pour les mobiles */
            @media (max-width: 767px) {

                .bottom-left-image,
                .top-right-image {
                    width: 144px;
                    height: 144px;
                }
            }
        </style>
        <div class="sesame-image-gallery <# if ( settings.enable_animation === 'yes' ) { #>animated-glow<# } #>">
            <div class="main-image-container">
                <div class="glow-effect"></div>
                <div class="main-image">
                    <img src="{{ settings.main_image.url }}" alt="Image principale" />
                </div>
            </div>
            <div class="bottom-left-image">
                <img src="{{ settings.bottom_left_image.url }}" alt="Image secondaire gauche" />
            </div>
            <div class="top-right-image">
                <img src="{{ settings.top_right_image.url }}" alt="Image secondaire droite" />
            </div>
        </div>
        <?php
    }
}