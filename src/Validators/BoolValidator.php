<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class BoolValidator extends AbstractSerializableValidator
{
  public static function deserialize($configuration): SerializableValidator
  {
    return new static();
  }

  public function serialize(): array
  {
    return [];
  }

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
