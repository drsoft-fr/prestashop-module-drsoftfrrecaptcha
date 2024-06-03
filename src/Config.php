<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha;

use Throwable;

/**
 * Class Config
 *
 * This class contains constants and a method to generate error messages.
 */
final class Config
{
    const ERROR_MESSAGE_PATTERN = 'drsoftfrrecaptcha - %s - %d - Throwable #%d - %s.';

    const INSTALLER_SERVICE = 'drsoft_fr.module.recaptcha.install.installer';

    const RECAPTCHA_PROVIDER_SERVICE = 'drsoft_fr.module.recaptcha.data.recaptcha_data_provider';

    /**
     * Creates an error message using the given method, line number and throwable object.
     *
     * @param string $method The name of the method where the error occurred.
     * @param int $line The line number where the error occurred.
     * @param Throwable $t The throwable object representing the error.
     *
     * @return string The formatted error message.
     */
    static public function createErrorMessage(string $method, int $line, Throwable $t): string
    {
        return sprintf(self::ERROR_MESSAGE_PATTERN, $method, $line, $t->getCode(), $t->getMessage());
    }
}
