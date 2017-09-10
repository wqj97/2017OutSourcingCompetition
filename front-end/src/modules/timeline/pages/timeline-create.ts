import {Component} from '@angular/core';
import {NavController, NavParams, ViewController} from 'ionic-angular';
import {Http, Headers, RequestOptions} from '@angular/http';
import {ImagePicker} from '@ionic-native/image-picker';
import {Transfer} from '@ionic-native/transfer';
import {Camera} from '@ionic-native/camera';

import {Timeline} from '../models/timeline.model';
import {TimelineImg} from '../models/timelineImg.model';

import {AppService} from '../../common/services/app.service';
import {TimelineService} from '../services/timeline.service';

import {ToastController} from 'ionic-angular';

@Component({
    selector: 'page-timeline-create',
    templateUrl: 'timeline-create.html',
})
export class TimelineCreatePage {
    newTimeline: { content?: string } = {};

    //
    waiting: boolean = false;

    //
    imgs: TimelineImg[] = [];

    //
    video: any;

    //
    imgIdArr: number[] = [];

    timeline: Timeline;

    fromCameraFile?: string

    headers: Headers;
    requestOptions: RequestOptions;

    //
    // constructor
    constructor(public camera: Camera,
                public http: Http,
                public imagePicker: ImagePicker,
                public transfer: Transfer,
                public heyApp: AppService,
                public timelineService: TimelineService,
                public navCtrl: NavController,
                public viewCtrl: ViewController,
                public navParam: NavParams,
                public toast: ToastController) {
        this.headers = new Headers({'X-Requested-With': 'XMLHttpRequest'});
        this.requestOptions = new RequestOptions({headers: this.headers});
        if (this.navParam.data.picData != undefined) {
            this.uploadImgByBase64(this.navParam.data.picData)
        }
    }


    //
    // timeline create handler
    timelineCreateHandler(ngForm) {
        console.log(this.imgIdArr, ngForm.value.content, this.video);
        if (this.imgIdArr.length || ngForm.value.content || this.video) {
            if (this.waiting) {
                let params = {
                    title: this.heyApp.translateService.instant('Waiting'),
                    subTitle: this.heyApp.translateService.instant('Waiting For Upload Images Or Video'),
                }

                this.heyApp.utilityComp.presentAlter(params);
            } else {
                this.heyApp.utilityComp.presentLoading();

                let data: any = {
                    content: ngForm.value.content,
                    imgs: JSON.stringify(this.imgIdArr),
                    video: this.video ? this.video.id : null,
                };

                this.timelineService.store(data)
                    .then((newTimeline: Timeline) => {
                        this.heyApp.utilityComp.dismissLoading();
                        let toast = this.toast.create({
                            message: '分享成功~',
                            duration: 2000,
                            position: 'top'
                        });
                        toast.present()
                        this.dismiss();
                    });
            }
        } else {
            let params = {
                title: this.heyApp.translateService.instant('Alert'),
                subTitle: this.heyApp.translateService.instant('timeline.Please share something that makes sense'),
            }

            this.heyApp.utilityComp.presentAlter(params);
        }
    }


    //
    // video play
    videoPlay(event) {
        if (event.srcElement.paused) {
            event.srcElement.play();
        } else {
            event.srcElement.pause();
        }
    }

    uploadImgByBase64(data) {
        this.waiting = true;
        let api = this.heyApp.helper.getAPI('timeline/store-base64-img')
        this.http.post(api, {
            base64Img: data
        }, this.headers).toPromise().then(data => {
            this.waiting = false
            this.mergeImgs(data.json().imgs)
        })
    }

    //
    // upload imgs by native camera
    uploadImgsByNativeCamera(type) {
        let options = {
            quality: 60,
            saveToPhotoAlbum: true,
            sourceType: 1,
            mediaType: 0,
            targetWidth: 1200,
            targetHeight: 1600,
        };
        if (type === 'library') {
            options.quality = 100;
            options.saveToPhotoAlbum = false;
            options.sourceType = 0;
            options.mediaType = 2;
        }

        this.camera.getPicture(options).then((result) => {
            this.waiting = true;

            console.log('the file', result);
            this.uploadFileByFileTransfer(result, this.timelineService.timelineStoreImgAPI);
        }, (err) => {
            console.log('Camera getPictures err', err);
        });
    }


    //
    // upload imgs by native library
    uploadImgsByNativeLibrary() {
        let options = {
            width: 1200,
            height: 0,
        };
        this.imagePicker.getPictures(options).then((results) => {
            this.waiting = true;

            for (let i = 0; i < results.length; i++) {
                this.uploadFileByFileTransfer(results[i], this.timelineService.timelineStoreImgAPI);
            }
        }, (err) => {
            console.log('ImagePIcker getPictures err', err);
        });
    }

    //
    // upload imgs by file transfer
    uploadFileByFileTransfer(file, api) {
        const fileTransfer = this.transfer.create();
        let options: any;
        options = {
            fileKey: 'uploads[]',
            fileName: file.replace(/^.*[\\\/]/, ''),
            headers: {},
        }

        fileTransfer.upload(file, api, options)
            .then((ret) => {
                this.waiting = false;

                // merge imgs
                this.mergeImgs(JSON.parse((<any> ret).response).imgs);
            }, (err) => {
                this.waiting = false;
            })
    }


    //
    // upload imgs
    uploadImgs(event) {
        this.waiting = true;
        let files = event.srcElement.files;

        this.heyApp.fileUploadService.upload(this.timelineService.timelineStoreImgAPI, files).then(data => {
            this.waiting = false;

            // merge imgs
            this.mergeImgs(data.imgs);
        }, () => {
            this.waiting = false;
        });
    }


    //
    // merge Imgs
    mergeImgs(imgs) {
        console.log(imgs)
        this.video = null;

        for (let i = 0; i < imgs.length; i++) {
            this.imgIdArr = this.imgIdArr.concat(imgs[i]['id']);
            this.imgs = this.imgs.concat(imgs[i]);
        }
    }


    //
    // upload video
    uploadVideo(event) {
        this.waiting = true;
        let files = event.srcElement.files;
        this.video = null;

        this.heyApp.fileUploadService.upload(this.timelineService.timelineStoreVideoAPI, files).then(data => {
            this.waiting = false;
            this.imgs = data.imgs;
            this.video = data;
            this.imgIdArr = [];
        }, () => {
            this.waiting = false;
        });
    }


    //
    // dismiss
    dismiss() {
        this.viewCtrl.dismiss();

    }


}
