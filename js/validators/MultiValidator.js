import {Validator} from '../validator';

export class MultiValidator extends Validator
{
  _validators = [];

  _configure(config)
  {
    this._validators = config.validators;
  }

  validate(ele)
  {
    this._validators.forEach(
      v =>
      {
        const validator = MultiValidator.fromObject(v);
        validator.validate(ele);
      });
  }
}
