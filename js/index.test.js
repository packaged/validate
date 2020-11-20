import {validateField, validateForm} from './index';
import base64 from 'base-64';

function i(value, validateType, validateObject = {}, inputType = 'text')
{
  const ob = Object.assign({}, validateObject, {t: validateType});
  const input = document.createElement('input');
  input.setAttribute('type', inputType);
  input.setAttribute('value', value);
  input.setAttribute('validate', base64.encode(JSON.stringify(ob)));
  return input;
}

/**
 * @param {HTMLInputElement} inputs
 * @return {HTMLFormElement}
 */
function f(...inputs)
{
  const form = document.createElement('form');
  form.append(...inputs);
  return form;
}

function e(allowedValues, caseSensitive = true, negate = false)
{
  return {allowedValues, caseSensitive, negate};
}

test('StringValidator', () =>
{
  expect(validateField(i('test', 'StringValidator'))).toBe(true);
  expect(validateField(i('', 'StringValidator'))).toBe(true);

  expect(validateField(i('test', 'StringValidator', {'minLength': 6}))).toBe(false);
  expect(validateField(i('test', 'StringValidator', {'minLength': 1}))).toBe(true);
  expect(validateField(i('', 'StringValidator', {'minLength': 1}))).toBe(false);

  expect(validateField(i('test', 'StringValidator', {'maxLength': 1}))).toBe(false);
  expect(validateField(i('t', 'StringValidator', {'maxLength': 1}))).toBe(true);
  expect(validateField(i('', 'StringValidator', {'maxLength': 1}))).toBe(true);

  expect(validateField(i('test', 'StringValidator', {'minLength': 3, 'maxLength': 5}))).toBe(true);
  expect(validateField(i('test', 'StringValidator', {'minLength': 4, 'maxLength': 4}))).toBe(true);

  // these get cast as strings 'true' and 'false'
  expect(validateField(i(true, 'StringValidator', {'minLength': 4, 'maxLength': 4}))).toBe(true);
  expect(validateField(i(false, 'StringValidator', {'minLength': 5, 'maxLength': 5}))).toBe(true);
});

test('BoolValidator', () =>
{
  expect(validateField(i('test', 'BoolValidator'))).toBe(false);
  expect(validateField(i('', 'BoolValidator'))).toBe(false);

  expect(validateField(i('1', 'BoolValidator'))).toBe(true);
  expect(validateField(i('0', 'BoolValidator'))).toBe(true);
  expect(validateField(i('true', 'BoolValidator'))).toBe(true);
  expect(validateField(i('false', 'BoolValidator'))).toBe(true);
});

test('EnumValidator', () =>
{
  expect(validateField(i('', 'EnumValidator', e([])))).toBe(true);
  expect(validateField(i('', 'EnumValidator', e(['test'])))).toBe(false);
  expect(validateField(i('test', 'EnumValidator', e([])))).toBe(false);
  expect(validateField(i('test', 'EnumValidator', e(['test'])))).toBe(true);
  expect(validateField(i('test', 'EnumValidator', e(['TEST'])))).toBe(false);
  expect(validateField(i('test', 'EnumValidator', e(['TEST'], false)))).toBe(true);
  expect(validateField(i('test', 'EnumValidator', e(['TEST'], false, true)))).toBe(false);
  expect(validateField(i('test', 'EnumValidator', e(['TEST'], true, true)))).toBe(true);
});

test('ConstEnumValidator', () =>
{
  expect(validateField(i('', 'ConstEnumValidator', e([])))).toBe(true);
  expect(validateField(i('', 'ConstEnumValidator', e(['test'])))).toBe(false);
  expect(validateField(i('test', 'ConstEnumValidator', e([])))).toBe(false);
  expect(validateField(i('test', 'ConstEnumValidator', e(['test'])))).toBe(true);
  expect(validateField(i('test', 'ConstEnumValidator', e(['TEST'])))).toBe(false);
  expect(validateField(i('test', 'ConstEnumValidator', e(['TEST'], false)))).toBe(true);
  expect(validateField(i('test', 'ConstEnumValidator', e(['TEST'], false, true)))).toBe(false);
  expect(validateField(i('test', 'ConstEnumValidator', e(['TEST'], true, true)))).toBe(true);
});

test('test no form elements', () =>
{
  expect(validateForm(f())).toBe(true);
});
