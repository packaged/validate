<?php
namespace Packaged\Validate;

use Generator;

abstract class AbstractValidator implements IValidator, \JsonSerializable
{
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
    /** @noinspection PhpUnusedLocalVariableInspection */
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

  public function jsonSerialize()
  {
    return [];
  }

  public function getAttributeValue()
  {
    $js = $this->jsonSerialize();
    if($js)
    {
      return base64_encode(json_encode($js));
    }
    return null;
  }
}
