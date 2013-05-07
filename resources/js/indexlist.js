  
$(document).ready(function() {  
	isEventAttentioners();
	isGroupMembers();
}); 


//邀请好友
function invite(){
    fusion2.dialog.invite({
     //  receiver: 'A25F8EA9568CA1BDD168DEC4BDFE2D34',
      msg  : "邀请你来玩~",

      img : "http://qzonestyle.gtimg.cn/qzonestyle/act/qzone_app_img/app353_353_75.png",
      
      source : "domain=yyjuhui.com/fevent/indexlist1.php",

      context : "invite",

      onSuccess : function (opt) {  
       // alert("邀请成功,发起邀请者的openid:" + opt.inviter +"被邀请者的openids:" + opt.invitees 
         // + "被邀请者的数量" + opt.invitees.length); 
         alert("邀请成功");
      },

      onCancel : function (opt) { 
        alert("邀请取消"); 
      },

      onClose : function (opt) {  
       // alert("邀请关闭"); 
      }
});
}

//倒计时
function startTime(endTimeStr, dom, label) {
        var currTime = new Date().getTime();
        var endTime = new Date(endTimeStr).getTime();
        var diffTime = (endTime - currTime) / 1000;
        if (diffTime < 0) {
          //  dom.innerHTML = "活动已结束";
            var str= '<div class="day">0<span>天</span></div>';
        	str+= '<div class="time_list"><span class="ti_hour">0</span><span class="ti_min">0</span><span class="ti_sec">0</span>';
        	
            dom.innerHTML=str;
            return;
        }
        var day = parseInt(diffTime / 86400);//秒数除以（3600*24） 则可以取到天数
        diffTime %= 86400;//取余数
        var hour = parseInt(diffTime / 3600);
        diffTime %= 3600;
        var minute = parseInt(diffTime / 60);
        diffTime %= 60;
        var second = parseInt(diffTime);
       //  dom.innerHTML = "现在离" + label + "还有" + day + "天" + hour + "小时" + minute + "分" + second + "秒";
		 var str= '<div class="day">'+day+'<span>天</span></div>';
		 str+= '<div class="time_list"><span class="ti_hour">'+hour+'</span><span class="ti_min">'+minute+'</span><span class="ti_sec">'+second+'</span>';
       
        dom.innerHTML=str;
        setTimeout(function () {
            startTime(endTime, dom,label);
        }, 500);
}



//遍历整个页面操作涉及到关注的标记
function  isEventAttentioners(){
	$(".eventclassforattention").each(function(data){
			isEventAttentioner($(this).text(),$(this));
	});
}


//判断某一个是否是已关注
function isEventAttentioner(event_id,obj) { 
    $.get("AjaxUtil.php",{param:"isattentioner",eventid:(''+event_id)} ,function(data){
    	if(data==0){
    		obj.html('<div id="eventattention'+event_id+'"><a href="javascript:void(0);" onclick="eventAttention('+event_id+')"><span class="focu_bu">关注</span></a><div>');
    	}else{
    		obj.html('已关注');
    	}
    });
}
//<a href="javascript:void(0);" onclick="attentevent()"><span class="focu_bu">关注</span></a>
function eventAttention(eventid){
	$.get("AjaxUtil.php",{param:"attention",eventid:eventid} ,function(data){
		 if(data>0){
			 $("#eventattention"+eventid).html('已关注');
		 }
	 });
}


//遍历是否是群组成员
function isGroupMembers(){
	$(".groupclass").each(function(data){
		isGroupMember($(this).text(),$(this));
});
}	
//判断是否是群组的成员
function isGroupMember(group_id,obj){
	   $.get("AjaxUtil.php",{param:"isgroupmember",groupid:(''+group_id)} ,function(data){
	    	if(data==0){
	    		obj.html('<div id="groupuser'+group_id+'"><a href="javascript:void(0);" onclick="joinGroup('+group_id+')"><img src="images/green_butt.png" width="17" height="15" /></a></div>');
	    	}else{
	    		obj.html('');
	    	}
	    });
}

function joinGroup(group_id){
	$.get("AjaxUtil.php",{param:"joingroup",groupid:(''+group_id)} ,function(data){
		 if(data>0){
			 $("#groupuser"+group_id).html('');
		 }
	 });

}

