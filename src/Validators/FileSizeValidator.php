<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class FileSizeValidator extends AbstractSerializableValidator
{
  public const DICT_INVALID = 'invalid';

  protected $_dictionary = [
    self::DICT_INVALID => 'File upload cannot be more than %smb in size',
  ];

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
    // Validation
    if(is_array($value) && array_key_exists('size', $value) && $value['size'] > ($this->_maxSize * 1024 * 1024))
    {
      $err = str_replace('%s', $this->_maxSize, $this->getDictionary()[self::DICT_INVALID]);
      yield $this->_makeError($err);
    }
  }

  public function getMaxSize()
  {
    return $this->_maxSize;
  }
}
