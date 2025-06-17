import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DasboardPage } from './dasboard.page';

describe('DasboardPage', () => {
  let component: DasboardPage;
  let fixture: ComponentFixture<DasboardPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DasboardPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
