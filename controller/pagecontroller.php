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
   * ATTENTION!!!
   * The following comments turn off security checks
   * Please look up their meaning in the documentation!
   *
   * @CSRFExemption
   * @IsAdminExemption
   * @IsSubAdminExemption
   */
  public function index() {
    return $this->render('index');
  }


/**
 * ATTENTION!!!
 * The following comments turn off security checks
 * Please look up their meaning in the documentation!
 *
 * @CSRFExemption
 * @IsAdminExemption
 * @IsSubAdminExemption
 */
  public function listEntries() {
    $entries = ['one', 'two', 'three'];
    return new JSONResponse($entries, 200);
  }
}