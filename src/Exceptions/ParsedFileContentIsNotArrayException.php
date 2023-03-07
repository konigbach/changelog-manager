<?php

namespace Konigbach\ChangelogManager\Exceptions;

class ParsedFileContentIsNotArrayException extends ChangelogManagerException
{
    public function __construct(string $filename)
    {
        parent::__construct();
        $filename = realpath($filename);
        $this->message = "The content of the parsed file {$filename} is not an array.";
        $this->code = 422;
    }
}
