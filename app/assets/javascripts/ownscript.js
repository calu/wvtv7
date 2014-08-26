/*
 * @file : ownscript.js
 * @date : 2014 august 26
 * @author : dr. Johan Calu
 */

function changeProfileGroup(dit, id)
{
	var thisbutton = dit.innerHTML;
	
	var data = { groupname : thisbutton, ditid : id };
	
	$.ajax({
		type : 'POST',
		url : 'changeprofilegroup',
		data : data,
		dataType : 'json',
		success : function(data){
			document.location.reload();
		}
	});
}

function setProfileGroup( groupname)
{
	var obj = document.getElementById('changeProfileGroup');
//	var x = toonobject(obj); alert("xxxx = " + x);
	for ( var o in obj)
	{
//		var s = toonobject(o); alert("object = " + s);
	}

}

/** hulpfunctie voor testen **/
    function toonobject(obj)
    {
    	var s = "";
    	for(o in obj)
    	{
    		s += "#" + o + " : " + obj[o];
    	}
    	return s;
    }
    
    function test(x)
    {
    	alert("een test");
    }
    

