/**
* Javascript object for Recaptcha plugin
*
* @version		$Id: recaptcha.js 319 2009-09-08 15:06:51Z dr_drsh $
* @package		Joomla
* @subpackage	JForms
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
* Slightly modified version from the original file written by my mentor "Jui-Yu Tsai" 
*/
var recaptcha = new Class({
	
	Extends : CElement,
	
	initialize: function(  parent, id, beforeObject, options ) {
	
		this.type	    = "recaptcha";
		this.parent( $(parent), id, $(beforeObject), options );

		this.htmlInput 	     = new Element('img', {
			'src' : '../media/com_jforms/plugins/elements/recaptcha/recaptcha-img.png',
			'styles':{
				'margin':'0px',
				'padding':'0px'	
			}
		});
		
		if( this.lang != '__' ){
			$('JFormsEPlugin_recaptchacustom_lang').disabled = true;	
		} else {
			$('JFormsEPlugin_recaptchacustom_lang').disabled = false;
		}
		
		this.htmlInput.inject( this.htmlContainer );
		
	},
	
	deselect: function() {
	
		this.htmlContainer.removeClass('selected'); 
		this.htmlDragHandle.set({'styles':{ 'visibility' : 'hidden' }});
		this.htmlDeleteButton.set({'styles':{ 'visibility' : 'hidden' }});
	
	},
	
	select  : function() {
		
		this.htmlContainer.addClass('selected');
		this.htmlDragHandle.set({'styles':{ 'visibility' : 'visible' }});
		this.htmlDeleteButton.set({'styles':{ 'visibility' : 'visible' }});

	},
	
	vaildate : function(){
	
		var errors = new Array();
	
		if( this.options.publickey.length < 40){
			if( this.options.publickey.length == 0)
				errors.push( "<?php echo JText::_('YOU_MUST_SPECIFY_A_PUBLIC_KEY'); ?>" );
			else
				errors.push( "<?php echo JText::_('INVALID_PUBLIC_KEY'); ?>" );
		}

		
		if( this.options.privatekey.length < 40){
			if( this.options.privatekey.length == 0)
				errors.push( "<?php echo JText::_('You must specify a private key'); ?>" );
			else
				errors.push( "<?php echo JText::_('INVALID_PRIVATE_KEY'); ?>" );
		}
		
		var stringCount = this.options.custom_lang.trim().split("\n").length;
		if( this.options.lang == '__' && stringCount != 9 )
			errors.push( "<?php echo JText::_('INVALID_INCOMPLETE_TRANSLATION_YOU_MUST_SPECIFY_9_STRINGS'); ?>" );
		
		return errors;
	},
	
		
    onUpdate : function(){
		if( this.options.lang != '__' ){
			$('JFormsEPlugin_recaptchacustom_lang').disabled = true;	
		} else {
			$('JFormsEPlugin_recaptchacustom_lang').disabled = false;
		}
	},
	serialize: function(){return this.genericSerialize();}
});