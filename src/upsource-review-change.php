<?php
$slack = "https://hooks.slack.com/services/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";
$upsourceData = json_decode(file_get_contents("php://input"), true);
$majorVersion = $upsourceData["majorVersion"];
$minorVersion = $upsourceData["minorVersion"];
$projectId = $upsourceData["projectId"];
$dataType = $upsourceData["dataType"];
$dataBody = $upsourceData["data"];
$dataBase = $dataBody["base"];
$userIds = $dataBase["userIds"];
$toUserId = $userIds[0]["userId"];
$toUserName = $userIds[0]["userName"];
$toUserEmail = $userIds[0]["userEmail"];
$reviewNumber = $dataBase["reviewNumber"];
$date = $dataBase["date"];
$actor = $dataBase["actor"];
$actorUserId = $actor["userId"];
$actorUserName = $actor["userName"];
$actorUserEmail = $actor["userEmail"];
$feedEventId = $dataBase["feedEventId"];

$oldState = $dataBody["oldState"];
$newState = "재오픈 하였습니다.";
if ($dataBody["newState"] == 1) {
	$newState = "종료 시켰습니다.";
}

$myFile = fopen("review-change.txt", "w");
fwrite($myFile, json_encode($upsourceData));
fclose($myFile);

$reviewUrl = "http://red.eyeq.co.kr:8989/toga/review/TA-CR-" . $reviewNumber;

$fallback = "리뷰의 상태가 변경되었습니다.";
$preText = $actorUserName . "님이 리뷰를 " . $newState . "\n" . "[<" . $reviewUrl . "|리뷰 보러 가기>]";
$notiColor = "#D00000";

//$subTitle = "신규 리뷰";
//$subValue = "[<" . $reviewUrl . "|리뷰 보러 가기>]";
//$subShort = true;
//$fieldsBody = array("title" => $subTitle, "value" => $subValue, "short" => $subShort);
$fields = array();

$attachmentsBody = array("fallback" => $fallback, "pretext" => $preText, "color" => $notiColor, "fields" => $fields);
$attachment = array($attachmentsBody);
$payload = array("attachments" => $attachment);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $slack);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch);
echo $reviewNumber;
?>