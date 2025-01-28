<?php
/**
 * Plugin Name: SCCWebDev Maintenance Mode
 * Plugin URI: https://github.com/StevenCid
 * Description: Lightweight plugin to put the frontend into maintenance mode for non-admin accounts.
 * Version: 1.0
 * Author: Steven Cid
 * Author URI: https://github.com/StevenCid
 * Text Domain: sccwd-maintenance-mode
 * License: MIT
 * Requires at least: 6.7
 * Requires PHP: 7.4
 * 
 * Copyright 2025 Steven Cid
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

if ( !defined( 'ABSPATH' ) ) {
    return;
}

// SCCWebDev Maintenance Mode Path
if ( !defined( 'SCCWD_MAINTENANCE_MODE_PATH' ) ) {
    define( 'SCCWD_MAINTENANCE_MODE_PATH', plugin_dir_path( __FILE__ ) );
}

// SCCWebDev Maintenance Mode Base File
if ( !defined( 'SCCWD_MAINTENANCE_MODE_FILE' ) ) {
    define( 'SCCWD_MAINTENANCE_MODE_FILE', __FILE__ );
}

// SCCWebDev Maintenance Mode URL
if ( !defined( 'SCCWD_MAINTENANCE_MODE_URL' ) ) {
    define( 'SCCWD_MAINTENANCE_MODE_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * SCCWebDev Maintenance Mode plugin main class
 */
final class Sccwd_Maintenance_Mode {
    /**
     * The instance of the Sccwd_Maintenance_Mode class
     * 
     * @var null
     */
    private static $instance = null;
    
    /**
     * The constructor for the Sccwd_Maintenance_Mode class
     */
    private function __construct() {
        $this->init_includes();
    }

    /**
     * Initialize the includes for the plugin
     * 
     * @return void
     */
    private function init_includes() {
        require_once( SCCWD_MAINTENANCE_MODE_PATH . 'includes/Admin.php' );
        require_once( SCCWD_MAINTENANCE_MODE_PATH . 'includes/Frontend.php' );
        
        \Sccwd_MM\Admin::instance();
        \Sccwd_MM\Frontend::instance();
    }

    /**
     * Get the instance of the Sccwd_Maintenance_Mode class
     * 
     * @return Sccwd_Maintenance_Mode
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

Sccwd_Maintenance_Mode::instance();