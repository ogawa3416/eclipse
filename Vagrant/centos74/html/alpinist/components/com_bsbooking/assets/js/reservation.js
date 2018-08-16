/**
By Prasit Gebsaap
*/
function verifyTimes(f) {
	if (f.del && f.del.checked) {
		return confirm("Delete this reservation?");
	}
	if (parseFloat(f.starttime.value) < parseFloat(f.endtime.value)) {
		return true;
	}
	else {
		window.alert("End time must be later than start time\nCurrent start time: " + f.starttime.value + " Current end time: " + f.endtime.value);
		return false;
	}
}

function checkAdminForm() {
	var f = document.forms[0];
	for (var i=0; i< f.elements.length; i++) {
		if ( (f.elements[i].type == "checkbox") && (f.elements[i].checked == true) )
			return confirm('This will delete all reservations and permission information for the checked items!\nContinue?');
	}
	alert("No boxes have been checked!");	
	return false;
}

function checkBoxes() {
	var f = document.train;
	for (var i=0; i< f.elements.length; i++) {
		if (f.elements[i].type == "checkbox")
			f.elements[i].checked = true;
	}
	void(0);
}

function checkAddResource(f) {
	var msg = "";
	minres = (parseInt(f.minH.value) * 60) + parseInt(f.minM.value);
	maxRes = (parseInt(f.maxH.value) * 60) + parseInt(f.maxM.value);
	
	if (f.name.value=="")
		msg+="-Resource name is required.\n";
	if (parseInt(minres) > parseInt(maxRes))
		msg+="-Minimum reservaion time must be less than or equal to maximum";
	if (msg!="") {
		alert("You have the following errors:\n\n"+msg);
		return false;
	}
	
	return true;
}

function checkAddSchedule() {
	var f = document.addSchedule;
	var msg = "";
	
	if (f.scheduletitle.value=="")
		msg+="-Schedule title is required.\n";
	if (parseInt(f.daystart.value) > parseInt(f.dayend.value))
		msg+="-Invalid start/end times.\n";
	if (f.viewdays.value == "" || parseInt(f.viewdays.value) <= 0)
		msg+="Invalid view days.\n";
	if (f.adminemail.value == "")
		msg+="Admin email is required.\n";

	if (msg!="") {
		alert("You have the following errors:\n\n"+msg);
		return false;
	}
	
	return true;
}

function checkAllBoxes(box) {
    var f = document.forms[0];
	
	for (var i = 0; i < f.elements.length; i++) {
		if (f.elements[i].type == "checkbox" && f.elements[i].name != "notify_user")
			f.elements[i].checked = box.checked;
	}

	void(0);
}



function check_for_delete(f) {
	if (f.del && f.del.checked == true)
		return confirm('Delete this reservation?');
}

function toggle_fields(box) {
	document.forms[0].elements["table," + box.value + "[]"].disabled = (box.checked == true) ? false : "disabled";
}

function search_user_lname(letter) {
	var frm = isIE() ? document.name_search : document.forms['name_search'];
	frm.firstName.value = "";
	frm.lastName.value=letter;
	frm.submit();
}

function isIE() {
	return document.all;
}

function changeDate(month, year) {
	var frm = isIE() ? document.changeMonth : document.forms['changeMonth'];
	frm.month.value = month;
	frm.year.value = year;
	frm.submit();
}
/*
 Change schedule to selected date
*/
function jumpToDate(url, Itemid){
    var date = document.getElementById('date').value;
    alert(date);
    document.location.href = url + date+'&Itemid='+Itemid;    
}


// BUGFIX by Eric Maclot
function isIE7() {
        return (document.all && (typeof document.body.style.maxHeight != "undefined"));
}
 
// Shorthand functions for schedule display
function ssum(e, text)
{
	showsummary('summary', e, text);
}
function hsum()
{
	hideSummary('summary');
}

function msum(e)
{
	moveSummary('summary', e);
}

