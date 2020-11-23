import {ValidationResponse, Validator} from '../validator';

export class RequiredValidator extends Validator
{
  _expect = null;

  _configure(config)
  {
    this._expect = config.expect;
  }

  validate(ele)
  {
    if('checked' in ele)
    {
      if(!ele.checked)
      {
        return ValidationResponse.error(ele, ['required']);
      }
    }
    else if('value' in ele && ele.value.length <= 0)
    {
      return ValidationResponse.error(ele, ['required']);
    }
    return ValidationResponse.success(ele);
  }
}
