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
require_once __DIR__ . '/../../core/api/oklynApi.php';

class oklyn extends eqLogic {
    /* *************************Attributs****************************** */
    public static $_widgetPossibility = ['custom' => true, 'custom::layout' => false];

    protected const PACKOKLYN = [
        'AUCUN' => 'aucun',
        'PHSEUL' => 'phseul',
        'PHREDOX' => 'phredox',
        'PHREDOXSALT' => 'phredoxsalt'
    ];

    /* ***********************Methode static*************************** */
    public static function cron() {
        foreach (oklyn::byType('oklyn') as $eqLogic) {
            if ($eqLogic->getIsEnable() == 1) {
                $api = new oklynApi(config::byKey('apicle','oklyn'));

                $eqLogic->checkAndUpdateCmd('pompe', $api->getPompe('pump'));
                $eqLogic->checkAndUpdateCmd('pompestatus', $api->getPompe('status'));
                $eqLogic->checkAndUpdateCmd('aux', $api->getAux('aux','aux'));
                $eqLogic->checkAndUpdateCmd('auxsecond', $api->getAux('aux2','aux'));

                $eqLogic->refreshWidget();
            }
        }
    }

    public static function cron30() {
        foreach (oklyn::byType('oklyn') as $eqLogic) {
            if ($eqLogic->getIsEnable() == 1) {
                $eqLogic->updateOklyn();
            }
        }
    }

    /* *********************Méthodes d'instance************************* */

    /**
     * @throws Exception
     */
    public function updateOklyn(){
        $api = new oklynApi(config::byKey('apicle','oklyn'));
        $airdate = new DateTime($api->getSonde('air','recorded'));

        $this->checkAndUpdateCmd('air', $api->getSonde('air','value'));
        $this->checkAndUpdateCmd('dateair', $airdate->format('d/m/Y \à H:i'));
        $this->checkAndUpdateCmd('water', $api->getSonde('water','value'));
        $this->checkAndUpdateCmd('ph', $api->getSonde('ph','value'));
        $this->checkAndUpdateCmd('orp', $api->getSonde('orp','value'));
        $this->checkAndUpdateCmd('salt', $api->getSonde('salt','value'));
        $this->checkAndUpdateCmd('pompe', $api->getPompe('pump'));
        $this->checkAndUpdateCmd('pompestatus', $api->getPompe('status'));
        $this->checkAndUpdateCmd('aux', $api->getAux('aux','aux'));
        $this->checkAndUpdateCmd('auxsecond', $api->getAux('aux2','aux'));

        $this->refreshWidget();
    }

    /**
     * @throws Exception
     */
    public function preInsert(){
        $apikey = config::byKey('apicle','oklyn');
        if ($apikey == '') {
            throw new Exception(__('Veulliez renseigner la clef d\'api dans Configuration', __FILE__));
        }
    }

    /**
     * @throws Exception
     */
    public function preUpdate() {
        if ($this->getConfiguration('packoklyn') == '') {
            throw new Exception(__('Veuillez sélectionner le pack acheter chez Oklyn', __FILE__));
        }

        if ($this->getConfiguration('auxiliaire') == '') {
            throw new Exception(__('Veuillez sélectionner si vous utiliser un auxilaire ou pas', __FILE__));
        }

        if ($this->getConfiguration('auxiliairesecond') == '') {
            throw new Exception(__('Veuillez sélectionner si vous utiliser un auxilaire 2 ou pas', __FILE__));
        }
    }

    /**
     * @throws Exception
     */
    public function postSave() {
        $air = $this->getCmd(null, 'air');
        if (!is_object($air)) {
            $air = new oklynCmd();
        }
        $air->setName(__('Air', __FILE__));
        $air->setLogicalId('air');
        $air->setEqLogic_id($this->getId());
        $air->setGeneric_type('TEMPERATURE');
        $air->setUnite('°C');
        $air->setType('info');
        $air->setSubType('numeric');
        $air->setIsHistorized(1);
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
        }
        $water->setName(__('Eau', __FILE__));
        $water->setLogicalId('water');
        $water->setEqLogic_id($this->getId());
        $water->setGeneric_type('TEMPERATURE');
        $water->setUnite('°C');
        $water->setType('info');
        $water->setSubType('numeric');
        $water->setIsHistorized(1);
        $water->save();

