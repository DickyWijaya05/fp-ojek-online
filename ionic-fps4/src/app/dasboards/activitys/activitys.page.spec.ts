import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ActivitysPage } from './activitys.page';

describe('ActivitysPage', () => {
  let component: ActivitysPage;
  let fixture: ComponentFixture<ActivitysPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ActivitysPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
