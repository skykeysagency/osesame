<?php
/**
 * Plugin Name: Sésame Image Gallery
 * Description: Widget Elementor personnalisé pour la galerie d'images Sésame
 * Version: 1.0.0
 * Author: Votre Nom
 * Text Domain: sesame-gallery
 */

// Si cette page est accédée directement, on quitte
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe principale du plugin
 */
final class Sesame_Image_Gallery
{
    /**
     * Version du plugin
     */
    const VERSION = '1.0.0';

    /**
     * Instance singleton
     */
    private static $_instance = null;

    /**
     * Obtenir l'instance singleton
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructeur
     */
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    /**
     * Initialiser le plugin
     */
    public function init_plugin()
    {
        // Vérifier si Elementor est installé et activé
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Ajouter les catégories de widgets
        add_action('elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories']);

        // Enregistrer les widgets
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);

        // Enregistrer les styles
        add_action('wp_enqueue_scripts', [$this, 'register_styles']);

        // Enregistrer la taxonomie pour les services traiteur
        add_action('init', [$this, 'register_taxonomies']);

        // Inclure les champs personnalisés pour WooCommerce
        if (class_exists('WooCommerce')) {
            require_once(__DIR__ . '/woocommerce-fields.php');
        }
    }

    /**
     * Message d'erreur si Elementor n'est pas installé
     */
    public function admin_notice_missing_elementor()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            esc_html__('"%1$s" nécessite "%2$s" pour fonctionner.', 'sesame-gallery'),
            '<strong>Sésame Image Gallery</strong>',
            '<strong>Elementor</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Ajouter une catégorie de widgets personnalisée
     */
    public function add_elementor_widget_categories($elements_manager)
    {
        $elements_manager->add_category(
            'sesame-category',
            [
                'title' => esc_html__('Widgets Sésame', 'sesame-gallery'),
                'icon' => 'fa fa-image',
            ]
        );
    }

    /**
     * Enregistrer les widgets
     */
    public function register_widgets()
    {
        // Inclure les fichiers des widgets
        require_once(__DIR__ . '/widgets/image-gallery-widget.php');
        require_once(__DIR__ . '/widgets/specialites-widget.php');
        require_once(__DIR__ . '/widgets/service-traiteur-widget.php');

        // Enregistrer les widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Sesame_Image_Gallery_Widget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Sesame_Specialites_Widget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Sesame_Service_Traiteur_Widget());
    }

    /**
     * Enregistrer les styles
     */
    public function register_styles()
    {
        wp_register_style('sesame-gallery-style', plugins_url('assets/css/style.css', __FILE__));
        wp_enqueue_style('sesame-gallery-style');
    }

    /**
     * Enregistrer les taxonomies personnalisées
     */
    public function register_taxonomies()
    {
        // Taxonomy pour les services traiteur
        $labels = array(
            'name' => _x('Catégories de service traiteur', 'Taxonomy General Name', 'sesame-gallery'),
            'singular_name' => _x('Catégorie de service traiteur', 'Taxonomy Singular Name', 'sesame-gallery'),
            'menu_name' => __('Catégories', 'sesame-gallery'),
            'all_items' => __('Toutes les catégories', 'sesame-gallery'),
            'parent_item' => __('Catégorie parente', 'sesame-gallery'),
            'parent_item_colon' => __('Catégorie parente:', 'sesame-gallery'),
            'new_item_name' => __('Nouvelle catégorie', 'sesame-gallery'),
            'add_new_item' => __('Ajouter une catégorie', 'sesame-gallery'),
            'edit_item' => __('Modifier la catégorie', 'sesame-gallery'),
            'update_item' => __('Mettre à jour la catégorie', 'sesame-gallery'),
            'view_item' => __('Voir la catégorie', 'sesame-gallery'),
            'separate_items_with_commas' => __('Séparer les catégories par des virgules', 'sesame-gallery'),
            'add_or_remove_items' => __('Ajouter ou supprimer des catégories', 'sesame-gallery'),
            'choose_from_most_used' => __('Choisir parmi les plus utilisées', 'sesame-gallery'),
            'popular_items' => __('Catégories populaires', 'sesame-gallery'),
            'search_items' => __('Rechercher des catégories', 'sesame-gallery'),
            'not_found' => __('Non trouvée', 'sesame-gallery'),
            'no_terms' => __('Aucune catégorie', 'sesame-gallery'),
            'items_list' => __('Liste des catégories', 'sesame-gallery'),
            'items_list_navigation' => __('Navigation de la liste des catégories', 'sesame-gallery'),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
        );

        register_taxonomy('catering_category', array('catering'), $args);
    }
}

// Initialiser le plugin
Sesame_Image_Gallery::instance();