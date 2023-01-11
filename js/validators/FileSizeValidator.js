import {ValidationResponse, Validator} from '../validator';

export class FileSizeValidator extends Validator
{
  constructor(maxSize = null)
  {
    super();
    if(maxSize === null)
    {
      throw 'maxSize must be set';
    }
    this._maxSize = maxSize;
  }

  static deserialize(config)
  {
    return new this(config.maxSize);
  }

  validate(value)
  {
    if(value.hasOwnProperty('size') && value.size > (this._maxSize * 1024 * 1024))
    {
      return ValidationResponse.error(['File upload cannot be more than ' + this._maxSize + 'mb in size']);
    }

    return ValidationResponse.success();
  }
}
