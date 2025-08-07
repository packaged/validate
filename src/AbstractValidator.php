<?php
namespace Packaged\Validate;

use Generator;
use JsonSerializable;

abstract class AbstractValidator implements IValidator, JsonSerializable
{
  protected $_dictionary = [];

  protected function _makeError(string $message): ValidationException
  {
    return new ValidationException($message);
  }

  /**
   * @param $value
   *
   * @return Generator|ValidationException[]
   */
  abstract protected function _doValidate($value): Generator;

  public static function withDictionary(array $dictionary, ...$args): static
  {
    $validator = new static(...$args);
    $validator->setDictionary($dictionary);
    return $validator;
  }

  public function getDictionary(): array
  {
    return $this->_dictionary;
  }

  /**
   * @param array $dictionary
   */
  public function setDictionary(array $dictionary): void
  {
    $this->_dictionary = $dictionary;
  }

  public function validate($value): array
  {
    $errors = [];
    $gen = $this->_doValidate($value);
    foreach($gen as $error)
    {
      if($error)
      {
        $errors[] = $error;
      }
    }
    $error = $gen->getReturn();
    if($error)
    {
      $errors[] = $error;
    }
    return $errors;
  }

  public function assert($value)
  {
    $gen = $this->_doValidate($value);
    foreach($gen as $error)
    {
      if($error)
      {
        throw $error;
      }
    }
    $error = $gen->getReturn();
    if($error)
    {
      throw $error;
    }
  }

  public function isValid($value): bool
  {
    $gen = $this->_doValidate($value);
    foreach($gen as $error)
    {
      if($error)
      {
        return false;
      }
    }
    $error = $gen->getReturn();
    if($error)
    {
      return false;
    }
    return true;
  }

  #[\ReturnTypeWillChange]
  public function jsonSerialize(): array
  {
    return [];
  }
}
