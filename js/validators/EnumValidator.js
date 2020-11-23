import {Validator} from '../validator';

export class EnumValidator extends Validator
{
  _allowedValues = [];
  _negate = false;
  _caseSensitive = true;

  _configure(config)
  {
    this._allowedValues = config.allowedValues || [];
    this._negate = !!config.negate;
    this._caseSensitive = !!config.caseSensitive;
  }

  validate(value, ele, isChanging = false)
  {
    if(this._allowedValues.length)
    {
      const regex = new RegExp(this._allowedValues.join('|'), !!this._caseSensitive ? '' : 'i');
      if(this._negate ^ !regex.test(value))
      {
        return ['not a valid value'];
      }
    }
    else if(this._negate ^ (value !== null && value !== ''))
    {
      return ['not a valid value'];
    }
    return [];
  }
}
