export interface Game {
    id: number;
    date: string;
    location: string;
    game_type: number;
    host_team: number;
    guest_team: number;
    host_score: number;
    guest_score: number;
}
