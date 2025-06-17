import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { FormRegisterPageRoutingModule } from './form-register-routing.module';

import { FormRegisterPage } from './form-register.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    FormRegisterPageRoutingModule
  ],
  declarations: [FormRegisterPage]
})
export class FormRegisterPageModule {}
