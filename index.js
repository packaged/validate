import {addValidator} from './js/validator';
import {EqualValidator} from './js/validators/EqualValidator';
import {NotEqualValidator} from './js/validators/NotEqualValidator';
import {EnumValidator} from './js/validators/EnumValidator';
import {ConstEnumValidator} from './js/validators/ConstEnumValidator';
import {BoolValidator} from './js/validators/BoolValidator';
import {StringValidator} from './js/validators/StringValidator';
import {RequiredValidator} from './js/validators/RequiredValidator';

export * from './js/validator';

addValidator(EqualValidator);
addValidator(NotEqualValidator);
addValidator(EnumValidator);
addValidator(ConstEnumValidator);
addValidator(BoolValidator);
addValidator(StringValidator);
addValidator(RequiredValidator);
