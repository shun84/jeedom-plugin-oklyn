<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function oklyn_install() {

}

function oklyn_update() {
    foreach (oklyn::byType('oklyn') as $oklyn){
        if($oklyn->getConfiguration('auxiliairesecond') == ''){
            $oklyn->setConfiguration('auxiliairesecond', 'aucun');
        }

        if (is_object($oklyn->getCmd(null, 'datewater'))){
            $oklyn->getCmd(null, 'datewater')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'pompestatus'))){
            $oklyn->getCmd(null, 'pompestatus')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'saltstatus'))){
            $oklyn->getCmd(null, 'saltstatus')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'saltdate'))){
            $oklyn->getCmd(null, 'saltdate')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'orpstatus'))){
            $oklyn->getCmd(null, 'orpstatus')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'orpdate'))){
            $oklyn->getCmd(null, 'orpdate')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'phstatus'))){
            $oklyn->getCmd(null, 'phstatus')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'phdate'))){
            $oklyn->getCmd(null, 'phdate')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'auxstatus'))){
            $oklyn->getCmd(null, 'auxstatus')->remove();
        }

        if (is_object($oklyn->getCmd(null, 'auxsecondstatus'))){
            $oklyn->getCmd(null, 'auxsecondstatus')->remove();
        }

        $oklyn->save();
    }
}

function oklyn_remove() {
    
}

