<?php
/**
 * Directorist conditional logic evaluator.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Conditional_Logic {
    public static function should_show_field( array $field_props, array $posted_data ) {
        $conditional_logic = self::extract_conditional_logic( $field_props );

        if ( empty( $conditional_logic ) || ! is_array( $conditional_logic ) ) {
            return true;
        }

        if ( ! self::is_enabled( isset( $conditional_logic['enabled'] ) ? $conditional_logic['enabled'] : false ) ) {
            return true;
        }

        if ( empty( $conditional_logic['groups'] ) || ! is_array( $conditional_logic['groups'] ) ) {
            return true;
        }

        $group_results = [];

        foreach ( $conditional_logic['groups'] as $group ) {
            if ( empty( $group['conditions'] ) || ! is_array( $group['conditions'] ) ) {
                continue;
            }

            $condition_results = [];

            foreach ( $group['conditions'] as $condition ) {
                if ( empty( $condition['field'] ) || empty( $condition['operator'] ) ) {
                    continue;
                }

                $field_key           = self::normalize_field_key( $condition['field'] );
                $field_value         = self::get_posted_field_value( $field_key, $posted_data );
                $condition_results[] = self::evaluate_condition( $condition, $field_value );
            }

            if ( empty( $condition_results ) ) {
                continue;
            }

            $group_operator = self::normalize_operator( isset( $group['operator'] ) ? $group['operator'] : '', 'AND' );
            $group_results[] = 'OR' === $group_operator ? in_array( true, $condition_results, true ) : ! in_array( false, $condition_results, true );
        }

        if ( empty( $group_results ) ) {
            $result = true;
        } else {
            $global_operator = self::normalize_operator( isset( $conditional_logic['globalOperator'] ) ? $conditional_logic['globalOperator'] : '', 'OR' );
            $result          = 'AND' === $global_operator ? ! in_array( false, $group_results, true ) : in_array( true, $group_results, true );
        }

        return ( isset( $conditional_logic['action'] ) && 'hide' === $conditional_logic['action'] ) ? ! $result : $result;
    }

    private static function extract_conditional_logic( array $field_props ) {
        if ( ! empty( $field_props['conditional_logic_data'] ) ) {
            if ( is_string( $field_props['conditional_logic_data'] ) ) {
                $decoded = json_decode( $field_props['conditional_logic_data'], true );
                if ( is_array( $decoded ) ) {
                    return $decoded;
                }
            } elseif ( is_array( $field_props['conditional_logic_data'] ) ) {
                return $field_props['conditional_logic_data'];
            }
        }

        if ( ! empty( $field_props['options']['conditional_logic']['value'] ) && is_array( $field_props['options']['conditional_logic']['value'] ) ) {
            return $field_props['options']['conditional_logic']['value'];
        }

        if ( ! empty( $field_props['options']['conditional_logic'] ) && is_array( $field_props['options']['conditional_logic'] ) ) {
            if ( ! isset( $field_props['options']['conditional_logic']['value'] ) ) {
                return $field_props['options']['conditional_logic'];
            }
        }

        if ( ! empty( $field_props['conditional_logic'] ) && is_array( $field_props['conditional_logic'] ) ) {
            return $field_props['conditional_logic'];
        }

        return null;
    }

    private static function is_enabled( $value ) {
        return true === $value || 1 === $value || '1' === $value || 'true' === $value;
    }

    private static function normalize_field_key( $field_key ) {
        $field_key = is_scalar( $field_key ) ? trim( (string) $field_key ) : '';

        $map = [
            'title'                          => 'listing_title',
            'description'                    => 'listing_content',
            'content'                        => 'listing_content',
            'categories'                     => 'category',
            'admin_category_select[]'        => 'category',
            'tax_input[at_biz_dir-category][]' => 'category',
            'in_cat'                         => 'category',
            'tags'                           => 'tag',
            'tax_input[at_biz_dir-tags][]'   => 'tag',
            'in_tag[]'                       => 'tag',
            'locations'                      => 'location',
            'tax_input[at_biz_dir-location][]' => 'location',
            'in_loc'                         => 'location',
            'image_upload'                   => 'listing_img',
        ];

        return isset( $map[ $field_key ] ) ? $map[ $field_key ] : $field_key;
    }

    private static function get_posted_field_value( $field_key, array $posted_data ) {
        $taxonomy_value = self::get_taxonomy_field_value( $field_key, $posted_data );
        if ( null !== $taxonomy_value ) {
            return $taxonomy_value;
        }

        $keys = self::get_field_key_aliases( $field_key );

        foreach ( $keys as $key ) {
            if ( isset( $posted_data[ $key ] ) ) {
                return $posted_data[ $key ];
            }
        }

        if ( ! empty( $posted_data['custom_field'] ) && is_array( $posted_data['custom_field'] ) ) {
            foreach ( $keys as $key ) {
                if ( isset( $posted_data['custom_field'][ $key ] ) ) {
                    return $posted_data['custom_field'][ $key ];
                }
            }
        }

        if ( 'privacy_policy' === $field_key ) {
            return '';
        }

        if ( 'listing_img' === $field_key ) {
            foreach ( [ 'listing_img_old', 'listing_img' ] as $key ) {
                if ( ! empty( $posted_data[ $key ] ) ) {
                    return 'uploaded';
                }
            }
        }

        return null;
    }

    private static function get_taxonomy_field_value( $field_key, array $posted_data ) {
        $taxonomy_map = [
            'category' => defined( 'ATBDP_CATEGORY' ) ? ATBDP_CATEGORY : 'at_biz_dir-category',
            'tag'      => defined( 'ATBDP_TAGS' ) ? ATBDP_TAGS : 'at_biz_dir-tags',
            'location' => defined( 'ATBDP_LOCATION' ) ? ATBDP_LOCATION : 'at_biz_dir-location',
        ];

        if ( ! isset( $taxonomy_map[ $field_key ] ) ) {
            return null;
        }

        $taxonomy = $taxonomy_map[ $field_key ];

        if ( ! empty( $posted_data['tax_input'][ $taxonomy ] ) ) {
            return $posted_data['tax_input'][ $taxonomy ];
        }

        return null;
    }

    private static function get_field_key_aliases( $field_key ) {
        $aliases = [ $field_key ];

        $map = [
            'listing_title'   => [ 'post_title', 'title', 'q' ],
            'listing_content' => [ 'content', 'description' ],
            'category'        => [ 'categories', 'admin_category_select', 'admin_category_select[]', 'in_cat' ],
            'tag'             => [ 'tags', 'in_tag', 'in_tag[]' ],
            'location'        => [ 'locations', 'in_loc', 'address' ],
            'listing_img'     => [ 'image_upload', 'listing_img[]' ],
        ];

        if ( isset( $map[ $field_key ] ) ) {
            $aliases = array_merge( $aliases, $map[ $field_key ] );
        }

        if ( 0 !== strpos( $field_key, 'custom-' ) ) {
            $aliases[] = 'custom-' . $field_key;
            $aliases[] = 'custom-' . str_replace( '_', '-', $field_key );
        }

        return array_values( array_unique( $aliases ) );
    }

    private static function evaluate_condition( array $condition, $field_value ) {
        $operator        = strtolower( trim( (string) $condition['operator'] ) );
        $condition_value = isset( $condition['value'] ) ? $condition['value'] : '';

        if ( is_scalar( $condition_value ) && 'uploaded' === strtolower( (string) $condition_value ) ) {
            if ( in_array( $operator, [ 'is', '==', '=' ], true ) ) {
                return 'uploaded' === $field_value || true === $field_value;
            }

            if ( in_array( $operator, [ 'is not', '!=', 'not' ], true ) ) {
                return 'uploaded' !== $field_value && true !== $field_value && self::is_empty( $field_value );
            }

            if ( 'empty' === $operator ) {
                return self::is_empty( $field_value ) || 'uploaded' !== $field_value;
            }

            if ( 'not empty' === $operator ) {
                return ! self::is_empty( $field_value ) && 'uploaded' === $field_value;
            }
        }

        if ( in_array( $operator, [ 'empty', 'is empty' ], true ) ) {
            return self::is_empty( $field_value );
        }

        if ( in_array( $operator, [ 'not empty', 'is not empty' ], true ) ) {
            return ! self::is_empty( $field_value );
        }

        if ( is_array( $field_value ) ) {
            return self::evaluate_array_condition( $field_value, $condition_value, $operator );
        }

        $field_value     = self::normalize_comparable_value( $field_value );
        $condition_value = self::normalize_comparable_value( $condition_value );

        switch ( $operator ) {
            case 'is':
            case '==':
            case '=':
                return (string) $field_value === (string) $condition_value;
            case 'is not':
            case '!=':
            case 'not':
                return (string) $field_value !== (string) $condition_value;
            case 'contains':
                return false !== strpos( (string) $field_value, (string) $condition_value );
            case 'does not contain':
                return false === strpos( (string) $field_value, (string) $condition_value );
            case 'greater than':
            case '>':
                return (float) $field_value > (float) $condition_value;
            case 'less than':
            case '<':
                return (float) $field_value < (float) $condition_value;
            case 'greater than or equal':
            case '>=':
                return (float) $field_value >= (float) $condition_value;
            case 'less than or equal':
            case '<=':
                return (float) $field_value <= (float) $condition_value;
            case 'starts with':
                return 0 === strpos( (string) $field_value, (string) $condition_value );
            case 'ends with':
                $condition_value = (string) $condition_value;
                if ( '' === $condition_value ) {
                    return true;
                }

                return substr( (string) $field_value, -strlen( $condition_value ) ) === $condition_value;
            default:
                return false;
        }
    }

    private static function evaluate_array_condition( array $field_value, $condition_value, $operator ) {
        $field_value = array_values( array_filter( array_map( [ __CLASS__, 'normalize_comparable_value' ], $field_value ), [ __CLASS__, 'is_not_empty_string' ] ) );

        if ( empty( $field_value ) ) {
            if ( in_array( $operator, [ 'empty', 'is empty' ], true ) ) {
                return true;
            }

            if ( in_array( $operator, [ 'not empty', 'is not empty', 'is', '==', '=' ], true ) ) {
                return false;
            }

            if ( in_array( $operator, [ 'is not', '!=', 'not' ], true ) ) {
                return true;
            }

            return false;
        }

        $condition_value = self::normalize_comparable_value( $condition_value );

        switch ( $operator ) {
            case 'is':
            case '==':
            case '=':
                return in_array( $condition_value, $field_value, true ) && count( array_unique( $field_value ) ) === 1;
            case 'contains':
                foreach ( $field_value as $value ) {
                    if ( $value === $condition_value || false !== strpos( $value, $condition_value ) ) {
                        return true;
                    }
                }

                return false;
            case 'is not':
            case '!=':
            case 'not':
            case 'does not contain':
                foreach ( $field_value as $value ) {
                    if ( $value === $condition_value || false !== strpos( $value, $condition_value ) ) {
                        return false;
                    }
                }

                return true;
            default:
                return false;
        }
    }

    private static function normalize_comparable_value( $value ) {
        if ( is_array( $value ) ) {
            if ( isset( $value['button_text'] ) || isset( $value['button_url_label'] ) ) {
                $value = ! empty( $value['button_text'] ) ? $value['button_text'] : ( isset( $value['button_url_label'] ) ? $value['button_url_label'] : '' );
            } elseif ( isset( $value['button_url'] ) ) {
                $value = $value['button_url'];
            } elseif ( isset( $value['name'] ) ) {
                $value = $value['name'];
            } elseif ( isset( $value['label'] ) ) {
                $value = $value['label'];
            } elseif ( isset( $value['value'] ) ) {
                $value = $value['value'];
            } elseif ( isset( $value['id'] ) ) {
                $value = $value['id'];
            } else {
                $value = implode( ',', self::flatten_scalar_values( $value ) );
            }
        }

        return is_scalar( $value ) ? strtolower( trim( (string) $value ) ) : '';
    }

    private static function flatten_scalar_values( array $values ) {
        $flattened = [];

        foreach ( $values as $value ) {
            if ( is_array( $value ) ) {
                $flattened = array_merge( $flattened, self::flatten_scalar_values( $value ) );
                continue;
            }

            if ( is_scalar( $value ) ) {
                $value = trim( (string) $value );

                if ( '' !== $value ) {
                    $flattened[] = $value;
                }
            }
        }

        return $flattened;
    }

    private static function is_empty( $value ) {
        if ( null === $value ) {
            return true;
        }

        if ( is_string( $value ) && '' === trim( $value ) ) {
            return true;
        }

        if ( is_array( $value ) && empty( array_filter( $value, [ __CLASS__, 'is_not_empty_value' ] ) ) ) {
            return true;
        }

        return false;
    }

    private static function is_not_empty_value( $value ) {
        return ! self::is_empty( $value );
    }

    private static function is_not_empty_string( $value ) {
        return '' !== $value;
    }

    private static function normalize_operator( $operator, $default ) {
        $operator = strtoupper( trim( (string) $operator ) );

        return in_array( $operator, [ 'AND', 'OR' ], true ) ? $operator : $default;
    }
}
