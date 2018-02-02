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
	
	function addPng()
	{
		var	canvas = document.getElementById('canvas');
			y1 = canvas.height,
			y2 = canvas.height,
			x1 = canvas.width,
			x2 = canvas.width;

		if (imagePngName == 'snapback')
			canvas.getContext('2d').drawImage(snapback, x1 - (x1 / 1.43), 0, x2 - (x2 / 1.70), y2 - (y2 / 1.88));
		else if (imagePngName == 'gangsta')
			canvas.getContext('2d').drawImage(gangsta, 0, 0, x2, y2);
		else if (imagePngName == 'lol')
			canvas.getContext('2d').drawImage(lol, x1 / 3, y1 - (y1 / 1.05), x2 / 1.73, y2 / 1.71);
		else if (imagePngName == 'batman')
			canvas.getContext('2d').drawImage(batman, x1 - (x1 / 1.30), 0, x2 - (x2 / 2.50), y2 / 1.66);
		else if (imagePngName == 'boss')
			canvas.getContext('2d').drawImage(boss, x1 - (x1 / 1.25), y1 - (y1 / 2.10), x2 - (x2 / 2.35), y2 - (y2 / 3));
		else if (imagePngName == 'chain')
			canvas.getContext('2d').drawImage(chain, x1 - (x1 / 1.3), y1 - (y1 / 2), x2 - (x2 / 2.5), y2);
	}

	function takePicture()
	{
		if (isCamera)
		{
			canvas.width = cam.offsetWidth;
			canvas.height = cam.offsetHeight;
			canvas.getContext('2d').drawImage(cam, 0, 0, cam.offsetWidth, cam.offsetHeight);
		}
		else
		{
			var importImg = document.getElementById('importImg');
			importImg.style.visibility = 'hidden';
			canvas.style.visibility = 'visible';
		}
		addPng(imagePngName);
		if (closeIconCreated == 0 && saveIconCreated == 0)
		{
			closeIcon = createCloseIcon();
			saveIcon = createSaveIcon();
			snap.appendChild(closeIcon);
			snap.appendChild(saveIcon);
			closeIconCreated = 1;
			saveIconCreated = 1;
			closeIcon.addEventListener('click', clearPicture);
			saveIcon.addEventListener('click', function()
			{
				var data = canvas.toDataURL('image/png');
				var xhr;
				// this.style.visibility='hidden';
				// this.innerHTML = '<form id="savePicture" action="scripts/actions.php" method="POST"><input type="hidden" name="imageTaken" value="'+data+'"></form>';
    //     		document.getElementById('savePicture').submit();

				if (window.XMLHttpRequest) {
				  xhr = new XMLHttpRequest();
				}
				else if (window.ActiveXObject) {
				  xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xhr.open("POST", "../members/scripts/mergeImage.php", false);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function() {
				  console.log('OK');
				}
				xhr.send("imgData=" + data);
			});
		}
	}

})();