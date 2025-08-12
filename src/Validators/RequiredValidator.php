<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class RequiredValidator extends AbstractSerializableValidator
{
  public const DICT_INVALID = 'invalid';

  protected $_dictionary = [
    self::DICT_INVALID => 'Required',
  ];

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
    if($value === null || $value === '')
    {
      yield $this->_makeError($this->getDictionary()[self::DICT_INVALID]);
    }
  }
}
