<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'hello-elementor','hello-elementor','hello-elementor-theme-style','hello-elementor-header-footer' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 20 );

/**
 * Endpoints personnalisés pour l'API REST O'Sesame
 * À ajouter dans le fichier functions.php de votre thème
 */

// Enregistrement des endpoints personnalisés
function osesame_register_custom_endpoints()
{
    // Endpoint pour les données de la page d'accueil
    register_rest_route('osesame/v1', '/home-data', array(
        'methods' => 'GET',
        'callback' => 'osesame_get_home_data',
        'permission_callback' => '__return_true'
    ));

    // Endpoint pour les catégories avec produits en vedette
    register_rest_route('osesame/v1', '/categories-with-featured', array(
        'methods' => 'GET',
        'callback' => 'osesame_get_categories_with_featured',
        'permission_callback' => '__return_true'
    ));
}
add_action('rest_api_init', 'osesame_register_custom_endpoints');

/**
 * Retourne les données pour la page d'accueil
 */
function osesame_get_home_data()
{
    // Récupérer les données de la page d'accueil
    $home_data = array(
        'banner' => array(
            'title' => get_field('home_banner_title', 'option'),
            'subtitle' => get_field('home_banner_subtitle', 'option'),
            'image' => get_field('home_banner_image', 'option'),
            'button_text' => get_field('home_banner_button_text', 'option'),
            'button_url' => get_field('home_banner_button_url', 'option')
        ),
        'about_section' => array(
            'title' => get_field('home_about_title', 'option'),
            'content' => get_field('home_about_content', 'option'),
            'image' => get_field('home_about_image', 'option')
        ),
        'features' => get_field('home_features', 'option')
    );

    return rest_ensure_response($home_data);
}

/**
 * Retourne les catégories avec des produits en vedette
 */
function osesame_get_categories_with_featured()
{
    // Récupérer les catégories principales
    $featured_cats = get_field('featured_categories', 'option');

    if (empty($featured_cats)) {
        // Si aucune catégorie n'est configurée, utiliser les 4 premières catégories
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'number' => 4,
            'orderby' => 'count',
            'order' => 'DESC',
            'exclude' => array(get_option('default_product_cat')) // Exclure la catégorie par défaut
        ));

        $featured_cats = array_map(function ($term) {
            return $term->term_id;
        }, $categories);
    }

    $result = array();

    foreach ($featured_cats as $cat_id) {
        $category = get_term($cat_id, 'product_cat');

        if (!$category || is_wp_error($category)) {
            continue;
        }

        // Récupérer les produits en vedette de cette catégorie
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 2,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $cat_id
                )
            ),
            'meta_query' => array(
                array(
                    'key' => '_featured',
                    'value' => 'yes'
                )
            )
        );

        $featured_products = array();
        $products_query = new WP_Query($args);

        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                $product = wc_get_product(get_the_ID());

                $featured_products[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'price' => $product->get_price_html(),
                    'image' => wp_get_attachment_url($product->get_image_id())
                );
            }
        }

        // Si aucun produit en vedette, prendre les 2 derniers
        if (empty($featured_products)) {
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 2,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $cat_id
                    )
                )
            );

            $products_query = new WP_Query($args);

            if ($products_query->have_posts()) {
                while ($products_query->have_posts()) {
                    $products_query->the_post();
                    $product = wc_get_product(get_the_ID());

                    $featured_products[] = array(
                        'id' => $product->get_id(),
                        'name' => $product->get_name(),
                        'price' => $product->get_price_html(),
                        'image' => wp_get_attachment_url($product->get_image_id())
                    );
                }
            }
        }

        wp_reset_postdata();

        // Construire l'objet catégorie
        $cat_thumb_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        $result[] = array(
            'id' => $category->term_id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'image' => $cat_thumb_id ? wp_get_attachment_url($cat_thumb_id) : '',
            'featured_products' => $featured_products
        );
    }

    return rest_ensure_response($result);
}

/**
 * Enregistrement du Custom Post Type pour les services de restauration
 */
function osesame_register_catering_post_type()
{
    $labels = array(
        'name' => 'Services de Restauration',
        'singular_name' => 'Service de Restauration',
        'menu_name' => 'Services Traiteur',
        'name_admin_bar' => 'Service Traiteur',
        'add_new' => 'Ajouter',
        'add_new_item' => 'Ajouter un Service',
        'new_item' => 'Nouveau Service',
        'edit_item' => 'Modifier le Service',
        'view_item' => 'Voir le Service',
        'all_items' => 'Tous les Services',
        'search_items' => 'Rechercher dans les Services',
        'parent_item_colon' => 'Service parent:',
        'not_found' => 'Aucun service trouvé.',
        'not_found_in_trash' => 'Aucun service trouvé dans la corbeille.'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'catering'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-food',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true, // Important pour l'API REST
    );

    register_post_type('catering', $args);
}
add_action('init', 'osesame_register_catering_post_type');

