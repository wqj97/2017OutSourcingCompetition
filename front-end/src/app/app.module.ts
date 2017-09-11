import {NgModule, ErrorHandler} from '@angular/core'
import {IonicApp, IonicModule, IonicErrorHandler} from 'ionic-angular'
import {BrowserModule} from '@angular/platform-browser'
import {HttpModule} from '@angular/http'
import {IonicStorageModule} from '@ionic/storage'
import {MyApp} from './app.component'

import {Badge} from '@ionic-native/badge'
import {StatusBar} from '@ionic-native/status-bar'
import {SplashScreen} from '@ionic-native/splash-screen'
import {Transfer} from '@ionic-native/transfer'
import {InAppBrowser} from '@ionic-native/in-app-browser'

import {HayCommonModule} from '../modules/common/common.module'
import {UserModule} from '../modules/user/user.module'
import {NoticeModule} from '../modules/notice/notice.module'
import {TimelineModule} from '../modules/timeline/timeline.module'
import {TopicModule} from '../modules/topic/topic.module'
import {CameraModule} from "../modules/camera/camera.module"
import {takePicModule} from "../modules/takePic/takePicmodule"
import {Base64ToGallery} from '@ionic-native/base64-to-gallery'

import {TabsPage} from '../pages/tabs/tabs'

@NgModule({
    declarations: [
        MyApp,
        TabsPage
    ],
    imports: [
        BrowserModule,
        HttpModule,
        IonicModule.forRoot(MyApp, {
            backButtonText: '',
            tabsHideOnSubPages: true,
            tabbarPlacement: 'bottom',
            backButtonIcon: 'arrow-round-back',
            backButtonColor: 'dark',
        }),
        IonicStorageModule.forRoot(),
        HayCommonModule,
        UserModule,
        NoticeModule,
        TimelineModule,
        TopicModule,
        CameraModule,
        takePicModule
    ],
    bootstrap: [IonicApp],
    entryComponents: [
        MyApp,
        TabsPage
    ],
    providers: [
        StatusBar,
        SplashScreen,
        Badge,
        Transfer,
        InAppBrowser,
        Base64ToGallery,
        {provide: ErrorHandler, useClass: IonicErrorHandler},
    ]
})
export class AppModule {
}
