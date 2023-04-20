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

if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div id="filtration">
    <label for="selectpool"></label>
    <select id="selectpool" class="form-select">
        <option id="poolauto" value="auto">Auto</option>
        <option id="poolon" value="on">On</option>
        <option id="pooloff" value="off">Off</option>
    </select>
</div>
<script>
    if (document.getElementById("popuppool").getAttribute("data-pompe") === "auto"){
        document.getElementById("poolauto").selected = "true"
    } else if(document.getElementById("popuppool").getAttribute("data-pompe") === "on"){
        document.getElementById("poolon").selected = "true"
    } else if(document.getElementById("popuppool").getAttribute("data-pompe") === "off"){
        document.getElementById("pooloff").selected = "true"
    }
</script>
