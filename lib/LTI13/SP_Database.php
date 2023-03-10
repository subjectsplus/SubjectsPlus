<?php
require_once __DIR__ . '/../../vendor/autoload.php';
define("TOOL_HOST", ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?: $_SERVER['REQUEST_SCHEME']) . '://' . $_SERVER['HTTP_HOST']);
session_start();
use \IMSGlobal\LTI;

$_SESSION['iss'] = [];
$reg_configs = array_diff(scandir(__DIR__ . '/configs'), array('..', '.', '.DS_Store'));
foreach ($reg_configs as $key => $reg_config) {
    $_SESSION['iss'] = array_merge($_SESSION['iss'], json_decode(file_get_contents(__DIR__ . "/configs/$reg_config"), true));
}

class SP_Database implements LTI\Database {
    public function find_registration_by_issuer($iss)
    {
        // TODO: Implement find_registration_by_issuer() method.
    }

    public function find_deployment($iss, $deployment_id)
    {
        // TODO: Implement find_deployment() method.
    }

    private function private_key($iss) {
        return file_get_contents(__DIR__ . $_SESSION['iss'][$iss]['private_key_file']);
    }
}