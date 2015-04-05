# Upload Tokens
CREATE TABLE `upload_tokens` (
  `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token`      VARCHAR(32) NOT NULL UNIQUE,
  `file`       VARCHAR(64) NOT NULL,
  `original`   VARCHAR(64) NOT NULL,
  `mime`       VARCHAR(16) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
