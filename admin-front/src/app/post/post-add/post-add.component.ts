import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, Validators } from '@angular/forms';
//import * as moment from 'moment';
import { PostService } from '../post.service';
import { CategoryService } from '../../category/category.service';
import { AlertService } from '../../alert/alert.service';
import { Category } from '../../model/category';

@Component({
    selector: 'app-post-add',
    templateUrl: './post-add.component.html'
})
export class PostAddComponent implements OnInit {
    today = new Date();
    categories: Category[] = [];
    
    postForm = this.fb.group({
        title: ['', Validators.required],
        slug: ['', Validators.required],
        body: ['', Validators.required],
        categories: [this.categories, Validators.required],
        //publishDate: [moment(new Date()).format('YYYY-MM-DD HH:mm:ss')]
    });

    constructor(
        private router: Router,
        private postService: PostService,
        private categoryService: CategoryService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }
    
    ngOnInit() {
        this.getCategories();
    }
    
    getCategories() {
        this.categoryService.getCategories().subscribe(
            resp => {
                this.categories = resp.categories;
                //console.log(resp);
            },
            error => {
                console.log(error);
            }
        );        
    }

    addPost() {
        return this.postService.addPost(this.postForm.value).subscribe(
            success => {
                this.alertService.success(success, true);
                this.router.navigate(['/posts/list']);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

}
