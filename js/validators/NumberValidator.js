import {ValidationResponse, Validator} from '../validator';

export class NumberValidator extends Validator {
  constructor(minValue = null, maxValue = null) {
    super();
    if((maxValue !== null) && (minValue !== null) && (maxValue < minValue)) {
      throw 'maxValue must be greater than or equal to minValue';
    }
    this._minValue = minValue;
    this._maxValue = maxValue;
  }

  static deserialize(config) {
    return new this(config.minValue, config.maxValue);
  }

  validate(value) {
    if(!/^[0-9.]+$/.test(value)) {
      if(this._dictionary && this._dictionary.invalid) {
        return ValidationResponse.error([this._dictionary.invalid]);
      }
      return ValidationResponse.error(['Must be a number']);
    }
    else if((this._minValue !== null) && (value < this._minValue)) {
      if(this._dictionary && this._dictionary.min) {
        return ValidationResponse.error([this._dictionary.min.replace('%s', this._minValue.toString())]);
      }
      return ValidationResponse.potentiallyValid([`Must be more than ${this._minValue}`]);
    }
    else if((this._maxValue !== null) && (value > this._maxValue)) {
      if(this._dictionary && this._dictionary.max) {
        return ValidationResponse.error([this._dictionary.max.replace('%s', this._maxValue.toString())]);
      }
      return ValidationResponse.error([`Must be less than ${this._maxValue}`]);
    }

    return ValidationResponse.success();
  }
}
