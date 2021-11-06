<?php

function verifica_contas_encerradas($id){
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://automatips.com.br/api/Adm/getLogAposta?token=soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a&idAposta='.$id.'&tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A&_=163501092167477',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'authority: automatips.com.br',
        'sec-ch-ua: "Chromium";v="94", "Google Chrome";v="94", ";Not A Brand";v="99"',
        'accept: application/json, text/javascript, */*; q=0.01',
        'cache-control: no-cache',
        'x-requested-with: XMLHttpRequest',
        'sec-ch-ua-mobile: ?0',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36',
        'sec-ch-ua-platform: "Windows"',
        'sec-fetch-site: same-origin',
        'sec-fetch-mode: cors',
        'sec-fetch-dest: empty',
        'referer: https://automatips.com.br/v2/dashboardAdm.html',
        'accept-language: pt-PT,pt;q=0.9,en-US;q=0.8,en;q=0.7',
        'cookie: token="soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a"; tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A; Servidor=http://automatips.com.br:7009; emailLogin=josealberto.gomes@hotmail.com; dtVen=2021-10-31T02:40:16Z; io=bk6qwm8f6U90rdR8ACR5'
    ),
    ));

    $response = json_decode(curl_exec($curl), TRUE)['Data'];

    curl_close($curl);

    $usuarios_novos = [];
    foreach($response as $linha){
        if($linha['logTexto'] == "Cashout realizado com sucesso!"){
            $usuarios_novos[] = $linha['contausuario'];
        }
    }
    $usuarios_novos = array_unique($usuarios_novos);

    return $usuarios_novos;
}

function verifica_usuario($id, $usuarios_antigos, $partida){
    $usuarios_novos = verifica_contas_encerradas($id);

    if(count($usuarios_novos) != count($usuarios_antigos)){
        envia_contas_encerradas($usuarios_novos, $partida);
    }
    return $usuarios_novos;
}

function pega_partidas_db($num_partidas){
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $query = "SELECT * FROM aposta LIMIT '$num_partidas'";
    $rs = pg_query($db_handle, $query);
    $row = pg_fetch_all($rs);
    return $row;
}

function envia_contas_encerradas($usuarios, $partida){
    $APIurl = "https://eu210.chat-api.com/instance219965/";
    $token = "1krwdq4lagx0dj1p";
    $array_usuarios = array("elenir19904" => array("04",
                                                                    "elenir19904",
                                                                    "",
                                                                    ""),
                            "ironsword1" => array("10",
                                                                    "ironsword1",
                                                                    "",
                                                                    ""),
                            "rafaelpordeus1" => array("12",
                                                                    "rafaelpordeus1",
                                                                    "",
                                                                    ""),
                            "flaviajanynne" => array("15",
                                                                    "flaviajanynne",
                                                                    "",
                                                                    ""),
                            "luisfred2019" => array("17",
                                                                    "luisfred19",
                                                                    "",
                                                                    ""),
                            "ivinalima1" => array("19",
                                                                    "ivinalima1",
                                                                    "",
                                                                    ""),
                            "valkle" => array("22",
                                                                    "valkle",
                                                                    "",
                                                                    ""),
                            "milu2021" => array("24",
                                                                    "milu2021",
                                                                    "",
                                                                    ""),
                            "caiocabralgba" => array("26",
                                                                    "caiocabralgba",
                                                                    "",
                                                                    ""),
                            "thainamilanez" => array("27",
                                                                    "thainamilanez",
                                                                    "",
                                                                    ""),
                            "alexmagno2008" => array("29",
                                                                    "alexmagno2008",
                                                                    "",
                                                                    ""),
                            "ster30" => array("43",
                                                                    "ster30",
                                                                    "",
                                                                    ""),
                            "thays087" => array("50",
                                                                    "thays087",
                                                                    "",
                                                                    ""),
                            "carolineols" => array("51",
                                                                    "carolineols",
                                                                    "",
                                                                    ""),
                            "marcioespeto" => array("52",
                                                                    "marcioespeto",
                                                                    "",
                                                                    ""));
                                                                    
    foreach($usuarios as $usuario){
            $array_usuarios[$usuario][3] = " 🟢";
    }

    $mensagem = urlencode("⚠️ *ENCERRANDO APOSTA*\n\n*".$partida."*\n\n");
    foreach($array_usuarios as $usuario){
        $mensagem = $mensagem.urlencode($usuario[0]." - ".$usuario[1].$usuario[3]."\n");
    }
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629251097@g.us&body=".$mensagem);

}

