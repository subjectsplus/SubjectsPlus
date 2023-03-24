<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../lib/LTI13/SP_Database.php';

use \IMSGlobal\LTI;
var_dump($_REQUEST);
// TODO: this is where we should get the request vars and redirect to lti13.php
LTI\LTI_OIDC_Login::new(new SP_Database())
    ->do_oidc_login_redirect(TOOL_HOST . "/addons/bb-lti/redirect.php")
    ->do_redirect();

