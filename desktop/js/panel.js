jeedomUtils.datePickerInit()

document.getElementById('bt_validChangeDate').addEventListener('click', function () {
    displayOklyn(
        object_id,
        document.getElementById('in_startDate').value,
        document.getElementById('in_endDate').value
    )
})

displayOklyn(object_id, '', '')

function displayOklyn(object_id, _dateStart, _dateEnd) {
    domUtils.ajax({
        type: 'POST',
        url: 'plugins/oklyn/core/ajax/oklyn.ajax.php',
        data: {
            action: 'getOklyn',
            object_id: object_id,
            version: 'dashboard',
            dateStart : _dateStart,
            dateEnd : _dateEnd
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            handleAjaxError(request, status, error)
        },
        success: function (data) {
            if (data.state !== 'ok') {
                jeedomUtils.showAlert({
                    message: data.result,
                    level: 'danger'
                })
            }
            let icon = '';
            if (isset(data.result.object.display) && isset(data.result.object.display.icon)) {
                icon = data.result.object.display.icon
            }
            document.getElementById('oklynname').innerHTML = icon + ' ' + data.result.object.name

            for (let i in data.result.eqLogics) {
                graphesOklyn(
                    data.result.eqLogics[i].eqLogic.id,
                    data.result.eqLogics[i].eqLogic.configuration.packoklyn,
                    data.result.date.start,
                    data.result.date.end
                );
            }
        }
    })
}

function graphesOklyn(_eqLogic_id, _param_pack, _dateStart, _dateEnd) {
    jeedom.eqLogic.getCmd({
        id: _eqLogic_id,
        error: function (error) {
            jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
            })
        },
        success: function (cmds) {
            const histoairwater = document.getElementById('oklyn_air_water')
            const histoph = document.getElementById('oklyn_ph')
            const histoorp = document.getElementById('oklyn_orp')
            const histosalt = document.getElementById('oklyn_salt')
            const packoklyn = _param_pack

            jeedom.history.chart = []

            for (let i in cmds) {
                if (cmds[i].logicalId === 'air') {
                    jeedom.history.drawChart({
                        cmd_id: cmds[i].id,
                        dateStart: _dateStart,
                        dateEnd: _dateEnd,
                        el: 'oklyn_air_water' + _eqLogic_id,
                        option: {
                            graphColor: '#406db0',
                            derive : 0,
                            graphZindex : 0
                        }
                    });
                    histoairwater.innerHTML = '<div class="chartContainer" id="oklyn_air_water' + _eqLogic_id + '"></div>';
                }
                if (cmds[i].logicalId === 'water') {
                    jeedom.history.drawChart({
                        cmd_id: cmds[i].id,
                        dateStart: _dateStart,
                        dateEnd: _dateEnd,
                        el: 'oklyn_air_water' + _eqLogic_id,
                        option: {
                            graphColor: '#015871',
                            derive : 0,
                            graphZindex : 0
                        }
                    });
                    histoairwater.innerHTML = '<div class="chartContainer" id="oklyn_air_water' + _eqLogic_id + '"></div>';
                }
                if (packoklyn === 'phseul' || packoklyn === 'phredox' || packoklyn === 'phredoxsalt'){
                    if (cmds[i].logicalId === 'ph') {
                        jeedom.history.drawChart({
                            cmd_id: cmds[i].id,
                            dateStart: _dateStart,
                            dateEnd: _dateEnd,
                            el: 'oklyn_ph' + _eqLogic_id,
                            option: {
                                graphColor: '#8612f3',
                                derive : 0,
                                graphZindex : 0
                            }
                        });
                        histoph.innerHTML = '<div class="chartContainer" id="oklyn_ph' + _eqLogic_id + '"></div>';
                    }

                    if (packoklyn === 'phredox' || packoklyn === 'phredoxsalt'){
                        if (cmds[i].logicalId === 'orp') {
                            jeedom.history.drawChart({
                                cmd_id: cmds[i].id,
                                dateStart: _dateStart,
                                dateEnd: _dateEnd,
                                el: 'oklyn_orp' + _eqLogic_id,
                                option: {
                                    graphColor: '#43f312',
                                    derive : 0,
                                    graphZindex : 0
                                }
                            });
                            histoorp.innerHTML = '<div class="chartContainer" id="oklyn_orp' + _eqLogic_id + '"></div>';
                        }
                        if (packoklyn === 'phredoxsalt'){
                            if (cmds[i].logicalId === 'salt') {
                                jeedom.history.drawChart({
                                    cmd_id: cmds[i].id,
                                    dateStart: _dateStart,
                                    dateEnd: _dateEnd,
                                    el: 'oklyn_salt' + _eqLogic_id,
                                    option: {
                                        graphColor: '#090704',
                                        derive : 0,
                                        graphZindex : 0
                                    }
                                });
                                histosalt.innerHTML = '<div class="chartContainer" id="oklyn_salt' + _eqLogic_id + '"></div>';
                            }
                        }
                    }

                }
            }
        }
    });
}