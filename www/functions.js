function WiFiDown() {
        var down = confirm("Take down wlan0 ?");
        if(down) {
        } else {
                alert("Action cancelled");
        }
}

function UpdateNetworks() {
	var existing = document.getElementById("networkbox").querySelectorAll('.NetworkBoxes').length;
	document.getElementById("Networks").value = existing;
}

function AddNetwork() {
	UpdateNetworks();
	var Networks = document.getElementById('Networks').value;

	document.getElementById('networkbox').insertAdjacentHTML('beforeend', '<div id="Networkbox'+Networks+'" class="NetworkBoxes">' +
'<h3>Network '+Networks+'</h3><input type="button" value="Delete" onClick="DeleteNetwork('+Networks+')" /></span><br />' +
'<span class="tableft" id="lssid0">SSID:</span>' +
'<input type="text" id="ssid0" name="ssid'+Networks+'" onkeyup="CheckForm('+Networks+')" /><br />' +
'<span class="tableft" id="lenterprise0">Enterprise:</span>' +
'<input type="checkbox" id="enterprise0" name="enterprise'+Networks+'" value="enterprise" onChange="ToggleEnterprise('+Networks+')" /><br />' +
'<div id="didentity0" style="display:none">' +
'<span class="tableft" id="lidentity0">Username:</span>' +
'<input type="text" id="identity0" name="identity'+Networks+'" onkeyup="CheckForm('+Networks+')" /><br />' +
'</div>' +
'<div id="dpassword0" style="display:none">' +
'<span class="tableft" id="lpassword0">Password:</span>' +
'<input type="password" id="password0" name="password'+Networks+'" onkeyup="CheckForm('+Networks+')" /><br />' +
'</div>' +
'<div id="dpsk0" style="display:inline">' +
'<span class="tableft" id="lpsk0">PSK:</span>' +
'<input type="password" id="psk0" name="psk'+Networks+'" onkeyup="CheckForm('+Networks+')" />' +
'</div>' +
'</div>');

	UpdateNetworks();
	CheckForm(-1);
}

function ToggleEnterprise(network) {
	var enterprise = document.querySelector('[name="enterprise'+network+'"]');
	var ParentBox = enterprise.parentNode;
	var personalDisplay   = (enterprise.checked) ? 'none' : 'inline',
		enterpriseDisplay = (enterprise.checked) ? 'inline' : 'none';

	ParentBox.querySelector('[id="dpsk0"]').style.display = personalDisplay;

	ParentBox.querySelector('[id="didentity0"]').style.display = enterpriseDisplay;

	ParentBox.querySelector('[id="dpassword0"]').style.display = enterpriseDisplay;

	CheckForm(-1);
}

function IsEnterprise(network) {
	return document.querySelector('[name="enterprise'+network+'"]').checked;
}

function CheckSSID(network) {
	var ssid = document.querySelector('[name="ssid'+network+'"]');

	if (ssid.value.length == 0 || ssid.value.length > 31) {
		ssid.style.background = '#FFD0D0';
		return false;
	}
	else {
		ssid.style.background = '#D0FFD0';
		return true;
	}
}

function CheckPSK(network) {
	var psk = document.querySelector('[name="psk'+network+'"]');

	if (psk.value.length < 8) {
		psk.style.background = '#FFD0D0';
		return false;
	}
	else {
		psk.style.background = '#D0FFD0';
		return true;
	}
}

function CheckUsername(network) {
	var username = document.querySelector('[name="identity'+network+'"]');

	if (username.value.length == 0) {
		username.style.background = '#FFD0D0';
		return false;
	}
	else {
		username.style.background = '#D0FFD0';
		return true;
	}
}

function CheckPassword(network) {
	var password = document.querySelector('[name="password'+network+'"]');

	if (password.value.length == 0) {
		password.style.background = '#FFD0D0';
		return false;
	}
	else {
		password.style.background = '#D0FFD0';
		return true;
	}
}

