<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IValidator;

class OptionalValidator extends AbstractValidator
{
  protected $_validator;
  protected $_allowEmpty = true;

  /**
   * @param IValidator $validator
   */
  public function __construct(IValidator $validator)
  {
    $this->_validator = $validator;
  }

  protected function _doValidate($value): Generator
  {
    if($value === null)
    {
      return;
    }

    if($this->_allowEmpty && empty($value))
    {
      return;
    }

    foreach($this->_validator->validate($value) as $error)
    {
      yield $error;
    }
  }
}
