<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Data\Configuration;

use Exception;
use PrestaShop\PrestaShop\Adapter\Configuration;

/**
 * Class ReCaptchaConfiguration
 *
 * This class handles the configuration ReCAPTCHA for the application.
 */
final class ReCaptchaConfiguration
{
    const CONFIGURATION_KEYS = [
        'key_v3' => 'DRSOFT_FR_GOOGLE_PUBLIC_KEY_V3',
    ];

    const CONFIGURATION_DEFAULT_VALUES = [
        'key_v3' => '',
    ];

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get the configuration array.
     *
     * This method retrieves the configuration values from the `configuration` object and
     * builds an array using the keys defined in `CONFIGURATION_KEYS` constant.
     *
     * @return array The configuration array.
     */
    public function getConfiguration(): array
    {
        $configuration = [];

        foreach (self::CONFIGURATION_KEYS as $key => $value) {
            $configuration[$key] = $this->configuration->get($value, self::CONFIGURATION_DEFAULT_VALUES[$key]);
        }

        return $configuration;
    }

    /**
     * Initialize the configuration.
     *
     * This method initializes the configuration by updating the current configuration
     * with the default values defined in `CONFIGURATION_DEFAULT_VALUES` constant.
     * It updates the configuration using the `updateConfiguration` method.
     *
     * @return void
     *
     * @throws Exception
     */
    public function initConfiguration(): void
    {
        $this->updateConfiguration(self::CONFIGURATION_DEFAULT_VALUES);
    }

    /**
     * Update the configuration array.
     *
     * This method updates the configuration values in the `configuration` object using the provided
     * configuration array. It validates the configuration before updating and returns an empty array if
     * the configuration is invalid.
     *
     * @param array $configuration The configuration array to update.
     *
     * @return array if not empty, populated by validation errors
     *
     * @throws Exception
     */
    public function updateConfiguration(array $configuration): array
    {
        if (!$this->validateConfiguration($configuration)) {
            return [];
        }

        foreach (self::CONFIGURATION_KEYS as $key => $value) {
            $this->configuration->set($value, $configuration[$key]);
        }

        return [];
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function removeConfiguration(): void
    {
        foreach (self::CONFIGURATION_KEYS as $key) {
            $this->configuration->remove($key);
        }
    }

    /**
     * Ensure the parameters passed are valid.
     *
     * @param array $configuration
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        if (!isset(
            $configuration['key_v3']
        )) {
            return false;
        }

        if (
            !is_string($configuration['key_v3'])
        ) {
            return false;
        }

        return true;
    }
}
