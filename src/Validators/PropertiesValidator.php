<?php
namespace Packaged\Validate\Validators;

use Generator;

class PropertiesValidator extends ArrayKeysValidator
{
  public function __construct(array $requiredEntries, bool $allowUnknownEntries = false)
  {
    parent::__construct($requiredEntries, $allowUnknownEntries);
    $this->_dictionary[self::DICT_INVALID] = 'Must be an object';
  }

  protected function _doValidate($value): Generator
  {
    if(!is_object($value))
    {
      return $this->_makeError($this->getDictionary()[self::DICT_INVALID]);
    }

    foreach(parent::_doValidate(get_object_vars($value)) as $error)
    {
      yield $error;
    }
  }
}
