<?php
namespace Sccwd_MM;

if ( !defined( 'ABSPATH' ) ) {
    return;
}

/**
 * SCCWebDev Maintenance Mode plugin Admin class
 */
class Admin {
    /**
     * The instance of the Admin class
     * 
     * @var null
     */
    protected static $instance = null;
    
    /**
     * The constructor for the Admin class
     */
    private function __construct() {
        add_action( 'admin_menu', array( $this, 'settings_page' ) );
        add_action( 'admin_init', array( $this, 'load_admin_resources' ) );
        add_action( 'admin_post_sccwd_save_admin_settings', array( $this, 'process_admin_settings' ) );
    }

    /**
     * Get the instance of the Admin class
     * 
     * @return Admin
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Create the settings page for the Maintenance Mode plugin
     * 
     * @return void
     */
    public function settings_page() {
        add_menu_page( 
            'Maintenance Mode', 
            'Maintenance Mode', 
            'activate_plugins', 
            'sccwd_maintenance_mode', 
            array( $this, 'settings_page_template' ),
            'dashicons-hammer', 
            100 
        );
    }

    /**
     * Load the template for the admin menu page
     * 
     * @return void
     */
    public function settings_page_template() {
        include SCCWD_MAINTENANCE_MODE_PATH . 'templates/admin/maintenance-settings.php';
    }

    /**
     * Load the necessary admin resources for the maintenance mode settings page
     * 
     * @return void
     */
    public function load_admin_resources() {
        if ( isset( $_GET['page'] ) && $_GET['page'] === 'sccwd_maintenance_mode' ) {
            wp_register_style( 'sccwd_bootstrap_min', plugins_url( 'assets/styles/bootstrap.min.css', SCCWD_MAINTENANCE_MODE_FILE ) );
            wp_enqueue_style( 'sccwd_bootstrap_min' );
    
            wp_register_style( 'sccwd_admin_settings', plugins_url( 'assets/styles/admin/settings.css', SCCWD_MAINTENANCE_MODE_FILE ) );
            wp_enqueue_style( 'sccwd_admin_settings' );
            
            wp_register_script( 'sccwd_validate_settings', plugins_url( 'assets/js/admin/validate-settings.js', SCCWD_MAINTENANCE_MODE_FILE ), array(), false, true );
            wp_enqueue_script( 'sccwd_validate_settings' );
            wp_add_inline_script( 'sccwd_validate_settings', 'const MAINTENANCEMODE = ' . json_encode( array(
                'maintenanceModeStatus' => get_option( 'sccwd_activate_maintenance_mode', null ),
                'maintenanceModeHeading' => get_option( 'sccwd_maintenance_mode_heading', null ),
                'maintenanceModeMessage' => get_option( 'sccwd_maintenance_mode_message', null ),
                'maintenanceModeBackground' => get_option( 'sccwd_maintenance_mode_background_image', null ),
            ) ), 'before' );
        }
    }

    /**
     * Process the maintenance mode settings selected by the user
     * 
     * @return void
     */
    public function process_admin_settings() {
        if ( !current_user_can( 'activate_plugins' ) ) {
            wp_die( __( 'You are not authorized to update this form.', 'sccwd-maintenance-mode' ) );
        }
        
        check_admin_referer( 'sccwd_verify_admin_settings_save' );
    
        # Whether or not to activate maintenance mode
        update_option( 'sccwd_activate_maintenance_mode', $_POST['maintenace-mode-status'], true );
    
        $maintenance_mode_heading = !empty( $_POST['maintenance-mode-heading'] ) ? sanitize_text_field( $_POST['maintenance-mode-heading'] ) : 'Maintenance Mode';
        update_option( 'sccwd_maintenance_mode_heading', $maintenance_mode_heading, true );
    
        $maintenance_mode_message = !empty( $_POST['maintenance-mode-message'] ) ? sanitize_text_field( $_POST['maintenance-mode-message'] ) : 'Our site is currently undergoing maintenance. Thank you for your patience.';
        update_option( 'sccwd_maintenance_mode_message', $maintenance_mode_message, true );
    
        $maintenance_mode_background_image = $_POST['maintenance-mode-background-image'];
        update_option( 'sccwd_maintenance_mode_background_image', $maintenance_mode_background_image, true );
    
        # Redirect back to the admin settings page
        wp_redirect( $_POST['_wp_http_referer'] . '&save=success' );
        exit;
    }
}