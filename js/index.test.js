import {validateField, validateForm} from './index';
import base64 from 'base-64';

function i(value, validateType, validateObject = {}, inputType = 'text', inputName = 'myInput')
{
  const ob = Object.assign({}, {t: validateType, c: validateObject});
  const input = document.createElement('input');
  input.setAttribute('name', inputName);
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
  expect(validateField(i('test', 'StringValidator')))
    .toStrictEqual([]);
  expect(validateField(i('', 'StringValidator')))
    .toStrictEqual([]);

  expect(validateField(i('test', 'StringValidator', {'minLength': 6})))
    .toStrictEqual(['must be at least 6 characters']);
  expect(validateField(i('test', 'StringValidator', {'minLength': 1})))
    .toStrictEqual([]);
  expect(validateField(i('', 'StringValidator', {'minLength': 1})))
    .toStrictEqual(['must be at least 1 characters']);

  expect(validateField(i('test', 'StringValidator', {'maxLength': 1})))
    .toStrictEqual(['must be no more than 1 characters']);
  expect(validateField(i('t', 'StringValidator', {'maxLength': 1})))
    .toStrictEqual([]);
  expect(validateField(i('', 'StringValidator', {'maxLength': 1})))
    .toStrictEqual([]);

  expect(validateField(i('test', 'StringValidator', {'minLength': 3, 'maxLength': 5})))
    .toStrictEqual([]);
  expect(validateField(i('test', 'StringValidator', {'minLength': 4, 'maxLength': 4})))
    .toStrictEqual([]);

  // these get cast as strings 'true' and 'false'
  expect(validateField(i(true, 'StringValidator', {'minLength': 4, 'maxLength': 4})))
    .toStrictEqual([]);
  expect(validateField(i(false, 'StringValidator', {'minLength': 5, 'maxLength': 5})))
    .toStrictEqual([]);
});

test('BoolValidator', () =>
{
  expect(validateField(i('test', 'BoolValidator')))
    .toStrictEqual(['Invalid boolean value']);
  expect(validateField(i('', 'BoolValidator')))
    .toStrictEqual(['Invalid boolean value']);

  expect(validateField(i('1', 'BoolValidator')))
    .toStrictEqual([]);
  expect(validateField(i('0', 'BoolValidator')))
    .toStrictEqual([]);
  expect(validateField(i('true', 'BoolValidator')))
    .toStrictEqual([]);
  expect(validateField(i('false', 'BoolValidator')))
    .toStrictEqual([]);
});

test('EnumValidator', () =>
{
  expect(validateField(i('', 'EnumValidator', e([]))))
    .toStrictEqual([]);
  expect(validateField(i('', 'EnumValidator', e(['test']))))
    .toStrictEqual(['not a valid value']);
  expect(validateField(i('test', 'EnumValidator', e([]))))
    .toStrictEqual(['not a valid value']);
  expect(validateField(i('test', 'EnumValidator', e(['test']))))
    .toStrictEqual([]);
  expect(validateField(i('test', 'EnumValidator', e(['TEST']))))
    .toStrictEqual(['not a valid value']);
  expect(validateField(i('test', 'EnumValidator', e(['TEST'], false))))
    .toStrictEqual([]);
  expect(validateField(i('test', 'EnumValidator', e(['TEST'], false, true))))
    .toStrictEqual(['not a valid value']);
  expect(validateField(i('test', 'EnumValidator', e(['TEST'], true, true))))
    .toStrictEqual([]);
});

test('ConstEnumValidator', () =>
{
  expect(validateField(i('', 'ConstEnumValidator', e([]))))
    .toStrictEqual([]);
  expect(validateField(i('', 'ConstEnumValidator', e(['test']))))
    .toStrictEqual(['not a valid value']);
  expect(validateField(i('test', 'ConstEnumValidator', e([]))))
    .toStrictEqual(['not a valid value']);
  expect(validateField(i('test', 'ConstEnumValidator', e(['test']))))
    .toStrictEqual([]);
  expect(validateField(i('test', 'ConstEnumValidator', e(['TEST']))))
    .toStrictEqual(['not a valid value']);
  expect(validateField(i('test', 'ConstEnumValidator', e(['TEST'], false))))
    .toStrictEqual([]);
  expect(validateField(i('test', 'ConstEnumValidator', e(['TEST'], false, true))))
    .toStrictEqual(['not a valid value']);
  expect(validateField(i('test', 'ConstEnumValidator', e(['TEST'], true, true))))
    .toStrictEqual([]);
});

test('EqualValidator', () =>
{
  expect(validateField(i('', 'EqualValidator', {expect: 'test'})))
    .toStrictEqual(['value does not match']);
  expect(validateField(i('test', 'EqualValidator', {expect: ''})))
    .toStrictEqual(['value does not match']);
  expect(validateField(i('test', 'EqualValidator', {expect: 'test'})))
    .toStrictEqual([]);
});

test('NotEqualValidator', () =>
{
  expect(validateField(i('', 'NotEqualValidator', {expect: 'test'})))
    .toStrictEqual([]);
  expect(validateField(i('test', 'NotEqualValidator', {expect: ''})))
    .toStrictEqual([]);
  expect(validateField(i('test', 'NotEqualValidator', {expect: 'test'})))
    .toStrictEqual(['value must not match']);
});

test('test form no elements', () =>
{
  expect(validateForm(f()))
    .toStrictEqual({});
});

test('test form error elements', () =>
{
  expect(validateForm(f(i('test', 'EqualValidator', {expect: ''}), i('test', 'NotEqualValidator', {expect: 'test'}))))
    .toStrictEqual(
      {
        'myInput': ['value does not match', 'value must not match']
      });
});
