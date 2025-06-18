import { Component, OnInit } from '@angular/core';

@Component({
  standalone: false,
  selector: 'app-activitys',
  templateUrl: './activitys.page.html',
  styleUrls: ['./activitys.page.scss'],
})
export class ActivitysPage implements OnInit {

  selectedTab = 'riwayat';

  riwayatList = [
    { universitas: 'Universitas UBP', keterangan: 'Perjalanan Selesai', harga: 5000 },
    { universitas: 'Universitas UBP', keterangan: 'Perjalanan Selesai', harga: 5000 },
  ];

  prosesList = [
    { universitas: 'Universitas UBP', keterangan: 'Perjalanan Dalam Proses!', harga: 5000 },
    { universitas: 'Universitas UBP', keterangan: 'Perjalanan Dalam Proses!', harga: 5000 },
  ];

  constructor() { }

  ngOnInit() {
  }

}