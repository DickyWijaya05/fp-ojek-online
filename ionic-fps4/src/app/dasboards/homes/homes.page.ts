import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { interval, Subscription } from 'rxjs';
import { ToastController } from '@ionic/angular';

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
  ) {}

  ngOnInit() {
    const token = localStorage.getItem('driver_token');
    if (token) {
      this.http.get('http://localhost:8000/api/driver/profile', {
        headers: { Authorization: `Bearer ${token}` }
      }).subscribe({
        next: (res: any) => {
          this.user = res;
          localStorage.setItem('driver_profile', JSON.stringify(res)); // Optional caching
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

  goToLokasi() {
    this.router.navigate(['/driver-tracking']);
  }

  toggleStatus() {
    this.driverStatus = this.isOnline ? 'available' : 'offline';

    const token = localStorage.getItem('driver_token');
    if (!token) {
      console.warn('🚫 Token tidak ditemukan!');
      return;
    }

    const body = {
      driver_id: this.user?.id,
      status: this.driverStatus
    };

    this.http.post('http://localhost:8000/api/driver-status', body, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe({
      next: () => console.log('✅ Status berhasil diperbarui:', this.driverStatus),
      error: (err) => console.error('❌ Gagal update status:', err)
    });
  }

  // --- Polling dan order toast code bisa diaktifkan lagi jika diperlukan ---

}
