(function() {
    var snap = document.getElementById('snap'),
    	cam = document.getElementById('webcam'),
    	canvas = document.getElementById('canvas'),
    	take = document.getElementById('take'),
    	closeIconCreated = 0,
    	saveIconCreated = 0;
    
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


	var miniature = document.getElementsByClassName('miniature'),
		del = document.getElementsByClassName('delete-mini');

	i = -1;
    while (++i < miniature.length)
    	del[i].addEventListener('click', deletePicture);


	navigator.getMedia = (navigator.getUserMedia ||
                        navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia);

	navigator.getMedia(
		{
		  video: true,
		  audio: false
		},
		function(stream)
		{
			if (navigator.mozGetUserMedia)
				cam.mozSrcObject = stream;
			else
			{
				var vendorURL = (window.URL || window.webkitURL);
				cam.src = vendorURL.createObjectURL(stream);
			}
			cam.play();
		},
		function(err)
		{
		  console.log("An error occured! " + err);
		}
	);

	function clearPicture()
	{
		canvas.setAttribute('width', 0);
		canvas.setAttribute('height', 0);
		snap.removeChild(closeIcon);
		snap.removeChild(saveIcon);
		closeIconCreated = 0;
		saveIconCreated = 0;
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
       	take.disabled = false;
       	take.style.visibility = 'visible';
       	take.addEventListener('click', takePicture);
	}
	
	function addPng()
	{
		var y1 = cam.offsetHeight,
			y2 = cam.offsetHeight,
			x1 = cam.offsetWidth,
			x2 = cam.offsetWidth;

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
		canvas.width = cam.offsetWidth;
		canvas.height = cam.offsetHeight;
		canvas.getContext('2d').drawImage(cam, 0, 0, cam.offsetWidth, cam.offsetHeight);
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
				document.body.innerHTML = '<form id="savePicture" action="scripts/actions.php" method="POST"><input type="hidden" name="imageTaken" value="'+data+'"></form>';
        		document.getElementById('savePicture').submit();
				clearPicture();
			});
		}
	}
	
	function deletePicture()
	{
		if (confirm('Are you sure you want to delete this picture ?'))
		{
			var actions = this.parentElement,
				miniature = actions.previousElementSibling;
			document.body.innerHTML = '<form id="deletePicture" action="scripts/actions.php" method="POST"><input type="hidden" name="imageDelete" value="'+miniature.id+'"></form>';
        	document.getElementById('deletePicture').submit();
		}
	}

})();