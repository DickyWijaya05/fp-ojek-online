import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { RegistersPageRoutingModule } from './registers-routing.module';

import { RegistersPage } from './registers.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RegistersPageRoutingModule
  ],
  declarations: [RegistersPage]
})
export class RegistersPageModule {}
