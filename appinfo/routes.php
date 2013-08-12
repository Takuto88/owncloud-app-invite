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

use \OCA\AppFramework\App;
use \OCA\Invite\DependencyInjection\DIContainer;

$this->create('invite_index', '/')->get()->action(
    function($params){
        // call the index method on the class PageController
        App::main('PageController', 'index', $params, new DIContainer());
    }
);

$this->create('invite_entries', '/entries')->get()->action(
    function($params){
        // call the index method on the class PageController
        App::main('PageController', 'listEntries', $params, new DIContainer());
    }
);