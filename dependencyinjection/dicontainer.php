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

namespace OCA\Invite\DependencyInjection;

use \OCA\AppFramework\DependencyInjection\DIContainer as BaseContainer;
use \OCA\Invite\Controller\PageController;
use \OCA\Invite\Controller\UserController;
use \OCA\Invite\Service\InviteService;
use \OCA\Invite\Service\SubAdminGroupBackend;

class DIContainer extends BaseContainer {

	public function __construct(){
		parent::__construct('invite');

		$this['GroupBackend'] = $this->share(function($c){
			return new SubAdminGroupBackend();
		});

		$this['InviteService'] = $this->share(function($c){
		  return new InviteService($c['API']);
		});

		$this['PageController'] = $this->share(function($c){
			return new PageController(
				$c['API'],
				$c['Request'],
				$c['InviteService'],
				$c['GroupBackend']
			);
		});

		$this['UserController'] = $this->share(function($c){
			return new UserController(
				$c['API'], $c['Request'], $c['InviteService']
			);
		});
	}

}