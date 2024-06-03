<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Controller\Hook;

use AuthController;
use ContactController;
use DrSoftFr\Module\ReCaptcha\Config;
use DrSoftFr\PrestaShopModuleHelper\Controller\Hook\AbstractHookController;
use DrSoftFr\PrestaShopModuleHelper\Controller\Hook\HookControllerInterface;
use Exception;
use RegistrationController;
use Throwable;

final class ActionFrontControllerSetMediaController extends AbstractHookController implements HookControllerInterface
{
    /**
     * @var array $settings
     */
    private $settings;

    /**
     * Handles an exception by logging an error message.
     *
     * @param Throwable $t The exception to handle.
     *
     * @return void
     */
    private function handleException(Throwable $t): void
    {
        $errorMessage = Config::createErrorMessage(__METHOD__, __LINE__, $t);

        $this->logger->error($errorMessage, [
            'error_code' => $t->getCode(),
            'object_type' => null,
            'object_id' => null,
            'allow_duplicate' => false,
        ]);
    }

    /**
     * Checks if the feature is enabled for the current context.
     *
     * @return bool Returns true if the feature is enabled, false otherwise.
     *
     * @throws Exception
     */
    private function isEnabled(): bool
    {
        if ($this->getContext()->controller instanceof ContactController) {
            return $this->settings['activated_on_contact_form'];
        }

        if ($this->getContext()->controller instanceof AuthController) {
            return $this->settings['activated_on_login_form'];
        }

        if ($this->module->isPsVersion8) {
            if ($this->getContext()->controller instanceof RegistrationController) {
                return $this->settings['activated_on_registration_form'];
            }
        } else {
            if ($this->getContext()->controller instanceof AuthController) {
                return $this->settings['activated_on_registration_form'];
            }
        }

        return false;
    }

    /**
     * Runs the application.
     *
     * This function checks if the module is active and if the reCAPTCHA is enabled.
     * If both conditions are met, it registers the necessary JavaScript for the Google reCAPTCHA API.
     *
     * @return void
     */
    public function run(): void
    {
        try {
            if (!$this->module->isModuleActive()) {
                return;
            }

            /** @var array $settings */
            $this->settings = $this->module->get(Config::RECAPTCHA_PROVIDER_SERVICE);

            if (!$this->isEnabled()) {
                return;
            }

            $this->getContext()->controller->registerJavascript(
                'modules-' . $this->module->name . 'google-recaptcha',
                "https://www.google.com/recaptcha/api.js?render={$this->settings['site_key']}",
                [
                    'attributes' => 'async',
                    'server' => 'remote'
                ]
            );
        } catch (Throwable $t) {
            $this->handleException($t);
        }
    }
}
