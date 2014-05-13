<?php
   namespace SubjectsPlus\Control;
class MailMessage {

    private $_to;
    private $_from;
    private $_fromString;
    private $_subjectLine;
    private $_content;
    private $_header;

    public function __construct($params=NULL) {
        if (is_array($params)) {
            if (isset($params['to'])) {
                $this->_to = $params['to'];
            }
            if (isset($params['subjectLine'])) {
                $this->_subjectLine = $params['subjectLine'];
            }
            if (isset($params['content'])) {
                $this->_content = $params['content'];
            }
            if (isset($params['from'])) {
                $this->_from = $params['from'];
                $this->_fromString = _("Library_No_Reply") . "<" . $this->_from . ">";
                $this->_header = "Return-Path: " . $this->_from . "\n";
                $this->_header .= "From:  " . $this->_from . "\n";
                $this->_header .= "Content-Type: text/html; charset=iso-8859-1;\n";
            }
        }
    }

    public function getTo() {
        return $this->_to;
    }

    public function getSubjectLine() {
        return $this->_subjectLine;
    }

    public function getContent() {
        return $this->_content;
    }

    public function getFrom() {
        return $this->_fromString;
    }

    public function getHeader() {
        return $this->_header;
    }

    public function setTo($recipientEmail) {
        $this->_to = $recipientEmail;
    }

    public function setSubjectLine($subjectLine) {
        $this->_subjectLine = $subjectLine;
    }

    public function setContent($contentString) {
        $this->_content = "<html><body>" . $contentString . "<p>" . _("This is an automatically generated email. Please do not respond.") . "</p><strong>" . _("Email sent: ") . date("l F j, Y, g:i a") . "</strong></body></html>";
    }

    public function setFrom($from) {
        $this->_fromString = "Library_No_Reply <" . $from . ">";
        $this->_header = "Return-Path: $from\r\n";
        $this->_header .= "From:  $this->_from\r\n";
        $this->_header .= "Content-Type: text/html; charset=iso-8859-1;\n\n\r\n";
    }

}
?>