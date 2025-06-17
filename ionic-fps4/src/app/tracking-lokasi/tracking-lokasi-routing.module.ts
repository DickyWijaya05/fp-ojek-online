import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { TrackingLokasiPage } from './tracking-lokasi.page';

const routes: Routes = [
  {
    path: '',
    component: TrackingLokasiPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TrackingLokasiPageRoutingModule {}