        $confpackoklyn = $this->getConfiguration('packoklyn');
        if ($confpackoklyn == self::PACKOKLYN['PHSEUL'] || $confpackoklyn == self::PACKOKLYN['PHREDOX'] || $confpackoklyn == self::PACKOKLYN['PHREDOXSALT']){
            $ph = $this->getCmd(null, 'ph');
            if (!is_object($ph)) {
                $ph = new oklynCmd();

            }
            $ph->setName(__('Ph', __FILE__));
            $ph->setLogicalId('ph');
            $ph->setEqLogic_id($this->getId());
            $ph->setGeneric_type('GENERIC_INFO');
            $ph->setType('info');
            $ph->setSubType('numeric');
            $ph->setUnite('Ph');
            $ph->setIsHistorized(1);
            $ph->save();

            if ($confpackoklyn == self::PACKOKLYN['PHREDOX'] || $confpackoklyn == self::PACKOKLYN['PHREDOXSALT']){
                $orp= $this->getCmd(null, 'orp');
                if (!is_object($orp)) {
                    $orp = new oklynCmd();
                }
                $orp->setName(__('Orp', __FILE__));
                $orp->setLogicalId('orp');
                $orp->setEqLogic_id($this->getId());
                $orp->setGeneric_type('GENERIC_INFO');
                $orp->setUnite('mV');
                $orp->setType('info');
                $orp->setSubType('numeric');
                $orp->setIsHistorized(1);
                $orp->save();
            }

            if ($confpackoklyn == self::PACKOKLYN['PHREDOXSALT']){
                $salt= $this->getCmd(null, 'salt');
                if (!is_object($salt)) {
                    $salt = new oklynCmd();
                }
                $salt->setName(__('Sel', __FILE__));
                $salt->setLogicalId('salt');
                $salt->setEqLogic_id($this->getId());
                $salt->setGeneric_type('GENERIC_INFO');
                $salt->setUnite('g/L');
                $salt->setType('info');
                $salt->setSubType('numeric');
                $salt->setIsHistorized(1);
                $salt->save();
            }
        }

        $aux = $this->getCmd(null, 'aux');
        if (!is_object($aux)) {
            $aux = new oklynCmd();
        }
        $aux->setName(__('Auxiliaire', __FILE__));
        $aux->setLogicalId('aux');
        $aux->setEqLogic_id($this->getId());
        $aux->setType('info');
        $aux->setSubType('string');
        $aux->setGeneric_type('GENERIC_INFO');
        $aux->save();

        $auxoff = $this->getCmd(null, 'auxoff');
        if (!is_object($auxoff)) {
            $auxoff = new oklynCmd();
        }
        $auxoff->setName(__('Aux Off', __FILE__));
        $auxoff->setLogicalId('auxoff');
        $auxoff->setEqLogic_id($this->getId());
        $auxoff->setGeneric_type('GENERIC_ACTION');
        $auxoff->setType('action');
        $auxoff->setSubType('other');
        $auxoff->setValue($aux->getId());
        $auxoff->save();

        $auxon = $this->getCmd(null, 'auxon');
        if (!is_object($auxon)) {
            $auxon = new oklynCmd();
        }
        $auxon->setName(__('Aux On', __FILE__));
        $auxon->setLogicalId('auxon');
        $auxon->setEqLogic_id($this->getId());
        $auxon->setGeneric_type('GENERIC_ACTION');
        $auxon->setType('action');
        $auxon->setSubType('other');
        $auxon->setValue($aux->getId());
        $auxon->save();

