<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/auto_load.php"; //Inclusion du chargement de tout les fichiers

$errorCode = EXIT_CODE_OK;

$arrayUri = getArrayUri();
$typeReqHttp = $_SERVER['REQUEST_METHOD'];

if(!$errorCode)
{
  switch($typeReqHttp)
  {
    case REQUEST_DELETE:
    switch($arrayUri[1])
    {
      case ROUTE_CARD:

      if($arrayUri[3] == SUB_ROUTE_EMPTY)
      {

        $resultat = deleteCardByUserid($DAO,$arrayUri[2],$arrayUri[3]);
        $errorCode = $resultat["error"];
      }
      else
      {
        $errorCode = EXIT_CODE_TOO_LONG_URI;
      }
      break;
      default:
      $errorCode = EXIT_CODE_INVALID_URI;
      break;
    }
    break;

    case REQUEST_POST:
    switch($arrayUri[1])
    {
      case ROUTE_CARD:
      if($arrayUri[3] == SUB_ROUTE_EMPTY)
      {
        if($arrayUri[2] == SUB_ROUTE_EXCHANGE)
        {
          if(isset($_POST['cardUserOne']) && isset($_POST['cardUserTwo']) && isset($_POST['idUserTwo']) && isset($_POST['idUserOne']))
          {
            $resultat = exchangeCards($DAO,$_POST['idUserOne'],$_POST['idUserTwo'],$_POST['cardUserOne'],$_POST['cardUserTwo']);
            $errorCode = $resultat["error"];
          }
          else
          {
            $errorCode = EXIT_CODE_MISSING_PARAMETTER;
          }
        }
        else
        {
          if(is_numeric($arrayUri[2]))
          {
            if(isset($_POST['cards']))
            {
              $resultat = setCardByUserId($DAO,$arrayUri[2],$_POST['cards']);
              $errorCode = $resultat["error"];
            }
            else
            {
              $errorCode = EXIT_CODE_MISSING_PARAMETTER;
            }
          }
          else
          {
            $errorCode = EXIT_CODE_INVALID_URI;
          }

        }
      }
      else
      {
        $errorCode = EXIT_CODE_TOO_LONG_URI;
      }

      break;
      case ROUTE_MONEY:
      if(isset($_POST['value']))
      {
        if($arrayUri[3] == SUB_ROUTE_EMPTY)
        {
          if($arrayUri[2] == SUB_ROUTE_EMPTY)
          {
            $resultat = setMoney($DAO,"",$_POST['value']);
            $errorCode = $resultat["error"];
          }
          else
          {
            setMoney($DAO,$arrayUri[2],$_POST['value']);
          }
        }
        else
        {
          $errorCode = EXIT_CODE_TOO_LONG_URI;
        }
      }
      else
      {
        $errorCode = EXIT_CODE_UNKNOW_VALUE_PARAMETTER;
      }
      break;

      case ROUTE_OTHER:
      if(is_numeric($arrayUri[3]))
      {
        switch($arrayUri[2])
        {
          case SUB_ROUTE_MELT:
          if(isset($_POST['cards']))
          {
            $resultat = meltCards($DAO,$arrayUri[3],$_POST['cards']);
            $errorCode = $resultat["error"];
          }
          else
          {
            $errorCode = EXIT_CODE_MISSING_PARAMETTER;
          }
          break;

          case SUB_ROUTE_CRAFTCARD:
          if(isset($_POST['cards']))
          {
            $resultat = craftOneCard($DAO,$arrayUri[3],$_POST['cards']);
            $errorCode = $resultat["error"];
          }
          else
          {
            $errorCode = EXIT_CODE_MISSING_PARAMETTER;
          }
          break;

          case SUB_ROUTE_QUIZZ:

          if(isset($_POST['numAnswer']) && isset($_POST['idQuestion']))
          {
            $resultat = setAnswer($DAO,$arrayUri[3],$_POST['$numAnswer'],$_POST['$idQuestion']);
            $errorCode = $resultat["error"];
          }
          else
          {
            $errorCode = EXIT_CODE_MISSING_PARAMETTER;
          }
          break;

          default:
          $errorCode = EXIT_CODE_INVALID_URI;
        }
      }
      else
      {
        $errorCode = EXIT_CODE_INCORRECT_ID_USER;
      }
      break;
      case ROUTE_CONNECT:
      if(isset($_POST['login']) && isset($_POST['pass']))
      {
        if($arrayUri[2] == SUB_ROUTE_EMPTY)
        {
          connect($DAO,$_POST['login'],$_POST['pass']);
        }
        else
        {
          $errorCode = EXIT_CODE_INVALID_URI;
        }
      }
      else
      {
        $errorCode = EXIT_CODE_UNKNOW_VALUE_PARAMETTER;
      }
      break;
      default:
      $errorCode = EXIT_CODE_INVALID_URI;
    }
    break;

    case REQUEST_GET:
    switch($arrayUri[1])
    {
      case ROUTE_USER:
      if($arrayUri[3] == SUB_ROUTE_EMPTY)
      {
        $resultat = getUser($DAO,$arrayUri[2]);
        $errorCode = $resultat["error"];
        $user = $resultat["user"];
      }
      else
      {
        $errorCode = EXIT_CODE_TOO_LONG_URI;
      }
      break;
      case ROUTE_MONEY:

      if($arrayUri[3] == SUB_ROUTE_EMPTY)
      {
        if($arrayUri[2] == SUB_ROUTE_EMPTY)
        {
          $resultat = getMoney($DAO,$arrayUri[2]);
          $errorCode = $resultat["error"];
          $money = $resultat["money"];
        }
        else if(is_numeric($arrayUri[2]))
        {
          $resultat = getMoney($DAO,$arrayUri[2]);
          $errorCode = $resultat["error"];
          $money = $resultat["money"];
        }
        else
        {
          $errorCode = EXIT_CODE_INVALID_URI;
        }

      }
      else
      {

        $errorCode = EXIT_CODE_TOO_LONG_URI;
      }
      break;
      case ROUTE_PARAM:
      if($arrayUri[2] == SUB_ROUTE_EMPTY)
      {
        $resultat = getParam($DAO);
        $errorCode = $resultat["error"];
      }
      else
      {
        $errorCode = EXIT_CODE_TOO_LONG_URI;
      }
      break;
      case ROUTE_CARD:
      if(is_numeric($arrayUri[2]) || SUB_ROUTE_EMPTY == $arrayUri[2])
      {
        $resultat = getCard($DAO,$arrayUri[2]);
        $errorCode = $resultat["error"];
      }
      else
      {
        $errorCode = EXIT_CODE_TOO_LONG_URI;
      }
      break;
      case ROUTE_OTHER:
      if(is_numeric($arrayUri[3]))
      {
        if($arrayUri[2] == SUB_ROUTE_QUIZZ)
        {
          $resultat = getQuestion($DAO);
          $errorCode = $resultat["error"];
        }
        else
        {
          $errorCode = EXIT_CODE_INVALID_URI;
        }
      }
      else
      {
        $errorCode = EXIT_CODE_INVALID_URI;
      }
      break;
      default:
      $errorCode = EXIT_CODE_INVALID_URI;
    }
    break;
    default:
    $errorCode = EXIT_CODE_INCORRECT_TYPE_REQUEST;
    break;
  }

  $newInventory = getInventory($DAO,$arrayUri[2]);
}

