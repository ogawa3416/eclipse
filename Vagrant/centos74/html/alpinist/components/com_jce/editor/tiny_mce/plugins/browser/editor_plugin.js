/* JCE Editor - 2.5.19 | 24 June 2016 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2016 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
(function(){tinymce.create('tinymce.plugins.Browser',{init:function(ed,url){this.ed=ed;},
browse:function(name,url,type,win)
{
	var ed=this.ed;
/***** Modified BlogStone 20110901 ******/
	var addpara = "";
	if( document.getElementsByName("pjid") && document.getElementsByName("pjid")[0] ) {
		jspjid = document.getElementsByName("pjid")[0].value;
		addpara = '&jspjid='+jspjid;
	}
	if( document.getElementsByName("catid") && document.getElementsByName("catid")[0] ) {
		jscatidarr = document.getElementsByName("catid")[0].value;
		jscatid = jscatidarr.split(":");
		addpara += '&jscatid='+jscatid[0];
	} 
/***** Modified BlogStone 20110901 ******
	befor:	file:ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=browser&type='+type,
	after:	file:ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=browser&type='+type + addpara,
 ***** Modified BlogStone 20110901 ******/
	ed.windowManager.open(
	{
		file:ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=browser&type='+type + addpara,
		width:780+ed.getLang('browser.delta_width',0),height:480+ed.getLang('browser.delta_height',0),resizable:"yes",inline:"yes",close_previous:"no",popup_css:false
	},
	{
		window:win,input:name,url:url,type:type});return false;},getInfo:function(){return{longname:'Browser',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net/index.php?option=com_content&amp;view=article&amp;task=findkey&amp;tmpl=component&amp;lang=en&amp;keyref=browser.about',version:'2.5.19'};}});tinymce.PluginManager.add('browser',tinymce.plugins.Browser);})();