/**
 * BsCore component 
 *
 * @package		BsAlpinist
 * @subpackage	javascript
 **/
/* call by milestentry , bsinsentry, entryform, repoform */
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
