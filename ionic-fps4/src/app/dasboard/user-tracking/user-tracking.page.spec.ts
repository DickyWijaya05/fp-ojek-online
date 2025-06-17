import { ComponentFixture, TestBed } from '@angular/core/testing';
import { UserTrackingPage } from './user-tracking.page';

describe('UserTrackingPage', () => {
  let component: UserTrackingPage;
  let fixture: ComponentFixture<UserTrackingPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(UserTrackingPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
