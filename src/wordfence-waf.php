<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

if (file_exists('/var/git/it-community.in.ua/src/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/var/git/it-community.in.ua/src/wp-content/wflogs/');
	include_once '/var/git/it-community.in.ua/src/wp-content/plugins/wordfence/waf/bootstrap.php';
}
?>