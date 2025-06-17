import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ActivitysPageRoutingModule } from './activitys-routing.module';

import { ActivitysPage } from './activitys.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ActivitysPageRoutingModule
  ],
  declarations: [ActivitysPage]
})
export class ActivitysPageModule {}
