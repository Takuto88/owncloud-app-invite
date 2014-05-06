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

namespace OCA\Invite\Service;

use \OCP\DB;

/**
 * Extended ownCloud Group backend for
 * accessing subadmin groups of a user
 */
class SubAdminGroupBackend extends \OC_Group_DataBase {

	public function getSubAdminGroups($uid = null) {
		if(!isset($uid)) {
			return null;
		}

		$db = new DB();
		$stmt = $db->prepare('SELECT `gid` FROM `*PREFIX*group_admin` WHERE `uid` = ?');
		$result = $stmt->execute(array($uid));
		$gids = array();
		while($row = $result->fetchRow()) {
			$gids[] = $row['gid'];
		}
		return $gids;
	}

}