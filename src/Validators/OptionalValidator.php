<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IValidator;

class OptionalValidator extends AbstractValidator
{
  protected $_validator;

  public function __construct(IValidator $validator)
  {
    $this->_validator = $validator;
  }

  protected function _doValidate($value): Generator
  {
    foreach($this->_validator->validate($value) as $error)
    {
      yield $error;
    }
  }
}
