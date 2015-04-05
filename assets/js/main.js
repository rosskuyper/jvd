(function() {
	"use strict";
	/**
	 * Detect when the DOM is ready
	 */
	(function(ready){
		function completed(){
			document.removeEventListener( "DOMContentLoaded", completed, false );
			window.removeEventListener( "load", completed, false );
			ready();
		}

		// Bind to check
		if ( document.readyState === "complete" ) {
			// Handle it asynchronously to allow scripts the opportunity to delay ready
			setTimeout( ready );
		} else {
			// Use the handy event callback
			document.addEventListener( "DOMContentLoaded", completed, false );

			// A fallback to window.onload, that will always work
			window.addEventListener( "load", completed, false );
		}
	})(function(){
		/**
		 * Dialogs
		 */
		(function() {
			var sections = document.getElementsByClassName('card');
			var body = document.querySelector('body');
			var dlgs = [];

			var onOpen = function(dlg){
				dlg.el.style.top = window.scrollY + "px";
			};

			var bindDialog = function(i){
				var card      = sections.item(i);

				var id        = card.attributes.getNamedItem('data-dialog');
				var dialog    = document.getElementById('dialog-' + (id.value || id.nodeValue));
				var dlg       = new DialogFx(dialog, {onOpenDialog : onOpen});

				var nextIndex = i + 1 >= sections.length ? 0 : i + 1;
				var prevIndex = i === 0 ? sections.length - 1 : i - 1;

				// Add to arr
				dlgs.push(dlg);

				// Toggle on card click
				card.addEventListener( 'click', dlg.toggle.bind(dlg) );

				// Close and open prev
				dialog.querySelector('.prev').addEventListener( 'click', function(){
					dlg.toggle();
					dlgs[prevIndex].toggle();
				});

				// Close and open next
				dialog.querySelector('.next').addEventListener( 'click', function(){
					dlg.toggle();
					dlgs[nextIndex].toggle();
				});
			};

			// Bind all
			for (var i = 0; i < sections.length; i++) {
				bindDialog(i);
			}
		})();

		/**
		 * File Upload
		 */
		(function(){
			var btn = document.getElementById("hire-me");
			var uploader = new Flow({
				target: '/upload',
				chunkSize : 300 * 1024,
				query: { uploadToken: 'asdf' }
			});

			// Resumable.js isn't supported
			if(!uploader.support) {
				// Tell user @todo
				return;
			}

			uploader.assignBrowse(btn);

			uploader.on('filesSubmitted', uploader.upload);
			uploader.on('progress', function(a,b,c){
				console.log(['progress', uploader.progress()]);
			});
			uploader.on('complete', function(a,b,c){
				console.log(['complete']);
			});
			uploader.on('error', function(a,b,c){
				console.log(['error', a, b, c]);
			});

			window.uploader = uploader;
		})();
	});
})();
