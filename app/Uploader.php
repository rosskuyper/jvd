<?php namespace Jvd;

/**
 * A lot of this code comes from https://github.com/23/resumable.js/blob/master/samples/Backend%20on%20PHP.md
 */
class Uploader
{
	/**
	 * Dir where temp files are stored
	 * @var string
	 */
	protected $temp_dir;

	/**
	 * Dir where the final file will be put
	 * @var string
	 */
	protected $dest;

	/**
	 * File where chunks are stored
	 * @var string
	 */
	protected $chunk_file;

	/**
	 * Request data
	 * @var array
	 */
	protected $data = [];

	/**
	 * Grab a reference to the slim instance
	 */
	public function __construct($resumableChunkNumber, $resumableChunkSize, $resumableTotalSize, $resumableIdentifier, $resumableFilename, $resumableRelativePath, $resumableCurrentChunkSize)
	{
		$this->data['resumableChunkNumber']      = $resumableChunkNumber;
		$this->data['resumableChunkSize']        = $resumableChunkSize;
		$this->data['resumableTotalSize']        = $resumableTotalSize;
		$this->data['resumableIdentifier']       = $resumableIdentifier;
		$this->data['resumableFilename']         = $resumableFilename;
		$this->data['resumableRelativePath']     = $resumableRelativePath;
		$this->data['resumableCurrentChunkSize'] = $resumableCurrentChunkSize;

		$this->dest = dirname(__DIR__) . '/uploads';

		$this->temp_dir = dirname(__DIR__) . '/uploads/chunks/'.$this->data['resumableIdentifier'];

		$this->chunk_file = $this->temp_dir.'/'.$this->data['resumableFilename'].'.part'.$this->data['resumableChunkNumber'];
	}

	/**
	 *
	 * Check if all the parts exist, and
	 * gather all the parts of the file together
	 * @param string $temp_dir - the temporary directory holding all the parts of the file
	 * @param string $fileName - the original file name
	 * @param string $chunkSize - each chunk size (in bytes)
	 * @param string $totalSize - original file size (in bytes)
	 */
	protected function createFileFromChunks($temp_dir, $fileName, $chunkSize, $totalSize) {

		// count all the parts of this file
		$total_files = 0;
		foreach(scandir($temp_dir) as $file) {
			if (stripos($file, $fileName) !== false) {
				$total_files++;
			}
		}

		// check that all the parts are present
		// the size of the last part is between chunkSize and 2*$chunkSize
		if ($total_files * $chunkSize >=  ($totalSize - $chunkSize + 1)) {

			// create the final destination file
			if (($fp = fopen($this->dest . '/'.$fileName, 'w')) !== false) {
				for ($i=1; $i<=$total_files; $i++) {
					fwrite($fp, file_get_contents($temp_dir.'/'.$fileName.'.part'.$i));
				}
				fclose($fp);
			} else {
				return false;
			}

			// rename the temporary directory (to avoid access from other
			// concurrent chunks uploads) and than delete it
			if (rename($temp_dir, $temp_dir.'_UNUSED')) {
				$this->rrmdir($temp_dir.'_UNUSED');
			} else {
				$this->rrmdir($temp_dir);
			}
		}

	}

	public function handleUpload() {
		// loop through files and move the chunks to a temporarily created directory
		if (!empty($_FILES)) foreach ($_FILES as $file) {

			// check the error status
			if ($file['error'] != 0) {
				continue;
			}

			// create the temporary directory
			if (!is_dir($this->temp_dir)) {
				mkdir($this->temp_dir, 0777, true);
			}

			// move the temporary file to the temp dir.
			if (!move_uploaded_file($file['tmp_name'], $this->chunk_file)) {
				// could not move it - problem!
			} else {
				// check if all the parts present, and create the final destination file
				$this->createFileFromChunks($this->temp_dir, $this->data['resumableFilename'], $this->data['resumableChunkSize'], $this->data['resumableTotalSize']);

				// Create a random name for the file, and move it there.
				$newFilename = $this->random();
				rename($this->dest . '/'. $this->data['resumableFilename'], $this->dest . '/' . $newFilename);

				return $newFilename;
			}
		}

		return false;
	}

	public function isValidChunk() {
		return file_exists($this->chunk_file);
	}

	/**
	 *
	 * Delete a directory RECURSIVELY
	 * @param string $dir - directory path
	 * @link http://php.net/manual/en/function.rmdir.php
	 */
	protected function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir . "/" . $object) == "dir") {
						rrmdir($dir . "/" . $object);
					} else {
						unlink($dir . "/" . $object);
					}
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	/**
	 * Taken from Laravel
	 * Generate a more truly "random" alpha-numeric string.
	 *
	 * @param  int  $length
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	public static function random($length = 32)
	{
		$bytes = openssl_random_pseudo_bytes($length * 2);

		if ($bytes === false)
		{
			// Fallback. It's just a filename - not too critical.
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

			return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
		}

		return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
	}
}