sendHttpRespond($arrayUri[1],$DAO,$arrayUri[2],$money,$inventory,$errorCode,$typeReqHttp);

function sendHttpRespond($pRequest,$pDAO,$pUser,$pMoney,$pCards,$pErrorCode,$typeReqHttp)
{
  header('Content-type: application/json');
  if($pErrorCode == EXIT_CODE_OK)
  {
    if($typeReqHttp == REQUEST_GET)
    {
      switch($pRequest)
      {
        case ROUTE_MONEY:
        if($pUser != null)
        {
          $data = ["user"=>$pUser,"money"=>$pMoney];
        }
        else
        {
          $data = ["money"=>$pMoney];
        }
        break;



        default:
        $data = ["user"=>$pUser,"cards"=>$cards];
        break;
      }
      echo json_encode(["exitCode"=>$pErrorCode,"data"=>$data]);
    }
    else
    {
      echo json_encode(["exitCode"=>$pErrorCode]);
    }
  }
  else
  {
    echo json_encode(["exitCode"=>$pErrorCode]);
  }


}

function checkIdUser($pDAO,$arrayUri)
{
  $postIdUser = $arrayUri[2];
  if(isset($postIdUser))
  {
    if($pDAO["User"]->checkOneById($idUser))//Checking de l'id user coté serveur
    {
      return EXIT_CODE_OK;
    }
    else
    {
      return EXIT_CODE_INCORRECT_ID_USER;
    }
  }
  else
  {
    return EXIT_CODE_UNKNOW_ID_USER;
  }
}

function getArrayUri()
{
  $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
  $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
  if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
  $uri = '/' . trim($uri, '/');
  $routes = array();
  $routes = explode('/', $uri);
  return $routes;
}



function debugVarDump($var)
{
  echo var_dump($var);
}
