import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { DriverTrackingPage } from './driver-tracking.page';

const routes: Routes = [
  {
    path: '',
    component: DriverTrackingPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class DriverTrackingPageRoutingModule {}
