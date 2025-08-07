<?php

namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\ValidationException;

class FunctionValidator extends AbstractValidator
{
  public const DICT_INVALID = 'invalid';

  protected $_dictionary = [
    self::DICT_INVALID => 'Failed to validate',
  ];

  /**
   * @var callable
   */
  protected $_fn;

  /**
   * @param callable $function (string $value)
   */
  public function __construct(callable $function)
  {
    $this->_fn = $function;
  }

  protected function _doValidate($value): Generator
  {
    $fn = $this->_fn;

    $result = $fn($value);
    if($result instanceof Generator)
    {
      foreach($result as $error)
      {
        yield $error;
      }
    }
    else if($result instanceof ValidationException)
    {
      yield $result;
    }
    else if(is_string($result))
    {
      yield new ValidationException($result);
    }
    else if(is_bool($result) && !$result)
    {
      yield new ValidationException($this->getDictionary()[self::DICT_INVALID]);
    }
  }
}
