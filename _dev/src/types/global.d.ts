import { PrestashopWindowInterface, UnknownType } from '@src/types/app'

export {}

declare global {
  /**
   * Represents a browser window.
   *
   * @interface
   */
  interface Window {
    $: UnknownType
    prestashop: PrestashopWindowInterface
  }
}
