<?php
    # ���������� ���� ����������
    require("facebook.php");
    # ������� ������ ������ Facebook
    $facebook = new Facebook(array(
        'appId'  => '540192692728862',
        'secret' => '3c86035b6485c88008cb6dc67b42bfba',
        'cookie' => true
    ));

 $fc_uid = $facebook->getUser();
 $fc_api_call = array(
     'method' => 'users.getinfo',
     'uids' => $fc_uid,
     'fields' => 'uid, first_name, last_name, pic_square, pic_big, sex'
 );
 $fc_users_getinfo = $facebook->api($fc_api_call);
 print_r($fc_users_getinfo);
/*
    #������� ID ������������
    $user = $facebook->getUser();
    if (!empty($user)) {
        try {   
                # ������ ������ �� �������������� ������������.
                $user_profile = $facebook->api('/me');
                print_r($user_profile);
              }
      catch (FacebookApiException $e) {
            error_log($e);
            $user = null;
        }
    }
    else
    {  
        # ��� ������ �� ����������� � ������, ���������� ������������ ��� �����������  
        $login_url = $facebook->getLoginUrl();  
        header("Location: ".$login_url);  
    }
*/    
?>