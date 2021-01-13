import {ValidationResponse, Validator} from '../validator';

export class MultiValidator extends Validator
{
  constructor(validators)
  {
    super();
    this._validators = validators || [];
  }

  static deserialize(config)
  {
    return new this(config.validators);
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
