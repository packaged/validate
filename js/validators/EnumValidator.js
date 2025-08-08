import {ValidationResponse, Validator} from '../validator';

export class EnumValidator extends Validator {
  constructor(allowedValues = [], caseSensitive = false, negate = false) {
    super();
    this._allowedValues = allowedValues || [];
    this._caseSensitive = !!caseSensitive;
    this._negate = !!negate;
  }

  static deserialize(config) {
    return new this(config.allowedValues || [], !!config.caseSensitive, !!config.negate);
  }

  validate(value) {
    if(this._allowedValues.length) {
      const regex = new RegExp(this._allowedValues.join('|'), !!this._caseSensitive ? '' : 'i');
      if(this._negate ^ !regex.test(value)) {
        if(this._dictionary && this._dictionary.invalid) {
          return ValidationResponse.error([this._dictionary.invalid]);
        }
        return ValidationResponse.error(['not a valid value']);
      }
    }
    else if(this._negate ^ (value !== null && value !== '')) {
      if(this._dictionary && this._dictionary.invalid) {
        return ValidationResponse.error([this._dictionary.invalid]);
      }
      return ValidationResponse.error(['not a valid value']);
    }
    return ValidationResponse.success();
  }
}
