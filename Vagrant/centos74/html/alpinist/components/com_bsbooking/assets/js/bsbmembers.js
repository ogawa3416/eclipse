function memsubmitform(bsfm,mb)
{
	var form = document.adminForm;
	bsbmenberssave(mb);
	BsFormsubmit(form,mb);
	document.getElementById('divcode').disabled = true;
	document.getElementById('uselectlist').disabled = true;
	
	return false;
}
function bsevchange(mb) 
{
	var evt = document.getElementsByName('eventry');
	var ems = document.getElementsByName('emsend');
	if( evt[0].checked == false ) {
		ems[0].checked = false ;
		ems[0].disabled = true;
	} else {
		ems[0].disabled = false;
		ems[0].checked = false ;
	}
	if( mb ) {
		$('input[type=checkbox]').checkboxradio('refresh');
	}
}
function bsbmenberssave(mb)
{
	var marker = '';
	var evt = document.getElementsByName("eventry");
	var ems = document.getElementsByName("emsend");
	if( mb ) {
		var dbm =document.getElementById('members');
	} else {
		var dbm = window.parent.document.getElementById('members');
	}
	if( dbm.value.search(/#MDFY#/i) == -1 ) {
		if( evt[0].checked == true ) {
			if( ems[0].checked == true ) {
				marker = "#SEND#";
			} else {
				marker = "#NONE#";
			}
		} else {
			marker = "#MEMB#";
		}
	} else {
		marker = "#MDFY#";
	}
	var mlt = document.getElementById("memberslist");
	mlt_members = mlt.children;
    for (var i = 0; i < mlt_members.length; i++){
		marker = marker + mlt_members[i].getAttribute("value") + "," + mlt_members[i].innerHTML + "/";
    }
	if( mb ) {
		var tmp = document.getElementById('tmpmembers');
	} else {
		var tmp = window.parent.document.getElementById('tmpmembers');
	}
	tmp.value = marker;
}
function updateMembers(task)
{
	mb = false;
    if (window.parent)
    {
    	var dbm = window.parent.document.getElementById('members');
		var tmp = window.parent.document.getElementById('tmpmembers');
		var members = window.parent.document.getElementById('bookingmembersname');

		if( task == 'save' ) { 
			bsbmenberssave(mb);
//			if( dbm.value.search(/#MDFY#/i) != -1 ) {
//				if( tmp.value[0] != '#' ) {
//					 tmp.varlue = "#MDFY#" + tmp.varlue;
//				}
//			}
			dbm.value = tmp.value;
			var mem = '';
			var mlt = document.getElementById("memberslist");
//			var n = mlt.options.length;
			var mlt_members = mlt.children;
			var n = mlt_members.length;
			for (i=0;i<n;i++) {
				if( mem.length  ) mem = mem + ',' ; 
				mem = mem + mlt_members[i].innerHTML ;
			}
			
			members.innerHTML = mem;
		} else {
			tmp.value = '';
		}
		window.parent.jQuery('#modal-ulist').modal('hide');
//		location.reload(false);
    }
}
bsbmemberchange = function ( inout,obj,mb ) 
{
	var mlt = document.getElementById("memberslist");
	var slt = document.getElementById("uselectlist");
	if( inout == 'In' ) {
		uname = obj.innerHTML;
		setval = obj.getAttribute("value");
		mlt_members = mlt.children;
		for (var i = 0; i < mlt_members.length; i++){
			if(setval == mlt_members[i].getAttribute("value")){
				return;
			}
		}
	  	mlt.innerHTML = mlt.innerHTML + "<div value='" + setval + "' onclick='bsbmemberchange(\"Out\",this," + mb + ");'>" + uname + "</div>";
//
		childdiv = jQuery('#uselectlist').children();
		for(i=0;i<childdiv.length;i++) {
			if( childdiv.eq(i).attr('value')==setval ) {
				childdiv.eq(i).addClass('dismode');
			}
		}
	} else {
		obj.parentNode.removeChild(obj);
//
		setval = obj.getAttribute("value");
		childdiv = jQuery('#uselectlist').children();
		for(i=0;i<childdiv.length;i++) {
			if( childdiv.eq(i).attr('value')==setval ) {
				childdiv.eq(i).removeClass('dismode');
			}
		}
	}

	if( mb ) {
//		var n = mlt.options.length;
		var mlt_members = mlt.children;
		var n = mlt_members.length;
		var mblt = document.getElementById("mbmemlist");
		mblt.innerHTML = null;
		for (i = n-1; i >= 0; i--) {
//			if( mlt.options[i].selected != true ) {
//				mblt.innerHTML += '<span>'+mlt.options[i].text+'</span><div class="br"></div>';
//			}
			mblt.innerHTML += '<span>'+mlt_members[i].innerHTML+'</span><div class="br"></div>';
		}
	}
};
function bsbmenbersload(mb)
{
    if (!window.parent) return;
    if( mb ) {
    	var dbm = document.getElementById('members');
		var tmp = document.getElementById('tmpmembers');
    } else {
		var dbm = window.parent.document.getElementById('members');
		var tmp = window.parent.document.getElementById('tmpmembers');
	}
	var mlt = document.getElementById("memberslist");
	var evt = document.getElementsByName("eventry");
	var ems = document.getElementsByName("emsend");
	if( !mlt ) return;
	var snd = Array();
	var dstr = Array();
	if( dbm.value.search(/#MDFY#/i) != -1 ) {
		document.getElementById("bsbmailcheck").style.display = 'none';
		evt[0].checked = false;
		ems[0].checked = false;
		evt[0].disabled = true;
		ems[0].disabled = true;
	} else {
		snd = tmp.value.match(/^#[^#]*#/);
		if(	!snd || (tmp.value.search(/#SEND#/i) == -1 && tmp.value.search(/#NONE#/i) == -1) ) {
			evt[0].checked = false;
			ems[0].checked = false;
			ems[0].disabled = true;
		} else {
			if( tmp.value.search(/#SEND#/i) != -1 || tmp.value.search(/#NONE#/i) != -1 ) evt[0].checked = true;
			else evt[0].checked = false;
			if( tmp.value.search(/#SEND#/i) != -1 ) ems[0].checked = true;
			else ems[0].checked = false;
		}
	}
	if( tmp.value ) {
		snd = tmp.value.match(/^#[^#]*#/);
		if( snd && snd[0].length ) dstr = tmp.value.replace(snd[0],"");
		else dstr = tmp.value;
	} else if ( dbm.value ) {
		snd = dbm.value.match(/^#[^#]*#/);
		if( snd && snd[0].length ) dstr = dbm.value.replace(snd[0],"");
		else dstr = tmp.value;
	} else {
		dstr = '';
	}
	if( dstr.length ) {
		var imems = Array();
		imems = dstr.split("/");
		for (i = 0; i<imems.length; i++) {
			imem = imems[i].split(",");
//		    if( 1 in imem ) mlt.options[i] = new Option(imem[1],imem[0]);
		  	if( 1 in imem ) mlt.innerHTML = mlt.innerHTML + "<div value='" + imem[0] + "' onclick='bsbmemberchange(\"Out\",this," + mb +");'>" + imem[1] + "</div>";
		}
//
		userdiv = jQuery('#uselectlist').children();
		memdiv = jQuery('#memberslist').children();
		for(i=0;i<userdiv.length;i++) {
			for(j=0;j<memdiv.length;j++) {
				if( userdiv.eq(i).attr('value') == memdiv.eq(j).attr('value') ) {
					userdiv.eq(i).addClass('dismode');
				}
			}
		}
	}
	if( mb ) {
//		var n = mlt.options.length;
		var mlt_members = mlt.children;
		var n = mlt_members.length;
		var mblt = document.getElementById("mbmemlist");
		mblt.innerHTML = null;
		for (i = n-1; i >= 0; i--) {
//			if( mlt.options[i].selected != true ) {
//				mblt.innerHTML += '<span>'+mlt.options[i].text+'</span><div class="br"></div>';
//			}
			mblt.innerHTML += '<span>'+mlt_members[i].innerHTML+'</span><div class="br"></div>';

		}
		jQuery("*[name=eventry]").checkboxradio("refresh");
		jQuery("*[name=emsend]").checkboxradio("refresh");
	}
}

