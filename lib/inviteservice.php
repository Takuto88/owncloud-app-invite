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
namespace OCA\Invite\Lib;

use \OC_Defaults;
use \OCA\AppFramework\Http\JSONResponse;

/**
 * This class is the businesslayer of the ownCloud Invitations App.
 *
 * Contains everything required to signup a new user. Including
 * validation, token generation and verification, mailings and
 * of course: adding the user to ownCloud.
 */
class InviteService {
  private $defaults;
  private $api;

  public function __construct($api){
    $this->defaults = new OC_Defaults();
    $this->api = $api;
  }

  /**
   * Creates a random token
   *
   * @return A random token as done the password reset feature of ownCloud
   */
  private function mkToken() {
    return hash('sha256', \OC_Util::generate_random_bytes(30).\OC_Config::getValue('passwordsalt', ''));
  }

  /**
   * Checks if the given token is valid for the given user
   *
   * @param uid The user id
   * @param token The token
   * @return True if the token is valid, otherwise false
   */
  public function validateToken($uid, $token) {
     return \OC_Preferences::getValue($uid, 'invite', 'token') === hash('sha256', $token);
  }

  /**
   * Validates the given username
   *
   * @param Username The username to validate
   * @return A validation result like $result['validUserName'] => true and $result['msg'] => 'OK'
   */
  public function validateUsername($username='') {
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
  public function validateEmail($email='') {
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
  public function validateGroups($groups=array(), $isAdmin) {
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
   * Validates the given password.
   *
   * At the moment, ownCloud does _NOT_ enforce secure
   * passwords. Anything but an empty password is valid.
   * Despite ownCloud's default behavior, you bet that
   * we WILL enforce secure passwords. At least to some
   * degree.
   *
   * The following will be considered a valid password:
   * - At least 6 characters in length
   * - Contain at least one upper and one lower case letter
   * - Contain at least one special character or number
   *
   * @param password The password to validate
   * @return True if the password is valid, otherwise false
   */
  public function validatePassword($password) {
    return isset($password) && preg_match("/(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password);
  }

  /**
   * Chreates the user, adds him to his groups and send him an invite mail.
   *
   * This function does double check it's input to make sure that
   * everything is in order before actually creating anything.
   *
   * @param user The user array containing the keys 'username', 'email', and groups.
   *             Groups may be empty if the current user is an administrator
   * @return The appropriate JSONResponse reporting success or error
   */
  public function invite($user=array()) {
    // Don't trust the user's input blindly... Validate things first
    $usernameValidation = $this->validateUsername($user['username']);
    $emailValidation = $this->validateEmail($user['email']);
    $uid = $this->api->getUserId();

    // Response model for invalid data
    $invalidDataResponse = array(
      'validUser' => $usernameValidation['validUsername'],
      'validEmail' => $emailValidation['validEmail'],
      'validGroups' => $this->validateGroups($user['groups'], $this->api->isAdminUser($uid))
      );

    if(!$usernameValidation['validUsername'] || !$emailValidation['validEmail'] || !$invalidDataResponse['validGroups']) {
      return new JSONResponse($invalidDataResponse, 400);
    }

    // Set a secure inital password (will not be send to the user)
    $user['password'] = $this->mkToken();

    // Create the user and add him to groups
    if (!\OC_User::createUser($user['username'], $user['password'])) {
      return new JSONResponse(array( 'msg' => 'User creation failed for '. $username . '. Please contact your system administrator!'), 500);
    }

    if(isset($user['groups']) && is_array($user['groups'])) {
      foreach ($user['groups'] as $group) {
        \OC_Group::addToGroup( $user['username'], $group );
      }
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
    $tmpl->assign('productname', $this->defaults->getName());
    $msg = $tmpl->fetchPage();
    $l = $this->api->getTrans();
    $from = \OC_Preferences::getValue($this->api->getUserId(), 'settings', 'email');

    if(!isset($from)) {
      $from = OCP\Util::getDefaultEmailAddress('invite-noreply');
    }

    try {
      \OC_Mail::send($user['email'], $user['username'], $l->t('You are invited to join %s', array($this->defaults->getName())), $msg, $from, $uid);
    } catch (Exception $e) {
      return new JSONResponse(array('msg' => 'Error sending email! Please contact your system administrator!', 'error' => $e), 500);
    }

    return new JSONResponse(array('msg' => 'OK'), 200);
  }

}