import base64 from 'base-64';

/**
 * @param {HTMLFormElement} form
 */
export function validateForm(form)
{
  let isValid = true;
  form.querySelectorAll('[validate]').forEach(
    (ele) =>
    {
      isValid = isValid && validateField(ele);
    });
  return isValid;
}

/**
 * @param {HTMLElement} ele
 * @return boolean
 */
export function validateField(ele)
{
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
        if(typeof value === 'boolean')
        {
          return true;
        }
        else if(typeof value === 'string')
        {
          return /true|false|0|1/.test(value.toLowerCase());
        }
        else if(typeof value === 'number')
        {
          return value === 0 || value === 1;
        }
        return 'checked' in ele;
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
            return false;
          }
        }
        else
        {
          if(validatorConfig.negate ^ (value !== null && value !== ''))
          {
            return false;
          }
        }
        break;
      case 'EqualValidator':
        break;
      case 'NotEqualValidator':
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
          return ele.checked;
        }
        return ele.hasOwnProperty('value') && ele.value.length > 0;
      case 'SchemaValidator':
        break;
      case 'StringValidator':
        if(!('value' in ele))
        {
          return false;
        }
        if(typeof ele.value !== 'string')
        {
          return false;
        }
        if(validatorConfig.minLength && ele.value.length < validatorConfig.minLength)
        {
          return false;
        }
        if(validatorConfig.maxLength && ele.value.length > validatorConfig.maxLength)
        {
          return false;
        }
        break;
    }
  }
  return true;
}
