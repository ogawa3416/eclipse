/**
 * Bsscheduler module component 
 *
 * @package		BlogStone 
 * @subpackage	javascript
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GROON solutions
 * @version		$Id: mod_bsscheduler.js 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/
var bsmobj;

function getbsplotmgr(dn,tp,dv,a,b,c,df,sh,eh,fc){
	if( bsmobj ) bsmobj = null;
	bsmobj = new bsplotmgr(dn,tp,dv,a,b,c,df,sh,eh,fc);
	return bsmobj;
}

function bsplotmgr(dn,tp,dv,a,b,c,df,sh,eh,fc)
{
	this.dispnum = dn;
	this.tmparts = tp;
	this.divcode = dv;
	this.year = a;
	this.month = b;
	this.modid = c;
	this.dayfrom = df;
	this.starthour = sh;
	this.endhour = eh;
	this.fbgcolor = fc.split(',');
	this.users;
	this.days;
	this.posmst;
	this.init = function()
	{
		var tbl = document.getElementById('bsschetable');
		var trl = tbl.getElementsByTagName('TR');
		var uidf = new Array(); 
		for(var i=0;i<trl.length;i++) {
			if( trl[i].className.indexOf("bsscheuname") !=- 1) {
				var ui = trl[i].className;
				var id = ui.replace('bsscheuname','');
				uidf[id] = {id:id}; 
			}
		}
		this.users = uidf;
	
		var dsf = new Array(); 
		dsf[0] = this.dayfrom;
		var dt = new Date(this.year,this.month,this.dayfrom);
		for(var i=1;i<this.dispnum;i++) {
			var dtm = dt.getTime();
			dt.setTime(eval(dtm+86400000));
			dsf[i] = dt.getDate();
		}
		this.days = dsf;
		
		var len = this.endhour - this.starthour -1;
		var spn = Math.floor(len / this.tmparts);
		var tm = len % this.tmparts;
		var pmstf= new Array(); 
		for(var i=eval(this.dispnum-1);i>=0;i--){
			pmstf[i] = eval(this.starthour*1 + spn*i + tm);
			if( tm > 0 ) tm--;
		}
		this.posmst = pmstf;
	};

	this.setposition = function(pdaytx,starttx,endtx) 
	{
		var rpos= new Array(); 
		for(var i=0;i<this.tmparts;i++){
			rpos[i] = false;
		}

		var len = this.endhour - this.starthour ;
		var st = new Date(starttx.replace(/-/g, '/'));
		var sttm = st.getTime();
		var en = new Date(endtx.replace(/-/g, '/'));
		var entm = en.getTime();
		var ckday = new Date(pdaytx.replace(/-/g, '/'));
		var ckdaytm = ckday.getTime();
		var sthour = st.getHours();
		var enhour = en.getHours();
		var endckdaytm = eval(ckdaytm + 86400000);
		if( entm == endckdaytm ) enhour = 24;
		if( ckdaytm <= sttm && endckdaytm < entm ) {
			enhour = 24;
		} else if (ckdaytm > sttm && endckdaytm > entm) {
			sthour = 0;
		} else if (ckdaytm > sttm && endckdaytm < entm) {
			sthour = 0;
			enhour = 24;
		} else {
			if( sthour == enhour ) {
				enhour = enhour +1;
			}
		} 

		for(var i=0;i<this.tmparts;i++){
			if( this.dispnum == 1 ) {
				rpos[0] = true;
				break;
			} else if (i == this.dispnum-1 ) {
				if( this.posmst[i] < enhour ) {
					rpos[i] = true;
				}
			} else {
				if(this.posmst[i] < enhour && this.posmst[i+1] > sthour ) {
					rpos[i] = true;
				}
			}
		}
		return rpos;
	};
	
	this.bssgettext =	function (data)
	{
		if( !data ) return '';
		if( data.text ) ret = data.text ;
		else			ret = data.textContent;
		return ret;
	};
	
	this.plotdata = function() 
	{
		var day = this.dayfrom;
		var tbl = document.getElementById('bsschetable');
		var tdl = tbl.getElementsByTagName('TD');
		for(var i=0;i<tdl.length;i++) {
			if( tdl[i].className.indexOf('tmparts-') != -1) {
//				tdl[i].style.backgroundColor = '#FFF';
				jQuery(tdl[i]).addClass('bg_off'); 
			}
		}
		for(var i=0;i<tdl.length;i++) {
			if( tdl[i].className.indexOf('tmparts-') == -1) {
				continue;
			}
			var id = tdl[i].parentNode.className.replace('bsscheuname','');
			if( !this.users[id] || !this.users[id].data ) continue;
			var brk = 0;
			for(var ii=0;ii<this.users[id].data.length;ii++ ) {
				var dp = this.users[id].data[ii];
				var dt = new Date(dp.plotday.replace(/-/g, '/'))
				var stday = dt.getDate();
				for( var j=0;j<dp.pos.length;j++){
					if( dp.pos[j] ) {
						var mk = 'tmparts-'+stday+'-'+j;
						var col;
						if( tdl[i].className.indexOf(mk) != -1 ) {
							if( this.fbgcolor[dp.field] ) col = this.fbgcolor[dp.field];
							else col = '#99ff66';
							tdl[i].style.backgroundColor = col;
							jQuery(tdl[i]).removeClass('bg_off'); 
							brk = 1;
							break;
						}
					}
				}
				if( brk == 1 ) break;
			}
		}
	};
};

modbsscheloadAjax	= function(modid,exmode,modlink) {
	var ajax=modbsscheduleAjax();
	ajax.open("POST", modlink , true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send('year='+bsmobj.year+'&month='+bsmobj.month+'&day='+bsmobj.dayfrom+'&divcd='+bsmobj.divcode+'&bssmode='+exmode+'&ajaxmodid='+modid);
	ajax.onreadystatechange=function()
	{
		if (ajax.readyState==4)
		{
			var response = ajax.responseText;
			var ldp = document.getElementById( 'bssscheduleview'+modid );
			var ldc = document.getElementById( 'bsloadingDiv-'+modid );
			ldp.removeChild(ldc);
			if( exmode == 'reload' ) {
				mstart = response.indexOf("<!--bsscheduler###mainview-begin###-->");
				mend = response.indexOf("<!--bsscheduler###mainview-end###-->");
				var mainview = response.substring(mstart,mend);
				var bsview = document.getElementById( 'bssscheduleview'+modid );
				if( bsview ) bsview.innerHTML=mainview;
				else return false;
			} 
			bsmobj.init();
			var stmk = "<!--bsscheduler###dataview-begin###-->";
			var edmk = "<!--bsscheduler###dataview-end###-->";
			dstart = response.indexOf(stmk);
			dend  = response.indexOf(edmk);
			var dataview = response.substring(dstart,dend);
			dataview = dataview.substring( stmk.length );
			if(window.DOMParser) {
				parser = new DOMParser();
				updata = parser.parseFromString(dataview,"text/xml");
			}
			else // Internet Explorer
			{
				var	updata=new ActiveXObject("Microsoft.XMLDOM");
				updata.async="false";
				updata.loadXML(dataview);
			} 
			for( row in bsmobj.users ) {
				if( row.data ) row.removeChild(row.data);
			}
			var tguid = updata.getElementsByTagName('uid');
			var tgschdata = updata.getElementsByTagName('schdata');
			var n = tguid.length;
			for(var i=0;i<n;i++){	
				try {
					if( !tgschdata[i].getElementsByTagName('eventid') ) continue;
					var uid = bsmobj.bssgettext(tguid[i]) ;
					var tgevent = tgschdata[i].getElementsByTagName('eventid');
					var tgstart = tgschdata[i].getElementsByTagName('start');
					var tgend = tgschdata[i].getElementsByTagName('end');
					var tgpday = tgschdata[i].getElementsByTagName('pday');
					var tgtext = tgschdata[i].getElementsByTagName('text');
					var tgfield = tgschdata[i].getElementsByTagName('field');
				} catch (e) {
					var uid = bsmobj.bssgettext(tguid[i]) ;
					var tgevent = tgschdata.context[i].getElementsByTagName('eventid');
					var tgstart = tgschdata.context[i].getElementsByTagName('start');
					var tgend = tgschdata.context[i].getElementsByTagName('end');
					var tgpday = tgschdata.context[i].getElementsByTagName('pday');
					var tgtext = tgschdata.context[i].getElementsByTagName('text');
					var tgfield = tgschdata.context[i].getElementsByTagName('field');
				}
				var m = tgevent.length;
				var k = 0;
				for(j=0;j<m;j++) {
					var starttx = bsmobj.bssgettext(tgstart[j]) ;
					var endtx = bsmobj.bssgettext(tgend[j]) ;
					var pdaytx = bsmobj.bssgettext(tgpday[j]) ;
					var mkdt = {
						event : bsmobj.bssgettext(tgevent[j]),
						text  : bsmobj.bssgettext(tgtext[j]) ,
						field : bsmobj.bssgettext(tgfield[j]) ,
						start : starttx ,
						end   : endtx ,
						plotday : pdaytx,
						pos   : bsmobj.setposition(pdaytx,starttx,endtx)
					};
					try {
						if( !bsmobj.users[uid].data ) {
							bsmobj.users[uid].data = new Array();
						}
					} catch (e) {
						bsmobj.users[uid].data = new Array();
					}
					bsmobj.users[uid].data[k++] = mkdt;
				}

			}	
			bsmobj.plotdata();
			var divc = document.getElementById('modivcode');
			divc.disabled=false;
			
			if(jQuery( '#modivcode' )[0].className.indexOf('chzn-done') != -1) {
				jQuery( '#modivcode' ).trigger("liszt:updated");
			} else {
				jQuery( '#modivcode' ).chosen();
			}
			
			var pgc = document.getElementsByName('bsmodpagebt');
			pgc[0].disabled=false;
			pgc[1].disabled=false;
		}
	}
}

modbsscheduleAjax = function ()
{
	/* THIS CREATES THE AJAX OBJECT */
	var xmlhttp=false; 
	if( window.navigator.userAgent.toLowerCase().indexOf("msie") != -1 ) {
		// ajax object for IE 
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	} else {
		// ajax object for non IE navigators
		xmlhttp=new XMLHttpRequest(); 
	}

	return xmlhttp; 
}

