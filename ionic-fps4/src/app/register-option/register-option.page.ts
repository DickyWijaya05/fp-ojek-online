import { Component } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  standalone: false,
  selector: 'app-register-option',
  templateUrl: './register-option.page.html',
  styleUrls: ['./register-option.page.scss'],
})
export class RegisterOptionPage {
  constructor(private navCtrl: NavController) { }

  pilihPeran(peran: string) {
    // ✅ Tambahan baris ini untuk menghindari error aria-hidden
    (document.activeElement as HTMLElement)?.blur();

    if (peran === 'penumpang') {
      this.navCtrl.navigateForward('/form-register'); // ke form register penumpang
    } else if (peran === 'pengemudi') {
      this.navCtrl.navigateForward('/registers'); // ke halaman register pengemudi
    } else {
      this.navCtrl.navigateForward(`/register/${peran}`); // fallback kalau ada peran lain
    }
  }
}
