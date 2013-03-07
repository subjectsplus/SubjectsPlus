<?php

class sp_Mailer {
	
  public function __construct() {
    ini_set("SMTP", $sendmail);
    ini_set("sendmail_from", $administrator_email);
  }

  public function send(sp_MailMessage $m) {
    if (mail($m->getTo(), $m->getSubjectLine(), $m->getContent(), $m->getHeader())) {
      return true;
    } else {
      throw new Exception('Mail could not be sent.');
    }
  }

}
