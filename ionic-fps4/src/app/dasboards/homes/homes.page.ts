import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
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
    private authService: AuthService,
    private http: HttpClient,
    private toastCtrl: ToastController
  ) { }

  ngOnInit() {
    this.authService.user.subscribe(userData => {
      if (userData) {
        this.user = userData;
      } else {
        const localUser = localStorage.getItem('user');
        this.user = localUser ? JSON.parse(localUser) : null;
      }

      console.log('✅ Driver data:', this.user);
    });

  //   // Mulai polling setiap 5 detik
  //   this.startPollingOrders();
   }

   ngOnDestroy() {
  //   this.stopPollingOrders();
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


  // startPollingOrders() {
  //   const token = localStorage.getItem('driver_token');
  //   if (!token) return;

  //   this.orderPollingSub = interval(5000).subscribe(() => {
  //     this.http.get('http://localhost:8000/api/driver/incoming-orders', {
  //       headers: { Authorization: `Bearer ${token}` }
  //     }).subscribe({
  //       next: async (res: any) => {
  //         if (res.data && res.data.length > 0) {
  //           const order = res.data[0];
  //           console.log('📦 Order masuk:', order);
  //           // this.showOrderToast(order);
  //         }
  //       },
  //       error: err => {
  //         console.error('❌ Gagal polling order:', err);
  //       }
  //     });
  //   });
  // }

  // stopPollingOrders() {
  //   if (this.orderPollingSub) {
  //     this.orderPollingSub.unsubscribe();
  //   }
  // }

  // async showOrderToast(order: any) {
  //   const toast = await this.toastCtrl.create({
  //     header: '🚕 Order Masuk!',
  //     message: `Dari: ${order.start_address} ke ${order.dest_address}`,
  //     position: 'top',
  //     color: 'primary',
  //     duration: 0,
  //     buttons: [
  //       {
  //         text: '❌ Tolak',
  //         role: 'cancel',
  //         handler: () => {
  //           this.rejectOrder(order.id);
  //         }
  //       },
  //       {
  //         text: '✅ Terima',
  //         handler: () => {
  //           this.acceptOrder(order.id);
  //         }
  //       }
  //     ]
  //   });
  //   await toast.present();
  // }

  // acceptOrder(orderId: number) {
  //   const token = localStorage.getItem('driver_token');
  //   this.http.post(`http://localhost:8000/api/driver/accept-order`, {
  //     order_id: orderId
  //   }, {
  //     headers: { Authorization: `Bearer ${token}` }
  //   }).subscribe({
  //     next: () => {
  //       console.log('✅ Order diterima');
  //       this.router.navigate(['/driver-tracking'], {
  //         queryParams: { orderId }
  //       });
  //     },
  //     error: err => {
  //       console.error('❌ Gagal menerima order:', err);
  //     }
  //   });
  // }

//   rejectOrder(orderId: number) {
//     const token = localStorage.getItem('driver_token');
//     this.http.post(`http://localhost:8000/api/driver/reject-order`, {
//       order_id: orderId
//     }, {
//       headers: { Authorization: `Bearer ${token}` }
//     }).subscribe({
//       next: () => {
//         console.log('⛔ Order ditolak');
//       },
//       error: err => {
//         console.error('❌ Gagal menolak order:', err);
//       }
//     });
//   }

 }
