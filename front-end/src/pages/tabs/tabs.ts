import {Component} from '@angular/core'
import {Events, Platform, NavController} from 'ionic-angular'

import {AppService} from '../../modules/common/services/app.service'
import {UserService} from '../../modules/user/services/user.service'
import {NoticeService} from '../../modules/notice/services/notice.service'
import {CameraService} from '../../modules/camera/services/camera.service'
import {TakePicService} from "../../modules/takePic/services/takePic.service"

import {CameraPage} from '../../modules/camera/pages/camera'
import {TimelinePage} from '../../modules/timeline/pages/timeline'
import {TopicPage} from '../../modules/topic/pages/topic'
import {MePage} from '../../modules/user/pages/me'
import {TakePicPage} from "../../modules/takePic/pages/takePic"

@Component({
    templateUrl: 'tabs.html'
})
export class TabsPage {
    // this tells the tabs component which Pages
    // should be each tab's root Page
    timelineTabRoot: any = TimelinePage
    topicTabRoot: any = TopicPage
    userTabRoot: any = MePage
    cameraTabRoot: any = CameraPage
    takePicTabRoot: any = TakePicPage

    constructor(public platform: Platform,
                public events: Events,
                public heyApp: AppService,
                public navCtrl: NavController,
                public userService: UserService,
                public noticeService: NoticeService,
                public cameraService: CameraService,
                public takePicService: TakePicService) {
        this.subscribeEvents()
    }


    //
    // Subscribe events
    subscribeEvents() {
        //
        // subscribe app gotoPage
        this.events.subscribe('app:gotoPage', (params) => {
            this.navCtrl.push(params.page)
        })
    }
}
