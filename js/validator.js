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
    return Validator.fromObject(JSON.parse(base64.decode(encoded)));
  }

  /**
   * @param {Object} obj
   * @return {Validator}
   */
  static fromObject(obj)
  {
    if(!_validators.has(obj.t))
    {
      throw 'unrecognised type ' + obj.t;
    }
    const c = _validators.get(obj.t);
    const validator = new c();
    validator._configure(obj.c);
    return validator;
  }

  _configure(config) { }

  /**
   * @param {HTMLElement} ele
   * @return {ValidationResponse}
   * @throws
   */
  validate(ele)
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
 * @return {ValidationResponse}
 */
export function validateField(ele)
{
  const validateAttr = ele.getAttribute('validate');
  if(validateAttr)
  {
    const validator = Validator.fromEncoded(validateAttr);
    return validator.validate(ele);
  }
  return ValidationResponse.success(ele);
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
