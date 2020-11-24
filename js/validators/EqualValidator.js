import {ValidationResponse, Validator} from '../validator';

export class EqualValidator extends Validator
{
  _expect = null;

  constructor(expect)
  {
    super();
    this._expect = expect;
  }

  static deserialize(config)
  {
    return new this(config.expect);
  }

  validate(value)
  {
    if(value !== this._expect)
    {
      return ValidationResponse.error(['value does not match']);
    }
    return ValidationResponse.success();
  }
}
