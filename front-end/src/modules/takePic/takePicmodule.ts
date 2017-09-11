import {NgModule} from '@angular/core';
import {Platform, Events} from 'ionic-angular';
import {HayCommonModule} from '../common/common.module';

import {AppService} from '../common/services/app.service';
import {TakePicService} from "./services/takePic.service"
import {TakePicTranslations} from "./i18n/takePic-translations"
import {TakePicPage} from "./pages/takePic";


@NgModule({
    imports: [
        HayCommonModule,
    ],
    declarations: [
        TakePicPage,
    ],
    entryComponents: [
        TakePicPage,
    ],
    providers: [
        TakePicService,
    ],
    exports: [],
})
export class takePicModule {
    constructor(public platform: Platform,
                public events: Events,
                public heyApp: AppService) {
        // load translations
        this.heyApp.loadTranslations(TakePicTranslations);

        // platform ready
        this.platform.ready().then(() => {
        });
    }


}
