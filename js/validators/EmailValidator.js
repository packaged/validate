import {RegexValidator} from './RegexValidator';

export class EmailValidator extends RegexValidator {
  constructor() {
    super(
      /^[_a-zA-Z0-9+\-]+(\.[_a-zA-Z0-9+\-]+)*@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*(\.[a-zA-Z]{2,})$/
    );
  }

  getDefaultErrorMessage() {
    return 'Invalid email address';
  }
}
