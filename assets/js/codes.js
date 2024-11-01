//

//register the onclick for the toobar buttons

	function ziggeobbpressStartRecording(type) {

		if(type === null || typeof type === 'undefined') {
			ziggeoDevReport('Unspecified recording type' , 'error');
			return false;
		}

		ziggeobbPressShowOverlayWithRecorder(type);

		ZiggeoWP.hooks.set('ziggeo_overlay_popup_on_verify', 'ziggeobbpress_reply_public_recording', function(embedding) {

			//We should check if the "bbPress Enable TinyMCE Visual Tab" plugin is used and it turned on TinyMCE
			// the element with class "mce-tinymce" will be right in front of the standard fields

			_textelement = null;

			//Do we have the default reply form shown? (for the forum topic reply)
			if(document.getElementById('bbp_reply_content')) {
				var _textelement = document.getElementById('bbp_reply_content');
			}
			//Do we have the default forum listing shown with topic creation? (for forum topic creation)
			else if(document.getElementById('bbp_topic_content')) {
				var _textelement = document.getElementById('bbp_topic_content');
			}

			if(_textelement !== null) {
				if(_textelement.previousSibling &&
				   typeof _textelement.previousSibling.className !== 'undefined' &&
				   _textelement.previousSibling.className.indexOf('mce-tinymce') > -1) {
					if(_textelement.style.display === 'none') {
						//So now we know that the TinyMCe editor is enabled and that it is the one that is currently active
						tinyMCE.activeEditor.setContent( tinyMCE.activeEditor.getContent() + '[ziggeoplayer]' +
						                                                                        embedding.get('video') +
						                                                                      '[/ziggeoplayer]');
						return true;
					}
				}
				//Medium editor support
				else if(MediumEditor) {
					var editor = MediumEditor.getEditorFromElement(_textelement.previousElementSibling);
					if(editor) {
						editor.setContent(editor.getContent() +
						                 '[ziggeoplayer]' +
						                    embedding.get('video') +
						                 '[/ziggeoplayer]');
					}
				}

				//Lets add the token into it then
				_textelement.textContent += '[ziggeoplayer]' +
												embedding.get('video') +
											'[/ziggeoplayer]';
			}
			else {
				//Lets just show the code that they should copy paste
				alert('Please add the following to your reply body:' + "\n" +
					'[ziggeoplayer]' + embedding.get('video') + '[/ziggeoplayer]');
			}

		});

		return true;
	}

	function ziggeobbPressShowOverlayWithRecorder(type) {

		ziggeoShowOverlay();

		//now the element that will hold our recorder (we make sure that it will be fully displayed on mobile and desktop screens)..
		var s = document.createElement('div');
		s.id="ziggeo-video-screen";
		document.body.appendChild(s);

		//Recorder element parameters
		_attrs = {
				width: 300,
				height: 300,
				theme: "modern",
				themecolor: "red"
		};

		if(type !== null && typeof type !== 'undefined') {
			if(type === 'screen') {
				_attrs.allowscreen = true;
			}
			else if(type === 'audio') {
				//@here - not now
			}
		}

		//If we have the info about the integrations recorder template
		if(ZiggeoWP.integrations_code_recorder && (ZiggeoWP.integrations_code_recorder !== false || ZiggeoWP.integrations_code_recorder === '') ) {

			var code = ZiggeoWP.integrations_code_recorder;
			//Removing the template start and end
			code = code.replace('[ziggeorecorder', '').replace(']', '');
			code = code.replace(/=/g, ':');

			code = code.match(/(?:[^\s']+|'[^\']*\')+/g);

			//We have a n->property array with string values

			for(i = 0, l = code.length; i < l; i++) {

				var _prop = code[i].split(':');

				if(typeof _prop[1] === 'undefined') {
					_prop[1] = true;
				}
				else {
					//To remove things like "'false'"
					//thanks: https://stackoverflow.com/a/32516190
					_prop[1] = _prop[1].replace(/^\'+|\'+$/g, '');
				}

				_attrs[_prop[0]] = _prop[1];
			}

		}

		//create recorder using v2 recorder code
		var recorder = new ZiggeoApi.V2.Recorder({
		element: document.getElementById('ziggeo-video-screen'),
			attrs: _attrs
		});

		//To place the recorder in the middle of the screen
		if(_attrs.width) {
			var _width = _attrs.width;
			var _height = _attrs.height;

			if(typeof _attrs.width === 'string') {
				var _width = _attrs.width.replace('%', '').replace('px', '');
			}
			if(typeof _attrs.height === 'string') {
				var _height = _attrs.height.replace('%', '').replace('px', '');
			}

			document.getElementById('ziggeo-video-screen').style.left = 'calc(50% - ' + (_width / 2) + 'px)';
			document.getElementById('ziggeo-video-screen').style.top = 'calc(50% - ' + (_height / 2) + 'px)';
		}

		recorder.activate();

		//add event handler
		recorder.on("verified", function () {
			ZiggeoWP.hooks.fire('ziggeo_overlay_popup_on_verify', recorder);
		});
	}
