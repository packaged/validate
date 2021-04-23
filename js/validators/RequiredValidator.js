import {ValidationResponse, Validator} from '../validator';

export class RequiredValidator extends Validator
{
  validate(value)
  {
    if(value === undefined || value === null || value === '' || value === false)
    {
      return ValidationResponse.error(['required']);
    }
    return ValidationResponse.success();
  }
}
