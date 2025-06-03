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

        $sql = "CREATE TABLE {$db_prefix}subscriptions (
			id INT NOT NULL AUTO_INCREMENT,
			plan_id INT NULL,
			user_id INT NOT NULL,
			`status` ENUM('pending','active','trialing','cancelled','paused','past_due','expired') NOT NULL DEFAULT 'pending',
			started_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			current_period_end TIMESTAMP NULL DEFAULT NULL,
			cancelled_at TIMESTAMP NULL DEFAULT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		);

		-- ORDERS
		CREATE TABLE {$db_prefix}orders (
			id INT NOT NULL AUTO_INCREMENT,
			subscription_id INT NULL,
			user_id INT NOT NULL,
			listing_id INT NULL,
			plan_id INT NULL,
			is_featured_listing TINYINT DEFAULT 0,
			`type` ENUM('one_time','recurring') NOT NULL,
			amount DECIMAL(10,2) NOT NULL,
			currency VARCHAR(10) NOT NULL,
			coupon_discount DECIMAL(10,2) DEFAULT 0.00,
			final_amount DECIMAL(10,2) DEFAULT 0.00,
			`status` ENUM('pending','paid','failed','cancelled','expired','refunded','unpaid') NOT NULL DEFAULT 'pending',
			expires_at TIMESTAMP NULL DEFAULT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		);

		-- PAYMENTS
		CREATE TABLE {$db_prefix}payments (
			id INT NOT NULL AUTO_INCREMENT,
			order_id INT NOT NULL,
			amount DECIMAL(10,2) NOT NULL,
			currency VARCHAR(10) NOT NULL,
			`status` ENUM('pending','paid','failed','cancelled','refunded','unpaid','expired') NOT NULL DEFAULT 'pending',
			transaction_id VARCHAR(100) NULL,
			method VARCHAR(30),
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		);";

        dbDelta( $sql );
    }
}