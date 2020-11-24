import {ValidationResponse, Validator} from '../validator';

export class RequiredValidator extends Validator
{
  _expect = null;

  _configure(config)
  {
    this._expect = config.expect;
  }

  validate(value)
  {
    if(!value)
    {
      return ValidationResponse.error(['required']);
    }
    return ValidationResponse.success();
  }
}
