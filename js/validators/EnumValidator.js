import {ValidationResponse, Validator} from '../validator';

export class EnumValidator extends Validator
{
  _allowedValues = [];
  _negate = false;
  _caseSensitive = true;

  constructor(allowedValues = [], caseSensitive = false, negate = false)
  {
    super();
    this._allowedValues = allowedValues;
    this._caseSensitive = caseSensitive;
    this._negate = negate;
  }

  static deserialize(config)
  {
    return new this(config.allowedValues || [], !!config.caseSensitive, !!config.negate);
  }

  validate(value)
  {
    if(this._allowedValues.length)
    {
      const regex = new RegExp(this._allowedValues.join('|'), !!this._caseSensitive ? '' : 'i');
      if(this._negate ^ !regex.test(value))
      {
        return ValidationResponse.error(['not a valid value']);
      }
    }
    else if(this._negate ^ (value !== null && value !== ''))
    {
      return ValidationResponse.error(['not a valid value']);
    }
    return ValidationResponse.success();
  }
}
