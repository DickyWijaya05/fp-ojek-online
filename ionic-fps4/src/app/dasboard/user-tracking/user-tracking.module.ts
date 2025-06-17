import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { UserTrackingPageRoutingModule } from './user-tracking-routing.module';

import { UserTrackingPage } from './user-tracking.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    UserTrackingPageRoutingModule
  ],
  declarations: [UserTrackingPage]
})
export class UserTrackingPageModule {}
