<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\IDataSetValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\SerializableValidator;
use Packaged\Validate\Validation;
use stdClass;

class RemoteValidator extends AbstractSerializableValidator implements IDataSetValidator
{
  protected $_validators;
  protected $_field;

  public function __construct($field)
  {
    $this->_field = $field;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    $validator = new static($configuration->field);
    $validator->_validators = $configuration->validators;
    foreach($validator->_validators as $k => $v)
    {
      $validator->_validators[$k]->validator = Validation::fromJsonObject($v->validator);
      $validator->_validators[$k]->remoteValidator = Validation::fromJsonObject($v->remoteValidator);
    }
    return $validator;
  }

  public function serialize(): array
  {
    return [
      'field'      => $this->_field,
      'validators' => $this->_validators,
    ];
  }

  protected function _doValidate($values): Generator
  {
    if(!is_array($values))
    {
      return $this->_makeError('not a valid value for a dataset validator');
    }

    $secondary = [];
    foreach($this->_validators as $validator)
    {
      if($validator->remoteValidator->isValid($values[$validator->remoteField] ?? null))
      {
        if(!isset($secondary[$this->_field]))
        {
          $secondary[$this->_field] = [];
        }
        $secondary[$this->_field][] = $validator->validator;
      }
    }

    $fieldValue = $values[$this->_field] ?? null;
    foreach($secondary as $field => $validators)
    {
      foreach($validators as $validator)
      {
        /** @var IValidator $validator */
        foreach($validator->validate($fieldValue) as $error)
        {
          yield $error;
        }
      }
    }
  }

  //Validate field if remote validator passes
  public function addValidator(IValidator $validator, string $remoteField, IValidator $remoteValidator)
  {
    $config = new stdClass();
    $config->validator = $validator;
    $config->remoteField = $remoteField;
    $config->remoteValidator = $remoteValidator;
    $this->_validators[] = $config;
    return $this;
  }
}
