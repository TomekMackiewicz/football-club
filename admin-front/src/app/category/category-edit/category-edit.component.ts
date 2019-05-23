import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { switchMap } from 'rxjs/operators';
import { FormBuilder, Validators } from '@angular/forms';

import { CategoryService } from '../category.service';
import { AlertService } from '../../alert/alert.service';
import { Category } from '../../model/category';

@Component({
    selector: 'app-category-edit',
    templateUrl: './category-edit.component.html'
})
export class CategoryEditComponent implements OnInit {
    
    category: Category;

    categoryForm = this.fb.group({
        id: [''],
        name: ['', Validators.required],
        posts: ['']
    });

    constructor(
        private router: Router,
        private route: ActivatedRoute,
        private categoryService: CategoryService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }

    ngOnInit(): void {
        this.route.params.forEach((params: Params) => {
            if (params['id'] !== undefined) {
                const id = +params['id'];
                this.categoryService.getCategory(id).subscribe(category => {
                    this.category = category;
                    this.categoryForm.setValue(this.category);
                });
            } else {
                this.alertService.error('error');
            }
        });
    }

    updateCategory() {
        return this.categoryService.updateCategory(this.categoryForm.value).subscribe(
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
