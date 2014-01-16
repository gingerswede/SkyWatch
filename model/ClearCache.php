<?php
require_once 'model/FTPConnector.php';

class ClearCache {
	static public function Clear() {
		$ftp = new FTPConnector();
		$ftp->clearCache();
	}
}
