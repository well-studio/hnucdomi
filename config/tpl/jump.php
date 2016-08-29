<?php
$code = 1;
$msg = "提示信息";
$wait = 500;
function config($var){
	return '/hnucdomi';
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>跳转提示</title>
	<link rel="stylesheet" href="<?php echo(config('site_root'));?>/public/plugins/font-awesome/4.6.1/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo(config('site_root'));?>/public/plugins/amazeui/assets/css/amazeui.min.css">
	<link rel="stylesheet" href="<?php echo(config('site_root'));?>/public/css/style.css">
	<style type="text/css">
		html {
			height:100%;
		}
		body {
			height:100%;
		}
		.am-alert {
			height: 100%;
			margin-bottom: 0px;
		}
	</style>
</head>
<body>
	<div class="am-alert am-alert-<?php switch($code){case 1:echo"success";break;case 0:echo"danger";break;}?>">
		<div class="am-g" style="margin-top:100px;">
			<div class="am-u-md-4 am-u-md-offset-4">
				<?php switch ($code) {?>
<?php /*要拿一个顶格，不然报语法错误。。这是为啥*/case 1:?>
					<i class="fa fa-check" style="font-size:10em;"></i>
					<p class="success" style="font-size:2em;"><?php echo(strip_tags($msg));?></p>
					<?php break;?>
					<?php case 0:?>
					<i class="fa fa-times" style="font-size:10em;"></i>
					<p class="error" style="font-size:2em;"><?php echo(strip_tags($msg));?></p>
					<?php break;?>
				<?php } ?>
				<p class="detail"></p>
				<p class="jump" style="font-size:1.5em;">
					页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
				</p>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		(function(){
			var wait = document.getElementById('wait'),
				href = document.getElementById('href').href;
			var interval = setInterval(function(){
				var time = --wait.innerHTML;
				if(time <= 0) {
					location.href = href;
					clearInterval(interval);
				};
			}, 1000);
		})();
	</script>
</body>
</html>
