export class FilterModel {

    ctx: any
    pixels: any

    constructor(public canvasElement: HTMLCanvasElement,
                public targetElement: HTMLCanvasElement,
                public targetWidth: number,
                public targetHeight: number) {
        this.ctx = this.canvasElement.getContext('2d')
    }

    getPixels() {
        if (this.pixels) {
            return this.pixels
        }
        this.pixels = this.ctx.getImageData(0, 0, this.canvasElement.width, this.canvasElement.height)
        return this.pixels
    }

    filterImage(brightnessValue, contrastValue, blurValue) {
        let pixels = this.getPixels()
        // 调整对比度, 亮度
        pixels = this.brightnessContrast(pixels, brightnessValue, contrastValue)
        pixels = this.gaussianBlur(pixels, blurValue)
        this.targetElement.getContext('2d').putImageData(pixels, 0, 0)
    }

    brightnessContrast(pixels, brightness, contrast) {
        let lut = this.brightnessContrastLUT(brightness, contrast)
        return this.applyLUT(pixels, {r: lut, g: lut, b: lut, a: this.identityLUT()})
    }

    brightnessContrastLUT(brightness, contrast) {
        let lut = this.getUint8Array(256)
        let contrastAdjust = -128 * contrast + 128
        let brightnessAdjust = 255 * brightness
        let adjust = contrastAdjust + brightnessAdjust
        for (let i = 0; i < lut.length; i++) {
            let c = i * contrast + adjust
            lut[i] = c < 0 ? 0 : (c > 255 ? 255 : c)
        }
        return lut
    }

    applyLUT(pixels, lut) {
        let output = this.createImageData(pixels.width, pixels.height)
        let d = pixels.data
        let dst = output.data
        let r = lut.r
        let g = lut.g
        let b = lut.b
        let a = lut.a
        for (let i = 0; i < d.length; i += 4) {
            dst[i] = r[d[i]]
            dst[i + 1] = g[d[i + 1]]
            dst[i + 2] = b[d[i + 2]]
            dst[i + 3] = a[d[i + 3]]
        }
        return output
    }

    gaussianBlur(pixels, diameter) {
        diameter = Math.abs(diameter)
        if (diameter <= 1) return this.identity(pixels, null)
        let radius = diameter / 2
        let len = Math.ceil(diameter) + (1 - (Math.ceil(diameter) % 2))
        let weights = this.getFloat32Array(len)
        let rho = (radius + 0.5) / 3
        let rhoSq = rho * rho
        let gaussianFactor = 1 / Math.sqrt(2 * Math.PI * rhoSq)
        let rhoFactor = -1 / (2 * rho * rho)
        let wsum = 0
        let middle = Math.floor(len / 2)
        for (let i = 0; i < len; i++) {
            let x = i - middle
            let gx = gaussianFactor * Math.exp(x * x * rhoFactor)
            weights[i] = gx
            wsum += gx
        }
        for (let i = 0; i < weights.length; i++) {
            weights[i] /= wsum
        }
        return this.separableConvolve(pixels, weights, weights, false)
    }

    identity(pixels, args) {
        let output = this.createImageData(pixels.width, pixels.height)
        let dst = output.data
        let d = pixels.data
        for (let i = 0; i < d.length; i++) {
            dst[i] = d[i]
        }
        return output
    }

    separableConvolve(pixels, horizWeights, vertWeights, opaque) {
        return this.horizontalConvolve(
            this.verticalConvolveFloat32(pixels, vertWeights, opaque),
            horizWeights, opaque
        )
    }

    verticalConvolveFloat32(pixels, weightsVector, opaque) {
        let side = weightsVector.length
        let halfSide = Math.floor(side / 2)

        let src = pixels.data
        let sw = pixels.width
        let sh = pixels.height

        let w = sw
        let h = sh
        let output = this.createImageData(w, h)
        let dst = output.data

        let alphaFac = opaque ? 1 : 0

        for (let y = 0; y < h; y++) {
            for (let x = 0; x < w; x++) {
                let sy = y
                let sx = x
                let dstOff = (y * w + x) * 4
                let r = 0, g = 0, b = 0, a = 0
                for (let cy = 0; cy < side; cy++) {
                    let scy = Math.min(sh - 1, Math.max(0, sy + cy - halfSide))
                    let srcOff = (scy * sw + sx) * 4
                    let wt = weightsVector[cy]
                    r += src[srcOff] * wt
                    g += src[srcOff + 1] * wt
                    b += src[srcOff + 2] * wt
                    a += src[srcOff + 3] * wt
                }
                dst[dstOff] = r
                dst[dstOff + 1] = g
                dst[dstOff + 2] = b
                dst[dstOff + 3] = a + alphaFac * (255 - a)
            }
        }
        return output
    }

    horizontalConvolve(pixels, weightsVector, opaque) {
        let side = weightsVector.length
        let halfSide = Math.floor(side / 2)

        let src = pixels.data
        let sw = pixels.width
        let sh = pixels.height

        let w = sw
        let h = sh
        let output = this.createImageData(w, h)
        let dst = output.data

        let alphaFac = opaque ? 1 : 0

        for (let y = 0; y < h; y++) {
            for (let x = 0; x < w; x++) {
                let sy = y
                let sx = x
                let dstOff = (y * w + x) * 4
                let r = 0, g = 0, b = 0, a = 0
                for (let cx = 0; cx < side; cx++) {
                    let scy = sy
                    let scx = Math.min(sw - 1, Math.max(0, sx + cx - halfSide))
                    let srcOff = (scy * sw + scx) * 4
                    let wt = weightsVector[cx]
                    r += src[srcOff] * wt
                    g += src[srcOff + 1] * wt
                    b += src[srcOff + 2] * wt
                    a += src[srcOff + 3] * wt
                }
                dst[dstOff] = r
                dst[dstOff + 1] = g
                dst[dstOff + 2] = b
                dst[dstOff + 3] = a + alphaFac * (255 - a)
            }
        }
        return output
    }

    getFloat32Array(len) {
        return new Float32Array(len)
    }

    getUint8Array(len) {
        return new Uint8Array(len)
    }

    createImageData(w, h) {
        return this.ctx.createImageData(w, h)
    }

    identityLUT() {
        let lut = this.getUint8Array(256)
        for (let i = 0; i < lut.length; i++) {
            lut[i] = i
        }
        return lut
    }
}
