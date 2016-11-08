<?php
namespace Packaged\Validate;

class IPv4AddressValidator extends RegexValidator
{
  public function __construct()
  {
    parent::__construct(
      '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/'
    );
  }

  public function validate($value)
  {
    $result = parent::validate($value);
    if(!$result)
    {
      $this->_setLastError('invalid IPv4 address');
    }
    return $result;
  }
}
