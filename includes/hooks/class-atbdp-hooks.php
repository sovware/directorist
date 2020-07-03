<?php

if ( ! class_exists( 'ATBDP_Hooks' ) ) :
    class ATBDP_Hooks {
        public static $hooks = [];
        public static $helper_hooks = [];

        public function __construct() {
            $this->hooks =  $this->get_hooks();
            $this->register_hooks( $this->hooks );
        }

        /**
         * Get Action Hooks
         *
         * @return array
         */
        public function get_hooks() {
            return [
                'title_update' => [
                    'name'     => 'the_title',
                    'type'     => 'action',
                    'callback' => ATBDP_Title_Update::class,
                    'priority' => 10,
                    'args'     => 2,
                ],
            ];
        }

        /**
         * Register Hooks
         *
         * @return void
         */
        private function register_hooks( array $hooks ) {
            if ( ! count( $hooks ) ) { return; }

            foreach ( $hooks as $hook ) {
                self::add_hook( $hook );
            }
        }

        /**
         * Register Hook
         *
         * @return void
         */
        public static function register_hook( $hook_id, array $args = []) {
            if ( ! isset( self::$hooks[ $hook_id]  ) && ! isset( self::$helper_hooks[ $hook_id ]  ) ) { 
                return;
            }

            $hook = self::$hooks[ $hook_id ];

            if ( isset( $args['priority'] )  ) {
                $hook['priority'] = $args['priority'];
            }

            self::add_hook( $hook );
        }

        /**
         * Register Add Hook
         *
         * @return void
         */
        public static function add_hook( array $hook = [] ) {
            if ( class_exists( $hook['callback'] ) ) {
                if ( method_exists( $hook['callback'], 'run' ) ) {
                    $class_name    = $hook['callback'];
                    $callback      = new $class_name();
                    $priority      = ( isset( $hook['priority'] ) ) ? $hook['priority'] : 10;
                    $accepted_args = ( isset( $hook['args'] ) ) ? $hook['args'] : 1;

                    if ( 'action' === $hook['type'] ) {
                        add_action( $hook['name'], [$callback, 'run'], $priority, $accepted_args );
                    }

                    if ( 'filter' === $hook['type'] ) {
                        add_filter( $hook['name'], [$callback, 'run'], $priority, $accepted_args );
                    }

                }
            }
        }
    }
endif;