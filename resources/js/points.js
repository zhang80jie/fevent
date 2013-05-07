$(document).ready(function() {  
	initGroup();
}); 



function initGroup() {  
    $.getJSON("AjaxUtil.php",{param:"group"} ,function(result){
    	  // var group=  document.getElementById('group');
    	   $.each(result, function(i, field){
    	   //group.options.add(new Option(field.group_name,field.group_id));
    	   $("#group").get(0).add(new Option(field.group_name,field.group_id));
    		    
    });
});
}
