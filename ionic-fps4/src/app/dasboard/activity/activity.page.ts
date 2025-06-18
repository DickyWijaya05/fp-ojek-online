import { Component, OnInit } from '@angular/core';

@Component({ 
  standalone: false,
  selector: 'app-activity',
  templateUrl: './activity.page.html',
  styleUrls: ['./activity.page.scss']
})
export class ActivityPage implements OnInit {

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

  ngOnInit() { }
}