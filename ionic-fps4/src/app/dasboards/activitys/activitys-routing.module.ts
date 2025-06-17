import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ActivitysPage } from './activitys.page';

const routes: Routes = [
  {
    path: '',
    component: ActivitysPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ActivitysPageRoutingModule {}
