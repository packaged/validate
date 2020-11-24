import {RegexValidator} from './RegexValidator';

export class EmailValidator extends RegexValidator
{
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
}
