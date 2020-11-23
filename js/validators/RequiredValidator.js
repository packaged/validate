import {Validator} from '../validator';

export class RequiredValidator extends Validator
{
  _expect = null;

  _configure(config)
  {
    this._expect = config.expect;
  }

  validate(value, ele)
  {
    if('checked' in ele)
    {
      if(!ele.checked)
      {
        return ['required'];
      }
    }
    else if(value.length <= 0)
    {
      return ['required'];
    }
    return [];
  }
}
