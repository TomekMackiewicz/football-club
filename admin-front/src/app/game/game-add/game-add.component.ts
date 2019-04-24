import { Component, AfterViewInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { MessageService } from 'primeng/api';

import { GameService } from '../game.service';
import { Game } from '../../model/game';

@Component({
    selector: 'app-game-add',
    templateUrl: './game-add.component.html'
})
export class GameAddComponent implements AfterViewInit {

    gameForm = this.fb.group({
        date: ['', Validators.required],
        location: ['', Validators.required],
        gameType: ['', Validators.required],
        hostTeam: ['', Validators.required],
        guestTeam: ['', Validators.required],
        hostScore: ['', [Validators.required, Validators.pattern("^[0-9]*$")]],
        guestScore: ['', [Validators.required, Validators.pattern("^[0-9]*$")]]
    });

    constructor(
        private gameService: GameService,
        private fb: FormBuilder,
        private messageService: MessageService
    ) { }

    ngAfterViewInit() {
        this.messageService.add({severity:'success', summary:'Game created', detail:'Via MessageService'});
    }

    addGame() {
        return this.gameService.addGame(this.gameForm.value)
        .subscribe(success => {
            //this.messageService.add({severity:'success', summary:'Service Message', detail:'Via MessageService'});
        });
    }

}
