<?php
/**
 * Ajout de champs personnalisés pour WooCommerce
 */

// Si cette page est accédée directement, on quitte
if (!defined('ABSPATH')) {
    exit;
}

class Sesame_WooCommerce_Fields
{
    /**
     * Constructeur
     */
    public function __construct()
    {
        // Ajouter un onglet personnalisé aux produits
        add_filter('woocommerce_product_data_tabs', [$this, 'add_custom_product_data_tab']);

        // Ajouter les champs dans l'onglet personnalisé
        add_action('woocommerce_product_data_panels', [$this, 'add_custom_product_data_fields']);

        // Sauvegarder les données du produit
        add_action('woocommerce_process_product_meta', [$this, 'save_custom_product_data_fields']);
    }

    /**
     * Ajouter un onglet personnalisé aux produits
     */
    public function add_custom_product_data_tab($tabs)
    {
        $tabs['sesame_options'] = [
            'label' => __('Options Sésame', 'sesame-gallery'),
            'target' => 'sesame_product_data',
            'class' => [],
            'priority' => 80,
        ];
        return $tabs;
    }

    /**
     * Ajouter les champs dans l'onglet personnalisé
     */
    public function add_custom_product_data_fields()
    {
        ?>
        <div id="sesame_product_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php
                woocommerce_wp_text_input([
                    'id' => 'number_of_persons',
                    'label' => __('Nombre de personnes', 'sesame-gallery'),
                    'placeholder' => '4-6 personnes',
                    'desc_tip' => true,
                    'description' => __('Indiquez le nombre de personnes que ce produit peut servir (ex: "4-6 personnes").', 'sesame-gallery'),
                ]);
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Sauvegarder les données du produit
     */
    public function save_custom_product_data_fields($post_id)
    {
        $number_of_persons = isset($_POST['number_of_persons']) ? sanitize_text_field($_POST['number_of_persons']) : '';
        update_post_meta($post_id, 'number_of_persons', $number_of_persons);
    }
}

// Initialiser la classe
new Sesame_WooCommerce_Fields();