        $auxsecond = $this->getCmd(null, 'auxsecond');
        if (!is_object($auxsecond)) {
            $auxsecond = new oklynCmd();
        }
        $auxsecond->setName(__('Auxiliaire 2', __FILE__));
        $auxsecond->setLogicalId('auxsecond');
        $auxsecond->setEqLogic_id($this->getId());
        $auxsecond->setType('info');
        $auxsecond->setSubType('string');
        $auxsecond->setGeneric_type('GENERIC_INFO');
        $auxsecond->save();

        $auxsecondoff = $this->getCmd(null, 'auxsecondoff');
        if (!is_object($auxsecondoff)) {
            $auxsecondoff = new oklynCmd();
        }
        $auxsecondoff->setName(__('Aux 2 Off', __FILE__));
        $auxsecondoff->setLogicalId('auxsecondoff');
        $auxsecondoff->setEqLogic_id($this->getId());
        $auxsecondoff->setGeneric_type('GENERIC_ACTION');
        $auxsecondoff->setType('action');
        $auxsecondoff->setSubType('other');
        $auxsecondoff->setValue($auxsecond->getId());
        $auxsecondoff->save();

        $auxsecondon = $this->getCmd(null, 'auxsecondon');
        if (!is_object($auxsecondon)) {
            $auxsecondon = new oklynCmd();
        }
        $auxsecondon->setName(__('Aux 2 On', __FILE__));
        $auxsecondon->setLogicalId('auxsecondon');
        $auxsecondon->setEqLogic_id($this->getId());
        $auxsecondon->setGeneric_type('GENERIC_ACTION');
        $auxsecondon->setType('action');
        $auxsecondon->setSubType('other');
        $auxsecondon->setValue($auxsecond->getId());
        $auxsecondon->save();

        $pompe = $this->getCmd(null, 'pompe');
        if (!is_object($pompe)) {
            $pompe = new oklynCmd();
        }
        $pompe->setName(__('Pompe', __FILE__));
        $pompe->setLogicalId('pompe');
        $pompe->setEqLogic_id($this->getId());
        $pompe->setType('info');
        $pompe->setSubType('string');
        $pompe->setGeneric_type('GENERIC_INFO');
        $pompe->save();

        $pompestatus = $this->getCmd(null, 'pompestatus');
        if (!is_object($pompestatus)) {
            $pompestatus = new oklynCmd();
        }
        $pompestatus->setName(__('Pompe status', __FILE__));
        $pompestatus->setLogicalId('pompestatus');
        $pompestatus->setEqLogic_id($this->getId());
        $pompestatus->setType('info');
        $pompestatus->setSubType('string');
        $pompestatus->setIsHistorized(1);
        $pompestatus->save();

        $pompeoff = $this->getCmd(null, 'pompeoff');
        if (!is_object($pompeoff)) {
            $pompeoff = new oklynCmd();
        }
        $pompeoff->setName(__('Off', __FILE__));
        $pompeoff->setLogicalId('pompeoff');
        $pompeoff->setEqLogic_id($this->getId());
        $pompeoff->setGeneric_type('GENERIC_ACTION');
        $pompeoff->setType('action');
        $pompeoff->setSubType('other');
        $pompeoff->setValue($pompe->getId());
        $pompeoff->save();

        $pompeon = $this->getCmd(null, 'pompeon');
        if (!is_object($pompeon)) {
            $pompeon = new oklynCmd();
        }
        $pompeon->setName(__('On', __FILE__));
        $pompeon->setLogicalId('pompeon');
        $pompeon->setEqLogic_id($this->getId());
        $pompeon->setGeneric_type('GENERIC_ACTION');
        $pompeon->setType('action');
        $pompeon->setSubType('other');
        $pompeon->setValue($pompe->getId());
        $pompeon->save();

        $pompeauto = $this->getCmd(null, 'pompeauto');
        if (!is_object($pompeauto)) {
            $pompeauto = new oklynCmd();
        }
        $pompeauto->setName(__('Auto', __FILE__));
        $pompeauto->setLogicalId('pompeauto');
        $pompeauto->setEqLogic_id($this->getId());
        $pompeauto->setGeneric_type('GENERIC_ACTION');
        $pompeauto->setType('action');
        $pompeauto->setSubType('other');
        $pompeauto->setValue($pompe->getId());
        $pompeauto->save();

