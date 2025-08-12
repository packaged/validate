import {ValidationResponse} from '../validator';
import {NumberValidator} from './NumberValidator';

export class IntegerValidator extends NumberValidator {
  validate(value) {
    const response = super.validate(value);
    if(response.errors.length === 0 && parseInt(value).toString() !== value.toString()) {
      if(this._dictionary && this._dictionary.invalid) {
        return ValidationResponse.error([this._dictionary.invalid]);
      }
      response.combine(ValidationResponse.error(['Must be an integer']));
    }
    return response;
  }
}
