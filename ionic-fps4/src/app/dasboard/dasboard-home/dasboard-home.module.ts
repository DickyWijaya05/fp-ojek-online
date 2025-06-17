import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { DasboardHomePageRoutingModule } from './dasboard-home-routing.module';

import { DasboardHomePage } from './dasboard-home.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    DasboardHomePageRoutingModule
  ],
  declarations: [DasboardHomePage]
})
export class DasboardHomePageModule {}
