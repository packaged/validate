import {ValidationResponse, Validator} from '../validator';

export class StringValidator extends Validator {
  constructor(minLength = 0, maxLength = 0)
  {
    super();
    this._minLength = minLength;
    this._maxLength = maxLength;
  }

  static deserialize(config)
  {
    return new this(config.minLength, config.maxLength);
  }

  validate(value)
  {
    if(typeof value !== 'string')
    {
      if(this._dictionary && this._dictionary.invalid)
      {
        return ValidationResponse.error([this._dictionary.invalid]);
      }

      return ValidationResponse.error(['Not a valid value']);
    }

    if(this._minLength > 0 && value.length < this._minLength)
    {
      if(this._dictionary && this._dictionary.min)
      {
        return ValidationResponse.error([this._dictionary.min.replace('%s', this._minLength.toString())]);
      }
      return ValidationResponse.potentiallyValid(['Must be at least ' + this._minLength + ' characters']);
    }

    if(this._maxLength > 0 && value.length > this._maxLength)
    {
      if(this._dictionary && this._dictionary.max)
      {
        return ValidationResponse.error([this._dictionary.max.replace('%s', this._maxLength.toString())]);
      }
      return ValidationResponse.error(['Must be no more than ' + this._maxLength + ' characters']);
    }

    return ValidationResponse.success();
  }
}
