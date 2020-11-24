import {ValidationResponse} from '../validator';
import {RegexValidator} from './RegexValidator';

export class EmailValidator extends RegexValidator
{
  _pattern = null;
  _message = null;

  constructor(message = 'invalid email address')
  {
    super(
      /^[_a-zA-Z0-9+\-]+(\.[_a-zA-Z0-9+\-]+)*@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*(\.[a-zA-Z]{2,})$/,
      message
    );
  }

  static deserialize(config)
  {
    return new this(config.message);
  }

  validate(value)
  {
    const parts = /\/(.*)\/(.*)/.exec(this._pattern);
    const regex = new RegExp(parts[1], parts[2]);
    if(!regex.test(value))
    {
      return ValidationResponse.error([this._message]);
    }
    return ValidationResponse.success();
  }
}
