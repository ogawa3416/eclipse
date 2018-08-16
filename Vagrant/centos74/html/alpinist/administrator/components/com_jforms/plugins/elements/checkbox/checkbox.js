/**
* Javascript object for checkbox plugin
*
* @version		$Id: checkbox.js 374 2010-03-28 23:32:05Z dr_drsh $
* @package		Joomla
* @subpackage	JForms
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/
var checkbox = new Class({
	
	Extends: CElement,

	initialize: function( parent, id, beforeObject, options ) {
	
		this.type	    = "checkbox";

		this.parent( $(parent), id, $(beforeObject), options);
	
		this.htmlControlResize = new Element('img',{

			'id': 'resizeHandle_control_' + this.type + this.index,
			'class': 'resize-handle hideondrag'
		});
		
		var e = null;
		if(this.options.elements.length == 0){
			e = new Array();
		} else {
			e = this.options.elements.split('\n');
		}
	
		this.htmlOptionContainer = new Element('fieldset', {
			
			'class': 'radio-container',
			'styles': {
				'width' : this.options.cw + 'px',
				'height' : this.options.ch + 'px'
			}
	
		});
		this.htmlOptionLegend    = new Element('legend');
		this.htmlOptionLegend.set('html', this.options.label );
		
		//Red star that denotes a required field
		this.htmlRequiredStar = new Element('span', {
			'html': ' * ',
			'styles': {
				'color' : 'red'
			}
		});
	
		if( this.options.required ){
			this.htmlRequiredStar.inject( this.htmlOptionLegend );
		}	
		
		this.htmlOptionLegend.inject( this.htmlOptionContainer );	
	
		this.defaultValueArray = this.options.defaultValue.split("\n");

		this._constructList();
		
		this.htmlOptionContainer.inject( this.htmlContainer );
		this.htmlControlResize.inject( this.htmlContainer );
	
		this.htmlOptionContainer.makeResizable({
			handle:this.htmlControlResize,
			onDrag:dispatch_onResizeDrag,
			onComplete:dispatch_onResizeEnd,
			limit:{x:[50,400],y:[50,1000]}
		});

		var br = new Element('br',{'clear':'all'});
		br.inject( this.htmlOptionContainer );
		br.inject( this.htmlContainer );

		this._alignControlResizeHandle();
		
		
	},
	
	vaildate : function(){
		var errors = new Array();
		var e = this.options.elements.trim();
		if( e.length == 0 ){
			errors.push( "<?php echo JText::_('YOU_MUST_HAVE_AT_LEAST_ONE_ELEMENT'); ?>" );
		}
		return errors;
	},
	
	_constructList : function(){
		
		//Destroy old elements
		var children = this.htmlOptionContainer.getChildren();
	
		for(i=0;i<children.length;i++){
			if(children[i].get('class') == 'radio' || children[i].get('tag') == 'br' )
				children[i].dispose();
		}
	
		//Create brand new ones
		var e = this.options.elements.split("\n");
		var validElements = new Array();
		for(i=0;i<e.length;i++){
			
			if( e[i].trim().length == 0)continue;
			validElements.push( e[i] );
			
			//Checks the default elements
			var checked = false;
			for(j=0;j<this.defaultValueArray.length;j++){
				if( this.defaultValueArray[j] == e[i] ){
					checked = true;
					break;
				}
			}

			var lbl = new Element('label', {
				'class': 'radio',
				'html' : e[i],
				'for' : 'input_' + this.type + this.index + '_' + i
			});
			
			var input       = new Element('input',{
				'type': 'checkbox',
				'name': 'input_' + this.type + this.index,
				'id':'input_' + this.type + this.index + '_' + i,
				'checked':checked,
				'class':'radio'
			});
			
			//Alignment
			if( this.options.align == 'Left' ){
				input.inject( this.htmlOptionContainer  );
				lbl.inject( this.htmlOptionContainer );	
			} else {
				lbl.inject( this.htmlOptionContainer );
				input.inject( this.htmlOptionContainer  );
			}
			
			//Layout
			if( this.options.layout == 'List' ){
				var br = new Element('br',{'clear':'all'});
				br.inject( this.htmlOptionContainer );
/*****
				br = new Element('br');
				br.inject( this.htmlOptionContainer );
*******/
			}
	
		}
		this.options.elements = validElements.join("\n");
	},
	
	onUpdate : function(){

		this._updateDefault();
  
		if(this.options.label.trim().length == 0)this.options.label = 'Checkbox Group ' + this.index;
		this.htmlOptionLegend.set('html', this.options.label );
		
		//Red star that denotes a required field
		//Fix for IE!
		this.htmlRequiredStar.dispose();
		this.htmlRequiredStar = new Element('span', {
			'html': ' * ',
			'styles': {
				'color' : 'red'
			}
		});
		if( this.options.required ){
			this.htmlRequiredStar.inject( this.htmlOptionLegend );
		}

		this._constructList();
		
		this._alignControlResizeHandle();

	},
	
	onDragEnd:  function() {
		this._alignControlResizeHandle();
	},

	
  	onResizeDrag: function(newSize,type) {
	
		this.htmlOptionContainer.set('styles', { 'border': '1px solid red' } );	
		this._alignControlResizeHandle();
		
	},


	onResizeEnd: function( newSize, type ){

		this.options.cw = newSize.x;
		this.options.ch = newSize.y;	
		this.htmlOptionContainer.set('styles', { 'border' : '1px solid'});
		this._alignControlResizeHandle();
	
	},
	
	_alignControlResizeHandle : function(){

		this.htmlControlResize.position({
			  relativeTo: this.htmlOptionContainer,
			  position  : 'bottomRight' ,
			  edge  	: 'bottomRight'
		});
	},
	
	deselect: function() {
	
		this.htmlContainer.removeClass('selected'); 
		this.htmlControlResize.set({'styles':{ 'visibility' : 'hidden' }})
		this.htmlDragHandle.set({'styles':{ 'visibility' : 'hidden' }})
		this.htmlDeleteButton.set({'styles':{ 'visibility' : 'hidden' }})
	
	},
	
	select  : function() {
		
		this.htmlContainer.addClass('selected');
		this.htmlControlResize.set({'styles':{ 'visibility' : 'visible' }})
		this.htmlDragHandle.set({'styles':{ 'visibility' : 'visible' }})
		this.htmlDeleteButton.set({'styles':{ 'visibility' : 'visible' }})
		this._alignControlResizeHandle();
	
	},
	
	_updateDefault: function(){
  	
		var e = this.options.elements.split("\n");
		this.defaultValueArray = new Array();
	
		for(i=0;i<e.length;i++){
	  
			currentId = "input_" + this.type + this.index + "_" + i;
			if($(currentId) && $(currentId).checked){
				this.defaultValueArray.push(e[i]);
			}
		}	
	},
  
	serialize: function(){
	
		this._updateDefault();
		this.options.defaultValue	= this.defaultValueArray.join("\n");
		return this.genericSerialize();

	}
});