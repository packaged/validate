<?php
namespace Packaged\Validate;

class DictionaryValidator extends AbstractValidator
{
  private $_validator;
  private $_requiredEntries;
  private $_allowUnknownEntries;

  /**
   * DictionaryValidator constructor.
   *
   * @param IValidator $validator           The validator to use for the fields
   * @param string[]   $requiredEntries     A list of entries that are required
   * @param bool       $allowUnknownEntries If true then don't fail if extra
   *                                        entries are passed in
   */
  public function __construct(
    IValidator $validator, $requiredEntries = [], $allowUnknownEntries = false
  )
  {
    $this->_validator = $validator;
    $this->_requiredEntries = $requiredEntries;
    $this->_allowUnknownEntries = $allowUnknownEntries;
  }

  public function validate($value)
  {
    if(!is_array($value))
    {
      $this->_setLastError('must be an array');
      return false;
    }

    $valueKeys = array_keys($value);

    if(count($this->_requiredEntries) > 0)
    {
      $missingEntries = array_diff($this->_requiredEntries, $valueKeys);
      if(count($missingEntries) > 0)
      {
        $this->_setLastError(
          'missing entries: ' . implode(', ', $missingEntries)
        );
        return false;
      }
    }

    if(!$this->_allowUnknownEntries)
    {
      $extraEntries = array_diff($valueKeys, $this->_requiredEntries);
      if(count($extraEntries) > 0)
      {
        $this->_setLastError(
          'unknown entries: ' . implode(', ', $extraEntries)
        );
        return false;
      }
    }

    $result = true;
    $errors = [];
    foreach($value as $key => $entry)
    {
      if(!$this->_validator->validate($entry))
      {
        $errors[$key] = $this->_validator->getLastError();
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
