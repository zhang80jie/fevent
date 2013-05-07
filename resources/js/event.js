$(document).ready(function() {
	initCatalog();
});

function initCatalog() {  
    $.getJSON("AjaxUtil.php",{param:"catalog"} ,function(result){
    	  // var group=  document.getElementById('group');
    	   $.each(result, function(i, field){
    	   //group.options.add(new Option(field.group_name,field.group_id));
    	   $("#content_catalog").get(0).add(new Option(field.content_cata_name,field.content_cata_id));
    		    
    });
});
}