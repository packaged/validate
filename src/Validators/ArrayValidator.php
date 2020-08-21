<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\IValidator;
use Packaged\Validate\SerializableValidator;
use Packaged\Validate\Validation;

class ArrayValidator extends AbstractSerializableValidator
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

  public static function deserialize($configuration): SerializableValidator
  {
    return new static(
      Validation::fromJsonObject($configuration->validator),
      $configuration->minCount,
      $configuration->maxCount
    );
  }

  public function serialize(): array
  {
    return [
      'validator' => $this->_validator,
      'minCount'  => $this->_minCount,
      'maxCount'  => $this->_maxCount,
    ];
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

  /**
   * @return int
   */
  public function getMinCount(): int
  {
    return $this->_minCount;
  }

  /**
   * @return int
   */
  public function getMaxCount(): int
  {
    return $this->_maxCount;
  }

}
