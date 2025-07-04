import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment';

@Component({
  standalone: false,
  selector: 'app-accounts',
  templateUrl: './accounts.page.html',
  styleUrls: ['./accounts.page.scss'],
})
export class AccountsPage implements OnInit {
  @ViewChild('fileInput', { static: false }) fileInput!: ElementRef<HTMLInputElement>;
  @ViewChild('qrisInput', { static: false }) qrisInput!: ElementRef<HTMLInputElement>;

  profile: any = {
    name: '',
    email: '',
    phone: '',
    foto_profil: '',
    foto_qris: '',
    gender: '',
    date_of_birth: ''
  };

  isEditing = false;

  constructor(private http: HttpClient, private router: Router) { }

  ngOnInit() {
    this.loadProfile();
  }

  loadProfile() {
    const token = localStorage.getItem('driver_token');
    if (!token) {
      console.error('Token tidak ditemukan.');
      return;
    }

    this.http.get(`${environment.apiUrl}/driver/profile`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe({
      next: (res: any) => {
        console.log('📦 Data profil driver dari backend:', res); // ⬅️ Tambahkan ini
        this.profile = res;
      },
      error: (err) => {
        console.error('❌ Gagal mengambil profil driver:', err);
      }
    });
  }


  saveProfile() {
    const token = localStorage.getItem('driver_token');
    if (!token) return;

    const data = {
      name: this.profile.name,
      phone: this.profile.phone,
      jenis_kelamin: this.profile.gender === 'Male' ? 'L' : 'P',
      tanggal_lahir: this.profile.date_of_birth
    };

    this.http.put(`${environment.apiUrl}/driver/profile`, data, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe(() => {
      this.isEditing = false;
      this.loadProfile();
    });
  }

  uploadPhoto(event: any) {
    const token = localStorage.getItem('driver_token');
    if (!token) return;

    const file = event.target.files[0];
    if (!file) {
      console.error('Tidak ada file foto profil dipilih.');
      return;
    }

    const formData = new FormData();
    formData.append('foto_profil', file);

    this.http.post(`${environment.apiUrl}/driver/profile/upload-photo`, formData, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe((res: any) => {
      this.profile.foto_profil = res.foto_profil;
      this.loadProfile(); // refresh data
    });
  }

  uploadQris(event: any) {
    const token = localStorage.getItem('driver_token');
    if (!token) return;

    const file = event.target.files[0];
    if (!file) {
      console.error('Tidak ada file QRIS dipilih.');
      return;
    }

    const formData = new FormData();
    formData.append('foto_qris', file);

    this.http.post(`${environment.apiUrl}/driver/profile/upload-qris`, formData, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe((res: any) => {
      this.profile.foto_qris = res.foto_qris;
      this.loadProfile(); // refresh data
    });
  }

  triggerFileInput() {
    this.fileInput.nativeElement.click();
  }

  triggerQrisInput() {
    this.qrisInput.nativeElement.click();
  }

 logout() {
  localStorage.clear(); // 🔥 Bersihkan SEMUA data di localStorage
  sessionStorage.clear();
  this.router.navigate(['/login']); // 🔁 Redirect ke halaman login
}
}
