<?php

namespace App\Libraries;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class WordProcessor
{
    protected $templateProcessor;

    public function __construct($templatePath)
    {
        $this->templateProcessor = new TemplateProcessor($templatePath);
    }

    public function setValues($values)
    {
        foreach ($values as $placeholder => $value) {
            $this->templateProcessor->setValue($placeholder, $value);
        }
    }

    public function saveAs($savePath)
    {
        $this->templateProcessor->saveAs($savePath);
    }

    public function output()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        $this->templateProcessor->saveAs($tempFile);
        return file_get_contents($tempFile);
    }
}