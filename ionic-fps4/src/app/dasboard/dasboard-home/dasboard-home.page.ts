import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  standalone: false,
  selector: 'app-dasboard-home',
  templateUrl: './dasboard-home.page.html',
  styleUrls: ['./dasboard-home.page.scss'],
})
export class DasboardHomePage implements OnInit {
  user: any = null;

  constructor(private router: Router, private authService: AuthService) {}

  ngOnInit() {
  this.authService.user.subscribe((firebaseUser: any) => {
    if (firebaseUser?.email) {
      // Ambil data lengkap dari Laravel
      this.authService.getUserFromLaravel(firebaseUser.email).subscribe(
        (res) => {
          this.user = res.user;
          localStorage.setItem('user', JSON.stringify(res.user)); // simpan untuk reload
        },
        (err) => {
          console.error('Gagal ambil user dari Laravel:', err);
        }
      );
    } else {
      // fallback dari localStorage
      const localUser = localStorage.getItem('user');
      this.user = localUser ? JSON.parse(localUser) : null;
    }
  });
}

  goToLokasi() {
    this.router.navigate(['/user-tracking']);
  }
}
