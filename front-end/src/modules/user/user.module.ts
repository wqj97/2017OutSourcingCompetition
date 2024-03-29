import { NgModule } from '@angular/core';
import { Platform, Events, ModalController } from 'ionic-angular';
import { HayCommonModule } from '../common/common.module';

import { AppService } from '../common/services/app.service';
import { UserService } from './services/user.service';
import { UserTranslations } from './i18n/user-translations';

import { MePage } from './pages/me';
import { MeProfilePage } from './pages/me-profile';
import { MeProfileUpdatePage } from './pages/me-profileUpdate';

import { MeSettingPage } from './pages/me-setting';
import { MeSettingLanguagePage } from './pages/me-setting-language';

import { HCAboutPage } from './pages/hc-about';
import { HCGuidePage } from './pages/hc-guide';
import { HCFeedbackPage } from './pages/hc-feedback';

import { AuthenticatePage } from './pages/authenticate';



@NgModule({
  imports: [
    HayCommonModule,
  ],
  declarations: [
    MePage,
    MeProfilePage,
    MeProfileUpdatePage,
    MeSettingPage,
    HCAboutPage,
    HCGuidePage,
    HCFeedbackPage,
    MeSettingLanguagePage,
    AuthenticatePage,
  ],
  entryComponents: [
    MePage,
    MeProfilePage,
    MeProfileUpdatePage,
    MeSettingPage,
    HCAboutPage,
    HCGuidePage,
    HCFeedbackPage,
    MeSettingLanguagePage,
    AuthenticatePage,
  ],
  providers: [
    UserService,
  ],
  exports: [
  ],
})
export class UserModule {
  constructor(
    public platform: Platform,
    public events: Events,
    public heyApp: AppService,
    public userService: UserService,
    public modalCtrl: ModalController
  ) {
    // load translations
    this.heyApp.loadTranslations(UserTranslations);

    // subcribe events
    this.subscribeEvents();

    // platform ready
    this.platform.ready().then(() => {
      // get user
      this.getUser();
    });
  }


  //
  // get user
  getUser() {
    setTimeout(() => {
      this.userService.getUser().then((userInfo) => {
        this.events.publish('auth:logIn', userInfo);
      }, () => {
        this.events.publish('auth:logOut');
      });
    }, 5000);
  }


  //
  // Subscribe events
  subscribeEvents() {
    //
    // subscribe app goto login
    this.events.subscribe('app:gotoLogin', (params) => {
      // this.heyApp.utilityComp.presentModal(AuthenticatePage);
      let modal = this.modalCtrl.create(AuthenticatePage)
      modal.present();
      console.log('present login page');
    });
  }
}
