import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { Subscription } from 'rxjs';
import { ToastController } from '@ionic/angular';
import { environment } from 'src/environments/environment';

@Component({
  standalone: false,
  selector: 'app-homes',
  templateUrl: './homes.page.html',
  styleUrls: ['./homes.page.scss'],
})
export class HomesPage implements OnInit, OnDestroy {
  user: any = null;
  isOnline: boolean = false;
  driverStatus: string = 'offline';
  orderPollingSub: Subscription | undefined;

  constructor(
    private router: Router,
    private http: HttpClient,
    private toastCtrl: ToastController
  ) { }

  ngOnInit() {
    const token = localStorage.getItem('driver_token');
    if (token) {
      this.http.get(`${environment.apiUrl}/driver/profile`, {
        headers: { Authorization: `Bearer ${token}` }
      }).subscribe({
        next: (res: any) => {
          this.user = res;
          localStorage.setItem('driver_profile', JSON.stringify(res));
          console.log('✅ Profil driver:', res);
        },
        error: (err) => {
          console.error('❌ Gagal ambil profil driver:', err);
          const fallback = localStorage.getItem('driver_profile');
          if (fallback) this.user = JSON.parse(fallback);
        }
      });
    } else {
      console.warn('⚠️ Token driver tidak ditemukan!');
    }
  }

  ngOnDestroy() {
    // this.stopPollingOrders();
  }

  async goToLokasi() {
    if (!this.isOnline) {
      const toast = await this.toastCtrl.create({
        message: '⚠️ Kamu harus aktifkan status online dulu untuk mencari pelanggan!',
        duration: 2500,
        color: 'warning',
        position: 'top'
      });
      await toast.present();
      return;
    }

    // Kalau sudah online, lanjut navigasi
    this.router.navigate(['/driver-tracking']);
  }

  toggleStatus(event: any) {
    const checked = event.detail.checked;
    this.isOnline = checked;
    this.driverStatus = checked ? 'available' : 'offline';

    const token = localStorage.getItem('driver_token');
    if (!token) {
      console.warn('🚫 Token tidak ditemukan!');
      return;
    }

    const body = {
      driver_id: this.user?.id,
      status: this.driverStatus
    };

    this.http.post(`${environment.apiUrl}/driver-status`, body, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe({
      next: () => {
        console.log('✅ Status berhasil diperbarui ke:', this.driverStatus);
      },
      error: (err) => {
        console.error('❌ Gagal update status:', err);
      }
    });
  }
}
