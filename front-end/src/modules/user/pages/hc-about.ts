import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';

import { AppService } from '../../common/services/app.service';

@Component({
  selector: 'page-hc-about',
  templateUrl: 'hc-about.html'
})
export class HCAboutPage {


  //
  // constructor
  constructor(
    public heyApp: AppService,
    public navCtrl: NavController
  ) {
  }


  //
  // present hc-debug modal
}
