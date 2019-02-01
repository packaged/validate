<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\ValidationException;

class SchemaValidator extends AbstractValidator
{
  /**
   * @var IValidator[]
   */
  private $_validators = [];
  /**
   * @var ArrayKeysValidator|null
   */
  private $_keyValidator;

  /**
   * ValidatorCollection constructor.
   *
   * @param array                   $fieldValidators
   * @param ArrayKeysValidator|null $keyValidator optional validator to ensure keys (or properties) are strictly matched
   */
  public function __construct(array $fieldValidators = [], ?ArrayKeysValidator $keyValidator = null)
  {
    $this->addFields($fieldValidators);
    $this->_keyValidator = $keyValidator;
  }

  public function addField($name, IValidator $validator)
  {
    $this->_validators[$name] = $validator;
  }

  /**
   * @param AbstractValidator[] $validators
   *
   * @return SchemaValidator
   */
  public function addFields($validators)
  {
    foreach($validators as $k => $v)
    {
      $this->addField($k, $v);
    }
    return $this;
  }

  /**
   * @param array|object $data The data to validate
   *
   * @return Generator|ValidationException[]
   */
  protected function _doValidate($data): Generator
  {
    $hasYielded = false;

    if($this->_keyValidator)
    {
      foreach($this->_keyValidator->validate($data) as $error)
      {
        yield $error;
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
          foreach($validator->validate($value) as $error)
          {
            yield $error;
          }
        }
      }
    }
  }
}
