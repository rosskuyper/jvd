<form action="/contact" method="post" class="contact-form" id="contact-form" enctype="application/x-www-form-urlencoded">
	<div class="form-group">
		<label for="email">Email address</label>
		<div>
			<input type="text" name="email" id="email">
		</div>
	</div>
	<div class="form-group">
		<label for="subject">Subject</label>
		<div>
			<input type="text" name="subject" id="subject">
		</div>
	</div>
	<div class="form-group">
		<label for="body">Body</label>
		<div>
			<textarea name="body" id="body" cols="30" rows="10"></textarea>
		</div>
	</div>
	<div class="form-group form-attachments">
		<label for="body">Attachments</label>
		<div>
			<button type="button" id="contact-upload" class="rounded-btn blue" data-token="<?= $uploadToken; ?>">Attach File(s)</button>
			<input type="hidden" name="uploadToken" value="<?= $uploadToken; ?>">
			<span id="upload-msg" class="upload-msg"></span>
		</div>
	</div>

	<ul class="uploaded-files" id="uploaded-files"></ul>

	<div class="form-submit">
		<button type="submit" class="rounded-btn red">Submit</button>
		<span id="form-msg" class="upload-msg"></span>
	</div>
</form>
