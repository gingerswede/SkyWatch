<?php

define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

class FTPConnector {
	private static $ftp_username = "113089_skywatch";
	private static $ftp_password = "j0hnhäggrud";
	private static $ftp_basedir = "forcast";
	private static $ftp_server = "ftp.code-monkey.se";
	private static $tmp_dir = 'temp';

	private $m_ftpConnection;

	public function clearCache() {
		try {
			$this -> ftpConnect();

			$this->deleteFiles(self::$ftp_basedir);

		} catch (Exception $e) {
			return new Exception("Error while clearing cache.");
		}
	}

	private function deleteFiles($path) {
		var_dump($path);
		$files = ftp_nlist($this -> m_ftpConnection, $path);
		var_dump($files);
		ftp_chdir($this->m_ftpConnection, $path);
		if (preg_match("/.xml$/", $path)) {
			ftp_delete($this->m_ftpConnection, $path);
		} else {
			foreach ($files as $file) {
				$subPath = $path."/".$file;
				if (!preg_match("/\.$/", $subPath)) {
					if (preg_match("/.xml$/", $file)) {
						echo "Removing " . $file . "<br />";
						ftp_delete($this->m_ftpConnection, $subPath);
					}
					else {
						echo "Entering " . $file . ". <br />";
						foreach (ftp_nlist($this->m_ftpConnection, $file) as $subFile) {
							if (!preg_match("/\.$/", $subFile))
								$this->deleteFiles(self::$ftp_basedir . "/" . $file . "/" . $subFile);
						}
						ftp_rmdir($this->m_ftpConnection, $file);	
					}	
				}						
			}
		}
	}

	public function getForcastTimeByCity(City $city) {
		$cityDir = $this -> getCityDir($city);

		try {
			$this -> ftpConnect();

			$file = ftp_nlist($this -> m_ftpConnection, $cityDir);
			ftp_close($this -> m_ftpConnection);
			return ($file) ? $file[2] : FALSE;
		} catch(Exception $e) {
			return false;
		}
	}

	public function saveReport(City $city, $xmlString, $fileName) {
		$cityDir = $this -> getCityDir($city);

		$lastReport = $this -> getForcastTimeByCity($city);

		try {
			$this -> ftpConnect();

			if ($lastReport) {
				ftp_delete($this -> m_ftpConnection, $cityDir . '/' . $lastReport);
			}

			$dir = explode('/', $cityDir);

			for ($i = 0; $i < count($dir); $i++) {
				$path = '/' . $dir[$i];
				if (!@ftp_chdir($this -> m_ftpConnection, $dir[$i])) {
					if (@ftp_mkdir($this -> m_ftpConnection, $dir[$i])) {
						ftp_chdir($this -> m_ftpConnection, $dir[$i]);
					}
				}
			}
			ftp_chdir($this -> m_ftpConnection, '/');

			ftp_chmod($this -> m_ftpConnection, DIR_WRITE_MODE, self::$tmp_dir);

			$file = fopen($this -> createFileFromString($xmlString), 'r');
			$remote_file = $cityDir . '/' . $fileName . '.xml';
			$result = ftp_fput($this -> m_ftpConnection, $remote_file, $file, FTP_ASCII);

			fclose($file);
			ftp_chmod($this -> m_ftpConnection, DIR_READ_MODE, self::$tmp_dir);

			ftp_close($this -> m_ftpConnection);
		} catch (Exception $e) {
			return false;
		}
	}

	private function createFileFromString($string) {
		$fileName = $_SERVER['DOCUMENT_ROOT'] . '/temp/tmp';

		$handle = fopen($fileName, 'w');

		fwrite($handle, $string);
		fclose($handle);

		return $fileName;
	}

	public function getCityDir(City $city) {

		$country = ($city -> country != NULL) ? strtolower($city -> country) . '/' : '';
		$county = ($city -> county != NULL) ? strtolower($city -> county) . '/' : '';
		$name = strtolower($city -> name);

		$path = self::$ftp_basedir . '/' . utf8_encode($country . $county . $name);

		$path = str_replace('å', 'a', $path);
		$path = str_replace('ä', 'a', $path);
		$path = str_replace('ö', 'o', $path);

		return $path;
	}

	private function ftpConnect() {
		$connectionId = ftp_connect(self::$ftp_server);

		$result = ftp_login($connectionId, self::$ftp_username, self::$ftp_password);

		if ((!$connectionId) || (!$result)) {
			throw new Exception("Error connecting to FTP-server", 1001);
		} else {
			$this -> m_ftpConnection = $connectionId;
		}
	}

}
