<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('oklyn');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-cog"></i>{{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction logoPrimary" data-action="add">
                <i class="fas fa-plus-circle"></i>
                <br>
                <span>{{Ajouter}}</span>
            </div>
            <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
                <i class="fas fa-wrench"></i>
                <br>
                <span>{{Configuration}}</span>
            </div>
        </div>
        <legend><i class="fas fa-table"></i> {{Mes templates}}</legend>
        <?php
            if (count($eqLogics) == 0) {
                echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement Template trouvé, cliquer sur "Ajouter" pour commencer}}</div>';
            } else {
                // Champ de recherche
                echo '<div class="input-group" style="margin:5px;">';
                echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
                echo '<div class="input-group-btn">';
                echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
                echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
                echo '</div>';
                echo '</div>';
                // Liste des équipements du plugin
                echo '<div class="eqLogicThumbnailContainer">';
                foreach ($eqLogics as $eqLogic) {
                    $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                    echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
                    echo '<img src="' . $plugin->getPathImgIcon() . '">';
                    echo '<br>';
                    echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                    echo '<span class="hiddenAsCard displayTableRight hidden">';
                    echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
                    echo '</span>';
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
    </div>

    <div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
            <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br/>
                <form class="form-horizontal">
                    <fieldset>
                        <div class="col-lg-7">
                            <legend><i class="fas fa-wrench"></i> {{Général}}</legend>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
                                <div class="col-sm-3">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="sel_object">{{Objet parent}}</label>
                                <div class="col-sm-3">
                                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                        <option value="">{{Aucun}}</option>
                                        <?php
                                            $options = '';
                                            foreach ((jeeObject::buildTree(null, false)) as $object) {
                                                $options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
                                            }
                                            echo $options;
                                        ?>
                                   </select>
                               </div>
                           </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Catégorie}}</label>
                                    <div class="col-sm-9">
                                        <?php
                                            foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                                echo '<label class="checkbox-inline">';
                                                echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
                                                echo '</label>';
                                            }
                                        ?>
                                   </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                                </div>
                            </div>
                            <legend><i class="fas fa-cogs"></i> {{Paramètres}}</legend>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="packoklyn">{{Pack Oklyn}}</label>
                                <div class="col-sm-3" style="width: auto">
                                    <select id="packoklyn" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="packoklyn">
                                        <option></option>
                                        <option value="aucun">Aucun (Oklyn Filtration)</option>
                                        <option value="phseul">Ph seul (Oklyn Filtration + PH seul)</option>
                                        <option value="phredox">Ph + Redox (Oklyn Filtration + Analyse)</option>
                                        <option value="phredoxsalt">Ph + Redox + sel (Oklyn Filtration + Analyse + Sel)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="auxiliaire">{{Auxilaire}}</label>
                                <div class="col-sm-3" style="width: auto">
                                    <select id="auxiliaire" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="auxiliaire">
                                        <option></option>
                                        <option value="aucun">Aucun</option>
                                        <option value="lumiere">Lumière</option>
                                        <option value="chauffage">Chauffage</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="auxiliairesecond">{{Auxilaire 2}}</label>
                                <div class="col-sm-3" style="width: auto">
                                    <select id="auxiliairesecond" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="auxiliairesecond">
                                        <option></option>
                                        <option value="aucun">Aucun</option>
                                        <option value="lumiere">Lumière</option>
                                        <option value="chauffage">Chauffage</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <legend><i class="fas fa-info"></i> {{Informations}}</legend>
                            <div class="form-group">
                                <div class="text-center">
                                    <img name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" style="max-width:160px;"/>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="commandtab">
                <table id="table_cmd" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>{{Nom}}</th><th>{{Type}}</th><th>{{Action}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_file('desktop', 'oklyn', 'js', 'oklyn');?>
<?php include_file('core', 'plugin.template', 'js');?>
