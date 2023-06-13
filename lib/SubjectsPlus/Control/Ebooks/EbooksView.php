<?php

namespace SubjectsPlus\Control\Ebooks;

class EbooksView
{

    private $templateData;

    public function __construct() {
        $this->templateData = array();
    }

    public function set($key, $value) {
        $this->templateData[$key] = $value;
    }

    public function render($templateFile) {
        if (file_exists($templateFile)) {
            extract($this->templateData); // Extract the template data to variables
            ob_start(); // Start output buffering
            include $templateFile; // Include the template file
            $content = ob_get_clean(); // Get the buffered output and clear the buffer
            return $content;
        } else {
            throw new \Exception("Template file '$templateFile' not found.");
        }
    }
}