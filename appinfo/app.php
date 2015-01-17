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
namespace OCA\Invite;


// dont break owncloud when the appframework is not enabled
if(\OCP\App::isEnabled('appframework')){

	// Check if the user has either admin or group admin rights.
	// Everyone else should not be allowed to continue
	$api = new \OCA\AppFramework\Core\API('invite');
	$userId = $api->getUserId();

	if($api->isLoggedIn() && ($api->isAdminUser($userId)
		|| $api->isSubAdminUser($userId))) {
		$api->addNavigationEntry(array(

			// the string under which your app will be referenced in owncloud
			'id' => $api->getAppName(),

			// sorting weight for the navigation. The higher the number,
			// the higher will it be listed in the navigation
			'order' => 10,

			// the route that will be shown on startup
			'href' => $api->linkToRoute('invite_index'),

			// the icon that will be shown in the navigation
			'icon' => $api->imagePath('navicon.png'),

			// the title of your application. This will be used in the
			// navigation or on the settings page of your app
			'name' => $api->getTrans()->t('Invitations')

		));
	}
} else {
	$msg = 	'Can not enable the Invitations app because the App Framework ' .
			'App is disabled';
	\OCP\Util::writeLog('invite', $msg, \OCP\Util::ERROR);
}
