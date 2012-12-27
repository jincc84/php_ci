<!DOCTYPE html>
<html lang="en">
<head>
<link href="/static/css/bootstrap.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	div {background-color:#cccccc;}
	div {border:1px solid black;}
	div.row {background-color:transparent;}
</style>
</head>
<body>
	<div class="row">
		<div class="span4">span4</div>
		<div class="span8">span8</div>
	</div>
	<div class="row">
		<div class="span4">span4</div>
		<div class="span3 offset2">span3 offset2</div>
	</div>
	<div class="row">
		<div class="span3 offset1">span3 offset1</div>
		<div class="span3 offset2">span3 offset2</div>
	</div>
	<div class="row">
		<div class="span6 offset2">span6 offset2</div>
	</div>
	<div class="row">
		<div class="span10">
			Level 1 column
			<div class="row">
				<div class="span6">Level 2</div>
				<div class="span3">Level 2</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">span4</div>
		<div class="span8">span8</div>
	</div>
	<div class="row-fluid">
		<div class="span4">span4</div>
		<div class="span4 offset2">span4 offset2</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			Fluid 12
			<div class="row-fluid">
				<div class="span6">
					Fluid 6
					<div class="row-fluid">
						<div class="span6">Fluid 6</div>
						<div class="span6">Fluid 6</div>
					</div>
				</div>
				<div class="span6">Fluid 6</div>
			</div>
		</div>
	</div>
</body>
</html>