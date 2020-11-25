import './index';
import {Validator} from './js/validator';
import {EqualValidator} from './js/validators/EqualValidator';
import {NotEqualValidator} from './js/validators/NotEqualValidator';
import {EnumValidator} from './js/validators/EnumValidator';
import {ConstEnumValidator} from './js/validators/ConstEnumValidator';
import {BoolValidator} from './js/validators/BoolValidator';
import {StringValidator} from './js/validators/StringValidator';
import {RequiredValidator} from './js/validators/RequiredValidator';
import {EmailValidator} from './js/validators/EmailValidator';
import {IPv4Validator} from './js/validators/IPv4Validator';

function testSuccess(response)
{
  expect(response).toHaveProperty('errors', []);
}

function testFailure(response, errors, potentiallyValid = false)
{
  expect(response).toHaveProperty('errors', errors);
  expect(response).toHaveProperty('potentiallyValid', potentiallyValid);
}

test(
  'deserialize',
  () =>
  {
    let v = Validator.fromJsonObject({t: 'String', c: {'minLength': 2, 'maxLength': 5}});
    expect(v).toBeInstanceOf(StringValidator);
    expect(v._minLength).toStrictEqual(2);
    expect(v._maxLength).toStrictEqual(5);
  }
);

test(
  'StringValidator',
  () =>
  {
    let v = new StringValidator();
    testSuccess(v.validate('test'));
    testSuccess(v.validate(''));

    v = new StringValidator(6);
    testFailure(v.validate('test'), ['must be at least 6 characters'], true);

    v = new StringValidator(1);
    testSuccess(v.validate('test'));
    testFailure(v.validate(''), ['must be at least 1 characters'], true);

    v = new StringValidator(0, 1);
    testFailure(v.validate('test'), ['must be no more than 1 characters']);
    testSuccess(v.validate('t'));
    testSuccess(v.validate(''));

    v = new StringValidator(3, 5);
    testSuccess(v.validate('test'));
    v = new StringValidator(4, 4);
    testSuccess(v.validate('test'));

    v = new StringValidator();
    testFailure(v.validate(true), ['not a valid value']);
    testFailure(v.validate(false), ['not a valid value']);
  }
);

test(
  'BoolValidator',
  () =>
  {
    const v = new BoolValidator();
    testFailure(v.validate('test'), ['Invalid boolean value']);
    testFailure(v.validate(''), ['Invalid boolean value']);

    testSuccess(v.validate('1'));
    testSuccess(v.validate('0'));
    testSuccess(v.validate('true'));
    testSuccess(v.validate('false'));
    testSuccess(v.validate(true));
    testSuccess(v.validate(false));

    testFailure(v.validate(null), ['Invalid boolean value']);
    testFailure(v.validate(undefined), ['Invalid boolean value']);
  }
);

test(
  'EnumValidator',
  () =>
  {
    let v = new EnumValidator();
    testSuccess(v.validate(''));
    testFailure(v.validate('test'), ['not a valid value']);

    v = new EnumValidator(['test']);
    testFailure(v.validate(''), ['not a valid value']);
    testSuccess(v.validate('test'));
    testSuccess(v.validate('TEST'));

    v = new EnumValidator(['TEST'], true);
    testFailure(v.validate('test'), ['not a valid value']);

    v = new EnumValidator(['TEST'], false);
    testSuccess(v.validate('test'));

    v = new EnumValidator(['TEST'], false, true);
    testFailure(v.validate('test'), ['not a valid value']);

    v = new EnumValidator(['TEST'], true, true);
    testSuccess(v.validate('test'));
  }
);

test(
  'ConstEnumValidator',
  () =>
  {
    let v = new ConstEnumValidator();
    testSuccess(v.validate(''));
    testFailure(v.validate('test'), ['not a valid value']);

    v = new ConstEnumValidator(['test']);
    testFailure(v.validate(''), ['not a valid value']);
    testSuccess(v.validate('test'));
    testSuccess(v.validate('TEST'));

    v = new ConstEnumValidator(['TEST'], true);
    testFailure(v.validate('test'), ['not a valid value']);

    v = new ConstEnumValidator(['TEST'], false);
    testSuccess(v.validate('test'));

    v = new ConstEnumValidator(['TEST'], false, true);
    testFailure(v.validate('test'), ['not a valid value']);

    v = new ConstEnumValidator(['TEST'], true, true);
    testSuccess(v.validate('test'));
  }
);

test(
  'EqualValidator',
  () =>
  {
    let v = new EqualValidator('test');
    testFailure(v.validate(''), ['value does not match']);
    testSuccess(v.validate('test'));

    v = new EqualValidator('');
    testFailure(v.validate('test'), ['value does not match']);
  }
);

test(
  'NotEqualValidator',
  () =>
  {
    let v = new NotEqualValidator('test');
    testFailure(v.validate('test'), ['value must not match']);
    testSuccess(v.validate(''));

    v = new NotEqualValidator('');
    testSuccess(v.validate('test'));
  }
);

test(
  'RequiredValidator',
  () =>
  {
    let v = new RequiredValidator();
    testSuccess(v.validate(true));
    testSuccess(v.validate(false));
    testSuccess(v.validate('true'));
    testSuccess(v.validate('false'));
    testSuccess(v.validate('0'));
    testSuccess(v.validate('1'));
    testSuccess(v.validate(0));
    testSuccess(v.validate(1));
    testFailure(v.validate(''), ['required']);
    testFailure(v.validate(null), ['required']);
    testFailure(v.validate(undefined), ['required']);
  }
);

test(
  'EmailValidator',
  () =>
  {
    let v = new EmailValidator();
    testSuccess(v.validate('test@test.com'));
    testSuccess(v.validate('a@b.com'));

    testFailure(v.validate('test'), ['invalid email address']);
    testFailure(v.validate('a@b.c'), ['invalid email address']);
  }
);

test(
  'IPv4Validator',
  () =>
  {
    let v = new IPv4Validator();
    testSuccess(v.validate('0.0.0.0'));
    testSuccess(v.validate('255.255.255.255'));
    testSuccess(v.validate('127.0.0.1'));

    testFailure(v.validate(''), ['invalid IPv4 address']);
    testFailure(v.validate('test'), ['invalid IPv4 address']);
    testFailure(v.validate('a.b.c.d'), ['invalid IPv4 address']);
    testFailure(v.validate('256.255.255.255'), ['invalid IPv4 address']);
    testFailure(v.validate('255.256.255.255'), ['invalid IPv4 address']);
    testFailure(v.validate('255.255.256.255'), ['invalid IPv4 address']);
    testFailure(v.validate('255.255.255.256'), ['invalid IPv4 address']);
    testFailure(v.validate('256.256.256.256'), ['invalid IPv4 address']);
  }
);
