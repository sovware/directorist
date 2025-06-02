<?php

namespace Directorist\App\Setup;

defined( "ABSPATH" ) || exit;

class Activation {
    public static function run() {
        // Run the activation tasks.
        self::create_tables();
    }

    public static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $db_prefix       = "{$wpdb->prefix}directorist_";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        $sql = "CREATE TABLE {$db_prefix}orders (
			id INT AUTO_INCREMENT PRIMARY KEY,
			user_id INT,
			listing_id INT NULL DEFAULT NULL,
			-- plan_id INT,
			order_type ENUM('one_time','recurring') DEFAULT 'one_time',
			amount DECIMAL(10,2),
			currency VARCHAR(10),
			-- coupon_discount DECIMAL(10,2) DEFAULT 0.00,
			final_amount DECIMAL(10,2) DEFAULT 0.00,
			order_status ENUM('pending','paid','failed','cancelled','expired') DEFAULT 'pending',
			expires_at TIMESTAMP NULL DEFAULT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
		);

		CREATE TABLE {$db_prefix}payments (
			id INT AUTO_INCREMENT PRIMARY KEY,
			order_id INT,
			payment_date TIMESTAMP NULL DEFAULT NULL,
			amount DECIMAL(10,2),
			currency VARCHAR(10),
			payment_status ENUM('pending','paid','failed'),
			transaction_id VARCHAR(100),
			payment_method VARCHAR(30),
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
		);

		CREATE TABLE {$db_prefix}subscriptions (
			id INT AUTO_INCREMENT PRIMARY KEY,
			order_id INT,
			`status` ENUM('active','cancelled','past_due','expired'),
			started_at TIMESTAMP NULL DEFAULT NULL,
			current_period_end TIMESTAMP NULL DEFAULT NULL,
			cancelled_at TIMESTAMP NULL DEFAULT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
		);";

        dbDelta( $sql );
    }
}