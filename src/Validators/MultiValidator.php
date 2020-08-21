<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\SerializableValidator;
use Packaged\Validate\Validation;

class MultiValidator extends AbstractSerializableValidator
{
  protected $_validators;

  public function __construct(IValidator ...$validators)
  {
    $this->_validators = $validators;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    $validators = [];
    foreach($configuration->validators as $obj)
    {
      $validators[] = Validation::fromJsonObject($obj);
    }
    return new static(...$validators);
  }

  public function serialize(): array
  {
    return ['validators' => $this->_validators];
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
