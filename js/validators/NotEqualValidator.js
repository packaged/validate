import {Validator} from '../validator';

export class NotEqualValidator extends Validator
{
  _expect = null;

  _configure(config)
  {
    this._expect = config.expect;
  }

  validate(value, ele)
  {
    if(value === this._expect)
    {
      return ['value must not match'];
    }
    return [];
  }
}
