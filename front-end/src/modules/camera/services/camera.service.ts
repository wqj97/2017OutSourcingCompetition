import { Injectable } from '@angular/core';
import { Headers, RequestOptions } from '@angular/http';
import 'rxjs/add/operator/toPromise';

import { CameraPageModel } from '../models/cameraPage.model';
import { Helper } from '../../common/services/helper.service';


@Injectable()
export class CameraService {
  headers: Headers;
  requestOptions: RequestOptions;
  cameraPage: CameraPageModel;
  userUpdateAvatarAPI: string = this.helper.getAPI('camera');


  //
  // constructor
  constructor(
    private helper: Helper
  ) {
    this.headers = new Headers({'X-Requested-With': 'XMLHttpRequest'});
    this.requestOptions = new RequestOptions({headers: this.headers});
  }

}
