<?php
namespace Packaged\Validate\Validators;

use Generator;
use Packaged\Validate\AbstractSerializableValidator;
use Packaged\Validate\SerializableValidator;

class ArrayKeysValidator extends AbstractSerializableValidator
{
  public const DICT_INVALID = 'invalid';
  public const DICT_MISSING = 'missing';
  public const DICT_UNKNOWN = 'unknown';

  protected $_dictionary = [
    self::DICT_INVALID => 'Must be an array',
    self::DICT_MISSING => 'Missing required entries: %s',
    self::DICT_UNKNOWN => 'Unknown entries: %s',
  ];

  protected $_requiredEntries;
  protected $_allowUnknownEntries;

  /**
   * @param string[] $requiredEntries       A list of entries that are required
   * @param bool     $allowUnknownEntries   If true then don't fail if extra
   *                                        entries are passed in
   */
  public function __construct(array $requiredEntries, bool $allowUnknownEntries = false)
  {
    $this->_requiredEntries = $requiredEntries;
    $this->_allowUnknownEntries = $allowUnknownEntries;
  }

  public static function deserialize($configuration): SerializableValidator
  {
    return new static($configuration->required, $configuration->allowUnknown);
  }

  public function serialize(): array
  {
    return [
      'required'     => $this->_requiredEntries,
      'allowUnknown' => $this->_allowUnknownEntries,
    ];
  }

  protected function _doValidate($value): Generator
  {
    if(!is_array($value))
    {
      return $this->_makeError($this->getDictionary()[self::DICT_INVALID]);
    }

    $valueKeys = array_keys($value);

    if(count($this->_requiredEntries) > 0)
    {
      $missingEntries = array_diff($this->_requiredEntries, $valueKeys);
      if(count($missingEntries) > 0)
      {
        $err = sprintf($this->getDictionary()[self::DICT_MISSING], implode(', ', $missingEntries));
        yield $this->_makeError($err);
      }
    }

    if(!$this->_allowUnknownEntries)
    {
      $extraEntries = array_diff($valueKeys, $this->_requiredEntries);
      if(count($extraEntries) > 0)
      {
        $err = sprintf($this->getDictionary()[self::DICT_UNKNOWN], implode(', ', $extraEntries));
        yield $this->_makeError($err);
      }
    }
  }
}