function encerra_aposta($id){
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://automatips.com.br/api/Adm/cashout?betid='.$id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'authority: automatips.com.br',
        'sec-ch-ua: "Chromium";v="94", "Google Chrome";v="94", ";Not A Brand";v="99"',
        'accept: */*',
        'content-type: application/json',
        'x-requested-with: XMLHttpRequest',
        'sec-ch-ua-mobile: ?0',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36',
        'sec-ch-ua-platform: "Windows"',
        'sec-fetch-site: same-origin',
        'sec-fetch-mode: cors',
        'sec-fetch-dest: empty',
        'referer: https://automatips.com.br/v2/dashboardAdm.html',
        'accept-language: pt-PT,pt;q=0.9,en-US;q=0.8,en;q=0.7',
        'cookie: token="soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a"; tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A; Servidor=http://automatips.com.br:7009; emailLogin=josealberto.gomes@hotmail.com; dtVen=2021-10-31T02:40:16Z; io=bk6qwm8f6U90rdR8ACR5'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

function requisitar_apostas(){
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://automatips.com.br/api/Bot/getBets?token=soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a&pendentes=sim&tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A&_=1630162602502',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'authority: automatips.com.br',
    'sec-ch-ua: "Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
    'accept: application/json, text/javascript, */*; q=0.01',
    'cache-control: no-cache',
    'x-requested-with: XMLHttpRequest',
    'sec-ch-ua-mobile: ?0',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'referer: https://automatips.com.br/v2/dashboardAdm.html',
    'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
    'cookie: token="soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a"; tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A; Servidor=http://automatips.com.br:7009; emailLogin=josealberto.gomes@hotmail.com; dtVen=2021-08-29T02:40:16Z'
  ),
));


    $response = json_decode(curl_exec($curl), TRUE);

    curl_close($curl);
    $ultimas_apostas = array_slice($response['Data'], 0, 79);
    return $ultimas_apostas;
}

function verifica_usuarios($id){
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://automatips.com.br/api/Bot/getBetsUser?token=soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a&matchid='.$id.'&tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A&_=1630162602507',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'authority: automatips.com.br',
    'sec-ch-ua: "Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
    'accept: application/json, text/javascript, */*; q=0.01',
    'cache-control: no-cache',
    'x-requested-with: XMLHttpRequest',
    'sec-ch-ua-mobile: ?0',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'referer: https://automatips.com.br/v2/dashboardAdm.html',
    'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
    'cookie: token="soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a"; tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A; Servidor=http://automatips.com.br:7009; emailLogin=josealberto.gomes@hotmail.com; dtVen=2021-08-29T02:40:16Z'
  ),
));


    $response = json_decode(curl_exec($curl), TRUE);

    curl_close($curl);

    return $response['Data'];
}

function verifica_status(){
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://automatips.com.br/api/Adm/getUsuarios?token=soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a&tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'authority: automatips.com.br',
    'sec-ch-ua: "Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
    'accept: application/json, text/javascript, */*; q=0.01',
    'x-requested-with: XMLHttpRequest',
    'sec-ch-ua-mobile: ?0',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
    'content-type: application/json; charset=utf-8',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'referer: https://automatips.com.br/v2/dashboardAdm.html',
    'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
    'cookie: token="soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a"; tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A; Servidor=http://automatips.com.br:7009; emailLogin=josealberto.gomes@hotmail.com; dtVen=2021-08-29T02:40:16Z'
  ),
));

