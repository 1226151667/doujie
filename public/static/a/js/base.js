$(function(){
	//layer全局配置
	layer.config({
		offset:'50px'
	});

	//入口文件
	var DOOR = '/admin.php';

	/***** 头部&左侧栏 *****/
	var navOJ = $("#nav");/*菜单栏*/
	var mainCase = navOJ.find("ul>li");/*主菜单*/
	var topOJ = $("#top");/*顶部栏*/
	var mainOJ = $("#main");
	var caseToggle = $(".case-toggle");/*菜单折叠/显示按钮*/
	
	/*菜单栏点击事件*/
	mainCase.click(function(){
		var iClass = "icon-angle-right";/*默认菜单图标*/
		var display = $(this).find("ul").css("display");/*此li下ul是否隐/显状态*/
		navOJ.find("ul .active>a>.pull-right").attr("class","pull-right "+iClass).parent().parent().removeClass("active").parent().find("li ul").hide();/*所有菜单和图标都收缩*/
		if(display=='none'){
			$(this).find("ul").show();
			$(this).addClass("active");
			iClass = "icon-angle-down";
		}/*此li下ul展开或隐藏*/
		$(this).find("a>.pull-right").attr("class","pull-right "+iClass);
	});
	
	/*折叠/显示left菜单栏*/
	caseToggle.click(function(){
		var small_logo = $(".logo a img").attr('small_logo');
		var big_logo = $(".logo a img").attr('big_logo');
		var left = navOJ.css("left");
		var logoW = (left=='0px')?"60px":"150px";
		var navL = (left=='0px')?"-150px":"0px";
		var mainP = (left=='0px')?"60px 20px 0px 20px":"60px 20px 0px 170px";
		var logo_src = (left=='0px')?small_logo:big_logo;
		var logo_h = (left=='0px')?"32":"50";
		navOJ.animate({"left":navL},250);
		mainOJ.animate({"padding":mainP},250);
		// topOJ.find(".top-left .logo").animate({"width":logoW},150,function(){
		// 	// $(this).find("span").toggle();
		// 	$(this).find("img").attr('src',logo_src).css('height',logo_h);
		// });
	});
	
	/***** 日期控件 *****/
	var start = {
	    format: 'YYYY-MM-DD',
	    isinitVal:true,
	    ishmsVal:false,
	    skinCell:"jedatefree",
	    minDate: '1990-01-01', //设定最小日期为当前日期
	    maxDate: $.nowDate({DD:0}), //最大日期
	    choosefun: function(elem, val, date){
	        end.minDate = date; //开始日选好后，重置结束日的最小日期
	        endDates();
	    }
	};
	var end = {
	    format: 'YYYY-MM-DD',
	    isinitVal:true,
	    skinCell:"jedatefree",
	    minDate: $.nowDate({DD:0}), //设定最小日期为当前日期
	    maxDate: '2099-12-31', //最大日期
	    choosefun: function(elem, val, date){
	        start.maxDate = date; //将结束日的初始值设定为开始日的最大日期
	    }
	};
	//这里是日期联动的关键        
	function endDates() {
	    //将结束日期的事件改成 false 即可
	    end.trigger = false;
	    $("#inpend").jeDate(end);
	}
	$('#inpstart').jeDate(start);
	$('#inpend').jeDate(end);

	// 图片预览
	$(".pic").change(function(){  
		// this.files代表的是选择的文件总资源（不止一个文件）
		var files = this.files;
		var jd = $(this).parent().parent().find("img");
		var objUrl = getObjectURL(files[0]);
		jd.attr("src",objUrl);
	});  
	//建立一个可存取到该file的url  
	function getObjectURL(file) {  
		var url = null ;   
		// 下面函数执行的效果是一样的，只是需要针对不同的浏览器执行不同的 js 函数而已  
		if (window.createObjectURL!=undefined) { // basic  
			url = window.createObjectURL(file) ;  
		} else if (window.URL!=undefined) { // mozilla(firefox)  
			url = window.URL.createObjectURL(file) ;  
		} else if (window.webkitURL!=undefined) { // webkit or chrome  
			url = window.webkitURL.createObjectURL(file) ;  
		}  
		return url ;  
	}

	$(".refresh").click(function(){
		window.location.reload();
	});

	$("input[name='select_all']").change(function(){
		if($(this).is(":checked")){
			$('input:checkbox').prop('checked',true);
		}else{
			$('input:checkbox').prop('checked',false);
		}
	});

	$("input[name='select_id']").change(function(){
		if($(this).is(':checked')){
			if($("input[name='select_id']").not("input:checked").length==0){
				$("input[name='select_all']").prop('checked',true);	
			}
		}else{
			$("input[name='select_all']").prop('checked',false);
		}
	});

	$(".trash").click(function(){
		var ids =[]; 
		$('input[name="select_id"]:checked').each(function(){ 
			ids.push($(this).val()); 
		}); 
		if(ids.length==0){
			layer.open({
				title:'提示',
				content:'您至少选中一个目标',
				icon:0
			});
		}else{
			layer.confirm('您确定删除?', {icon:3,title:'提示'}, function(index){
				var trash_before_load;
				var cate = $("#main").attr('class');
				$.ajax({url:DOOR+'/trash/common', type:'post', data:{'cate':cate,'ids':ids},
					beforeSend:function(){
						trash_before_load = layer.load();
					},
					success:function(data){
						var icon = 5;
						if(data[0]==200){
							icon = 1
						}
						layer.open({
							icon:icon,
							title: '操作结果',
							content: data[1],
							yes:function(index,layero){
								layer.close(index);
								window.location.href =  window.location.pathname;
							}
						});     
						  
						// layer.msg(data[1],{icon:icon},function(){
						// 	window.location.reload();
						// });
					},
					error:function(){
						// layer.msg('删除失败',{icon:5})
						layer.open({
							title:'提示',
							content:'删除失败',
							icon:5
						});
					},
					complete:function(){
						layer.close(trash_before_load);
					}
				})
			});
		}
	});

	$("#search").click(function(){
		var st = $("#inpstart").text();
		var et = $("#inpend").text();
		var q = $("#keyword").val();
		var s = $("#select").val();
		var url = $(this).attr('now_url');
		url_arr = url.split('.');
		url_arr.pop();
		url = url_arr.join(".");
		window.location.href = url+"/"+st+"/"+et+"/"+s+"/"+q;
	});


	$(".login_box button").click(function(){
		data=$("form").serializeArray();
		var login_before_load;
		$.ajax({url:DOOR+"/Login/login_check", type:'post', data:data,
			beforeSend:function(){
				login_before_load = layer.load();
			},
			success:function(data){
				if(data[0]==200){
					window.location.href = DOOR;
				}else{
					layer.msg(data[1],{icon:5});
				}
			},
			error:function(){
				layer.msg('登录失败',{icon:5});
			},
			complete:function(){
				layer.close(login_before_load);
			}
		});
	});

});
