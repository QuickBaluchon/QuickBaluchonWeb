<?PHP
    if(isset($_POST["send"]) && isset($_POST["app_id"])){
        if(isset($_POST["message"]))
            $response = sendMessage($_POST["message"]);
        else
            $response = sendMessage();
        $return["allresponses"] = $response;
        $return = json_encode( $return);
        header('Location:https://quickbaluchon.ovh/deliveryman/roadmap');
    }

    function sendMessage($message = null){
        if($message != null){
            $content = array(
                "en" => $message
            );
        }else{
            $content = array(
                "en" => "le client n'étais pas là"
            );
        }

        $fields = array(
            'app_id' => $_POST["app_id"],
            'include_player_ids' => [$_POST["send"]],
            'data' => ["foo" => "bar"],
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);


        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
