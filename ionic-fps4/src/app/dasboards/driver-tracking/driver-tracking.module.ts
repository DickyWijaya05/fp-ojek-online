import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { DriverTrackingPageRoutingModule } from './driver-tracking-routing.module';

import { DriverTrackingPage } from './driver-tracking.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    DriverTrackingPageRoutingModule
  ],
  declarations: [DriverTrackingPage]
})
export class DriverTrackingPageModule {}
