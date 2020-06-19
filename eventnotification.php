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
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function eventnotification_civicrm_xmlMenu(&$files) {
  _eventnotification_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function eventnotification_civicrm_postInstall() {
  _eventnotification_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function eventnotification_civicrm_uninstall() {
  _eventnotification_civix_civicrm_uninstall();
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
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function eventnotification_civicrm_disable() {
  _eventnotification_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function eventnotification_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _eventnotification_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function eventnotification_civicrm_managed(&$entities) {
  _eventnotification_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function eventnotification_civicrm_caseTypes(&$caseTypes) {
  _eventnotification_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function eventnotification_civicrm_angularModules(&$angularModules) {
  _eventnotification_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function eventnotification_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _eventnotification_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function eventnotification_civicrm_entityTypes(&$entityTypes) {
  _eventnotification_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function eventnotification_civicrm_themes(&$themes) {
  _eventnotification_civix_civicrm_themes($themes);
}

/**
 * Implements hook_civicrm_copy().
 */
function eventnotification_civicrm_copy($objectName, &$object) {
  if (CRM_Eventnotification_Utils::isNoficationEnable()) {
    CRM_Eventnotification_Utils::sendEmailNotification(CRM_Core_Config::domainID(), ['title' => $object->title, 'id' => $object->id]);
  }
  else {
    if (!empty($object->financial_type_id)) {
      $financialTypes = CRM_Eventnotification_Utils::getNotifiedFinancialTypes();
      if (array_key_exists($object->financial_type_id, $financialTypes)) {
        CRM_Eventnotification_Utils::sendEmailNotification($financialTypes[$object->financial_type_id], ['title' => $object->title, 'id' => $object->id]);
      }
    }
  }
}

function eventnotification_civicrm_postCommit($op, $objectName, $objectId, &$objectRef) {
  if ($objectName === 'Event' && $op === 'create') {
    if (CRM_Eventnotification_Utils::isNoficationEnable()) {
      CRM_Eventnotification_Utils::sendEmailNotification(CRM_Core_Config::domainID(), ['title' => $objectRef->title, 'id' => $objectRef->id]);
    }
    else {
      if (!empty($objectRef->financial_type_id)) {
        $financialTypes = CRM_Eventnotification_Utils::getNotifiedFinancialTypes();
        if (array_key_exists($objectRef->financial_type_id, $financialTypes)) {
          CRM_Eventnotification_Utils::sendEmailNotification($financialTypes[$objectRef->financial_type_id], ['title' => $objectRef->title, 'id' => $objectRef->id]);
        }
      }
    }
  }
}

function eventnotification_civicrm_postProcess($formName, $form) {
  if ($formName === 'CRM_Event_Form_ManageEvent_Fee') {
    $params = $form->exportValues();
    if ($params['is_monetary') {
      $financialTypes = CRM_Eventnotification_Utils::getNotifiedFinancialTypes();
      if (array_key_exists($params['financial_type_id'], $financialTypes)) {
        $event = civicrm_api3('Event', 'getsingle', ['id' => $form->_id]);
        CRM_Eventnotification_Utils::sendEmailNotification($financialTypes[$params['financial_type_id']], $event);
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
function eventnotification_civicrm_preProcess($formName, &$form) {

} // */

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
