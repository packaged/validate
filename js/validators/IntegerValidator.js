import {ValidationResponse} from '../validator';
import {NumberValidator} from './NumberValidator';

export class IntegerValidator extends NumberValidator
{
  validate(value)
  {
    const response = super.validate(value);
    if(response.errors.length === 0 && parseInt(value).toString() !== value.toString())
    {
      response.combine(ValidationResponse.error(['must be an integer']));
    }
    return response;
  }
}
