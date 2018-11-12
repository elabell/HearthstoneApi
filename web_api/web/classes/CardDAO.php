<?php
class CardDAO extends DAO {

    public function getOne($id_card)
    {
      $stmt = $this->pdo->prepare("SELECT * FROM Cards WHERE id = ?");
      $stmt->execute(array($id_card));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return ["card"=>$row];
    }

    public function getAll()
    {
      $stmt = $this->pdo->prepare("SELECT * FROM Cards");
      $stmt->execute();
      foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
      {
        $result[] = [$row['id']=>$row];
      }
      return ["cards"=>$result];
    }

    public function getRandomCard()
    {
      $stmt = $this->pdo->prepare("SELECT * FROM Cards ORDER BY RAND() LIMIT 1");
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      return $row;
    }

    public function update($obj)
    {
      return true;
    }

    public function insert($pCard)
    {
      $stmt = $this->pdo->prepare("INSERT INTO Cards(id,nameCard,description,url,type,attack,life,cost) VALUES (:id,:nameCard,:description,:url,:type,:attack,:life,:cardCost)");
      $stmt->execute(array('id'=>$pCard['id'],'nameCard'=>$pCard['nameCard'],'description'=>$pCard['description'],'url'=>$pCard['url'],'type'=>$pCard['type'],'attack'=>$pCard['attack'],'life'=>$pCard['life'],'cardCost'=>$pCard['cardCost']));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return $row;
    }

    public function delete($id_card,$id_user)
    {
      return true;
    }


    public function checkExist($id_card,$id_user)
    {
      return true;
    }

}
