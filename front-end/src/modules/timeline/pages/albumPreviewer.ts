import {Component} from '@angular/core';
import {NavController, NavParams} from 'ionic-angular';

import {AppService} from '../../common/services/app.service';
import {ToastController, ActionSheetController} from 'ionic-angular';

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
                public toast: ToastController) {
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

    ionViewWillLeave() {
        this.previewLeave = true
        this.timeLine.content='公园'
    }

    presentActionSheet() {
        let actionSheet = this.actionSheet.create({
            title: '举报',
            buttons: [
                {
                    text: '举报违规内容',
                    role: 'destructive',
                    handler: () => {
                        this.heyApp.utilityComp.presentAlter({title: this.heyApp.translateService.instant('Report'), subTitle: this.heyApp.translateService.instant('Thanks For Your Report')});
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
