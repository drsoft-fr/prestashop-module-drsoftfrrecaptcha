<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Install;

use DrSoftFr\Module\ReCaptcha\Data\Configuration\ReCaptchaConfiguration;
use Module;
use Throwable;

/**
 * Class responsible for modifications needed during installation/uninstallation of the module.
 */
final class Installer
{
    const HOOKS = [
        'actionFrontControllerSetMedia',
        'actionFrontControllerSetVariables',
        'displayHeader',
    ];

    /**
     * @var ReCaptchaConfiguration
     */
    private $recaptchaConfiguration;

    /**
     * @param ReCaptchaConfiguration $recaptchaConfiguration
     */
    public function __construct(
        ReCaptchaConfiguration $recaptchaConfiguration
    )
    {
        $this->recaptchaConfiguration = $recaptchaConfiguration;
    }

    /**
     * Module's installation entry point.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function install(Module $module): bool
    {
        try {
            if (!$this->registerHooks($module)) {
                return false;
            }

            $this->recaptchaConfiguration->initConfiguration();
        } catch (Throwable $t) {
            return false;
        }

        return true;
    }

    /**
     * Module's uninstallation entry point.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function uninstall(Module $module): bool
    {
        try {
            $this->recaptchaConfiguration->removeConfiguration();
        } catch (Throwable $t) {
            return false;
        }

        return true;
    }

    /**
     * Register hooks for the module.
     *
     * @param Module $module
     *
     * @return bool
     */
    private function registerHooks(Module $module): bool
    {
        return (bool)$module->registerHook(self::HOOKS);
    }

    /**
     * @return ReCaptchaConfiguration
     */
    public function getReCaptchaConfiguration(): ReCaptchaConfiguration
    {
        return $this->recaptchaConfiguration;
    }
}
