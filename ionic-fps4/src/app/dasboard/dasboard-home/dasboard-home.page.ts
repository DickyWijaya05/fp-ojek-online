import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment'; // ✅ Import environment

@Component({
  standalone: false,
  selector: 'app-dasboard-home',
  templateUrl: './dasboard-home.page.html',
  styleUrls: ['./dasboard-home.page.scss'],
})
export class DasboardHomePage implements OnInit {
  user: any = null;

  constructor(private router: Router, private http: HttpClient) {}

  ngOnInit() {
    this.loadUserFromApi();
  }

  loadUserFromApi() {
    const token = localStorage.getItem('token');
    if (!token) return;

    this.http.get(`${environment.apiUrl}/profile`, {
      headers: { Authorization: `Bearer ${token}` }
    }).subscribe({
      next: (res: any) => {
        this.user = res;
        localStorage.setItem('user', JSON.stringify(res));
      },
      error: (err) => {
        console.error('Gagal ambil profil:', err);
      }
    });
  }

  goToLokasi() {
    this.router.navigate(['/user-tracking']);
  }
}
