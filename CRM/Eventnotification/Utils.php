<?php

abstract class CRM_Eventnotification_Utils {

  public static function getNotifiedFinancialTypes(): array {
    $financialTypes = [];
    $domainValues = civicrm_api3('Setting', 'get', ['domain_id' => 'all', 'limit' => 0]);
    foreach ($domainValues as $domainID => $settings) {
      if (!empty($settings['eventnotification_financial_types']) && !empty($settings['eventnotification_enable'])) {
        foreach ($settings['eventnotification_financial_types'] as $financialTypeID) {
          $financialTypes[$financialTypeID] = $domainID;
        }
      }
    }
    return $financialTypes;
  }

  public static function sendEmailNotification(array $eventDetails, int $domainId) {
    $subject = 'New CiviCRM Event Created or modified ' . $eventDetails['title'];
    $url = CRM_Utils_System::url('civicrm/event/manage/settings', 'reset=1&action=update&id=' . $eventDetails['id'], TRUE);
    $body = '<p>A CiviCRM event has been either modified to use a financial type you are to be notified about or has been created in your domain please go to <a href="' . $url . '"> Event settings page</a> to ensure settings are correct</p>';
    $email = Civi::settings($domainId)->get('eventnotification_email_address');
    $toName = Civi::settings($domainId)->get('eventnotification_email_to_name');
    list($domainEmailName, $_) = CRM_Core_BAO_Domain::getNameAndEmail();
    $params = [
     'toName' => $toName,
     'toEmail' => $email,
     'subject' => $subject,
     'html' => $body,
     'from' =>  "\"$domainEmailName\" <" . CRM_Core_BAO_Domain::getNoReplyEmailAddress() . '>'
    ];
    CRM_Utils_Mail::send($params);
  }

  public static function isNoficationEnable($domainId = NULL) {
    if (empty($domainId)) {
      $domainId = CRM_Core_Config::domainID();
    }
    return (bool) Civi::settings($domainId)->get('eventnotification_enable');
  }

}
