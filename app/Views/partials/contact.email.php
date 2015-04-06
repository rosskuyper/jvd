From: <?= $email; ?>

Subject: <?= empty($subject) ? '[No subject provided]' : $subject; ?>

--------------------------------------------------------------------------------
<?php if (!empty($files)) : ?>
Files Uploaded:
<?php foreach($files as $file) : ?>
<?= $file['original']; ?> - <?= $file['url'] . ' '; ?>

<?php endforeach; else : ?>
No files were uploaded.
<?php endif; ?>

--------------------------------------------------------------------------------

<?= $body; ?>
