<?php

namespace WPDeveloper\BetterDocs\Dependencies\PhpParser\ErrorHandler;

use WPDeveloper\BetterDocs\Dependencies\PhpParser\Error;
use WPDeveloper\BetterDocs\Dependencies\PhpParser\ErrorHandler;

/**
 * Error handler that collects all errors into an array.
 *
 * This allows graceful handling of errors.
 */
class Collecting implements ErrorHandler
{
    /** @var Error[] Collected errors */
    private $errors = [];

    public function handleError(Error $error) {
        $this->errors[] = $error;
    }

    /**
     * Get collected errors.
     *
     * @return Error[]
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Check whether there are any errors.
     *
     * @return bool
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Reset/clear collected errors.
     */
    public function clearErrors() {
        $this->errors = [];
    }
}