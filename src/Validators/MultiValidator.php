<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IValidator;

class MultiValidator extends AbstractValidator
{
  protected $_validators;

  public function __construct(IValidator ...$validators)
  {
    $this->_validators = $validators;
  }

  protected function _doValidate($value): Generator
  {
    foreach($this->_validators as $validator)
    {
      foreach($validator->validate($value) as $error)
      {
        yield $error;
      }
    }
  }
}
