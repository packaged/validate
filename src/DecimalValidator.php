<?php
namespace Packaged\Validate;

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

  public function validate($value)
  {
    if(parent::validate($value))
    {
      $parts = explode('.', $value);
      if((count($parts) > 2)
        || ((count($parts) == 2)
          && (strlen($parts[1]) > $this->_decimalPlaces))
      )
      {
        $this->_setLastError(
          'must be a number to no more than '
          . $this->_decimalPlaces . ' decimal places'
        );
      }
      else
      {
        return true;
      }
    }
    return false;
  }

  public function tidy($value)
  {
    return round(parent::tidy($value), $this->_decimalPlaces);
  }
}
