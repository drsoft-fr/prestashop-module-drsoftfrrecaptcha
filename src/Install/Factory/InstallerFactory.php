<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Install\Factory;

use DrSoftFr\Module\ReCaptcha\Data\Configuration\ReCaptchaConfiguration;
use DrSoftFr\Module\ReCaptcha\Install\Installer;
use PrestaShop\PrestaShop\Adapter\Configuration;

/**
 * The InstallerFactory class is responsible for creating instances of the Installer class.
 */
final class InstallerFactory
{
    /**
     * Creates a new instance of the Installer class.
     *
     * This method initializes a new Installer object by creating a new ReCaptchaConfiguration object
     * and passing in a new Configuration object.
     *
     * @return Installer A new instance of the Installer class.
     */
    public static function create(): Installer
    {
        return new Installer(
            new ReCaptchaConfiguration(
                new Configuration(),
            )
        );
    }
}