$response = json_decode(curl_exec($curl), TRUE);

curl_close($curl);
return $response['Data'];
}

function muda_usuario($usuario, $status){
    $curl = curl_init();
    $array_usuarios = array("contarfxinvesting04@gmail.com" => array("04",
                                                                    "elenir19904",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting10@gmail.com" => array("10",
                                                                    "ironsword1",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting12@gmail.com" => array("12",
                                                                    "rafaelpordeus1",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting15@gmail.com" => array("15",
                                                                    "flaviajanynne",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting17@gmail.com" => array("17",
                                                                    "luisfred19",
                                                                    "",
                                                                    " ⚫"),
                            "conrfxinvesting192@gmail.com" => array("19",
                                                                    "ivinalima1",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting22@gmail.com" => array("22",
                                                                    "valkle",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting24@gmail.com" => array("24",
                                                                    "milu2021",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting26@gmail.com" => array("26",
                                                                    "caiocabralgba",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting27@gmail.com" => array("27",
                                                                    "thainamilanez",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting29@gmail.com" => array("29",
                                                                    "alexmagno2008",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting43@gmail.com" => array("43",
                                                                    "ster30",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting50@gmail.com" => array("50",
                                                                    "thays087",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting51@gmail.com" => array("51",
                                                                    "carolineols",
                                                                    "",
                                                                    " ⚫"),
                            "contarfxinvesting52@gmail.com" => array("52",
                                                                    "marcioespeto",
                                                                    "",
                                                                    " ⚫"));
    curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://automatips.com.br/api/Usuario/alteraStatusClientePainel?email='.$usuario.'&contaBet365='.$array_usuarios[$usuario][1].'&status='.$status.'&token=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'authority: automatips.com.br',
    'sec-ch-ua: "Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
    'accept: application/json, text/javascript, */*; q=0.01',
    'x-requested-with: XMLHttpRequest',
    'sec-ch-ua-mobile: ?0',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
    'content-type: application/json; charset=utf-8',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'referer: https://automatips.com.br/v2/dashboardAdm.html',
    'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
    'cookie: token="soMe6uEUlLUIi6aslS1v7ons5EHGbnTkUQDMl9inUveRfXSpIEgdsQqeKGvdF3a"; tokenAplicacao=JOS2F00AF043DBB75A3B12F28A5D4A1391A48EE9DD3DF424F840C63BCD3345CE02A; Servidor=http://automatips.com.br:7009; emailLogin=josealberto.gomes@hotmail.com; dtVen=2021-08-29T02:40:16Z'
  ),
));

    $response = json_decode(curl_exec($curl), TRUE);

    curl_close($curl);
    return $array_usuarios;
}

function seleciona_id_aposta($numero){
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $seleciona_id = "SELECT id FROM aposta WHERE numero='$numero'";
    $result = pg_query($db_handle, $seleciona_id);
    $row = pg_fetch_assoc($result);
    $id = $row['id'];
    return $id;
}

function seleciona_partida_aposta($id){
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $seleciona_partida = "SELECT partida FROM aposta WHERE id='$id'";
    $result = pg_query($db_handle, $seleciona_partida);
    $row = pg_fetch_assoc($result);
    $partida = $row['partida'];
    return $partida;
}

function cadastra_apostas($apostas){
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $deletar_query = "TRUNCATE TABLE aposta";
    $deletar_dados = pg_query($db_handle, $deletar_query);

    $i = 1;
    foreach($apostas as $aposta){
        if($aposta['tipsterAtivo'] == 'Bloco D'){
            $id = $aposta['matchID'];
            $id2 = json_decode($aposta['dadosAposta'], TRUE)['betId'];
            $partida = $aposta['evento']." - ".$aposta['mercado']." - ".$aposta['aposta'];
            $adicionar_query = "INSERT INTO aposta (numero, id, id2, partida) VALUES ('$i', '$id', '$id2', '$partida')";
            $adicionar_dados = pg_query($db_handle, $adicionar_query);
            $i++;
        }
    }
}