function showsummary(object, e, text) {
 
        myLayer = document.getElementById(object);
        myLayer.innerHTML = text;
 
        w = parseInt(myLayer.style.width) ;
        h = parseInt(myLayer.style.height);
 
        if (e != '') {
            if (isIE()) {
                  x = e.clientX;
                  y = e.clientY;
                  browserX = document.body.offsetWidth - 25;
                  if (isIE7()) {
                     // IE 7
                    x += document.documentElement.scrollLeft - document.body.clientLeft ;
                    y += document.documentElement.scrollTop - document.body.clientTop;
                 } else {
                    // IE6, and previous version
                    x += document.body.scrollLeft ;                        // Adjust for scrolling on IE
                    y += document.body.scrollTop ;
                }
 
            }
            if (!isIE()) {
            x = e.pageX;
            y = e.pageY;
            browserX = window.innerWidth - 35;
            }
    }
 
        x1 = x + 20;                // Move out of mouse pointer
        y1 = y + 20;
 
        // Keep box from going off screen
        if (x1 + w > browserX){
                x1 = browserX - w;
        }
    myLayer.style.left = parseInt(x1)+ "px";
    myLayer.style.top = parseInt(y1) + "px";
    myLayer.style.visibility = "visible";
}

function getAbsolutePosition(element) {
    var r = { x: element.offsetLeft, y: element.offsetTop };
    if (element.offsetParent) {
      var tmp = getAbsolutePosition(element.offsetParent);
      r.x += tmp.x;
      r.y += tmp.y;
    }
    return r;
};

function moveSummary(object, e) {
 
        myLayer = document.getElementById(object);
        w = parseInt(myLayer.style.width);
        h = parseInt(myLayer.style.height);
 
    if (e != '') {
        if (isIE()) {
            x = e.clientX;
            y = e.clientY;
            browserX = document.body.offsetWidth -25;
             if (isIE7()) {
   // IE 7
                    x += document.documentElement.scrollLeft - document.body.clientLeft ;
                    y += document.documentElement.scrollTop - document.body.clientTop;
   } else {
   // IE6, and previous version
                    x += document.body.scrollLeft ;                        // Adjust for scrolling on IE
                    y += document.body.scrollTop ;
   }
        }
        if (!isIE()) {
            x = e.pageX;
            y = e.pageY;
                        browserX = window.innerWidth - 30;
        }
    }
 
        x1 = x + 20;        // Move out of mouse pointer
        y1 = y + 20;
 
        // Keep box from going off screen
        if (x1 + w > browserX)
                x1 = browserX - w;
 
    myLayer.style.left = parseInt(x1) + "px";
    myLayer.style.top = parseInt(y1) + "px";
}

function hideSummary(object) {
	myLayer = document.getElementById(object);
	myLayer.style.visibility = 'hidden';
}

/* Show or hide recursive date selection
    opt 0->none, 1=day, 2=week, 3=month_date, 4=month_day
*/
function showHideDays(opt,mb) {
    e = document.getElementById("until_div");
    if (opt.options[0].selected == true) {
   		e.style.visibility = "hidden";
		e.style.display = "none";
    }else{
  		e.style.visibility = "visible";
		e.style.display = isIE() ? "inline" : "table";   
    }
    
	e = document.getElementById("days");
	if (opt.options[2].selected == true || opt.options[4].selected == true) {
		e.style.visibility = "visible";
		e.style.display = isIE() ? "inline" : "table";
	}
	else {
		e.style.visibility = "hidden";
		e.style.display = "none";
	}
	
	e = document.getElementById("week_num")
	if (opt.options[4].selected == true) {
		e.style.visibility = "visible";
		e.style.display = isIE() ? "inline" : "table";
	}
	else {
		e.style.visibility = "hidden";
		e.style.display = "none";
	}
/* blogstone */
	if(opt.options[2].selected == true || opt.options[4].selected == true ){
		ck = document.getElementsByName("repeat_day[]");
		for(i=0;i<ck.length;i++){
			ck[i].checked = false;
		}
	}
	if( mb ) {
		$("input[name='repeat_day[]']").checkboxradio("refresh");
	}
}

