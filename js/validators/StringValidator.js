import {ValidationResponse, Validator} from '../validator';

export class StringValidator extends Validator
{
  _minLength = null;
  _maxLength = null;

  constructor(minLength = 0, maxLength = 0)
  {
    super();
    this._minLength = minLength;
    this._maxLength = maxLength;
  }

  _configure(config)
  {
    this._minLength = config.minLength;
    this._maxLength = config.maxLength;
  }

  validate(value)
  {
    if(typeof value !== 'string')
    {
      return ValidationResponse.error(['not a valid value']);
    }

    if(this._minLength > 0 && value.length < this._minLength)
    {
      return ValidationResponse.potentiallyValid(['must be at least ' + this._minLength + ' characters']);
    }

    if(this._maxLength > 0 && value.length > this._maxLength)
    {
      return ValidationResponse.error(['must be no more than ' + this._maxLength + ' characters']);
    }

    return ValidationResponse.success();
  }
}
