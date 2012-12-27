<!DOCTYPE html>
<html lang="en">
<head>
<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript" src="/static/js/bootstrap.js"></script>
<link href="/static/css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="/static/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	#bottom {height:100px;}
</style>
</head>
<body>
	<a href="#aaa" id="bottom_anchor">bottom</a>
<?php for($i=1;$i<=6;$i++):?>
	<h<?php echo $i;?>><?php echo "H".$i;?> Heading <?php $i;?></h<?php echo $i;?>>
<?php endfor;?>

	<p>
		Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.
		Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec ullamcorper nulla non metus auctor fringilla.
		Maecenas sed diam eget risus varius blandit sit amet non magna. Donec id elit non mi porta gravida at eget metus. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.
	</p>
	<p class="lead">
		Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus.
	</p>
	<p>
		<small>This line of text is meant to be treated as fine print.</small>
	</p>
	<p>
		The following snippet of text is <strong>rendered as bold text</strong>.
	</p>
	<p>
		The following snippet of text is <em>rendered as italicized text</em>.
	</p>
	<p class="muted">
		Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.
	</p>
	<p class="text-warning">
		Etiam porta sem malesuada magna mollis euismod.
	</p>
	<p class="text-error">
		Donec ullamcorper nulla non metus auctor fringilla.
	</p>
	<p class="text-info">
		Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis.
	</p>
	<p class="text-success">
		Duis mollis, est non commodo luctus, nisi erat porttitor ligula.
	</p>
	<p>
		An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.
	</p>
	<p>
		<abbr title="HyperText Markup Language" class="initialism">HTML</abbr> is the best thing since sliced bread.
	</p>
	<address>
		<strong>Twitter, Inc.</strong><br>
		795 Folsom Ave, Suite 600<br>
		San Francisco, CA 94107<br>
		<abbr title="Phone">P:</abbr> (123) 456-7890
	</address>

	<address>
		<strong>Full Name</strong><br>
		<a href="mailto:#">first.last@example.com</a>
	</address>

	<blockquote class="pull-right">
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
		<small>Someone famous <cite title="Source Title">Source Title</cite></small>
	</blockquote>

	<ul class="unstyled">
		<li>Nulla volutpat aliquam velit</li>
		<ul>
			<li>Phasellus iaculis neque</li>
			<li>Purus sodales ultricies</li>
			<li>Vestibulum laoreet porttitor sem</li>
			<li>Ac tristique libero volutpat at</li>
		</ul>
		<li>Faucibus porta lacus fringilla vel</li>
		<li>Aenean sit amet erat nunc</li>
	</ul>

	<ul class="inline">
		<li>A</li>
		<li>B</li>
		<li>C</li>
	</ul>

	<dl>
		<dt>Description lists</dt>
		<dd>A description list is perfect for defining terms.</dd>
		<dt>Euismod</dt>
		<dd>
			Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.<br />
			Donec id elit non mi porta gravida at eget metus.
		</dd>
		<dt>Malesuada porta</dt>
		<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
	</dl>

	<dl class="dl-horizontal">
		<dt>Description lists</dt>
		<dd>A description list is perfect for defining terms.</dd>
		<dt>Euismod</dt>
		<dd>
			Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.<br />
			Donec id elit non mi porta gravida at eget metus.
		</dd>
		<dt>Malesuada porta</dt>
		<dd>Etiam porta sem malesuada magna mollis euismod.</dd>
	</dl>
	<p>
		For example, <code>&lt;section&gt;</code> should be wrapped as inline.
	</p>
	<pre class="pre-scrollable">
  		&lt;p&gt;Sample text here...&lt;/p&gt;
	</pre>

	<table class="table table-striped table-bordered table-condensed">
		<caption>Table Example</caption>
		<thead>
			<tr>
				<th>#</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Username</th>
			</tr>
		</thead>
		<tbody>
			<tr class="success">
				<td rowspan="2">1</td>
				<td>Mark</td>
				<td>Otto</td>
				<td>@mdo</td>
			</tr>
			<tr class="error">
				<td>Mark</td>
				<td>Otto</td>
				<td>@mdo2</td>
			</tr>
			<tr class="warning">
				<td>2</td>
				<td>Mark</td>
				<td>Otto</td>
				<td>@mdo</td>
			</tr>
			<tr class="info">
				<td>3</td>
				<td>Mark</td>
				<td>Otto</td>
				<td>@mdo</td>
			</tr>
		</tbody>
	</table>

	<form class="form-search">
		<fieldset>
			<legend>Legend</legend>
			<label>Label name</label> <input type="text"
				placeholder="Type something…" class="search-query"> <span
				class="help-block">Example block-level help text here.</span> <label
				class="checkbox"> <input type="checkbox"> Check me out
			</label>
			<button type="submit" class="btn">Submit</button>
		</fieldset>
	</form>

	<form class="form-inline">
		<input type="text" class="input-medium" placeholder="Email">
		<input type="password" class="input-small" placeholder="Password">
		<label class="checkbox"> <input type="checkbox"> Remember me</label> <button type="submit" class="btn">Sign in</button>
	</form>

	<form class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="inputEmail">Email</label>
			<div class="controls">
				<input type="text" id="inputEmail" placeholder="Email">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPassword">Password</label>
			<div class="controls">
				<input type="password" id="inputPassword" placeholder="Password">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox"> <input type="checkbox"> Remember me</label>
				<button type="submit" class="btn">Sign in</button>
			</div>
		</div>
	</form>

	<p>
		<textarea rows="3"></textarea>
	</p>

	<p>
		<select>
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select>
	</p>

	<p>
		<select multiple="multiple">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select>
	</p>

	<div class="input-append">
		<input class="span2" id="appendedInputButtons" type="text">
		<button class="btn" type="button">Search</button>
		<button class="btn" type="button">Options</button>
	</div>

	<div class="input-append">
		<input class="span2" id="appendedDropdownButton" type="text">
		<div class="btn-group">
			<button class="btn dropdown-toggle" data-toggle="dropdown">
				Action
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="#">Action</a></li>
				<li><a href="#">Another action</a></li>
				<li><a href="#">Something else here</a></li>
				<li class="divider"></li>
				<li><a href="#">Separated link</a></li>
			</ul>
		</div>
	</div>

	<div id="bottom"></div>
</body>
</html>