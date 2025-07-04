import { Component, OnInit } from '@angular/core';
import { NavController, AlertController, ToastController } from '@ionic/angular';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute } from '@angular/router';
import { environment } from 'src/environments/environment';

@Component({
  standalone: false,
  selector: 'app-rating',
  templateUrl: './rating.page.html',
  styleUrls: ['./rating.page.scss'],
})
export class RatingPage implements OnInit {
  rating = 0;
  comment = '';
  stars = new Array(5);

  orderId!: number;
  rideType = 'Motor'; // default
  paymentMethod = '';
  totalPrice = 0;
  driverPhotoUrl: string = 'assets/Profile.png';
  driverName: string = '';

  constructor(
    private navCtrl: NavController,
    private alertController: AlertController,
    private toastController: ToastController,
    private http: HttpClient,
    private route: ActivatedRoute
  ) { }

  ngOnInit() {
    this.orderId = Number(this.route.snapshot.queryParamMap.get('order_id')) || 0;
    console.log('📦 ORDER ID:', this.orderId);

    if (this.orderId) {
      this.getPaymentDetails();
      this.getDriverProfile();
    } else {
      console.error('❌ Order ID tidak ditemukan di query params.');
    }
  }

  getPaymentDetails() {
    const token = localStorage.getItem('token');
    if (!token) {
      console.error('❌ Token tidak ditemukan.');
      return;
    }

    this.http.get(`${environment.apiUrl}/payment-details/order/${this.orderId}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    }).subscribe({
      next: (res: any) => {
        console.log('✅ DATA DARI BACKEND:', res);
        this.totalPrice = res.total_price ?? 0;
        this.paymentMethod = res.metode ?? '-';
        this.rideType = 'Motor'; // tetap jika tidak ada info ride
      },
      error: (err) => {
        console.error('❌ Gagal ambil info pembayaran:', err);
      }
    });
  }

  setRating(value: number) {
    this.rating = value;
  }

  dismiss() {
    this.navCtrl.back();
  }

  getDriverProfile() {
    const token = localStorage.getItem('token');
    if (!token) {
      console.error('❌ Token tidak ditemukan.');
      return;
    }

    this.http.get(`${environment.apiUrl}/driver-profile/order/${this.orderId}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe({
      next: (res: any) => {
        this.driverName = res.driver_name ?? 'Driver';
        this.driverPhotoUrl = res.driver_photo || 'assets/Profile.png';
      },
      error: (err) => {
        console.error('❌ Gagal ambil profil driver:', err);
      }
    });
  }


  async submitRating() {
    if (this.rating === 0) {
      const alert = await this.alertController.create({
        header: 'Rating Belum Diisi',
        message: 'Silakan beri rating terlebih dahulu.',
        buttons: ['Oke'],
      });
      await alert.present();
      return;
    }

    const payload = {
      order_id: this.orderId,
      rating: this.rating,
      comment: this.comment
    };

    const token = localStorage.getItem('token');
    if (!token) {
      console.error('❌ Token tidak ditemukan saat submit rating.');
      return;
    }

    this.http.post(`${environment.apiUrl}/rate-driver`, payload, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe({
      next: async (res: any) => {
        const toast = await this.toastController.create({
          message: '✅ Rating berhasil dikirim!',
          duration: 2000,
          color: 'success',
        });
        await toast.present();
        this.navCtrl.navigateRoot('/confirm-rating');
      },
      error: async (err) => {
        const toast = await this.toastController.create({
          message: '❌ Gagal mengirim rating. Coba lagi!',
          duration: 2000,
          color: 'danger',
        });
        await toast.present();
      }
    });
  }
}
