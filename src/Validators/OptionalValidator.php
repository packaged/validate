<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IValidator;

class OptionalValidator extends AbstractValidator
{
  protected $_validator;
  protected $_allowEmptyString = false;

  /**
   * @param IValidator $validator
   * @param bool       $allowEmptyString If true then treat empty strings as nulls
   */
  public function __construct(IValidator $validator, $allowEmptyString = false)
  {
    $this->_validator = $validator;
    $this->_allowEmptyString = $allowEmptyString;
  }

  protected function _doValidate($value): Generator
  {
    if($value === null)
    {
      return;
    }
    if($this->_allowEmptyString && ($value === ''))
    {
      return;
    }

    foreach($this->_validator->validate($value) as $error)
    {
      yield $error;
    }
  }
}
