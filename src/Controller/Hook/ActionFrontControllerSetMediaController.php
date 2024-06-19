<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Controller\Hook;

use AuthController;
use ContactController;
use DrSoftFr\Module\ReCaptcha\Config;
use DrSoftFr\PrestaShopModuleHelper\Controller\Hook\AbstractHookController;
use DrSoftFr\PrestaShopModuleHelper\Controller\Hook\HookControllerInterface;
use Exception;
use Module;
use RegistrationController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Throwable;

final class ActionFrontControllerSetMediaController extends AbstractHookController implements HookControllerInterface
{
    /**
     * @var Package
     */
    private $manifest;

    /**
     * @var array $settings
     */
    private $settings;

    /**
     * @param Module $module
     * @param string $file
     * @param string $path
     * @param array $props
     */
    public function __construct(
        Module $module,
        string $file,
        string $path,
        array  $props
    )
    {
        parent::__construct(
            $module,
            $file,
            $path,
            $props
        );

        $this->manifest = new Package(
            new JsonManifestVersionStrategy(
                _PS_MODULE_DIR_ . '/' . $this->module->name . '/views/.vite/manifest.json'
            )
        );
    }

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

            if (true === $this->settings['import_google_recaptcha_script']) {
                $this->getContext()->controller->registerJavascript(
                    'modules-' . $this->module->name . '-google-recaptcha',
                    "https://www.google.com/recaptcha/api.js?render={$this->settings['site_key']}",
                    [
                        'attributes' => 'async',
                        'server' => 'remote'
                    ]
                );
            }

            $this->getContext()->controller->registerJavascript(
                'modules-' . $this->module->name . '-preload-recaptcha',
                'modules/' . $this->module->name . '/views/' . $this->manifest->getUrl('src/js/front/preload-recaptcha.ts')['file']
            );

            $this->getContext()->controller->registerJavascript(
                'modules-' . $this->module->name . '-recaptcha-v3',
                'modules/' . $this->module->name . '/views/' . $this->manifest->getUrl('src/js/front/recaptcha-v3.ts')['file']
            );
        } catch (Throwable $t) {
            $this->handleException($t);
        }
    }
}
