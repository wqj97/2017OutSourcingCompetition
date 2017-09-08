import { NgModule } from '@angular/core';
import { Platform, Events } from 'ionic-angular';
import { HayCommonModule } from '../common/common.module';

import { AppService } from '../common/services/app.service';
import { CameraService } from './services/camera.service';
import { CameraTranslations } from './i18n/camera-translations';

import { CameraPage } from './pages/camera';


@NgModule({
  imports: [
    HayCommonModule,
  ],
  declarations: [
      CameraPage,
  ],
  entryComponents: [
      CameraPage,
  ],
  providers: [
      CameraService,
  ],
  exports: [
  ],
})
export class CameraModule {
  constructor(
    public platform: Platform,
    public events: Events,
    public heyApp: AppService
  ) {
    // load translations
    this.heyApp.loadTranslations(CameraTranslations);

    // platform ready
    this.platform.ready().then(() => {
    });
  }



}
