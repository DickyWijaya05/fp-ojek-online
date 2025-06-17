import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { RouterModule } from '@angular/router';

import { CustomTabBarComponent } from './custom-tab-bar.component';

@NgModule({
  declarations: [CustomTabBarComponent],
  imports: [
    CommonModule,
    IonicModule,
    RouterModule
  ],
  exports: [CustomTabBarComponent]
})
export class CustomTabBarModule {}
