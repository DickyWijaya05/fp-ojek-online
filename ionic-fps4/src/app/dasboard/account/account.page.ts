import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment';

@Component({
  standalone:false,
  selector: 'app-account',
  templateUrl: './account.page.html',
  styleUrls: ['./account.page.scss'],
})
export class AccountPage implements OnInit {
  @ViewChild('fileInput', { static: false }) fileInput!: ElementRef<HTMLInputElement>;

  profile: any = null;
  isEditing: boolean = false;

  constructor(private http: HttpClient, private router: Router) {}

  ngOnInit() {
    console.log('AccountPage Loaded');
    this.loadProfile();
  }

  loadProfile() {
    const token = localStorage.getItem('token');
    this.http.get('http://localhost:8000/api/profile', {
      headers: { Authorization: `Bearer ${token}` }
    }).subscribe({
      next: (res: any) => {
        console.log('Respon dari /api/profile:', res);
        this.profile = res;
      },
      error: (err) => {
        console.error('Gagal ambil profil:', err);
      }
    });
  }

  saveProfile() {
    const token = localStorage.getItem('token');
    const data = {
      name: this.profile.name,
      phone: this.profile.phone,
      alamat: this.profile.address,
      jenis_kelamin: this.profile.gender === 'Male' ? 'L' : 'P',
    };

    this.http.put(`${environment.apiUrl}/profile`, data, {
      headers: { Authorization: `Bearer ${token}` }
    }).subscribe(() => {
      this.isEditing = false;
      this.loadProfile();
    });
  }

  uploadPhoto(event: any) {
    const token = localStorage.getItem('token');
    const file = event.target.files[0];
    const formData = new FormData();
    formData.append('foto_profil', file);

    this.http.post(`${environment.apiUrl}/profile/upload-photo`, formData, {
      headers: { Authorization: `Bearer ${token}` }
    }).subscribe((res: any) => {
      this.profile.foto_profil = res.foto_profil;
    });
  }

  triggerFileInput() {
    this.fileInput.nativeElement.click();
  }

logout() {
  localStorage.clear();
  sessionStorage.clear(); // Hapus token customer
  window.location.href = '/login-costumer'; // Atau pakai router.navigate(['/login']) kalau routing Angular
}

}
