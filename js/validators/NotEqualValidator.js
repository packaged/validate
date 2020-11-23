import {ValidationResponse, Validator} from '../validator';

export class NotEqualValidator extends Validator
{
  _expect = null;

  _configure(config)
  {
    this._expect = config.expect;
  }

  validate(ele)
  {
    if('value' in ele && ele.value === this._expect)
    {
      return ValidationResponse.error(ele, ['value must not match']);
    }
    return ValidationResponse.success(ele);
  }
}
