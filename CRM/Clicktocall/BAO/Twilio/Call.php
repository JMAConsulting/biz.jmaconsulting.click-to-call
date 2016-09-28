<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2016                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2016
 * $Id$
 *
 */
class CRM_Clicktocall_BAO_Twilio_Call implements CRM_Clicktocall_ClickToCallAPI {

  public static function create($cid, $fromNumber, $twilio, $host) {

    $client = new Twilio\Rest\Client(
      $twilio['twilio_account_sid'],
      $twilio['twilio_auth_token']
    );
    $isRecord = FALSE;
    if (strtolower($twilio['record_call']) == 'yes') {
      $isRecord = TRUE;
    }

    try {
      $call = $client->account->calls->create(
        $fromNumber,
        $twilio['twilio_number'],
        array(
          "url" => $host,
          "method" => "GET",
          "record" => $isRecord,
          "statusCallbackMethod" => "POST",
          "statusCallback" => CRM_Utils_System::url('civicrm/call/callstatus', NULL, TRUE, NULL, TRUE, TRUE, FALSE),
          "statusCallbackEvent" => array(
            "completed",
          ),
        )
      );
    }
    catch (Exception $e) {
      // Failed calls will throw
      return $e;
    }
    
    // return success
    return TRUE;
  }

  public static function createActivity($result) {
    $result = json_decode($result);
  }

  public static function formatPhone($number) {
    // Weird case when passing numbers between XMLs cause + to disappear.
    if (strpos($number, '+') === FALSE) {
      return '+' . trim($number);
    }
    return trim($number);
  }
}
