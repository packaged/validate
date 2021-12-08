<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\DatasetValidatorTrait;
use Packaged\Validate\IDataSetValidator;
use Packaged\Validate\SerializableValidator;

class ConfirmationValidator extends AbstractSerializableValidator implements IDataSetValidator
{
  use DatasetValidatorTrait;

  protected $_field;

  public function __construct($field)
  {
    $this->_field = $field;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->field);
  }

  public function serialize(): array
  {
    return [
      'field' => $this->_field,
    ];
  }

  protected function _doValidate($value): Generator
  {
    $compare = $this->_data[$this->_field] ?? null;
    if($compare !== $value)
    {
      yield $this->_makeError('value does not match');
    }
  }
}
