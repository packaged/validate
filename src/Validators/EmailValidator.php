<?php
namespace Packaged\Validate\Validators;

class EmailValidator extends RegexValidator
{
  public function __construct($message = 'invalid email address')
  {
    parent::__construct(
      '/^[_a-zA-Z0-9+\-]+(\.[_a-zA-Z0-9+\-]+)*@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*(\.[a-zA-Z]{2,})$/',
      $message
    );
  }
}
