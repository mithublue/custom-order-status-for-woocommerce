<?php

class WOOCOS_Admin_Panel {

    public $order_statuses = [];

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return ${ClassName} An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'build_menu' ]);
        add_action( 'init', array( $this, 'custom_post_registeration' ) );
        add_filter( 'init', [ $this, 'wc_register_post_statuses' ] );
        add_filter( 'wc_order_statuses', array( $this, 'add_custom_statuses_to_filter' ) );
    }

    function build_menu() {
        add_submenu_page(
            'woocommerce',
            __( 'Custom Order Status', 'woocos' ),
            __( 'Custom Order Status', 'woocos' ),
            'manage_options',
            'edit.php?post_type=woocos_order_status'
        );
    }

    public function custom_post_registeration() {
        $labels = array(
            'name'               => _x( 'Custom Order Status', 'custom_order_status', 'woocos' ),
            'singular_name'      => _x( 'Custom Order Status', 'post type singular name', 'woocos' ),
            'menu_name'          => _x( 'Custom Order Status', 'admin menu', 'woocos' ),
            'name_admin_bar'     => _x( 'Custom Order Status', 'add new on admin bar', 'woocos' ),
            'add_new'            => _x( 'Add New', 'custom_order_status', 'woocos' ),
            'add_new_item'       => __( 'Add New Custom Order Status', 'woocos' ),
            'new_item'           => __( 'New Custom Order Status', 'woocos' ),
            'edit_item'          => __( 'Edit Custom Order Status', 'woocos' ),
            'view_item'          => __( 'View Custom Order Status', 'woocos' ),
            'all_items'          => __( 'All Custom Order Status', 'woocos' ),
            'search_items'       => __( 'Search Custom Order Status', 'woocos' ),
            'parent_item_colon'  => __( 'Parent Custom Order Status:', 'woocos' ),
            'not_found'          => __( 'No Custom Order Status found.', 'woocos' ),
            'not_found_in_trash' => __( 'No Custom Order Status found in Trash.', 'woocos' ),
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'woocos' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'woocos_order_status' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title' ),
        );

        register_post_type( 'woocos_order_status', $args );
    }

    public function wc_register_post_statuses() {

        $this->order_statuses = WOOCOS_Order_Status()->get_order_statuses( ['post_name', 'post_title'] );

        foreach ( $this->order_statuses as $k => $status ) {
            register_post_status( 'wc-'.$status['post_name'], array(
                'label' => _x( $status['post_title'], $status['post_title'], 'woocos' ),
                'public' => true,
                'exclude_from_search' => false,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop( $status['post_title']." <span class='count'>(%s)</span>", $status['post_title']." <span class='count'>(%s)</span>" ), // phpcs:ignore
            ) );
        }
    }

    /**
     * @param $order_statuses
     * @return mixed
     */
    public function add_custom_statuses_to_filter( $order_statuses ) {

        if( !empty( $this->order_statuses ) ) {

            foreach ( $this->order_statuses as $k => $status ) {
                $order_statuses['wc-'.$status['post_name']] = _x( $status['post_title'], 'WooCommerce Order status', 'woocos' );
            }
        }

        return $order_statuses;
    }
}

function WOOCOS_Admin_Panel() {
    return WOOCOS_Admin_Panel::instance();
}

WOOCOS_Admin_Panel();