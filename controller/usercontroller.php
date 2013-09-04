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

	private $inviteService;

 	/**
	 * @param Request $request an instance of the request
	 * @param API $api an api wrapper instance
	 */
	public function __construct($api, $request, $inviteService) {
		parent::__construct($api, $request);
		$this->inviteService = $inviteService;
	}

	/**
	 * Tests a new user if it is valid
	 *
	 * @Ajax
	 * @IsAdminExemption
	 */
	public function test() {
		$user = $this->params('user');
		$usernameValidation = $this->inviteService
			->validateUsername($user['username']);
		$emailValidation = $this->inviteService->validateEmail($user['email']);

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
		return $this->inviteService->invite($user);
	}
}