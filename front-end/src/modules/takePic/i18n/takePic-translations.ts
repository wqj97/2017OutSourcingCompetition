import { ZhCn } from './zh-cn';
import { EnGb } from './en-gb';


export class TakePicTranslations {
  //
  //
  static getTranslations(lang) {
    return TakePicTranslations.langs[lang] ? TakePicTranslations.langs[lang] : {};
  }


  //
  //
  static langs = {
    'zh-cn': ZhCn.translations,
    'en-gb': EnGb.translations,
  }
}