        if ($this->getIsEnable() == 1) {
            $this->updateOklyn();
        }
    }

    /**
     * @throws Exception
     */
    public function toHtml($_version = 'dashboard') {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $version = jeedom::versionAlias($_version);

        $air = $this->getCmd(null, 'air');
        $replace['#idair#'] = is_object($air) ? $air->getId() : '';
        $replace['#stateair#'] = is_object($air) ? $air->execCmd() : '';
        $replace['#historyair#'] = is_object($air) && $air->getIsHistorized() === "1" ? 'history cursor' : '';
        $dateair = $this->getCmd(null, 'dateair');
        $replace['#datetemperature#'] = is_object($dateair) ? $dateair->execCmd() : '';

        $water = $this->getCmd(null, 'water');
        $replace['#idwater#'] = is_object($water) ? $water->getId() : '';
        $replace['#statewater#'] = is_object($water) ? $water->execCmd() : '';
        $replace['#historywater#'] = is_object($water) && $water->getIsHistorized() === "1" ? 'history cursor' : '';

        $confpackoklyn = $this->getConfiguration('packoklyn');
        $replace['#confpackoklyn#'] = $confpackoklyn;

        $ph = $this->getCmd(null, 'ph');
        $replace['#idph#'] = is_object($ph) ? $ph->getId() : '';
        $replace['#stateph#'] = is_object($ph) ? $ph->execCmd() : '';
        $replace['#historyph#'] = is_object($ph) && $ph->getIsHistorized() === "1" ? 'history cursor' : '';

        $orp = $this->getCmd(null, 'orp');
        $replace['#idorp#'] = is_object($orp) ? $orp->getId() : '';
        $replace['#stateorp#'] = is_object($orp) ? $orp->execCmd() : '';
        $replace['#historyorp#'] = is_object($orp) && $orp->getIsHistorized() === "1" ? 'history cursor' : '';

        $salt = $this->getCmd(null, 'salt');
        $replace['#idsalt#'] = is_object($salt) ? $salt->getId() : '';
        $replace['#statesalt#'] = is_object($salt) ? $salt->execCmd() : '';
        $replace['#historysalt#'] = is_object($salt) && $salt->getIsHistorized() === "1" ? 'history cursor' : '';

        $pompe = $this->getCmd(null, 'pompe');
        $replace['#pompe#'] = is_object($pompe) ? $pompe->execCmd() : '';
        $pompeoff = $this->getCmd(null, 'pompeoff');
        $replace['#pompeoff_id#'] = is_object($pompeoff) ? $pompeoff->getId() : '';
        $pompeon = $this->getCmd(null, 'pompeon');
        $replace['#pompeon_id#'] = is_object($pompeon) ? $pompeon->getId() : '';
        $pompeauto = $this->getCmd(null, 'pompeauto');
        $replace['#pompeauto_id#'] = is_object($pompeauto) ? $pompeauto->getId() : '';

        $aux = $this->getCmd(null, 'aux');
        $replace['#aux#'] = is_object($aux) ? $aux->execCmd() : '';
        $auxoff = $this->getCmd(null, 'auxoff');
        $replace['#auxoff_id#'] = is_object($auxoff) ? $auxoff->getId() : '';
        $auxon = $this->getCmd(null, 'auxon');
        $replace['#auxon_id#'] = is_object($auxon) ? $auxon->getId() : '';

        $auxsecond = $this->getCmd(null, 'auxsecond');
        $replace['#auxsecond#'] = is_object($auxsecond) ? $auxsecond->execCmd() : '';
        $auxsecondoff = $this->getCmd(null, 'auxsecondoff');
        $replace['#auxsecondoff_id#'] = is_object($auxsecondoff) ? $auxsecondoff->getId() : '';
        $auxsecondon = $this->getCmd(null, 'auxsecondon');
        $replace['#auxsecondon_id#'] = is_object($auxsecondon) ? $auxsecondon->getId() : '';

        $confaux = $this->getConfiguration('auxiliaire');
        $replace['#confaux#'] = $confaux;

        $confauxsecond = $this->getConfiguration('auxiliairesecond');
        $replace['#confauxsecond#'] = $confauxsecond;

        if (
            $this->getConfiguration('auxiliaire') !== 'aucun' &&
            $this->getConfiguration('packoklyn') === 'phredox' &&
            $this->getConfiguration('auxiliairesecond') === 'aucun'
        ){
            $html = $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'phredox', 'oklyn')));
        } else {
            $html = $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'oklyn', 'oklyn')));
        }

        cache::set('widgetHtml' . $_version . $this->getId(), $html);
        return $html;
    }
}

