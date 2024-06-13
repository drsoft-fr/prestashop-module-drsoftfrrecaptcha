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

final class DisplayHeaderController extends AbstractHookController implements HookControllerInterface
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
        if (false === $this->settings['insert_google_recaptcha_preconnect_link']) {
            return false;
        }

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
     * Run the method.
     *
     * @return string The result of the method execution.
     */
    public function run(): string
    {
        $value = '';

        try {
            if (!$this->module->isModuleActive()) {
                return $value;
            }

            /** @var array $settings */
            $this->settings = $this->module->get(Config::RECAPTCHA_PROVIDER_SERVICE);

            if (!$this->isEnabled()) {
                return $value;
            }

            $value = $this->module->display($this->file, '/views/templates/hook/display_header.tpl');
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $value;
    }
}
