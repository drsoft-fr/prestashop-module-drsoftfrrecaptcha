<?php

declare(strict_types=1);

use DrSoftFr\Module\ReCaptcha\Config;
use ReCaptcha\ReCaptcha;

/**
 * DrsoftfrrecaptchaValidationModuleFrontController
 *
 * This controller is responsible for validating the reCAPTCHA token and displaying the response for an AJAX request.
 */
final class DrsoftfrrecaptchaValidationModuleFrontController extends ModuleFrontController
{
    /**
     * @var null|string
     */
    private $token = null;

    /**
     * Process the POST request and validate the reCAPTCHA token.
     *
     * @return void
     */
    public function postProcess(): void
    {
        try {
            parent::postProcess();

            $this->token = Tools::getValue('token', null);

            if (empty($this->token)) {
                $this->sendErrorResponse('reCAPTCHA token does not exist.');
            }
        } catch (Throwable $t) {
            $this->sendErrorResponse('An error has occurred while updating your message.');
        }
    }

    /**
     * Displays the response for an AJAX request.
     *
     * @return void
     */
    public function displayAjax(): void
    {
        try {
            if (false === $this->module->isModuleActive()) {
                $this->sendErrorResponse('This feature is disabled.');
            }

            $this->verifyRecaptchaToken();
            $this->sendSuccessResponse();
        } catch (Throwable $t) {
            $this->sendErrorResponse('An error has occurred while updating your message.');
        }
    }

    /**
     * Redirects to the 404 error page.
     *
     * @return void
     */
    public function display(): void
    {
        Tools::redirect('/index.php?controller=404');
    }

    /**
     * Sends an error response for an AJAX request.
     *
     * @param string $messageKey The message key.
     * @param array $messageParams The message parameters.
     *
     * @return void
     */
    private function sendErrorResponse(string $messageKey, array $messageParams = []): void
    {
        http_response_code(400);

        $this->ajaxRender(json_encode([
            'success' => false,
            'message' => $this
                ->context
                ->getTranslator()
                ->trans(
                    $messageKey,
                    $messageParams,
                    'Modules.Drsoftfrrecaptcha.Error'
                ),
        ]));

        die;
    }

    /**
     * Sends a success response for an AJAX request.
     *
     * @return void
     */
    private function sendSuccessResponse(): void
    {
        $this->ajaxRender(json_encode([
            'success' => true,
        ]));
    }

    /**
     * Verifies the reCAPTCHA token.
     *
     * @return void
     *
     * @throws Exception
     *
     */
    private function verifyRecaptchaToken(): void
    {
        /** @var array $settings */
        $settings = $this->get(Config::RECAPTCHA_PROVIDER_SERVICE);
        /** @var string $secret */
        $secret = $settings['secret_key'];
        $recaptcha = new ReCaptcha($secret);
        $resp = $recaptcha
            ->setExpectedHostname(Tools::getServerName())
            ->setScoreThreshold($settings['score'])
            ->verify($this->token, Tools::getRemoteAddr());

        if (!$resp->isSuccess()) {
            $err = $resp->getErrorCodes() ? implode(', ', $resp->getErrorCodes()) : '';

            $this->sendErrorResponse(
                'You are robot please contact shop support for further assistance. reCAPTCHA verification failed. Error code: #code#',
                [
                    '#code#' => $err
                ]
            );
        }
    }
}
