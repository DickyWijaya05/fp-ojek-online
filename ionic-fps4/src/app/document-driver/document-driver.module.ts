import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { DocumentDriverPageRoutingModule } from './document-driver-routing.module';

import { DocumentDriverPage } from './document-driver.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    DocumentDriverPageRoutingModule
  ],
  declarations: [DocumentDriverPage]
})
export class DocumentDriverPageModule {}
