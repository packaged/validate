import {ValidationResponse, Validator} from '../validator';

export class NotEqualValidator extends Validator
{
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
    if(value === this._expect)
    {
      return ValidationResponse.error(['value must not match']);
    }
    return ValidationResponse.success();
  }
}
