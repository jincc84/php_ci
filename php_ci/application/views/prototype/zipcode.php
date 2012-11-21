<script type="text/javascript">
$().ready(function() {
});
</script>
<style type="text/css">
</style>
<div id="body">
	<h1>zipcode</h1>
	<?php echo validation_errors(); ?>
	<?php echo form_open("prototype/zipcode"); ?>
	<code>
			검색할 동 입력 : <input name="dong" value="<?php echo set_value("dong");?>" />
	</code>
	</form>
	<p>
<?php
	if($error["is_error"]) {
		echo $error["error_code"] . " / " . $error["message"];
	} elseif(count($zipcode_list) > 0) {
		foreach($zipcode_list as $zipcode) {
?>
		<ul>
			<li><?php echo $zipcode["address"];?></li>
			<li><?php echo $zipcode["postcd"];?></li>
		</ul>
<?php
		}
	}
?>
	</p>
</div>

