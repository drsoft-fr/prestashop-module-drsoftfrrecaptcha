/**
 * A generic class representing a configurable object with dynamic properties.
 */
type ConfigurableObjectType = Record<string, unknown>

/**
 * Represents the configuration object for DrSoftFrReCaptcha module.
 *
 * @interface
 */
interface DrSoftFrReCaptchaObjectInterface {
  formType: string
  moduleDrsoftfrrecaptchaVerifyReCaptchaV3Url: string
  siteKey: string
}

/**
 * Represents a ModulesObjectInterface interface.
 *
 * @interface
 */
interface ModulesObjectInterface {
  drsoftfrrecaptcha?: DrSoftFrReCaptchaObjectInterface
  [propName: string]: UnknownType
}

/**
 * Type constant for unknown types.
 */
type UnknownType = unknown

/**
 * Represents a Prestashop window object.
 *
 * @interface
 */
interface PrestashopWindowInterface {
  cart: ConfigurableObjectType
  currency: ConfigurableObjectType
  customer: ConfigurableObjectType
  language: ConfigurableObjectType
  page: ConfigurableObjectType
  shop: ConfigurableObjectType
  urls: ConfigurableObjectType
  configuration: ConfigurableObjectType
  field_required: Array<UnknownType>
  breadcrumb: ConfigurableObjectType
  link: ConfigurableObjectType
  time: number
  static_token: string
  token: string
  debug: boolean
  modules: ModulesObjectInterface
  selectors: ConfigurableObjectType
  _events: ConfigurableObjectType
  _eventsCount: number
  themeSelectors: ConfigurableObjectType
  responsive: ConfigurableObjectType
  [propName: string]: UnknownType
}

/**
 * Interface for JSON response objects.
 *
 * @interface
 */
interface JsonResponseInterface {
  success: boolean
  message: string
}

export { JsonResponseInterface, PrestashopWindowInterface, type UnknownType }
