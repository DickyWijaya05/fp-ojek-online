import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CancelRidePage } from './cancel-ride.page';

const routes: Routes = [
  {
    path: '',
    component: CancelRidePage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CancelRidePageRoutingModule {}
