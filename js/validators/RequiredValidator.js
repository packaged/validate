import {ValidationResponse, Validator} from '../validator';

export class RequiredValidator extends Validator {
  validate(value) {
    if(value === undefined || value === null || value === '') {
      if(this._dictionary && this._dictionary.invalid) {
        return ValidationResponse.error([this._dictionary.invalid]);
      }
      return ValidationResponse.error(['Required']);
    }
    return ValidationResponse.success();
  }
}
