import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { switchMap } from 'rxjs/operators';
import { FormBuilder, Validators } from '@angular/forms';

import { PostService } from '../post.service';
import { AlertService } from '../../alert/alert.service';
import { Post } from '../../model/post';

@Component({
    selector: 'app-post-edit',
    templateUrl: './post-edit.component.html'
})
export class PostEditComponent implements OnInit {
    
    post: Post;

    postForm = this.fb.group({
        id: [''],
        title: ['', Validators.required],
        slug: ['', Validators.required],
        body: ['', Validators.required],
        categories: ['', Validators.required]
    });

    constructor(
        private router: Router,
        private route: ActivatedRoute,
        private postService: PostService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }

    ngOnInit(): void {
        this.route.params.forEach((params: Params) => {
            if (params['id'] !== undefined) {
                const id = +params['id'];
                this.postService.getPost(id).subscribe(post => {
                    this.post = post;
                    this.postForm.setValue(this.post);
                });
            } else {
                this.alertService.error('error');
            }
        });
    }

    updatePost() {
        return this.postService.updatePost(this.postForm.value).subscribe(
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
