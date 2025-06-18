import { Component } from '@angular/core';

@Component({
  standalone: false,
  selector: 'app-custom-tab-bar',
  templateUrl: './custom-tab-bar.component.html',
  styleUrls: ['./custom-tab-bar.component.scss']
})
export class CustomTabBarComponent {
  selectedTab: string = 'home';

  constructor() { }

  setSelectedTab(tab: string) {
    this.selectedTab = tab;
  }
}