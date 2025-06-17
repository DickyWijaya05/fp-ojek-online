import { Component, OnInit } from '@angular/core';
import { NavController, AlertController } from '@ionic/angular';

@Component({
  standalone: false,
  selector: 'app-cancel-ride',
  templateUrl: './cancel-ride.page.html',
  styleUrls: ['./cancel-ride.page.scss'],
})
export class CancelRidePage implements OnInit {
  selectedReason: string = '';
  reasons: string[] = [
    'Mau ganti rencana',
    'Driver terlalu lama datang ke lokasi',
    'Tidak bisa menghubungi driver',
    'Keadaan darurat',
    'Lainnya'
  ];

  constructor(
    private navCtrl: NavController,
    private alertController: AlertController
  ) { }

  ngOnInit() {
    this.selectedReason = ''; // Reset saat awal component dibuka pertama kali
  }

  ionViewWillEnter() {
    this.selectedReason = ''; // Reset saat halaman kembali aktif
  }

  dismiss() {
    // Jika kamu ingin menutup modal/popover, bisa tambahkan logika di sini nanti
  }

  async confirmCancel() {
    if (this.selectedReason) {
      console.log('Alasan pembatalan:', this.selectedReason);
      this.navCtrl.navigateForward('/canceled');
    } else {
      const alert = await this.alertController.create({
        header: 'Cancel Ride',
        message: 'Silakan pilih alasan pembatalan terlebih dahulu.',
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
