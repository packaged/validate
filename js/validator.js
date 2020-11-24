/**
 * @typedef {function(new: Validator)} ValidatorType
 */

/**
 * @type {Map<String, ValidatorType>}
 * @private
 */
const _validatorMap = new Map();

export class Validator
{
  /**
   * @param {Object} obj
   * @return {Validator}
   */
  static deserialize(obj)
  {
    if(!_validatorMap.has(obj.t))
    {
      throw 'unrecognised type ' + obj.t;
    }
    const c = _validatorMap.get(obj.t);
    const validator = new c();
    validator._configure(obj.c);
    return validator;
  }

  _configure(config) { }

  /**
   * @param value
   * @return {ValidationResponse}
   * @throws
   */
  validate(value)
  {
    throw 'validate not implemented on ' + this.name;
  }
}

export class ValidationResponse
{
  errors = [];
  potentiallyValid = false;

  constructor(errors, potentiallyValid)
  {
    this.errors = errors;
    this.potentiallyValid = potentiallyValid;
  }

  static success()
  {
    return new ValidationResponse([], true);
  }

  static potentiallyValid(errors = [])
  {
    return new ValidationResponse(errors, true);
  }

  static error(errors = [])
  {
    return new ValidationResponse(errors, false);
  }

  /**
   * @param {...ValidationResponse} responses
   */
  combine(...responses)
  {
    responses.forEach(
      r =>
      {
        if(r instanceof ValidationResponse)
        {
          this.errors.push(...r.errors);
          this.potentiallyValid = this.potentiallyValid && r.potentiallyValid;
        }
      }
    );
  }
}

/**
 * @param {ValidatorType} validator
 */
export function addValidator(validator)
{
  _validatorMap.set(validator.name, validator);
}
