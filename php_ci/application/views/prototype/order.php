<script type="text/javascript" src="/static/js/Order.js" charset="utf-8"></script>
<script type="text/javascript">
$().ready(function() {
	Order.init();
});
</script>
<style type="text/css">
#total_price_area {position:absolute; top:50px; left:350px;}
#total_price_area>h3 {display:inline;}
#orderlist>li>span.btn {cursor:pointer;}
#option_area {display:none; position:absolute; top:20px; left:30px; width:300px; min_height:200px; border:1px solid gray; background-color:white; margin:0;}
#option_area h2 {font-size:12pt; border-bottom:1px dotted gray; margin:0; padding:5px; cursor:pointer;}
#option_area h3 {font-size:9pt; padding-left:5px;}
span.add, span.sub {font-size:12pt;}
</style>

<div id="body">
	<h1>order</h1>
	<code style="position:relative;">
		<p>
			<ul id="menulist" ></ul>
		</p>
		<code>
			<ul id="orderlist"></ul>
		</code>
		<div id="total_price_area">
			<h3>total price:</h3>
			<span id="total_price">0</span>원
		</div>
		<div id="option_area">
			<h2 id="menu_name" onclick="Order.close();"></h2>
			<p id="optionlist_area"></p>
			<p>
				주문금액 : <span id="order_price">0</span>원	<input type="button" value="add order" onclick="Order.set_option();" />
			</p>
		</div>
	</code>
</div>

