/**
 * @typedef {function(new: Validator), deserialize} ValidatorType
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
  static fromJsonObject(obj)
  {
    if(!_validatorMap.has(obj.t))
    {
      throw 'unrecognised type ' + obj.t;
    }
    const c = _validatorMap.get(obj.t);
    return c.deserialize(obj.c);
  }

  static deserialize(config)
  {
    return new this();
  }

  /**
   * @param value
   * @return {ValidationResponse}
   * @throws
   */
  validate(value)
  {
    throw 'validate not implemented on ' + this.constructor.name;
  }
}

export class DataSetValidator extends Validator
{
  _data = {};

  setData(data)
  {
    this._data = data;
  }

  getData()
  {
    return this._data;
  }
}

export class ValidationResponse
{
  constructor(errors, potentiallyValid)
  {
    this._errors = errors || [];
    this._potentiallyValid = potentiallyValid || false;
  }

  get errors()
  {
    return this._errors;
  }

  get potentiallyValid()
  {
    return this._potentiallyValid;
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
          this._errors.push(...r._errors);
          this._potentiallyValid = this._potentiallyValid && r._potentiallyValid;
        }
      }
    );
  }
}

/**
 * @param {string} name
 * @param {ValidatorType} validator
 */
export function addValidator(name, validator)
{
  _validatorMap.set(name, validator);
}
