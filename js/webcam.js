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

	function getFilterSize()
	{
		var	canvas = document.getElementById('canvas'),
			y1 = canvas.height,
			y2 = canvas.height,
			x1 = canvas.width,
			x2 = canvas.width;

		var tab = [];
		if (imagePngName == 'snapback')
		{
			tab[0] = x1 - (x1 / 1.43);
			tab[1] = 0;
			tab[2] = x2 - (x2 / 1.70);
			tab[3] = y2 - (y2 / 1.88);
		}
		else if (imagePngName == 'gangsta')
		{
			tab[0] = 0;
			tab[1] = 0;
			tab[2] = x2;
			tab[3] = y2;
		}
		else if (imagePngName == 'lol')
		{
			tab[0] = x1 / 3;
			tab[1] = y1 - (y1 / 1.05);
			tab[2] = x2 / 1.73;
			tab[3] = y2 / 1.71;
		}
		else if (imagePngName == 'batman')
		{
			tab[0] = x1 - (x1 / 1.30);
			tab[1] = 0;
			tab[2] = x2 - (x2 / 2.50);
			tab[3] = y2 / 1.66;
		}
		else if (imagePngName == 'boss')
		{
			tab[0] = x1 - (x1 / 1.25);
			tab[1] = y1 - (y1 / 2.10);
			tab[2] = x2 - (x2 / 2.35);
			tab[3] = y2 - (y2 / 3);
		}
		else if (imagePngName == 'chain')
		{
			tab[0] = x1 - (x1 / 1.3);
			tab[1] = y1 - (y1 / 2);
			tab[2] = x2 - (x2 / 2.5);
			tab[3] = y2;
		}
		return (tab);
	}

	function selectFilter()
	{
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

	function sendPicture(data, filterPath, filterSize)
	{
		var xml = new XMLHttpRequest()
		xml.open('POST', '../members/scripts/mergeImage.php', true);
		xml.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xml.send("data="+data+"&filterPath="+filterPath+"&filterSize="+filterSize);
		// xml.onreadystatechange = function() {
  //       	if (this.readyState == 4 && this.status == 200)
  //           	console.log(this.responseText);
  //       }
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
		var filterPath = selectFilter(imagePngName);
		var tabSize = getFilterSize();
		var data = canvas.toDataURL('image/png');

		if (filterPath && tabSize)
			addPng(tabSize[0], tabSize[1], tabSize[2], tabSize[3]);
		
		if (closeIconCreated == 0 && saveIconCreated == 0)
		{
			closeIcon = createCloseIcon();
			saveIcon = createSaveIcon();
			snap.appendChild(closeIcon);
			snap.appendChild(saveIcon);
			closeIconCreated = 1;
			saveIconCreated = 1;
			
			closeIcon.addEventListener('click', clearPicture);
			saveIcon.addEventListener('click', sendPicture(data, filterPath, tabSize));
		}
	}

})();