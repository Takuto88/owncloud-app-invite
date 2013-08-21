<?php
/**
 * ownCloud - Invitations App
 *
 * @author Lennart Rosam
 * @copyright 2013 MSP Medien Systempartner GmbH & Co. KG <lennart.rosam@medien-systempartner.de>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Invite\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\JSONResponse;
use \OCA\AppFramework\Http;

class PageController extends Controller {

 /**
  * @param Request $request an instance of the request
  * @param API $api an api wrapper instance
  */
  public function __construct($api, $request) {
    parent::__construct($api, $request);
  }

  /**
   * IsAdminExemption is OK, because we want subadmins to access things
   * CSRFExemption is OK for index
   *
   * @IsAdminExemption
   * @CSRFExemption
   */
  public function index() {
    $uid = $this->api->getUserId();

    $tmpGroups = array();
    $groups = array();
    $isAdmin = $this->api->isAdminUser($uid);

    // Query groups based on user's permissions
    if($isAdmin) {
      $tmpGroups = \OC_Group::getGroups();
    } else {
      $tmpGroups = \OC_SubAdmin::getSubAdminsGroups($uid);
    }

    // Filter out just the gid (=group name)
    foreach ($tmpGroups as $group) {
      $groups[] = $group;
    }

    $model = array('groups' => $groups, 'isAdmin' => $isAdmin);

    return $this->render('index', $model);
  }
}