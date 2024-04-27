<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Form;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;

/**
 * Class ReCaptchaType
 *
 * This class represents a form type for managing settings.
 * It extends the TranslatorAwareType class for translation support.
 */
final class ReCaptchaType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('key_v3', SwitchType::class, [
                'label' => $this->trans(
                    'Google ReCAPTCHA public key v3?',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
            ]);
    }
}
