<?php
namespace Packaged\Validate;

class ArrayValidator extends AbstractValidator
{
  private $_validator;
  private $_minCount;
  private $_maxCount;

  public function __construct(IValidator $validator, $minCount = 0, $maxCount = 0)
  {
    $this->_validator = $validator;
    $this->_minCount = $minCount;
    $this->_maxCount = $maxCount;
  }

  public function validate($value)
  {
    if(!is_array($value))
    {
      $this->_setLastError('must be an array');
      return false;
    }

    $numItems = count($value);
    if($numItems < $this->_minCount)
    {
      $this->_setLastError(
        'must contain at least ' . $this->_minCount . ' items'
      );
      return false;
    }

    if(($this->_maxCount > 0) && ($numItems > $this->_maxCount))
    {
      $this->_setLastError(
        'must not contain more than ' . $this->_maxCount . ' items'
      );
      return false;
    }

    $result = true;
    $errors = [];
    foreach($value as $idx => $entry)
    {
      if(!$this->_validator->validate($entry))
      {
        $errors[$idx] = $this->_validator->getLastError();
        $result = false;
      }
    }
    if(!$result)
    {
      $this->_setLastError($errors);
    }
    return $result;
  }

  public function tidy($value)
  {
    if(!is_array($value))
    {
      throw new \Exception('Supplied value is not an array');
    }

    $result = [];
    foreach($value as $k => $v)
    {
      $result[$k] = $this->_validator->tidy($v);
    }
    return $result;
  }

}
