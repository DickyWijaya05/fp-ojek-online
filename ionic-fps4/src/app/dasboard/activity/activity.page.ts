import { Component, OnInit } from '@angular/core';
import { AlertController } from '@ionic/angular';

@Component({
  standalone: false,
  selector: 'app-activity',
  templateUrl: './activity.page.html',
  styleUrls: ['./activity.page.scss'],
})
export class ActivityPage implements OnInit {
  selected: string = '';

  constructor(private alertController: AlertController) {}

  ngOnInit() {
    this.selected = ''; // Reset saat awal component dibuka pertama kali
  }

  ionViewWillEnter() {
    this.selected = ''; // Reset saat halaman kembali aktif
  }

  selectMethod(method: string) {
    this.selected = method;
  }

  async confirmMethod() {
    if (this.selected) {
      console.log('Metode dipilih:', this.selected);
      // navigasi atau logika lanjutan bisa ditambahkan di sini
    } else {
      const alert = await this.alertController.create({
        header: 'Pilih Metode Pembayaran',
        message: 'Silakan pilih metode pembayaran terlebih dahulu.',
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