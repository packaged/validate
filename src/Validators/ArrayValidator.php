<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractValidator;
use Packaged\Validate\IValidator;

class ArrayValidator extends AbstractValidator
{
  protected $_validator;
  protected $_minCount;
  protected $_maxCount;

  public function __construct(IValidator $validator, $minCount = 0, $maxCount = 0)
  {
    $this->_validator = $validator;
    $this->_minCount = $minCount;
    $this->_maxCount = $maxCount;
  }

  protected function _doValidate($value): Generator
  {
    if(!is_array($value))
    {
      return $this->_makeError('must be an array');
    }

    $numItems = count($value);
    if($numItems < $this->_minCount)
    {
      return $this->_makeError('must contain at least ' . $this->_minCount . ' items');
    }

    if(($this->_maxCount > 0) && ($numItems > $this->_maxCount))
    {
      return $this->_makeError('must not contain more than ' . $this->_maxCount . ' items');
    }

    foreach($value as $idx => $entry)
    {
      foreach($this->_validator->validate($entry) as $error)
      {
        yield $error;
      }
    }
  }
}