bsToolTip = function(ev){ 
	var mdata = bsmobj;
	if( !mdata ) return;
	bsToolTiphidden();
	var tbl = document.getElementById('bsschetable'); 
	if( ev.target ) {
		if(ev.target.tagName == "TD"){
			tget = ev.target;
		} else {
			return;
		}
	} else {
		if(ev.srcElement.nodeName == "TD"){
			tget = ev.srcElement;
		} else {
			return;
		}
	}
	if( tget.className.indexOf('tmparts-') == -1) {
		bsToolTiphidden();
		return;
	}
	var pt = tget.className.split('-');
	var eid = tget.parentNode.className.replace('bsscheuname','');
	var uname = tget.parentNode.getElementsByTagName('TD');
	var users = mdata.users;
	try {
		if( users[eid].data.length ) {}
	} catch(e) {
		return;
	}
	var evarr = new Array();
	var cnt = 0;
	var pld = 0;
	for(i=0;i<users[eid].data.length;i++) {
		var dt = users[eid].data[i];
		pdt = new Date(dt.plotday.replace(/-/g, '/'));
		if( pdt.getDate() == pt[1] ){
			evarr[cnt] = dt.start.substr(11,5) + "-" + dt.end.substr(11,5) + " : "+dt.text;
			cnt++;
			pld = dt.plotday;
		}
	}
	if( cnt > 0 ) {
		evarr.sort();
		evtext = evarr.join('<br />');
		evtext = pld + "<br />" +evtext;
		uname[0].style.fontWeight = "bold";
		document.getElementById('bstooltip').innerHTML = evtext; 
		document.getElementById('bstooltipblk').style.display = 'block';

		if( document.documentElement.scrollTop ) 
			var scl = document.documentElement.scrollTop;
		else 
			scl = document.body.scrollTop;
		yh1 = document.getElementById('bsschehead').offsetHeight;
		yth = document.getElementById('userlist').offsetHeight;
		ytip = document.getElementById('bstooltipblk').offsetHeight;
		
		var py = 0;
		if( !ev.srcElement ) 
			var elp = ev.target;
		else 
			elp = ev.srcElement;
		var AA = elp;
		while(elp){ 
			py += elp.offsetTop;
			elp = elp.offsetParent;
		}
		var elm = document.getElementById('bsschehead');
		var mh = 0;
		while(elm){ 
			mh += elm.offsetTop;
			elm = elm.offsetParent;
		}
		var oy = 0;
		var mat1 = ev.clientY-mh+ytip+scl;
		var mat2 = yh1+yth;
		var mat3 = mat1 -mat2;
		if( mat1  > mat2  ) {
			oy = ev.clientY+scl - mh - mat3 +5;
		} else {
			oy = ev.clientY+scl - mh;
		}
		if( oy < 0 ) oy = 0;

		document.getElementById('bstooltipblk').style.top = oy + "px"; 
	}
}
bsToolTiphidden = function(){ 
	var tbl = document.getElementById('bsschetable'); 
	var tr = tbl.getElementsByTagName('TR');
	for( i=2;i<tr.length;i<i++){
		var un = tr[i].getElementsByTagName('TD');
		un[0].style.fontWeight = "";
	}
	document.getElementById('bstooltip').innerHTML = ""; 
	document.getElementById('bstooltipblk').style.display = 'none';
}
