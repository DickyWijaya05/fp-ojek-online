import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';

import { TrackingLokasiPageRoutingModule } from './tracking-lokasi-routing.module';
import { TrackingLokasiPage } from './tracking-lokasi.page';

import { CustomTabBarModule } from '../components/custom-tab-bar/custom-tab-bar.component.module'; // ✅ import modul custom tab

@NgModule({
  declarations: [TrackingLokasiPage],
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    TrackingLokasiPageRoutingModule,
    CustomTabBarModule // ✅ pastikan ini ditambahkan
  ]
})
export class TrackingLokasiPageModule {}