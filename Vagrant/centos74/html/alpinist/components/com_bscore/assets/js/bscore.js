BsmobilePagenav = function(pg,form)
{
	if( typeof(form) === 'undefined' ) {
		form = document.getElementById('adminForm');
	}
	form.limitstart.value = pg;
	BsFormsubmit(form,1);
}

BsFormsubmit = function(form,ismobile) 
{
	if( typeof(ismobile) === 'undefined' ) ismobile = 0;
	var isd = -1;
	if( ismobile ) {
		isd = $('.ui-page-active')[0].className.indexOf('ui-dialog');
	}
	if( ismobile &&  isd != -1  ) {
		var hist = $.mobile.navigate.history;
		var idx = hist.activeIndex ;
		var url = hist.stack[idx].url;
		if( idx > 0 ) {
			hist.stack.splice(idx,1);
			idx--;
			$.mobile.navigate.history.activeIndex = idx;
		}
		$.mobile.changePage( url, {
//			type: "put",
			data: $(form).serialize(),
			transition: 'none',
			changeHash:false,
			role: 'dialog'
		});
	} else {
		form.submit();
	}
	return true;
}
BsmodalCancel = function(mid,cls)
{
	if(typeof mid === "undefined") {mid='modal';}
	if(typeof cls === "undefined") {cls=true;}
	if( cls ) {
		window.parent.jQuery('#'+mid).modal('hide');
	} else {
		location.reload(false);
	}
}
Bsmobiledialog = function(url)
{
	jQuery($.mobile).ajaxEnabled = true;
	jQuery($.mobile).linkBindingEnabled = true;
//	jQuery($.mobile.changePage( url, {
	jQuery($("body")).pagecontainer("change",url, {
		transition: "slide",
		changeHash:false,
		role: "dialog"
		});	
}
function isValidDate(dstr,f)
{
	if( !dstr || dstr.length == 0 ) {
		if( f!=1 ) {  // not required
			return true;
		}  
	}	
	regex=/\d{4}-\d{1,2}-\d{1,2}$/;
	if( !regex.test(dstr) ) return false;
	r = dstr.match(/\d+/g);
    if(r){
		if( r.length == 3 ) {
			var di = new Date(r[0],r[1]-1,r[2]);
			if(di.getFullYear() == r[0] && di.getMonth() == r[1]-1 && di.getDate() == r[2]){
				return true;
			}
		}
	}	
	return false;
}