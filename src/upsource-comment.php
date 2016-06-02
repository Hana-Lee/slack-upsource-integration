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
$fromUserId = $userIds[1]["userId"];
$fromUserName = $userIds[1]["userName"];
$fromUserEmail = $userIds[1]["userEmail"];
$reviewNumber = $dataBase["reviewNumber"];
$date = $dataBase["date"];
$actor = $dataBase["actor"];
$actorUserId = $actor["userId"];
$actorUserName = $actor["userName"];
$actorUserEmail = $actor["userEmail"];
$feedEventId = $dataBase["feedEventId"];
$notificationReason = $dataBody["notificationReason"];
$discussionId = $dataBody["discussionId"];
$commentId = $dataBody["commentId"];
$commentText = $dataBody["commentText"];

$myFile = fopen("review-comment.txt", "w");
fwrite($myFile, json_encode($upsourceData));
fclose($myFile);

$reviewUrl = "http://red.eyeq.co.kr:8989/toga/review/TA-CR-" . $reviewNumber . "?commentId=" . $commentId;

$fallback = $actorUserName . "님이 댓글을 생성 했습니다. [<" . $reviewUrl . "|댓글확인>]";
$preText = $actorUserName . "님이 댓글을 생성 했습니다. [<" . $reviewUrl . "|댓글확인>]";
$notiColor = "#D00000";

$subTitle = "댓글";
$subValue = $commentText;
$subShort = true;
$fieldsBody = array("title" => $subTitle, "value" => $subValue, "short" => $subShort);
$fields = array($fieldsBody);

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