function chooseDate(input_box, m, y) {
	var file = "recurCalendar.php?m=" + m + "&y="+ y;
	if (isIE()) {
		yVal = "top=" + 200;
		xVal = "left=" + 500;
	}
	if (!isIE()) {
		yVal = "screenY=" + 200;
		xVal = "screenX=" + 500
	}
	window.open(file, "calendar",yVal + "," + xVal + ",height=270,width=220,resizable=no,status=no,menubar=no");
	void(0);
}

function selectRecurDate(m, d, y, isPopup) {
	f = window.opener.document.forms[0];
	f._repeat_until.value = m + "/" + d + "/" + y;
	f.repeat_until.value = f._repeat_until.value;
	window.close();
}


function showHideCpanelTable(element) {
	var expires = new Date();
	var time = expires.getTime() + 2592000000;
	expires.setTime(time);
	var showHide = "";
	if (document.getElementById(element).style.display == "none") {
		document.getElementById(element).style.display='block';
		showHide = "show";
	} else {
		document.getElementById(element).style.display='none';
		showHide = "hide";
	}
	
	document.cookie = element + "=" + showHide + ";expires=" + expires.toGMTString();
}


function clickTab(tabid, panel_to_show) {
	document.getElementById(tabid.getAttribute("id")).className = "tab-selected";
	rows = document.getElementById("tab-container").getElementsByTagName("td");
	for (i = 0; i < rows.length; i++) {
		if (rows[i].className == "tab-selected" && rows[i] != tabid) {
			rows[i].className = "tab-not-selected";
		}
	}

	div_to_display = document.getElementById(panel_to_show);
	div_to_display.style.display = isIE() ? "block" : "table";
	divs = document.getElementById("main-tab-panel").getElementsByTagName("div");

	for (i = 0; i < divs.length; i++) {
		// only hide panels with prefix "pnl"
		if (divs[i] != div_to_display && divs[i].getAttribute("id").substring(0,3) == "pnl") {
			divs[i].style.display = "none";
		}
	}
}

function checkCalendarDates() {
	var table = document.getElementById("repeat_table");
	if (table == null) return;
    
	// If the start/end date are not equal, hide the whole repeat section
	if (document.getElementById("start_date").value != document.getElementById("end_date").value) {
		table.style.display = "none";
		table.style.visibility = "hidden";	
	}
	else {
		table.style.display = isIE() ? "inline" : "table";
		table.style.visibility = "visible";
	}
}

function showHideMinMax(chk) {
	document.getElementById("minH").disabled = document.getElementById("minM").disabled = document.getElementById("maxH").disabled = document.getElementById("maxM").disabled= chk.checked
}

function moveSelectItems(from, to) {
	from_select = document.getElementById(from);
	to_select = document.getElementById(to);
	
	for (i = 0; i < from_select.options.length; i++) {
		if (from_select.options[i].selected) {
			if (isIE()) {
				var option = new Option(from_select.options[i].text, from_select.options[i].value);
				to_select.options.add(option);
				from_select.options.remove(i);
				to_select.options[0].selected = true;
			}
			else {
				to_select.options.add(from_select.options[i]);
			}
			i--;	
		}
	}
}

function selectAllOptions(button) {
	var form = button.form;
	var i;
	
	for (i = 0; i < form.elements.length; i++) {
		if (form.elements[i].type == "select-multiple" && form.elements[i].multiple == true) {
			selectbox = form.elements[i];
			for (j = 0; j < selectbox.options.length; j++) {
				selectbox.options[j].selected = true;
			}
		}
	}
}

function selectUserForReservation(memberid, fname, lname, email) {
	var doc = window.opener.document
	doc.forms[0].memberid.value = memberid;
	doc.getElementById('name').innerHTML = fname + " " + lname;
	doc.getElementById('phone').innerHTML = "";
	doc.getElementById('email').innerHTML = email;
	window.close();
}

