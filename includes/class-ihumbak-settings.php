<?php
/**
 * Settings Management Class
 *
 * @package Ihumbak_Chat
 */

class Ihumbak_Settings {

    /**
     * Instance of this class
     *
     * @var Ihumbak_Settings
     */
    private static $instance = null;

    /**
     * Settings validator instance
     *
     * @var Ihumbak_Settings_Validator
     */
    private $validator = null;

    /**
     * Get instance
     *
     * @return Ihumbak_Settings
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->validator = new Ihumbak_Settings_Validator();
    }

    /**
     * Get option value
     *
     * @param string $key     Option key.
     * @param mixed  $default Default value.
     * @return mixed
     */
    public function get_option( $key, $default = '' ) {
        return get_option( $key, $default );
    }

    /**
     * Update option value
     *
     * @param string $key   Option key.
     * @param mixed  $value Option value.
     * @return bool
     */
    public function update_option( $key, $value ) {
        return update_option( $key, $value );
    }

    /**
     * Get all options
     *
     * @return array
     */
    public function get_all_options() {
        $keys = array_keys( Ihumbak_Settings_Defaults::get_all() );
        $options = array();

        foreach ( $keys as $key ) {
            $default = Ihumbak_Settings_Defaults::get( $key );
            $options[ $key ] = $this->get_option( $key, $default );
        }

        // Fallback for admin email
        if ( empty( $options['ihumbak_admin_email'] ) ) {
            $options['ihumbak_admin_email'] = get_option( 'admin_email' );
        }

        return $options;
    }

    /**
     * Save settings
     *
     * @param array $data Settings data.
     * @return bool|WP_Error
     */
    public function save( $data ) {
        return $this->validator->save_all( $data );
    }
}
