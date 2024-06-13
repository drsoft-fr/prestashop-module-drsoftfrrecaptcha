<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Form;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;
use PrestaShop\PrestaShop\Core\Domain\ValueObject\Email as EmployeeEmail;
use PrestaShopBundle\Form\Admin\Type\EmailType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
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
            ->add('import_google_recaptcha_script', SwitchType::class, [
                'empty_data' => true,
                'label' => $this->trans(
                    'Enable import of Google ReCAPTCHA scripts?',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => true,
            ])
            ->add('insert_google_recaptcha_preconnect_link', SwitchType::class, [
                'empty_data' => true,
                'label' => $this->trans(
                    'Insert Google ReCAPTCHA preconnect links?',
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
            ->add('secret_key', PasswordType::class, [
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
            ->add('merchant_email', EmailType::class, [
                'constraints' => [
                    $this->getLengthConstraint(),
                    new CleanHtml(),
                    new Email([
                        'message' => $this->trans(
                            '%s is invalid.',
                            'Admin.Notifications.Error'
                        ),
                    ]),
                ],
                'empty_data' => '',
                'label' => $this->trans(
                    'Merchant email address',
                    'Modules.Drsoftfrrecaptcha.Admin'
                ),
                'required' => false,
            ]);
    }

    /**
     * Returns a Length constraint object for validating the length of a field.
     *
     * @return Length The Length constraint object.
     */
    private function getLengthConstraint(): Length
    {
        $options = [
            'max' => EmployeeEmail::MAX_LENGTH,
            'maxMessage' => $this->trans(
                'This field cannot be longer than %limit% characters',
                'Admin.Notifications.Error',
                ['%limit%' => EmployeeEmail::MAX_LENGTH]
            ),
        ];

        return new Length($options);
    }
}
