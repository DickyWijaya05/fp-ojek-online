import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CancelRidePageRoutingModule } from './cancel-ride-routing.module';

import { CancelRidePage } from './cancel-ride.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    CancelRidePageRoutingModule
  ],
  declarations: [CancelRidePage]
})
export class CancelRidePageModule {}
