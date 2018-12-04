<?php
/*********************/
/** MONEY FUNCTION ***/
/********************/

function setMoney($pDAO,$idUser,$newValueMoney) //100%
{
  $resultat["error"] = EXIT_CODE_OK;
  if($idUser == "")
  {
    $resultat["error"] = $pDAO["User"]->checkIdUser($idUser);
    if($resultat["error"] == 0)
    {
      $resultat["money"] = $pDAO["User"]->setAllMoney(intval($newValueMoney));
    }
    else
    {
      $resultat["error"] = EXIT_CODE_INCORRECT_ID_USER;
    }
  }
  else
  {
    $resultat["money"] = $pDAO["User"]->setMoney($idUser,intval($newValueMoney));
  }
  if($resultat["money"] == -1)
  {
    $resultat["error"] = EXIT_CODE_ERROR_SQL;
  }
  return $resultat;

};

function getMoney($pDAO,$idUser) ////100%
{
  $resultat["error"] = EXIT_CODE_OK;
  if($idUser == "")
  {
    $resultat["money"] = $pDAO["User"]->getAllMoney();
  }
  else
  {
    $resultat["error"] = $pDAO["User"]->checkIdUser($idUser);
    if($resultat["error"] == 0)
    {
      $resultat["money"] = $pDAO["User"]->getMoney($idUser);
    }
    else
    {
      $resultat["error"] = EXIT_CODE_INCORRECT_ID_USER;
    }

  }
  if($resultat["money"] == -1)
  {
    $resultat["error"] = EXIT_CODE_ERROR_SQL;
  }
  return $resultat;

};

/******************/
/* FUNCTION PARAM  */
/******************/

function getParam($pDAO) //100%
{
  $resultat["param"] = $pDAO["Param"]->getAll();

  if($resultat["param"] == -1)
  {
    $resultat["error"] = EXIT_CODE_ERROR_SQL;
  }
  else
  {
    $resultat["error"] = EXIT_CODE_OK;
  }

  return $resultat;
};


/*********************/
/*** FUNCTION USER***/
/*********************/

function getUser($pDAO,$idUser) //100%
{
  $resultat["error"] = EXIT_CODE_OK;
  if($idUser == "")
  {
    $resultat["user"] = $pDAO["User"]->getAll();
  }
  else
  {
    $resultat["error"] = $pDAO["User"]->checkIdUser($idUser);
    if($resultat["error"] == 0)
    {
      $resultat["user"] = $pDAO["User"]->getOne($idUser);
    }
    else
    {
      $resultat["error"] = EXIT_CODE_INCORRECT_ID_USER;
    }
  }
  if($resultat["user"] == -1)
  {
    $resultat["error"] = EXIT_CODE_INCORRECT_USER_READ;
  }
  return $resultat;
};


function setUser($pDAO,$idUser) //OK
{
  $resultat["error"] = EXIT_CODE_OK;
  if($idUser == "")
  {
    $resultat["user"] = $pDAO["User"]->getAll();
  }
  else
  {
    $resultat["error"] = $pDAO["User"]->checkIdUser($idUser);
    if($resultat["error"] == 0)
    {
      $resultat["user"] = $pDAO["User"]->getOne($idUser);
    }
    else
    {
      $resultat["error"] = EXIT_CODE_INCORRECT_ID_USER;
    }
  }
  if($resultat["user"] == -1)
  {
    $resultat["error"] = EXIT_CODE_ERROR_SQL;
  }
  return $resultat;
};

/******************/
/* FUNCTION CARDS  */
/******************/

function getCard($pDAO,$idCard) //100%
{
  $resultat["error"] = EXIT_CODE_OK;
  if($idCard == "")
  {
    $resultat["card"] = $pDAO["Card"]->getAll();
  }
  else
  {
    $resultat["error"] = $pDAO["Card"]->checkIdCard($idCard);
    if($resultat["error"] == 0)
    {
      $resultat["card"] = $pDAO["Card"]->getOne($idCard);
    }
    else
    {
      $resultat["error"] = EXIT_CODE_INCORRECT_ID_USER;
    }
    if($resultat["card"] == false)
    {
      $resultat["error"] = EXIT_CODE_ERROR_SQL;
    }
  }

  return $resultat;
};

