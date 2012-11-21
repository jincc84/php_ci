<script type="text/javascript" src="http://openapi.map.naver.com/openapi/naverMap.naver?ver=2.0&key=<?php echo $map_key;?>"></script>
<script type="text/javascript" src="/static/js/map.js" charset="utf-8"></script>
<script type="text/javascript">
$().ready(function() {
	map.init("<?php echo $location_name;?>", eval(<?php echo $coord;?>), "<?php echo $error_code;?>");
});
</script>
<link rel="shortcut icon" href="favicon.icon" />
<style type="text/css">
</style>
<div id="body">
	<h1>map</h1>
	<code style="position:relative;">
		<div id="map" style="border:1px solid #000;"></div>
	</code>
</div>

<link rel="icon" href="/favicon.ico" />