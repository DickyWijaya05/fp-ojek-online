import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CancelRidePage } from './cancel-ride.page';

describe('CancelRidePage', () => {
  let component: CancelRidePage;
  let fixture: ComponentFixture<CancelRidePage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(CancelRidePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
