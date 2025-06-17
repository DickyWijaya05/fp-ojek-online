import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  standalone: false,
  selector: 'app-confirm-rating',
  templateUrl: './confirm-rating.page.html',
  styleUrls: ['./confirm-rating.page.scss'],
})
export class ConfirmRatingPage implements OnInit {

  constructor(private navCtrl: NavController ) { }

  ngOnInit() {
  }
  goHome() {
  this.navCtrl.navigateRoot('/dasboard'); // arahkan ke halaman utama
}
}
