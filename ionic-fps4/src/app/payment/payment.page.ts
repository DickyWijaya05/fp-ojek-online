import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-payment',
  templateUrl: './payment.page.html',
  styleUrls: ['./payment.page.scss'],
  standalone: false,
})
export class PaymentPage implements OnInit {
  selected: string = '';
  orderId: number | null = null;
  qrisUrl: string | null = null;

  constructor(
    private alertController: AlertController,
    private route: ActivatedRoute,
    private http: HttpClient,
    private router: Router
  ) {}

  ngOnInit() {
    this.selected = '';

    // Ambil orderId dari route param
    const orderIdParam = this.route.snapshot.queryParamMap.get('orderId') || this.route.snapshot.paramMap.get('id');
    this.orderId = orderIdParam ? Number(orderIdParam) : null;

    if (this.orderId) {
      this.getDriverQris(this.orderId);
    } else {
      console.error('❌ Order ID tidak ditemukan!');
    }
  }

  ionViewWillEnter() {
    this.selected = '';
  }

  selectMethod(method: string) {
    this.selected = method;
  }

  async confirmMethod() {
  if (!this.selected) {
    const alert = await this.alertController.create({
      header: '💳 Metode Belum Dipilih',
      message: `
        <div class="alert-custom">
          Silakan pilih metode pembayaran terlebih dahulu untuk melanjutkan. 😊
        </div>
      `,
      buttons: ['OK'],
      cssClass: 'elegant-alert',
      backdropDismiss: false,
    });
    return await alert.present();
  }

  const alert = await this.alertController.create({
    header: '✅ Konfirmasi Pembayaran',
    message: `
      <div class="alert-custom">
        Kamu memilih metode <strong>${this.selected.toUpperCase()}</strong>.<br><br>
        Silakan <strong>lakukan pembayaran</strong> dan <strong>konfirmasi ke driver</strong> ya! 🙌
      </div>
    `,
    buttons: [
      {
        text: 'Siap!',
        cssClass: 'alert-button-confirm',
        handler: () => {
          this.router.navigate(['/dasboard']);
        }
      }
    ],
    cssClass: 'elegant-alert',
    backdropDismiss: false,
  });

  await alert.present();
}


  getDriverQris(orderId: number) {
    const token = localStorage.getItem('token');

    if (!token) {
      console.error('❌ Token passenger atau driver tidak ditemukan.');
      return;
    }

    this.http.get<{ foto_qris: string }>(`http://localhost:8000/api/order/${orderId}/driver-qris`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe({
      next: (res) => {
        this.qrisUrl = res.foto_qris;
      },
      error: (err) => {
        console.error('❌ Gagal memuat QRIS:', err);
      }
    });
  }
}