function setCardInInventoryByUserId($pDAO,$idUser,$idCard)
{

  $resultat["error"] = EXIT_CODE_OK;

  $resultat["error"] = $pDAO["Inventory"]->insert($idCard,$idUser);

  if($resultat["error"] == false)
  {
    $resultat["error"] = EXIT_CODE_OK;
  }
  else
  {
    $resultat["error"] = EXIT_CODE_ERROR_INSERTION_IN_DB;
  }
  return $resultat;

};

function exchangeCard($pDAO,$idUser,$idUser_secondary,$idCard,$idCard_secondary)
{

  $flagUsersCardsExists = false;
  $flagUsersCardsInInventory = false;

  $resultatUsers = $pDAO["Inventory"]->usersExist($idUser,$idUser_secondary); //Check User
  if($resultatUsers)
  {
        var_dump("LES USER 2 EXISTES PAS");
    return $resultat["error"] = EXIT_CODE_INCORRECT_ID_USER;

  }
  $resultatCards = $pDAO["Inventory"]->cardsExist($idCard,$idCard_secondary); //Check Card

  if($resultatCards)
  {
    var_dump("LES CARTES 2 EXISTES PAS");
    return $resultat["error"] = EXIT_CODE_CARDS_IS_MISSING_IN_DB;
  }

  $resultatCardsPlayer = $pDAO["Inventory"]->checkInventoryExist($idCard,$idUser); //Check InventoryidUserOne

  if($resultatCardsPlayer)
  {
    var_dump("ERROR DANS CHECK INVENTORY 1 EXIST");
    return $resultat["error"] = EXIT_CODE_CARDS_IS_MISSING_IN_DB;
  }

  $resultatCardsPlayerSecondary = $pDAO["Inventory"]->checkInventoryExist($idCard_secondary,$idUser_secondary); //Check InventoryidUserTwo

  if($resultatCardsPlayerSecondary)
  {
    var_dump("ERROR DANS CHECK INVENTORY 2 EXIST");
    return $resultat["error"] = EXIT_CODE_CARDS_IS_MISSING_IN_DB;
  }

  $resultatDeleteCards = $pDAO["Inventory"]-> deleteInventory($idUser,$idUser_secondary,$idCard,$idCard_secondary);
  var_dump($resultatDeleteCards);
  if($resultatDeleteCards)
  {
    var_dump("ERROR DANS LE DELETE");
    return $resultat["error"] = EXIT_CODE_ERROR_SQL;
  }

  $resultatExchange = $pDAO["Inventory"]-> exchange($idUser,$idUser_secondary,$idCard,$idCard_secondary);


  $idInventory=0; //TODO recup iDInventory deleted
  $pDate= CURRENT_DATE();//getdate();
  $type ="DELETE_REC";
  $details= "";
  $price = 0;

  $resultatInsertLog1 = $pDAO["Inventory"]-> insertTransaction_History($idCard,$idUser,$idUser_secondary,$pDate,$type,$details, $idInventory, $price);


  return $resultatExchange;
       //TODO after tests  copy this all to exchange2 function
          $idInventory=0; //TODO recup iDInventory new created
          $pDate= CURRENT_DATE();//getdate();
          $type = "INSERT_REC" ;
          $details= "";
          $price = 0;

  if ($resultatExchange == EXIT_CODE_OK)
     {
        // Log record type Exchange  =>  insert to table Transaction_history
        $resultatInsertLog2 = $pDAO["Inventory"]-> insertTransaction_History($idCard,$idUser,$idUser_secondary,
                                                                    $pDate,$type,$details, $idInventory, $price);
                                                  //TODO 2 insert -><- bc 2 inserts
       }

     $resultat["error"] = $resultatExchange;//EXIT_CODE_OK;

   return $resultat;
}






function getRandomCard($pDAO) //100%
{
  $resultat["error"] = EXIT_CODE_OK;
    $resultat["randomCard"] = $pDAO["Card"]->getRandomCard($idUser);
    if($resultat["randomCard"] == -1)
    {
      $resultat["error"] = EXIT_CODE_ERROR_SQL;
    }
  return $resultat;
};


