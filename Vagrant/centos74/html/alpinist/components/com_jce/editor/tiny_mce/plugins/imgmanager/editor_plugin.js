/* JCE Editor - 2.5.19 | 24 June 2016 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2016 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
(function(){tinymce.create('tinymce.plugins.ImageManager',{init:function(ed,url){this.editor=ed;function isMceItem(n){return/mceItem/.test(n.className);}
ed.addCommand('mceImageManager',function()
{
	var n=ed.selection.getNode();if(n.nodeName=='IMG'&&isMceItem(n)){return;}
/***** Modified BsAlpinist 20110901 *** START ***/
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
	befor:	file:ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=imgmanager',
	after:	file:ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=imgmanager'+addpara,
/***** Modified BlogStone 20100721 *** END ***/
	ed.windowManager.open(
	{
		file:ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=imgmanager'+addpara,
		width:780+ed.getLang('imgmanager.delta_width',0),height:640+ed.getLang('imgmanager.delta_height',0),inline:1,popup_css:false,size:'large-landscape'
	},{plugin_url:url});});ed.addButton('imgmanager',{title:'imgmanager.desc',cmd:'mceImageManager'});
	ed.onNodeChange.add(function(ed,cm,n){cm.setActive('imgmanager',n.nodeName=='IMG'&&!isMceItem(n));});
	ed.onInit.add(function(){if(ed&&ed.plugins.contextmenu){ed.plugins.contextmenu.onContextMenu.add(function(th,m,e){m.add({title:'imgmanager.desc',icon:'imgmanager',cmd:'mceImageManager'});});}});},insertUploadedFile:function(o){var ed=this.editor,data=this.getUploadConfig();if(data&&data.filetypes){if(new RegExp('\.('+data.filetypes.join('|')+')$','i').test(o.file)){var args={'src':o.file,'alt':o.alt||o.name,'style':{}};var attribs=['alt','title','id','dir','class','usemap','style','longdesc'];if(o.styles){var s=ed.dom.parseStyle(ed.dom.serializeStyle(o.styles));tinymce.extend(args.style,s);delete o.styles;}
if(o.style){var s=ed.dom.parseStyle(o.style);tinymce.extend(args.style,s);delete o.style;}
tinymce.each(attribs,function(k){if(typeof o[k]!=='undefined'){args[k]=o[k];}});return ed.dom.create('img',args);}}
return false;},getUploadURL:function(file){var ed=this.editor,data=this.getUploadConfig();if(data&&data.filetypes){if(new RegExp('\.('+data.filetypes.join('|')+')$','i').test(file.name)){return ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=imgmanager';}}
return false;},getUploadConfig:function(){var ed=this.editor,data=ed.getParam('imgmanager_upload');return data;},getInfo:function(){return{longname:'Image Manager',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net/index2.php?option=com_content&amp;task=findkey&amp;pop=1&amp;lang=en&amp;keyref=imgmanager.about',version:'2.5.19'};}});tinymce.PluginManager.add('imgmanager',tinymce.plugins.ImageManager);})();