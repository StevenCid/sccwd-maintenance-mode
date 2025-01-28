<?php
/**
 * This file contains the template for the admin menu settings
 */

// Check and see what the status of maintenance mode currently is.
$maintenance_mode = get_option( 'sccwd_activate_maintenance_mode', null );
$maintenance_mode_status_on = $maintenance_mode === 'true' ? 'checked' : '';
$maintenance_mode_status_off = $maintenance_mode === 'false' ? 'checked' : '';
$maintenance_mode_display_saved = $maintenance_mode === 'true' ? 'ON' : 'OFF';

$maintenance_mode_heading = get_option( 'sccwd_maintenance_mode_heading', 'Maintenance Mode' );
$maintenance_mode_message = get_option( 'sccwd_maintenance_mode_message', 'Our site is currently undergoing maintenance. Thank you for your patience' );

$maintenace_mode_bg_construction = get_option( 'sccwd_maintenance_mode_background_image', 'under-construction' ) === 'under-construction' ? 'checked' : '';
$maintenace_mode_bg_coding = get_option( 'sccwd_maintenance_mode_background_image', 'under-construction' ) === 'coding' ? 'checked' : '';
$maintenace_mode_bg_clock = get_option( 'sccwd_maintenance_mode_background_image', 'under-construction' ) === 'clock' ? 'checked' : '';

$maintenance_mode_url = get_admin_url() . 'admin.php?page=sccwd_maintenance_mode';
$maintenance_mode_save_url = get_admin_url() . 'admin.php?page=sccwd_maintenance_mode&save=success';

?>
<div class="container-fluid">
    <?php
        if ( isset( $_GET['save'] ) && $_GET['save'] === 'success' ) : 
            if ( wp_get_referer() === $maintenance_mode_url ||  wp_get_referer() === $maintenance_mode_save_url ):
    ?>
                <div class="alert alert-success" role="alert">
                    Settings Saved! Maintenance Mode is <?php echo $maintenance_mode_display_saved; ?>.
                </div>
    <?php
            endif;
        endif;    
    ?>
    <h1>Maintenance Mode Settings</h1>
    <form action="admin-post.php" method="post" id="sccwd_maintenance_mode_settings">
            <input type="hidden" name="action" value="sccwd_save_admin_settings">
            <?php wp_nonce_field( 'sccwd_verify_admin_settings_save' ); ?>
            <div class="form-group">
                <h2>Activate Maintenance Mode</h2>
                <div class="switch-field">
                    <input class="maintenance-mode-form-element" type="radio" id="activate-maintenace-mode" name="maintenace-mode-status" value="true" <?php echo $maintenance_mode_status_on; ?>/>
                    <label for="activate-maintenace-mode">Yes</label>
                    <input class="maintenance-mode-form-element" type="radio" id="deactivate-maintenace-mode" name="maintenace-mode-status" value="false" <?php echo $maintenance_mode_status_off; ?>/>
                    <label for="deactivate-maintenace-mode">No</label>
                </div>
            </div>
            <div class="form-group">
                <h2>Maintenance Mode Heading</h2>
                <p>For security, HTML tags and special characters will be stripped.</p>
                <input class="maintenance-mode-form-element" type="text" name="maintenance-mode-heading" id="maintenance-mode-heading" value="<?php echo $maintenance_mode_heading; ?>">
            </div>
            <div class="form-group">
                <h2>Maintenance Mode Message</h2>
                <p>For security, HTML tags and special characters will be stripped.</p>
                <textarea class="maintenance-mode-form-element" name="maintenance-mode-message" id="maintenance-mode-message" rows="4"><?php echo $maintenance_mode_message; ?></textarea>
            </div>
            <div class="form-group">
                <h2>Background Image</h2>
                <div class="background-images-container">
                    <div class="background-images">
                        <input class="maintenance-mode-form-element" type="radio" name="maintenance-mode-background-image" id="under-contruction" value="under-construction" <?php echo $maintenace_mode_bg_construction; ?>>
                        <label for="under-contruction"><img src="<?php echo SCCWD_MAINTENANCE_MODE_URL . 'assets/images/under-construction.png'; ?>" alt="Construction Workers Building"></label>
                    </div>
                    <div class="background-images">
                        <input class="maintenance-mode-form-element" type="radio" name="maintenance-mode-background-image" id="coding" value="coding" <?php echo $maintenace_mode_bg_coding; ?>>
                        <label for="coding"><img src="<?php echo SCCWD_MAINTENANCE_MODE_URL . 'assets/images/coding.png'; ?>" alt="Person Coding"></label>
                    </div>
                    <div class="background-images">
                        <input class="maintenance-mode-form-element" type="radio" name="maintenance-mode-background-image" id="clock" value="clock" <?php echo $maintenace_mode_bg_clock; ?>>
                        <label for="clock"><img src="<?php echo SCCWD_MAINTENANCE_MODE_URL . 'assets/images/clock.png'; ?>" alt="Clock Ticking"></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="alert alert-danger" role="alert">
                    Must change a setting before saving settings.
                </div>
                <button type="submit" class="btn btn-outline-primary">Save Settings</button>
            </div>
    </form>
</div>