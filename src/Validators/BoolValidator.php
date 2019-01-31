<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;

class BoolValidator extends AbstractValidator
{
  protected function _doValidate($value): Generator
  {
    if(is_string($value))
    {
      $result = in_array(strtolower($value), ['true', 'false', '1', '0']);
    }
    else if(is_int($value))
    {
      $result = in_array($value, [0, 1]);
    }
    else
    {
      $result = is_bool($value);
    }
    if(!$result)
    {
      yield $this->_makeError('Invalid boolean value');
    }
  }
}
