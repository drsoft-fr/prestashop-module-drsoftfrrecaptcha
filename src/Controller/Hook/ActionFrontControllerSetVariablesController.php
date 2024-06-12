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
use Tools;

/**
 * Class ActionFrontControllerSetVariablesController
 *
 * This controller handles setting variables for the front controller action.
 * It extends AbstractHookController and implements HookControllerInterface.
 */
final class ActionFrontControllerSetVariablesController extends AbstractHookController implements HookControllerInterface
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
     * Checks if the current module is enabled according to the context and settings.
     *
     * @return bool|string Returns the enabled form type ('contact', 'login', 'registration') if enabled, otherwise false.
     *
     * @throws Exception
     */
    private function isEnabled()
    {
        if ($this->getContext()->controller instanceof ContactController) {
            return $this->settings['activated_on_contact_form'] ? 'contact' : false;
        }

        if ($this->module->isPsVersion8) {
            if ($this->getContext()->controller instanceof RegistrationController) {
                return $this->settings['activated_on_registration_form'] ? 'registration' : false;
            }
        }

        if ($this->getContext()->controller instanceof AuthController) {
            if (true === Tools::isSubmit('create_account')) {
                return $this->settings['activated_on_registration_form'] ? 'registration' : false;
            }

            return $this->settings['activated_on_login_form'] ? 'login' : false;
        }

        return false;
    }

    /**
     * Runs the application and returns an array of values.
     *
     * @return array The values returned by the application.
     */
    public function run(): array
    {
        $values = [];

        try {
            if (!$this->module->isModuleActive()) {
                return $values;
            }

            /** @var array $settings */
            $this->settings = $this->module->get(Config::RECAPTCHA_PROVIDER_SERVICE);

            if (!$type = $this->isEnabled()) {
                return $values;
            }

            $values['merchantEmail'] = $this->settings['merchant_email'];
            $values['formType'] = $type;
            $values['siteKey'] = $this->settings['site_key'];
            $values['moduleDrsoftfrrecaptchaVerifyReCaptchaV3Url'] = $this
                ->getContext()
                ->link
                ->getModuleLink(
                    $this->module->name,
                    'validation',
                    ['ajax' => true]
                );
            $values['text'] = [
                'error' => $this
                    ->getContext()
                    ->getTranslator()
                    ->trans(
                        'Error during submission, please contact #email# for further assistance.',
                        [
                            '#email#' => !empty($this->settings['merchant_email']) ? $this->settings['merchant_email'] : 'us',
                        ],
                        'Modules.Drsoftfrrecaptcha.Shop'
                    )
            ];
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $values;
    }
}
