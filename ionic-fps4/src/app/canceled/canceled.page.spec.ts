import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CanceledPage } from './canceled.page';

describe('CanceledPage', () => {
  let component: CanceledPage;
  let fixture: ComponentFixture<CanceledPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(CanceledPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
