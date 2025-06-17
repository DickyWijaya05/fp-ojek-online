import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DocumentDriverPage } from './document-driver.page';

describe('DocumentDriverPage', () => {
  let component: DocumentDriverPage;
  let fixture: ComponentFixture<DocumentDriverPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentDriverPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
