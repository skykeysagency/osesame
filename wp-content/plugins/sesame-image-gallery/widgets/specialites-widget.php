<?php
/**
 * Widget Nos Spécialités pour WooCommerce
 */

// Si cette page est accédée directement, on quitte
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe du widget Nos Spécialités
 */
class Sesame_Specialites_Widget extends \Elementor\Widget_Base
{
    /**
     * Obtenir le nom du widget
     */
    public function get_name()
    {
        return 'sesame_specialites';
    }

    // ... code existant ...

    /**
     * Enregistrer les contrôles du widget
     */
    protected function register_controls()
    {
        // Ajouter cette section de contenu au début
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Contenu', 'sesame-gallery'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Titre
        $this->add_control(
            'title',
            [
                'label' => esc_html__('Titre', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Nos Spécialités', 'sesame-gallery'),
                'placeholder' => esc_html__('Saisissez votre titre', 'sesame-gallery'),
                'label_block' => true,
            ]
        );

        // Sous-titre
        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Sous-titre', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Découvrez nos produits d\'exception préparés avec passion', 'sesame-gallery'),
                'placeholder' => esc_html__('Saisissez votre sous-titre', 'sesame-gallery'),
                'label_block' => true,
            ]
        );

        // Vos contrôles existants - badges, description, personnes, etc.
        $this->add_control(
            'show_badges',
            [
                'label' => esc_html__('Afficher les badges de catégorie', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Oui', 'sesame-gallery'),
                'label_off' => esc_html__('Non', 'sesame-gallery'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => esc_html__('Afficher la description courte', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Oui', 'sesame-gallery'),
                'label_off' => esc_html__('Non', 'sesame-gallery'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_persons',
            [
                'label' => esc_html__('Afficher le nombre de personnes', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Oui', 'sesame-gallery'),
                'label_off' => esc_html__('Non', 'sesame-gallery'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Nombre de produits à afficher
        $this->add_control(
            'products_count',
            [
                'label' => esc_html__('Nombre de produits', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 24,
                'step' => 1,
                'default' => 4,
            ]
        );

        // Colonnes
        $this->add_control(
            'columns',
            [
                'label' => esc_html__('Colonnes', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        // Source des produits
        $this->add_control(
            'product_source',
            [
                'label' => esc_html__('Source des produits', 'sesame-gallery'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'manual',
                'options' => [
                    'manual' => esc_html__('Sélection manuelle', 'sesame-gallery'),
                    'category' => esc_html__('Par catégorie', 'sesame-gallery')
                ],
                'separator' => 'before',
            ]
        );

        // ... autres contrôles de sélection de produits ...

        // Terminer la section
        $this->end_controls_section();

        // ... le reste des sections de style existantes ...
    }

    /**
     * Afficher le contenu du widget
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Vérifier si WooCommerce est actif
        if (!class_exists('WooCommerce')) {
            echo '<div class="woocommerce-error">WooCommerce n\'est pas installé ou activé.</div>';
            return;
        }

        // Paramètres de base
        $title = $settings['title'];
        $subtitle = $settings['subtitle'];
        $columns = $settings['columns'];
        $count = $settings['products_count'];
        $show_badges = $settings['show_badges'];
        $show_description = $settings['show_description'];
        $show_persons = $settings['show_persons'];

        // Préparation de la requête WooCommerce
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $count,
            'post_status' => 'publish',
        ];

        // Source des produits
        if ($settings['product_source'] === 'manual' && !empty($settings['product_ids'])) {
            $args['post__in'] = $settings['product_ids'];
            $args['orderby'] = 'post__in';
        } elseif ($settings['product_source'] === 'category' && !empty($settings['product_category'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $settings['product_category'],
                ]
            ];
        }

        // CSS personnalisé - remplacez tout le bloc CSS actuel par celui-ci
        $custom_css = "
            <style>
                .sesame-specialties {
                    width: 100%;
                    margin-bottom: 40px;
                }
                
                .sesame-specialties-caption {
                    font-family: 'Montserrat', sans-serif;
                    font-size: 14px;
                    color: #8A5D5D;
                    text-align: center;
                    margin-bottom: 5px;
                    font-weight: 500;
                }
                
                .sesame-specialties-title {
                    font-family: 'Playfair Display', serif;
                    font-size: 42px;
                    font-weight: 600;
                    color: #5F3C3C;
                    text-align: center;
                    margin-bottom: 15px;
                }
                
                .sesame-specialties-subtitle {
                    font-family: 'Montserrat', sans-serif;
                    font-size: 16px;
                    background-color: rgba(173, 216, 230, 0.3);
                    padding: 8px 16px;
                    border-radius: 4px;
                    color: #5F3C3C;
                    text-align: center;
                    margin: 0 auto 40px;
                    max-width: 800px;
                    display: inline-block;
                }
                
                .sesame-specialties-header {
                    text-align: center;
                    margin-bottom: 40px;
                }
                
                .sesame-products-grid {
                    display: grid;
                    grid-template-columns: repeat({$columns}, 1fr);
                    gap: 30px;
                    margin-bottom: 30px;
                }
                
                @media (max-width: 1024px) {
                    .sesame-products-grid {
                        grid-template-columns: repeat(min({$columns}, 3), 1fr);
                    }
                }
                
                @media (max-width: 768px) {
                    .sesame-products-grid {
                        grid-template-columns: repeat(min({$columns}, 2), 1fr);
                    }
                }
                
                @media (max-width: 480px) {
                    .sesame-products-grid {
                        grid-template-columns: repeat(1, 1fr);
                    }
                }
                
                .sesame-product-item {
                    background-color: #FFFFFF;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                    border: 1px solid #F0F0F0;
                    transition: all 0.4s ease;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }
                
                .sesame-product-image-container {
                    position: relative;
                    overflow: hidden;
                    width: 100%;
                    padding-bottom: 100%; /* Force aspect ratio 1:1 */
                }
                
                .sesame-product-badges {
                    position: absolute;
                    top: 15px;
                    left: 15px;
                    display: flex;
                    flex-direction: column;
                    gap: 5px;
                    z-index: 2;
                    max-width: 70%;
                }
                
                .sesame-product-badge {
                    background-color: rgba(255, 255, 255, 0.85);
                    color: #666;
                    padding: 4px 10px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 500;
                    backdrop-filter: blur(4px);
                    display: inline-block;
                    margin-right: 5px;
                    margin-bottom: 5px;
                }
                
                .sesame-product-image {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.7s ease;
                }
                
                .sesame-product-wishlist {
                    position: absolute;
                    top: 15px;
                    right: 15px;
                    background-color: rgba(255, 255, 255, 0.85);
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    z-index: 2;
                }
                
                .sesame-product-wishlist svg {
                    width: 18px;
                    height: 18px;
                    fill: none;
                    stroke: #8A5D5D;
                    stroke-width: 2;
                    transition: all 0.3s ease;
                }
                
                .sesame-product-content {
                    padding: 15px;
                    display: flex;
                    flex-direction: column;
                    flex-grow: 1;
                }
                
                .sesame-product-persons {
                    font-family: 'Montserrat', sans-serif;
                    font-size: 14px;
                    color: #666666;
                    margin-bottom: 5px;
                }
                
                .sesame-product-title {
                    font-family: 'Playfair Display', serif;
                    font-size: 20px;
                    font-weight: 600;
                    color: #5F3C3C;
                    margin-bottom: 8px;
                    line-height: 1.3;
                }
                
                .sesame-product-title a {
                    color: inherit;
                    text-decoration: none;
                }
                
                .sesame-product-description {
                    font-family: 'Montserrat', sans-serif;
                    font-size: 14px;
                    color: #777777;
                    margin-bottom: 15px;
                    line-height: 1.5;
                    flex-grow: 1;
                }
                
                .sesame-product-price {
                    font-family: 'Montserrat', sans-serif;
                    font-size: 20px;
                    font-weight: 600;
                    color: #8A5D5D;
                    margin-top: auto;
                }
                
                .sesame-product-add-to-cart {
                    margin-top: 15px;
                }
                
                .sesame-product-add-to-cart a {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    background-color: rgba(95, 60, 60, 0.8);
                    color: white;
                    padding: 8px 15px;
                    border-radius: 6px;
                    text-decoration: none;
                    font-family: 'Montserrat', sans-serif;
                    font-size: 14px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                    text-align: center;
                    width: 100%;
                }
                
                .sesame-product-add-to-cart a:hover {
                    background-color: #5F3C3C;
                }
                
                .sesame-product-add-to-cart a .icon {
                    display: inline-flex;
                }
                
                .sesame-view-all {
                    text-align: center;
                    margin-top: 20px;
                }
                
                .sesame-view-all a {
                    font-family: 'Montserrat', sans-serif;
                    font-size: 16px;
                    color: #8A5D5D;
                    text-decoration: none;
                    transition: all 0.3s ease;
                    display: inline-flex;
                    align-items: center;
                }
                
                .sesame-view-all a:hover {
                    color: #5F3C3C;
                }
                
                .sesame-view-all svg {
                    width: 18px;
                    height: 18px;
                    margin-left: 8px;
                }
                
                .sesame-product-item.added-to-cart {
                    animation: pulse-success 1s ease;
                }
                
                @keyframes pulse-success {
                    0% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7); }
                    50% { box-shadow: 0 0 0 10px rgba(76, 175, 80, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
                }
            </style>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Gestionnaire pour les messages d'ajout au panier
                jQuery(document.body).on('added_to_cart', function(event, fragments, cart_hash, button) {
                    // Animation de confirmation
                    var jqButton = jQuery(button);
                    var parentItem = jqButton.closest('.sesame-product-item');
                    
                    if (parentItem.length) {
                        parentItem.addClass('added-to-cart');
                        
                        // Supprimer la classe après l'animation
                        setTimeout(function() {
                            parentItem.removeClass('added-to-cart');
                        }, 1000);
                    }
                });
            });
            </script>
        ";

        // Début du HTML
        echo $custom_css;
        ?>
        <div class="sesame-specialties">
            <div class="sesame-specialties-header">
                <?php if (!empty($title)): ?>
                    <div class="sesame-specialties-caption">Nos Spécialités</div>
                    <h2 class="sesame-specialties-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>

                <?php if (!empty($subtitle)): ?>
                    <div class="sesame-specialties-subtitle"><?php echo esc_html($subtitle); ?></div>
                <?php endif; ?>
            </div>

            <div class="sesame-products-grid">
                <?php
                // Exécuter la requête WooCommerce
                $loop = new WP_Query($args);
                if ($loop->have_posts()) {
                    while ($loop->have_posts()):
                        $loop->the_post();
                        global $product;

                        if (!$product || !$product->is_visible()) {
                            continue;
                        }

                        // Obtenir les données du produit
                        $product_id = $product->get_id();
                        $product_url = get_permalink($product_id);
                        $product_title = get_the_title();
                        $product_image = wp_get_attachment_url(get_post_thumbnail_id($product_id)) ?: wc_placeholder_img_src('full');
                        $price_html = $product->get_price_html();
                        $add_to_cart_url = $product->add_to_cart_url();
                        $add_to_cart_text = $product->add_to_cart_text();
                        $product_description = $product->get_short_description();

                        // Obtenir les données ACF si elles existent
                        $portion_info = '';
                        if (function_exists('get_field')) {
                            $personnes = get_field('personnes', $product_id);
                            if ($personnes) {
                                $portion_info = sprintf(_n('Pour %d personne', 'Pour %d personnes', $personnes, 'sesame-gallery'), $personnes);
                            }
                        }

                        // Gérer les badges
                        $badges = array();
                        if ($show_badges === 'yes') {
                            if ($product->is_on_sale()) {
                                $badges[] = esc_html__('Promotion', 'sesame-gallery');
                            }

                            if ($product->is_featured()) {
                                $badges[] = esc_html__('Populaire', 'sesame-gallery');
                            }

                            if (!$product->is_in_stock()) {
                                $badges[] = esc_html__('Rupture de stock', 'sesame-gallery');
                            }
                        }
                        ?>
                        <div class="sesame-product-item">
                            <div class="sesame-product-image-container">
                                <?php if (!empty($badges)): ?>
                                    <div class="sesame-product-badges">
                                        <?php foreach ($badges as $badge): ?>
                                            <span class="sesame-product-badge"><?php echo $badge; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php echo esc_url($product_url); ?>">
                                    <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>"
                                        class="sesame-product-image">
                                </a>

                                <?php if (function_exists('woosw_init')): ?>
                                    <div class="sesame-product-wishlist" data-id="<?php echo esc_attr($product_id); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path
                                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                            </path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="sesame-product-content">
                                <?php if ($show_persons === 'yes' && !empty($portion_info)): ?>
                                    <div class="sesame-product-persons"><?php echo esc_html($portion_info); ?></div>
                                <?php endif; ?>

                                <h3 class="sesame-product-title">
                                    <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_title); ?></a>
                                </h3>

                                <?php if ($show_description === 'yes' && !empty($product_description)): ?>
                                    <div class="sesame-product-description"><?php echo wp_trim_words($product_description, 20); ?></div>
                                <?php endif; ?>

                                <div class="sesame-product-price"><?php echo $price_html; ?></div>

                                <div class="sesame-product-add-to-cart">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_loop_add_to_cart_link',
                                        sprintf(
                                            '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
                                            esc_url($add_to_cart_url),
                                            'button ajax_add_to_cart add_to_cart_button product_type_' . $product->get_type(),
                                            'data-product_id="' . esc_attr($product_id) . '" data-product_sku="' . esc_attr($product->get_sku()) . '" aria-label="' . esc_attr($add_to_cart_text) . '"',
                                            '<span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg></span> ' . esc_html($add_to_cart_text)
                                        ),
                                        $product
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                } else {
                    echo '<p>' . esc_html__('Aucun produit trouvé.', 'sesame-gallery') . '</p>';
                }
                wp_reset_postdata();
                ?>
            </div>

            <?php if (!empty($settings['view_all_text']) && !empty($settings['view_all_url']['url'])): ?>
                <div class="sesame-view-all">
                    <a href="<?php echo esc_url($settings['view_all_url']['url']); ?>" <?php echo $settings['view_all_url']['is_external'] ? 'target="_blank"' : ''; ?>             <?php echo $settings['view_all_url']['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                        <?php echo esc_html($settings['view_all_text']); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Injecter le CSS du widget
     */
    public function content_template()
    {
        ?>
        <!-- Template pour l'éditeur Elementor -->
        <?php
    }
}