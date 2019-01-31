<?php
namespace Packaged\Validate\Validators;

class IPv4AddressValidator extends RegexValidator
{
  public function __construct()
  {
    parent::__construct(
      '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
      'invalid IPv4 address'
    );
  }
}
