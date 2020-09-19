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

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class oklyn extends eqLogic {
    /*     * *************************Attributs****************************** */
    private $_apiToken;
    public static $_widgetPossibility = array('custom' => true, 'custom::layout' => false);

    /*     * ***********************Methode static*************************** */
    public function __construct()
    {
        $this->_apiToken = config::byKey('apicle','oklyn');
    }

    /*
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/pump
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     *
     */
    public function getPompe(string $value){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/pump",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $this->_apiToken,
                "Content-Type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        return $response[$value];
    }

    /*
     * Méthode: PUT
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/pump
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * pump peut prendre comme valeur « on », « off », et « auto »
     *
     */

    public function putPompe(string $value){
        $api = new oklyn();
        $data = array("pump" => $value);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/pump",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $api->_apiToken,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /*
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/data/{typeDeMesure}
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * {typeDeMesure} est à remplacer par le nom d’une mesure parmi air, water (température de l’eau), ph, orp (redox).
     *
     */
    public function getSonde(string $sonde, string $value) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/data/".$sonde,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $this->_apiToken,
                "Content-Type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        return $response[$value];
    }

    /*
     * Méthode: GET
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/aux
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     *
     */

    public function getAux(string $value){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/aux",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $this->_apiToken,
                "Content-Type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        return $response[$value];
    }

    /*
     * Méthode: PUT
     * URL: https://api.oklyn.fr/public/v1/device/{deviceId}/aux
     * {deviceId} est à remplacer par le numéro unique d’association ou par le mot clef my.
     * pump peut prendre comme valeur « on », « off »
     *
     */
    public function putAux(string $value){
        $api = new oklyn();
        $data = array("aux" => $value);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oklyn.fr/public/v1/device/my/aux",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "X-API-TOKEN: ". $api->_apiToken,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /*
     * Retour API pour lire les mesures des sondes
     * {
     *  "recorded": "2019-04-15T15:15:06+00:00",
     *  "value": 14.7,
     *  "status": null,
     *  "value_raw": null
     * }
     * recorded correspond à la date d’enregistrement des mesures.
     *
     * value correspond à la valeur exploitable de la mesure. Pour les mesures de RedOx et de pH, value correspond à une moyenne pondérée sur les mesures des 18 dernières heures.
     *
     * value_raw correspond à la valeur brute de la mesure, telle que transmise par la sonde.
     *
     * status indique s’il y a une alerte en cours sur cette mesure. Il peut prendre comme valeur:
     * null: non applicable
     * normal: pas d’alerte en cours
     * warning: alerte en cours sur cette mesure (trop faible ou trop élevée)
     * danger: alerte importante en cours sur cette mesure (vraiment trop faible ou vraiment trop élevé)
     *
     */
    public function updateOklyn(){
        $api = new oklyn();
        $airdate = new DateTime($api->getSonde('air','recorded'));
        $waterdate = new DateTime($api->getSonde('water','recorded'));

        $changed = false;
        $changed = $this->checkAndUpdateCmd('air', $api->getSonde('air','value')) || $changed;
        $changed = $this->checkAndUpdateCmd('dateair', $airdate->format('d-m-Y H:i:s')) || $changed;
        $changed = $this->checkAndUpdateCmd('water', $api->getSonde('water','value')) || $changed;
        $changed = $this->checkAndUpdateCmd('datewater', $waterdate->format('d-m-Y H:i:s')) || $changed;
        $changed = $this->checkAndUpdateCmd('ph', $api->getSonde('ph','value')) || $changed;
        $changed = $this->checkAndUpdateCmd('phstatus', $api->getSonde('ph','status')) || $changed;
        $changed = $this->checkAndUpdateCmd('orp', $api->getSonde('orp','value')) || $changed;
        $changed = $this->checkAndUpdateCmd('orpstatus', $api->getSonde('orp','status')) || $changed;
        $changed = $this->checkAndUpdateCmd('pompe', $api->getPompe('pump')) || $changed;
        $changed = $this->checkAndUpdateCmd('pompestatus', $api->getPompe('status')) || $changed;
        $changed = $this->checkAndUpdateCmd('aux', $api->getAux('aux')) || $changed;
        $changed = $this->checkAndUpdateCmd('auxstatus', $api->getAux('status')) || $changed;

        if ($changed) {
            $this->refreshWidget();
        }
    }

    //Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
    public static function cron30() {
        foreach (oklyn::byType('oklyn') as $eqLogic) {
            if ($eqLogic->getIsEnable() == 1) {
                $eqLogic->updateOklyn();
            }
        }
    }

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {

    }

    public function postInsert() {

    }

    public function preSave() {
        $this->setDisplay("width","800px");
        $this->setDisplay("height","200px");
    }

    public function postSave() {
        $air = $this->getCmd(null, 'air');
        if (!is_object($air)) {
            $air = new oklynCmd();
            $air->setIsHistorized(1);
        }
        $air->setName(__('Air', __FILE__));
        $air->setLogicalId('air');
        $air->setEqLogic_id($this->getId());
        $air->setUnite('°C');
        $air->setType('info');
        $air->setSubType('numeric');
        $air->save();

        $dateair= $this->getCmd(null, 'dateair');
        if (!is_object($dateair)) {
            $dateair = new oklynCmd();
        }
        $dateair->setName(__('Date Air', __FILE__));
        $dateair->setLogicalId('dateair');
        $dateair->setEqLogic_id($this->getId());
        $dateair->setType('info');
        $dateair->setSubType('string');
        $dateair->save();

        $water= $this->getCmd(null, 'water');
        if (!is_object($water)) {
            $water = new oklynCmd();
            $water->setIsHistorized(1);
        }
        $water->setName(__('Eau', __FILE__));
        $water->setLogicalId('water');
        $water->setEqLogic_id($this->getId());
        $water->setUnite('°C');
        $water->setType('info');
        $water->setSubType('numeric');
        $water->save();

        $datewater = $this->getCmd(null, 'datewater');
        if (!is_object($datewater)) {
            $datewater = new oklynCmd();
        }
        $datewater->setName(__('Date Eau', __FILE__));
        $datewater->setLogicalId('datewater');
        $datewater->setEqLogic_id($this->getId());
        $datewater->setType('info');
        $datewater->setSubType('string');
        $datewater->save();

        $ph = $this->getCmd(null, 'ph');
        if (!is_object($ph)) {
            $ph = new oklynCmd();
            $ph->setIsHistorized(1);
        }
        $ph->setName(__('PH', __FILE__));
        $ph->setLogicalId('ph');
        $ph->setEqLogic_id($this->getId());
        $ph->setType('info');
        $ph->setSubType('numeric');
        $ph->save();

        $phstatus = $this->getCmd(null, 'phstatus');
        if (!is_object($phstatus)) {
            $phstatus = new oklynCmd();
        }
        $phstatus->setName(__('Status PH', __FILE__));
        $phstatus->setLogicalId('phstatus');
        $phstatus->setEqLogic_id($this->getId());
        $phstatus->setType('info');
        $phstatus->setSubType('string');
        $phstatus->save();

        $orp= $this->getCmd(null, 'orp');
        if (!is_object($orp)) {
            $orp = new oklynCmd();
            $orp->setIsHistorized(1);
        }
        $orp->setName(__('ORP', __FILE__));
        $orp->setLogicalId('orp');
        $orp->setEqLogic_id($this->getId());
        $orp->setType('info');
        $orp->setSubType('numeric');
        $orp->save();

        $orpstatus = $this->getCmd(null, 'orpstatus');
        if (!is_object($orpstatus)) {
            $orpstatus = new oklynCmd();
        }
        $orpstatus->setName(__('Status ORP', __FILE__));
        $orpstatus->setLogicalId('orpstatus');
        $orpstatus->setEqLogic_id($this->getId());
        $orpstatus->setType('info');
        $orpstatus->setSubType('string');
        $orpstatus->save();

        $pompe = $this->getCmd(null, 'pompe');
        if (!is_object($pompe)) {
            $pompe = new oklynCmd();
        }
        $pompe->setName(__('Pompe', __FILE__));
        $pompe->setLogicalId('pompe');
        $pompe->setEqLogic_id($this->getId());
        $pompe->setType('info');
        $pompe->setSubType('string');
        $pompe->save();

        $pompestatus = $this->getCmd(null, 'pompestatus');
        if (!is_object($pompestatus)) {
            $pompestatus = new oklynCmd();
        }
        $pompestatus->setName(__('Pompe Status', __FILE__));
        $pompestatus->setLogicalId('pompestatus');
        $pompestatus->setEqLogic_id($this->getId());
        $pompestatus->setType('info');
        $pompestatus->setSubType('string');
        $pompestatus->save();

        $aux = $this->getCmd(null, 'aux');
        if (!is_object($aux)) {
            $aux = new oklynCmd();
        }
        $aux->setName(__('Auxiliaire', __FILE__));
        $aux->setLogicalId('aux');
        $aux->setEqLogic_id($this->getId());
        $aux->setType('info');
        $aux->setSubType('string');
        $aux->save();

        $auxstatus = $this->getCmd(null, 'auxstatus');
        if (!is_object($auxstatus)) {
            $auxstatus = new oklynCmd();
        }
        $auxstatus->setName(__('Auxiliaire status', __FILE__));
        $auxstatus->setLogicalId('auxstatus');
        $auxstatus->setEqLogic_id($this->getId());
        $auxstatus->setType('info');
        $auxstatus->setSubType('string');
        $auxstatus->save();

        $auxoff = $this->getCmd(null, 'auxoff');
        if (!is_object($auxoff)) {
            $auxoff = new oklynCmd();
        }
        $auxoff->setName(__('Aux OFF', __FILE__));
        $auxoff->setLogicalId('auxoff');
        $auxoff->setEqLogic_id($this->getId());
        $auxoff->setType('action');
        $auxoff->setSubType('other');
        $auxoff->save();

        $auxon = $this->getCmd(null, 'auxon');
        if (!is_object($auxon)) {
            $auxon = new oklynCmd();
        }
        $auxon->setName(__('Aux ON', __FILE__));
        $auxon->setLogicalId('auxon');
        $auxon->setEqLogic_id($this->getId());
        $auxon->setType('action');
        $auxon->setSubType('other');
        $auxon->save();

        $pompeoff = $this->getCmd(null, 'pompeoff');
        if (!is_object($pompeoff)) {
            $pompeoff = new oklynCmd();
        }
        $pompeoff->setName(__('Pompe OFF', __FILE__));
        $pompeoff->setLogicalId('pompeoff');
        $pompeoff->setEqLogic_id($this->getId());
        $pompeoff->setType('action');
        $pompeoff->setSubType('other');
        $pompeoff->save();

        $pompeon = $this->getCmd(null, 'pompeon');
        if (!is_object($pompeon)) {
            $pompeon = new oklynCmd();
        }
        $pompeon->setName(__('Pompe ON', __FILE__));
        $pompeon->setLogicalId('pompeon');
        $pompeon->setEqLogic_id($this->getId());
        $pompeon->setType('action');
        $pompeon->setSubType('other');
        $pompeon->save();

        $pompeauto = $this->getCmd(null, 'pompeauto');
        if (!is_object($pompeauto)) {
            $pompeauto = new oklynCmd();
        }
        $pompeauto->setName(__('Pompe AUTO', __FILE__));
        $pompeauto->setLogicalId('pompeauto');
        $pompeauto->setEqLogic_id($this->getId());
        $pompeauto->setType('action');
        $pompeauto->setSubType('other');
        $pompeauto->save();

        if ($this->getIsEnable() == 1) {
            $this->updateOklyn();
        }
    }

    public function preUpdate() {

    }

    public function postUpdate() {

    }

    public function preRemove() {

    }

    public function postRemove() {

    }

    // Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
    public function toHtml($_version = 'dashboard') {
        $this->emptyCacheWidget();
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $_version = jeedom::versionAlias($_version);
        foreach ($this->getCmd() as $cmd) {
            if ($cmd->getType() == 'info') {
                $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
            }
        }

        $air = $this->getCmd(null, 'air');
        $replace['#temperature#'] = $air->execCmd();
        $dateair = $this->getCmd(null, 'dateair');
        $replace['#datetemperature#'] = $dateair->execCmd();
        $water = $this->getCmd(null, 'water');
        $replace['#eau#'] = $water->execCmd();
        $datewater = $this->getCmd(null, 'datewater');
        $replace['#dateeau#'] = $datewater->execCmd();
        $ph = $this->getCmd(null, 'ph');
        $replace['#ph#'] = $ph->execCmd();
        $phstatus = $this->getCmd(null, 'phstatus');
        $replace['#phstatus#'] = $phstatus->execCmd();
        $orp = $this->getCmd(null, 'orp');
        $replace['#orp#'] = $orp->execCmd();
        $orpstatus = $this->getCmd(null, 'orpstatus');
        $replace['#orpstatus#'] = $orpstatus->execCmd();
        $pompe = $this->getCmd(null, 'pompe');
        $replace['#pompe#'] = $pompe->execCmd();
        $pompestatus = $this->getCmd(null, 'pompestatus');
        $replace['#pompestatus#'] = $pompestatus->execCmd();
        $pompeoff = $this->getCmd(null, 'pompeoff');
        $replace['#pompeoff_id#'] = $pompeoff->getId();
        $replace['#pompeoff_name#'] = $pompeoff->getName();
        $pompeon = $this->getCmd(null, 'pompeon');
        $replace['#pompeon_id#'] = $pompeon->getId();
        $replace['#pompeon_name#'] = $pompeon->getName();
        $pompeauto = $this->getCmd(null, 'pompeauto');
        $replace['#pompeauto_id#'] = $pompeauto->getId();
        $replace['#pompeauto_name#'] = $pompeauto->getName();
        $aux = $this->getCmd(null, 'aux');
        $replace['#aux#'] = $aux->execCmd();
        $auxstatus = $this->getCmd(null, 'auxstatus');
        $replace['#auxstatus#'] = $auxstatus->execCmd();
        $auxoff = $this->getCmd(null, 'auxoff');
        $replace['#auxoff_id#'] = $auxoff->getId();
        $replace['#auxoff_name#'] = $auxoff->getName();
        $auxon = $this->getCmd(null, 'auxon');
        $replace['#auxon_id#'] = $auxon->getId();
        $replace['#auxon_name#'] = $auxon->getName();

        $html = $this->postToHtml($_version, template_replace($replace, getTemplate('core', $_version, 'oklyn', 'oklyn')));
        cache::set('widgetHtml' . $_version . $this->getId(), $html, 0);
        return $html;
    }

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class oklynCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        $eqlogic = $this->getEqLogic();
        if ($this->getLogicalId() == 'pompeoff') {
            $putpompeoff = $eqlogic->putPompe('off');
            $eqlogic->checkAndUpdateCmd('pompeoff', $putpompeoff);
        }
        if ($this->getLogicalId() == 'pompeon') {
            $putpompeon = $eqlogic->putPompe('on');
            $eqlogic->checkAndUpdateCmd('pompeon', $putpompeon);
        }
        if ($this->getLogicalId() == 'pompeauto') {
            $putpompeauto = $eqlogic->putPompe('auto');
            $eqlogic->checkAndUpdateCmd('pompeauto', $putpompeauto);
        }
        if ($this->getLogicalId() == 'auxoff') {
            $putauxoff = $eqlogic->putAux('off');
            $eqlogic->checkAndUpdateCmd('auxoff', $putauxoff);
        }
        if ($this->getLogicalId() == 'auxon') {
            $putauxon = $eqlogic->putAux('on');
            $eqlogic->checkAndUpdateCmd('auxon', $putauxon);
        }
        $eqlogic->updateOklyn();
    }

    /*     * **********************Getteur Setteur*************************** */
}


