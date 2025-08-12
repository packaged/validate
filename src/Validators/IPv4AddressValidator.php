<?php
namespace Packaged\Validate\Validators;

use Packaged\Validate\SerializableValidator;

class IPv4AddressValidator extends RegexValidator
{
  public function __construct()
  {
    parent::__construct(
      '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
    );
    $this->_dictionary = [
      self::DICT_INVALID => 'Invalid IPv4 address',
    ];
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static();
  }
}
