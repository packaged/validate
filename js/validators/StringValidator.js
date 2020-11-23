import {ValidationResponse, Validator} from '../validator';

export class StringValidator extends Validator
{
  _minLength = null;
  _maxLength = null;

  _configure(config)
  {
    this._minLength = config.minLength;
    this._maxLength = config.maxLength;
  }

  validate(value, ele)
  {
    if(typeof value !== 'string')
    {
      return ValidationResponse.error(ele, ['not a valid value']);
    }

    if(this._minLength !== null && value.length < this._minLength)
    {
      return ValidationResponse.potentiallyValid(ele, ['must be at least ' + this._minLength + ' characters']);
    }

    if(this._maxLength > 0 && value.length > this._maxLength)
    {
      return ValidationResponse.error(ele, ['must be no more than ' + this._maxLength + ' characters']);
    }

    return ValidationResponse.success(ele);
  }
}
