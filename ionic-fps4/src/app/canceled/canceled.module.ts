import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CanceledPageRoutingModule } from './canceled-routing.module';

import { CanceledPage } from './canceled.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    CanceledPageRoutingModule
  ],
  declarations: [CanceledPage]
})
export class CanceledPageModule {}