class oklynCmd extends cmd {
    public static $_widgetPossibility = ['custom' => false];

    /**
     * @throws Exception
     */
    public function execute($_options = []) {
        $api = new oklynApi(config::byKey('apicle','oklyn'));

        if ($this->getLogicalId() == 'pompeoff') {
            $putpompeoff = $api->putPompe('off');
            $errorapi = json_decode($putpompeoff);
            if ($errorapi->{'error'}){
                throw new Exception(__('L\'arret de la pompe n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action pompe Off');
                $this->getEqLogic()->checkAndUpdateCmd('pompeoff', $putpompeoff);
            }
        }
        if ($this->getLogicalId() == 'pompeon') {
            $putpompeon = $api->putPompe('on');
            $errorapi = json_decode($putpompeon);
            if ($errorapi->{'error'}){
                throw new Exception(__('Le lancement de la pompe n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action pompe On');
                $this->getEqLogic()->checkAndUpdateCmd('pompeon', $putpompeon);
            }
        }
        if ($this->getLogicalId() == 'pompeauto') {
            $putpompeauto = $api->putPompe('auto');
            $errorapi = json_decode($putpompeauto);
            if ($errorapi->{'error'}){
                throw new Exception(__('L\'automatisation de la pompe n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action pompe Auto');
                $this->getEqLogic()->checkAndUpdateCmd('pompeauto', $putpompeauto);
            }
        }
        if ($this->getLogicalId() == 'auxoff') {
            $putauxoff = $api->putAux('aux','off');
            $errorapi = json_decode($putauxoff);
            if ($errorapi->{'error'}){
                throw new Exception(__('L\'arret de l\'auxilaire n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action auxilaire Off');
                $this->getEqLogic()->checkAndUpdateCmd('auxoff', $putauxoff);
            }
        }
        if ($this->getLogicalId() == 'auxon') {
            $putauxon = $api->putAux('aux','on');
            $errorapi = json_decode($putauxon);
            if ($errorapi->{'error'}){
                throw new Exception(__('Le lancement de l\'auxilaire n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action auxilaire On');
                $this->getEqLogic()->checkAndUpdateCmd('auxon', $putauxon);
            }
        }
        if ($this->getLogicalId() == 'auxsecondoff') {
            $putauxsecondoff = $api->putAux('aux2','off');
            $errorapi = json_decode($putauxsecondoff);
            if ($errorapi->{'error'}){
                throw new Exception(__('L\'arret de l\'auxilaire 2 n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action auxilaire Off 2');
                $this->getEqLogic()->checkAndUpdateCmd('auxsecondoff', $putauxsecondoff);
            }
        }
        if ($this->getLogicalId() == 'auxsecondon') {
            $putauxsecondon = $api->putAux('aux2','on');
            $errorapi = json_decode($putauxsecondon);
            if ($errorapi->{'error'}){
                throw new Exception(__('Le lancement de l\'auxilaire 2 n\'a pas pu se faire : '.$errorapi->{'formatted_error'}, __FILE__));
            }else{
                log::add('oklyn','debug','Lancement de l\'action auxilaire On 2');
                $this->getEqLogic()->checkAndUpdateCmd('auxsecondon', $putauxsecondon);
            }
        }
        $this->getEqLogic()->updateOklyn();
    }
}


