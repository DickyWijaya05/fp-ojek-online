import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ConfirmRatingPage } from './confirm-rating.page';

const routes: Routes = [
  {
    path: '',
    component: ConfirmRatingPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ConfirmRatingPageRoutingModule {}
