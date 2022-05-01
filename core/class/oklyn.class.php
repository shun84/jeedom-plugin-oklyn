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
    public static $_widgetPossibility = array('custom' => true, 'custom::layout' => false);
    public const GENERICOKLYN = [
        'TEMPERATUREAIR' => 'OKLYN_TEMPERATUREAIR',
        'TEMPERATUREEAU' => 'OKLYN_TEMPERATUREEAU',
        'ORP' => 'OKLYN_ORP',
        'PH' => 'OKLYN_PH',
        'SEL' => 'OKLYN_SEL',
        'AUXOFF' => 'OKLYN_AUXOFF',
        'AUXON' => 'OKLYN_AUXON',
        'POMPEOFF' => 'OKLYN_POMPEOFF',
        'POMPEON' => 'OKLYN_POMPEON',
        'POMPEAUTO' => 'OKLYN_POMPEAUTO'
    ];

    protected const PACKOKLYN = [
        'AUCUN' => 'aucun',
        'PHSEUL' => 'phseul',
        'PHREDOX' => 'phredox',
        'PHREDOXSALT' => 'phredoxsalt'
    ];

    /* ***********************Methode static*************************** */
    public static function cron30() {
        foreach (oklyn::byType('oklyn') as $eqLogic) {
            if ($eqLogic->getIsEnable() == 1) {
                $eqLogic->updateOklyn();
            }
        }
    }

    public static function pluginGenericTypes(): array
    {
        return [
            self::GENERICOKLYN['TEMPERATUREAIR'] => [
                'name' => __('Temperature de l\'air',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Info',
                'subtype' => ['numeric']
            ],
            self::GENERICOKLYN['TEMPERATUREEAU'] => [
                'name' => __('Temperature de l\'eau',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Info',
                'subtype' => ['numeric']
            ],
            self::GENERICOKLYN['ORP'] => [
                'name' => __('ORP',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Info',
                'subtype' => ['numeric']
            ],
            self::GENERICOKLYN['PH'] => [
                'name' => __('PH',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Info',
                'subtype' => ['numeric']
            ],
            self::GENERICOKLYN['SEL'] => [
                'name' => __('SEL',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Info',
                'subtype' => ['numeric']
            ],
            self::GENERICOKLYN['AUXOFF'] => [
                'name' => __('Arrêter l\'auxilaire',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Action',
                'subtype' => ['other']
            ],
            self::GENERICOKLYN['AUXON'] => [
                'name' => __('Lancer l\'auxilaire',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Action',
                'subtype' => ['other']
            ],
            self::GENERICOKLYN['POMPEOFF'] => [
                'name' => __('Arrêter la pompe',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Action',
                'subtype' => ['other']
            ],
            self::GENERICOKLYN['POMPEON'] => [
                'name' => __('Lancer la pompe',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Action',
                'subtype' => ['other']
            ],
            self::GENERICOKLYN['POMPEAUTO'] => [
                'name' => __('Mode automatique de la pompe',__FILE__),
                'familyid' => 'oklyn',
                'family' => __('Plugin Oklyn',__FILE__),
                'type' => 'Action',
                'subtype' => ['other']
            ]
        ];
    }

    /* *********************Méthodes d'instance************************* */

    /**
     * @throws Exception
     */
    public function updateOklyn(){
        $api = new oklynApi(config::byKey('apicle','oklyn'));
        $airdate = new DateTime($api->getSonde('air','recorded'));
        $waterdate = new DateTime($api->getSonde('water','recorded'));
        $phdate = new DateTime($api->getSonde('ph','recorded'));
        $orpdate = new DateTime($api->getSonde('orp','recorded'));
        $saltdate = new DateTime($api->getSonde('salt','recorded'));

        $this->checkAndUpdateCmd('air', $api->getSonde('air','value'));
        $this->checkAndUpdateCmd('dateair', $airdate->format('d/m/Y \à H:i'));
        $this->checkAndUpdateCmd('water', $api->getSonde('water','value'));
        $this->checkAndUpdateCmd('datewater', $waterdate->format('d/m/Y \à H:i'));
        $this->checkAndUpdateCmd('ph', $api->getSonde('ph','value'));
        $this->checkAndUpdateCmd('phstatus', $api->getSonde('ph','status'));
        $this->checkAndUpdateCmd('phdate', $phdate->format('d/m/Y \à H:i'));
        $this->checkAndUpdateCmd('orp', $api->getSonde('orp','value'));
        $this->checkAndUpdateCmd('orpstatus', $api->getSonde('orp','status'));
        $this->checkAndUpdateCmd('orpdate', $orpdate->format('d/m/Y \à H:i'));
        $this->checkAndUpdateCmd('salt', $api->getSonde('salt','value'));
        $this->checkAndUpdateCmd('saltstatus', $api->getSonde('salt','status'));
        $this->checkAndUpdateCmd('saltdate', $saltdate->format('d/m/Y \à H:i'));
        $this->checkAndUpdateCmd('pompe', $api->getPompe('pump'));
        $this->checkAndUpdateCmd('pompestatus', $api->getPompe('status'));
        $this->checkAndUpdateCmd('aux', $api->getAux('aux','aux'));
        $this->checkAndUpdateCmd('auxstatus', $api->getAux('aux','status'));
        $this->checkAndUpdateCmd('auxsecond', $api->getAux('aux2','aux'));
        $this->checkAndUpdateCmd('auxsecondstatus', $api->getAux('aux2','status'));

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

    public function preSave() {
        $this->setDisplay("width","792px");
        $this->setDisplay("height","232px");
    }

    /**
     * @throws Exception
     */
    public function postSave() {
        $air = $this->getCmd(null, 'air');
        if (!is_object($air)) {
            $air = new oklynCmd();
            $air->setIsHistorized(1);
        }
        $air->setName(__('Air', __FILE__));
        $air->setLogicalId('air');
        $air->setEqLogic_id($this->getId());
        $air->setGeneric_type(self::GENERICOKLYN['TEMPERATUREAIR']);
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
        $water->setGeneric_type(self::GENERICOKLYN['TEMPERATUREEAU']);
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
        if ($confpackoklyn == self::PACKOKLYN['PHSEUL'] || $confpackoklyn == self::PACKOKLYN['PHREDOX'] || $confpackoklyn == self::PACKOKLYN['PHREDOXSALT']){
            $ph = $this->getCmd(null, 'ph');
            if (!is_object($ph)) {
                $ph = new oklynCmd();
                $ph->setIsHistorized(1);
            }
            $ph->setName(__('PH', __FILE__));
            $ph->setLogicalId('ph');
            $ph->setEqLogic_id($this->getId());
            $ph->setGeneric_type(self::GENERICOKLYN['PH'] );
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

            if ($confpackoklyn == self::PACKOKLYN['PHREDOX'] || $confpackoklyn == self::PACKOKLYN['PHREDOXSALT']){
                $orp= $this->getCmd(null, 'orp');
                if (!is_object($orp)) {
                    $orp = new oklynCmd();
                    $orp->setIsHistorized(1);
                }
                $orp->setName(__('ORP', __FILE__));
                $orp->setLogicalId('orp');
                $orp->setEqLogic_id($this->getId());
                $orp->setGeneric_type(self::GENERICOKLYN['ORP'] );
                $orp->setUnite('mV');
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

            if ($confpackoklyn == self::PACKOKLYN['PHREDOXSALT']){
                $salt= $this->getCmd(null, 'salt');
                if (!is_object($salt)) {
                    $salt = new oklynCmd();
                    $salt->setIsHistorized(1);
                }
                $salt->setName(__('SEL', __FILE__));
                $salt->setLogicalId('salt');
                $salt->setEqLogic_id($this->getId());
                $salt->setGeneric_type(self::GENERICOKLYN['SEL'] );
                $salt->setUnite('g/L');
                $salt->setType('info');
                $salt->setSubType('numeric');
                $salt->save();

                $saltstatus = $this->getCmd(null, 'saltstatus');
                if (!is_object($saltstatus)) {
                    $saltstatus = new oklynCmd();
                }
                $saltstatus->setName(__('Status SEL', __FILE__));
                $saltstatus->setLogicalId('saltstatus');
                $saltstatus->setEqLogic_id($this->getId());
                $saltstatus->setType('info');
                $saltstatus->setSubType('string');
                $saltstatus->save();

                $saltdate = $this->getCmd(null, 'saltdate');
                if (!is_object($saltdate)) {
                    $saltdate = new oklynCmd();
                }
                $saltdate->setName(__('Date sel', __FILE__));
                $saltdate->setLogicalId('saltdate');
                $saltdate->setEqLogic_id($this->getId());
                $saltdate->setType('info');
                $saltdate->setSubType('string');
                $saltdate->save();
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
        $auxoff->setGeneric_type(self::GENERICOKLYN['AUXOFF']);
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
        $auxon->setGeneric_type(self::GENERICOKLYN['AUXON']);
        $auxon->setType('action');
        $auxon->setSubType('other');
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
        $auxsecond->save();

        $auxsecondstatus = $this->getCmd(null, 'auxsecondstatus');
        if (!is_object($auxsecondstatus)) {
            $auxsecondstatus = new oklynCmd();
        }
        $auxsecondstatus->setName(__('Auxiliaire status 2', __FILE__));
        $auxsecondstatus->setLogicalId('auxsecondstatus');
        $auxsecondstatus->setEqLogic_id($this->getId());
        $auxsecondstatus->setType('info');
        $auxsecondstatus->setSubType('string');
        $auxsecondstatus->save();

        $auxsecondoff = $this->getCmd(null, 'auxsecondoff');
        if (!is_object($auxsecondoff)) {
            $auxsecondoff = new oklynCmd();
        }
        $auxsecondoff->setName(__('Aux OFF 2', __FILE__));
        $auxsecondoff->setLogicalId('auxsecondoff');
        $auxsecondoff->setEqLogic_id($this->getId());
        $auxsecondoff->setGeneric_type(self::GENERICOKLYN['AUXOFF']);
        $auxsecondoff->setType('action');
        $auxsecondoff->setSubType('other');
        $auxsecondoff->save();

        $auxsecondon = $this->getCmd(null, 'auxsecondon');
        if (!is_object($auxsecondon)) {
            $auxsecondon = new oklynCmd();
        }
        $auxsecondon->setName(__('Aux ON 2', __FILE__));
        $auxsecondon->setLogicalId('auxsecondon');
        $auxsecondon->setEqLogic_id($this->getId());
        $auxsecondon->setGeneric_type(self::GENERICOKLYN['AUXON']);
        $auxsecondon->setType('action');
        $auxsecondon->setSubType('other');
        $auxsecondon->save();

        $pompeoff = $this->getCmd(null, 'pompeoff');
        if (!is_object($pompeoff)) {
            $pompeoff = new oklynCmd();
        }
        $pompeoff->setName(__('Pompe OFF', __FILE__));
        $pompeoff->setLogicalId('pompeoff');
        $pompeoff->setEqLogic_id($this->getId());
        $pompeoff->setGeneric_type(self::GENERICOKLYN['POMPEOFF']);
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
        $pompeon->setGeneric_type(self::GENERICOKLYN['POMPEON']);
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
        $pompeauto->setGeneric_type(self::GENERICOKLYN['POMPEAUTO']);
        $pompeauto->setType('action');
        $pompeauto->setSubType('other');
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
        $replace['#temperature#'] = is_object($air) ? $air->execCmd() : '';
        $dateair = $this->getCmd(null, 'dateair');
        $replace['#datetemperature#'] = is_object($dateair) ? $dateair->execCmd() : '';

        $water = $this->getCmd(null, 'water');
        $replace['#eau#'] = is_object($water) ? $water->execCmd() : '';
        $datewater = $this->getCmd(null, 'datewater');
        $replace['#dateeau#'] = is_object($datewater) ? $datewater->execCmd() : '';

        $confpackoklyn = $this->getConfiguration('packoklyn');
        $replace['#confpackoklyn#'] = $confpackoklyn;

        $ph = $this->getCmd(null, 'ph');
        $replace['#ph#'] = is_object($ph) ? $ph->execCmd() : '';
        $phstatus = $this->getCmd(null, 'phstatus');
        $replace['#phstatus#'] = is_object($phstatus) ? $phstatus->execCmd() : '';
        $phdate = $this->getCmd(null, 'phdate');
        $replace['#phdate#'] = is_object($phdate) ? $phdate->execCmd() : '';

        $orp = $this->getCmd(null, 'orp');
        $replace['#orp#'] = is_object($orp) ? $orp->execCmd() : '';
        $orpstatus = $this->getCmd(null, 'orpstatus');
        $replace['#orpstatus#'] = is_object($orpstatus) ? $orpstatus->execCmd() : '';
        $orpdate = $this->getCmd(null, 'orpdate');
        $replace['#orpdate#'] = is_object($orpdate) ? $orpdate->execCmd() : '';

        $salt = $this->getCmd(null, 'salt');
        $replace['#salt#'] = is_object($salt) ? $salt->execCmd() : '';
        $saltstatus = $this->getCmd(null, 'saltstatus');
        $replace['#saltstatus#'] = is_object($saltstatus) ? $saltstatus->execCmd() : '';
        $saltdate = $this->getCmd(null, 'saltdate');
        $replace['#saltdate#'] = is_object($saltdate) ? $saltdate->execCmd() : '';

        $pompe = $this->getCmd(null, 'pompe');
        $replace['#pompe#'] = is_object($pompe) ? $pompe->execCmd() : '';
        $pompestatus = $this->getCmd(null, 'pompestatus');
        $replace['#pompestatus#'] = is_object($pompestatus) ? $pompestatus->execCmd() : '';
        $pompeoff = $this->getCmd(null, 'pompeoff');
        $replace['#pompeoff_id#'] = is_object($pompeoff) ? $pompeoff->getId() : '';
        $pompeon = $this->getCmd(null, 'pompeon');
        $replace['#pompeon_id#'] = is_object($pompeon) ? $pompeon->getId() : '';
        $pompeauto = $this->getCmd(null, 'pompeauto');
        $replace['#pompeauto_id#'] = is_object($pompeauto) ? $pompeauto->getId() : '';

        $aux = $this->getCmd(null, 'aux');
        $replace['#aux#'] = is_object($aux) ? $aux->execCmd() : '';
        $auxstatus = $this->getCmd(null, 'auxstatus');
        $replace['#auxstatus#'] = is_object($auxstatus) ? $auxstatus->execCmd() : '';
        $auxoff = $this->getCmd(null, 'auxoff');
        $replace['#auxoff_id#'] = is_object($auxoff) ? $auxoff->getId() : '';
        $auxon = $this->getCmd(null, 'auxon');
        $replace['#auxon_id#'] = is_object($auxon) ? $auxon->getId() : '';

        $auxsecond = $this->getCmd(null, 'auxsecond');
        $replace['#auxsecond#'] = is_object($auxsecond) ? $auxsecond->execCmd() : '';
        $auxsecondstatus = $this->getCmd(null, 'auxsecondstatus');
        $replace['#auxsecondstatus#'] = is_object($auxsecondstatus) ? $auxsecondstatus->execCmd() : '';
        $auxsecondoff = $this->getCmd(null, 'auxsecondoff');
        $replace['#auxsecondoff_id#'] = is_object($auxsecondoff) ? $auxsecondoff->getId() : '';
        $auxsecondon = $this->getCmd(null, 'auxsecondon');
        $replace['#auxsecondon_id#'] = is_object($auxsecondon) ? $auxsecondon->getId() : '';

        $confaux = $this->getConfiguration('auxiliaire');
        $replace['#confaux#'] = $confaux;

        $confauxsecond = $this->getConfiguration('auxiliairesecond');
        $replace['#confauxsecond#'] = $confauxsecond;

        $html = $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'oklyn', 'oklyn')));
        cache::set('widgetHtml' . $_version . $this->getId(), $html, 0);
        return $html;
    }
}

class oklynCmd extends cmd {
    public static $_widgetPossibility = ['custom' => false];

    /**
     * @throws Exception
     */
    public function execute($_options = array()) {
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


