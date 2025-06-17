import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { DasboardsPageRoutingModule } from './dasboards-routing.module';

import { DasboardsPage } from './dasboards.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    DasboardsPageRoutingModule
  ],
  declarations: [DasboardsPage]
})
export class DasboardsPageModule {}
