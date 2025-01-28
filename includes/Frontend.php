<?php
namespace Sccwd_MM;

if ( !defined( 'ABSPATH' ) ) {
    return;
}

/**
 * SCCWebDev Maintenance Mode plugin Frontend class
 */
class Frontend {
    /**
     * The instance of the Frontend class
     * 
     * @var null
     */
    protected static $instance = null;

    /**
     * The maintenance mode status
     * 
     * @var bool
     */
    protected $maintenance_mode = false;

    /**
     * The constructor for the Frontend class
     */
    private function __construct() {
        add_action( 'set_current_user', array( $this, 'set_maintenance_mode' ), 10 );
        add_action( 'init', array( $this, 'start_output_buffer' ), 10 );
        add_action( 'wp_loaded', array( $this, 'wp_maintenance_mode'), 10 );
        add_action( 'wp_loaded', array( $this, 'alter_maintenance_mode_dom' ), 11 );
    }

    /**
     * Get the instance of the Frontend class
     * 
     * @return Frontend, class instance
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Fetch the option in the db to see if maintenance mode has been turned on
     * If maintenance mode is turned on, check for non admin permissions to enforce maintenance mode
     * 
     * @return void
     */
    public function activate_maintenance_mode(){
        global $pagenow;
        $on_login_page = site_url() . '/' . $pagenow;
        
        // Grant an exception for the login page to ensure if WP session expires, 
        // Admin can log back in, otherwise, they will also get the maintenance mode template
        if ( wp_login_url() === $on_login_page ) {
            return false;
        }
        
        $maintenance_mode = get_option( 'sccwd_activate_maintenance_mode', false );
    
        // If maintenance mode is turned on, and the user is not an admin, enforce maintenance mode
        if ( $maintenance_mode === 'true' ) {
            if ( !current_user_can( 'activate_plugins' ) ) {
                return true;
            }
        }
    
        return false;
    }

    /**
     * Set the maintenance mode status
     * 
     * @return void
     */
    public function set_maintenance_mode() {
        $this->maintenance_mode = $this->activate_maintenance_mode();
    }

    /**
     * Start the output buffer if maintenance mode is activated
     * Starting at the 'init' hook to capture all possible output from the site
     * 
     * @return void
     */
    public function start_output_buffer(){

        if ( $this->maintenance_mode ) {
            ob_start();
        }
    }

    /**
     * Fetch the maintenance mode styles
     * 
     * @return string, the maintenance mode styles markup
     */
    public function fetch_maintenance_styles() {
        ob_start();
        include SCCWD_MAINTENANCE_MODE_PATH . 'assets/styles/frontend/maintenance-styles.php';
        return ob_get_clean();
    }

    /**
     * Build the styling for the maintenance mode page
     * 
     * @return string, the maintenance mode styling markup with bootstrap styles
     */
    public function build_styling() {
        // Load the unminified version of bootstrap for development, and the minified version for production
        $bootstrap = WP_DEBUG === true ? 'bootstrap.css' : 'bootstrap.min.css';
        
        $styling = '<link rel="stylesheet" href="' . SCCWD_MAINTENANCE_MODE_URL . 'assets/styles/' . $bootstrap . '">';
        $styling .= $this->fetch_maintenance_styles();
    
        return $styling;
    }

    /**
     * Fetch the maintenance mode markup
     * 
     * @return string, the maintenance mode markup
     */
    public function fetch_maintenance_markup() {
        ob_start();
        include SCCWD_MAINTENANCE_MODE_PATH . 'templates/frontend/maintenance-markup.php';
        return ob_get_clean();
    }

    /**
     * Activate the maintenance mode for all users who are not site administrators
     * 
     * @return void
     */
    public function wp_maintenance_mode() {
        if ( $this->maintenance_mode ) {
            $page_title = get_bloginfo( 'name' ) . "- under maintenance";
            
            // A 'response' of 200 won't generate errors and is intentional
            // Setting 'exit' to false will allow the next hook 'sccwd_alter_maintenance_mode_dom' to alter the dom
            $args = array(
                'response' => 200,
                'exit' => false
            );
    
            $maintenance_markup = $this->fetch_maintenance_markup();
            
            wp_die( $maintenance_markup, $page_title, $args );
        }
    }

    /**
     * Alter the dom by altering the contents of the output buffer
     * 
     * @return void
     */
    public function alter_maintenance_mode_dom() {
        if ( $this->maintenance_mode ) {
            $page = ob_get_contents();

            $altered_dom = new \DOMDocument();
            $altered_dom->loadHTML( $page );
    
            // Remove the style tag that is loaded onto the page
            $style_tag = $altered_dom->getElementsByTagName( 'style' )->item( 0 );
            $style_tag->parentNode->removeChild( $style_tag );
    
            $output = $altered_dom->saveHTML();
            $output = str_replace( '</head>', $this->build_styling() . '</head>', $output );
    
            ob_get_clean();
    
            echo $output;
    
            // Prevent WP from loading any further
            exit();
        }
    }
}