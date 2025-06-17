import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { RegisterOptionPageRoutingModule } from './register-option-routing.module';

import { RegisterOptionPage } from './register-option.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RegisterOptionPageRoutingModule
  ],
  declarations: [RegisterOptionPage]
})
export class RegisterOptionPageModule {}
