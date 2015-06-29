var fbyEventMethod 	= window.addEventListener ? 'addEventListener' : 'attachEvent';
var fbyEventer 		= window[fbyEventMethod];
var fbyMessageEvent = fbyEventMethod == 'attachEvent' ? 'onmessage' : 'message';

fbyEventer(fbyMessageEvent, fliibyOnMessage, false);

function fliibyOnMessage(event) {
	try {
		var upload_page = false;
		var origin 		= event.origin;
		var data 		= event.data;

		if (data.indexOf('fliibyembed') == 0) {
			var embed_line_arr 	= data.split('|');
			var embed_line 		= embed_line_arr[1];

			if (embed_line_arr.length > 2) {
				upload_page = embed_line_arr[2] == 'fliibyupload';
			}

			if (embed_line.indexOf('[') !== 0) {
				embed_line = "<p>" + embed_line + "</p>";
			}

			embed_line += "\n";

			if (window.tinyMCE !== null && window.tinyMCE.activeEditor !== null && !window.tinyMCE.activeEditor.isHidden()) {
				if (typeof window.tinyMCE.execInstanceCommand !== 'undefined') {
					window.tinyMCE.execInstanceCommand(window.tinyMCE.activeEditor.id, 'mceInsertContent', false, embed_line);
				} else {
					send_to_editor(embed_line);
				}
			} else {
				embed_line = embed_line.replace("<p>", "\n").replace("</p>", "");

				if (typeof QTags.insertContent === 'function') {
					QTags.insertContent(embed_line);
				} else {
					send_to_editor(embed_line);
				}
			}

			//if (!upload_page)
				//tb_remove();
		}
	} catch (err) {
		if (typeof console !== 'undefined')
			console.log(err.message);
	}
}