import base64 from 'base-64';

/**
 * @param {HTMLFormElement} form
 */
export function validateForm(form)
{
  const keyedErrors = {};
  form.querySelectorAll('[validate]').forEach(
    (ele) =>
    {
      const errors = validateField(ele);
      if(errors.length)
      {
        const eleName = ele.getAttribute('name');
        if(!keyedErrors[eleName])
        {
          keyedErrors[eleName] = [];
        }
        keyedErrors[eleName].push(...errors);
      }
    });
  return keyedErrors;
}

/**
 * @param {HTMLElement} ele
 * @return {Array}
 */
export function validateField(ele)
{
  const errors = [];
  const validateAttr = ele.getAttribute('validate');
  if(validateAttr)
  {
    const validator = JSON.parse(base64.decode(validateAttr));
    const validatorType = validator.t;
    const validatorConfig = validator.c;
    const value = 'value' in ele ? ele.value : null;
    switch(validatorType)
    {
      case 'ArrayKeysValidator':
        break;
      case 'ArrayValidator':
        break;
      case 'BoolValidator':
        if(typeof value !== 'boolean')
        {
          if(typeof value === 'string')
          {
            if(!(/true|false|0|1/.test(value.toLowerCase())))
            {
              errors.push('Invalid boolean value');
            }
          }
          else if(typeof value === 'number')
          {
            if(value !== 0 && value !== 1)
            {
              errors.push('Invalid boolean value');
            }
          }
          else if(!('checked' in ele))
          {
            errors.push('Invalid boolean value');
          }
        }
        break;
      case 'DecimalValidator':
        break;
      case 'EmailValidator':
        break;
      case 'ConstEnumValidator':
      case 'EnumValidator':
        if(validatorConfig.allowedValues.length)
        {
          const regex = new RegExp(validatorConfig.allowedValues.join('|'), !!validatorConfig.caseSensitive ? '' : 'i');
          if(validatorConfig.negate ^ !regex.test(value))
          {
            errors.push('not a valid value');
          }
        }
        else if(validatorConfig.negate ^ (value !== null && value !== ''))
        {
          errors.push('not a valid value');
        }
        break;
      case 'EqualValidator':
        if(value !== validatorConfig.expect)
        {
          errors.push('value does not match');
        }
        break;
      case 'NotEqualValidator':
        if(value === validatorConfig.expect)
        {
          errors.push('value must not match');
        }
        break;
      case 'IntegerValidator':
        break;
      case 'IPv4AddressValidator':
        break;
      case 'MultiValidator':
        break;
      case 'NullableValidator':
        break;
      case 'NumberValidator':
        break;
      case 'OptionalValidator':
        break;
      case 'PropertiesValidator':
        break;
      case 'RegexValidator':
        break;
      case 'RemoteValidator':
        break;
      case 'RequiredValidator':
        if('checked' in ele)
        {
          if(!ele.checked)
          {
            errors.push('required');
          }
        }
        else if(value.length <= 0)
        {
          errors.push('required');
        }
        break;
      case 'SchemaValidator':
        break;
      case 'StringValidator':
        if(typeof value !== 'string')
        {
          errors.push('not a valid value');
        }
        else if(value.length < validatorConfig.minLength)
        {
          errors.push('must be at least ' + validatorConfig.minLength + ' characters');
        }
        else if(validatorConfig.maxLength > 0 && value.length > validatorConfig.maxLength)
        {
          errors.push('must be no more than ' + validatorConfig.maxLength + ' characters');
        }
        break;
    }
  }
  return errors;
}
