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
    protected function _register_controls()
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
                $products_query = new WP_Query($args);

                if ($products_query->have_posts()):
                    while ($products_query->have_posts()):
                        $products_query->the_post();
                        global $product;

                        // Assurez-vous que c'est un produit valide
                        if (!$product || !$product->is_visible()) {
                            continue;
                        }

                        // Obtenir les catégories du produit
                        $categories = get_the_terms($product->get_id(), 'product_cat');
                        $persons_meta = get_post_meta($product->get_id(), 'number_of_persons', true);
                        ?>
                        <div class="sesame-product-item">
                            <div class="sesame-product-image-container">
                                <?php if ($show_badges && !empty($categories)): ?>
                                    <div class="sesame-product-badges">
                                        <?php foreach ($categories as $category): ?>
                                            <span class="sesame-product-badge"><?php echo esc_html($category->name); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php echo esc_url(get_permalink()); ?>" class="sesame-product-link">
                                    <?php echo $product->get_image('woocommerce_thumbnail', ['class' => 'sesame-product-image']); ?>
                                </a>

                                <div class="sesame-product-wishlist">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="sesame-product-content">
                                <?php if ($show_persons && !empty($persons_meta)): ?>
                                    <div class="sesame-product-persons"><?php echo esc_html($persons_meta); ?></div>
                                <?php endif; ?>

                                <h3 class="sesame-product-title">
                                    <a href="<?php echo esc_url(get_permalink()); ?>">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </h3>

                                <?php if ($show_description): ?>
                                    <div class="sesame-product-description">
                                        <?php echo wp_trim_words($product->get_short_description(), 15, '...'); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="sesame-product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>

                                <div class="sesame-product-add-to-cart">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_loop_add_to_cart_link',
                                        sprintf(
                                            '<a href="%s" data-quantity="%s" class="%s ajax_add_to_cart" %s data-product_id="%d">%s</a>',
                                            esc_url($product->add_to_cart_url()),
                                            esc_attr(isset($quantity) ? $quantity : 1),
                                            esc_attr('button'),
                                            isset($attributes) ? wc_implode_html_attributes($attributes) : '',
                                            esc_attr($product->get_id()),
                                            '<span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg></span> ' . esc_html($product->add_to_cart_text())
                                        ),
                                        $product,
                                        []
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                else:
                    echo '<p>' . esc_html__('Aucun produit trouvé.', 'sesame-gallery') . '</p>';
                endif;

                wp_reset_postdata();
                ?>
            </div>

            <div class="sesame-view-all">
                <a href="/boutique">
                    Découvrir tous nos plateaux
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        <?php
    }
}