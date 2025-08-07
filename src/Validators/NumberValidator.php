<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class NumberValidator extends AbstractSerializableValidator
{
  public const DICT_INVALID = 'invalid';
  public const DICT_MIN = 'min';
  public const DICT_MAX = 'max';

  protected $_dictionary = [
    self::DICT_INVALID => 'must be a number',
    self::DICT_MIN     => 'must be more than %s',
    self::DICT_MAX     => 'must be less than %s',
  ];

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
      yield $this->_makeError($this->getDictionary()[self::DICT_INVALID]);
    }
    else if(($this->_minValue !== null) && ($value < $this->_minValue))
    {
      $err = str_replace('%s', $this->_minValue, $this->getDictionary()[self::DICT_MIN]);
      yield $this->_makeError($err);
    }
    else if(($this->_maxValue !== null) && ($value > $this->_maxValue))
    {
      $err = str_replace('%s', $this->_maxValue, $this->getDictionary()[self::DICT_MAX]);
      yield $this->_makeError($err);
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
