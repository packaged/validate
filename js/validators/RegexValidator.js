import {ValidationResponse, Validator} from '../validator';

export class RegexValidator extends Validator {
  constructor(pattern) {
    super();
    this._pattern = pattern;
  }

  static deserialize(config) {
    return new this(config.pattern);
  }

  validate(value) {
    let regex = this._pattern;
    if(typeof regex === 'string') {
      const parts = /\/(.*)\/(.*)/.exec(regex);
      if(!parts) {
        if(this._dictionary && this._dictionary.invalid) {
          return ValidationResponse.error([this._dictionary.invalid]);
        }
        return ValidationResponse.error([this.getDefaultErrorMessage()]);
      }
      regex = new RegExp(parts[1], parts[2]);
    }

    if(!(regex instanceof RegExp)) {
      if(this._dictionary && this._dictionary.invalid) {
        return ValidationResponse.error([this._dictionary.invalid]);
      }
      return ValidationResponse.error([this.getDefaultErrorMessage()]);
    }

    if(!regex.test(value)) {
      if(this._dictionary && this._dictionary.invalid) {
        return ValidationResponse.error([this._dictionary.invalid]);
      }
      return ValidationResponse.error([this.getDefaultErrorMessage()]);
    }
    return ValidationResponse.success();
  }

  getDefaultErrorMessage() {
    return 'not a valid regular expression';
  }
}
