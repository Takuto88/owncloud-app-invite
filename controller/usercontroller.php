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

class UserController extends Controller {

 /**
  * @param Request $request an instance of the request
  * @param API $api an api wrapper instance
  */
  public function __construct($api, $request) {
    parent::__construct($api, $request);
  }

  /**
   * Checks if the user is exists within ownCloud
   *
   * @CSRFExemption
   * @Ajax
   */
  public function show() {
    $uid = $this->params('username');

    $exists = \OCP\User::userExists($uid);
    $result = array();
    $result['user']['username'] = $uid;
    $result['user']['email'] = \OC_Preferences::getValue($uid, 'settings', 'email', 'none');
    $result['user']['exists'] = $exists;
    $result['user']['nameIsValid'] = !preg_match( '/[^a-zA-Z0-9 _\.@\-]/', $uid );

    return new JSONResponse($result, 200);
  }

  /**
   * Tests a new user if it is valid
   *
   * @CSRFExemption
   * @Ajax
   */
  public function test() {
    $user = $this->params('user');
    $result['user']['validUserName'] = !preg_match( '/[^a-zA-Z0-9 _\.@\-]/', $user['username'] );

    return new JSONResponse($result, 200);
  }

  /**
   * Validates the given username
   *
   * @param Username The username to validate
   * @return A validation result like $result['validUserName']['true'] and $result['msg']['OK']
   */
  private function validateUsername($username='') {
      $result['validUserName'][true];
      $result['msg']['OK'];

      return $result;
  }

}