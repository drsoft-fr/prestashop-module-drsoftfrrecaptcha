<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Data\Factory;

use DrSoftFr\Module\ReCaptcha\Data\Configuration\ReCaptchaConfiguration;

/**
 * Class ReCaptchaDataFactory is in charge of accessing the ReCAPTCHA settings in PrestaShop configuration
 */
final class ReCaptchaDataFactory
{
    /**
     * @var ReCaptchaConfiguration
     */
    private $configuration;

    /**
     * @param ReCaptchaConfiguration $configuration
     */
    public function __construct(
        ReCaptchaConfiguration $configuration
    )
    {
        $this->configuration = $configuration;
    }

    /**
     * @return array the form data as an associative array
     */
    public function getData(): array
    {
        return $this->configuration->getConfiguration();
    }
}
