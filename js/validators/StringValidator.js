import {Validator} from '../validator';

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
      return ['not a valid value'];
    }

    if(this._minLength !== null && value.length < this._minLength)
    {
      return ['must be at least ' + this._minLength + ' characters'];
    }

    if(this._maxLength > 0 && value.length > this._maxLength)
    {
      return ['must be no more than ' + this._maxLength + ' characters'];
    }

    return [];
  }
}
