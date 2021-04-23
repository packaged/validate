<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class RequiredValidator extends AbstractSerializableValidator
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
    if($value === null || $value === '' || $value === false)
    {
      yield $this->_makeError('required');
    }
  }
}
