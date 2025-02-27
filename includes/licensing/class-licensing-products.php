<?php
declare ( strict_types = 1 );

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;
class Licensing_Products {
	public static function get_templates(): array {
		return self::get_products( 'templates' );
	}

	public static function get_extensions(): array {
		return self::get_products( 'extensions' );
	}

	private static function get_products( string $type ): array {
		return Repository::get_promotional_content()[$type] ?? [];
	}
}