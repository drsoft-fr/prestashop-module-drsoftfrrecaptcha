<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Exception\ReCaptcha;

/**
 * Thrown when ReCAPTCHA constraints are violated
 */
class ReCaptchaConstraintException extends ReCaptchaException
{
    /**
     * When active field is invalid
     */
    public const INVALID_ACTIVE = 10;

    /**
     * When activated_on_contact_form field is invalid
     */
    public const INVALID_ACTIVATED_ON_CONTACT_FORM = 20;

    /**
     * When activated_on_login_form field is invalid
     */
    public const INVALID_ACTIVATED_ON_LOGIN_FORM = 30;

    /**
     * When activated_on_registration_form field is invalid
     */
    public const INVALID_ACTIVATED_ON_REGISTRATION_FORM = 40;

    /**
     * When score field is invalid
     */
    public const INVALID_SCORE = 50;

    /**
     * When Google ReCAPTCHA site_key field is invalid
     */
    public const INVALID_SITE_KEY = 60;

    /**
     * When Google ReCAPTCHA secret_key field is invalid
     */
    public const INVALID_SECRET_KEY = 70;

    /**
     * When v2_type field is invalid
     */
    public const INVALID_V2_TYPE = 80;

    /**
     * When version field is invalid
     */
    public const INVALID_VERSION = 90;
}