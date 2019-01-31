<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\ValidationException;

class ValidatorCollection extends AbstractValidator
{
  /**
   * @var IValidator[]
   */
  private $_validators = [];
  private $_requiredFields = true;
  private $_allowExtraFields = false;

  /**
   * ValidatorCollection constructor.
   *
   * @param array         $fieldValidators
   * @param bool|string[] $requiredFields   A list of fields that are required.
   *                                        If this is true then all fields are
   *                                        required. An empty array or false means
   *                                        all fields are optional
   * @param bool          $allowExtraFields If false then don't complain if the
   *                                        data contains unknown fields
   */
  public function __construct(array $fieldValidators = [], bool $requiredFields = true, bool $allowExtraFields = false)
  {
    $this->addFields($fieldValidators);
    $this->_requiredFields = $requiredFields;
    $this->_allowExtraFields = $allowExtraFields;
  }

  public function addField($name, $validator)
  {
    if(is_array($validator))
    {
      $this->_validators[$name] = new ValidatorCollection($validator);
    }
    else
    {
      $this->_validators[$name] = $validator;
    }
  }

  /**
   * @param AbstractValidator[] $validators
   *
   * @return ValidatorCollection
   */
  public function addFields($validators)
  {
    foreach($validators as $k => $v)
    {
      $this->addField($k, $v);
    }
    return $this;
  }

  private function _dataAsArray($data)
  {
    if(($data === null) || ($data === ""))
    {
      return [];
    }
    $arrayData = json_decode(json_encode($data), true);
    return $arrayData ?: [];
  }

  /**
   * @param array|object $data The data to validate
   *
   * @return Generator|ValidationException[]
   */
  protected function _doValidate($data): Generator
  {
    $hasYielded = false;
    $data = $this->_dataAsArray($data);

    $requiredFields = $this->_requiredFields;
    if($requiredFields)
    {
      if(!is_array($requiredFields))
      {
        $requiredFields = array_keys($this->_validators);
      }

      $missingFields = array_diff($requiredFields, array_keys($data));
      foreach($missingFields as $k => $field)
      {
        if(isset($this->_validators[$field])
          && ($this->_validators[$field] instanceof OptionalValidator)
        )
        {
          unset($missingFields[$k]);
        }
      }

      if(count($missingFields) > 0)
      {
        yield $this->_makeError('The following fields are required: ' . implode(', ', $missingFields));
        $hasYielded = true;
      }
    }

    if(empty($errors) && (!$this->_allowExtraFields))
    {
      $extraFields = array_diff_key($data, $this->_validators);

      if(count($extraFields) > 0)
      {
        yield $this->_makeError('Data contains extra fields: ' . implode(', ', array_keys($extraFields)));
        $hasYielded = true;
      }
    }

    if(!$hasYielded)
    {
      foreach($data as $field => $value)
      {
        if(isset($this->_validators[$field]))
        {
          $validator = $this->_validators[$field];
          if($validator instanceof ValidatorCollection)
          {
            foreach($validator->validate($value, $requiredFields ? true : false, $this->_allowExtraFields) as $error)
            {
              yield $error;
            }
          }
          else
          {
            foreach($validator->validate($value) as $error)
            {
              yield $error;
            }
          }
        }
      }
    }
  }
}
