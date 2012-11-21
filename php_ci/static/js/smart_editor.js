var smart_editor = function() {
	var oEditors = [];

	function _pasteHTML() {
		var sHTML = "<span style='color:#FF0000;'>이미지도 같은 방식으로 삽입합니다.<\/span>";
		oEditors.getById["ir1"].exec("PASTE_HTML", [sHTML]);
	}

	function _showHTML() {
		var sHTML = oEditors.getById["ir1"].getIR();
		alert(sHTML);
	}

	function _submitContents(elClickedObj) {
		oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.

		// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("ir1").value를 이용해서 처리하면 됩니다.

		try {
			elClickedObj.form.submit();
		} catch(e) {}
	}

	function _setDefaultFont() {
		var sDefaultFont = '궁서';
		var nFontSize = 24;
		oEditors.getById["ir1"]._setDefaultFont(sDefaultFont, nFontSize);
	}

	return {
		init: function() {
			nhn.husky.EZCreator.createInIFrame({
				oAppRef: oEditors,
				elPlaceHolder: "ir1",
				sSkinURI: "/static/smart_editor/SmartEditor2Skin.html",
				htParams : {bUseToolbar : true,
					fOnBeforeUnload : function(){
						//alert("아싸!");
					}
				}, //boolean
				fOnAppLoad : function(){
					//예제 코드
					//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
				},
				fCreator: "createSEditor2"
			});
		},
		paste: function() {
			_pasteHTML();
		},
		show: function() {
			_showHTML();
		},
		submit: function(elClickedObj) {
			_submitContents(elClickedObj);
		},
		default_font: function() {
			_setDefaultFont();
		},
		del: function(_this) {
			if(confirm("정말 삭제?")) {
				$("form[name=delete_content_form]").submit();
			}
		}

	};
}();