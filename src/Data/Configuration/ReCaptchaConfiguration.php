<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Data\Configuration;

use DrSoftFr\Module\ReCaptcha\Config;
use DrSoftFr\Module\ReCaptcha\Exception\ReCaptcha\ReCaptchaConstraintException;
use Exception;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use Throwable;

/**
 * Class ReCaptchaConfiguration
 *
 * This class handles the configuration ReCAPTCHA for the application.
 */
final class ReCaptchaConfiguration implements DataConfigurationInterface
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $errors = [];

        try {
            $this->validateConfiguration($configuration);

            foreach (self::CONFIGURATION_KEYS as $key => $value) {
                $this->configuration->set($value, $configuration[$key]);
            }
        } catch (Throwable $t) {
            $errors[] = [
                'key' => Config::createErrorMessage(__METHOD__, __LINE__, $t),
                'domain' => 'Modules.Drsoftfrrecaptcha.Error',
                'parameters' => [],
            ];
        }

        return $errors;
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
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function validateConfiguration(array $configuration): bool
    {
        $this->validateKeyV3($configuration);

        return true;
    }

    /**
     * Validates the 'key_v3' configuration value.
     *
     * @param array $configuration The configuration array.
     *
     * @return void
     *
     * @throws ReCaptchaConstraintException If the 'key_v3' value is not set or is not a string.
     */
    private function validateKeyV3(array $configuration): void
    {
        if (!isset($configuration['key_v3'])) {
            throw new ReCaptchaConstraintException(
                'empty key v3',
                ReCaptchaConstraintException::INVALID_KEY_V3
            );
        }

        if (!is_string($configuration['key_v3'])) {
            throw new ReCaptchaConstraintException(
                'invalid key v3',
                ReCaptchaConstraintException::INVALID_KEY_V3
            );
        }
    }
}