function seleciona_id2($numero){
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $seleciona_id2 = "SELECT id2 FROM aposta WHERE numero='$numero'";
    $result = pg_query($db_handle, $seleciona_id2);
    $row = pg_fetch_assoc($result);
    $id2 = $row['id2'];
    return $id2;
}

function seleciona_numeropartida(){
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $seleciona_numeropartida = "SELECT numeropartida FROM chat WHERE menu='5'";
    $result = pg_query($db_handle, $seleciona_numeropartida);
    $row = pg_fetch_assoc($result);
    $numeropartida = $row['numeropartida'];
    return $numeropartida;
}

function verifica_apostas_concluidas($array_aposta){

    $array_aposta_cadastrada = array();
    $i = 0;
    foreach($array_aposta as $aposta){
        foreach($array_aposta as $key => $aposta_duplicada){
            if($aposta['partida'] == $aposta_duplicada['partida']){
                $array_aposta_cadastrada[$aposta['partida']][] = $aposta_duplicada;
                unset($array_aposta[$key]);
            }
        }
        $i++;
    }
    $mensagem = "";
    foreach($array_aposta_cadastrada as $key => $aposta){
        $array_usuarios = array("contarfxinvesting04@gmail.com" => array("04",
                                                                    "elenir19904",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting10@gmail.com" => array("10",
                                                                    "ironsword1",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting12@gmail.com" => array("12",
                                                                    "rafaelpordeus1",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting15@gmail.com" => array("15",
                                                                    "flaviajanynne",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting17@gmail.com" => array("17",
                                                                    "luisfred19",
                                                                    "0",
                                                                    " ⚫"),
                            "conrfxinvesting192@gmail.com" => array("19",
                                                                    "ivinalima1",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting22@gmail.com" => array("22",
                                                                    "valkle",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting24@gmail.com" => array("24",
                                                                    "milu2021",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting26@gmail.com" => array("26",
                                                                    "caiocabralgba",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting27@gmail.com" => array("27",
                                                                    "thainamilanez",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting29@gmail.com" => array("29",
                                                                    "alexmagno2008",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting43@gmail.com" => array("43",
                                                                    "ster30",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting50@gmail.com" => array("50",
                                                                    "thays087",
                                                                    "0",
                                                                    " ⚫"),
                            "contarfxinvesting51@gmail.com" => array("51",
                                                                    "carolineols",
                                                                    "0",
                                                                    " ⚫"));
        $usuarios_aposta = array();
        $controle_duplicadas = 0;
        $controle_naofeitas = 0;
        foreach($aposta as $aposta_duplicada){
            $usuarios = verifica_usuarios($aposta_duplicada['id']);
            foreach($usuarios as $usuario){
                if($usuario['resultado'] == 0){
                    $array_usuarios[$usuario['emailUsuario']][2]++;
                }  
            }
        }
        
        $mensagem_duplicadas = "🔄 Contas duplicadas:\n";
        $mensagem_naofeitas = "⛔ Contas que não fizeram:\n";
        foreach($array_usuarios as $usuario){
            if($usuario[2] != 1){
                if($usuario[2] == 0){
                    $controle_naofeitas = 1;
                    $mensagem_naofeitas = $mensagem_naofeitas.$usuario[0]." - ".$usuario[1]."\n";
                }if($usuario[2] > 1){
                    $controle_duplicadas = 1;
                    $mensagem_duplicadas = $mensagem_duplicadas.$usuario[0]." - ".$usuario[1]." (".$usuario[2]."x)\n";
                }
            }
        }
        
        if($controle_naofeitas == 1 or $controle_duplicadas == 1){
            $mensagem = $mensagem."*".$key."*\n\n";
            if($controle_naofeitas == 1){
                $mensagem = "\n".$mensagem.$mensagem_naofeitas."\n";
            }if($controle_duplicadas == 1){
                $mensagem = "\n".$mensagem.$mensagem_duplicadas."\n";
            }
        }
    }
    return $mensagem;
}

