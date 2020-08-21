<?php
namespace Packaged\Validate\Validators;

use Packaged\Validate\SerializableValidator;

class EmailValidator extends RegexValidator
{
  public function __construct($message = 'invalid email address')
  {
    parent::__construct(
      '/^[_a-zA-Z0-9+\-]+(\.[_a-zA-Z0-9+\-]+)*@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*(\.[a-zA-Z]{2,})$/',
      $message
    );
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->message);
  }

  public function serialize(): array
  {
    return [
      'message' => $this->_message,
    ];
  }
}
