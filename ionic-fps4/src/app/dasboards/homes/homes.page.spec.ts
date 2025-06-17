import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HomesPage } from './homes.page';

describe('HomesPage', () => {
  let component: HomesPage;
  let fixture: ComponentFixture<HomesPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(HomesPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
