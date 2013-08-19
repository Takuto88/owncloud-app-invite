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
   * @Ajax
   */
  public function test() {
    $user = $this->params('user');
    $usernameValidation = $this->validateUsername($user['username']);
    $emailValidation = $this->validateEmail($user['email']);

    $response = array(
      'usernameValidation' => $usernameValidation,
      'emailValidation' => $emailValidation
      );

    return new JSONResponse($response, 200);
  }

  /**
   * Validates the given username
   *
   * @param Username The username to validate
   * @return A validation result like $result['validUserName'] => true and $result['msg'] => 'OK'
   */
  private function validateUsername($username='') {
      $result = array(
          'validUsername' => true,
          'msg' => 'OK'
        );

      if(!isset($username) || empty($username)) {
        $result['validUsername'] = false;
        $result['msg'] = 'Username is empty';
      }

      if(preg_match( '/[^a-zA-Z0-9 _\.@\-]/', $username )) {
        $result['validUsername'] = false;
        $result['msg'] = 'Username contains illegal characters';
      }

      return $result;
  }

  /**
   * Validates the given email address
   *
   * @param email The email to validate
   * @return A validation result like $result['validEmail'] => true and $result['msg'] => 'OK'
   */
  private function validateEmail($email='') {
      $result = array(
          'validEmail' => true,
          'msg' => 'OK'
        );

      if(!empty( $email ) || !filter_var( $email, FILTER_VALIDATE_EMAIL)) {
        $result['validEmail'] = false;
        $result['msg'] = 'Invalid mail address';
      }

      return $result;
  }

}