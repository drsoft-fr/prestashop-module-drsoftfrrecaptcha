<?php

declare(strict_types=1);

use DrSoftFr\Module\ReCaptcha\Config;
use DrSoftFr\Module\ReCaptcha\Controller\Admin\ReCaptchaController;
use DrSoftFr\Module\ReCaptcha\Controller\Hook\ActionFrontControllerSetMediaController;
use DrSoftFr\Module\ReCaptcha\Controller\Hook\ActionFrontControllerSetVariablesController;
use DrSoftFr\Module\ReCaptcha\Controller\Hook\DisplayHeaderController;
use DrSoftFr\Module\ReCaptcha\Install\Factory\InstallerFactory;
use DrSoftFr\Module\ReCaptcha\Install\Installer;

if (!defined('_PS_VERSION_') || !defined('_CAN_LOAD_FILES_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

/**
 * Class drsoftfrrecaptcha
 */
class drsoftfrrecaptcha extends Module
{
    /**
     * @var string $authorEmail Author email
     */
    public $authorEmail;

    /**
     * @var string $moduleGithubRepositoryUrl Module GitHub repository URL
     */
    public $moduleGithubRepositoryUrl;

    /**
     * @var string $moduleGithubIssuesUrl Module GitHub issues URL
     */
    public $moduleGithubIssuesUrl;

    /**
     * @var bool $isPsVersion8 Indicates whether the version of PrestaShop is 8 or not
     */
    public $isPsVersion8;

    public function __construct()
    {
        $this->author = 'drSoft.fr';
        $this->bootstrap = true;
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Drsoftfrrecaptcha.Admin');
        $this->dependencies = [];
        $this->description = $this->trans('PrestaShop module to use Google reCAPTCHA', [], 'Modules.Drsoftfrrecaptcha.Admin');
        $this->displayName = $this->trans('drSoft.fr reCAPTCHA', [], 'Modules.Drsoftfrrecaptcha.Admin');
        $this->name = 'drsoftfrrecaptcha';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.8',
            'max' => _PS_VERSION_
        ];
        $this->tab = 'front_office_features';
        $this->tabs = [
            [
                'class_name' => ReCaptchaController::TAB_CLASS_NAME,
                'icon' => 'security',
                'name' => 'ReCaptcha',
                'parent_class_name' => 'IMPROVE',
                'route_name' => 'admin_drsoft_fr_recaptcha_index',
                'visible' => true,
                'wording' => 'ReCaptcha',
                'wording_domain' => 'Modules.Drsoftfrrecaptcha.Admin',
            ],
        ];
        $this->version = '0.0.1';
        $this->authorEmail = 'contact@drsoft.fr';
        $this->moduleGithubRepositoryUrl = 'https://github.com/drsoft-fr/prestashop-module-drsoftfrrecaptcha';
        $this->moduleGithubIssuesUrl = 'https://github.com/drsoft-fr/prestashop-module-drsoftfrrecaptcha/issues';
        $this->isPsVersion8 = (bool)version_compare(_PS_VERSION_, '8.0', '>=');

        parent::__construct();
    }

    /**
     * Disables the module.
     *
     * @param bool $force_all Whether to disable all instances of the module, even if they are currently enabled.
     *
     * @return bool Whether the module was disabled successfully.
     */
    public function disable($force_all = false)
    {
        $this->_clearCache('*');

        if (!parent::disable($force_all)) {
            return false;
        }

        return true;
    }

    /**
     * Enables the module by clearing the cache and calling the parent's enable method.
     *
     * @param bool $force_all Whether to force the enabling of all modules.
     *
     * @return bool True on successful enable, false otherwise.
     */
    public function enable($force_all = false)
    {
        $this->_clearCache('*');

        if (!parent::enable($force_all)) {
            return false;
        }

        return true;
    }

    /**
     * Redirects the admin user to the ReCaptcha controller in the admin panel.
     *
     * @return void
     */
    public function getContent(): void
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink(ReCaptchaController::TAB_CLASS_NAME)
        );
    }

    /**
     * @param array $p
     *
     * @return void
     */
    public function hookActionFrontControllerSetMedia(array $p = []): void
    {
        $file = _PS_MODULE_DIR_ . $this->name . '/' . $this->name . '.php';
        $controller = new ActionFrontControllerSetMediaController($this, $file, $this->_path, $p);

        $controller->run();
    }

    /**
     * @param array $p
     *
     * @return array
     */
    public function hookActionFrontControllerSetVariables(array $p = []): array
    {
        $file = _PS_MODULE_DIR_ . $this->name . '/' . $this->name . '.php';
        $controller = new ActionFrontControllerSetVariablesController($this, $file, $this->_path, $p);

        return $controller->run();
    }

    /**
     * @param array $p
     *
     * @return string
     */
    public function hookDisplayHeader(array $p = []): string
    {
        $file = _PS_MODULE_DIR_ . $this->name . '/' . $this->name . '.php';
        $controller = new DisplayHeaderController($this, $file, $this->_path, $p);

        return $controller->run();
    }

    /**
     * Installs the module
     *
     * @return bool Returns true if the installation is successful, false otherwise.
     */
    public function install(): bool
    {
        $this->_clearCache('*');

        if (!parent::install()) {
            return false;
        }

        $installer = InstallerFactory::create();

        if (!$installer->install($this)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the module is active
     *
     * @return bool Returns true if the module is active and the necessary settings are configured, false otherwise
     *
     * @throws Exception
     */
    public function isModuleActive(): bool
    {
        if (!$this->active) {
            return false;
        }

        /** @var array $settings */
        $settings = $this->get(Config::RECAPTCHA_PROVIDER_SERVICE);

        if (
            false === $settings['active'] ||
            empty($settings['site_key']) ||
            empty($settings['secret_key'])
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    /**
     * Uninstalls the module
     *
     * @return bool Returns true if uninstallation was successful, false otherwise
     *
     * @throws Exception
     */
    public function uninstall(): bool
    {
        $this->_clearCache('*');

        /** @var Installer $installer */
        $installer = $this->get(Config::INSTALLER_SERVICE);

        if (!$installer->uninstall($this)) {
            return false;
        }

        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }
}
