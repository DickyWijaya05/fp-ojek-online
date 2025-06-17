import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ConfirmRatingPageRoutingModule } from './confirm-rating-routing.module';

import { ConfirmRatingPage } from './confirm-rating.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ConfirmRatingPageRoutingModule
  ],
  declarations: [ConfirmRatingPage]
})
export class ConfirmRatingPageModule {}
