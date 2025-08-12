import {RegexValidator} from './RegexValidator';

export class IPv4Validator extends RegexValidator {
  constructor() {
    super(
      '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/'
    );
  }

  getDefaultErrorMessage() {
    return 'Invalid IPv4 address';
  }
}
