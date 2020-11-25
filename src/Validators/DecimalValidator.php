<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\SerializableValidator;

class DecimalValidator extends NumberValidator
{
  protected $_decimalPlaces;

  /**
   * DecimalValidator constructor.
   *
   * @param int  $decimalPlaces
   * @param null $minValue
   * @param null $maxValue
   */
  public function __construct(
    $decimalPlaces, $minValue = null, $maxValue = null
  )
  {
    parent::__construct($minValue, $maxValue);
    $this->_decimalPlaces = $decimalPlaces;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->decimalPlaces, $configuration->minValue, $configuration->maxValue);
  }

  public function serialize(): array
  {
    return [
      'decimalPlaces' => $this->_decimalPlaces,
      'minValue'      => $this->_minValue,
      'maxValue'      => $this->_maxValue,
    ];
  }

  protected function _doValidate($value): Generator
  {
    $passParent = true;
    foreach(parent::_doValidate($value) as $err)
    {
      yield $err;
      $passParent = false;
    }

    if($passParent)
    {
      $parts = explode('.', $value);
      if(count($parts) > 2)
      {
        yield $this->_makeError('invalid decimal value');
      }
      else if(count($parts) == 2 && ($this->_decimalPlaces !== null && strlen($parts[1]) > $this->_decimalPlaces))
      {
        yield $this->_makeError('must be a number to no more than ' . $this->_decimalPlaces . ' decimal places');
      }
    }
  }

  /**
   * @return int
   */
  public function getDecimalPlaces(): int
  {
    return $this->_decimalPlaces;
  }

}
