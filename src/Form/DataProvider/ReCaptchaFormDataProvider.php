<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Form\DataProvider;

use Exception;
use DrSoftFr\Module\ReCaptcha\Data\Configuration\ReCaptchaConfiguration;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

/**
 * Class ReCaptchaFormDataProvider is in charge of accessing/saving the ReCAPTCHA settings in PrestaShop configuration
 */
final class ReCaptchaFormDataProvider implements FormDataProviderInterface
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

    /**
     * Persists form Data in Database and Filesystem.
     *
     * @param array $data
     *
     * @return array $errors if data can't persisted an array of errors messages
     *
     * @throws Exception
     */
    public function setData(array $data): array
    {
        return $this->configuration->updateConfiguration($data);
    }
}
