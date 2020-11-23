import {ValidationResponse, Validator} from '../validator';

export class BoolValidator extends Validator
{
  _configure(config)
  {
  }

  validate(ele)
  {
    const value = 'value' in ele ? ele.value : null;
    if(typeof value !== 'boolean')
    {
      if(typeof value === 'string')
      {
        if(!(/true|false|0|1/.test(value.toLowerCase())))
        {
          return ValidationResponse.error(ele, ['Invalid boolean value']);
        }
      }
      else if(typeof value === 'number')
      {
        if(value !== 0 && value !== 1)
        {
          return ValidationResponse.error(ele, ['Invalid boolean value']);
        }
      }
    }
    return ValidationResponse.success();
  }
}
