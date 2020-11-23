import {ValidationResponse, Validator} from '../validator';

export class EqualValidator extends Validator
{
  _expect = null;

  _configure(config)
  {
    this._expect = config.expect;
  }

  validate(value, ele)
  {
    if(value !== this._expect)
    {
      return ValidationResponse.error(ele, ['value does not match']);
    }
    return ValidationResponse.success(ele);
  }
}
