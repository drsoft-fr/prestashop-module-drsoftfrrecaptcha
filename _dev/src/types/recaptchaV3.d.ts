import { UnknownType } from '@src/types/app'

declare global {
  const grecaptcha: IReCaptchaInstance

  interface Window {
    grecaptcha: IReCaptchaInstance
    ___grecaptcha_cfg: IReCaptchaCfg
  }
}

/**
 * Represents the configuration for reCaptcha functionality.
 *
 * @interface
 */
export interface IReCaptchaCfg {
  fns: Array<CallableFunction>
  [key: string]: UnknownType
}

/**
 * Interface representing an instance of the Google reCAPTCHA.
 */
export interface IReCaptchaInstance {
  ready: (callback: () => void) => void
  execute: (siteKey: string, options: IExecuteOptions) => Promise<string>
  render: ((
    container: string | Element,
    parameters: IRenderParameters,
  ) => string) &
    ((parameters: IRenderParameters) => string)
  enterprise: Omit<IReCaptchaInstance, 'enterprise'>
}

/**
 * Interface representing the options for executing an action.
 */
export declare interface IExecuteOptions {
  action?: string
}

/**
 * Represents the parameters required for rendering the reCAPTCHA widget.
 */
export declare interface IRenderParameters {
  sitekey: string
  badge?: 'bottomright' | 'bottomleft' | 'inline'
  size?: 'invisible'
  tabindex?: number
}
