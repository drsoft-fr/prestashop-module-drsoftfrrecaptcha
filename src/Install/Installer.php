<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Install;

use DrSoftFr\Module\ReCaptcha\Data\Configuration\ReCaptchaConfiguration;
use Exception;
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
     * @param Module $module The module to install.
     *
     * @return bool True if the module is successfully installed.
     *
     * @throws Exception If an error occurs during the installation process.
     */
    public function install(Module $module): bool
    {
        if (!$this->registerHooks($module)) {
            throw new Exception('An error occurred when registering hooks for the module.');
        }

        $this->recaptchaConfiguration->initConfiguration();

        return true;
    }

    /**
     * Module's uninstallation entry point.
     *
     * @param Module $module The module to uninstall.
     *
     * @return bool True if the module is successfully uninstalled
     *
     * @throws Exception if an error occurs when deleting the module parameters.
     */
    public function uninstall(Module $module): bool
    {
        try {
            $this->recaptchaConfiguration->removeConfiguration();
        } catch (Throwable $t) {
            throw new Exception('An error occurred when deleting the module parameters.');
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
