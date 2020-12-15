<?php

class WOOCOS_Order_Status {

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

    }

    function get_order_statuses( $fields = [] ) {
        $items = get_posts( apply_filters( 'woocos_get_order_status_args', [
            'numposts' => -1,
            'post_type' => 'woocos_order_status',
            'fields' => 'id'
        ] ) );

        $data = [];

        if( !empty( $fields ) ) {

            foreach ( $items as $item ) {

                $itemdata = [];

                foreach ( $fields as $field ) {

                    if( isset( $item->{$field} ) ) {
                         $itemdata[$field] = $item->{$field};
                    }
                }

                $data[] = $itemdata;
            }

            return $data;
        }

        return $items;
    }
}

function WOOCOS_Order_Status() {
    return WOOCOS_Order_Status::instance();
}

WOOCOS_Order_Status();