/**
 * Ajouter les champs ACF pour les services de restauration
 * Remarque: Ceci utilise ACF Pro, vous devez l'installer et activer
 */
function osesame_add_catering_acf_fields() {
    acf_add_local_field_group(array(
        'key' => 'group_catering_service',
        'title' => 'Service Traiteur Details',
        'fields' => array(
            array(
                'key' => 'field_categories',
                'label' => 'Catégories',
                'name' => 'categories',
                'type' => 'checkbox',
                'choices' => array(
                    'Mariage' => 'Mariage',
                    'Entreprise' => 'Entreprise',
                    'Anniversaire' => 'Anniversaire',
                    'Réception' => 'Réception',
                    'Séminaire' => 'Séminaire',
                    'Événement privé' => 'Événement privé'
                ),
                'required' => 1,
            ),
            array(
                'key' => 'field_featured_image',
                'label' => 'Image principale',
                'name' => 'featured_image',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
                'required' => 1,
            ),
         array(
    'key' => 'field_gallery',
    'label' => 'Galerie d\'images',
    'name' => 'gallery',
    'type' => 'gallery',
    'instructions' => 'Ajoutez entre 3 et 10 images.',
    'required' => 1,
    'return_format' => 'array',
    'preview_size' => 'medium',
    'insert' => 'append',
    'library' => 'all',
    'min' => 3,
    'max' => 10,
),

            array(
                'key' => 'field_formules',
                'label' => 'Formules associées',
                'name' => 'formules',
                'type' => 'relationship',
                'post_type' => array('formule'),
                'filters' => array('search'),
                'return_format' => 'object',
                'min' => 1,
                'max' => 5,
                'required' => 1,
            ),
            array(
                'key' => 'field_short_description',
                'label' => 'Description courte',
                'name' => 'short_description',
                'type' => 'textarea',
                'rows' => 3,
                'required' => 1,
            ),
            array(
                'key' => 'field_full_description',
                'label' => 'Description complète',
                'name' => 'full_description',
                'type' => 'wysiwyg',
                'required' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'catering',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));
}
add_action('acf/init', 'osesame_add_catering_acf_fields');

/**
 * Enregistrement du Custom Post Type pour les formules
 */

function osesame_register_formule_post_type() {
    $labels = array(
        'name' => 'Formules',
        'singular_name' => 'Formule',
        'menu_name' => 'Formules',
        'add_new' => 'Ajouter une formule',
        'add_new_item' => 'Ajouter une nouvelle formule',
        'edit_item' => 'Modifier la formule',
        'new_item' => 'Nouvelle formule',
        'view_item' => 'Voir la formule',
        'search_items' => 'Rechercher des formules',
        'not_found' => 'Aucune formule trouvée',
        'not_found_in_trash' => 'Aucune formule trouvée dans la corbeille',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'formule'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-list-view',
    );

    register_post_type('formule', $args);
}
add_action('init', 'osesame_register_formule_post_type');

/**
 * Ajouter les champs ACF pour les formules
 */

function osesame_add_formule_acf_fields() {
    acf_add_local_field_group(array(
        'key' => 'group_65f1b123456789',
        'title' => 'Formule Details',
        'fields' => array(
            array(
                'key' => 'field_65f1b123456789_name',
                'label' => 'Nom de la formule',
                'name' => 'name',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_65f1b123456789_price',
                'label' => 'Prix par personne',
                'name' => 'price',
                'type' => 'text',
                'required' => 1,
                'append' => '€',
            ),
            array(
                'key' => 'field_65f1b123456789_desc',
                'label' => 'Description',
                'name' => 'description',
                'type' => 'textarea',
                'required' => 1,
            ),
            array(
                'key' => 'field_65f1b123456789_features',
                'label' => 'Éléments inclus',
                'name' => 'features',
                'type' => 'textarea',
                'required' => 1,
                'instructions' => 'Entrez chaque élément sur une nouvelle ligne',
                'new_lines' => 'br',
            ),
            array(
                'key' => 'field_65f1b123456789_persons',
                'label' => 'Nombre de personnes',
                'name' => 'persons',
                'type' => 'text',
                'required' => 1,
                'instructions' => 'Ex: 50-100 ou 80+',
            ),
            array(
                'key' => 'field_65f1b123456789_popular',
                'label' => 'Formule populaire',
                'name' => 'popular',
                'type' => 'true_false',
                'ui' => 1,
                'ui_on_text' => 'Oui',
                'ui_off_text' => 'Non',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'formule',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));
}
add_action('acf/init', 'osesame_add_formule_acf_fields');

// Autoriser les requêtes CORS
function osesame_add_cors_headers() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
}
add_action('init', 'osesame_add_cors_headers');
