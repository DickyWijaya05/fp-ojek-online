import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DasboardsPage } from './dasboards.page';

const routes: Routes = [
  {
    path: '',
    component: DasboardsPage,
    children: [
      {
        path: 'homes',
        loadChildren: () =>
          import('./homes/homes.module').then(m => m.HomesPageModule)
      },
      {
        path: 'activitys',
        loadChildren: () =>
          import('./activitys/activitys.module').then(m => m.ActivitysPageModule)
      },
      {
        path: 'accounts',
        loadChildren: () =>
          import('./accounts/accounts.module').then(m => m.AccountsPageModule)
      },
      {
        path: 'chats',
        loadChildren: () =>
          import('./chats/chats.module').then(m => m.ChatsPageModule)
      },
      {
        path: '',
        redirectTo: '/dasboards/homes',
        pathMatch: 'full'
      }
    ]
  }
];
 
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class DasboardsPageRoutingModule {}
