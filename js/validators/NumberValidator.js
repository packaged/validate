import {ValidationResponse, Validator} from '../validator';

export class NumberValidator extends Validator
{
  _minValue = null;
  _maxValue = null;

  constructor(minValue = null, maxValue = null)
  {
    super();
    if((maxValue !== null) && (minValue !== null) && (maxValue < minValue))
    {
      throw 'maxValue must be greater than or equal to minValue';
    }
    this._minValue = minValue;
    this._maxValue = maxValue;
  }

  static deserialize(config)
  {
    return new this(config.minValue, config.maxValue);
  }

  validate(value)
  {
    if(!/^[0-9.]+$/.test(value))
    {
      return ValidationResponse.error(['must be a number']);
    }
    else if((this._minValue !== null) && (value < this._minValue))
    {
      return ValidationResponse.potentiallyValid([`must be more than ${this._minValue}`]);
    }
    else if((this._maxValue !== null) && (value > this._maxValue))
    {
      return ValidationResponse.error([`must be less than ${this._maxValue}`]);
    }

    return ValidationResponse.success();
  }
}
