<?php

add_action( 'woocommerce_order_status_completed', 'piggy_woo_txn_result' );

function piggy_woo_txn_result( $id ) {
	global $wpdb;
	if ( $id ) {
		$file_path = false;
		if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/classes/class-wc-order.php' ) ) {
			$file_path = WP_PLUGIN_DIR . '/woocommerce/classes/class-wc-order.php';
		} else if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/includes/class-wc-order.php' ) ) {
			$file_path = WP_PLUGIN_DIR . '/woocommerce/includes/class-wc-order.php';				
		}
		
		if ( $file_path ) {
			require_once( $file_path );
			$order = new WC_Order( $id );
			if ( $order ) {
				$contents = $order->get_items();
				if ( $contents ) {
					foreach( $contents as $item ) {
						piggy_send_notification_message( $item['name'] . ' - $' . sprintf( '%0.2f', $item['line_subtotal'] ) );
					}
				}
			}			
		}
	}
}
