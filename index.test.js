import './index';
import {validateField, validateForm} from './js/validator';
import base64 from 'base-64';
import {EqualValidator} from './js/validators/EqualValidator';
import {NotEqualValidator} from './js/validators/NotEqualValidator';
import {EnumValidator} from './js/validators/EnumValidator';
import {ConstEnumValidator} from './js/validators/ConstEnumValidator';
import {BoolValidator} from './js/validators/BoolValidator';
import {StringValidator} from './js/validators/StringValidator';

function i(value, validateType, validateObject = {}, inputType = 'text', inputName = 'myInput')
{
  const ob = Object.assign({}, {t: validateType.name, c: validateObject});
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

function testSuccess(response)
{
  expect(response).toHaveProperty('errors', []);
}

function testFailure(response, errors, potentiallyValid = false)
{
  expect(response).toHaveProperty('errors', errors);
  expect(response).toHaveProperty('potentiallyValid', potentiallyValid);
}

test('StringValidator', () =>
{
  testSuccess(validateField(i('test', StringValidator)));
  testSuccess(validateField(i('', StringValidator)));

  testFailure(validateField(i('test', StringValidator, {'minLength': 6})), ['must be at least 6 characters'], true);
  testSuccess(validateField(i('test', StringValidator, {'minLength': 1})));
  testFailure(validateField(i('', StringValidator, {'minLength': 1})), ['must be at least 1 characters'], true);

  testFailure(validateField(i('test', StringValidator, {'maxLength': 1})), ['must be no more than 1 characters']);
  testSuccess(validateField(i('t', StringValidator, {'maxLength': 1})));
  testSuccess(validateField(i('', StringValidator, {'maxLength': 1})));

  testSuccess(validateField(i('test', StringValidator, {'minLength': 3, 'maxLength': 5})));
  testSuccess(validateField(i('test', StringValidator, {'minLength': 4, 'maxLength': 4})));

  // these get cast as strings 'true' and 'false'
  testSuccess(validateField(i(true, StringValidator, {'minLength': 4, 'maxLength': 4})));
  testSuccess(validateField(i(false, StringValidator, {'minLength': 5, 'maxLength': 5})));
});

test('BoolValidator', () =>
{
  testFailure(validateField(i('test', BoolValidator)), ['Invalid boolean value']);
  testFailure(validateField(i('', BoolValidator)), ['Invalid boolean value']);

  testSuccess(validateField(i('1', BoolValidator)));
  testSuccess(validateField(i('0', BoolValidator)));
  testSuccess(validateField(i('true', BoolValidator)));
  testSuccess(validateField(i('false', BoolValidator)));
});

test('EnumValidator', () =>
{
  testSuccess(validateField(i('', EnumValidator, e([]))));
  testFailure(validateField(i('', EnumValidator, e(['test']))), ['not a valid value']);
  testFailure(validateField(i('test', EnumValidator, e([]))), ['not a valid value']);
  testSuccess(validateField(i('test', EnumValidator, e(['test']))));
  testFailure(validateField(i('test', EnumValidator, e(['TEST']))), ['not a valid value']);
  testSuccess(validateField(i('test', EnumValidator, e(['TEST'], false))));
  testFailure(validateField(i('test', EnumValidator, e(['TEST'], false, true))), ['not a valid value']);
  testSuccess(validateField(i('test', EnumValidator, e(['TEST'], true, true))));
});

test('ConstEnumValidator', () =>
{
  testSuccess(validateField(i('', ConstEnumValidator, e([]))));
  testFailure(validateField(i('', ConstEnumValidator, e(['test']))), ['not a valid value']);
  testFailure(validateField(i('test', ConstEnumValidator, e([]))), ['not a valid value']);
  testSuccess(validateField(i('test', ConstEnumValidator, e(['test']))));
  testFailure(validateField(i('test', ConstEnumValidator, e(['TEST']))), ['not a valid value']);
  testSuccess(validateField(i('test', ConstEnumValidator, e(['TEST'], false))));
  testFailure(validateField(i('test', ConstEnumValidator, e(['TEST'], false, true))), ['not a valid value']);
  testSuccess(validateField(i('test', ConstEnumValidator, e(['TEST'], true, true))));
});

test('EqualValidator', () =>
{
  testFailure(validateField(i('', EqualValidator, {expect: 'test'})), ['value does not match']);
  testFailure(validateField(i('test', EqualValidator, {expect: ''})), ['value does not match']);
  testSuccess(validateField(i('test', EqualValidator, {expect: 'test'})));
});

test('NotEqualValidator', () =>
{
  testSuccess(validateField(i('', NotEqualValidator, {expect: 'test'})));
  testSuccess(validateField(i('test', NotEqualValidator, {expect: ''})));
  testFailure(validateField(i('test', NotEqualValidator, {expect: 'test'})), ['value must not match']);
});

test('test form no elements', () =>
{
  expect(validateForm(f()).size).toStrictEqual(0);
});

test('test form error elements', () =>
{
  const inputA = i('test', EqualValidator, {expect: ''}),
    inputB = i('test', NotEqualValidator, {expect: 'test'});
  const validationResults = validateForm(f(inputA, inputB));
  expect(validationResults.size).toStrictEqual(2);
  testFailure(validationResults.get(inputA), ['value does not match']);
  testFailure(validationResults.get(inputB), ['value must not match']);
});

test('test form multiple errors, potentially valid', () =>
{

  const inputA = i('test', EqualValidator, {expect: ''}),
    inputB = i('test', NotEqualValidator, {expect: 'test'});
  const validationResults = validateForm(f(inputA, inputB));
  expect(validationResults.size).toStrictEqual(2);
  testFailure(validationResults.get(inputA), ['value does not match']);
  testFailure(validationResults.get(inputB), ['value must not match']);
});
