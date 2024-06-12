import {
  DrSoftFrReCaptchaObjectInterface,
  JsonResponseInterface,
  JsTextType,
  PrepareFormObjectReturnInterface,
} from '@src/types/app'
;((): void => {
  if (typeof window.prestashop.modules.drsoftfrrecaptcha === 'undefined') {
    return
  }

  /**
   * Represents the DrSoftFrReCaptchaObjectInterface object for the DrSoftFrReCaptcha module in PrestaShop.
   *
   * @type {Object} DrSoftFrReCaptchaObjectInterface
   *
   * @namespace prestashop.modules
   * @memberof window
   * @memberOf window.prestashop.modules
   *
   * @name props
   */
  const props: DrSoftFrReCaptchaObjectInterface =
    window.prestashop.modules.drsoftfrrecaptcha

  /**
   * A collection of text messages.
   *
   * @type {JsTextType}
   *
   * @property {string} error - An error message for registration failure.
   */
  const TEXT: JsTextType = {
    error: `Error during submission, please contact ${
      props.merchantEmail ? props.merchantEmail : 'us'
    } for further assistance.`,
  }

  /**
   * Adds reCAPTCHA attributes and an event listener to a button element.
   *
   * @param {HTMLButtonElement} btnElm - The button element to add reCAPTCHA attributes and event listener to.
   * @param {HTMLFormElement} formElm - The form element to handle on submit event.
   *
   * @returns {void} - This function does not return a value.
   */
  const addRecaptchaAndListener = (
    btnElm: HTMLButtonElement,
    formElm: HTMLFormElement,
  ): void => {
    addRecaptchaAttributes(btnElm)
    btnElm.addEventListener('click', (ev) => handleSubmit(ev, formElm))
  }

  /**
   * Add reCAPTCHA attributes to an HTML element.
   *
   * @param {HTMLElement} elm - The HTML element to add reCAPTCHA attributes to.
   *
   * @returns {void}
   */
  const addRecaptchaAttributes = (elm: HTMLElement): void => {
    const attr = {
      'data-sitekey': props.siteKey,
      'data-callback': 'handleSubmit',
      'data-action': 'submit',
      'data-form': 'submitForm',
    }

    Object.entries(attr).forEach(([key, value]) => {
      elm.setAttribute(key, value)
    })

    elm.classList.add('g-recaptcha')
  }

  /**
   * Function to get element by id and handle null check
   *
   * @param {string} identifier - The ID of the element to retrieve.
   *
   * @returns {HTMLElement|null}  - The element with the specified ID, or null if not found.
   *
   * @template T - The type of the element to retrieve, extending HTMLElement.
   */
  const getElementById = <T extends HTMLElement>(
    identifier: string,
  ): T | null => {
    const elm = document.getElementById(identifier) as T

    if (!elm) {
      console.error(`Element with ${identifier} not found`)

      return null
    }

    return elm
  }

  /**
   * Handles errors in a form submission.
   *
   * @param {HTMLFormElement} form - The form element.
   * @param {null|string} [message=null] - Optional error message.
   *
   * @returns {void} - A promise that resolves when error handling is complete.
   */
  const handleError = (
    form: HTMLFormElement,
    message: null | string = null,
  ): void => {
    const alertElms = document.getElementsByClassName('alert-danger')

    if (0 < alertElms.length) {
      return
    }

    const html = `<p class="alert alert-danger">${
      !message ? TEXT.error : message
    }</p>`

    form.insertAdjacentHTML('afterbegin', html)
  }

  /**
   * Submits a form after reCAPTCHA verification.
   *
   * @param {MouseEvent} ev - The click event triggered by submitting the form.
   * @param {HTMLFormElement} formElm - The form element to be submitted.
   *
   * @returns {void}
   */
  const handleSubmit = (ev: MouseEvent, formElm: HTMLFormElement): void => {
    grecaptcha.ready(async () => {
      ev.preventDefault()

      try {
        let token

        try {
          token = await grecaptcha.execute(props.siteKey, {
            action: 'submit',
          })
        } catch (error) {
          handleError(formElm)

          return
        }

        const response = await postForm(token)

        if (!response.success) {
          handleError(formElm, response.message)

          return
        }
      } catch (error) {
        console.error(error)
      }

      formElm.submit()
    })
  }

  /**
   * Initializes the texts by merging the provided texts with the default texts.
   *
   * @param {JsTextType} texts - The custom texts object.
   * @param {JsTextType} defaultTexts - The default texts object.
   *
   * @return {JsTextType} - The merged texts object.
   */
  const initializeTexts = (
    texts: JsTextType,
    defaultTexts: JsTextType,
  ): JsTextType => {
    return {
      error: texts.error ?? defaultTexts.error,
    }
  }

  /**
   * Perform a POST request with form data and return a JSON response.
   *
   * @param {string} token - The form data token.
   *
   * @returns {Promise<JsonResponseInterface>} - The JSON response object.
   */
  const postForm = async (token: string): Promise<JsonResponseInterface> => {
    try {
      const response = await fetch(
        props.moduleDrsoftfrrecaptchaVerifyReCaptchaV3Url,
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'token=' + token,
        },
      )

      return await response.json()
    } catch (error) {
      console.error(error)

      return { success: false, message: '' }
    }
  }

  /**
   * Prepare form based on the given type.
   *
   * @param {'contact' | 'login' | 'registration'} type - The type of the form. Possible values are 'contact', 'login', or 'registration'.
   *
   * @returns {void}
   *
   * @throws {Error} If there is an error during the preparation of the contact form.
   */
  const prepareForm = (type: 'contact' | 'login' | 'registration'): void => {
    const fns = {
      contact: prepareContactForm,
      login: prepareLoginForm,
      registration: prepareRegistrationForm,
    }
    const { btnElm, ...rest } = fns[type]()
    let { formElm } = rest

    if (!btnElm) {
      throw new Error(`${type} button not found`)
    }

    formElm = formElm || btnElm.closest('form')

    if (!formElm) {
      throw new Error(`${type} form not found`)
    }

    if ('contact' === type) {
      const hiddenElm = document.createElement('input')

      hiddenElm.setAttribute('type', 'hidden')
      hiddenElm.setAttribute('name', 'submitMessage')
      hiddenElm.setAttribute('value', '1')

      formElm.appendChild(hiddenElm)
    }

    addRecaptchaAndListener(btnElm as HTMLButtonElement, formElm)
  }

  /**
   * Prepares the contact form by retrieving and validating the necessary elements.
   *
   * @returns {PrepareFormObjectReturnInterface} An object containing the button element and the form element.
   *
   * @throws {Error} If there is an error during the preparation of the contact form.
   */
  const prepareContactForm = (): PrepareFormObjectReturnInterface => {
    const bodyElm = getElementById<HTMLBodyElement>('contact')

    if (!bodyElm) {
      throw new Error('Error during preparation of the contact form')
    }

    const btnElm = document.querySelector(
      'input[name="submitMessage"]',
    ) as HTMLButtonElement | null

    return { btnElm, formElm: null }
  }

  /**
   * Prepares the login form by retrieving necessary elements and validating their existence.
   *
   * @returns {PrepareFormObjectReturnInterface} - An object containing the login form button element and form element.
   *
   * @throws {Error} - If there was an error during the preparation of the login form.
   */
  const prepareLoginForm = (): PrepareFormObjectReturnInterface => {
    const bodyElm = getElementById<HTMLBodyElement>('authentication')
    const formElm = getElementById<HTMLFormElement>('login-form')
    const urlParams = new URLSearchParams(window.location.search)

    if (!bodyElm || !formElm || urlParams.has('create_account')) {
      throw new Error('Error during preparation of the login form')
    }

    const btnElm =
      (document.getElementById('submit-login') as HTMLButtonElement | null) ||
      (document.querySelector(
        '[data-link-action="sign-in"]',
      ) as HTMLButtonElement | null)

    return { btnElm, formElm }
  }

  /**
   * Prepares the registration form by retrieving the necessary elements from the DOM.
   *
   * @returns {PrepareFormObjectReturnInterface} - The object containing the registration form elements.
   *
   * @throws {Error} - If there is an error during preparation of the registration form.
   */
  const prepareRegistrationForm = (): PrepareFormObjectReturnInterface => {
    const bodyElm =
      (document.getElementById('authentication') as HTMLBodyElement | null) ||
      (document.getElementById('registration') as HTMLBodyElement | null)

    if (!bodyElm) {
      throw new Error('Error during preparation of the registration form')
    }

    const btnElm = document.querySelector(
      '[data-link-action="save-customer"]',
    ) as HTMLButtonElement | null

    return { btnElm, formElm: null }
  }

  /**
   * Runs the appropriate form preparation function based on the value of `props.formType`.
   *
   * @returns {void}
   */
  const run = (): void => {
    try {
      const types = ['contact', 'login', 'registration']

      if (!types.includes(props.formType)) {
        return
      }

      initializeTexts(props.text ?? {}, TEXT)
      prepareForm(props.formType as 'contact' | 'login' | 'registration')
    } catch (error) {
      console.error(error)
    }
  }

  document.addEventListener('DOMContentLoaded', run)
})()
