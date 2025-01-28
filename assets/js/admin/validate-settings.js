/**
 * Validate the SCCWebDev Maintenance Mode plugin settings before saving
 */

let submitButton = document.querySelector('button[type="submit"]');
let formElements = document.querySelectorAll('form .maintenance-mode-form-element');

/**
 * Read the current selected settings and compare to the ones saved in the db
 * If the current selected settings exactly match the ones saved in the db, output error message and don't submit form
 * Otherwise, submit the form
 * 
 * @returns {boolean}
 */
function validateSettings(){
  // Read the current values on the form
  let formMaintenanceStatus = document.querySelector('input[name="maintenace-mode-status"]:checked').value;
  let formMaintenanceHeading = document.querySelector('input[name="maintenance-mode-heading"]').value;
  let formMaintenanceMessage = document.querySelector('textarea[name="maintenance-mode-message"]').value;
  let formMaintenanceBackground = document.querySelector('input[name="maintenance-mode-background-image"]:checked').value;

  // Destructure the object that contained the saved settings in the db for comparison to the current values on the form
  let {maintenanceModeStatus, maintenanceModeHeading, maintenanceModeMessage, maintenanceModeBackground} = MAINTENANCEMODE;

  if (maintenanceModeStatus !== formMaintenanceStatus){
    return true;
  }
  else if (maintenanceModeHeading !== formMaintenanceHeading){
    return true;
  }
  else if (maintenanceModeMessage !== formMaintenanceMessage){
    return true;
  } 
  else if (maintenanceModeBackground !== formMaintenanceBackground){
    return true;
  }  

  return false;
}

/**
 * If the user tries to save but, hasn't changed a setting, show the alert indicating why they can't save yet
 * 
 * @param {boolean} submitForm - whether to allow the form to be submitted 
 * @returns {void}
 */
function changeSettingsMessage( submitForm ){
  let alert = document.querySelector('div.alert-danger');
  let alertStyles = window.getComputedStyle(alert);

  if (!submitForm){
    if ( alertStyles.display === 'none' ){
      alert.style.display = 'block';

      formElements.forEach(element => {
        // Check is the form element does not have a change-listener attribute before adding the event listener
        if (element.hasAttribute('change-listener') === false ){
          // Bind event listener to the form elements to hide the alert if the user changes a setting
          element.addEventListener('change', e => {
            if (alert.style.display === 'block'){
              alert.style.display = 'none';
            }
          });
          element.setAttribute('change-listener', 'true');
        }
      });
    }
  }
}

// Bind event listener to the submit button to validate the settings before being submitted
submitButton.addEventListener('click', e => {
  e.preventDefault();
  let submitForm = validateSettings();
  changeSettingsMessage( submitForm );

  if (submitForm){
    document.forms['sccwd_maintenance_mode_settings'].submit();
  }
}, true);
