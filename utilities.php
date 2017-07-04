<?php
function getResponseResult($params){

    $resultarray = array(
        array("Name"=>"Barbarian","Level"=>4,"Description"=>"The Barbarian is a kilt-clad Scottish warrior with an angry, battle-ready expression, hungry for destruction. He has Killer yellow horseshoe mustache.","Training"=>20,"Speed"=>16,"Cost"=>150,"Image"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/barbarian.png","BG"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/barbarian-bg.jpg","Theme"=>"#EC9B3B"),
        array("Name"=>"The Archer","Level"=>5,"Description"=>"The Archer is a female warrior with sharp eyes. She wears a short, light green dress, a hooded cape, a leather belt and an attached small pouch.","Training"=>25,"Speed"=>24,"Cost"=>300,"Image"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/archer.png","BG"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/archer-bg.jpg","Theme"=>"#EE5487"),
        array("Name"=>"The Giant","Level"=>5,"Description"=>"Slow, steady and powerful, Giants are massive warriors that soak up huge amounts of damage. Show them a turret or cannon and you'll see their fury unleashed!","Training"=>120,"Speed"=>12,"Cost"=>2250,"Image"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/giant.png","BG"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/giant-bg.jpg","Theme"=>"#F6901A"),
        array("Name"=>"The Goblin","Level"=>5,"Description"=>"These pesky little creatures only have eyes for one thing: LOOT! They are faster than a Spring Trap, and their hunger for resources is limitless.","Training"=>30,"Speed"=>32,"Cost"=>100,"Image"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/goblin.png","BG"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/giant-bg.jpg","Theme"=>"#82BB30"),
        array("Name"=>"The Wizard","Level"=>6,"Description"=>"The Wizard is a terrifying presence on the battlefield. Pair him up with some of his fellows and cast concentrated blasts of destruction on anything, land or sky!","Training"=>300,"Speed"=>16,"Cost"=>4000,"Image"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/wizard.png","BG"=>"https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/wizard-bg.jpg","Theme"=>"#4FACFF")
    );

    $maxcount = count($resultarray);

    if(isset($params['count'])){

        if(!is_numeric($params['count']) || ($params['count'] > $maxcount)){
            return array('error'=>1001,'reason'=>'Invalid Count');
        }

    }

    $output = array_slice($resultarray, 0, $params['count']);

    return $output;
}
