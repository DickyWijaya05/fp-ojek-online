import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { format } from 'date-fns';
import { environment } from 'src/environments/environment';

@Component({
  standalone: false,
  selector: 'app-activity',
  templateUrl: './activity.page.html',
  styleUrls: ['./activity.page.scss']
})
export class ActivityPage implements OnInit {

  selectedTab = 'riwayat';
  riwayatList: any[] = [];
  prosesList: any[] = [];

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.fetchRiwayat();
  }

  fetchRiwayat() {
    const token = localStorage.getItem('token'); // Ambil token dari localStorage

this.http.get<any>(`${environment.apiUrl}/customer/transactions`, {
  headers: {
    Authorization: `Bearer ${token}`
  }
})
.subscribe({
      next: (res) => {
        this.riwayatList = res.data;

        // Simulasi dalam proses: jika waktu transaksi kurang dari 15 menit
        this.prosesList = res.data.filter((item: any) => {
          const now = new Date();
          const waktu = new Date(item.created_at);
          const selisihMenit = (now.getTime() - waktu.getTime()) / 60000;
          return selisihMenit < 15;
        });
      },
      error: (err) => {
        console.error('Gagal ambil data transaksi:', err);
      }
    });
  }

  formatTanggal(dateStr: string): string {
    return format(new Date(dateStr), 'dd MMM yyyy, HH:mm');
  }
}
