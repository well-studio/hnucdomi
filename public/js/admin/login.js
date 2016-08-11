$('form').submit(function(){
	var ii = layer.load();
	//此处用setTimeout演示ajax的回调
	$.ajax({
		url: 'ajax',
		method: 'post',
		data: {
			func: 'login',
			pass: $('#pss').val()
		},
		dataType: 'json',
		success: function(data){
			layer.close(ii);
			if(data.err){
				layer.msg(data.msg);
			}else{
				window.location.href = '..';
			}
		},
		error: function(){
			layer.close(ii);
			layer.msg('网络异常');
		}
	});
    return false;
});