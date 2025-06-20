import { Component, OnInit } from '@angular/core';
import { NavController, AlertController } from '@ionic/angular';


@Component({
  standalone: false,
  selector: 'app-rating',
  templateUrl: './rating.page.html',
  styleUrls: ['./rating.page.scss'],
})
export class RatingPage implements OnInit {
  rating = 0;
  stars = new Array(5);
  selected: string = '';

  constructor(private navCtrl: NavController, private alertController: AlertController) { }

  ngOnInit() {
    this.selected = ''; // Reset saat awal component dibuka pertama kali
  }

  ionViewWillEnter() {
    this.selected = ''; // Reset saat halaman kembali aktif
  }

  setRating(value: number) {
    this.rating = value;
  }

  submitRating() {
    console.log('Rating diberikan:', this.rating);
    this.navCtrl.navigateRoot('/dasboard-home');
  }

  dismiss() {
    this.navCtrl.back();
  }

  selectMethod(method: string) {
    this.selected = method;
  }

  async confirmMethod() {
    if (this.selected) {
      console.log('Metode dipilih:', this.selected);
      this.navCtrl.navigateForward('/confirm-rating');
    } else {
      const alert = await this.alertController.create({
        header: 'Input Rating',
        message: 'Silakan input rating terlebih dahulu.',
        buttons: [
          {
            text: 'Oke',
            role: 'cancel',
            cssClass: 'elegant-alert-button',
          },
        ],
        cssClass: 'elegant-alert',
        backdropDismiss: false,
      });
      await alert.present();
    }
  }
}
