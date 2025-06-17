import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { UserTrackingPage } from './user-tracking.page';

const routes: Routes = [
  {
    path: '',
    component: UserTrackingPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class UserTrackingPageRoutingModule {}
