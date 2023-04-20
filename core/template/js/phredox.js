if (document.getElementById('confpackoklyn').getAttribute('value') === 'phredox'){
    document.getElementById('phredox').style.display = 'block'
    document.getElementById('phseul').style.display = 'block'
} else if (document.getElementById('confpackoklyn').getAttribute('value') === 'phseul'){
    document.getElementById('phredox').style.display = 'none'
    document.getElementById('phseul').style.display = 'block'
} else if (document.getElementById('confpackoklyn').getAttribute('value') === 'aucun'){
    document.getElementById('phredox').style.display = 'none'
    document.getElementById('phseul').style.display = 'none'
}



