var modid;
var key;

window.addEvent('domready', function(){
var links = document.getElementsByTagName('a');

for(key in links){

	if(typeof(links[key]) == "object"){
		if(links[key].id.substring(0,10)=='prevMonth-'){
			modid= links[key].id.substring(10);
			links[key].onclick = function(){month--; newDate(month,year,modid);}
			links[key].href='javascript:void(0)';
			}


		if(links[key].id.substring(0,10)=='nextMonth-'){
			links[key].onclick = function(){month++; newDate(month,year,modid);}
			links[key].href='javascript:void(0)';
			}
		if(links[key].id.substring(0,9)=='prevYear-'){
			modid= links[key].id.substring(9);
			links[key].onclick = function(){year--; newDate(month,year,modid);}
			links[key].href='javascript:void(0)';
			}


		if(links[key].id.substring(0,9)=='nextYear-'){
			links[key].onclick = function(){year++; newDate(month,year,modid);}
			links[key].href='javascript:void(0)';
			}

		}
	}
})

function newAjax()
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

function newDate(month,year,modid)
{

                
		loadHtml  = "<p id='loadingDiv-"+modid+"' style='margin-left: 1cm; margin-top: -2cm; margin-bottom: 2cm;'>";
		loadHtml += "<img src='"+calendar_baseurl+"modules/mod_blog_calendar/img/loading.gif'>";
		loadHtml += "Loading...</p>";
		document.getElementById( 'calendar-'+modid ).innerHTML +=  loadHtml ;
		var myFx = new Fx.Morph($('tableCalendar-'+modid),{duration:200,transition: Fx.Transitions.Sine.easeOut}).start({'opacity':[1,0.2]});
		if(month<=0){
		month+=12;
		year--;
		}
		if(month>12){
		month-=12;
		year++;
		}
		
		var ajax=newAjax();
		ajax.open("POST", location.href, true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send('year='+year+'&month='+month+'&ajaxCalMod=1'+'&ajaxmodid='+modid);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4)
			{
			
				var response = ajax.responseText;
				var start = response.indexOf('<!--calendar-'+modid+' start-->');
				var finish = response.indexOf('<!--calendar-'+modid+' end-->');
				
				justTheCalendar= response.substring(start,finish);
				
				document.getElementById( 'calendar-'+modid ).innerHTML=justTheCalendar;
				
				linkPrev= document.getElementById('prevMonth-'+modid);
				linkNext= document.getElementById('nextMonth-'+modid);
				
				linkPrevYear= document.getElementById('prevYear-'+modid);
				linkNextYear= document.getElementById('nextYear-'+modid);
				
				linkPrev.onclick= function(){month--; newDate(month,year,modid);}
				linkNext.onclick= function(){month++; newDate(month,year,modid);}
				
				linkPrevYear.onclick= function(){year--; newDate(month,year,modid);}
				linkNextYear.onclick= function(){year++; newDate(month,year,modid);}
				
				linkNext.href= linkPrev.href= linkNextYear.href= linkPrevYear.href= 'javascript:void(0)';
			}
		}
	}