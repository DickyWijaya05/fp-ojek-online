import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  standalone:false,
  selector: 'app-login-option',
  templateUrl: './login-option.page.html',
  styleUrls: ['./login-option.page.scss'],
})
export class LoginOptionPage {
  constructor(private navCtrl: NavController) { }

  pilihPeran(peran: string) {
    if (peran === 'penumpang') {
      this.navCtrl.navigateForward('login-costumer'); // ke form register penumpang
    } else if (peran === 'pengemudi') {
      this.navCtrl.navigateForward('/login'); // ke halaman register pengemudi
    } else {
      this.navCtrl.navigateForward(`/landing-page-option/${peran}`); // fallback kalau ada peran lain
    }
  }
}
