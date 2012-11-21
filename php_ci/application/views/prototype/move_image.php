<script type="text/javascript" src="/static/js/move_image.js"></script>
<script type="text/javascript">
$().ready(function() {
	move_image.init();
});
</script>
<style type="text/css">
#area {text-align:center; }
#img_area {vertical-align:top; height:300px;  line-height:300px;}
#img_area div {display:inline-block; vertical-align:top; font-size:30pt; cursor:pointer;}
#desc_area {font-size:9pt; padding-top:10px;}
</style>
<div id="body">
	<h1>move_image</h1>
	<code>
		<div id="area">
			<div id="img_area">
				<div class="btn" onclick="move_image.move('prev');"><</div>
				<img id="img" src="/static/img/moohandojeon.jpg" width="400" height="300" />
				<div class="btn" onclick="move_image.move('next');">></div>
			</div>
			<div id="desc_area"></div>
		</div>
	</code>
</div>