function envia_dados($data){
    $data_string = json_encode($data);

    $url = "https://menurfx3.herokuapp.com";

    $headr = array();
    $headr[] = 'Content-length: '.strlen( $data_string );
    $headr[] = 'Content-type: application/json';

    $ch = curl_init( $url );

    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headr );

    $result = curl_exec( $ch );
}

$APIurl = 'https://eu210.chat-api.com/instance219965/';
$token = '1krwdq4lagx0dj1p';

$requisicaocod = file_get_contents("php://input");
$requisicao = json_decode($requisicaocod, TRUE);

if(array_key_exists("messages", $requisicao)){
$texto = urlencode($requisicao["messages"][0]["body"]);


$minha = $requisicao["messages"][0]['fromMe'];

$db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
$conversa_query = "SELECT * FROM chat WHERE numero=1";
$seleciona_conversa = pg_query($db_handle, $conversa_query);
$array_conversa = pg_fetch_array($seleciona_conversa, 0);

if(!empty($texto) and empty($array_conversa['menu'])){
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".urlencode("*Selecione a opção desejada:*\n\n*1.* Reenviar apostas\n*2.* Religar todas as contas\n*3.* Verificar apostas\n*4.* ⚠️ Encerrar Aposta"));
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $menu = 1;
    $hora = time();
    $menu_query = "UPDATE chat SET hora='$hora', menu='$menu' WHERE numero=1";
    $seleciona_menu = pg_query($db_handle, $menu_query);
}else if($texto == "1" and $array_conversa['menu'] == 1 and ($array_conversa['hora'] + 1800) >= time()){
    $mensagem = urlencode("*Digite o número de alguma aposta para desligar as contas:*\n\n");
    $apostas = requisitar_apostas();
    $i = 1;
    foreach($apostas as $aposta){
        if($aposta['tipsterAtivo'] == 'Bloco D'){
        $mensagem = $mensagem.urlencode("*".$i.".* ".$aposta['evento']." - ".$aposta['aposta']."\n");
        $i++;
        }
    }
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".$mensagem);
    cadastra_apostas($apostas);
    $hora = time();
    $menu = 2;
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $update_menu = "UPDATE chat SET hora='$hora', menu='$menu' WHERE numero=1";
    $atualiza_menu = pg_query($db_handle, $update_menu);
}else if($texto == "4" and $array_conversa['menu'] == 1 and ($array_conversa['hora'] + 1800) >= time()){
    $mensagem = urlencode("*⚠️ Digite o número de alguma aposta para encerrar:*\n\n");
    $apostas = requisitar_apostas();
    $i = 1;
    foreach($apostas as $aposta){
        if($aposta['tipsterAtivo'] == 'Bloco D'){
        $mensagem = $mensagem.urlencode("*".$i.".* ".$aposta['evento']." - ".$aposta['aposta']."\n");
        $i++;
        }
    }
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".$mensagem);
    cadastra_apostas($apostas);
    $hora = time();
    $menu = 4;
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $update_menu = "UPDATE chat SET hora='$hora', menu='$menu' WHERE numero=1";
    $atualiza_menu = pg_query($db_handle, $update_menu);
}else if($texto == "3" and $array_conversa['menu'] == 1 and ($array_conversa['hora'] + 1800) >= time()){
    $mensagem = urlencode("*⚠️ Iremos verificar as apostas do número 1 até o número que você selecionar:*\n\n");
    $apostas = requisitar_apostas();
    $i = 1;
    foreach($apostas as $aposta){
        if($aposta['tipsterAtivo'] == 'Bloco D'){
        $mensagem = $mensagem.urlencode("*".$i.".* ".$aposta['evento']." - ".$aposta['aposta']."\n");
        $i++;
        }
    }
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".$mensagem);
    cadastra_apostas($apostas);
    $hora = time();
    $menu = 3;
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $update_menu = "UPDATE chat SET hora='$hora', menu='$menu' WHERE numero=1";
    $atualiza_menu = pg_query($db_handle, $update_menu);
}else if(is_numeric($texto) and $array_conversa['menu'] == 3 and ($array_conversa['hora'] + 1800) >= time()){
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".urlencode("*Verificando apostas...*"));
    $mensagem = urlencode(verifica_apostas_concluidas(pega_partidas_db($texto)));
    if($mensagem != ""){
        file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".$mensagem);
    }else{
        file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".urlencode("✅ Todas as apostas foram enviadas com sucesso. Sem apostas duplicadas ou contas sem pegar!"));
    }

    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $deletar_query = "TRUNCATE TABLE aposta";
    $deletar_dados = pg_query($db_handle, $deletar_query);
    $deletar2_query = "TRUNCATE TABLE chat";
    $deletar2_dados = pg_query($db_handle, $deletar2_query);
    $reiniciar =  "INSERT INTO chat (numero) VALUES (1)";
    $reiniciar_dados = pg_query($db_handle, $reiniciar);
}else if(is_numeric($texto) and $array_conversa['menu'] == 4 and ($array_conversa['hora'] + 1800) >= time()){
    $id = seleciona_id_aposta($texto);
    $partida = seleciona_partida_aposta($id);
    $menu = 5;
    $hora = time();
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".urlencode("*⚠️ Deseja realmente encerrar a seguinte aposta? ".$partida."*\n\n1. Sim\n2. Não"));
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $update_menu = "UPDATE chat SET hora='$hora', menu='$menu', numeropartida = '$texto' WHERE numero=1";
    $atualiza_menu = pg_query($db_handle, $update_menu);
}else if((strtolower($texto) == "sim" or $texto == "1") and $array_conversa['menu'] == 5 and ($array_conversa['hora'] + 1800) >= time()){
    $numeropartida = seleciona_numeropartida();
    $id2 = seleciona_id2($numeropartida);
    encerra_aposta($id2);
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".urlencode("*⚠️ Comando de encerrar enviado!*"));
    $usuarios_antigos = array();
    $id = seleciona_id_aposta($numeropartida);
    $partida = seleciona_partida_aposta($id);
    for($i=0;$i<5;$i++){
        sleep(5);
        $usuarios_antigos = verifica_usuario($id, $usuarios_antigos, $partida);
    }
    $data = array($id, $usuarios_antigos, $partida, 1);

    envia_dados($data);

    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $deletar_query = "TRUNCATE TABLE aposta";
    $deletar_dados = pg_query($db_handle, $deletar_query);
    $deletar2_query = "TRUNCATE TABLE chat";
    $deletar2_dados = pg_query($db_handle, $deletar2_query);
    $reiniciar =  "INSERT INTO chat (numero) VALUES (1)";
    $reiniciar_dados = pg_query($db_handle, $reiniciar);
}
else if(is_numeric($texto) and $array_conversa['menu'] == 2 and ($array_conversa['hora'] + 1800) >= time()){
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".urlencode("*Desligando contas. Aguarde...*"));
    $id = seleciona_id_aposta($texto);
    $usuarios = verifica_usuarios($id);
    $email_usuarios_pegaram = array();
    $mensagem = urlencode("*Status dos Usuários:*\n\n");
    foreach($usuarios as $usuario){
        if($usuario['resultado'] == 0){
            $email_usuarios_pegaram[] = $usuario['emailUsuario'];
            $array_usuarios = muda_usuario($usuario['emailUsuario'], 0);
        }
    }
    $status = verifica_status();

    foreach($status as $user){
        if(!empty($array_usuarios[$user['email']][0])){
        if($user['statusPainel'] == 0){
            $array_usuarios[$user['email']][2] = "⚫";
        }else{
            $array_usuarios[$user['email']][2] = "🟢";
        }
        }
    }
    foreach($array_usuarios as $usuario){
        $mensagem = $mensagem.urlencode($usuario[0]." - ".$usuario[1]."  ".$usuario[2]."\n");
    }
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".$mensagem);
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $deletar_query = "TRUNCATE TABLE aposta";
    $deletar_dados = pg_query($db_handle, $deletar_query);
    $deletar2_query = "TRUNCATE TABLE chat";
    $deletar2_dados = pg_query($db_handle, $deletar2_query);
    $reiniciar =  "INSERT INTO chat (numero) VALUES (1)";
    $reiniciar_dados = pg_query($db_handle, $reiniciar);
}else if($texto == "2" and $array_conversa['menu'] == 1 and ($array_conversa['hora'] + 1800)>= time()){
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".urlencode("*Religando contas. Aguarde...*"));
    $usuarios = verifica_status();
    foreach($usuarios as $usuario){
        $array_usuarios = muda_usuario($usuario['email'], 1);
    }
    $status = verifica_status();
    $mensagem = urlencode("*Usuários ligados:*\n\n");
    foreach($status as $user){
        if(!empty($array_usuarios[$user['email']][0])){
        if($user['statusPainel'] == 0){
            $array_usuarios[$user['email']][2] = "⚫";
        }else{
            $array_usuarios[$user['email']][2] = "🟢";
        }
        }
    }
    foreach($array_usuarios as $usuario){
        $mensagem = $mensagem.urlencode($usuario[0]." - ".$usuario[1]."  ".$usuario[2]."\n");
    }
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".$mensagem);
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $deletar_query = "TRUNCATE TABLE aposta";
    $deletar_dados = pg_query($db_handle, $deletar_query);
    $deletar2_query = "TRUNCATE TABLE chat";
    $deletar2_dados = pg_query($db_handle, $deletar2_query);
    $reiniciar =  "INSERT INTO chat (numero) VALUES (1)";
    $reiniciar_dados = pg_query($db_handle, $reiniciar);
}else{
    $db_handle = pg_connect("host=ec2-54-147-93-73.compute-1.amazonaws.com dbname=d8q4dlsoafqi5t port=5432 user=cqcnyvrfyyhzoo password=c7dfc5c9eade7b20eb4e7f1b7df52adc5f7c026ec5b38d59f968961ba92c0625");
    $deletar_query = "TRUNCATE TABLE aposta";
    $deletar_dados = pg_query($db_handle, $deletar_query);
    $deletar2_query = "TRUNCATE TABLE chat";
    $deletar2_dados = pg_query($db_handle, $deletar2_query);
    $reiniciar =  "INSERT INTO chat (numero) VALUES (1)";
    $reiniciar_dados = pg_query($db_handle, $reiniciar);
    file_get_contents($APIurl."sendMessage?token=".$token."&chatId=558399711150-1629250128@g.us&body=".urlencode("*Selecione a opção desejada:*\n\n*1.* Reenviar apostas\n*2.* Religar todas as contas\n*3.* Verificar apostas\n*4.* ⚠️ Encerrar Aposta"));
    $menu = 1;
    $hora = time();
    $menu_query = "UPDATE chat SET hora='$hora', menu='$menu' WHERE numero=1";
    $seleciona_menu = pg_query($db_handle, $menu_query);
}
}else{
    $usuarios_antigos = $requisicao[1];
    $id = $requisicao[0];
    $partida = $requisicao[2];
    $j = $requisicao[3];
    for($i=0;$i<5;$i++){
        sleep(5);
        $usuarios_antigos = verifica_usuario($id, $usuarios_antigos, $partida);
    }
    $j++;
    if($j<=20){
        $data = array($id, $usuarios_antigos, $partida, $j);
        envia_dados($data);
    }
}
?>
