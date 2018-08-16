function checkReservation(checkurl, formid) {
	var f = document.getElementById(formid);
	
	var keyValue = "";
	keyValue = buildKeyValueString(f, keyValue);
    /*
    mootools 1.2 AJAX class
    
    var request = new Request({
            url: checkurl,
            method: 'post',
            onRequest: function(){
                var div = document.getElementById("checkDiv");
                var txt = 'Validating reservation....';
       	        div.style.textAlign = "center";
                div.style.display = "inline";
                div.innerHTML = "<h4>" + txt + "..." + "</h4>";     
            },
            onSuccess: function (text, XML){
                var div = document.getElementById("checkDiv");
                div.innerHTML = text;            
            }
            
        });
    request.send(keyValue);
	*/
    
    var request = new Ajax(checkurl+'&'+keyValue, {
                method: 'get',
                onRequest: function(){
                    var div = document.getElementById("checkDiv");
                    var txt = 'Validating reservation....';
       	            div.style.textAlign = "center";
                    div.style.display = "inline";
                    div.innerHTML = "<h4>" + txt + "..." + "</h4>";     
                },
                onSuccess: function (text, XML){
                    var div = document.getElementById("checkDiv");
                    div.innerHTML = text;            
                }
            }).request();
}

function buildKeyValueString(f, keyValue) {
	for (var i = 0; i < f.elements.length; i++) {
		if (f.elements[i].name == "") { continue; }
        if (f.elements[i].name == "task") { continue; }
		if (f.elements[i].type=="select-multiple") {
			for (var o = 0; o < f.elements[i].options.length; o++) {
				keyValue += f.elements[i].name + "=" + f.elements[i].options[o].value + "&";
			}
		}
		else if (f.elements[i].type=="checkbox" && f.elements[i].name.indexOf("[]",0) >= 0){
			if (f.elements[i].checked) {
				keyValue += f.elements[i].name + "=" + f.elements[i].value + "&";
			}
		}
		else {
			keyValue += f.elements[i].name + "=" + f.elements[i].value + "&";
		}
	}
	
	return keyValue;
}

function showCheckResults() {
	if (http_request.readyState == 4) {
		var txt = "";
		var div = document.getElementById("checkDiv");
	
		if (http_request.status == 200) {
			div.style.textAlign = "left";
			txt = http_request.responseText;
		}
		else {
			txt = "Error checking reservations";
		}
		
		div.innerHTML = txt;		
	}
}