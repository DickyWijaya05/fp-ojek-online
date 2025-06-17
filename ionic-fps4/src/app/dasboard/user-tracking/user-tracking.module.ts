import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';

import { UserTrackingPageRoutingModule } from './user-tracking-routing.module';
import { UserTrackingPage } from './user-tracking.page';

import { CustomTabBarModule } from '../../components/custom-tab-bar/custom-tab-bar.component.module'; // ✅ import modul custom tab

@NgModule({
  declarations: [UserTrackingPage],
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    UserTrackingPageRoutingModule,
    CustomTabBarModule
  ]
})
export class UserTrackingPageModule {}
