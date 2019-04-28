import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { switchMap } from 'rxjs/operators';
import { FormBuilder, Validators } from '@angular/forms';

import { GameService } from '../game.service';
import { AlertService } from '../../alert/alert.service';
import { Game } from '../../model/game';

@Component({
    selector: 'app-game-edit',
    templateUrl: './game-edit.component.html'
})
export class GameEditComponent implements OnInit {
    
    game: Game;

    gameForm = this.fb.group({
        id: [''],
        date: ['', Validators.required],
        location: ['', Validators.required],
        game_type: ['', Validators.required],
        host_team: ['', Validators.required],
        guest_team: ['', Validators.required],
        host_score: ['', [Validators.required, Validators.pattern("^[0-9]*$")]],
        guest_score: ['', [Validators.required, Validators.pattern("^[0-9]*$")]]
    });

    constructor(
        private router: Router,
        private route: ActivatedRoute,
        private gameService: GameService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }

    ngOnInit(): void {
        this.route.params.forEach((params: Params) => {
            if (params['id'] !== undefined) {
                const id = +params['id'];
                this.gameService.getGame(id).subscribe(game => {
                    this.game = game;
                    this.gameForm.setValue(this.game);
                });
            } else {
                this.alertService.error('error');
            }
        });
    }

    updateGame() {
        return this.gameService.updateGame(this.gameForm.value).subscribe(
            success => {
                this.alertService.success(success, true);
                this.router.navigate(['/games/list']);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

}
