<?php
namespace Packaged\Validate\Validators;

class EmailValidator extends RegexValidator
{
  public function __construct()
  {
    parent::__construct(
      '/^[_a-zA-Z0-9+\-]+(\.[_a-zA-Z0-9+\-]+)*@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*(\.[a-zA-Z]{2,})$/',
      'invalid email address'
    );
  }
}
