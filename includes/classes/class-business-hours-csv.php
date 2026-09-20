<?php
/**
 * Business hours and social CSV import/export helpers.
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'ATBDP_Business_Hours_CSV' ) ) :

    class ATBDP_Business_Hours_CSV {
        const COLUMN        = 'business_hours';
        const SOCIAL_COLUMN = 'social_links';

        private $days = [
            'monday'    => 'Monday',
            'tuesday'   => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday'  => 'Thursday',
            'friday'    => 'Friday',
            'saturday'  => 'Saturday',
            'sunday'    => 'Sunday',
        ];

        private $social_fields = [
            'facebook'  => 'Facebook',
            'instagram' => 'Instagram',
            'youtube'   => 'YouTube',
            'linkedin'  => 'LinkedIn',
            'twitter'   => 'Twitter',
            'x'         => 'X',
            'pinterest' => 'Pinterest',
            'tiktok'    => 'TikTok',
        ];

        public function __construct() {
            add_filter( 'directorist_importable_fields', [ $this, 'add_importable_field' ] );
            add_filter( 'directorist_listings_field_label_to_key_map', [ $this, 'add_field_label_aliases' ] );
            add_filter( 'directorist_listings_export_row', [ $this, 'add_export_column' ] );
            add_action( 'directorist_listing_imported', [ $this, 'import_business_hours' ], 10, 2 );
            add_action( 'directorist_listing_imported', [ $this, 'import_social_links' ], 10, 2 );
        }

        public function add_importable_field( $fields ) {
            unset( $fields['bdbh'] );

            if ( ! isset( $fields[ self::COLUMN ] ) ) {
                $fields[ self::COLUMN ] = __( 'Business Hours', 'directorist' );
            }

            if ( ! isset( $fields[ self::SOCIAL_COLUMN ] ) ) {
                $fields[ self::SOCIAL_COLUMN ] = __( 'Social Links', 'directorist' );
            }

            foreach ( $this->get_social_fields() as $network => $label ) {
                $key = 'social_' . $network;

                if ( ! isset( $fields[ $key ] ) ) {
                    $fields[ $key ] = sprintf( __( 'Social - %s', 'directorist' ), $label );
                }
            }

            return $fields;
        }

        public function add_field_label_aliases( $map ) {
            $aliases = [
                'bdbh'            => self::COLUMN,
                'BDBH'            => self::COLUMN,
                'business_hours'  => self::COLUMN,
                'business hours'  => self::COLUMN,
                'Business Hours'  => self::COLUMN,
                'operating_hours' => self::COLUMN,
                'operating hours' => self::COLUMN,
                'Operating Hours' => self::COLUMN,
                'operation_hours' => self::COLUMN,
                'operation hours' => self::COLUMN,
                'Operation Hours' => self::COLUMN,
                'social_links'    => self::SOCIAL_COLUMN,
                'social links'    => self::SOCIAL_COLUMN,
                'Social Links'    => self::SOCIAL_COLUMN,
                'social'          => self::SOCIAL_COLUMN,
                'Social'          => self::SOCIAL_COLUMN,
            ];

            foreach ( $this->get_social_fields() as $network => $label ) {
                $key = 'social_' . $network;

                $aliases[ $key ]                       = $key;
                $aliases[ 'social ' . $network ]       = $key;
                $aliases[ 'Social ' . $label ]         = $key;
                $aliases[ 'Social - ' . $label ]       = $key;
                $aliases[ 'Social Media - ' . $label ] = $key;
                $aliases[ 'Social Media ' . $label ]   = $key;
            }

            return array_merge( $map, $aliases );
        }

        public function add_export_column( $row ) {
            unset( $row['bdbh'] );

            $row[ self::COLUMN ] = $this->format_business_hours( get_the_ID() );

            $socials = $this->get_normalized_social_links( get_the_ID() );
            foreach ( $this->get_social_fields() as $network => $label ) {
                if ( ! empty( $socials[ $network ] ) ) {
                    $row[ 'social_' . $network ] = $socials[ $network ];
                }
            }

            return $row;
        }

        public function import_business_hours( $post_id, $post ) {
            $value = $this->find_business_hours_value( $post );

            if ( '' === $value ) {
                $value = get_post_meta( $post_id, '_' . self::COLUMN, true );
            }

            if ( '' === trim( (string) $value ) ) {
                if ( get_post_meta( $post_id, '_disable_bz_hour_listing', true ) ) {
                    delete_post_meta( $post_id, '_' . self::COLUMN );
                    return;
                }

                $this->enable_existing_business_hours_meta( $post_id );
                delete_post_meta( $post_id, '_' . self::COLUMN );
                return;
            }

            $parsed = $this->parse_business_hours( $value );

            if ( empty( $parsed['handled'] ) ) {
                return;
            }

            if ( ! empty( $parsed['is_247'] ) ) {
                update_post_meta( $post_id, '_enable247hour', 'always' );
                update_post_meta( $post_id, '_bdbh', [] );
            } else {
                delete_post_meta( $post_id, '_enable247hour' );
                update_post_meta( $post_id, '_bdbh', $parsed['hours'] );
            }

            update_post_meta( $post_id, '_bdbh_version', 'update' );
            update_post_meta( $post_id, '_enable_bz_hour_listing', 'enable' );
            delete_post_meta( $post_id, '_disable_bz_hour_listing' );
            delete_post_meta( $post_id, '_' . self::COLUMN );
        }

        public function import_social_links( $post_id, $post ) {
            $socials = [];

            $combined = $this->find_social_links_value( $post );

            if ( '' === $combined ) {
                $combined = get_post_meta( $post_id, '_' . self::SOCIAL_COLUMN, true );
            }

            if ( '' !== trim( (string) $combined ) ) {
                $socials = $this->parse_social_links( $combined );
            }

            foreach ( array_keys( $this->get_social_fields() ) as $network ) {
                $value = $this->find_social_network_value( $post, $network );

                if ( '' === $value ) {
                    $value = get_post_meta( $post_id, '_social_' . $network, true );
                }

                if ( '' !== trim( (string) $value ) ) {
                    $url = $this->sanitize_social_url( $value );

                    if ( $url ) {
                        $socials[ $network ] = $url;
                    }
                }
            }

            if ( ! empty( $socials ) ) {
                $meta = [];

                foreach ( $socials as $network => $url ) {
                    if ( '' === $url ) {
                        continue;
                    }

                    $meta[] = [
                        'id'  => $network,
                        'url' => $url,
                    ];
                }

                update_post_meta( $post_id, '_social', $meta );
            }

            delete_post_meta( $post_id, '_' . self::SOCIAL_COLUMN );

            foreach ( array_keys( $this->get_social_fields() ) as $network ) {
                delete_post_meta( $post_id, '_social_' . $network );
            }
        }

        private function find_business_hours_value( $post ) {
            $keys = [
                self::COLUMN,
                'Business Hours',
                'business hours',
                'Operating Hours',
                'operating hours',
                'operating_hours',
                'Operation Hours',
                'operation hours',
                'operation_hours',
            ];

            foreach ( $keys as $key ) {
                if ( isset( $post[ $key ] ) && '' !== trim( (string) $post[ $key ] ) ) {
                    return $post[ $key ];
                }
            }

            return '';
        }

        private function find_social_links_value( $post ) {
            $keys = [
                self::SOCIAL_COLUMN,
                'Social Links',
                'social links',
                'Social',
                'social',
            ];

            foreach ( $keys as $key ) {
                if ( isset( $post[ $key ] ) && '' !== trim( (string) $post[ $key ] ) ) {
                    return $post[ $key ];
                }
            }

            return '';
        }

        private function find_social_network_value( $post, $network ) {
            $social_fields = $this->get_social_fields();
            $label         = $social_fields[ $network ] ?? $network;
            $keys          = [
                'social_' . $network,
                'Social ' . $label,
                'Social - ' . $label,
                'Social Media - ' . $label,
                'Social Media ' . $label,
                $label,
            ];

            foreach ( $keys as $key ) {
                if ( isset( $post[ $key ] ) && '' !== trim( (string) $post[ $key ] ) ) {
                    return $post[ $key ];
                }
            }

            return '';
        }

        private function enable_existing_business_hours_meta( $post_id ) {
            $hours = get_post_meta( $post_id, '_bdbh', true );

            if ( empty( $hours ) || ! is_array( $hours ) ) {
                return;
            }

            update_post_meta( $post_id, '_bdbh_version', 'update' );
            update_post_meta( $post_id, '_enable_bz_hour_listing', 'enable' );
            delete_post_meta( $post_id, '_disable_bz_hour_listing' );
        }

        private function format_business_hours( $post_id ) {
            if ( get_post_meta( $post_id, '_disable_bz_hour_listing', true ) ) {
                return '';
            }

            $enable_247 = get_post_meta( $post_id, '_enable247hour', true );

            if ( ! empty( $enable_247 ) ) {
                return '24/7';
            }

            $hours = get_post_meta( $post_id, '_bdbh', true );

            if ( empty( $hours ) || ! is_array( $hours ) ) {
                return '';
            }

            $parts = [];

            foreach ( $this->days as $day => $label ) {
                $day_hours = $this->get_business_hours_for_day( $hours, $day );

                if ( ! $this->is_enabled( $day_hours['enable'] ?? '' ) ) {
                    if ( $this->has_business_hour_ranges( $day_hours ) && ! $this->is_closed( $day_hours ) ) {
                        $day_hours['enable'] = 'enable';
                    } else {
                        $parts[] = $label . ' Closed';
                        continue;
                    }
                }

                if ( $this->is_all_day( $day_hours['remain_close'] ?? '' ) || $this->is_all_day( $day_hours['always_open'] ?? '' ) || $this->is_all_day( $day_hours['all_day'] ?? '' ) ) {
                    $parts[] = $label . ' 24 Hours';
                    continue;
                }

                $slots = [];

                foreach ( $this->extract_business_hours_ranges( $day_hours ) as $range ) {
                    $start = $this->normalize_time( $range['open'] ?? '' );
                    $close = $this->normalize_time( $range['close'] ?? '' );

                    if ( '' === $start || '' === $close ) {
                        continue;
                    }

                    $slots[] = $start . '-' . $close;
                }

                $parts[] = $label . ' ' . ( $slots ? implode( ', ', $slots ) : 'Closed' );
            }

            return implode( '; ', $parts );
        }

        private function get_normalized_social_links( $post_id ) {
            $socials = get_post_meta( $post_id, '_social', true );

            if ( empty( $socials ) || ! is_array( $socials ) ) {
                return [];
            }

            $normalized = [];

            foreach ( $socials as $social ) {
                if ( ! is_array( $social ) || empty( $social['id'] ) || empty( $social['url'] ) || ! is_scalar( $social['id'] ) || ! is_scalar( $social['url'] ) ) {
                    continue;
                }

                $network = $this->normalize_social_network( $social['id'] );

                if ( empty( $network ) ) {
                    continue;
                }

                $normalized[ $network ] = esc_url_raw( $social['url'] );
            }

            return $normalized;
        }

        private function parse_social_links( $value ) {
            $value   = trim( wp_strip_all_tags( (string) $value ) );
            $socials = [];

            if ( '' === $value ) {
                return $socials;
            }

            foreach ( preg_split( '/\s*;\s*/', $value ) as $segment ) {
                $segment = trim( $segment );

                if ( '' === $segment ) {
                    continue;
                }

                if ( preg_match( '/^([a-z0-9 _-]+)\s*[:|,]\s*(https?:\/\/.+)$/i', $segment, $matches ) ) {
                    $network = $this->normalize_social_network( $matches[1] );
                    $url     = $this->sanitize_social_url( $matches[2] );

                    if ( $network && $url ) {
                        $socials[ $network ] = $url;
                    }
                }
            }

            return $socials;
        }

        private function normalize_social_network( $network ) {
            if ( ! is_scalar( $network ) ) {
                return '';
            }

            $network = strtolower( trim( (string) $network ) );
            $network = str_replace( [ 'social media - ', 'social media ', 'social - ', 'social ' ], '', $network );
            $network = str_replace( [ ' ', '_' ], '-', $network );

            $aliases = [
                'fb'        => 'facebook',
                'facebook'  => 'facebook',
                'ig'        => 'instagram',
                'insta'     => 'instagram',
                'instagram' => 'instagram',
                'youtube'   => 'youtube',
                'you-tube'  => 'youtube',
                'yt'        => 'youtube',
                'linkedin'  => 'linkedin',
                'linked-in' => 'linkedin',
                'twitter'   => 'twitter',
                'x'         => 'x',
                'pinterest' => 'pinterest',
                'tiktok'    => 'tiktok',
                'tik-tok'   => 'tiktok',
            ];

            if ( isset( $aliases[ $network ] ) ) {
                return $aliases[ $network ];
            }

            return isset( $this->get_social_fields()[ $network ] ) ? $network : '';
        }

        private function get_social_fields() {
            if ( function_exists( 'ATBDP' ) ) {
                $directorist = ATBDP();

                if ( is_object( $directorist ) && isset( $directorist->helper ) && method_exists( $directorist->helper, 'social_links' ) ) {
                    $social_fields = $directorist->helper->social_links();

                    if ( is_array( $social_fields ) && ! empty( $social_fields ) ) {
                        return array_merge( $this->social_fields, $social_fields );
                    }
                }
            }

            return $this->social_fields;
        }

        private function get_business_hours_for_day( $hours, $day ) {
            if ( ! is_array( $hours ) ) {
                return [];
            }

            $keys = [
                $day,
                ucfirst( $day ),
                substr( $day, 0, 3 ),
                ucfirst( substr( $day, 0, 3 ) ),
            ];

            foreach ( $keys as $key ) {
                if ( isset( $hours[ $key ] ) && is_array( $hours[ $key ] ) ) {
                    return $hours[ $key ];
                }
            }

            foreach ( $hours as $key => $value ) {
                if ( ! is_string( $key ) || ! is_array( $value ) ) {
                    continue;
                }

                if ( $day === $this->normalize_day( $key ) ) {
                    return $value;
                }
            }

            return [];
        }

        private function has_business_hour_ranges( $value ) {
            return ! empty( $this->extract_business_hours_ranges( $value ) );
        }

        private function extract_business_hours_ranges( $value ) {
            if ( ! is_array( $value ) || $this->is_closed( $value ) ) {
                return [];
            }

            $open_keys  = [ 'open', 'start', 'opening', 'opening_time', 'start_time', 'from' ];
            $close_keys = [ 'close', 'end', 'closing', 'closing_time', 'end_time', 'to' ];
            $open       = null;
            $close      = null;

            foreach ( $open_keys as $key ) {
                if ( ! empty( $value[ $key ] ) ) {
                    $open = $value[ $key ];
                    break;
                }
            }

            foreach ( $close_keys as $key ) {
                if ( ! empty( $value[ $key ] ) ) {
                    $close = $value[ $key ];
                    break;
                }
            }

            if ( is_array( $open ) && is_array( $close ) ) {
                $ranges = [];

                foreach ( $open as $index => $open_time ) {
                    if ( empty( $close[ $index ] ) ) {
                        continue;
                    }

                    $ranges[] = [
                        'open'  => $open_time,
                        'close' => $close[ $index ],
                    ];
                }

                return $ranges;
            }

            if ( is_array( $open ) || is_array( $close ) ) {
                return [];
            }

            if ( $open && $close ) {
                return [
                    [
                        'open'  => $open,
                        'close' => $close,
                    ],
                ];
            }

            $ranges = [];

            foreach ( $value as $item ) {
                if ( is_array( $item ) ) {
                    $ranges = array_merge( $ranges, $this->extract_business_hours_ranges( $item ) );
                }
            }

            return $ranges;
        }

        private function is_closed( $value ) {
            if ( ! is_array( $value ) ) {
                return false;
            }

            foreach ( [ 'closed', 'is_closed', 'disabled' ] as $key ) {
                if ( isset( $value[ $key ] ) && $this->is_truthy( $value[ $key ] ) ) {
                    return true;
                }
            }

            return isset( $value['enable'] ) && is_scalar( $value['enable'] ) && '' !== trim( (string) $value['enable'] ) && ! $this->is_enabled( $value['enable'] );
        }

        private function is_truthy( $value ) {
            if ( ! is_scalar( $value ) ) {
                return false;
            }

            return in_array( strtolower( trim( (string) $value ) ), [ '1', 'on', 'yes', 'true', 'enable', 'enabled', 'closed' ], true );
        }

        private function sanitize_social_url( $url ) {
            if ( ! is_scalar( $url ) ) {
                return '';
            }

            $url    = trim( (string) $url );
            $scheme = wp_parse_url( $url, PHP_URL_SCHEME );

            if ( ! in_array( strtolower( (string) $scheme ), [ 'http', 'https' ], true ) ) {
                return '';
            }

            return esc_url_raw( $url );
        }

        private function parse_business_hours( $value ) {
            $value = trim( wp_strip_all_tags( (string) $value ) );

            if ( '' === $value ) {
                return [ 'handled' => false ];
            }

            if ( preg_match( '/^(24\s*\/\s*7|24\s*-\s*7|always\s+open|open\s+24)/i', $value ) ) {
                return [
                    'handled' => true,
                    'is_247'  => true,
                ];
            }

            $hours   = $this->get_empty_days();
            $handled = false;

            foreach ( preg_split( '/\s*;\s*/', $value ) as $segment ) {
                $segment = trim( $segment );

                if ( '' === $segment ) {
                    continue;
                }

                if ( ! preg_match( '/^(mon(?:day)?|tue(?:s|sday|day)?|wed(?:nesday)?|thu(?:rs|rsday|day)?|fri(?:day)?|sat(?:urday)?|sun(?:day)?)\s*:?\s*(.*)$/i', $segment, $matches ) ) {
                    continue;
                }

                $day  = $this->normalize_day( $matches[1] );
                $body = trim( $matches[2] );

                if ( empty( $day ) ) {
                    continue;
                }

                $handled = true;

                if ( '' === $body || preg_match( '/^(closed|close|off|n\/a)$/i', $body ) ) {
                    $hours[ $day ]['enable'] = '';
                    continue;
                }

                if ( preg_match( '/(24\s*hours?|all\s*day)/i', $body ) ) {
                    $hours[ $day ] = [
                        'enable'       => 'enable',
                        'remain_close' => 'open',
                        'start'        => [],
                        'close'        => [],
                    ];
                    continue;
                }

                $starts = [];
                $closes = [];

                preg_match_all( '/(\d{1,2}(?::\d{2})?\s*(?:a\.?m\.?|p\.?m\.?)?)\s*(?:-|–|—|\bto\b)\s*(\d{1,2}(?::\d{2})?\s*(?:a\.?m\.?|p\.?m\.?)?)/i', $body, $slots, PREG_SET_ORDER );

                foreach ( $slots as $slot ) {
                    $start = $this->normalize_time( $slot[1] );
                    $close = $this->normalize_time( $slot[2] );

                    if ( '' === $start || '' === $close ) {
                        continue;
                    }

                    $starts[] = $start;
                    $closes[] = $close;
                }

                if ( $starts ) {
                    $hours[ $day ] = [
                        'enable' => 'enable',
                        'start'  => $starts,
                        'close'  => $closes,
                    ];
                } else {
                    $hours[ $day ]['enable'] = '';
                }
            }

            if ( ! $handled ) {
                return [ 'handled' => false ];
            }

            return [
                'handled' => true,
                'is_247'  => false,
                'hours'   => $hours,
            ];
        }

        private function get_empty_days() {
            $hours = [];

            foreach ( array_keys( $this->days ) as $day ) {
                $hours[ $day ] = [
                    'enable' => '',
                    'start'  => [],
                    'close'  => [],
                ];
            }

            return $hours;
        }

        private function normalize_day( $day ) {
            if ( ! is_scalar( $day ) ) {
                return '';
            }

            $day = strtolower( trim( (string) $day ) );

            $aliases = [
                'mon'       => 'monday',
                'monday'    => 'monday',
                'tue'       => 'tuesday',
                'tues'      => 'tuesday',
                'tuesday'   => 'tuesday',
                'wed'       => 'wednesday',
                'wednesday' => 'wednesday',
                'thu'       => 'thursday',
                'thur'      => 'thursday',
                'thurs'     => 'thursday',
                'thursday'  => 'thursday',
                'fri'       => 'friday',
                'friday'    => 'friday',
                'sat'       => 'saturday',
                'saturday'  => 'saturday',
                'sun'       => 'sunday',
                'sunday'    => 'sunday',
            ];

            return $aliases[ $day ] ?? '';
        }

        private function normalize_time( $time ) {
            if ( ! is_scalar( $time ) ) {
                return '';
            }

            $time = trim( (string) $time );

            if ( '' === $time ) {
                return '';
            }

            $timestamp = strtotime( $time );

            if ( false === $timestamp ) {
                return '';
            }

            return date( 'H:i', $timestamp );
        }

        private function is_enabled( $value ) {
            if ( ! is_scalar( $value ) ) {
                return false;
            }

            return in_array( strtolower( trim( (string) $value ) ), [ '1', 'on', 'yes', 'true', 'enable', 'enabled', 'open' ], true );
        }

        private function is_all_day( $value ) {
            if ( ! is_scalar( $value ) ) {
                return false;
            }

            return in_array( strtolower( trim( (string) $value ) ), [ '1', 'on', 'yes', 'true', 'open', '24', '24hours', '24 hours' ], true );
        }
    }

endif;
