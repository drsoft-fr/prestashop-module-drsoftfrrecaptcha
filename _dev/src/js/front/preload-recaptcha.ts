import { IReCaptchaCfg, IReCaptchaInstance } from '@src/types/recaptchaV3'

if (typeof window.grecaptcha === 'undefined') {
  window.grecaptcha = {} as IReCaptchaInstance
}

window.grecaptcha.ready = function (callback: CallableFunction): void {
  if (typeof window.grecaptcha === 'undefined') {
    const c = '___grecaptcha_cfg'

    window[c] = window[c] || ({} as IReCaptchaCfg)
    ;(window[c]['fns'] = window[c]['fns'] || []).push(callback)
  } else {
    callback()
  }
}
