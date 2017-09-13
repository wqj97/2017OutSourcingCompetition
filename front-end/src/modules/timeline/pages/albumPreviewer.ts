import {Component} from '@angular/core';
import {NavController, NavParams} from 'ionic-angular';

import {AppService} from '../../common/services/app.service';
import {ActionSheetController} from 'ionic-angular';

import {StatusBar} from '@ionic-native/status-bar'

import {HCFeedbackPage} from '../../user/pages/hc-feedback'


@Component({
    selector: 'album-preview',
    templateUrl: 'albumPreviewer.html',
})
export class AlbumPreviewerPage {
    albumImages: Array<string> = []
    timeLine: any
    previewLeave: boolean = false
    //
    // constructor
    constructor(public heyApp: AppService,
                public navCtrl: NavController,
                public navParams: NavParams,
                public actionSheet: ActionSheetController,
                public statusBar: StatusBar) {
        this.timeLine = navParams.data.timeline
        if (this.timeLine.content.length > 5) {
            this.timeLine.content = this.timeLine.content.substring(0, 5) + '...'
        }
        console.log(navParams.data.timeline)
        let srcs = []
        navParams.data.timeline.imgs.forEach(img => {
            srcs.push(img.uri)
        })
        this.albumImages = srcs
    }

    ionViewWillEnter() {
        this.statusBar.styleLightContent()
        this.previewLeave = false
        if (this.timeLine.content.length > 5) {
            this.timeLine.content = this.timeLine.content.substring(0, 5) + '...'
        }
    }

    ionViewWillLeave() {
        this.statusBar.styleDefault()
        this.previewLeave = true
        this.timeLine.content = '公园'
    }

    ionViewWillUnload() {

    }

    presentActionSheet() {
        let actionSheet = this.actionSheet.create({
            title: '举报',
            buttons: [
                {
                    text: '举报违规内容',
                    role: 'destructive',
                    handler: () => {
                        this.navCtrl.push(HCFeedbackPage, {
                            timeline: this.timeLine
                        })
                    }
                },
                {
                    text: '取消',
                    role: 'cancel',
                    handler: () => {
                    }
                }
            ]
        });

        actionSheet.present();
    }
}
