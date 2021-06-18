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
require_once __DIR__ . '/../../resources/apioklyn/Apioklyn.php';

class oklyn extends eqLogic {
    /*     * *************************Attributs****************************** */
    public static $_widgetPossibility = array('custom' => true, 'custom::layout' => false);

    /*     * ***********************Methode static*************************** */

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
     */
    public function updateOklyn(){
        $api = new Apioklyn(config::byKey('apicle','oklyn'));
        $airdate = new DateTime($api->getSonde('air','recorded'));
        $waterdate = new DateTime($api->getSonde('water','recorded'));
        $phdate = new DateTime($api->getSonde('ph','recorded'));
        $orpdate = new DateTime($api->getSonde('orp','recorded'));

        $changed = false;
        $changed = $this->checkAndUpdateCmd('air', $api->getSonde('air','value')) || $changed;
        $changed = $this->checkAndUpdateCmd('dateair', $airdate->format('d/m/Y \à H:i')) || $changed;
        $changed = $this->checkAndUpdateCmd('water', $api->getSonde('water','value')) || $changed;
        $changed = $this->checkAndUpdateCmd('datewater', $waterdate->format('d/m/Y \à H:i')) || $changed;
        $changed = $this->checkAndUpdateCmd('ph', $api->getSonde('ph','value')) || $changed;
        $changed = $this->checkAndUpdateCmd('phstatus', $api->getSonde('ph','status')) || $changed;
        $changed = $this->checkAndUpdateCmd('phdate', $phdate->format('d/m/Y \à H:i')) || $changed;
        $changed = $this->checkAndUpdateCmd('orp', $api->getSonde('orp','value')) || $changed;
        $changed = $this->checkAndUpdateCmd('orpstatus', $api->getSonde('orp','status')) || $changed;
        $changed = $this->checkAndUpdateCmd('orpdate', $orpdate->format('d/m/Y \à H:i')) || $changed;
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
            if ($eqLogic->getIsEnable() === 1) {
                $eqLogic->updateOklyn();
            }
        }
    }

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert(){
        $apikey = config::byKey('apicle','oklyn');
        if ($apikey === '') {
            throw new Exception(__('Veulliez renseigner la clef d\'api dans Configuration', __FILE__));
        }
    }

    public function preUpdate() {
        if ($this->getConfiguration('packoklyn') === '') {
            throw new Exception(__('Veuillez sélectionner le pack acheter chez Oklyn', __FILE__));
        }

        if ($this->getConfiguration('auxiliaire') === '') {
            throw new Exception(__('Veuillez sélectionner si vous utiliser un auxilaire ou pas', __FILE__));
        }
    }

    public function preSave() {
        $this->setDisplay("width","632px");
        $this->setDisplay("height","272px");
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

        $confpackoklyn = $this->getConfiguration('packoklyn');
        if ($confpackoklyn === 'phseul' || $confpackoklyn === 'phredox'){
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

            $phdate = $this->getCmd(null, 'phdate');
            if (!is_object($phdate)) {
                $phdate = new oklynCmd();
            }
            $phdate->setName(__('Date ph', __FILE__));
            $phdate->setLogicalId('phdate');
            $phdate->setEqLogic_id($this->getId());
            $phdate->setType('info');
            $phdate->setSubType('string');
            $phdate->save();

            if ($confpackoklyn === 'phredox'){
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

                $orpdate = $this->getCmd(null, 'orpdate');
                if (!is_object($orpdate)) {
                    $orpdate = new oklynCmd();
                }
                $orpdate->setName(__('Date orp', __FILE__));
                $orpdate->setLogicalId('orpdate');
                $orpdate->setEqLogic_id($this->getId());
                $orpdate->setType('info');
                $orpdate->setSubType('string');
                $orpdate->save();
            }
        }

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

    public function toHtml($_version = 'dashboard') {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $_version = jeedom::versionAlias($_version);

        // Température de l'air
        $air = $this->getCmd(null, 'air');
        $replace['#temperature#'] = $air->execCmd();
        $dateair = $this->getCmd(null, 'dateair');
        $replace['#datetemperature#'] = $dateair->execCmd();

        // Température de l'eau
        $water = $this->getCmd(null, 'water');
        $replace['#eau#'] = $water->execCmd();
        $datewater = $this->getCmd(null, 'datewater');
        $replace['#dateeau#'] = $datewater->execCmd();

        // Sondes PH et ORP
        $confpackoklyn = $this->getConfiguration('packoklyn');
        if ($confpackoklyn === 'aucun'){
            $replace['#phseul#'] = 'phaucun';
            $replace['#phredox#'] = 'phredoxaucun';
        }elseif ($confpackoklyn === 'phseul' || $confpackoklyn === 'phredox'){
            if ($confpackoklyn !== 'phredox'){
                $replace['#phredox#'] = 'phredoxaucun';
            } else {
                $replace['#phredox#'] = 'phredox';
                $orp = $this->getCmd(null, 'orp');
                $replace['#orp#'] = $orp->execCmd();
                $orpstatus = $this->getCmd(null, 'orpstatus');
                $replace['#orpstatus#'] = $orpstatus->execCmd();
                $orpdate = $this->getCmd(null, 'orpdate');
                $replace['#orpdate#'] = $orpdate->execCmd();
            }
            $replace['#phseul#'] = 'phseul';
            $ph = $this->getCmd(null, 'ph');
            $replace['#ph#'] = $ph->execCmd();
            $phstatus = $this->getCmd(null, 'phstatus');
            $replace['#phstatus#'] = $phstatus->execCmd();
            $phdate = $this->getCmd(null, 'phdate');
            $replace['#phdate#'] = $phdate->execCmd();
        }

        // Pompe de pisicne
        $pompe = $this->getCmd(null, 'pompe');
        $replace['#pompe#'] = $pompe->execCmd();
        $pompestatus = $this->getCmd(null, 'pompestatus');
        $replace['#pompestatus#'] = $pompestatus->execCmd();
        $pompeoff = $this->getCmd(null, 'pompeoff');
        $replace['#pompeoff_id#'] = $pompeoff->getId();
        $pompeon = $this->getCmd(null, 'pompeon');
        $replace['#pompeon_id#'] = $pompeon->getId();
        $pompeauto = $this->getCmd(null, 'pompeauto');
        $replace['#pompeauto_id#'] = $pompeauto->getId();

        //Gérer la fonction auxiliaire
        $aux = $this->getCmd(null, 'aux');
        $replace['#aux#'] = $aux->execCmd();
        $auxstatus = $this->getCmd(null, 'auxstatus');
        $replace['#auxstatus#'] = $auxstatus->execCmd();
        $auxoff = $this->getCmd(null, 'auxoff');
        $replace['#auxoff_id#'] = $auxoff->getId();
        $auxon = $this->getCmd(null, 'auxon');
        $replace['#auxon_id#'] = $auxon->getId();

        $confaux = $this->getConfiguration('auxiliaire');
        if ($confaux == 'aucun'){
            $replace['#confaux#'] = 'aucun';
        } elseif ($confaux == 'lumiere'){
            $replace['#confaux#'] = 'lumiere';
            $replace['#icon_aux#'] = '<i class="fas fa-lightbulb fa-5x"></i>';
        } elseif ($confaux == 'chauffage'){
            $replace['#confaux#'] = 'chauffage';
            $replace['#icon_aux#'] = '<i class="fas fa-thermometer-full fa-5x"></i>';
        }

        $html = $this->postToHtml($_version, template_replace($replace, getTemplate('core', $_version, 'oklyn', 'oklyn')));
        cache::set('widgetHtml' . $_version . $this->getId(), $html, 0);
        return $html;
    }
}