function CheckForm(network) {
	var SaveElement = document.getElementById('Save');
	var Networks = document.getElementById('Networks').value;

	if (network >= 0) {
		document.querySelector('[id="Networkbox'+network+'"]').classList.remove('noupdate');
		if (document.querySelector('[name="keep'+network+'"]')) {
			document.querySelector('[name="keep'+network+'"]').remove();
		}
	}

	SaveElement.disabled = false;

	for (var network = 0; network < Networks; network++) {
		if (!CheckSSID(network)) {
			SaveElement.disabled = true;
		}

		if (!IsEnterprise(network) && !CheckPSK(network)) {
			SaveElement.disabled = true;
		}

		if (IsEnterprise(network) && !CheckUsername(network)) {
			SaveElement.disabled = true;
		}

		if (IsEnterprise(network) && !CheckPassword(network)) {
			SaveElement.disabled = true;
		}
	}

	var NetworkBoxes = Array.prototype.slice.call(document.querySelectorAll('.NetworkBoxes'));
	if (NetworkBoxes.every(function(value) { return value.classList.contains('noupdate'); })) {
		SaveElement.disabled = true;
	}

	if (document.querySelector('.NetworkBoxDeleted')) {
		SaveElement.disabled = false;
	}
}

function EditPassword(network) {
	var psk = document.querySelector('[name="psk'+network+'"]');
	var password = document.querySelector('[name="password'+network+'"]');

	var NewOnKeyUp = function() { CheckForm(network) };

	if (!IsEnterprise(network) && psk.value.length > 1) {
		psk.value = (psk.value.length > 8) ? psk.value[psk.value.length - 1] : '';
	}
	else if (IsEnterprise(network) && password.value.length > 1) {
		password.value = (password.value.length > 8) ? password.value[password.value.length - 1] : '';
	}

	if (IsEnterprise(network)) {
		password.setAttribute('onkeyup', 'CheckForm('+network+')');
	}
	else {
		psk.setAttribute('onkeyup', 'CheckForm('+network+')');
	}

	CheckForm(network);
}

function DeleteNetwork(network) {
	var element = document.getElementById('Networkbox'+network);
	element.parentNode.removeChild(element);

	/* Update network numbers */
	document.querySelectorAll('.NetworkBoxes')
		.forEach(function(value, index) {
			value.id = 'Networkbox'+index;
			value.querySelector('h3').innerHTML = 'Network '+index;
			value.querySelector('[value="Delete"]').setAttribute('onClick', 'DeleteNetwork('+index+')');

			var keep       = value.querySelector('[id="keep0"]'),
				ssid       = value.querySelector('[id="ssid0"]'),
				enterprise = value.querySelector('[id="enterprise0"]'),
				identity   = value.querySelector('[id="identity0"]'),
				password   = value.querySelector('[id="password0"]'),
				psk        = value.querySelector('[id="psk0"]');

			ssid.name = 'ssid'+index;
			ssid.setAttribute('onkeyup', 'CheckForm('+index+')');
			enterprise.name = 'enterprise'+index;
			enterprise.onchange = function() { ToggleEnterprise(index) };
			identity.name = 'identity'+index;
			identity.setAttribute('onkeyup', 'CheckForm('+index+')');
			password.name = 'password'+index;
			if (password.getAttribute('onkeyup').match('EditPassword\\(\\d+\\)'))
				password.setAttribute('onkeyup', 'EditPassword('+index+')');
			else
				password.setAttribute('onkeyup', 'CheckForm('+index+')');
			psk.name = 'psk'+index;
			if (psk.getAttribute('onkeyup').match('EditPassword\\(\\d+\\)'))
				psk.setAttribute('onkeyup', 'EditPassword('+index+')');
			else
				psk.setAttribute('onkeyup', 'CheckForm('+index+')');
		});

	UpdateNetworks();
}
