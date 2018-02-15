(function() {
    var snap = document.getElementById('snap'),
    	cam = document.getElementById('webcam'),
    	canvas = document.getElementById('canvas'),
    	take = document.getElementById('take'),
    	closeIconCreated = 0,
    	saveIconCreated = 0,
    	isCamera = false,
    	imgIsLoad = false;
    
    var imagePng = document.getElementsByClassName('imagePng');
    	snapback = document.getElementById('snapback'),
    	gangsta = document.getElementById('gangsta'),
    	lol = document.getElementById('lol'),
    	batman = document.getElementById('batman'),
    	boss = document.getElementById('boss'),
    	imagePngName = '',
    	i = -1;

    while (++i < imagePng.length)
		imagePng[i].addEventListener('click', pictureEnabled);


	var constraints = { video: true, audio: false },
		setCam = navigator.mediaDevices.getUserMedia(constraints);

    setCam.then(function haveCamera(mediaStream)
    {
    	isCamera = true;
        cam.srcObject = mediaStream;
        cam.onloadedmetadata = function () {
            cam.play();
        };
	})
	.catch(function noCamera()
	{
		createImportDiv();
		var upload = document.getElementById('upload');
		upload.addEventListener('change', uploadImg);
	}
	);

	function uploadImg(e)
	{
	    var success = document.createElement('div'),
	    	newP = document.createElement('p'),
	    	text = document.createTextNode('Your picture has been successfully uploaded.'),
	    	img = new Image();

	    canvas.style.height = '48vh';
	    canvas.style.visibility = 'visible';
	    importImg.style.visibility = 'hidden';

	    img.src = URL.createObjectURL(e.target.files[0]);
	    img.addEventListener('load', function(){
	    	canvas.getContext('2d').drawImage(img, 0, 0, 300, 200);
	    	imgIsLoad = true;
	    	success.setAttribute('class', 'alert-successNav');
	    	newP.appendChild(text);
			success.appendChild(newP);
	    	document.body.insertBefore(success, snap);
	    });
	}

	function clearPicture()
	{
		canvas.setAttribute('width', 0);
		canvas.setAttribute('height', 0);
		snap.removeChild(closeIcon);
		snap.removeChild(saveIcon);
		closeIconCreated = 0;
		saveIconCreated = 0;
		if (!isCamera)
			location.reload(false);
	}

	function createInput()
	{
		var input = document.createElement('input');
		input.type = 'file';
		input.id = 'upload';
		input.name = 'upload';
		input.accept = 'image/jpeg,image/png,image/gif';
		return input;
	}

	function createImportDiv()
	{
		var importImg = document.createElement('div'),
			info = document.createElement('p'),
			text = document.createTextNode('Import your image and choose a filter');
		
		isCamera = false;
		importImg.id = 'importImg';
		snap.replaceChild(importImg, cam);
		info.appendChild(text);
		importImg.appendChild(info);

		input = createInput();
		importImg.appendChild(input);
		canvas.style.visibility = 'hidden';
	}

	function createCloseIcon()
	{
		var closeIcon = document.createElement('img');
		closeIcon.src = '/camagru/img/close.png';
		closeIcon.id = 'closeIcon';
		return closeIcon;
	}

	function createSaveIcon()
	{
		var saveIcon = document.createElement('img');
		saveIcon.src = '/camagru/img/save.png';
		saveIcon.id = 'saveIcon';
		return saveIcon;
	}

	function pictureEnabled()
	{
       	var inc = -1;

       	while (++inc < imagePng.length)
       		imagePng[inc].style.border = '2px solid #dfdfdf';
       	this.style.border = '2px solid #e74c3c';

       	imagePngName = this.firstChild.id;
       	if (isCamera || (!isCamera && imgIsLoad))
       	{
	       	take.disabled = false;
	       	take.style.visibility = 'visible';
	       	take.addEventListener('click', takePicture);
	    }
	}
	
	function addPng(dx, dy, width, height)
	{
		if (imagePngName == 'snapback')
			canvas.getContext('2d').drawImage(snapback, dx, dy, width, height);
		else if (imagePngName == 'gangsta')
			canvas.getContext('2d').drawImage(gangsta, dx, dy, width, height);
		else if (imagePngName == 'lol')
			canvas.getContext('2d').drawImage(lol, dx, dy, width, height);
		else if (imagePngName == 'batman')
			canvas.getContext('2d').drawImage(batman, dx, dy, width, height);
		else if (imagePngName == 'boss')
			canvas.getContext('2d').drawImage(boss, dx, dy, width, height);
		else if (imagePngName == 'chain')
			canvas.getContext('2d').drawImage(chain, dx, dy, width, height);
	}

	function selectFilter()
	{
		var filter;
		if (imagePngName == 'snapback')
			filter = '../../img/snapback.png';
		else if (imagePngName == 'gangsta')
			filter = '../../img/gangsta.png';
		else if (imagePngName == 'lol')
			filter = '../../img/lol.png';
		else if (imagePngName == 'batman')
			filter = '../../img/batman.png';
		else if (imagePngName == 'boss')
			filter = '../../img/boss.png';
		else if (imagePngName == 'chain')
			filter = '../../img/chain.png';
		return (filter);
	}

	function sendPicture(data, filterPath, imported)
	{
		var xml = new XMLHttpRequest()
		xml.open('POST', '../members/scripts/mergeImage.php', true);
		xml.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xml.send("data="+data+"&filterPath="+filterPath+"&imported="+imported);
		xml.onload = function() {
           window.location.reload();
       }
	}

	function takePicture()
	{
		var imported;
		if (isCamera)
		{
			canvas.width = cam.offsetWidth;
			canvas.height = cam.offsetHeight;
			canvas.getContext('2d').drawImage(cam, 0, 0, cam.offsetWidth, cam.offsetHeight);
			imported = false;
		}
		else
		{
			var importImg = document.getElementById('importImg');
			importImg.style.visibility = 'hidden';
			canvas.style.visibility = 'visible';
			imported = true;
		}

		var data = canvas.toDataURL('image/png');

		if (closeIconCreated == 0 && saveIconCreated == 0)
		{
			closeIcon = createCloseIcon();
			saveIcon = createSaveIcon();
			snap.appendChild(closeIcon);
			snap.appendChild(saveIcon);
			closeIconCreated = 1;
			saveIconCreated = 1;		
			
			var filterPath = selectFilter();
			if (filterPath)
			{
				var width = (importImg) ? 300 : 512;
				var height = (importImg) ? 200 : 384;
				addPng(0, 0, width, height);
			}
			closeIcon.addEventListener('click', clearPicture);
			take.addEventListener('click', function () {
				clearPicture();
				takePicture();
			});
			saveIcon.addEventListener('click', function () {
				sendPicture(data, filterPath, imported);
				clearPicture();
			});
		}		
	}

})();