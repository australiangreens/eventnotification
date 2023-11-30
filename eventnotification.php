<?php

require_once 'eventnotification.civix.php';
use CRM_Eventnotification_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/ 
 */
function eventnotification_civicrm_config(&$config) {
  _eventnotification_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function eventnotification_civicrm_install() {
  _eventnotification_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function eventnotification_civicrm_enable() {
  _eventnotification_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_copy().
 */
function eventnotification_civicrm_copy($objectName, &$object) {
  if (CRM_Eventnotification_Utils::isNotificationEnable()) {
    CRM_Eventnotification_Utils::sendEmailNotification(['title' => $object->title, 'id' => $object->id], CRM_Core_Config::domainID());
  }
  else {
    if (!empty($object->financial_type_id)) {
      $financialTypes = CRM_Eventnotification_Utils::getNotifiedFinancialTypes();
      if (array_key_exists($object->financial_type_id, $financialTypes)) {
        CRM_Eventnotification_Utils::sendEmailNotification(['title' => $object->title, 'id' => $object->id], $financialTypes[$object->financial_type_id]);
      }
    }
  }
}

function eventnotification_civicrm_postCommit($op, $objectName, $objectId, &$objectRef) {
  if ($objectName === 'Event' && $op === 'create') {
    if (CRM_Eventnotification_Utils::isNotificationEnable()) {
      CRM_Eventnotification_Utils::sendEmailNotification(['title' => $objectRef->title, 'id' => $objectRef->id], CRM_Core_Config::domainID());
    }
    else {
      if (!empty($objectRef->financial_type_id)) {
        $financialTypes = CRM_Eventnotification_Utils::getNotifiedFinancialTypes();
        if (array_key_exists($objectRef->financial_type_id, $financialTypes)) {
          CRM_Eventnotification_Utils::sendEmailNotification(['title' => $objectRef->title, 'id' => $objectRef->id], $financialTypes[$objectRef->financial_type_id]);
        }
      }
    }
  }
}

/**
 * Block changes to the Is active field if we are creating a new event and are not stats officers or similar and are in the NSW Domain
 */
function eventnotification_civicrm_buildForm($formName, &$form) {
  if ($formName === 'CRM_Event_Form_ManageEvent_EventInfo' && CRM_Core_Config::domainID() === 8) {
    $defaults = ['is_active' => 0];
    if (empty($form->_id)) {
      if (!CRM_Core_Permission::check('administer CiviCRM')) {
        $form->setDefaults($defaults);
        CRM_Core_Resources::singleton()->addStyle('.crm-event-manage-eventinfo-form-block-is_active { pointer-events: none; }');
      }
    }
    else {
      $eventDetails = civicrm_api3('Event', 'getsingle', ['id' => $form->_id]);
      if (empty($eventDetails['is_active']) && !CRM_Core_Permission::check('administer CiviCRM')) {
        $form->setDefaults($defaults);
        CRM_Core_Resources::singleton()->addStyle('.crm-event-manage-eventinfo-form-block-is_active { pointer-events: none; }');
      }
    }
  }
}
// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *

 // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function eventnotification_civicrm_navigationMenu(&$menu) {
  _eventnotification_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _eventnotification_civix_navigationMenu($menu);
} // */
