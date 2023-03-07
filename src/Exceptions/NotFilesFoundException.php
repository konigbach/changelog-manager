<?php

namespace Konigbach\ChangelogManager\Exceptions;

class NotFilesFoundException extends ChangelogManagerException
{
    /**
     * @var int
     */
    protected $code = 404;

    /**
     * @var string
     */
    protected $message = 'There is no files to prepare the changelog.';
}
