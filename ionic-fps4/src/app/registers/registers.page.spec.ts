import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RegistersPage } from './registers.page';

describe('RegistersPage', () => {
  let component: RegistersPage;
  let fixture: ComponentFixture<RegistersPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(RegistersPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
