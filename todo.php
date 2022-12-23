<?php
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "configs/utils.php";
require_once "configs/methods.php";
require_once "model/ToDo.php";
// require_once "security/sessionVerifier.php"; Impementar futuramente????

if (isMetodo("POST")) {

    if (parametrosValidos($_POST, ["title", "description", "user_id"])) {
        $title = $_POST["title"];
        $description = $_POST["description"];
        $user_id = $_POST["user_id"];

        if (ToDo::create($title, $description, 0, $user_id)) {
            $saida = array(
                "status" => "Sucesso",
                "title" => $title,
                "description" => $description,
                "done" => 0
            );
            $codigo = 201;
        } else {
            $saida = array("status" => "Erro", "msg" => "Falha ao registrar");
            $codigo = 400;
        }
    } else if (parametrosValidos($_POST, ["title", "user_id"])) {
        $title = $_POST["title"];
        $user_id = $_POST["user_id"];

        if (ToDo::createWithNullDesc($title, 0, $user_id)) {
            $saida = array(
                "status" => "Sucesso",
                "title" => $title,
                "description" => null,
                "done" => 0
            );
            $codigo = 201;
        } else {
            $saida = array("status" => "Erro", "msg" => "Falha ao registrar");
            $codigo = 400;
        }
    } else {
        $saida = array("status" => "Erro", "msg" => "Operacao Invalida");
        $codigo = 400;
    }
    responder($codigo, $saida);
}

if (isMetodo("GET")) {

    if (parametrosValidos($_GET, ["id"])) {
        $id = $_GET["id"];
        if (ToDo::existsUserId($id)) {
            $todo_list = Todo::getToDoByUserId($id);
            $saida = [];
            foreach ($todo_list as $todo) {
                $saida[] = array(
                    "id" => $todo["id"],
                    "title" => $todo["title"],
                    "description" => $todo["description"],
                    "done" => $todo["done"],
                    "user_id" => $todo["user_id"]
                );
            }
            $codigo = 200;
        } else {
            $saida = array("status" => "Erro", "msg" => "Registro nao encontrado");
            $codigo = 404;
        }
    }
    responder($codigo, $saida);
}
