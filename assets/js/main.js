(function() {
	"use strict";

	/**
	 * Detect when the DOM is ready
	 */
	$(function(){
		/**
		 * Dialogs
		 */
		(function() {
			var $sections = $('.card');
			var $body     = $('body');
			var dlgs      = [];
			var $menuIcon = $(".hamburger-icon");

			var onOpen = function(dlg){
				dlg.el.style.top = window.scrollY + "px";
			};

			// Bind all
			$sections.each(function(i, elem){
				var $card     = $(elem);
				var $dialog   = $('#dialog-' + $card.attr('data-dialog'));
				var dlg       = new DialogFx($dialog.get(0), {onOpenDialog : onOpen});

				var nextIndex = i + 1 >= $sections.length ? 0 : i + 1;
				var prevIndex = i === 0 ? $sections.length - 1 : i - 1;

				// Add to arr
				dlgs.push(dlg);

				// Toggle on card click
				$card.on( 'click', dlg.toggle.bind(dlg) );

				// Close and open prev
				$dialog.find('.prev').on( 'click', function(){
					dlg.toggle();
					dlgs[prevIndex].toggle();
				});

				// Close and open next
				$dialog.find('.next').on( 'click', function(){
					dlg.toggle();
					dlgs[nextIndex].toggle();
				});
			});

			// Assumes mobile menu is in the same order as sections / dialogues (it is)
			$('.mobilenav').find('button').each(function(i){
				var toggle = dlgs[i].toggle.bind(dlgs[i]);
				$(this).on( 'click', function(){
					$menuIcon.click();
					toggle();
				});
			});

			// Hire me today - open the 6th section (Contact)
			$("#hire-me").on('click', dlgs[5].toggle.bind(dlgs[5]) );
		})();

		/**
		 * File Upload
		 */
		(function(){
			var $btn      = $("#contact-upload");
			var $msg      = $("#upload-msg").msgInterface();
			var $formMsg  = $("#form-msg").msgInterface();
			var $list     = $("#uploaded-files");
			var tokenAttr = $btn.attr('data-token');
			var uploader  = new Flow({
				target    : '/upload',
				chunkSize : 300 * 1024,
				query     : { uploadToken : tokenAttr }
			});

			// Resumable.js isn't supported
			if(! uploader.support ) {
				// Hide the attach file button. They can email instead.
				$btn.closest('.form-group').hide();
			}

			// Assign the button to open the browse window
			uploader.assignBrowse($btn.get(0));

			// Auto upload when a user selects a file
			uploader.on('filesSubmitted', uploader.upload);

			// Show the user the progress
			uploader.on('progress', function(){
				var prog = uploader.progress();

				if (prog === 0) {
					$msg.info('Uploading...');
				} else if (prog === 1) {
					$msg.info('Processing');
				} else {
					$msg.info('Uploading: ' + Math.round(prog * 100) + '%' );
				}
			});

			// Once the last bit is uploaded show the file
			uploader.on('fileSuccess', function(file){
				if ($list.is(':hidden')) {
					$list.slideDown();
				}

				$("<li />").text(file.name).hide().appendTo($list).slideDown();
			});

			uploader.on('complete', function(){
				$msg.success('File(s) uploaded successfully.');
			});

			// If something goes wrong, let the user know.
			uploader.on('error', function(){
				$msg.err("We experienced an error uploading your file, please email us at jvdtranslations@gmail.com.");
				uploader.cancel(); // End all other uploads.
			});

			/**
			 * Submit the final form
			 */
			$("#contact-form").submit(function(e){
				var $form = $(this), err = false;

				e.preventDefault();

				$("#email, #body").each(function(index, id){
					if (! $(this).val()) {
						err = true;
						$(this).setError();
					}
				});

				if (err) {
					// Show the user that they need to fill in more fields.
					$formMsg.err('Please fill in all the required fields.');
				} else {
					// Disable the submit btn
					$form.find('button[type="submit"]').disable("Processing");
					$formMsg.fadeOut();

					// Post the data across
					$.ajax({
						url      : '/contact',
						method   : 'POST',
						dataType : 'json',
						data     : $form.serialize(),
						complete : function(){
							$form.find('button[type="submit"]').enable();
						},
						success : function(data){
							if (data && data.success) {
								$("#dialog-contact").children('.dialog__overlay').click();
								$("#contact-success").css('top', window.scrollY + 'px').fadeIn();
							}
						}
					});
				}
			});
		})();

		/**
		 * Responsive menu - Hamburgler
		 */
		(function(){
			$(".hamburger-icon").click(function () {
				$(".mobilenav").fadeToggle(500);
				$(".top-menu").toggleClass("top-animate");
				$(".mid-menu").toggleClass("mid-animate");
				$(".bottom-menu").toggleClass("bottom-animate");
			});
		})();

		/**
		 * Contact success close
		 */
		(function(){
			$("#contact-success").find('button').on('click', function(){
				$("#contact-success").fadeOut();
			});
		})();
	});

	/**
	 * jQuery extensions
	 */
	$.fn.extend({
		msgInterface : function(){
			var $this             = this;
			var originalClassName = $this.attr('class');
			var timeout           = 0;

			// Curry a function used to set messages
			var generateSetMessageFn = function(type){
				window.clearTimeout(timeout);
				type = 'msg-' + type;

				return function(message){
					return $this.text(message)
					            .attr('class', originalClassName)
					            .addClass(type)
					            .show();
				};
			};

			$this.info    = generateSetMessageFn('info');
			$this.success = generateSetMessageFn('success');
			$this.err     = generateSetMessageFn('error');

			return $this;
		},
		disable : function(text){
			return this.data('activeText', this.text())
			           .prop('disabled', true)
			           .text(text);
		},
		enable : function(){
			return this.prop('disabled', false)
			           .text(this.data('activeText'));
		},
		setError : function() {
			var $this = this;
			return $this.addClass('error').on('keypress', function(){
				if ($this.val()) {
					$this.removeClass('error').off('keypress');
				}
			});
		}
	});
})();