function showHide(element) {
	if (document.getElementById(element).style.display == "none") {
		document.getElementById(element).style.display='block';
	}
	else {
		document.getElementById(element).style.display='none';
	}
}

function submitJoinForm(isLoggedIn) {
	var loggedIn = (isLoggedIn != 0);
	var f = document.getElementById("join_form");
	f.h_join_fname.value = (!loggedIn) ? document.getElementById("join_fname").value : "";
	f.h_join_lname.value = (!loggedIn) ? document.getElementById("join_lname").value : "";
	f.h_join_email.value = (!loggedIn) ? document.getElementById("join_email").value : "";
	f.h_join_userid.value= (loggedIn) ? document.getElementById("join_userid").value : "";
	f.h_join_resid.value = document.getElementById("resid").value;
	f.submit();
}


function createXMLDoc() {
	var xmlDoc = null;
	if (document.implementation && document.implementation.createDocument)
	{
		xmlDoc = document.implementation.createDocument("", "", null);
	}
	else if (window.ActiveXObject)
	{
		xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
 	}
	
	return xmlDoc;
}

function getOption(opt) {
	if (isIE()) {
		return new Option(opt.text, opt.value);
	}
	else {
		return opt;
	}
}

function popGroupEdit(memberid) {
	window.open("group_edit.php?edit=1&memberid=" + memberid, "groups","height=250,width=470,resizable=no,status=no,menubar=no");
	void(0);
}

function popGroupView(memberid) {
	window.open("group_edit.php?edit=0&memberid=" + memberid, "groups","height=250,width=470,resizable=no,status=no,menubar=no");
	void(0);
}

function showHere(parent, id) {
	var element = document.getElementById(id);
	var x;
	var y;
	
	var offset = getOffset(parent);
	x = offset[0];
	y = offset[1];
	element.style.left = parseInt(x) + "px";
    element.style.top = parseInt(y - 34) + "px";
	element.style.display = "inline";
}

function getOffset(obj) {
	var curLeft = 0;
	var curTop = 0;
	
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curLeft += obj.offsetLeft
			curTop += obj.offsetTop;
			obj = obj.offsetParent;
		}
	}
	else if (obj.x) {
		curLeft += obj.x;
		curTop += obj.y;
	}
	
	return new Array(curLeft, curTop);
}

function switchStyle(obj, style) {
	obj.className = style;
}

function openExport(type, id, start, end) {
	var qs = 'type=' + type;
	
	if (id.length > 0) {
		qs += "&resid=" + id;
	}
	else {
		if (start.length > 0) {
			qs += "&start_date=" + start; 	
		}
		if (end.length >0) {
			qs += "&end_date=" + end;	
		}
	}
	
	window.open("exports/ical.php?" + qs);
}

function exportSearch() {
	var _type = document.getElementById("type");
	var type = _type[_type.selectedIndex].value;
	
	var start = document.getElementById("nostart").checked ? '' : document.getElementById("hdn_start_date").value;
	var end = document.getElementById("noend").checked ? '' : document.getElementById("hdn_end_date").value;
	
	openExport(type, '', start, end);
}

function blurDiv(checkbox, divid) {
	document.getElementById(divid).className = checkbox.checked ? "blur_textbox" : "textbox";
}

function updateEnd(startDrop)
{
	var endDrop = document.getElementById("endtime");
	var index = startDrop.selectedIndex;
	endDrop.selectedIndex = (endDrop.options.length-1 > index) ? index + 1 : index;	
}

/* blogstone*/
function selectHideWeek(c,pt,mb) {
    e = document.getElementsByName("interval");
    ck = document.getElementsByName("repeat_day[]");
    for(i=0;i<e[0].options.length;i++){
    	if( e[0].options[i].selected == true ) {
		    if( e[0].options[i].value != "month_day" ) return;
		}
	}
    if( c.checked != true ) return;
    
	for(i=0;i<ck.length;i++){
		if( pt == i ) continue;
		ck[i].checked = false;
	}
	if( mb ) {
		$("input[name='repeat_day[]']").checkboxradio("refresh");
	}
}
