<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\SerializableValidator;

class OptionalValidator extends AbstractSerializableValidator
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

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->validator);
  }

  public function serialize(): array
  {
    return [
      'validator' => $this->_validator,
    ];
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

  /**
   * @return IValidator
   */
  public function getValidator(): IValidator
  {
    return $this->_validator;
  }
}
