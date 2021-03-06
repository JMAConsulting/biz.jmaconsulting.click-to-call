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

class CRM_Clicktocall_Page_Dial extends CRM_Core_Page {

  function run() {
    if ($_REQUEST['Digits'] == '1') {
      $sayMessage = "Thank you, please stay on the line while we connect you.";
      $response = new Twilio\Twiml();
      $response->say($sayMessage);
      $toNumber = CRM_Utils_Request::retrieve('toNumber', 'String');
      $toNumber = CRM_Clicktocall_BAO_Twilio_Call::formatPhone($toNumber);
      $dialParams = array(
        'timeout' => 20,
      );
      $response->dial($toNumber, $dialParams);
      print $response->__toString();
    }
    else if ($_REQUEST['Digits'] == '2') {
      $sayMessage = "Thank you. Bye.";
      $response = new Twilio\Twiml();
      $response->say($sayMessage);
      print $response->__toString();
    }
    else {
      $sayMessage = "We did not recognize this input. Bye.";
      $response = new Twilio\Twiml();
      $response->say($sayMessage);
      print $response->__toString();
    }
    exit();
  }

}

