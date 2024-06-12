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
        'active' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_ACTIVE',
        'activated_on_contact_form' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_ACTIVATED_ON_CONTACT_FORM',
        'activated_on_login_form' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_ACTIVATED_ON_LOGIN_FORM',
        'activated_on_registration_form' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_ACTIVATED_ON_REGISTRATION_FORM',
        'import_google_recaptcha_script' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_IMPORT_SCRIPT',
        'insert_google_recaptcha_preconnect_link' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_INSERT_PRECONNECT_LINK',
        'score' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_SCORE',
        'site_key' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_SITE_KEY',
        'secret_key' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_SECRET_KEY',
        'merchant_email' => 'DRSOFT_FR_GOOGLE_RECAPTCHA_MERCHANT_EMAIL',
    ];

    const CONFIGURATION_DEFAULT_VALUES = [
        'active' => false,
        'activated_on_contact_form' => false,
        'activated_on_login_form' => false,
        'activated_on_registration_form' => false,
        'import_google_recaptcha_script' => true,
        'insert_google_recaptcha_preconnect_link' => true,
        'score' => 1.0,
        'site_key' => '',
        'secret_key' => '',
        'merchant_email' => '',
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
            if (in_array(
                $key,
                [
                    'active',
                    'activated_on_contact_form',
                    'activated_on_login_form',
                    'activated_on_registration_form',
                    'import_google_recaptcha_script',
                    'insert_google_recaptcha_preconnect_link'
                ],
                true
            )) {
                $configuration[$key] = $this->configuration->getBoolean($value, self::CONFIGURATION_DEFAULT_VALUES[$key]);

                continue;
            }

            if ($key === 'score') {
                $configuration[$key] = $this->configuration->filter($value, self::CONFIGURATION_DEFAULT_VALUES[$key], \FILTER_VALIDATE_FLOAT);

                continue;
            }

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
        $this
            ->validateActive($configuration)
            ->validateActivatedOnContactForm($configuration)
            ->validateActivatedOnLoginForm($configuration)
            ->validateActivatedOnRegistrationForm($configuration)
            ->validateImportGoogleRecaptchaScript($configuration)
            ->validateInsertGoogleRecaptchaPreconnectLink($configuration)
            ->validateScore($configuration)
            ->validateSiteKey($configuration)
            ->validateSecretKey($configuration)
            ->validateMerchantEmail($configuration);

        return true;
    }

    /**
     * Validates the "active" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "active" field is missing or invalid.
     */
    private function validateActive(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['active'])) {
            throw new ReCaptchaConstraintException(
                'empty active field',
                ReCaptchaConstraintException::INVALID_ACTIVE
            );
        }

        if (!is_bool($configuration['active'])) {
            throw new ReCaptchaConstraintException(
                'invalid active field',
                ReCaptchaConstraintException::INVALID_ACTIVE
            );
        }

        return $this;
    }

    /**
     * Validates the "activated_on_contact_form" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "activated_on_contact_form" field is missing or invalid.
     */
    private function validateActivatedOnContactForm(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['activated_on_contact_form'])) {
            throw new ReCaptchaConstraintException(
                'empty activated_on_contact_form field',
                ReCaptchaConstraintException::INVALID_ACTIVATED_ON_CONTACT_FORM
            );
        }

        if (!is_bool($configuration['activated_on_contact_form'])) {
            throw new ReCaptchaConstraintException(
                'invalid activated_on_contact_form field',
                ReCaptchaConstraintException::INVALID_ACTIVATED_ON_CONTACT_FORM
            );
        }

        return $this;
    }

    /**
     * Validates the "activated_on_login_form" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "activated_on_login_form" field is missing or invalid.
     */
    private function validateActivatedOnLoginForm(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['activated_on_login_form'])) {
            throw new ReCaptchaConstraintException(
                'empty activated_on_login_form field',
                ReCaptchaConstraintException::INVALID_ACTIVATED_ON_LOGIN_FORM
            );
        }

        if (!is_bool($configuration['activated_on_login_form'])) {
            throw new ReCaptchaConstraintException(
                'invalid activated_on_login_form field',
                ReCaptchaConstraintException::INVALID_ACTIVATED_ON_LOGIN_FORM
            );
        }

        return $this;
    }

    /**
     * Validates the "activated_on_registration_form" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "activated_on_registration_form" field is missing or invalid.
     */
    private function validateActivatedOnRegistrationForm(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['activated_on_registration_form'])) {
            throw new ReCaptchaConstraintException(
                'empty activated_on_registration_form field',
                ReCaptchaConstraintException::INVALID_ACTIVATED_ON_REGISTRATION_FORM
            );
        }

        if (!is_bool($configuration['activated_on_registration_form'])) {
            throw new ReCaptchaConstraintException(
                'invalid activated_on_registration_form field',
                ReCaptchaConstraintException::INVALID_ACTIVATED_ON_REGISTRATION_FORM
            );
        }

        return $this;
    }

    /**
     * Validates the "import_google_recaptcha_script" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "import_google_recaptcha_script" field is missing or invalid.
     */
    private function validateImportGoogleRecaptchaScript(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['import_google_recaptcha_script'])) {
            throw new ReCaptchaConstraintException(
                'empty import_google_recaptcha_script field',
                ReCaptchaConstraintException::IMPORT_GOOGLE_RECAPTCHA_SCRIPT
            );
        }

        if (!is_bool($configuration['import_google_recaptcha_script'])) {
            throw new ReCaptchaConstraintException(
                'invalid import_google_recaptcha_script field',
                ReCaptchaConstraintException::IMPORT_GOOGLE_RECAPTCHA_SCRIPT
            );
        }

        return $this;
    }

    /**
     * Validates the "insert_google_recaptcha_preconnect_link" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "insert_google_recaptcha_preconnect_link" field is missing or invalid.
     */
    private function validateInsertGoogleRecaptchaPreconnectLink(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['insert_google_recaptcha_preconnect_link'])) {
            throw new ReCaptchaConstraintException(
                'empty insert_google_recaptcha_preconnect_link field',
                ReCaptchaConstraintException::INSERT_GOOGLE_RECAPTCHA_PRECONNECT_LINK
            );
        }

        if (!is_bool($configuration['insert_google_recaptcha_preconnect_link'])) {
            throw new ReCaptchaConstraintException(
                'invalid insert_google_recaptcha_preconnect_link field',
                ReCaptchaConstraintException::INSERT_GOOGLE_RECAPTCHA_PRECONNECT_LINK
            );
        }

        return $this;
    }

    /**
     * Validates the "score" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "score" field is missing or invalid.
     */
    private function validateScore(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['score'])) {
            throw new ReCaptchaConstraintException(
                'score field is not set',
                ReCaptchaConstraintException::INVALID_SCORE
            );
        }

        if (empty($configuration['score'])) {
            throw new ReCaptchaConstraintException(
                'empty score field',
                ReCaptchaConstraintException::INVALID_SCORE
            );
        }

        if (!is_float($configuration['score'])) {
            throw new ReCaptchaConstraintException(
                'invalid score field',
                ReCaptchaConstraintException::INVALID_SCORE
            );
        }

        return $this;
    }

    /**
     * Validates the "site_key" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "site_key" field is missing or invalid.
     */
    private function validateSiteKey(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['site_key'])) {
            throw new ReCaptchaConstraintException(
                'empty site_key field',
                ReCaptchaConstraintException::INVALID_SITE_KEY
            );
        }

        if (!is_string($configuration['site_key'])) {
            throw new ReCaptchaConstraintException(
                'invalid site_key field',
                ReCaptchaConstraintException::INVALID_SITE_KEY
            );
        }

        return $this;
    }

    /**
     * Validates the "secret_key" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return ReCaptchaConfiguration The ReCaptcha configuration object.
     *
     * @throws ReCaptchaConstraintException When the "secret_key" field is missing or invalid.
     */
    private function validateSecretKey(array $configuration): ReCaptchaConfiguration
    {
        if (!isset($configuration['secret_key'])) {
            throw new ReCaptchaConstraintException(
                'empty secret_key field',
                ReCaptchaConstraintException::INVALID_SECRET_KEY
            );
        }

        if (!is_string($configuration['secret_key'])) {
            throw new ReCaptchaConstraintException(
                'invalid secret_key field',
                ReCaptchaConstraintException::INVALID_SECRET_KEY
            );
        }

        return $this;
    }

    /**
     * Validates the "merchant_email" field of the ReCaptcha configuration.
     *
     * @param array $configuration The ReCaptcha configuration array.
     *
     * @return void
     *
     * @throws ReCaptchaConstraintException When the "merchant_email" field is missing or invalid.
     */
    private function validateMerchantEmail(array $configuration): void
    {
        if (!isset($configuration['merchant_email'])) {
            throw new ReCaptchaConstraintException(
                'empty merchant_email field',
                ReCaptchaConstraintException::INVALID_MERCHANT_EMAIL
            );
        }

        if (!is_string($configuration['merchant_email'])) {
            throw new ReCaptchaConstraintException(
                'invalid merchant_email field',
                ReCaptchaConstraintException::INVALID_MERCHANT_EMAIL
            );
        }
    }
}
