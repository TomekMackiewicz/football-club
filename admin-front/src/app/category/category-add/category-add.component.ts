import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, Validators } from '@angular/forms';

import { CategoryService } from '../category.service';
import { AlertService } from '../../alert/alert.service';

@Component({
    selector: 'app-category-add',
    templateUrl: './category-add.component.html'
})
export class CategoryAddComponent {

    categoryForm = this.fb.group({
        name: ['', Validators.required]
    });

    constructor(
        private router: Router,
        private categoryService: CategoryService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }

    addCategory() {
        return this.categoryService.addCategory(this.categoryForm.value).subscribe(
            success => {
                this.alertService.success(success, true);
                this.router.navigate(['/categories/list']);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

}
