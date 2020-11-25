import {ValidationResponse} from '../validator';
import {NumberValidator} from './NumberValidator';

export class DecimalValidator extends NumberValidator
{
  _decimalPlaces = null;

  constructor(decimalPlaces = null, minValue = null, maxValue = null)
  {
    super();
    if((maxValue !== null) && (minValue !== null) && (maxValue < minValue))
    {
      throw 'maxValue must be greater than or equal to minValue';
    }
    this._decimalPlaces = decimalPlaces;
    this._minValue = minValue;
    this._maxValue = maxValue;
  }

  static deserialize(config)
  {
    return new this(config.decimalPlaces, config.minValue, config.maxValue);
  }

  validate(value)
  {
    const response = super.validate(value);

    const split = value.toString().split('.');
    if(split.length > 2)
    {
      response.combine(ValidationResponse.error(['invalid decimal value']));
    }
    else if(split.length === 2 && (this._decimalPlaces !== null && split[1].length > this._decimalPlaces))
    {
      response.combine(ValidationResponse.error([`must be a number to no more than ${this._decimalPlaces} decimal places`]));
    }
    return response;
  }
}
