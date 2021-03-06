<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class EqualValidator extends AbstractSerializableValidator
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

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->expect);
  }

  public function serialize(): array
  {
    return [
      'expect' => $this->_expect,
    ];
  }

}
