<?php declare(strict_types=1);
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends MY_Controller{


	function __construct(){
		parent::__construct();
		$this->load->model("logging_model","logging");
	}

	function index(){
		$filename = sprintf("backup-%s.sql.gz", date("Y-m-d-H-i-s"));
		$backup_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;
		$error_path = $backup_path . '.err';

		// Quoted so a `#`, space, or quote in the value can't be
		// misread as an ini comment or truncate the option.
		$quote_ini_value = fn(string $value): string => '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';

		$credentials_path = tempnam(sys_get_temp_dir(), 'mysqldump-cnf-');
		$credentials = sprintf(
			"[client]\nhost=%s\nport=%d\nuser=%s\npassword=%s\n",
			$quote_ini_value($this->db->hostname),
			$this->db->port ?: 3306,
			$quote_ini_value($this->db->username),
			$quote_ini_value($this->db->password)
		);
		file_put_contents($credentials_path, $credentials);
		chmod($credentials_path, 0600);

		// Stream mysqldump straight through gzip to disk so we never hold
		// the whole export in PHP memory (the old dbutil-based backup did,
		// and that's what was exhausting memory_limit as the db grew).
		// --defaults-file (not --defaults-extra-file) is required here: the
		// extra-file form still lets a host's ~/.my.cnf override our
		// password with its own, which silently authenticates as the wrong
		// user instead of failing.
		$command = sprintf(
			'set -o pipefail; mysqldump --defaults-file=%s --single-transaction --no-tablespaces --routines --triggers --default-character-set=utf8mb4 --add-drop-table %s 2>%s | gzip -c > %s',
			escapeshellarg($credentials_path),
			escapeshellarg($this->db->database),
			escapeshellarg($error_path),
			escapeshellarg($backup_path)
		);

		exec('/bin/bash -c ' . escapeshellarg($command), $output, $return_code);

		unlink($credentials_path);

		$error_output = is_file($error_path) ? trim((string) file_get_contents($error_path)) : '';
		if (is_file($error_path)) {
			unlink($error_path);
		}

		// gzip still writes ~20 bytes of header/footer for an empty input,
		// so a bare "=== 0" check wouldn't catch a failed/empty dump.
		if ($return_code !== 0 || !is_file($backup_path) || filesize($backup_path) < 100) {
			$this->logging->log("backup", "failed: " . $error_output);
			show_error("Database backup failed: " . $error_output);
		}

		$this->logging->log("backup", "success");
		$this->session->set_flashdata("notice", "Move the backup file from your downloads folder to your backups folder.");

		// Clean up the temp file once the download finishes; force_download()
		// exits the script itself, so this can't run as normal cleanup code.
		register_shutdown_function('unlink', $backup_path);

		$this->load->helper('download');
		force_download($backup_path, NULL);
	}


}