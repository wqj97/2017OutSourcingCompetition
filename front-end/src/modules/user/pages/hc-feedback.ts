import {Component, ViewChild} from '@angular/core'
import {NavController, ViewController, NavParams} from 'ionic-angular'
import {Http, Headers, RequestOptions} from '@angular/http'
import {Helper} from '../../common/services/helper.service'
import {ToastController} from 'ionic-angular'
import {AppService} from '../../common/services/app.service'

@Component({
    selector: 'page-hc-feedback',
    templateUrl: 'hc-feedback.html'
})
export class HCFeedbackPage {
    @ViewChild('inputFeedBack') inputCommentEl
    feedbackContent?: string
    requestOptions: RequestOptions
    headers: Headers
    timelineId?: number
    //
    // constructor
    constructor(public navCtrl: NavController,
                public http: Http,
                public helper: Helper,
                public viewCtrl: ViewController,
                public toast: ToastController,
                public heyApp: AppService,
                public navParam: NavParams) {
        this.headers = new Headers({'X-Requested-With': 'XMLHttpRequest'})
        this.requestOptions = new RequestOptions({headers: this.headers})
        console.log(navParam)
        if (navParam.data.timeline) {
            this.timelineId = navParam.data.timeline.id
        }
    }

    ionViewDidEnter() {
        this.inputCommentEl.setFocus()
    }

    sendFeedBack() {
        let userId = null
        if (this.heyApp.authService.userInfo != null) {
            userId = this.heyApp.authService.userInfo.id
        }
        let api: string = this.helper.getAPI('feedBack')
        this.http.post(api, {
            content: this.feedbackContent,
            userId: userId,
            timeLineId: this.timelineId
        }, this.requestOptions).toPromise().then(() => {
            let toast = this.toast.create({
                message: '我们收到您的宝贵意见了, 感谢你的反馈',
                duration: 2000,
                position: 'top'
            })
            toast.present()
            this.viewCtrl.dismiss()
        }).catch(() => {
            let toast = this.toast.create({
                message: '网络连接不通畅, 请稍后再试',
                duration: 2000,
                position: 'top'
            })
            toast.present()
        })
    }
}
