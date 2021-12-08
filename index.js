import {addValidator} from './js/validator.js';
import {EqualValidator} from './js/validators/EqualValidator.js';
import {NotEqualValidator} from './js/validators/NotEqualValidator.js';
import {EnumValidator} from './js/validators/EnumValidator.js';
import {ConstEnumValidator} from './js/validators/ConstEnumValidator.js';
import {BoolValidator} from './js/validators/BoolValidator.js';
import {StringValidator} from './js/validators/StringValidator.js';
import {RequiredValidator} from './js/validators/RequiredValidator.js';
import {MultiValidator} from './js/validators/MultiValidator.js';
import {RegexValidator} from './js/validators/RegexValidator.js';
import {EmailValidator} from './js/validators/EmailValidator.js';
import {IPv4Validator} from './js/validators/IPv4Validator.js';
import {ConfirmationValidator} from './js/validators/ConfirmationValidator.js';

export * from './js/validator';

addValidator('Equal', EqualValidator);
addValidator('NotEqual', NotEqualValidator);
addValidator('Enum', EnumValidator);
addValidator('ConstEnum', ConstEnumValidator);
addValidator('Bool', BoolValidator);
addValidator('String', StringValidator);
addValidator('Required', RequiredValidator);
addValidator('Multi', MultiValidator);
addValidator('Regex', RegexValidator);
addValidator('Email', EmailValidator);
addValidator('IPv4', IPv4Validator);
addValidator('Confirmation', ConfirmationValidator);
