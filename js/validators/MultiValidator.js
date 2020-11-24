import {ValidationResponse, Validator} from '../validator';

export class MultiValidator extends Validator
{
  _validators = [];

  _configure(config)
  {
    this._validators = config.validators;
  }

  validate(value)
  {
    let response = ValidationResponse.success();
    this._validators.forEach(
      obj =>
      {
        const v = Validator.fromObject(obj);
        response.combine(v.validate(value));
      }
    );
    return response;
  }
}
