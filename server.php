<?php
header("Content-type: text/html; charset=utf-8"); 

define(KEY, "4f3664efdfacd7f7968f72d2dd594113");//图灵fjyt
//define(KEY, "b073898069f9d162bff92779730a32cb");//5982
$getMessage = $_GET["message"]; 
$userId = $_GET["userId"]; 

if($userId == ""){ //用户ID 不能为空
	$userId = 0;
}


if($getMessage == "get_greet_request"){//GREET
      //get greet
      echo getGreet();

}else if(strpos($getMessage,'二维码') === 0){//code 
		$content = trim(str_replace('二维码', '',$getMessage));
      	echo "拿去吧！地球人！</br><img id='messageImg'  width='200' height='200' src='http://qr.liantu.com/api.php?bg=ffffff&fg=66ccff&text=".urlencode($content)."'/>";

}else if(strpos($getMessage,'地理位置') === 0){//place
	$place = trim(str_replace('地理位置', '',$getMessage));
	$baiduPoiUrl = "http://api.map.baidu.com/geocoder/v2/?ak=E4805d16520de693a3fe707cdc962045&callback=renderReverse&location=".$place."&output=json&pois=1";		
		$html = '查询地理数据';
	      try{
            $text = file_get_contents($baiduPoiUrl);
			if(strpos($text,'renderReverse') === 0){
				$placeJson1 = trim(str_replace('renderReverse&&renderReverse(', '',$text));
				$placeJson2 = substr($placeJson1, 0, -1);
				
				$result = json_decode($placeJson2);
			
				$html = $result->result->formatted_address;
			}else{
				$html = '哎呀！出错了~'.$text;
			}
		  } catch(Exception $e){
            //返回数据
      		$html = '哎呀！我不知道！我不知道你在哪里呀！~ ';        
		} 
		echo $html;
}else{
             //图灵接口
      $tulingApiUrl='http://www.tuling123.com/openapi/api?key='.KEY.'&userid='.$userId.'&info=';
            //发送get请求
      try{
            $text = file_get_contents($tulingApiUrl.urlencode($getMessage));
            $result = json_decode($text);
            switch ($result->code) {
                  case 100000://文字
                  $html = $result->text;
                  break;
                  case 200000://链接
                  $html = $result->text.": </br> "."<div class=\"app-button green corner_button\" onclick=\"javascrtpt:window.location.href='".$result->url."'\"> ---点击进入---</div>";
                  break;

                  case 301000://小说
                  case 312000://餐厅 
                  case 309000://酒店
                  case 304000://下载
                  case 307000://团购
                  case 308000://优惠
                  case 311000://价格
                  $caterList = $result->{'list'};
                  $caterListCount = count($caterList);
                  if($caterListCount <= 0){
                        $html = "哎呀！真不好意思，本喵这里没有你要的答案！ 喵~~";
                        break;
                  }
                  $index = rand(0, $caterListCount - 1);
                  $html = $result->text.": </br> </br> ".$caterList[$index]->{'name'}."</br>"."<a href=\"".$caterList[$index]->{'detailurl'}."\"> ---点击跳转链接---</a>";

                  break;

                  case 302000://新闻
                  $caterList = $result->{'list'};
                  $caterListCount = count($caterList);
                  if($caterListCount <= 0){
                        $html = "哎呀！真不好意思，本喵这里没有你要的答案！ 喵~~";
                        break;
                  }
                  $index = rand(0, $caterListCount - 1);
                  $html = $result->text.": </br> </br> ".$caterList[$index]->{'article'}."\n".$caterList[$index]->{'source'}."</br>"."<a href=\"".$caterList[$index]->{'detailurl'}."\"> ---点击跳转链接---</a>";

                  break;

                  case 305000://列车
                  $caterList = $result->{'list'};
                  $caterListCount = count($caterList);
                  if($caterListCount <= 0){
                        $html = "哎呀！真不好意思，本喵这里没有你要的答案！ 喵~~";
                        break;
                  }
                  $index = rand(0, $caterListCount - 1);
                  $html = $result->text.": </br> </br> ".$caterList[$index]->{'start'}.' - '.$caterList[$index]->{'terminal'}."\n".$caterList[$index]->{'starttime'}.' - '.$caterList[$index]->{'endtime'}."</br>"."<a href=\"".$caterList[$index]->{'detailurl'}."\"> ---点击跳转链接---</a>";

                  break;

                  case 306000://航班
                  $caterList = $result->{'list'};
                  $caterListCount = count($caterList);
                  if($caterListCount <= 0){
                        $html = "哎呀！真不好意思，本喵这里没有你要的答案！ 喵~~";
                        break;
                  }
                  $index = rand(0, $caterListCount - 1);
                  $html = $result->text.": </br> </br> ".$caterList[$index]->{'flight'}."\n".$caterList[$index]->{'route'}."\n".$caterList[$index]->{'starttime'}.' - '.$caterList[$index]->{'endtime'}
                  ."\n".$caterList[$index]->{'state'}.$caterList[$index]->{'detailurl'}."</br>"."<a href=\"".$caterList[$index]->{'detailurl'}."\"> ---点击跳转链接---</a>";

                  break;

                  case 310000://彩票
                  $caterList = $result->{'list'};
                  $caterListCount = count($caterList);
                  if($caterListCount <= 0){
                        $html = "哎呀！真不好意思，本喵这里没有你要的答案！ 喵~~";
                        break;
                  }
                  $index = rand(0, $caterListCount - 1);
                  $html = $result->text.": </br> </br> ".$caterList[$index]->{'number'}."\n".$caterList[$index]->{'info'}."</br>"."<a href=\"".$caterList[$index]->{'detailurl'}."\"> ---点击跳转链接---</a>";

                  break;



                  case 40001://key的长度错误（32位）
                  $html = "小花猫的程序好像出了点问题！ 喵~~";
                  break;
                  case 40006://服务器升级中
                  case 40004://当天请求次数已用完
                  $html = "小花猫困了，要去睡觉了~ 明天再陪你聊吧~ 喵~~";
                  break;
                  case 40005://不支持功能
                  $html = "这个功能我好像还不会哦！ 喵~~";
                  break;
                  case 40002://请求内容为空
                  case 40003://key错误或帐号未激活
                  case 40007://服务器数据格式异常
                  $html = "这，，我竟然无言以对！ 喵~~";
                  break;

                default://其他功能
                $html = "你这么问我，我也不知道怎么回答哦~ 喵~";
                break;
          }
    }catch(Exception $e){
            //返回数据
      $html = '哎呀！我不知道！我什么也不知道~ ';        
} 
echo $html;
}






      // //获取记录PV数量
 //  function getPVCount(){
 // //连主库
 //    $link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);

 //    if($link){
 //      mysql_select_db(SAE_MYSQL_DB, $link);
 //   //插入
 //      mysql_query(" UPDATE wechat_user SET nickname = '".$nickname."' WHERE  wechat_id = '".$userWechatId."'" );

 //      mysql_close($link);

 //    }else{
 //      die('getPVCount() failed! Could not connect: '.mysql_error());
 //    }

 //    return $queryUserResult;
 //  }

 //  //注册一个用户
 //  function registerUser($userWechatId){
 // //连主库
 //    $link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
 //  // 连从库
 //  // $link=mysql_connect(SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
 //    if($link){
 //      mysql_select_db(SAE_MYSQL_DB, $link);
 //  //插入
 //      mysql_query(" INSERT INTO pv (wechat_id) VALUES ('".$userWechatId."')");

 //      mysql_close($link);

 //    }else{
 //      die('registerUser() failed! Could not connect: '.mysql_error());
 //    }

 //    return $queryUserResult;
 //  }


      //获取招呼语
      function getGreet() {

      $greetArray = array(
            "我已经仰慕您很久了！有什么问题需要我来帮你解决吗？ 喵~",
            "哈哈！你有什么开心的事情吗？说出来让我听听呗~ 喵~",
            "你可以问问我任何你想知道的事情，至于回不回答你嘛，看我心情~ 喵~",
            "我喜欢吃鱼，你呢？喵~",
            "我是小花猫，我的作者是大花猫~ 喵~",
            "告诉你一个秘密：关注微信公众号“大大花猫”，它比我还能吹牛逼哦！喵~",
            "学挖掘机到底哪家强？！喵~",
            "天王盖地虎！",
            "你可以问问我今天的天气怎么样哦~ 喵~",
            "你回复我‘成语接龙’,我就跟你玩~ 喵~",
            "你回复我‘苍井空 图片’，你猜我回告诉你啥？ 喵~",
            "回复‘二维码’+‘内容’给我，我就给你搞一张内容对应的二维码 ~ ",
            "我是数学天才！1亿以内的加减乘除我都会！喵~~",
            "我是赛诸葛，懂吗！喵~",
            "喵~ 今天啥也不想做~ 好想懒懒地睡一觉啊~",
            "我是你的小呀小花猫，怎么爱我都不嫌多~ 喵喵…喵~",
          // "这朵白云老在我面前飘来飘去，好讨厌啊！喵！~",
          	"回复“我的位置”我就可以告诉你在哪里哦！",
          	"右上角的菜单里面有秘密哦~"
            );
      $index = rand(0, count($greetArray) - 1);
      return $greetArray[$index];
}


  function face($imgUrl)  
  {  
    // face++ 链接  
    $jsonStr =  
    file_get_contents("http://apicn.faceplusplus.com/v2/detection/detect?url=".$imgUrl."&api_key=".FACE_KEY."&api_secret=".FACE_SECRET."&attribute=glass,pose,gender,age,race,smiling");  

    $replyDic = json_decode($jsonStr);  
    $resultStr = "";  
    $faceArray = $replyDic->{'face'};  
    if(count($faceArray) == 0){
      return '哎呀！识别不出来！请尽量发 正面、清晰的人类全脸 哦！图像识别效率目前还比较低，所以希望见谅，后面我会尽快优化一些功能，先娱乐一下吧！';
    }
    $resultStr .= "图中共检测到".count($faceArray)."张脸！\n";  
    for ($i= 0;$i< count($faceArray); $i++){  
      $resultStr .= "<--第".($i+1)."张脸-->\n";  
      $tempFace = $faceArray[$i];  
        // 获取所有属性  
      $tempAttr = $tempFace->{'attribute'};  

        // 年龄：包含年龄分析结果  
        // value的值为一个非负整数表示估计的年龄, range表示估计年龄的正负区间  
      $tempAge = $tempAttr->{'age'};  

        // 性别：包含性别分析结果  
        // value的值为Male/Female, confidence表示置信度  
      $tempGenger = $tempAttr->{'gender'};   

        // 种族：包含人种分析结果  
        // value的值为Asian/White/Black, confidence表示置信度  
      $tempRace = $tempAttr->{'race'};       

        // 微笑：包含微笑程度分析结果  
        //value的值为0-100的实数，越大表示微笑程度越高  
      $tempSmiling = $tempAttr->{'smiling'};  

        // 眼镜：包含眼镜佩戴分析结果  
        // value的值为None/Dark/Normal, confidence表示置信度  
      $tempGlass = $tempAttr->{'glass'};     

        // 造型：包含脸部姿势分析结果  
        // 包括pitch_angle, roll_angle, yaw_angle  
        // 分别对应抬头，旋转（平面旋转），摇头  
        // 单位为角度。  
      $tempPose = $tempAttr->{'pose'};  

    //分数
      $grade = 0;

        // 返回性别  
      if($tempGenger->{'value'} === "Male")  {
        $grade += 30;
        $resultStr .= "嗨~ 帅哥！\n";   
      } else if($tempGenger->{'value'} === "Female")  {
        $grade += 50;
        $resultStr .= "嗨~ 美女！\n";  
      }

     //返回年龄  
      $minAge = $tempAge->{'value'} - $tempAge->{'range'};  
      $minAge = $minAge < 0 ? 0 : $minAge;  
      $maxAge = $tempAge->{'value'} + $tempAge->{'range'};  

        //$resultStr .= "年龄：".$minAge."-".$maxAge."岁\n";  
      $resultStr .= "我猜你".$tempAge->{'value'}."岁左右吧~ \n(误差 ".$tempAge->{'range'}."岁)\n";  
      $grade += (100 - $tempAge->{'value'})/2;

        // 返回种族  
      if($tempRace->{'value'} === "Asian")  {
        $grade += 30;
        $resultStr .= "肤色很健康哦~\n";     
      }
      else if($tempRace->{'value'} === "White") { 
        $grade += 40;
        $resultStr .= "你皮肤好白哟！^ 3^\n"; 
      }
      else if($tempRace->{'value'} === "Black")  {
        $grade += 10;
        $resultStr .= " 0.0 你有点黑？！！！\n";    
      }

        // 返回眼镜  
      if($tempGlass->{'value'} === "None") { 
        $grade += 30;
        $resultStr .= "不戴眼镜，看着很清爽哦！\n";    
      } else if($tempGlass->{'value'} === "Dark")  {
        $grade += 40;
        $resultStr .= "戴个墨镜真是靓极了！\n";    
      } else if($tempGlass->{'value'} === "Normal"){ 
        $grade += 20;
        $resultStr .= "嘿嘿，戴着眼镜呀，近视几度啦？\n";    
      }



      $happy = '';
      if(round($tempSmiling->{'value'})>55){
        $grade += round($tempSmiling->{'value'} + 10);
        $happy = '笑得很开心嘛！继续保持哦！';
      }else if(round($tempSmiling->{'value'})>22){
        $grade += round($tempSmiling->{'value'} + 5);
        $happy = '你可以笑得更灿烂点哦！亲~';
      }else{
        $grade += round($tempSmiling->{'value'});
        $happy = '亲，有啥不开心的吗？说出来让我开心一下呗~';
      }

        //返回微笑  
      $resultStr .= "微笑度：".round($tempSmiling->{'value'})."%\n".$happy."\n";  

      $resultStr .= "------------------------------ \n外貌协会专家评分：".$grade."分\n------------------------------ \n";
    }    



    if(count($faceArray) === 1){




    }else if(count($faceArray) === 2){  
        // 获取face_id  
      $tempFace = $faceArray[0];  
      $tempId1 = $tempFace->{'face_id'};  
      $tempFace = $faceArray[1];  
      $tempId2 = $tempFace->{'face_id'};  


        // face++ 链接  
      $jsonStr =  
      file_get_contents("https://apicn.faceplusplus.com/v2/recognition/compare?api_secret=".FACE_SECRET."&api_key=".FACE_KEY."&face_id2=".$tempId2 ."&face_id1=".$tempId1);  
      $replyDic = json_decode($jsonStr);  

        //取出相似程度  
      $tempResult = $replyDic->{'similarity'};  
      $suggest = '';
      if(round($tempResult)>55){
        $suggest = '哇塞！绝对的夫妻相了！';
      }else if(round($tempResult)>40){
        $suggest = "哎哟，长得挺像！\n你们快点在一起吧！";
      }else{
        $suggest = '0.0 长得不太一样哦。';
      }

      $resultStr .= "<----匹配结果---->\n两人相似程度：".round($tempResult)."%\n".$suggest." \n";  

        //具体分析相似处  
      $tempSimilarity = $replyDic->{'component_similarity'};  
      $tempEye = $tempSimilarity->{'eye'};  
      $tempEyebrow = $tempSimilarity->{'eyebrow'};  
      $tempMouth = $tempSimilarity->{'mouth'};  
      $tempNose = $tempSimilarity->{'nose'};  

      $resultStr .= "~~~~~~~~~~\n相似分析：\n";  
      $resultStr .= "眼睛：".round($tempEye)."%\n";  
      $resultStr .= "眉毛：".round($tempEyebrow)."%\n";  
      $resultStr .= "嘴巴：".round($tempMouth)."%\n";  
      $resultStr .= "鼻子：".round($tempNose)."%\n";  
    }  


    //如果没有检测到人脸  
    if($resultStr === "")  
      $resultStr = "悟空，你又调皮了！照片中木有人脸！ =.=\n";  
    //返回数据
    return $resultStr;  
  };  


?>
