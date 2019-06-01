<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 1000; $i++) {            
            $min=1;
            $max=9;
            $startDate = new \DateTime('2019-01-01');
            $endDate = new \DateTime('2019-12-30');            
            $game = new Game();
            $game->setDate($this->randomDateInRange($startDate, $endDate));
            $game->setLocation($this->randomLocation());
            $game->setGameType($this->randomGameType());
            $game->setHostTeam(rand($min,$max));
            $game->setGuestTeam(rand($min,$max));
            $game->setHostScore(rand($min,$max));
            $game->setGuestScore(rand($min,$max));
            
            $manager->persist($game);
        }

        $manager->flush();
    }
    
    private function randomDateInRange(\DateTime $start, \DateTime $end) 
    {
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new \DateTime();
        $randomDate->setTimestamp($randomTimestamp);
        
        return $randomDate;
    }
    
    private function randomLocation()
    {
        $locations = ["Location1", "Location2", "Location3", "Location4", "Location5", "Location6", "Location7"];        
        return (string) array_rand($locations, 1);
    }
    
    private function randomGameType()
    {
        $gameTypes = ["league", "sparring", "tournament"];        
        return array_rand($gameTypes, 1);
    }
}
