<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Form\ChoiceProvider;

/**
 * Provides choices for forms
 */
final class ReCaptchaChoiceProvider
{
    /**
     * @var array
     */
    private $v2types;

    /**
     * @var array
     */
    private $versionTypes;

    /**
     * Class constructor.
     *
     * @param array $v2types The V2 types.
     * @param array $versionTypes The version types.
     */
    public function __construct(
        array $v2types,
        array $versionTypes
    )
    {
        $this->v2types = $v2types;
        $this->versionTypes = $versionTypes;
    }

    /**
     * Get the choices for V2 types.
     *
     * @return array The V2 type choices.
     */
    public function getV2TypeChoices(): array
    {
        $a = [];

        foreach ($this->v2types as $key => $value) {
            $a[$value] = $key;
        }

        return $a;
    }

    /**
     * Get an array of version choices.
     *
     * @return array The array of version choices.
     */
    public function getVersionChoices(): array
    {
        $a = [];

        foreach ($this->versionTypes as $key => $value) {
            $a[$value] = $key;
        }

        return $a;
    }
}