class oklynCmd extends cmd {
    public function execute($_options = array()) {
        $api = new Apioklyn(config::byKey('apicle','oklyn'));
        $eqlogic = $this->getEqLogic();

        if ($this->getLogicalId() === 'pompeoff') {
            $putpompeoff = $api->putPompe('off');
            $errorapi = json_decode($putpompeoff);
            if ($errorapi->{'error'}){
                throw new Exception(__('L\'arret de la pompe n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action pompe Off');
                $eqlogic->checkAndUpdateCmd('pompeoff', $putpompeoff);
            }
        }
        if ($this->getLogicalId() === 'pompeon') {
            $putpompeon = $api->putPompe('on');
            $errorapi = json_decode($putpompeon);
            if ($errorapi->{'error'}){
                throw new Exception(__('Le lancement de la pompe n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action pompe On');
                $eqlogic->checkAndUpdateCmd('pompeon', $putpompeon);
            }
        }
        if ($this->getLogicalId() === 'pompeauto') {
            $putpompeauto = $api->putPompe('auto');
            $errorapi = json_decode($putpompeauto);
            if ($errorapi->{'error'}){
                throw new Exception(__('L\'automatisation de la pompe n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action pompe Auto');
                $eqlogic->checkAndUpdateCmd('pompeauto', $putpompeauto);
            }
        }
        if ($this->getLogicalId() === 'auxoff') {
            $putauxoff = $api->putAux('off');
            $errorapi = json_decode($putauxoff);
            if ($errorapi->{'error'}){
                throw new Exception(__('L\'arret de l\'auxilaire n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action auxilaire Off');
                $eqlogic->checkAndUpdateCmd('auxoff', $putauxoff);
            }
        }
        if ($this->getLogicalId() === 'auxon') {
            $putauxon = $api->putAux('on');
            $errorapi = json_decode($putauxon);
            if ($errorapi->{'error'}){
                throw new Exception(__('Le lancement de l\'auxilaire n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action auxilaire On');
                $eqlogic->checkAndUpdateCmd('auxon', $putauxon);
            }
        }
        $eqlogic->updateOklyn();
    }
}


