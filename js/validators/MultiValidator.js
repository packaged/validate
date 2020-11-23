import {combineValidationResponse, ValidationResponse, Validator} from '../validator';

export class MultiValidator extends Validator
{
  _validators = [];

  _configure(config)
  {
    this._validators = config.validators;
  }

  validate(ele)
  {
    let response = ValidationResponse.success(ele);
    this._validators.forEach(
      obj =>
      {
        const v = Validator.fromObject(obj);
        response = combineValidationResponse(ele, response, v.validate(ele));
      });
    return response;
  }
}
