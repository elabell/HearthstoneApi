<?php

//////////////////////////////
// API HEARTHSTONE FUNCTION //
/////////////////////////////

function insertAllCardInDataBase($pDAO)
{
  $parse = file_get_contents(HEARTHSTONE_API_URI);
  $decoded = json_decode($parse, true);
  foreach($decoded as $c)
  {
    switch($c['type'])
    {
      case"HERO_POWER":
      if(DEACTIVATE) //Its not a card
      {
        $card = ["id"=>$c['id'],"nameCard"=>$c['name'],"description"=>$c['text'],"url"=>HEARTHSTONE_ART_URI.$c['id'].".png","type"=>$c['rarity'],"attack"=>"null","life"=>"null","cardCost"=>$c['cost']];
      }
      break;
      case"MINION":
      if(ACTIVATE)
      {
        $card = ["id"=>$c['id'],"nameCard"=>$c['name'],"description"=>$c['text'],"url"=>HEARTHSTONE_ART_URI.$c['id'].".png","type"=>$c['rarity'],"attack"=>$c['attack'],"life"=>$c['health'],"cardCost"=>$c['cost']];
      }
      break;
      case"SPELL":
      if(ACTIVATE)
      {
        $card = ["id"=>$c['id'],"nameCard"=>$c['name'],"description"=>$c['text'],"url"=>HEARTHSTONE_ART_URI.$c['id'].".png","type"=>$c['rarity'],"attack"=>"null","life"=>"null","cardCost"=>$c['cost']];
      }
      break;
      case"HERO":
      if(DEACTIVATE) //Its not a card
      {
        $card = ["id"=>$c['id'],"nameCard"=>$c['name'],"description"=>"null","url"=>HEARTHSTONE_ART_URI.$c['id'].".png","type"=>"null","attack"=>"null","life"=>$c['health'],"cardCost"=>"null"];
      }
      break;
      default:
      $card = [];
      break;

    }
    $pDAO["Card"]->insert($card);
  }
}
?>
