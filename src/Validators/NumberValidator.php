<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class NumberValidator extends AbstractSerializableValidator
{
  protected $_minValue;
  protected $_maxValue;

  public function __construct($minValue = null, $maxValue = null)
  {
    if(($maxValue !== null) && ($minValue !== null) && ($maxValue < $minValue))
    {
      throw new \InvalidArgumentException(
        'maxValue must be greater than or equal to minValue'
      );
    }
    $this->_minValue = $minValue;
    $this->_maxValue = $maxValue;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->minValue, $configuration->maxValue);
  }

  public function serialize(): array
  {
    return [
      'minValue' => $this->_minValue,
      'maxValue' => $this->_maxValue,
    ];
  }

  protected function _doValidate($value): Generator
  {
    if(!is_numeric($value))
    {
      yield $this->_makeError('must be a number');
    }
    else if(($this->_minValue !== null) && ($value < $this->_minValue))
    {
      yield $this->_makeError('must be more than ' . $this->_minValue);
    }
    else if(($this->_maxValue !== null) && ($value > $this->_maxValue))
    {
      yield $this->_makeError('must be less than ' . $this->_maxValue);
    }
  }

  /**
   * @return null
   */
  public function getMinValue()
  {
    return $this->_minValue;
  }

  /**
   * @return null
   */
  public function getMaxValue()
  {
    return $this->_maxValue;
  }

}
