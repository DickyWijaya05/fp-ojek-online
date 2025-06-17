import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LoginOptionPageRoutingModule } from './login-option-routing.module';

import { LoginOptionPage } from './login-option.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LoginOptionPageRoutingModule
  ],
  declarations: [LoginOptionPage]
})
export class LoginOptionPageModule {}
