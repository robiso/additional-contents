$(function() {

	$(".addition_content > b.toolbar").click(function(){
		var id = 'addition_content_show_'+$(this).attr('value');
		if($(this).hasClass("content_show")){
			var content = 'show';
			$(this).attr('title', 'Hide content');
			$.ajaxSetup({async: false});
			$.post("",{
				fieldname: id,
				content: content,
				target: 'pages',
				token: token,
			});
			new Promise(resolve => setTimeout(resolve, 5000));
			window.location.reload();
		} else if($(this).hasClass("content_hide")){
			var content = 'hide';
			$(this).attr('title', 'Show content');
			$.ajaxSetup({async: false});
			$.post("",{
				fieldname: id,
				content: content,
				target: 'pages',
				token: token,
			});
			new Promise(resolve => setTimeout(resolve, 5000));
			window.location.reload();
		} else{
			var id = 'addition_content_'+$(this).attr('value');
			$.ajaxSetup({async: false});
			$.post("",{
				delac: id,
				token: token,
			});
			new Promise(resolve => setTimeout(resolve, 5000));
			window.location.reload();
		}
	});

	$(".content_plus").click(function(){
		var id = 'addition_content_'+$(this).attr('value');
		var content = "This is a new ediable area. By default it's hidden.";
		$.ajaxSetup({async: false});
		$.post("",{
			addac: id,
			content: content,
			target: 'pages',
			token: token,
		});
		new Promise(resolve => setTimeout(resolve, 5000));
		window.location.reload();
	});
});
