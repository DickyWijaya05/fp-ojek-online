import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
@Component({
  standalone: false,
  selector: 'app-cenceled',
  templateUrl: './canceled.page.html',
  styleUrls: ['./canceled.page.scss'],
})
export class CanceledPage implements OnInit {

  constructor(private navCtrl: NavController ) { }

  ngOnInit() {
  }
dismiss() {
  this.navCtrl.back(); // atau modalController.dismiss() jika menggunakan modal
}

goHome() {
  this.navCtrl.navigateRoot('/dasboard'); // arahkan ke halaman utama
}

}

