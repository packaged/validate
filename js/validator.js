import base64 from 'base-64';

const _validators = new Map();

export class Validator
{
  /**
   * @param {string} encoded
   * @return {Validator}
   */
  static fromEncoded(encoded)
  {
    const decoded = JSON.parse(base64.decode(encoded));
    if(!_validators.has(decoded.t))
    {
      throw 'unrecognised type ' + decoded.t;
    }
    const c = _validators.get(decoded.t);
    const validator = new c();
    validator._configure(decoded.c);
    return validator;
  }

  _configure(config) { }

  /**
   * @param value
   * @param {HTMLElement} ele
   * @return {ValidationResponse}
   * @throws
   */
  validate(value, ele)
  {
    throw 'validate not implemented on ' + this.name;
  }
}

export class ValidationResponse
{
  element = null;
  errors = null;
  potentiallyValid = false;

  constructor(element, errors, potentiallyValid)
  {
    this.element = element;
    this.errors = errors;
    this.potentiallyValid = potentiallyValid;
  }

  static success(element)
  {
    return new ValidationResponse(element, [], true);
  }

  static potentiallyValid(element, errors = [])
  {
    return new ValidationResponse(element, errors, true);
  }

  static error(element, errors = [])
  {
    return new ValidationResponse(element, errors, false);
  }
}

/**
 * @param {ClassDecorator} validator
 */
export function addValidator(validator)
{
  _validators.set(validator.name, validator);
}

/**
 * @param {HTMLElement} ele
 * @return {Array}
 */
export function validateField(ele)
{
  const errors = [];
  const validateAttr = ele.getAttribute('validate');
  if(validateAttr)
  {
    const validator = Validator.fromEncoded(validateAttr);
    const value = 'value' in ele ? ele.value : null;
    return validator.validate(value, ele);
  }
  return errors;
}

/**
 * @param {HTMLFormElement} form
 */
export function validateForm(form)
{
  const keyedErrors = new Map();
  form.querySelectorAll('[validate]').forEach(
    (ele) =>
    {
      const response = validateField(ele);
      if(response.errors.length)
      {
        if(!keyedErrors.has(ele))
        {
          keyedErrors.set(ele, response);
        }
        else
        {
          const resp = keyedErrors.get(ele);
          resp.errors.push(response.errors);
          resp.potentiallyValid = resp.potentiallyValid && response.potentiallyValid;
        }
      }
    }
  );
  return keyedErrors;
}
