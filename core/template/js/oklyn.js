if (document.getElementById('confpackoklyn').getAttribute('value') === 'phredoxsalt'){
    if (parseFloat(document.getElementById("domsalt").innerHTML) !== 0) {
        document.getElementById('phredoxsalt').style.display = 'block'
    } else {
        document.getElementById('phredoxsalt').style.display = 'none'
    }
    document.getElementById('phredox').style.display = 'block'
    document.getElementById('phseul').style.display = 'block'
} else if (document.getElementById('confpackoklyn').getAttribute('value') === 'phredox'){
    document.getElementById('phredoxsalt').style.display = 'none'
    document.getElementById('phredox').style.display = 'block'
    document.getElementById('phseul').style.display = 'block'
} else if (document.getElementById('confpackoklyn').getAttribute('value') === 'phseul'){
    document.getElementById('phredoxsalt').style.display = 'none'
    document.getElementById('phredox').style.display = 'none'
    document.getElementById('phseul').style.display = 'block'
} else if (document.getElementById('confpackoklyn').getAttribute('value') === 'aucun'){
    document.getElementById('phredoxsalt').style.display = 'none'
    document.getElementById('phredox').style.display = 'none'
    document.getElementById('phseul').style.display = 'none'
}

if (document.getElementById("chauffage_aux_second")){
    document.getElementById("icon_aux_second").innerHTML = '<i id="iconechauffage_second" class=\"fas fa-thermometer-full fa-4x\"></i>'
}

if (document.getElementById("lumiere_aux_second")){
    document.getElementById("icon_aux_second").innerHTML = '<i id="iconelumiere_second" class=\"fas fa-lightbulb fa-4x\"></i>'
}

if (document.getElementById("autre_aux_second")){
    document.getElementById("icon_aux_second").innerHTML = '<i id="iconeautre_second" class=\"fas fa-power-off fa-4x\"></i>'
}

if (parseFloat(document.getElementById("domsalt").innerHTML) >= 4.00 && parseFloat(document.getElementById("domsalt").innerHTML) <= 6.00){
    document.getElementById("outer-circle-salt").style.boxShadow = "0 0 0 10px green"
} else if ((parseFloat(document.getElementById("domsalt").innerHTML) >= 3.00 && parseFloat(document.getElementById("domsalt").innerHTML) < 4.00)
    || (parseFloat(document.getElementById("domsalt").innerHTML) > 6.00 && parseFloat(document.getElementById("domsalt").innerHTML) <= 7.00)){
    document.getElementById("outer-circle-salt").style.boxShadow = "0 0 0 10px yellow"
} else if (parseFloat(document.getElementById("domsalt").innerHTML) < 3.00 || parseFloat(document.getElementById("domsalt").innerHTML) > 7.0){
    document.getElementById("outer-circle-salt").style.boxShadow = "0 0 0 10px red"
}

document.querySelector('#icon_aux_second').addEventListener('click', function () {
    if (document.getElementById("icon_aux_second").getAttribute("data-statutsecond") === 'off'){
        jeedom.cmd.execute({id: $(this).data('cmd_on_id')})
    } else {
        jeedom.cmd.execute({id: $(this).data('cmd_off_id')})
    }
});

if (document.getElementById("icon_aux_second").getAttribute("data-statutsecond") === 'on' && document.getElementById("lumiere_aux_second")){
    document.getElementById("iconelumiere_second").style.color = "yellow"
}

if (document.getElementById("icon_aux_second").getAttribute("data-statutsecond") === 'on' && document.getElementById("chauffage_aux_second")){
    document.getElementById("iconechauffage_second").style.color = "red"
}

if (document.getElementById("icon_aux_second").getAttribute("data-statutsecond") === 'on' && document.getElementById("autre_aux_second")){
    document.getElementById("iconeautre_second").style.color = "yellow"
}
