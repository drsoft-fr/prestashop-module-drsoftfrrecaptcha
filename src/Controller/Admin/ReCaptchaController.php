<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Controller\Admin;

use DrSoftFr\Module\ReCaptcha\Data\Configuration\ReCaptchaConfiguration;
use drsoftfrrecaptcha;
use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\ModuleActivated;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ReCaptchaController.
 *
 * @ModuleActivated(moduleName="drsoftfrrecaptcha", redirectRoute="admin_improve")
 */
final class ReCaptchaController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminDrSoftFrReCaptcha';

    /**
     * Renders the index page of the drSoft.fr ReCAPTCHA settings.
     *
     * @AdminSecurity(
     *     "is_granted(['read'], request.get('_legacy_controller'))",
     *     redirectRoute="admin_improve",
     *     message="Access denied."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $form = $this
            ->getReCaptchaFormHandler()
            ->getForm();

        return $this->render('@Modules/drsoftfrrecaptcha/views/templates/admin/index.html.twig', [
            'enableSidebar' => true,
            'form' => $form->createView(),
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
            'module' => $this->getModule(),
        ]);
    }

    /**
     * Reset setting
     *
     * @AdminSecurity(
     *     "is_granted('update', request.get('_legacy_controller'))",
     *     redirectRoute="admin_drsoft_fr_recaptcha_index",
     *     message="You do not have permission to reset this."
     * )
     *
     * @return RedirectResponse
     */
    public function resetAction(): RedirectResponse
    {
        try {
            $this
                ->getReCaptchaConfiguration()
                ->initConfiguration();

            $this->addFlash(
                'success',
                $this->trans(
                    'The default setting for ReCAPTCHA are reset.',
                    'Modules.Drsoftfrrecaptcha.Success'
                )
            );
        } catch (Throwable $t) {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot reset the setting. Throwable: #%code% - %message%',
                    'Modules.Drsoftfrrecaptcha.Error',
                    [
                        '%code%' => $t->getCode(),
                        '%message%' => $t->getMessage(),
                    ]
                )
            );
        }

        return $this->redirectToRoute('admin_drsoft_fr_recaptcha_index');
    }

    /**
     * Edit setting
     *
     * @AdminSecurity(
     *     "is_granted('update', request.get('_legacy_controller'))",
     *     redirectRoute="admin_drsoft_fr_recaptcha_index",
     *     message="You do not have permission to edit this."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function saveAction(Request $request): Response
    {
        try {
            $handler = $this->getReCaptchaFormHandler();

            $form = $handler->getForm();
            $form->handleRequest($request);

            if (!$form->isSubmitted()) {
                return $this->redirectToRoute('admin_drsoft_fr_recaptcha_index');
            }

            if (!$form->isValid()) {
                $this->addFlash(
                    'error',
                    $this->trans(
                        'The form is invalid.',
                        'Modules.Drsoftfrrecaptcha.Error'
                    )
                );

                return $this->redirectToRoute('admin_drsoft_fr_recaptcha_index');
            }

            $errors = $handler->save($form->getData());

            if (!empty($errors)) {
                $this->flashErrors($errors);
            } else {
                $this->addFlash(
                    'success',
                    $this->trans(
                        'Your setting for ReCAPTCHA are saved.',
                        'Modules.Drsoftfrrecaptcha.Success'
                    )
                );
            }

        } catch (Throwable $t) {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot save the setting. Throwable: #%code% - %message%',
                    'Modules.Drsoftfrrecaptcha.Error',
                    [
                        '%code%' => $t->getCode(),
                        '%message%' => $t->getMessage(),
                    ]
                )
            );
        }

        return $this->redirectToRoute('admin_drsoft_fr_recaptcha_index');
    }

    /**
     * @return drsoftfrrecaptcha|object
     */
    protected function getModule()
    {
        return $this->get('drsoft_fr.module.recaptcha.module');
    }

    /**
     * Get ReCAPTCHA configuration.
     *
     * @return ReCaptchaConfiguration
     */
    protected function getReCaptchaConfiguration(): ReCaptchaConfiguration
    {
        return $this->get('drsoft_fr.module.recaptcha.data.configuration.recaptcha_configuration');
    }

    /**
     * Get ReCAPTCHA form handler.
     *
     * @return FormHandlerInterface
     */
    protected function getReCaptchaFormHandler(): FormHandlerInterface
    {
        return $this->get('drsoft_fr.module.recaptcha.form.handler.recaptcha_form_handler');
    }
}
