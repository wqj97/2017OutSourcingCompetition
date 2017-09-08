import { ZhCn } from './zh-cn';
import { EnGb } from './en-gb';


export class CameraTranslations {
  //
  //
  static getTranslations(lang) {
    return CameraTranslations.langs[lang] ? CameraTranslations.langs[lang] : {};
  }


  //
  //
  static langs = {
    'zh-cn': ZhCn.translations,
    'en-gb': EnGb.translations,
  }
}
