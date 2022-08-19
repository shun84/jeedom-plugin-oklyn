$(".in_datepicker").datepicker();

$('#bt_validChangeDate').on('click', function () {
    jeedom.history.chart = [];
    displayOklyn(object_id);
});

displayOklyn(object_id);

function displayOklyn(object_id) {
    $.ajax({
        type: 'POST',
        url: 'plugins/oklyn/core/ajax/oklyn.ajax.php',
        data: {
            action: 'getOklyn',
            object_id: object_id,
            version: 'dashboard'
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            if (data.state !== 'ok') {
                $.fn.showAlert({message: data.result, level: 'danger'});
            }
            let icon = '';
            if (isset(data.result.object.display) && isset(data.result.object.display.icon)) {
                icon = data.result.object.display.icon;
            }
            $('#oklynname').empty().append(icon + ' ' + data.result.object.name);

            for (let i in data.result.eqLogics) {
                graphesOklyn(data.result.eqLogics[i].eqLogic.id);
            }
        }
    });
}

function graphesOklyn(_eqLogic_id) {
    jeedom.eqLogic.getCmd({
        id: _eqLogic_id,
        error: function (error) {
            $.fn.showAlert({message: error.message, level: 'danger'});
        },
        success: function (cmds) {
            const histoairwater = document.getElementById('oklyn_air_water');
            const histoph = document.getElementById('oklyn_ph');
            const histoorp = document.getElementById('oklyn_orp');
            const histosalt = document.getElementById('oklyn_salt');

            for (let i in cmds) {
                if (cmds[i].logicalId === 'air') {
                    jeedom.history.drawChart({
                        cmd_id: cmds[i].id,
                        dateStart: $('#in_startDate').value(),
                        dateEnd: $('#in_endDate').value(),
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
                        dateStart: $('#in_startDate').value(),
                        dateEnd: $('#in_endDate').value(),
                        el: 'oklyn_air_water' + _eqLogic_id,
                        option: {
                            graphColor: '#015871',
                            derive : 0,
                            graphZindex : 0
                        }
                    });
                    histoairwater.innerHTML = '<div class="chartContainer" id="oklyn_air_water' + _eqLogic_id + '"></div>';
                }

                if (cmds[i].logicalId === 'ph') {
                    jeedom.history.drawChart({
                        cmd_id: cmds[i].id,
                        dateStart: $('#in_startDate').value(),
                        dateEnd: $('#in_endDate').value(),
                        el: 'oklyn_ph' + _eqLogic_id,
                        option: {
                            graphColor: '#8612f3',
                            derive : 0,
                            graphZindex : 0
                        }
                    });
                    histoph.innerHTML = '<div class="chartContainer" id="oklyn_ph' + _eqLogic_id + '"></div>';
                }
                if (cmds[i].logicalId === 'orp') {
                    jeedom.history.drawChart({
                        cmd_id: cmds[i].id,
                        dateStart: $('#in_startDate').value(),
                        dateEnd: $('#in_endDate').value(),
                        el: 'oklyn_orp' + _eqLogic_id,
                        option: {
                            graphColor: '#43f312',
                            derive : 0,
                            graphZindex : 0
                        }
                    });
                    histoorp.innerHTML = '<div class="chartContainer" id="oklyn_orp' + _eqLogic_id + '"></div>';
                }
                if (cmds[i].logicalId === 'salt') {
                    jeedom.history.drawChart({
                        cmd_id: cmds[i].id,
                        dateStart: $('#in_startDate').value(),
                        dateEnd: $('#in_endDate').value(),
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
            setTimeout(function(){
                jeedom.history.chart['oklyn_air_water' + _eqLogic_id].chart.xAxis[0].setExtremes(jeedom.history.chart['oklyn_air_water' + _eqLogic_id].chart.navigator.xAxis.min,jeedom.history.chart['oklyn_air_water' + _eqLogic_id].chart.navigator.xAxis.max);
                jeedom.history.chart['oklyn_ph' + _eqLogic_id].chart.xAxis[0].setExtremes(jeedom.history.chart['oklyn_ph' + _eqLogic_id].chart.navigator.xAxis.min,jeedom.history.chart['oklyn_ph' + _eqLogic_id].chart.navigator.xAxis.max);
                jeedom.history.chart['oklyn_orp' + _eqLogic_id].chart.xAxis[0].setExtremes(jeedom.history.chart['oklyn_orp' + _eqLogic_id].chart.navigator.xAxis.min,jeedom.history.chart['oklyn_orp' + _eqLogic_id].chart.navigator.xAxis.max);
                jeedom.history.chart['oklyn_salt' + _eqLogic_id].chart.xAxis[0].setExtremes(jeedom.history.chart['oklyn_salt' + _eqLogic_id].chart.navigator.xAxis.min,jeedom.history.chart['oklyn_salt' + _eqLogic_id].chart.navigator.xAxis.max);
            }, 1000);
        }
    });
}