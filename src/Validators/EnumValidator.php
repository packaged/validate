<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class EnumValidator extends AbstractSerializableValidator
{
  protected $_allowedValues;
  protected $_caseSensitive;

  /**
   * @param string[] $allowedValues
   * @param bool     $caseSensitive
   */
  public function __construct(array $allowedValues, $caseSensitive = false)
  {
    $this->_allowedValues = $allowedValues;
    $this->_caseSensitive = $caseSensitive;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->allowedValues, $configuration->caseSensitive);
  }

  public function serialize(): array
  {
    return [
      'allowedValues' => $this->_allowedValues,
      'caseSensitive' => $this->_caseSensitive,
    ];
  }

  protected function _doValidate($value): Generator
  {
    if(($value === null || $value === '') && empty($this->_getAllowedValues()))
    {
      // this is always valid
      return null;
    }

    if($this->isCaseSensitive())
    {
      if(!in_array($value, $this->_getAllowedValues()))
      {
        return $this->_makeError('not a valid value');
      }
    }

    $result = false;
    foreach($this->_getAllowedValues() as $allowedValue)
    {
      if(strcasecmp($allowedValue, $value) == 0)
      {
        $result = true;
        break;
      }
    }
    if(!$result)
    {
      yield $this->_makeError('not a valid value');
    }
  }

  protected function _getAllowedValues()
  {
    return $this->_allowedValues;
  }

  public function getAllowedValues(): array
  {
    return $this->_getAllowedValues();
  }

  public function isCaseSensitive(): bool
  {
    return $this->_caseSensitive;
  }

}
