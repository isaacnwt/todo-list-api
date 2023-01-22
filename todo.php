<?php
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
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
                "msg" => "Registro realizado com sucesso",
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
                "msg" => "Registro realizado com sucesso",
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
            $todo_list = Todo::getByUserId($id);
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

if(isMetodo("PUT")) {
    if(parametrosValidos($_PUT, ["id", "user_id", "title", "description", "done"])) {
        $user_id = $_PUT["user_id"];
        if(ToDo::existsUserId($user_id)) {
            $id = $_PUT["id"];
            $title = $_PUT["title"];
            $description = $_PUT["description"];
            $done = $_PUT["done"];

            if(Todo::update($id, $user_id, $title, $description, $done)) {
                $saida = array("status" => "Sucesso", "msg" => "Edicao realizada com sucesso");
                $codigo = 200;  
            } else {
                $saida = array("status" => "Erro", "msg" => "Falha ao editar");
                $codigo = 400;  
            }
        } else {
            $saida = array("status" => "Erro", "msg" => "Registro nao encontrado");
            $codigo = 404;    
        }
    } else {
        $saida = array("status" => "Erro", "msg" => "Operacao invalida");
        $codigo = 400;
    }
    responder($codigo, $saida);
}

if(isMetodo("DELETE")){
    if (parametrosValidos($_DELETE, ["id", "user_id"])) {
        $id = $_DELETE["id"];
        $user_id = $_DELETE["user_id"];
        if (ToDo::existsUserId($user_id)) {
            if (ToDo::delete($_DELETE["id"], $_DELETE["user_id"])) {
                $saida = array("status" => "Sucesso", "msg" => "Registro deletado com sucesso");
                $codigo = 200;
            }else{
                $saida = array("status" => "Erro", "msg" => "Falha ao deletar");
                $codigo = 400;
            }
        }else{
            $saida = array("status" => "Erro", "msg" => "Registro nao encontrado");
            $codigo = 404;
        }
    }else{
        $saida = array("status" => "Erro", "msg" => "Operacao Invalida");
        $codigo = 400;
    }
    responder($codigo, $saida);
}