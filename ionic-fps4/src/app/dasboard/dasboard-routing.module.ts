import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DasboardPage } from './dasboard.page';
const routes: Routes = [
  {
    path: '',
    component: DasboardPage, 
    children: [
      {
        path: 'home',
        loadChildren: () =>
          import('./dasboard-home/dasboard-home.module').then(m => m.DasboardHomePageModule)
      },
      {
        path: 'activity',
        loadChildren: () =>
          import('./activity/activity.module').then(m => m.ActivityPageModule)
      },
      {
        path: 'account',
        loadChildren: () =>
          import('./account/account.module').then(m => m.AccountPageModule)
      },
      {
        path: 'chat',
        loadChildren: () =>
          import('./chat/chat.module').then(m => m.ChatPageModule)
      },
      {
        path: '',
        redirectTo: '/dasboard/home',
        pathMatch: 'full'
      }
    ]
  }
];
 
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class DasboardPageRoutingModule {}