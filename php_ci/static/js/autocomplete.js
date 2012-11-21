$().ready(function() {
	function log(event, data, formatted) {
		formatted = eval("(" + formatted + ")");
		$("<li>").html( !data ? "No match!" : "Selected Address: " + formatted.address + ", Address ID:" + formatted.address_id).appendTo("#address_id");
	}

	function formatItem(row) {
		return row[0] + " (<strong>id: " + row[1] + "</strong>)";
	}
	function formatResult(row) {
		return row[0].replace(/(<.+?>)/gi, '');
	}

	$("#address").autocomplete("/prototype/autocomplete_address", {
		minChars: 0,
		width: 310,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			row = eval("(" + row + ")");
			return i + "/" + max + ": \"" + row.address + "\" [" + row.address_id + "]";
		},
		formatMatch: function(row, i, max) {
			row = eval("(" + row + ")");
			return row.address + " " + row.address_id;
		},
		formatResult: function(row) {
			row = eval("(" + row + ")");
			return row.address;
		}
	});

	$(":text, textarea").result(log).next().click(function() {
		$(this).prev().search();
	});
});