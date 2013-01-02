<html>
<head>
<script type="text/javascript" src="/static/js/jquery.js"></script>
</head>
<body>
<div id="bbb"></div>
<script type="text/javascript">
var test = (function() {
	var aa = 0;
	function aaa(text) {
		aa++;
	}

	function bbb(text) {
		$("#bbb").text(($("#bbb").text()) + " " + aa);
	}

	return {
		a : function(text) {
			aaa(text);
		},
		b: function(text) {
			bbb(text);
		},
		result_aa:function() {
			return aa;
		}
	};
});

$().ready(function() {
	var t1 = test();
	t1.a("test.a");
	t1.b("test.b");
	t1.a("test.a");
	alert(t1.result_aa());

	var t2 = test();
	t2.b("test.b");
	t2.a("test.a");
	t2.b("test.b");

	t1.b("test.b");
});
</script>
</body>
</html>