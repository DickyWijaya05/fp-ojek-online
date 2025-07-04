import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { format } from 'date-fns';import { environment } from 'src/environments/environment';

@Component({
  standalone: false,
  selector: 'app-activitys',
  templateUrl: './activitys.page.html',
  styleUrls: ['./activitys.page.scss'],
})
export class ActivitysPage implements OnInit {

  selectedTab = 'riwayat';
  riwayatList: any[] = [];
  prosesList: any[] = [];
  isLoading: boolean = true;
  isEmpty: boolean = false;

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.fetchRiwayat();
  }

  fetchRiwayat() {
    const token = localStorage.getItem('driver_token');

    this.http.get<any>(`${environment.apiUrl}/driver/transactions`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).subscribe({
      next: (res) => {
        this.riwayatList = res.data;
        this.isEmpty = res.data.length === 0;
        this.isLoading = false;

        this.prosesList = res.data.filter((item: any) => {
          const now = new Date();
          const waktu = new Date(item.created_at);
          const selisihMenit = (now.getTime() - waktu.getTime()) / 60000;
          return selisihMenit < 15;
        });
      },
      error: (err) => {
        console.error('Gagal ambil data transaksi driver:', err);
        this.isLoading = false;
        this.isEmpty = true;
      }
    });
  }

  formatTanggal(dateStr: string): string {
    return format(new Date(dateStr), 'dd MMM yyyy, HH:mm');
  }
}
