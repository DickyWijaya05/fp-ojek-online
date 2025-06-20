import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { HttpClient } from '@angular/common/http';

@Component({
  standalone: false,
  selector: 'app-homes',
  templateUrl: './homes.page.html',
  styleUrls: ['./homes.page.scss'],
})
export class HomesPage implements OnInit {
  user: any = null;
  isOnline: boolean = false;
  driverStatus: string = 'offline';

  constructor(
    private router: Router,
    private authService: AuthService,
    private http: HttpClient
  ) {}

  ngOnInit() {
    this.authService.user.subscribe(userData => {
      if (userData) {
        this.user = userData;
      } else {
        const localUser = localStorage.getItem('user');
        this.user = localUser ? JSON.parse(localUser) : null;
      }

      console.log('✅ Driver data:', this.user);

      // (Opsional) ambil status awal dari backend
      // this.loadDriverStatus();
    });
  }

  goToLokasi() {
    this.router.navigate(['/driver-tracking']);
  }

  toggleStatus() {
    this.driverStatus = this.isOnline ? 'available' : 'offline';

    const body = {
      driver_id: this.user?.id,
      status: this.driverStatus
    };

    // Ganti URL dengan endpoint Laravel milikmu
    this.http.post('https://your-api-url.com/api/driver-status', body).subscribe({
      next: () => console.log('✅ Status berhasil diperbarui:', this.driverStatus),
      error: (err) => console.error('❌ Gagal update status:', err)
    });
  }

  // (Opsional) Ambil status dari database saat pertama kali masuk
  // loadDriverStatus() {
  //   this.http.get(`https://your-api-url.com/api/driver-status/${this.user?.id}`)
  //     .subscribe((res: any) => {
  //       this.driverStatus = res.status;
  //       this.isOnline = this.driverStatus === 'available';
  //     });
  // }
}
