<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>test</title>
<script type="text/javascript" src="/static/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="/static/js/topmenu.js"></script>

<link rel="stylesheet" type="text/css" href="/static/css/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="/static/css/test.css" />
<style type="text/css">

</style>
<script type="text/javascript">

</script>
</head>
<body>

	<div id="container">
		<div id="menu_area">
			<ul class="first_depth"></ul>
			<ul class="second_depth"></ul>
		</div>

<?php echo $content_in_layout;?>

		<p class="footer">
			Page rendered in <strong>{elapsed_time}</strong> seconds
		</p>
	</div>

</body>
</html>
