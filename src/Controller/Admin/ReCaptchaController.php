<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ReCaptcha\Controller\Admin;

use drsoftfrrecaptcha;
use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\ModuleActivated;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            ->getSettingFormHandler()
            ->getForm();

        return $this->render('@Modules/drsoftfrrecaptcha/views/templates/admin/index.html.twig', [
            'enableSidebar' => true,
            'form' => $form->createView(),
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
            'module' => $this->getModule(),
        ]);
    }

    /**
     * @return drsoftfrrecaptcha|object
     */
    protected function getModule()
    {
        return $this->get('drsoft_fr.module.recaptcha.module');
    }

    /**
     * Get setting form handler.
     *
     * @return FormHandlerInterface
     */
    protected function getSettingFormHandler(): FormHandlerInterface
    {
        return $this->get('drsoft_fr.module.recaptcha.form.handler.recaptcha_form_handler');
    }
}
