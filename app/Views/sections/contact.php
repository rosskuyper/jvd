<?= $lang->paragraph('contact.content'); ?>

<form action="/contact" method="post" class="contact-form" id="contact-form" enctype="application/x-www-form-urlencoded">
	<div class="form-group">
		<label for="email"><?= $lang->get('contact.label.email'); ?>*</label>
		<div>
			<input type="text" name="email" id="email">
		</div>
	</div>
	<div class="form-group">
		<label for="subject"><?= $lang->get('contact.label.subject'); ?></label>
		<div>
			<input type="text" name="subject" id="subject">
		</div>
	</div>
	<div class="form-group">
		<label for="body"><?= $lang->get('contact.label.body'); ?>*</label>
		<div>
			<textarea name="body" id="body" cols="30" rows="10"></textarea>
		</div>
	</div>
	<div class="form-group form-attachments">
		<label for="body"><?= $lang->get('contact.label.attachments'); ?></label>
		<div>
			<button type="button" id="contact-upload" class="rounded-btn blue" data-token="<?= $uploadToken; ?>" data-proc="<?= $lang->get('contact.msg.upload.proc'); ?>" data-error="<?= $lang->get('contact.msg.upload.error'); ?>" data-success="<?= $lang->get('contact.msg.upload.success'); ?>" data-busy="<?= $lang->get('contact.msg.upload.busy'); ?>"><?= $lang->get('contact.label.attach-files'); ?></button>
			<input type="hidden" name="uploadToken" value="<?= $uploadToken; ?>">
			<span id="upload-msg" class="upload-msg"></span>
		</div>
	</div>

	<ul class="uploaded-files" id="uploaded-files"></ul>

	<div class="form-submit">
		<button type="submit" class="rounded-btn red"><?= $lang->get('contact.label.submit'); ?></button>
		<span id="form-msg" class="upload-msg" data-error="<?= $lang->get('contact.msg.form.error'); ?>"></span>
	</div>
</form>
