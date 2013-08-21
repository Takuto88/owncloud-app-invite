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
   * Tests a new user if it is valid
   *
   * @Ajax
   * @IsAdminExemption
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
   * Creates a new user
   *
   * @Ajax
   * @IsAdminExemption
   */
  public function create(){
    $user = $this->params('user');
    $uid = $this->api->getUserId();

    // Don't trust the user's input blindly... Validate things first
    $usernameValidation = $this->validateUsername($user['username']);
    $emailValidation = $this->validateEmail($user['email']);

    if(!$usernameValidation['validUsername'] || !$emailValidation['validEmail']) {
      return new JSONResponse(array('msg' => 'Invalid user or email'), 400);
    }

    if(!$this->validateGroups($user['groups'], $this->api->isAdminUser($uid))) {
      return new JSONResponse(array('msg' => 'Invalid groups'), 400);
    }

    // Set a secure inital password (will not be send to the user)
    $user['password'] = $this->mkToken();

    // Create the user and add him to groups
    if (!\OC_User::createUser($user['username'], $user['password'])) {
      return new JSONResponse(array( 'msg' => 'User creation failed for '.$username ), 500);
    }

    foreach ($user['groups'] as $group) {
      \OC_Group::addToGroup( $user['username'], $group );
    }

    // Set email and password token
    $token = $this->mkToken();
    \OC_Preferences::setValue($user['username'], 'settings', 'email', $user['email']);
    \OC_Preferences::setValue($user['username'], 'invite', 'token', hash('sha256', $token)); // Hash again for timing attack protection

    // Send email
    $link = \OC_Helper::linkToRoute('invite_join', array('user' => $user['username'], 'token' => $token));
    $link = \OC_Helper::makeURLAbsolute($link);
    $tmpl = new \OCP\Template('invite', 'email');
    $tmpl->assign('link', $link);
    $tmpl->assign('inviter', $uid);
    $tmpl->assign('invitee', $user['username']);
    $msg = $tmpl->fetchPage();
    $l = $this->api->getTrans();
    $from = \OCP\Util::getDefaultEmailAddress('lostpassword-noreply');

    try {
      \OC_Mail::send($user['email'], $user['username'], $l->t('ownCloud Invitation'), $msg, $from, 'ownCloud');
    } catch (Exception $e) {
      return new JSONResponse(array('msg' => 'Error sending email!', 'error' => $e), 500);
    }

    return new JSONResponse(array('msg' => 'OK'), 200);
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
        $result['msg'] = $this->api->getTrans()->t('Username is empty')->text;
      }

      if(preg_match( '/[^a-zA-Z0-9 _\.@\-]/', $username )) {
        $result['validUsername'] = false;
        $result['msg'] = $this->api->getTrans()->t('Username contains illegal characters')->text;
      }

      if(strlen($username) < 3) {
        $result['validUsername'] = false;
        $result['msg'] = $this->api->getTrans()->t('Username must be at least 3 characters long')->text;
      }

      if(\OC_User::userExistsForCreation($username)) {
        $result['validUsername'] = false;
        $result['msg'] = $this->api->getTrans()->t('User exists already')->text;
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

      if(empty( $email ) || !filter_var( $email, FILTER_VALIDATE_EMAIL)) {
        $result['validEmail'] = false;
        $result['msg'] = 'Invalid mail address';
      }

      return $result;
  }

  /**
   * Checks if the given groups are valid and do exist
   *
   * @param groups The group array
   * @param isAdmin Whether or not the user has admin privileges (true / false)
   * @return True if everything is ok, otherwise false
   */
  private function validateGroups($groups=array(), $isAdmin) {
    // Admins may invite users without setting a group.
    if($isAdmin && (!isset($groups) || count($groups) === 0 )) {
      return true;
    }

    if(!is_array($groups) || count($groups) < 1) {
      return false;
    }

    foreach ($groups as $group) {
        // For now, we don't create new groups!
      if(!\OC_Group::groupExists($group)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Creates a random token
   *
   * @return A random token as done the password reset feature of ownCloud
   */
  private function mkToken() {
    return hash('sha256', \OC_Util::generate_random_bytes(30).\OC_Config::getValue('passwordsalt', ''));
  }

}