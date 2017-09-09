import {Component, ViewChild} from '@angular/core'
import {AppService} from "../../common/services/app.service";
import {StatusBar} from '@ionic-native/status-bar';
import {Camera} from '@ionic-native/camera'
import {FilterModel} from '../models/filter.model'
import Draggabilly from 'Draggabilly'
import {CameraPage} from "../../camera/pages/camera"
import {LoadingController, NavController} from 'ionic-angular'

@Component({
    selector: 'takePic',
    templateUrl: 'takePic.html'
})
export class TakePicPage {

    brightnessValue: number = 0
    contrastValue: number = 0
    blurValue: number = 0

    imageCtx?: any

    filter: any

    filterStyle?: object

    stickerListUrl: Array<string> = ['1.png', '3.png', '4.png', '5.png', '6.png', '7.png', '8.png', '9.png']

    cameraRawImageData: string

    @ViewChild('cameraBox') cameraBox
    @ViewChild('cameraPicture') cameraPicture

    @ViewChild('brightness') brightnessSlider
    @ViewChild('contrast') contrastSlider
    @ViewChild('sticker') stickerList
    @ViewChild('blur') blurSlider
    @ViewChild('save') saveMenu

    @ViewChild('targetCanvas') targetCanvas

    @ViewChild('stickerImage') stickerImage

    constructor(public heyApp: AppService,
                public camera: Camera,
                public statusBar: StatusBar,
                public loading: LoadingController,
                public navCtrl: NavController) {
    }

    ionViewDidLoad() {
        this.applyCanvasWidth(this.cameraPicture.nativeElement, window.screen.width, window.screen.width)
        this.applyCanvasWidth(this.targetCanvas.nativeElement, window.screen.width, window.screen.width)
        new Draggabilly(this.stickerImage.nativeElement, {
            containment: this.cameraBox.nativeElement
        })
    }

    applyCanvasWidth(element, width, height) {
        element.width = width
        element.height = height
    }

    changedExtraHandler() {
        this.filterStyle = {
            filter: `brightness(${1 + this.brightnessValue / 100}) contrast(${1 + this.contrastValue / 100}) blur(${this.blurValue}px)`
        }
    }

    ionViewDidEnter() {
        this.statusBar.styleLightContent()
        this.openCamera()
    }

    openCamera() {
        let option = {
            quality: 100,
            allowEdit: true,
            targetWidth: 600,
            targetHeight: 600,
            destinationType: 0,
        }
        this.camera.getPicture(option).then((imageData) => {
            let image = new Image()
            image.onload = () => {
                let ctx = this.cameraPicture.nativeElement.getContext('2d')
                this.imageCtx = ctx
                ctx.drawImage(image, 0, 0, window.screen.width, window.screen.width)
            }
            this.cameraRawImageData = 'data:image/jpeg;base64,' + imageData
            image.src = this.cameraRawImageData
            this.filter = new FilterModel(this.cameraPicture.nativeElement, this.targetCanvas.nativeElement, window.screen.width, window.screen.width)
        }).catch(() => {
            this.navCtrl.parent.select(1)
        })
    }

    savePicture() {
        let saveLoading = this.loading.create({
            content: '正在保存图片...请稍后'
        })

        let promise = new Promise((resolve, reject) => {
            saveLoading.present()
            this.filter.filterImage(this.brightnessValue / 100, 1 + this.contrastValue / 100, this.blurValue * 1.5)
            this.addStikerToSave(resolve)
        })
        promise.then(() => {
            saveLoading.dismiss()
            this.openCameraPage(this.targetCanvas.nativeElement.toDataURL())
        })
    }

    openCameraPage(imageData) {
        let page = CameraPage
        let params = {
            picData: imageData
        }
        this.statusBar.styleDefault()
        this.heyApp.utilityComp.presentModal(page, params)
    }

    showSlider(sliderType, el) {
        document.querySelector('takepic .menu .active').classList.remove('active')
        el.target.parentNode.classList.add('active')
        document.querySelector('takepic .slider .active').classList.remove('active')
        switch (sliderType) {
            case 'save':
                this.saveMenu.nativeElement.classList.add('active')
                break
            case 'sticker':
                this.stickerList.nativeElement.classList.add('active')
                break
            default:
                this[`${sliderType}Slider`]._elementRef.nativeElement.classList.add('active')
                break
        }
    }

    addSticker(index) {
        let element = this.stickerImage.nativeElement
        element.style.display = 'block'
        element.src = './assets/images/sticker/' + this.stickerListUrl[index]
    }

    addStikerToSave(resolve) {
        let canvas = this.targetCanvas.nativeElement
        let imageElement = this.stickerImage.nativeElement

        let ctx = canvas.getContext('2d')
        let image = new Image()

        image.onload = () => {
            ctx.drawImage(image, imageElement.offsetLeft, imageElement.offsetTop, imageElement.width, imageElement.height)
            resolve()
        }
        if (imageElement.src) {
            image.src = imageElement.src
        } else {
            resolve()
        }
    }

    clearSticker() {
        this.stickerImage.nativeElement.style.display = 'none'
    }

    chooseImage() {
        let options = {
            destinationType: 0,
            sourceType: 0,
            allowEdit: true,
            quality: 100
        }
        this.camera.getPicture(options).then((result) => {
            let image = new Image()
            image.onload = () => {
                let ctx = this.cameraPicture.nativeElement.getContext('2d')
                this.imageCtx = ctx
                ctx.drawImage(image, 0, 0, window.screen.width, window.screen.width)
            }
            this.cameraRawImageData = 'data:image/jpeg;base64,' + result
            image.src = this.cameraRawImageData
            this.filter = new FilterModel(this.cameraPicture.nativeElement, this.targetCanvas.nativeElement, window.screen.width, window.screen.width)
        })
    }

    retake() {
        this.openCamera()
    }

    ionViewWillLeave() {
        this.statusBar.styleDefault()
    }


}
