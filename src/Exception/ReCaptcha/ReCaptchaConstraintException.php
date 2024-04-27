<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Exception\ReCaptcha;

/**
 * Thrown when ReCAPTCHA constraints are violated
 */
class ReCaptchaConstraintException extends ReCaptchaException
{
    /**
     * When Google ReCAPTCHA public key v3 is invalid
     */
    public const INVALID_KEY_V3 = 10;
}
