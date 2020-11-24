import {ValidationResponse, Validator} from '../validator';

export class BoolValidator extends Validator
{
  validate(value)
  {
    if(value === null || value === undefined)
    {
      return ValidationResponse.error(['Invalid boolean value']);
    }

    if(typeof value !== 'boolean')
    {
      if(typeof value === 'string')
      {
        if(!(/true|false|0|1/.test(value.toLowerCase())))
        {
          return ValidationResponse.error(['Invalid boolean value']);
        }
      }
      else if(typeof value === 'number')
      {
        if(value !== 0 && value !== 1)
        {
          return ValidationResponse.error(['Invalid boolean value']);
        }
      }
    }

    return ValidationResponse.success();
  }
}
