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

if (!empty($_POST) && isset($_POST['data'])
	&& isset($_POST['filterPath']) && isset($_POST['filterSize']))
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

	$img_filter = imagecreatefrompng($_POST['filterPath']);
	$filter_x = intval($_POST['filterSize'][0]);
	$filter_y = intval($_POST['filterSize'][1]);
	$filter_w = intval($_POST['filterSize'][2]);
	$filter_h = intval($_POST['filterSize'][3]);

	imagealphablending($img_filter, true);
	imagesavealpha($img_filter, true);

	if ($type == "png")
		$dest = imagecreatefrompng("$filePath");
	else if ($type == "jpeg" || $type == "jpg")
		$dest = imagecreatefromjpeg("$filePath");
	
	$dest_w = imagesx($dest);
	$dest_h = imagesy($dest);
	//$img_filter = imagecreatetruecolor($dest_w, $dest_h);
	$dest_x = ($dest_w - $filter_w);
	$dest_y = ($dest_h - $filter_h);
	imagecopy($dest, $img_filter, $dest_x, $dest_y, $filter_x, $filter_y, $filter_w, $filter_h);
	//imagecopyresampled($dest, $img_filter, 0, 0, $filter_x, $filter_y, $dest_w, $dest_h, $filter_w, $filter_h);
	imagepng($dest, $dir."/fusion".'.'.$type);
	imagedestroy($img_filter);
	imagedestroy($dest);
	unlink($filePath);
	rename($dir.'fusion.'.$type, $filePath);

	// $user->setPhoto($db, htmlspecialchars($fusion));
	// $session->setFlash('successNav', 'Your picture has been successfully added.');
}

?>