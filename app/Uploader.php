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

		$this->temp_dir = __DIR__ . '/../uploads/chunks/'.$this->data['resumableIdentifier'];

		$this->chunk_file = $this->temp_dir.'/'.$this->data['resumableFilename'].'.part'.$this->data['resumableChunkNumber'];
	}

	/**
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
			if (($fp = fopen('temp/'.$fileName, 'w')) !== false) {
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
				rrmdir($temp_dir.'_UNUSED');
			} else {
				rrmdir($temp_dir);
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

			// init the destination file (format <filename.ext>.part<#chunk>
			// the file is stored in a temporary directory
			$dest_file = $this->temp_dir.'/'.$this->data['resumableFilename'].'.part'.$this->data['resumableChunkNumber'];

			// create the temporary directory
			if (!is_dir($this->temp_dir)) {
				mkdir($this->temp_dir, 0777, true);
			}

			// move the temporary file
			if (!move_uploaded_file($file['tmp_name'], $dest_file)) {
			} else {
				// check if all the parts present, and create the final destination file
				$this->createFileFromChunks($this->temp_dir, $this->data['resumableFilename'], $this->data['resumableChunkSize'], $this->data['resumableTotalSize']);
			}
		}
	}

	public function isValidChunk() {
		return file_exists($this->chunk_file);
	}
}
