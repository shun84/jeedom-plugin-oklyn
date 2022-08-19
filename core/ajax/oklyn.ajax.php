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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
    
    ajax::init();

    if (init('action') == 'getOklyn') {
        if (init('object_id') == '') {
            $_GET['object_id'] = $_SESSION['user']->getOptions('defaultDashboardObject');
        }
        $object = jeeObject::byId(init('object_id'));
        if (!is_object($object)) {
            $object = jeeObject::rootObject();
        }
        if (!is_object($object)) {
            throw new Exception(__('Aucun objet racine trouvé', __FILE__));
        }
        if (count($object->getEqLogic(true, false, 'oklyn')) == 0) {
            $allObject = jeeObject::buildTree();
            foreach ($allObject as $object_sel) {
                if (count($object_sel->getEqLogic(true, false, 'oklyn')) > 0) {
                    $object = $object_sel;
                    break;
                }
            }
        }
        $return = ['object' => utils::o2a($object)];

        foreach ($object->getEqLogic(true, false, 'oklyn') as $eqLogic) {
            $return['eqLogics'][] = [
                'eqLogic' => utils::o2a($eqLogic)
            ];
        }

        ajax::success($return);
    }

    throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}

