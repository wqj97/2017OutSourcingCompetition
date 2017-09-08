import {Component, ViewChild} from '@angular/core'
import {Http, Headers, RequestOptions} from '@angular/http'
import {Helper} from '../../common/services/helper.service'
import {Camera} from '@ionic-native/camera'
import {Base64ToGallery} from '@ionic-native/base64-to-gallery'
import {AppService} from '../../common/services/app.service'
import {TimelineCreatePage} from "../../timeline/pages/timeline-create"
import {ToastController, ActionSheetController, NavParams, ViewController, LoadingController} from 'ionic-angular'
import {StatusBar} from '@ionic-native/status-bar'

@Component({
    selector: 'camera',
    templateUrl: 'camera.html'
})
export class CameraPage {
    styledPic: any = null
    cameraRaw?: string = ''

    styleCategory?: Object
    styleRecommand?: Array<Object>
    styles?: Object
    headers: Headers
    requestOptions: RequestOptions
    cameraPicStyle: Object = {
        width: '100%'
    }

    @ViewChild('styleList') styleList

    // constructor
    constructor(public camera: Camera,
                public http: Http,
                public helper: Helper,
                public Loading: LoadingController,
                public heyApp: AppService,
                public base64ToGallery: Base64ToGallery,
                public tost: ToastController,
                public navParam: NavParams,
                public statusBar: StatusBar,
                public viewController: ViewController,
                public actionSheet: ActionSheetController) {
        this.headers = new Headers({'X-Requested-With': 'XMLHttpRequest'})
        this.requestOptions = new RequestOptions({headers: this.headers})
        this.cameraRaw = navParam.data.picData
    }

    ionViewDidLoad() {
        let url: string = this.helper.getStyleAPI('list')
        this.http.get(url, this.requestOptions).toPromise().then(data => {
            let result = data.json()
            this.styleRecommand = result.recommend
            this.styleCategory = result.category
            this.styles = result.recommend
        })
    }
    ionViewWillLeave() {
        this.statusBar.styleLightContent()
    }
    goShare() {
        let actionSheet = this.actionSheet.create({
            title: '分享',
            buttons: [
                {
                    text: '保存到相册',
                    handler: () => {
                        this.saveInAlbum()
                    }
                },
                {
                    text: '分享到公园',
                    handler: () => {
                        this.saveInAlbum()
                        this.goCreateTimeLine()
                    }
                },
                {
                    text: '分享到朋友圈',
                    handler: () => {
                        let toast = this.tost.create({
                            message: '暂时无法连接到朋友圈',
                            duration: 2000,
                            position: 'top'
                        })
                        toast.present()
                    }
                },
                {
                    text: '取消',
                    role: 'cancel',
                    handler: () => {
                    }
                }
            ]
        })

        actionSheet.present()
    }

    saveInAlbum() {
        let toast = this.tost.create({
            message: '相片保存成功',
            duration: 2000,
            position: 'bottom'
        })
        this.base64ToGallery.base64ToGallery(this.styledPic, {prefix: 'ArcInPic_'}).then(res => {
            toast.present()
        }, err => {
            console.log(err)
        })
    }

    goCreateTimeLine() {
        if (this.heyApp.authService.authOrLogin()) {
            let page = TimelineCreatePage
            let params = {
                picData: this.styledPic
            }
            this.heyApp.utilityComp.presentModal(page, params)
        }
    }

    showCategoryStyles(index, el) {
        let target = window.document.querySelector('.category-active')
        this.styleList.nativeElement.scrollLeft = 0
        if (target) {
            target.classList.remove('category-active')
        }
        el.target.classList.add('category-active')
        if (index == 'recommand') {
            this.styles = this.styleRecommand
        } else {
            this.styles = this.styleCategory[index].style_list
        }
    }

    getStyled(Id) {
        let url: string = this.helper.getStyleAPI('eval')
        let loading = this.Loading.create({
            content: '有人说画画是在写诗~<br />我写诗不到30s☕️'
        })
        loading.present()
        this.http.post(url, {
            'image': this.cameraRaw,
            styleId: Id
        }, this.requestOptions).toPromise().then(data => {
            loading.dismiss()
            this.styledPic = 'data:image/jpeg;base64,' + data.text()
        }).catch(() => {
            loading.dismiss()
            let toast = this.tost.create({
                message: '哦天哪...没等我画完, 网络就把我电源掐了, 要不, 让我再试试~',
                duration: 2000,
                position: 'bottom'
            })
            toast.present()
        })
    }

    getImage() {
        let options = {
            destinationType: 0,
            sourceType: 0,
            allowEdit:true,
            quality: 100
        }
        this.camera.getPicture(options).then((result) => {
            this.cameraRaw = 'data:image/jpeg;base64,' + result
            let img = new Image()
            img.src = this.cameraRaw
            img.onload = () => {
                if (img.height > img.width) {
                    this.cameraPicStyle = {
                        height: '100%'
                    }
                } else {
                    this.cameraPicStyle = {
                        width: '100%'
                    }
                }
            }
            this.styledPic = null
        }, () => {
            let toast = this.tost.create({
                message: '读取相片失败, 要不换张试试',
                duration: 2000,
                position: 'bottom'
            })
            toast.present()
        }).catch(() => {
            let toast = this.tost.create({
                message: '没有选择相片',
                duration: 1000,
                position: 'bottom'
            })
            toast.present()
        })
    }

    openCamera() {
        this.viewController.dismiss()
    }
}
