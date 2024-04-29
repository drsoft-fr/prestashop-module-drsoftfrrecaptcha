<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Form;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ReCaptchaType
 *
 * This class represents a form type for managing settings.
 * It extends the TranslatorAwareType class for translation support.
 */
final class ReCaptchaType extends TranslatorAwareType
{
    /**
     * @var array
     */
    private $v2typeChoices;

    /**
     * @var array
     */
    private $versionChoices;

    /**
     * Class Constructor.
     *
     * @param TranslatorInterface $translator The translator interface instance.
     * @param array $locales The array of locales.
     * @param array $v2typeChoices The array of v2 type choices.
     * @param array $versionChoices The array of version choices.
     */
    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        array $v2typeChoices,
        array $versionChoices
    )
    {
        parent::__construct($translator, $locales);

        $this->v2typeChoices = $v2typeChoices;
        $this->versionChoices = $versionChoices;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', SwitchType::class, [
                'empty_data' => false,
                'label' => $this->trans(
                    'Active',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
            ])
            ->add('activated_on_contact_form', SwitchType::class, [
                'empty_data' => false,
                'label' => $this->trans(
                    'Activated on contact Form',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
            ])
            ->add('activated_on_login_form', SwitchType::class, [
                'empty_data' => false,
                'label' => $this->trans(
                    'Activated on login Form',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
            ])
            ->add('activated_on_registration_form', SwitchType::class, [
                'empty_data' => false,
                'label' => $this->trans(
                    'Activated on registration Form',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
            ])
            ->add('score', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => $this->trans(
                            'The %s field is required.',
                            'Admin.Notifications.Error',
                            [
                                sprintf(
                                    '"%s"',
                                    $this->trans(
                                        'Number of days added to the date of availability of the products.',
                                        'Modules.Drsoftfrrecaptcha.Admin'
                                    )
                                ),
                            ]
                        ),
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => $this->trans(
                            'This value should be greater than or equal to %value%',
                            'Admin.Notifications.Error',
                            [
                                '%value%' => 0,
                            ]
                        ),
                    ]),
                    new LessThanOrEqual([
                        'value' => 1,
                        'message' => $this->trans(
                            'This value should be less than or equal to %value%.',
                            'Admin.Notifications.Error',
                            [
                                '%value%' => 1
                            ]
                        ),
                    ]),
                ],
                'empty_data' => 1.0,
                'label' => $this->trans(
                    'Score',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
                'scale' => 1,
            ])
            ->add('site_key', TextType::class, [
                'constraints' => [
                    new CleanHtml(),
                ],
                'empty_data' => '',
                'label' => $this->trans(
                    'Site key',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => false,
            ])
            ->add('secret_key', TextType::class, [
                'constraints' => [
                    new CleanHtml(),
                ],
                'empty_data' => '',
                'label' => $this->trans(
                    'Secret key',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => false,
            ])
            ->add('v2_type', ChoiceType::class, [
                'choices' => $this->v2typeChoices,
                'expanded' => true,
                'label' => $this->trans(
                    'Type',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
            ])
            ->add('version', ChoiceType::class, [
                'choices' => $this->versionChoices,
                'expanded' => true,
                'label' => $this->trans(
                    'Version',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
            ]);
    }
}
