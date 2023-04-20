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
        $object = jeeObject::byId(init('object_id'));

        if (!is_object($object)) {
            throw new Exception(__('Oklyn non trouvé', __FILE__));
        }

        $return = ['object' => utils::o2a($object)];

        $date = [
            'start' => init('dateStart'),
            'end' => init('dateEnd'),
        ];

        if ($date['start'] == '') {
            $date['start'] = date('Y-m-d', strtotime('-1 months ' . date('Y-m-d')));
        }
        if ($date['end'] == '') {
            $date['end'] = date('Y-m-d', strtotime('+1 days ' . date('Y-m-d')));
        }
        $return['date'] = $date;

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