function deleteCardByUserid($pDAO,$idUser,$idCards)
{
  $resultat["error"] = $pDAO["User"]->checkIdUser($idUser);
  if($resultat["error"] == 0)
  {
    if($idCards != "")
    {
      if($pDAO["Card"]->checkIdCard($idCards) == EXIT_CODE_OK)
      {
        if($pDAO["Inventory"]->checkInventoryExist($idCards,$idUser) == EXIT_CODE_OK)
        {
          $pDAO["Inventory"]->delete($idCards,$idUser);
        }
        else
        {
          $resultat["error"] = EXIT_CODE_INVENTORY_IS_MISSING;
        }
      }
      else
      {
        $resultat["error"] = EXIT_CODE_CARDS_IS_MISSING_IN_DB;
      }
    }
    else
    {
      $pDAO["Inventory"]->deleteAll($idUser);
    }
  }
  else
  {
    $resultat["error"] = EXIT_CODE_INCORRECT_ID_USER;
  }
  return $resultat;
}

/******************/
/* FUNCTION OTHER  */
/******************/


function craftOneCard($pDAO,$idUser,$cards)  //Non Fonctionnel
{
  $resultat["error"] = EXIT_CODE_NO_IMPLEMENTED_FUNCTION;
  return $resultat;
}

function meltCards($pDAO,$idUser,$cards)   //Non Fonctionnel
{
  $resultat["error"] = EXIT_CODE_NO_IMPLEMENTED_FUNCTION;
  return $resultat;
}

function getQuestion($pDAO)  //Non Fonctionnel
{
  $resultat["error"] = EXIT_CODE_NO_IMPLEMENTED_FUNCTION;
  return $resultat;
  //Voir API Goub
}

function setAnswer($pDAO,$idUser,$numAnswer,$idQuestion)  //Non Fonctionnel
{
  $resultat["error"] = EXIT_CODE_NO_IMPLEMENTED_FUNCTION;
  return $resultat;
  //Voir API Goub
}

/**********************/
/* FUNCTION INVENTORY */
/**********************/

function getInventory($pDAO,$IdUser)  //Ready for test
{
  $resultat["error"] = EXIT_CODE_NO_IMPLEMENTED_FUNCTION;
  $resultat["inventory"]=  $pDAO["Inventory"]->getAllCardByUserId($pIdUser);
  return $resultat;
  //return $pDAO["Card"]->getAll($IdUser);
}



/********************/
/* FUNCTION CONNECT */
/********************/

function connect($pDAO,$pLogin,$pPass,$pKey,$pMode) //100%
{
  $resultat["error"] = EXIT_CODE_OK;
  if($pMode == "facebook")
  {
    $resultat["connect"] = $pDAO["User"]->checkAccountWithFacebook($pKey);
  }
  else if($pMode == "google")
  {
    $resultat["connect"] = $pDAO["User"]->checkAccountWithGoogle($pKey);
  }
  else if($pMode == "")
  {
    $resultat["connect"] = $pDAO["User"]->checkAccountWithBase($pLogin,$pPass);
  }
  else
  {
    $resultat["connect"] = false;
    $resultat["error"] = EXIT_CODE_TOO_LONG_URI;
  }
  return $resultat;
}

function register($pDAO,$pLogin,$pPass,$pKey,$pMode) //100%
{
  $resultat["error"] = EXIT_CODE_OK;
  if($pMode == "facebook")
  {
    $checking = $pDAO["User"]->checkAccountWithFacebook($pKey);
    if($checking == true)
    {
      $resultat["error"] = EXIT_CODE_FACEBOOK_ACCOUNT_EXIST;
    }
    else
    {
      $resultat["connect"] = $pDAO["User"]->registerWithFacebook($pKey);
    }
  }
  else if($pMode == "google")
  {
    $checking = $pDAO["User"]->checkAccountWithGoogle($pKey);
    if($checking == true)
    {
      $resultat["error"] = EXIT_CODE_GOOGLE_ACCOUNT_EXIST;
    }
    else
    {
      $resultat["connect"] = $pDAO["User"]->registerWithGoogle($pKey);
    }
  }
  else if($pMode == "")
  {
    $checking = $pDAO["User"]->checkAccountWithBase($pLogin,$pPass);

    if($checking == true)
    {
      $resultat["error"] = EXIT_CODE_LOGIN_EXIST;
    }
    else
    {
      $resultat["connect"] = $pDAO["User"]->registerWithBase($pLogin,$pPass);
    }
  }
  else
  {
    $resultat["connect"] = false;
    $resultat["error"] = EXIT_CODE_TOO_LONG_URI;
  }
  return $resultat;
}
