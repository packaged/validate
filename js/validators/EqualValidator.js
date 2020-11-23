import {Validator} from '../validator';

export class EqualValidator extends Validator
{
  _expect = null;

  _configure(config)
  {
    this._expect = config.expect;
  }

  validate(value, ele, isChanging = false)
  {
    if(value !== this._expect)
    {
      return ['value does not match'];
    }
    return [];
  }
}
