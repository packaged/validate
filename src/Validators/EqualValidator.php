<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;

class EqualValidator extends AbstractValidator
{
  protected $_expect;

  public function __construct($expect)
  {
    $this->_expect = $expect;
  }

  protected function _doValidate($value): Generator
  {
    if($value !== $this->_expect)
    {
      yield $this->_makeError('value does not match');
    }
  }
}
