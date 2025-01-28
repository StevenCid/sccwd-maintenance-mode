<?php
/**
 * This file contains the markup for the Maintenance message that will be output to the screen when the site is under maintenance
 */
$maintenance_mode_heading = get_option( 'sccwd_maintenance_mode_heading', '' );
$maintenance_mode_heading_output = empty( $maintenance_mode_heading ) ? 'Maintenance Mode' : $maintenance_mode_heading;

$maintenance_mode_message = get_option( 'sccwd_maintenance_mode_message', '' );
$maintenance_mode_message_output = empty( $maintenance_mode_message ) ? 'Our site is currently undergoing maintenance. Thank you for your patience.' : $maintenance_mode_message;

$maintenance_mode_background_image_option = get_option( 'sccwd_maintenance_mode_background_image', 'under-construction' );
$maintenance_mode_background_image = SCCWD_MAINTENANCE_MODE_URL . 'assets/images/' . $maintenance_mode_background_image_option . '.png';
?>
<div class="container">
    <div class="d-flex flex-column" id="maintenance-mode" style="background-image: url('<?php echo $maintenance_mode_background_image; ?>');">
        <h1><?php echo $maintenance_mode_heading_output; ?></h1>
        <p><?php echo $maintenance_mode_message_output; ?></p>  
    </div>
</div>  