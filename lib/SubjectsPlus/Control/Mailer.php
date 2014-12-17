<?php
   namespace SubjectsPlus\Control;
class Mailer {

  public function __construct() {
  	global $email_server;
  	global $administrator_email;

  	ini_set("SMTP", $email_server);
  	ini_set("sendmail_from", $administrator_email);
  }

  public function send(MailMessage $m) {
    if (mail($m->getTo(), $m->getSubjectLine(), $m->getContent(), $m->getHeader())) {
      return true;
    } else {
      throw new Exception('Mail could not be sent.');
    }
  }

}
?>
