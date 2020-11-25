import {RegexValidator} from './RegexValidator';

export class IPv4Validator extends RegexValidator
{
  constructor(message = 'invalid IPv4 address')
  {
    super(
      '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
      message
    );
  }

  static deserialize(config)
  {
    return new this(config.message);
  }
}
