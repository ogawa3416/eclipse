jQuery(document).ready(function($){
    if( ((navigator.pointerEnabled || navigator.msPointerEnabled) && navigator.maxTouchPoints > 0 )  || window.ontouchstart === null ) {
		if( $('html').hasClass('touch') == false ) {
			$('html').addClass('touch');
			$('html').removeClass('no-touch');
		}
    } else {
	    if( $('html').hasClass('no-touch') == false ) {
			$('html').removeClass('touch');
			$('html').addClass('no-touch');
		}
    }
});
