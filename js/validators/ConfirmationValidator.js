import {DataSetValidator, ValidationResponse} from '../validator.js';

export class ConfirmationValidator extends DataSetValidator {
  constructor(field) {
    super();
    this._field = field;
  }

  static deserialize(config) {
    return new this(config.field);
  }

  validate(value) {
    const data = this.getData();
    const compare = data.hasOwnProperty(this._field) ? data[this._field] : null;
    if(compare !== value) {
      if(compare?.substr(0, value?.length) === value) {
        if(this._dictionary && this._dictionary.invalid) {
          return ValidationResponse.potentiallyValid([this._dictionary.invalid]);
        }
        return ValidationResponse.potentiallyValid(['value does not match']);
      }
      if(this._dictionary && this._dictionary.invalid) {
        return ValidationResponse.error([this._dictionary.invalid]);
      }
      return ValidationResponse.error(['value does not match']);
    }
    return ValidationResponse.success();
  }
}
