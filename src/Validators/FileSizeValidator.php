<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class FileSizeValidator extends AbstractSerializableValidator
{
  protected $_maxSize;

  public function __construct($maxSize = null)
  {
    if($maxSize === null)
    {
      throw new \InvalidArgumentException(
        'maxSize must be set'
      );
    }

    $this->_maxSize = $maxSize;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->maxSize);
  }

  public function serialize(): array
  {
    return [
      'maxSize' => $this->getMaxSize(),
    ];
  }

  protected function _doValidate($value): Generator
  {
    if(is_object($value) && method_exists($value, 'getRealPath'))
    {
      $realSize = file_exists($value->getRealPath()) ? filesize($value->getRealPath()) : 0;
      // Validation
      if($realSize > 0 && $realSize > ($this->_maxSize * 1024 * 1024))
      {
        yield $this->_makeError("File upload cannot be more than " . $this->_maxSize . "mb in size");
      }
    }
  }

  public function getMaxSize()
  {
    return $this->_maxSize;
  }

}
