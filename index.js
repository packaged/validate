import {addValidator} from './js/validator';
import {EqualValidator} from './js/validators/EqualValidator';
import {NotEqualValidator} from './js/validators/NotEqualValidator';
import {EnumValidator} from './js/validators/EnumValidator';
import {ConstEnumValidator} from './js/validators/ConstEnumValidator';
import {BoolValidator} from './js/validators/BoolValidator';
import {StringValidator} from './js/validators/StringValidator';
import {RequiredValidator} from './js/validators/RequiredValidator';
import {MultiValidator} from './js/validators/MultiValidator';
import {RegexValidator} from './js/validators/RegexValidator';
import {EmailValidator} from './js/validators/EmailValidator';

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
