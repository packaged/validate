<?php
namespace Packaged\Validate;

use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\OptionalValidator;

class ValidatorCollection extends AbstractValidator
{
  /**
   * @var IValidator[]
   */
  private $_validators;

  public function __construct($fieldValidators = [])
  {
    $this->_validators = [];
    foreach($fieldValidators as $field => $validator)
    {
      $this->addField($field, $validator);
    }
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
   * @param array|object  $data             The data to validate
   * @param bool|string[] $requiredFields   A list of fields that are required.
   *                                        If this is true then all fields are
   *                                        required. An empty array or false means
   *                                        all fields are optional
   * @param bool          $allowExtraFields If false then don't complain if the
   *                                        data contains unknown fields
   *
   * @return bool
   */
  public function validate($data, $requiredFields = true, $allowExtraFields = false)
  {
    $result = true;
    $data = $this->_dataAsArray($data);

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
        $this->_setLastError(
          'The following fields are required: ' . implode(', ', $missingFields)
        );
        $result = false;
      }
    }

    if($result && (!$allowExtraFields))
    {
      $extraFields = array_diff_key($data, $this->_validators);

      if(count($extraFields) > 0)
      {
        $this->_setLastError(
          'Data contains extra fields: '
          . implode(', ', array_keys($extraFields))
        );
        $result = false;
      }
    }

    if($result)
    {
      $failedFields = [];
      foreach($data as $field => $value)
      {
        if(isset($this->_validators[$field]))
        {
          $validator = $this->_validators[$field];
          if($validator instanceof ValidatorCollection)
          {
            $vRes = $validator->validate(
              $value,
              $requiredFields ? true : false,
              $allowExtraFields
            );
          }
          else
          {
            $vRes = $validator->validate($value);
          }
          if(!$vRes)
          {
            $result = false;
            $failedFields[$field] = $validator->getLastError();
          }
        }
      }
      if(!$result)
      {
        $this->_setLastError($failedFields);
      }
    }
    return $result;
  }

  /**
   * @param array|object $data
   * @param bool $nullMissing If true then fill in missing fields with nulls
   *
   * @return array
   */
  public function tidy($data, $nullMissing = false)
  {
    $data = $this->_dataAsArray($data);
    foreach($this->_validators as $field => $validator)
    {
      if(isset($data[$field]))
      {
        $data[$field] = $validator->tidy($data[$field]);
      }
      else if($nullMissing)
      {
        $data[$field] = null;
      }
    }
    return $data;
  }
}
