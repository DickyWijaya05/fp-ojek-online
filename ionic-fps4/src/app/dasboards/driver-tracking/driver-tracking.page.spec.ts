import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DriverTrackingPage } from './driver-tracking.page';

describe('DriverTrackingPage', () => {
  let component: DriverTrackingPage;
  let fixture: ComponentFixture<DriverTrackingPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DriverTrackingPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
