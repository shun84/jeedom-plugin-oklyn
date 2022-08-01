if (document.getElementById('confpackoklyn').getAttribute('value') === 'phredoxsalt'){
    if (parseFloat(document.getElementById("domsalt").innerHTML) !== 0) {
        document.getElementById('phredoxsalt').style.display = 'block';
    }else {
        document.getElementById('phredoxsalt').style.display = 'none';
    }
    document.getElementById('phredox').style.display = 'block';
    document.getElementById('phseul').style.display = 'block';
}else if (document.getElementById('confpackoklyn').getAttribute('value') === 'phredox'){
    document.getElementById('phredoxsalt').style.display = 'none';
    document.getElementById('phredox').style.display = 'block';
    document.getElementById('phseul').style.display = 'block';
}else if (document.getElementById('confpackoklyn').getAttribute('value') === 'phseul'){
    document.getElementById('phredoxsalt').style.display = 'none';
    document.getElementById('phredox').style.display = 'none';
    document.getElementById('phseul').style.display = 'block';
}else if (document.getElementById('confpackoklyn').getAttribute('value') === 'aucun'){
    document.getElementById('phredoxsalt').style.display = 'none';
    document.getElementById('phredox').style.display = 'none';
    document.getElementById('phseul').style.display = 'none';
}

if (document.getElementById("chauffage_aux")){
    document.getElementById("icon_aux").innerHTML = '<i id="iconechauffage" class=\"fas fa-thermometer-full fa-4x\"></i>'
}

if (document.getElementById("chauffage_aux_second")){
    document.getElementById("icon_aux_second").innerHTML = '<i id="iconechauffage_second" class=\"fas fa-thermometer-full fa-4x\"></i>'
}

if (document.getElementById("lumiere_aux")){
    document.getElementById("icon_aux").innerHTML = '<i id="iconelumiere" class=\"fas fa-lightbulb fa-4x\"></i>'
}

if (document.getElementById("lumiere_aux_second")){
    document.getElementById("icon_aux_second").innerHTML = '<i id="iconelumiere_second" class=\"fas fa-lightbulb fa-4x\"></i>'
}

if (document.getElementById("autre_aux")){
    document.getElementById("icon_aux").innerHTML = '<i id="iconeautre" class=\"fas fa-power-off fa-4x\"></i>'
}

if (document.getElementById("autre_aux_second")){
    document.getElementById("icon_aux_second").innerHTML = '<i id="iconeautre_second" class=\"fas fa-power-off fa-4x\"></i>'
}

if (parseFloat(document.getElementById("domph").innerHTML) >= 7.00 && parseFloat(document.getElementById("domph").innerHTML) <= 7.80){
    document.getElementById("outer-circle-ph").style.boxShadow = "0 0 0 10px green";
}else if ((parseFloat(document.getElementById("domph").innerHTML) >= 6.80 && parseFloat(document.getElementById("domph").innerHTML) < 7.00)
    || (parseFloat(document.getElementById("domph").innerHTML) > 7.80 && parseFloat(document.getElementById("domph").innerHTML) <= 8.00)){
    document.getElementById("outer-circle-ph").style.boxShadow = "0 0 0 10px yellow";
}else if (parseFloat(document.getElementById("domph").innerHTML) < 6.80 || parseFloat(document.getElementById("domph").innerHTML) > 8.0){
    document.getElementById("outer-circle-ph").style.boxShadow = "0 0 0 10px red";
}

if (parseInt(document.getElementById("domorp").innerHTML) >= 650 && parseInt(document.getElementById("domorp").innerHTML) <= 750){
    document.getElementById("outer-circle-orp").style.boxShadow = "0 0 0 10px green";
}else if ((parseInt(document.getElementById("domorp").innerHTML) >= 500 && parseInt(document.getElementById("domorp").innerHTML) < 650)
    || (parseInt(document.getElementById("domorp").innerHTML) > 750 && parseInt(document.getElementById("domorp").innerHTML) <= 800)){
    document.getElementById("outer-circle-orp").style.boxShadow = "0 0 0 10px yellow";
}else if (parseInt(document.getElementById("domorp").innerHTML) < 500 || parseInt(document.getElementById("domorp").innerHTML) > 800){
    document.getElementById("outer-circle-orp").style.boxShadow = "0 0 0 10px red";
}

if (parseFloat(document.getElementById("domsalt").innerHTML) >= 4.00 && parseFloat(document.getElementById("domsalt").innerHTML) <= 6.00){
    document.getElementById("outer-circle-salt").style.boxShadow = "0 0 0 10px green";
}else if ((parseFloat(document.getElementById("domsalt").innerHTML) >= 3.00 && parseFloat(document.getElementById("domsalt").innerHTML) < 4.00)
    || (parseFloat(document.getElementById("domsalt").innerHTML) > 6.00 && parseFloat(document.getElementById("domsalt").innerHTML) <= 7.00)){
    document.getElementById("outer-circle-salt").style.boxShadow = "0 0 0 10px yellow";
}else if (parseFloat(document.getElementById("domsalt").innerHTML) < 3.00 || parseFloat(document.getElementById("domsalt").innerHTML) > 7.0){
    document.getElementById("outer-circle-salt").style.boxShadow = "0 0 0 10px red";
}

