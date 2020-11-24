import {ValidationResponse, Validator} from '../validator';

export class RegexValidator extends Validator
{
  _pattern = null;
  _message = null;

  constructor(pattern, message = 'does not match regular expression')
  {
    super();
    this._pattern = pattern;
    this._message = message;
  }

  static deserialize(config)
  {
    return new this(config.pattern, config.message);
  }

  validate(value)
  {
    let regex = this._pattern;
    if(typeof regex === 'string')
    {
      const parts = /\/(.*)\/(.*)/.exec(regex);
      regex = new RegExp(parts[1], parts[2]);
    }

    if(!(regex instanceof RegExp))
    {
      return ValidationResponse.error(['not a valid regular expression']);
    }

    if(!regex.test(value))
    {
      return ValidationResponse.error([this._message]);
    }
    return ValidationResponse.success();
  }
}
