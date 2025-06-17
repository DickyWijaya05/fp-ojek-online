import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FormRegisterPage } from './form-register.page';

describe('FormRegisterPage', () => {
  let component: FormRegisterPage;
  let fixture: ComponentFixture<FormRegisterPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(FormRegisterPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