if (document.getElementById("popuppool").getAttribute("data-pompe") === "auto"){
    document.getElementById("icon_pool").style.color = "yellow";
} else if(document.getElementById("popuppool").getAttribute("data-pompe") === "on"){
    const divfitration = document.createElement("div");

    divfitration.id = "modefiltration";
    divfitration.innerHTML = "Mode manuel on";

    document.getElementById("icon_pool").style.color = "green";
    document.getElementById("icon_filtation").appendChild(divfitration);

} else if(document.getElementById("popuppool").getAttribute("data-pompe") === "off"){
    const divfitration = document.createElement("div");

    divfitration.id = "modefiltration";
    divfitration.innerHTML = "Mode manuel off";

    document.getElementById("icon_pool").style.color = "red";
    document.getElementById("icon_filtation").appendChild(divfitration);
}

document.querySelector('#icon_aux').addEventListener('click', function () {
    if (document.getElementById("icon_aux").getAttribute("data-statut") === 'off'){
        jeedom.cmd.execute({id: $(this).data('cmd_on_id')});
    } else {
        jeedom.cmd.execute({id: $(this).data('cmd_off_id')});
    }
});

document.querySelector('#icon_aux_second').addEventListener('click', function () {
    if (document.getElementById("icon_aux_second").getAttribute("data-statutsecond") === 'off'){
        jeedom.cmd.execute({id: $(this).data('cmd_on_id')});
    } else {
        jeedom.cmd.execute({id: $(this).data('cmd_off_id')});
    }
});

if (document.getElementById("icon_aux").getAttribute("data-statut") === 'on' && document.getElementById("lumiere_aux")){
    document.getElementById("iconelumiere").style.color = "yellow";
}
if (document.getElementById("icon_aux_second").getAttribute("data-statutsecond") === 'on' && document.getElementById("lumiere_aux_second")){
    document.getElementById("iconelumiere_second").style.color = "yellow";
}
if (document.getElementById("icon_aux").getAttribute("data-statut") === 'on' && document.getElementById("chauffage_aux")){
    document.getElementById("iconechauffage").style.color = "red";
}
if (document.getElementById("icon_aux_second").getAttribute("data-statutsecond") === 'on' && document.getElementById("chauffage_aux_second")){
    document.getElementById("iconechauffage_second").style.color = "red";
}
if (document.getElementById("icon_aux").getAttribute("data-statut") === 'on' && document.getElementById("autre_aux")){
    document.getElementById("iconeautre").style.color = "yellow";
}
if (document.getElementById("icon_aux_second").getAttribute("data-statutsecond") === 'on' && document.getElementById("autre_aux_second")){
    document.getElementById("iconeautre_second").style.color = "yellow";
}

function popUpPool(){
    const iconpool = document.getElementById("icon_pool");
    const divfitration = document.getElementById("modefiltration");
    const select = document.createElement("select");
    const option = document.createElement("option");
    const option1 = document.createElement("option");
    const option2 = document.createElement("option");
    const label = document.createElement("label");

    iconpool.parentNode.removeChild(iconpool);

    option.id = "poolauto";
    option.value = "auto";
    option.text = "Auto";

    option1.id = "poolon";
    option1.value = "on";
    option1.text = "On";

    option2.id = "pooloff";
    option2.value = "off";
    option2.text = "Off";

    select.id = "selectpool";
    select.add(option);
    select.add(option1);
    select.add(option2);

    label.innerHTML = "Filtration";

    document.getElementById("popuppool").appendChild(label).appendChild(select);

    if (document.getElementById("popuppool").getAttribute("data-pompe") === "auto"){
        document.getElementById("poolauto").selected = "true";
    } else if(document.getElementById("popuppool").getAttribute("data-pompe") === "on"){
        document.getElementById("poolon").selected = "true";
        divfitration.style.display = "none";
    } else if(document.getElementById("popuppool").getAttribute("data-pompe") === "off"){
        document.getElementById("pooloff").selected = "true";
        divfitration.style.display = "none";
    }

    document.getElementById('selectpool').addEventListener('change', function() {
        if (this.value === 'auto'){
            jeedom.cmd.execute({id:document.querySelector('#popuppool').dataset.cmd_auto_id});
        }
        if (this.value === 'on'){
            jeedom.cmd.execute({id:document.querySelector('#popuppool').dataset.cmd_on_id});
        }
        if (this.value === 'off'){
            jeedom.cmd.execute({id:document.querySelector('#popuppool').dataset.cmd_off_id});
        }
    });
}
