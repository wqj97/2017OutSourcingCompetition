import { Injectable } from '@angular/core';
import { Headers, RequestOptions } from '@angular/http';
import 'rxjs/add/operator/toPromise';

import { Helper } from '../../common/services/helper.service';


@Injectable()
export class TakePicService {
  headers: Headers;
  requestOptions: RequestOptions;
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
