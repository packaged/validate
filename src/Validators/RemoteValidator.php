<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IDataSetValidator;
use Packaged\Validate\IValidator;

class RemoteValidator extends AbstractValidator implements IDataSetValidator
{
  protected $_validators;
  protected $_field;

  public function __construct($field)
  {
    $this->_field = $field;
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
      if($validator['remoteValidator']->isValid($values[$validator['remoteField']] ?? null))
      {
        if(!isset($secondary[$this->_field]))
        {
          $secondary[$this->_field] = [];
        }
        $secondary[$this->_field][] = $validator['validator'];
      }
    }

    $fieldValue = $values[$this->_field] ?? null;
    foreach($secondary as $field => $validators)
    {
      foreach($validators as $validator)
      {
        /** @var IValidator $validator */
        yield $validator->validate($fieldValue);
      }
    }
  }

  //Validate field if remote validator passes
  public function addValidator(IValidator $validator, string $remoteField, IValidator $remoteValidator)
  {
    $this->_validators[] = [
      'validator'       => $validator,
      'remoteField'     => $remoteField,
      'remoteValidator' => $remoteValidator,
    ];
    return $this;
  }
}
