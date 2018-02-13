<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    header('Location: /camagru/index.php');	
}

require '../../templates/autoload.php';
require_once '../../config/setup.php';

$session = Session::getInstance();
$db = App::getDatabase($pdo);
$auth = new Auth($session);
$user = new User($_SESSION['auth']['id']);

if (!empty($_POST) && isset($_POST['data'])
	&& isset($_POST['filterPath']) && isset($_POST['imported']))
{
	$id = $_SESSION['auth']['id'];
	$dir = "../../img/photos/".$id."/";
	if (!file_exists($dir))
		mkdir($dir, 0777, true);
	$img = $_POST['data'];
	$base64data = explode(',', $img);
	$data_format = explode(';', $base64data[0]);
	$format = explode('/', $data_format[0]);
	$type = $format[1];
	$img = $base64data[1];
	$img = str_replace(' ', '+', $img);
	$dataDecode = base64_decode($img);
	$datePhoto = date("d-m-y_H.i.s");
	$filePath = $dir.$datePhoto.'.'.$type;
	file_put_contents($filePath, $dataDecode);

	header ("Content-type: image/png");
	header("Content-type: image/jpeg");
	header("Content-type: image/gif");

	$img_filter = imagecreatefrompng($_POST['filterPath']);

	imagealphablending($img_filter, true);
	imagesavealpha($img_filter, true);

	if ($type == "png")
		$dest = imagecreatefrompng("$filePath");
	else if ($type == "jpeg" || $type == "jpg")
		$dest = imagecreatefromjpeg("$filePath");
	else if ($type == "gif")
		$dest = imagecreatefromgif("$filePath");
	
	$filter_w = ($_POST['imported'] == false) ? imagesx($img_filter) : imagesx($img_filter) + 200;
	$filter_h = ($_POST['imported'] == false) ? imagesy($img_filter) : imagesx($img_filter) + 300;	
	print_r($_POST['imported']);
	imagecopyresampled($dest, $img_filter, 0, 0, 0, 0, 512, 384, $filter_w, $filter_h);
	imagepng($dest, $dir."/fusion".'.'.$type);
	imagedestroy($img_filter);
	imagedestroy($dest);
	unlink($filePath);
	rename($dir.'fusion.'.$type, $filePath);

	$user->setPhoto($db, htmlspecialchars($id.'/'.$datePhoto.'.'.$type));
	$session->setFlash('successNav', 'Your picture has been successfully added.');
}

?>